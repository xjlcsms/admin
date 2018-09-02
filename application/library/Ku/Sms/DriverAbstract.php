<?php

namespace Ku\Sms;

abstract class DriverAbstract {
    protected $_mobiles = null;
    protected $_content = null;
    protected $_sender = '';
    protected $_error = '';
    protected $_accesskey = '';
    protected $_secret = '';
    protected $_sign = '';
    protected $_templateId = '';
    protected $_data = '';
    protected $_scheduleSendTime = '';



    final public function __construct() {
        $conf = \Yaf\Registry::get('config');
        $this->_config = $conf;
    }

    private function __clone() {}
    private function __sleep() {}
    
    public function setMobiles($mobiles){
        $this->_mobiles = $mobiles;
    }
    
    public function getMobiles(){
       return $this->_mobiles;
    }
    
    public function setContent($content){
        $this->_content = $content;
    }
    
    public function getContent(){
        return $this->_content;
    }
    
    public function getError(){
        return $this->_error;
    }
    public function setError($error){
        $this->_error = $error;
    }

    public function setAccesskey($accesskey){
        $this->_accesskey = $accesskey;
    }
    public function getAccesskey(){
        return $this->_accesskey;
    }

    public function setSecret($secret){
        $this->_secret = $secret;
    }
    public function getSecret(){
        return $this->_secret;
    }
    public function setSign($sign){
        $this->_sign = $sign;
    }
    public function getSign(){
        return $this->_sign;
    }
    public function setTemplateId($templateId){
        $this->_templateId = $templateId;
    }
    public function getTemplateId(){
        return $this->_templateId;
    }
    public function setData($data){
        $this->_data = $data;
    }
    public function getData(){
        return $this->_data;
    }
    public function setScheduleSendTime($scheduleSendTime){
        $this->_scheduleSendTime = $scheduleSendTime;
    }
    public function getScheduleSendTime(){
        return $this->_scheduleSendTime;
    }
    abstract public function send();

}

