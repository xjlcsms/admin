<?php

namespace Ku\Thumb\Gif;

use \Ku\Thumb\Resource\Consts as Consts;

class Gif extends \Ku\Thumb\ThumbAbstract {

    /**
     * GIF 目前仅支持生成静态
     *
     * @return string
     */
    public function resized() {
        return $this->getDstResource()->getGifResizedType() === Consts::GIF_DYNAMIC ?  $this->dynamicResized() : $this->staticResized();
    }

    /**
     * 静态解析
     *
     * @return boolean
     */
    protected function staticResized() {
        $dstResource = $this->getDstResource();
        $srcResource = $this->getSrcResource();

        $image = $srcResource->getImage();
        $dstW  = $dstResource->getWidth();
        $dstH  = $dstResource->getHeight();

        $newimage = imagecreatetruecolor($dstW, $dstH);
        $alpha    = imagecolorallocatealpha($newimage, 0, 0, 0, 127);

        imagefill($newimage, 0, 0, $alpha);

        imagecopyresampled($newimage, $image, $dstResource->getOffsetX(), $dstResource->getOffsetY(), $srcResource->getOffsetX(), $srcResource->getOffsetY(), $dstW, $dstH,  $srcResource->getWidth(), $srcResource->getHeight());
        imagesavealpha($newimage, true);

        $this->setImage($newimage)->setFilename()->setSavePath();

        imagegif($newimage, $dstResource->getFullpath());
        imagedestroy($newimage);

        return true;
    }

    /**
     * 动态解析
     *
     * @return string
     * @todo 未完成
     */
    protected function dynamicResized() {
        $thumbGif = new \Ku\Thumb\Gif\DynamicThumb($this);

        $r = $thumbGif->resized();

        header("Content-type: image/gif");
        imagegif(imagecreatefromstring($r));
    }

}
