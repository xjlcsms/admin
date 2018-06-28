<?php

namespace Ku\Log\Driver;

final class Mysql extends \Ku\Log\DriverAbstract {

    /**
     * 写日志
     *
     * @param string $log
     * @return boolean
     */
    public function push() {
        $model = new \LogModel();
        $model->setTime(time());
        $model->setType($this->getType());
        $model->setFrom($this->getFrom());
        $model->setLog($this->getLog());
        \Mapper\LogModel::getInstance()->insert($model);

        return true;
    }

    /**
     * 读取日志
     *
     * @return string
     */
    public function read() {}
}

