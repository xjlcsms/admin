<?php

namespace Ku\Thumb\Png;

class Png extends \Ku\Thumb\ThumbAbstract {

    /**
     * 压缩级别
     *
     * @return int [0-9]
     */
    protected function compressionLevel(){
        $quality = $this->getDstResource()->getQuality();

        return round(abs((($quality - 100)/11.111111)));
    }

    /**
     * 处理缩略
     *
     * @return boolean
     */
    public function resized() {
        $dstResource = $this->getDstResource();
        $srcResource = $this->getSrcResource();

        $image = $srcResource->getImage();
        $dstW  = $dstResource->getWidth();
        $dstH  = $dstResource->getHeight();

        $newimage = imagecreatetruecolor($dstW, $dstH);
        $alpha    = imagecolorallocatealpha($newimage, 0, 0, 0, 127);

        imagefill($newimage, 0, 0, $alpha);

        imagecopyresampled($newimage, $image, $dstResource->getOffsetX(), $dstResource->getOffsetY(), $srcResource->getOffsetX(), $srcResource->getOffsetY(), $dstW, $dstH, $srcResource->getWidth(), $srcResource->getHeight());
        imagesavealpha($newimage, true);
        $this->setImage($newimage)->setFilename()->setSavePath();

        imagepng($newimage, $dstResource->getFullpath(), $this->compressionLevel());
        imagedestroy($newimage);

        return true;
    }

}