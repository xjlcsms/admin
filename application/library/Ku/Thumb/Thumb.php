<?php

namespace Ku\Thumb;

final class Thumb {

    /**
     * 源图像资源
     *
     * @var \Ku\Thumb\Resource\Src
     */
    protected $_srcResource = null;

    public function __construct($src = null) {
        if (!empty($src)) {
            $this->src($src);
        }
    }

    /**
     * 源图像资源
     *
     * @param string|resource $src
     * @return \Ku\Thumb\Thumb
     */
    public function src($src) {
        if (!$this->_srcResource instanceof \Ku\Thumb\Resource\Src) {
            $srcResource = \Ku\Thumb\Resource\Src::getInstance();
            (is_resource($src)) ? $srcResource->setImage($src) : $srcResource->setPath($src);
            $srcResource->parse();
            $this->_srcResource = $srcResource;
        }

        return $this;
    }

    /**
     * 源图像资源
     *
     * @return \Ku\Thumb\Resource\Src
     */
    public function getSrcResource() {
        return $this->_srcResource;
    }

    /**
     * 目标图像资源
     *
     * @param array $dst
     * @return \Ku\Thumb\Resource\Dst
     */
    public function dst(array $dst = null) {
        $dstResource = \Ku\Thumb\Resource\Dst::getInstance();

        if (!empty($dst)) {
            foreach ($dst as $key => $val) {
                $func = 'set' . ucfirst(strtolower($key));

                if (method_exists($dstResource, $func)) {
                    $dstResource->{$func}($val);
                }
            }
        }

        return $dstResource;
    }

    /**
     * 缩略执行
     *
     * @params $optimal boolean
     * @return boolean
     * @throws \Exception
     */
    public function resized($optimal = false) {
        $srcResource = $this->getSrcResource();

        if (!$srcResource instanceof \Ku\Thumb\Resource\Src) {
            throw new \Exception('Invalid Resource');
        }

        $ext    = ucfirst(\Ku\Thumb\Resource\Dst::getInstance()->getExt());
        $handle = '\\Ku\\Thumb\\' . $ext . '\\' . $ext;
     
        $handle = new $handle($this);
        if (!$handle instanceof \Ku\Thumb\ThumbAbstract) {
            throw new \Exception('Invalid Handler');
        }

        if(true === $optimal) {
            $this->optimal();
        }
        return $handle->resized();
    }

    /**
     * 自动计算裁切最佳大小(width,height)
     */
    protected function optimal() {
        $srcResource = $this->getSrcResource();
        $dstResource = $this->dst();

        $src_w = $srcResource->getWidth();
        $src_h = $srcResource->getHeight();

        if($src_w === $src_h) {
            return true;
        }

        $dst_w = (int)$dstResource->getWidth();
        $dst_h = (int)$dstResource->getHeight();
        $minbv = min(array($src_w, $src_h)); // 取最小长度作基准
        $opt_w = floor($minbv*(number_format($dst_w/$dst_h, 6)));
        $opt_h = floor($minbv*(number_format($dst_h/$dst_w, 6)));
        $opt_x = floor(($src_w - $opt_w)/2);
        $opt_y = floor(($src_h - $opt_h)/2);

        $srcResource->setWidth(min(array($opt_w, $minbv)));
        $srcResource->setHeight(min(array($opt_h, $minbv)));
        $srcResource->setOffsetX($srcResource->getOffsetX() + $opt_x);
        $srcResource->setOffsetY($srcResource->getOffsetY() + $opt_y);

        return true;
    }

}
