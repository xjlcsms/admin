<?php

namespace Cron;

abstract class CronAbstract {

    protected $argv = null;
    protected $conf = null;
    protected $redis = null;
    protected $adapter = 'default';

    final public function __construct(array $argv, \Yaf\Application $app) {
        $conf = $app->getConfig();
        $this->argv = $argv;
        $this->conf = $conf;
        $this->addAdapter($this->adapter);
        $this->getRedis();
        \Yaf\Registry::set('config', $conf);
    }

    /**
     * 配置
     *
     * @return \Yaf\Config\Ini
     */
    protected function getConfig() {
        return $this->conf;
    }

    /**
     * 获取参数
     *
     * @param int $index
     * @return string
     */
    protected function getArgv($index) {
        return isset($this->argv[$index]) ? $this->argv[$index] : null;
    }

    /**
     * Redis数据库
     *
     * @throws Exception 'Redis is need redis Extension!
     */
    protected function getRedis() {
        if ($this->redis instanceof \Redis) {
            return $this->redis;
        }

        if (!extension_loaded('redis')) {
            throw new \Exception('Redis is need redis Extension!');
        }

        $conf = $this->getConfig()->get('resources.redis');

        if (!$conf) {
            throw new \Exception('Not redis configure!', 503);
        }

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
        $this->redis = $redis;
        \Yaf\Registry::set('redis', $redis);
        return $redis;
    }

    /**
     * 增加适配器
     *
     * @param string $name
     * @throws \Exception
     */
    public function addAdapter() {
        $conf = new \Yaf\Config\Ini(APPLICATION_PATH . '/conf/database.ini', \Yaf\Application::app()->environ());
        $connects = $conf->get('resources.database');
        if (!$connects) {
            throw new \Exception('Not database configure', 503);
        }
        if (isset($connects['multi'])) {
            $connect = $connects['multi']['default'];
        } else {
            $connect = $connects;
        }
        $dbAdapter = new \Zend\Db\Adapter\Adapter($connect->toArray());
        \Yaf\Registry::set('defaultAdapter', $dbAdapter);
    }

    // 静态页面的配置
    public function getApiDomain($conName) {
        $apiIni = \Yaf\Registry::get('apiini');
        if (!$apiIni) {
            $apiIni = new \Yaf\Config\Ini(APPLICATION_PATH . '/conf/api.ini', \Yaf\Application::app()->environ());
            \Yaf\Registry::set('apiini', $apiIni);
        }
        return $apiIni->get($conName);
    }
    
    public function log($msg){
          echo date('Y-m-d H:i:s').'-'.$msg."\r\n";
    }    

    abstract public function main();
}
