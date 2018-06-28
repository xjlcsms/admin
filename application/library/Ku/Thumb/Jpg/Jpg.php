<?php

namespace Ku\Thumb\Jpg;

class Jpg extends \Ku\Thumb\ThumbAbstract {

    /**
     * 处理缩略
     *
     * @return  boolean
     */
    public function resized() {
        $dstResource = $this->getDstResource();
        $srcResource = $this->getSrcResource();

        $image = $srcResource->getImage();
        $dstW  = $dstResource->getWidth();
        $dstH  = $dstResource->getHeight();

        $newimage = imagecreatetruecolor($dstW, $dstH);

        imagecopyresampled($newimage, $image, $dstResource->getOffsetX(), $dstResource->getOffsetY(), $srcResource->getOffsetX(), $srcResource->getOffsetY(), $dstW, $dstH, $srcResource->getWidth(), $srcResource->getHeight());

        $this->setImage($newimage)->setFilename()->setSavePath();

        imagejpeg($newimage, $dstResource->getFullpath(), $dstResource->getQuality());
        imagedestroy($newimage);

        return true;
    }

}

