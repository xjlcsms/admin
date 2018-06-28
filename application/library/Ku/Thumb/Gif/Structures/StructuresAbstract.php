<?php

namespace Ku\Thumb\Gif\Structures;

abstract class StructuresAbstract {

    protected $gifThumb = null;

    public function __construct(\Ku\Thumb\Gif\DynamicThumb $gifThumb) {
        $this->gifThumb = $gifThumb;
    }

    /**
     * 图像资源实例
     *
     * @return string
     */
    protected function getResource(){
        return $this->gifThumb->getResource();
    }

    /**
     * 复制PHP基类
     *
     * @staticvar null $_std
     * @return stdClass
     */
    protected function std(){
        static $_std = null;

        if($_std === null)
            $_std = new \stdClass();

        return (clone $_std);
    }

    abstract public function toString();
}
