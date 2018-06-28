<?php

namespace Ku\Log;

use \Ku\Log\Consts;

final class Adapter {

    protected static $_instance = null;
    protected $_driverName = Consts::DRIVER_MYSQL;
    protected $_driver     = 'files';

    public function __construct() {}
    private function __clone() {}
    private function __sleep() {}

    /**
     * @return \Ku\Log\Adapter
     */
    public static function getInstance() {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Driver Name
     *
     * @param string $driverName
     * @return \Ku\Log\Adapter
     */
    public function setDriverName($driverName) {
        $this->_driverName = (string)$driverName;

        return $this;
    }

    /**
     * Driver Name
     *
     * @return string
     */
    public function getDriverName() {
        return $this->_driverName;
    }

    /**
     * 获取当前日志驱动器
     *
     * @return \Ku\Log\DriverAbstract
     */
    public function getDriver() {
        if (!$this->_driver instanceof \Ku\Log\DriverAbstract) {
            $driveName     = '\\Ku\\Log\\Driver\\' . ucfirst(strtolower($this->_driverName));
            $this->_driver = new $driveName();
        }

        return $this->_driver;
    }

    /**
     * 写日志
     *
     * @param array|string $log
     * @param string $format
     * @param int $from
     * @return boolean
     */
    public static function push($log, $type = Consts::ADMIN_LOGIN, $from = Consts::FROM_ADMIN) {
        try {
            $adapter = self::getInstance()->getDriver();
            $adapter->setFrom($from)->setType($type)->setLog($log, $type);
            $adapter->push();
        } catch (\Exception $ex) {
            return false;
        }

        return true;
    }


    public static function Applog($log, $type = Consts::APPLICATION, $from = Consts::FROM_APPLICATION){
        try {
            array_unshift($log, date('Y-m-d H:i:s'));
            $adapter = self::getInstance()->setDriverName(Consts::DRIVER_FILES)->getDriver();
            $adapter->setFrom($from)->setType($type)->setLog($log, $type);
            $adapter->push();
        } catch (\Exception $ex) {
            return false;
        }

        return true;


    }
}
