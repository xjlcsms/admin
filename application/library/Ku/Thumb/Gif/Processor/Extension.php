<?php

namespace Ku\Thumb\Gif\Processor;

use Ku\Thumb\Gif\Mark as Mark;

class Extension extends \Ku\Thumb\Gif\Processor\ProcessorAbstract {

    /**
     * 相关解析器
     *
     * @var array
     */
    protected $parser = array(
        Mark::EXTENSION_GRAPHIC     => 'Graphic',
        Mark::EXTENSION_COMMENT     => 'Comment',
        Mark::EXTENSION_APPLICATION => 'Application',
        Mark::EXTENSION_PLAINTEXT   => 'PlainText'
    );

    protected $_std = null;

    /**
     * 图形控制扩展块
     *
     * @return \stdClass
     */
    protected function parseGraphic() {
        $graphic  = $this->_std;
        $resource = $this->resource;

        $graphic->introducer = Mark::EXTENSION_GRAPHIC;
        $graphic->block_size = $resource->readUnsignedChar();

        $gifBitsResource             = new \Ku\Thumb\Gif\Buffer\Bits($resource->read(1));
        $graphic->input              = $this->std();
        $graphic->input->reserved    = $gifBitsResource->read(3);
        $graphic->input->disposal    = $gifBitsResource->read(3);
        $graphic->input->userInput   = $gifBitsResource->read();
        $graphic->input->transparent = $gifBitsResource->read();

        $graphic->delay_time       = $resource->readUnsignedShort();
        $graphic->transparentIndex = $resource->readUnsignedChar();

        return $graphic;
    }

    /**
     * 注释块
     *
     * @return \stdClass
     */
    protected function parseComment() {
        $comment = $this->_std;

        $comment->introducer  = Mark::EXTENSION_COMMENT;
        $comment->commentData = $this->readParseData();

        return $comment;
    }

    /**
     * 应用程序扩展块
     *
     * @return \stdClass
     */
    protected function parseApplication() {
        $application = $this->_std;
        $resource    = $this->resource;

        $application->introducer         = Mark::EXTENSION_APPLICATION;
        $application->block_size         = $resource->readUnsignedChar();
        $application->identifier         = $resource->read(8);
        $application->authenticationCode = $resource->read(3);
        $application->applicationData    = $this->readParseData();

        return $application;
    }

    /**
     * 图形文本扩展块
     *
     * @return \stdClass
     */
    protected function parsePlainText() {
        $plainText = $this->_std;
        $resource  = $this->resource;

        $plainText->introducer    = Mark::EXTENSION_PLAINTEXT;
        $plainText->block_size    = $resource->readUnsignedChar();
        $plainText->positionL     = $resource->readUnsignedShort();
        $plainText->positionT     = $resource->readUnsignedShort();
        $plainText->textW         = $resource->readUnsignedShort();
        $plainText->textH         = $resource->readUnsignedShort();
        $plainText->charW         = $resource->readUnsignedChar();
        $plainText->charH         = $resource->readUnsignedChar();
        $plainText->foreground    = $resource->readUnsignedChar();
        $plainText->blackground   = $resource->readUnsignedChar();
        $plainText->plainTextData = $this->readParseData();

        return $plainText;
    }

    public function read() {
        $resource     = $this->resource;
        $introducer   = $this->createExtensionStd();
        $controlLabel = $resource->readUnsignedChar();

        if (!isset($this->parser[$controlLabel]))
            throw new \Exception("Invalid extension introducer 0x" . strtoupper(dechex($controlLabel)));

        $parserName = strtolower($this->parser[$controlLabel]);
        $parserStd  = 'parse' . $this->parser[$controlLabel];
        $this->_std = $this->std();

        $this->_std->identifier  = Mark::EXTENSION;
        $introducer->$parserName = $this->$parserStd();

        if ($introducer->$parserName instanceof \stdClass) {
            $introducer->$parserName->terminator = Mark::TERMINATOR;
        }

        return $introducer;
    }

    /**
     * 复制 \Ku\Thumb\Gif\Std\Extension
     *
     * @staticvar null $_std
     * @return \Ku\Thumb\Gif\Std\Extension
     */
    public function createExtensionStd() {
        static $_std = null;

        if ($_std === null)
            $_std = new \Ku\Thumb\Gif\Std\Extension();

        return (clone $_std);
    }

}
