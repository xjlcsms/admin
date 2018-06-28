<?php

namespace Ku\Thumb\Gif\Structures;
use Ku\Thumb\Gif\Mark as Mark;

class DataStream extends \Ku\Thumb\Gif\Structures\StructuresAbstract {

    /**
     * 处理器
     *
     * @var array
     */
    protected $handler = array(
        Mark::EXTENSION => 'Extension',
        Mark::SEPARTOR  => 'Frame'
    );

    protected $_screen = null;
    protected $_blocks = null;

    /**
     * 逻辑屏幕标识符
     *
     * @return \stdClass
     */
    public function getScreen(){
        if($this->_screen === null){
            $screen   = new \Ku\Thumb\Gif\Std\Screen();
            $resource = $this->getResource();

            $screen->srcW = $resource->readUnsignedShort(); // 逻辑屏幕宽度
            $screen->srcH = $resource->readUnsignedShort(); // 逻辑屏幕高度

            $gifBitsResource  = new \Ku\Thumb\Gif\Buffer\Bits($resource->read(1));
            $screen->globalCt = $this->std();
            $screen->globalCt->flag       = $gifBitsResource->read(1); // 全局颜色列表标志
            $screen->globalCt->resolution = $gifBitsResource->read(3); // 颜色深度
            $screen->globalCt->sort       = $gifBitsResource->read(1); // 分类标志
            $screen->globalCt->pixelSize  = $gifBitsResource->read(3); // 全局颜色列表大小

            $screen->blackground  = $resource->readUnsignedChar(); // 背景颜色, 在全局颜色列表中的索引，如果没有全局颜色列表，该值没有意义
            $screen->aspect_radio = $resource->readUnsignedChar(); // 像素宽高比

            // 全局颜色列表
            $screen->globalCt->table = array();

            if($screen->globalCt->flag){
                for($i = 0, $n = pow(2, $screen->globalCt->pixelSize + 1) ; $i < $n; $i++){
                    $screen->globalCt->table[] = unpack("Cr/Cg/Cb", $resource->read(3));
                }
            }

            $this->_screen = $screen;
        }

        return $this->_screen;
    }

    /**
     * 图像标识符块
     */
    public function getBlocks(){
        if($this->_blocks === null){
            $blocks   = array();
            $resource = $this->getResource();

            while (($identifier = $resource->readUnsignedChar()) !== Mark::TRAILER){
                if($identifier === Mark::TERMINATOR)
                    continue;

                $blocks[] = $this->getProcessor($identifier)->read();
            }

            $this->_blocks = $blocks;
        }

        return $this->_blocks;
    }

    public function toString() {
    }

    /**
     * 处理器
     *
     * @staticvar array $_handle
     * @param string $identifier
     * @return \Ku\Thumb\Gif\Processor\ProcessorAbstract
     * @throws \Exception
     */
    protected function getProcessor($identifier){
        static $_processor = array();

        if(!isset($_processor[$identifier]) && isset($this->handler[$identifier])){
            $handlerName = '\\Ku\\Thumb\\Gif\\Processor\\' . $this->handler[$identifier];
            $_processor[$identifier] = new $handlerName($this->getResource(), $this->std());
        }

        if(!isset($_processor[$identifier]) || !$_processor[$identifier] instanceof \Ku\Thumb\Gif\Processor\ProcessorAbstract)
            throw new \Exception('Invalid identifier 0x' . strtoupper(dechex($identifier)));

        return $_processor[$identifier];
    }

}