<?php

namespace Ku\Thumb\Resource;

final class Src {

    protected static $_instance = null;
	protected static $_count =0;
    /**
     * 目标图相关数据
     */
    protected $_image    = null;
    protected $_path     = null;
    protected $_filename = null;
    protected $_ext      = null;
    protected $_offsetX  = 0;
    protected $_offsetY  = 0;
    protected $_width    = null;
    protected $_height   = null;

    private function __construct() {}
    private function __clone() {}
    private function __sleep() {}

    /**
     * @return \Ku\Thumb\Resource\Src;
     */
    public static function getInstance() {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }

        return self::$_instance;
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
     * 图像资源
     *
     * @return resource
     */
    public function setImage($image) {
        $this->_image = $image;

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
     * @return string
     */
    public function getPath() {
        return $this->_path;
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
     * @return string
     */
    public function getExt() {
        return $this->_ext;
    }

    /**
     * 图像宽
     *
     * @param type $width
     * @return \Ku\Thumb\Resource\Src
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
     * 图像高
     *
     * @param int $height
     * @return \Ku\Thumb\Resource\Src
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
     * 分析图像相关信息
     *
     * @param resource $image
     * @return \Ku\Thumb\Resource\Dst
     */
    public function parse() {
        $this->retResource();

        if (!is_resource($this->_image)) {
            throw new \Exception('invalid image resource');
        }

        $this->retFilename();
        $this->retExt();
        $this->retSize();

        return $this;
    }

    /**
     * 图像资源
     *
     * @throws \Exception
     */
    protected function retResource() {
//         if (!is_resource($this->_image)) {
            $try = 3;

            $image = file_get_contents($this->_path);

            while (!$image && $try > 0) {
                $image = file_get_contents($this->_path);
                $try--;
                sleep(1);
            }

            $this->_image = imagecreatefromstring($image);
//         }
    }

    /**
     * 图片名称
     *
     * @throws \Exception
     */
    protected function retFilename() {
        if (!empty($this->_path)) {
            $lastPosIndex    = strrpos($this->_path, '/');
            $this->_filename = ($lastPosIndex > 0) ? substr($this->_path, $lastPosIndex + 1) : $this->_path;
        }
    }

    /**
     * 计算图像相关大小(宽,高)
     *
     * @throws \Exception
     */
    protected function retSize() {
        $this->_width  = imagesx($this->_image);
        $this->_height = imagesy($this->_image);
    }

    /**
     * 计算图像后缀
     *
     * @throws \Exception
     */
    protected function retExt() {
        $size = \getimagesize($this->_path);

        if(isset($size['mime'])) {
            $extArr = explode('/', $size['mime']);
            $ext    = strtolower(end($extArr));

            if (strpos(\Ku\Thumb\Resource\Consts::IMAGE_ALLOW_TYPE, '|' . $ext . '|') === false) {
                throw new \Exception('Invalid file type');
            }

            if($ext == 'jpeg') {
                $ext = 'jpg';
            }

            $this->_ext = $ext;
            return true;
        }

        throw new \Exception('Invalid file type');
    }

}
