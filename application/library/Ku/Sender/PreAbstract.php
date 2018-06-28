<?php

namespace Ku\Sender;

class PreAbstract {

    protected $_errval = array();

    final public function __construct() {}
    private function __clone() {}
    private function __sleep() {}

    /**
     * 错误信息
     *
     * @param int $code
     * @param string $val
     * @return \Ku\Sender\SenderAbstract
     */
    protected function setErrval($code, $val) {
        $this->_errval[$code] = (string)$val;

        return $this;
    }

    /**
     * 错误信息
     *
     * @return string
     */
    public function getErrval() {
        return $this->_errval;
    }

    /**
     * @return \Yaf\Request
     */
    protected function getRequest() {
        return \Yaf\Dispatcher::getInstance()->getRequest();
    }

}
