<?php

namespace Ku\Thumb\Gif\Processor;

class Frame extends \Ku\Thumb\Gif\Processor\ProcessorAbstract {

    public function read() {
        $frame   = $this->createImageStd();
        $resource = $this->resource;

        $frame->offestX = $resource->readUnsignedShort();
        $frame->offestY = $resource->readUnsignedShort();
        $frame->imageW  = $resource->readUnsignedShort();
        $frame->imageH  = $resource->readUnsignedShort();

        $gifBitsResource  = new \Ku\Thumb\Gif\Buffer\Bits($resource->read(1));
        $frame->localCt = $this->std();
        $frame->localCt->flag       = $gifBitsResource->read(); // 局部颜色列表标志
        $frame->localCt->interlace  = $gifBitsResource->read(); // 交织标志
        $frame->localCt->sort       = $gifBitsResource->read(); // 分类标志
        $frame->localCt->reserved   = $gifBitsResource->read(2); // 保留，必须初始化为0
        $frame->localCt->size       = $gifBitsResource->read(3); // 局部颜色列表大小

        // 局部颜色列表
        $frame->localCt->table = array();

        if($frame->localCt->flag){
            for($i = 0, $n = pow(2, $frame->globalCt->size + 1) ; $i < $n; $i++){
                $frame->localCt->table[] = unpack("Cr/Cg/Cb", $resource->read(3));
            }
        }

        // 基于颜色列表的图像数据
        $frame->tbiData = $this->std();
        $frame->tbiData->LZW_Minimum = $resource->readUnsignedChar();
        $frame->tbiData->imageData  = $this->readParseData();

        return $frame;
    }

    /**
     * 复制 \Ku\Thumb\Gif\Std\Frame
     *
     * @staticvar null $_std
     * @return \Ku\Thumb\Gif\Std\Frame
     */
    public function createImageStd(){
        static $_std = null;

        if($_std === null)
            $_std = new \Ku\Thumb\Gif\Std\Frame();

        return (clone $_std);
    }
}

