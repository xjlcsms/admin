<?php

namespace Base\Model;

trait InstanceModel {

    protected static $_instance = null;

    private function __sleep(){}
    private function __clone(){}

    /**
     * 单例
     *
     * @return \Mapper\Base\AbstractModel
     */
    public static function getInstance(){
        if (!self::$_instance instanceof self){
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}

