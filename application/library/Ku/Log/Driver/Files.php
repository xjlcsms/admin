<?php

namespace Ku\Log\Driver;

final class Files extends \Ku\Log\DriverAbstract {

    protected $_file = null;

    protected function init() {
        if($this->_file === null || !file_exists($this->_file)) {
            $path = APPLICATION_PATH . DS .'data'. DS . 'log' . DS;

            if(!file_exists($path)) {
                mkdir($path, '0755', true);
            }

            $this->_file = sprintf(\Ku\Log\Consts::FILESNAME, $path, $this->getFrom(), $this->getDay());
        }
    }

    /**
     * 将日志写入对应文件末尾
     *
     * @param string $log
     * @return boolean
     */
    public function push() {
        file_put_contents($this->_file, $this->getLog() . PHP_EOL, FILE_APPEND);

        return true;
    }

    /**
     * 读取日志
     *
     * @return string
     */
    public function read() {
        $ret    = array();
        $offset = $this->getOffset() + 1;
        $limit  = $this->getLimit();
        $end    = $limit + $offset;
        $handle = fopen($this->_file, 'r');
        $lpoint = 0; // 虚构 行指针

        while (($char = fgets($handle)) !== false) {
            $lpoint++;

            if($limit > 0 && $end < $lpoint) {
                break;
            }

            if($lpoint < $offset) {
                continue;
            }

            $ret[] = \Ku\Log\Data::dencode($char);
        }

        return $ret;
    }
}

