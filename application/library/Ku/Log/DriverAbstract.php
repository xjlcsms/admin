<?php

namespace Ku\Log;

use \Ku\Log\Consts;

abstract class DriverAbstract {

    private $_log    = null;
    private $_day    = null;
    private $_limit  = 0;
    private $_offset = 0;

    /**
     * 日志来源(相应信息查看类文件 \Ku\Log\Consts)
     *
     * @var int
     */
    private $_from = Consts::FROM_USER;

    private $_type = Consts::ADMIN_LOGIN;

    final public function __construct() {
        $this->init();
    }

    private function __clone() {}
    private function __sleep() {}

    protected function init() {
        return true;
    }

    /**
     * 日志写前置
     *
     * @param string $log
     * @return \Ku\Log\DriverAbstract
     */
    public function setLog(array $log, $type = Consts::GENERAL) {
        $this->_log = vsprintf(Consts::FORMAT[$type], $log);
        return $this;
    }

    /**
     * 日期
     *
     * @param string $day
     * @return \Ku\Log\DriverAbstract
     */
    public function setDay($day) {
        $this->_day = (string)$day;

        return $this;
    }

    /**
     * Limit
     *
     * @param int $limit
     * @return \Ku\Log\DriverAbstract
     */
    public function setLimit($limit) {
        $this->_limit = (int)$limit - 1;

        return $this;
    }

    /**
     * Offset
     *
     * @param int $offset
     * @return \Ku\Log\DriverAbstract
     */
    public function setOffset($offset) {
        $this->_offset = (int)$offset;

        return $this;
    }

    /**
     * From
     *
     * @param int $from
     * @return \Ku\Log\DriverAbstract
     */
    public function setFrom($from) {
        $from = ($from > 0x00 && $from < 0x0A) ? $from : Consts::FROM_USER;
        $this->_from = (int)$from;

        return $this;
    }



    public function setType($type){
        $type = ($type > 0x00 && $type < 0x0A) ? $from : Consts::ADMIN_LOGIN;
        $this->_type = (int)$type;

        return $this;

    }

    /**
     * 日志写前置
     *
     * @return string
     */
    protected function getLog() {
        return $this->_log;
    }

    /**
     * 日期
     *
     * @return string
     */
    protected function getDay() {
        return ($this->_day === null ? date('Ymd') : $this->_day);
    }

    /**
     * Limit
     *
     * @return int
     */
    protected function getLimit() {
        return $this->_limit;
    }

    /**
     * Offset
     *
     * @return int
     */
    protected function getOffset() {
        return $this->_offset;
    }

    /**
     * From
     *
     * @return int
     */
    protected function getFrom() {
        return $this->_from;
    }

    protected function getType(){
        return $this->_type;
    }

    abstract public function push();
    abstract public function read();
}

