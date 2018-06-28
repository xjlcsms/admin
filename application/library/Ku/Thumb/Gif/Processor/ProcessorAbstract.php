<?php

namespace Ku\Thumb\Gif\Processor;
use Ku\Thumb\Gif\Mark as Mark;

abstract class ProcessorAbstract {

    protected $resource = null;
    protected $saveHandler = null;

    public function __construct(\Ku\Thumb\Gif\Buffer\Char $resource, \stdClass $saveHandler) {
        $this->resource    = $resource;
        $this->saveHandler = $saveHandler;
    }

    /**
     * 复制PHP基类
     *
     * @return \stdClass
     */
    protected function std(){
        return clone $this->saveHandler;
    }

    /**
     * 相关数据
     */
    protected function readParseData(){
        $data     = array();
        $resource = $this->resource;

        while (($size = $resource->readUnsignedChar()) !== Mark::TERMINATOR){
            $data[] = $resource->read($size);
        }

        return $data;
    }

    abstract public function read();
}
