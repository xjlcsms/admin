<?php

namespace Ku\Log\Driver;

final class Redis extends \Ku\Log\DriverAbstract {

    private $_redis    = null;
    private $_salveKey = null;

    protected function init() {
        if(!$this->_redis instanceof \Redis) {
            $this->initRedis();
            $this->_salveKey = sprintf(\Ku\Log\Consts::REDISKEY, $this->getFrom(), $this->getDay());
        }
    }

    /**
     * 将日志写入对应key中
     *
     * @param string $log
     * @return boolean
     */
    public function push() {
        $this->_redis->rpush($this->_salveKey, \Ku\Log\Data::encode($this->getLog()));

        return true;
    }

    /**
     * 读取日志
     *
     * @return string
     */
    public function read() {
        $redis  = $this->_redis;
        $limit  = $this->getLimit();
        $offset = $this->getOffset();
        $lkey   = $this->_salveKey;

        if($limit === 0) {
            $limit = $redis->llen($lkey);
        }

        $logs = $redis->lrange($lkey, $offset, $limit + $offset);
        $ret  = array();

        foreach ($logs as $log) {
            $ret[] = \Ku\Log\Data::dencode($log);
        }

        return $ret;
    }

    /**
     * 配置redis连接
     *
     * @throws \Exception
     */
    protected function initRedis() {
        if (!extension_loaded('redis')) {
            throw new \Exception('Redis is need redis Extension!');
        }

        $confs = new \Yaf\Config\Ini(APPLICATION_PATH . '/conf/application.ini', \Yaf\Application::app()->environ());

        if (!$confs instanceof \Yaf\Config\Ini) {
            throw new \Exception('Not redis configure!', 503);
        }


        $conf  = $confs->get('resources.redis');
        $redis = new \Redis();

        /*
         * 连接Redis
         *
         * 当没有定义 port 时, 可以支持 sock.
         * 但是, 需要注意: 如果host是IP或者主机名时, port 的默认值是 6379
         */
        if ($conf->get('port')) {
            $status = $redis->pconnect($conf->get('host'), $conf->get('port'));
        } else {
            $status = $redis->pconnect($conf->get('host'));
        }

        if (!$status) {
            throw new \Exception('Unable to connect to the redis:' . $conf->get('host'));
        }

        // 是否有密码
        if ($conf->get('auth')) {
            $redis->auth($conf->get('auth'));
        }

        // 是否要切换Db
        if ($conf->get('db')) {
            $redis->select($conf->get('db'));
        }

        // Key前缀
        if ($conf->get('options.prefix')) {
            $redis->setOption(\Redis::OPT_PREFIX, $conf->get('options.prefix'));
        }

        $this->_redis = $redis;
    }
}

