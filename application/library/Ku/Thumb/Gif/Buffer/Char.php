<?php

namespace Ku\Thumb\Gif\Buffer;

class Char {

    /**
     * 资源数据解析后的缓冲器
     *
     * @var array
     */
    private $buffer   = null;

    /**
     * 数据指针
     *
     * @var int
     */
    private $point = 0;

    /**
     * 数据块大小
     *
     * @var int
     */
    private $size = 0;

    public function __construct($resource) {
        $this->buffer = $resource;
        $this->size   = mb_strlen($resource);
    }

    /**
     * 读取一定长度数据
     *
     * @param int $length
     * @return string $str
     */
    public function read($length){
        $str = substr($this->buffer, $this->point, (int)$length);
        $this->next($length);

        return $str;
    }

    /**
     *
     * pack/unpack > v
     *
     * unsigned short (always 16 bit, little endian byte order)
     * http://cn2.php.net/manual/zh/function.pack.php
     *
     * @return int
     */
    public function readUnsignedShort(){
        return $this->readStr(2);
    }

    /**
     * 0 到 255
     *
     * pack/unpack > C
     *
     * unsigned char
     * http://cn2.php.net/manual/zh/function.pack.php
     *
     * @return int
     */
    public function readUnsignedChar() {
        return $this->readStr(1, "C");
    }

    /**
     * 取得指定长度数据
     *
     * @param int $length
     * @param string $format
     * @return int
     */
    protected function readStr($length, $format = 'v'){
        $items  = array();

        for($i = 0; $i < $length; $i++){
            $items[] = $this->buffer[$this->point + $i];
        }

        $this->next($length);

        $data = unpack($format, implode($items));

        return $data[1];
    }

    /**
     * 是否数据结束
     *
     * @return boolean
     */
    protected function end(){
        return (bool)($this->point <= $this->size);
    }

    /**
     * 移动指针
     *
     * @param int $length
     */
    protected function next($length = 1){
        $this->point += (int)$length;
    }

    /**
     * 当前指针位置
     *
     * @return int
     */
    public function current(){
        return $this->point;
    }

}

