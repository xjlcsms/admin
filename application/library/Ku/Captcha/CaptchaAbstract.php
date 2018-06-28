<?php

namespace Ku\Captcha;

abstract class CaptchaAbstract {

    protected $_code = array();
    protected $_result = null;
    protected $_append = null;

    final public function __construct() {}

    /**
     * 取得验证码
     *
     * @return array
     */
    public function getCode(){
        return (array)$this->_code;
    }

    /**
     * 取得验证码结果
     *
     * @return string
     */
    public function getResult(){
        return (string)$this->_result;
    }

    /**
     * 获取附加信息
     *
     * @return string
     */
    public function getAppendMsg(){
        return $this->_append;
    }

    /**
     * Captcha相关属性
     *
     * @return \Ku\Captcha\Captcha
     */
    protected function getCaptcha() {
        return \Ku\Captcha\Captcha::getInstance();
    }

    /**
     * @return \Ku\Captcha\CaptchaAbstract
     */
    abstract public function exec();
}
