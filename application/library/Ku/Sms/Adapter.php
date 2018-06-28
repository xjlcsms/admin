<?php

namespace Ku\Sms;

final class Adapter {

    protected $_initSmser = array(
        'runyuan' => '润圆',
        'xuanwu' => '玄武',
    );

    protected $_smser = null;
    protected $_driver     = null;

    public function __construct($smser = null) {
        if (isset($this->_initSmser[$smser])) {
            $this->setSmser($smser);
        }
    }

    /**
     * init smser
     *
     * @return array
     */
    public function getSmser() {
        return $this->_smser;
    }

    /**
     * smser
     *
     * @param string $authorizer
     * @return \Ku\Sms\Adapter
     */
    public function setSmser($smser) {
        $this->_smser = (string)$smser;

        return $this;
    }

    /**
     * sms Driver
     *
     * @return \Ku\Sms\DriverAbstract
     * @throws \Exception
     */
    public function getDriver() {
        if (!$this->_driver instanceof \Ku\Sms\DriverAbstract) {
            if (!isset($this->_initSmser[$this->_smser])) {
                throw new \Exception('invalid smser');
            }

            $driverName    = '\\Ku\\Sms\Driver\\' . ucfirst(strtolower($this->_smser));
            $this->_driver = new $driverName();
        }

        if (!$this->_driver instanceof \Ku\Sms\DriverAbstract) {
            throw new \Exception('invalid Driver');
        }

        return $this->_driver;
    }

}
