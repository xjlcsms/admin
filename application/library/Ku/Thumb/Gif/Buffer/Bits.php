<?php

namespace Ku\Thumb\Gif\Buffer;

class Bits {

    const RIGHE = 1;
    const LEFT  = 0;

    /**
     * 资源数据解析后的缓冲器
     *
     * @var array
     */
    private $buffer = array();

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

    public function __construct($resource, $direc = self::LEFT) {
        $this->buffer = $this->decbin($resource, $direc);
        $this->size   = strlen($this->buffer);
    }

    /**
     * 读取一定长度数据
     *
     * @param int $length
     * @return string $str
     */
    public function read($length = 1){
        $str = substr($this->buffer, $this->point, (int)$length);
        $this->next($length);

        return bindec($str);
    }

    /**
     * 转化
     *
     * @param string $resource
     * @return string
     */
    protected function decbin($resource, $direc){
        $item = array();
        $str  = str_split($resource, 1);
        $len  = count($str);

        if($direc === self::LEFT){
            for($i = 0; $i < $len; $i++){
                $item[] = decbin(ord($str[$i]));
            }
        }else{
            for($i = 0; $i < $len; $i++){
                $item[] = strrev(decbin(ord($str[$i])));
            }
        }

        return implode($item);
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