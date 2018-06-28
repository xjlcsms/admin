<?php

namespace Ku\Thumb\Gif;

class DynamicThumb {

    public $header     = null;
    public $dataStream = null;

    /**
     * 实例
     * @var Object
     */
    protected $resource = null;
    protected $gif      = null;

    public function __construct(\Ku\Thumb\Gif\Gif $gif) {
        $this->gif   = $gif;

        $this->header     = new \Ku\Thumb\Gif\Structures\Header($this);
        $this->dataStream = new \Ku\Thumb\Gif\Structures\DataStream($this);

        if(strtolower($this->header->readSignature()) !== 'gif')
            throw new \Exception("Invalid GIF image resource");

        $this->header->readVersion();
    }

    /**
     * 执行缩略处理
     *
     * @return
     * @throws \Exception
     */
    public function resized(){
        $thumb = new \stdClass();

        $thumb->header = $this->header;
        $thumb->screen = $this->dataStream->getScreen();
        $thumb->blocks = array();

        $radioW = $this->getThumb()->getW()/$thumb->screen->srcW;
        $radioH = $this->getThumb()->getH()/$thumb->screen->srcH;

        $thumb->screen->setRadioW($radioW);
        $thumb->screen->setRadioH($radioH);

        $blocks        = $this->dataStream->getBlocks();
        $globalCtTable = $thumb->screen->globalCt->table;

        if(!$blocks)
            throw new \Exception("Invalid image");

        foreach ($blocks as $block){
            if($block instanceof \Ku\Thumb\Gif\Std\Frame){
                $dstW = round($block->imageW * $radioW);
                $dstH = round($block->imageH * $radioH);

                $src  = $block->getSrcImage($globalCtTable);
                $dst  = imagecreatetruecolor($dstW, $dstH);
                imagecopyresized($dst, $src, 0, 0, 0, 0, $dstW, $dstH, $block->imageW, $block->imageH);


            }elseif($block instanceof \Ku\Thumb\Gif\Std\Extension){
                $thumb->blocks[] = $block->toString();
            }
        }

        return implode(array(
            pack("v", $thumb->header->toString()),
            $thumb->screen->toString(),
            implode(pack("v", 0x00), $thumb->blocks),
            pack("v", 0x3B)
        ));
    }

    /**
     * GIF 入口基础实例
     *
     * @return \Ku\Thumb\Gif\Gif
     */
    public function getGif(){
        return $this->gif;
    }

    /**
     * 基础缩略实例
     *
     * @return \Ku\Thumb\Thumb
     */
    public function getThumb(){
        return $this->gif->getThumb();
    }

    /**
     * 图像资源实例
     *
     * @return string
     */
    public function getResource(){
        if(!$this->resource instanceof \Ku\Thumb\Gif\Buffer\Char)
            $this->resource = new \Ku\Thumb\Gif\Buffer\Char($this->getThumb()->getResource());

        return $this->resource;
    }
}

