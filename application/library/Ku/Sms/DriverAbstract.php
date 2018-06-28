<?php

namespace Ku\Sms;

abstract class DriverAbstract {
    protected $_phones = null;
    protected $_msg = null;
    protected $_config = null;
    protected $_sender = '';
    protected $_error = '';



    final public function __construct() {
        $conf = \Yaf\Registry::get('config');
        $this->_config = $conf;
    }

    private function __clone() {}
    private function __sleep() {}
    
    public function setPhones($phones){
        $this->_phones = $phones;
    }
    
    public function getPhones(){
       return $this->_phones;
    }
    
    public function setMsg($msg){
        $this->_msg = $msg;
    }
    
    public function getMsg(){
        return $this->_msg;
    }
    /**
     * 
     * @return \Yaf\
     */
    public function getConfig(){
        return $this->_config;
    }
    
    public function getError(){
        return $this->_error;
    }
    public function setError($error){
        $this->_error = $error;
    }

    abstract public function send();
}

