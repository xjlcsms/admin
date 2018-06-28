<?php

namespace Ku\Thumb\Resource;

final class Dst {

    protected static $_instance = null;

    /**
     * 目标图相关数据
     */
    protected $_image    = null;
    protected $_path     = null;
    protected $_filename = null;
    protected $_ext      = null;
    protected $_width    = 1;
    protected $_height   = 1;
    protected $_offsetX  = 0;
    protected $_offsetY  = 0;
    protected $_fullpath = null;
    /**
     * 压缩质量, 默认 100
     *
     * @var int [1-100]
     */
    protected $_quality = 100;

    /**
     * GIF图像压缩方式
     *
     * @var int
     */
    protected $_gifResizedType = \Ku\Thumb\Resource\Consts::GIF_STATIC;

    private function __construct() {}
    private function __clone() {}
    private function __sleep() {}

    /**
     * @return \Ku\Thumb\Resource\Dst;
     */
    public static function getInstance() {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * 重置相关数据
     */
    public function reset() {
        $this->_image    = null;
        $this->_ext      = null;
        $this->_filename = null;
        $this->_fullpath = null;
        $this->_height   = 1;
        $this->_width    = 1;
        $this->_quality  = 80;
        $this->_path     = null;
    }

    /**
     * 图像资源
     *
     * @param resource $image
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setImage($image) {
        if (is_resource($image)) {
            $this->_image = $image;
        }

        return $this;
    }

    /**
     * 图像资源
     *
     * @return resource
     */
    public function getImage() {
        return $this->_image;
    }

    /**
     * 路径
     *
     * @param string $path
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setPath($path) {
        $this->_path = (string)$path;

        return $this;
    }

    /**
     * 路径
     *
     * @return string
     */
    public function getPath() {
        return $this->_path;
    }

    /**
     * 最后完整路径(path+filename+ext)
     *
     * @param string $fullpath
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setFullpath($fullpath) {
        $this->_fullpath = (string)$fullpath;

        return $this;
    }

    /**
     * 最后完整路径(path+filename+ext)
     *
     * @return string
     */
    public function getFullpath() {
        return $this->_fullpath  . '.' . $this->_ext;
    }

    /**
     * 最后完整文件名(filename+ext)
     *
     * @return string
     */
    public function getFullFilename() {
        return $this->_filename . '.' . $this->_ext;
    }

    /**
     * 文件名
     *
     * @param string $filename
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setFilename($filename) {
        $this->_filename = (string)$filename;

        return $this;
    }

    /**
     * 文件名
     *
     * @return string
     */
    public function getFilename() {
        return $this->_filename;
    }

    /**
     * 文件名扩展名
     *
     * @param string $ext
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setExt($ext) {
        $ext = strtolower($ext);

        if (strpos(\Ku\Thumb\Resource\Consts::IMAGE_ALLOW_TYPE, '|' . $ext . '|') !== false) {
            $this->_ext = (string)$ext;
        }

        return $this;
    }

    /**
     * 文件名扩展名
     *
     * @return string
     */
    public function getExt() {
        return $this->_ext;
    }

    /**
     * 图像宽
     *
     * @param int $width
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setWidth($width) {
        $this->_width = (int)$width;

        return $this;
    }

    /**
     * 图像宽
     *
     * @return int
     */
    public function getWidth() {
        return $this->_width;
    }

    /**
     * x 轴左坐标
     *
     * @param int $offsetX
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setOffsetX($offsetX) {
        $this->_offsetX = (int)$offsetX;

        return $this;
    }

    /**
     * x 轴左坐标
     *
     * @return int
     */
    public function getOffsetX() {
        return $this->_offsetX;
    }

    /**
     * y 轴左坐标
     *
     * @param int $offsetY
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setOffsetY($offsetY) {
        $this->_offsetY = (int)$offsetY;

        return $this;
    }

    /**
     * y 轴左坐标
     *
     * @return int
     */
    public function getOffsetY() {
        return $this->_offsetY;
    }

    /**
     * 图像高
     *
     * @param int $height
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setHeight($height) {
        $this->_height = (int)$height;

        return $this;
    }

    /**
     * 图像高
     *
     * @return int
     */
    public function getHeight() {
        return $this->_height;
    }

    /**
     * 图像压缩质量
     *
     * @param int $quality
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setQuality($quality) {
        $this->_quality = (int)$quality;

        return $this;
    }

    /**
     * 图像压缩质量
     *
     * @return int
     */
    public function getQuality() {
        return $this->_quality;
    }

    /**
     * GIF图像压缩方式
     *
     * @param int $gifResizedType
     * @return \Ku\Thumb\Resource\Dst
     */
    public function setGifResizedType($gifResizedType) {
        $this->_gifResizedType = (int)$gifResizedType;

        return $this;
    }

    /**
     * GIF图像压缩方式
     *
     * @return int
     */
    public function getGifResizedType() {
        return $this->_gifResizedType;
    }
}
