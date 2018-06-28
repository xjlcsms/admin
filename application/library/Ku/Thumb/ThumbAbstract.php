<?php

namespace Ku\Thumb;

abstract class ThumbAbstract {

    public function __construct() {}

    /**
     * 目标资源
     *
     * @return \Ku\Thumb\Resource\Dst
     */
    protected function getDstResource() {
        return \Ku\Thumb\Resource\Dst::getInstance();
    }

    /**
     * 源图像资源
     *
     * @return type
     */
    protected function getSrcResource() {
        return \Ku\Thumb\Resource\Src::getInstance();
    }

    /**
     * 目标图像资源
     *
     * @param resource $image
     * @return \Ku\Thumb\ThumbAbstract
     */
    protected function setImage($image) {
        $this->getDstResource()->setImage($image);

        return $this;
    }

    /**
     * 目标图像名称
     *
     * @param string $filename
     * @return \Ku\Thumb\ThumbAbstract
     */
    protected function setFilename($filename = null) {
        $dstResource = $this->getDstResource();

        if (empty($filename)) {
            $filename = $dstResource->getFilename();

            if (empty($filename)) {
                $filename = md5($dstResource->getExt() . 'Thmub:' . uniqid(mt_rand(100000, 999999)) . ':Time:' . time());
            }
        }

        $dstResource->setFilename($filename);

        return $this;
    }

    /**
     * 保存路径
     *
     * @return \Ku\Thumb\ThumbAbstract
     */
    protected function setSavePath() {
        $dstResource = $this->getDstResource();
        $path        = trim(rtrim($dstResource->getPath(), '/')) . '/';
        $filename    = $dstResource->getFilename();

        $dstResource->setFullPath($filename ? $path . $filename : null);

        return $this;
    }

    /**
     * 处理缩略抽象方法
     *
     * @return  self::thumb
     */
    abstract public function resized();
}
