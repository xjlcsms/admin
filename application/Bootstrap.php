<?php

use Yaf\Application;
use Yaf\Bootstrap_Abstract;
use Yaf\Dispatcher;
use Yaf\Registry;
use Yaf\Session;
use Yaf\Config\Ini;
use Yaf\Loader;
use Zend\Db\Adapter\Adapter as ZendDbAdapter;

/**
 * 引导调度类, 初始化
 */
class Bootstrap extends Bootstrap_Abstract {

    /**
     * Yaf的配置缓存
     *
     * @var \Yaf\Config\Ini
     */
    protected $config = null;

    /**
     * 重置header部分信息
     */
    public function _initResetHeader() {
        header('Server: nginx');
        header_remove("X-Powered-By");
    }

    /**
     * 把配置存到注册表
     */
    public function _initConfig() {
        $config = Application::app()->getConfig();

        $this->config = $config;
        Registry::set('config', $config);
    }

    public function _initPlugin(Dispatcher $dispatcher) {
            /* register a plugin */
            $dispatcher->registerPlugin(new QrstatPlugin());
        }

    /**
     * 重新设置一些PHP配置
     */
    public function _initPhpIni() {
        $phpSettings = $this->config->get('phpSettings');

        if ($phpSettings instanceof Ini) {
            foreach ($phpSettings->toArray() as $key => $value) {
                $this->phpIniSet($key, $value);
            }
        }
    }

    /**
     * 设置ini
     *
     * @param string $key       配置项.
     * @param mixed  $val       配置值.
     * @param string $prefix    用于和 "$key" 拼接 "." 连接符
     */
    public function phpIniSet($key, $val = null, $prefix = '') {
        $prefix .= $prefix ? ('.' . $key) : $key;

        if (is_array($val)) {
            foreach ($val as $k => $v) {
                $this->phpIniSet($k, $v, $prefix);
            }
        } else {
            @ini_set($prefix, $val);
        }
    }

    /**
     * 处理会话
     * Session 直接交由Yaf自带的类去处理.
     */
    public function _initSession() {
        $session = Session::getInstance();

        Registry::set('session', $session);
    }

    /**
     * 注册本地类
     */
    public function _initRegisterLocalNamespace() {
        Loader::getInstance()->registerLocalNamespace(array('Zend', 'Ku'));
    }

    /**
     * 设置Layout
     *
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initLayout(Dispatcher $dispatcher) {
        $layout = new \Ku\Layout($this->config->get('application.layout.directory'));
        $dispatcher->setView($layout);
        Registry::set('layout', $layout);
    }

    /**
     * Redis数据库
     *
     * @throws Exception 'Redis is need redis Extension!
     */
    public function _initRedis() {
        if (!extension_loaded('redis')) {
            throw new Exception('Redis is need redis Extension!');
        }

        $conf = $this->config->get('resources.redis');

        if (!$conf) {
            throw new Exception('Not redis configure!', 503);
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

        Registry::set('redis', $redis);
    }

    /**
     * 通过派遣器得到默认的路由器
     * 主要有以下几种路由协议
     *
     * Yaf\Route_Simple
     * Yaf\Route_Supervar
     * Yaf\Route_Static
     * Yaf\Route_Map
     * Yaf\Route_Rewrite
     * Yaf\Route_Regex
     *
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initRoute(Dispatcher $dispatcher) {
        // 通过派遣器得到默认的路由器
        $router = $dispatcher->getRouter();
        if ($this->config->routes) {
            $conf = new \Yaf\Config\Ini($this->config->routes);
            $router->addConfig($conf->get('routes'));
        }

        // 添加一个以 Module\Controller\Acation 方式优先的路由.
        $mcaRoute = new \Ku\Route();
        $router->addRoute('Kumca', $mcaRoute);
    }

    /**
     * 连接 MySQL
     *
     * @description 读取不到数据库配置参数时, 这里应该抛异常, 并中止执行.
     */
    public function _initMySQL() {
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
        $dbAdapter = new ZendDbAdapter($connect->toArray());

        Registry::set('defaultAdapter', $dbAdapter);
    }
    
    /**
     * IP白名单设置
     */
    public function _initIp() {
        // IP白名单设置
        \Yaf\Registry::set('ipwhitelist', new \Yaf\Config\Ini(APPLICATION_PATH . '/conf/ipwhitelist.ini', Application::app()->environ()));
    }
    
   /**
     * API配置文件下的指定域名
     * @param string $conName
     * @return string
     */
    public static function getApiDomain($conName){
        $apiIni = Registry::get('apiini');
        if (!$apiIni instanceof \Yaf\Config\Ini) {
            $apiIni = new \Yaf\Config\Ini(APPLICATION_PATH . '/conf/api.ini',\Yaf\Application::app()->environ());
             \Yaf\Registry::set('apiini',$apiIni);
        }
        return $apiIni->get($conName);
    }

}