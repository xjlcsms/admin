<?php

namespace Ku;

/**
 * 以 /m/c/a 方式优先的路由器, 而不是 Yaf 自带的 /c/a 优先
 *
 * @author ghost
 */
class Route implements \Yaf\Route_Interface {

    /**
     * 已在Yaf中注册的模块
     *
     * @var array
     */
    protected $_modules = array();

    public function __construct() {
        $this->_modules = \Yaf\Application::app()->getModules();
    }

    /**
     *
     * @param  \Yaf\Request\Http  $request
     * @return boolean
     */
    public function route($request) {
        $uri = $request->getRequestUri();

        /**
         * 如果 uri=/ 那么直接返回 false, 路由权交给Yaf默认路由自己处理.
         */
        if ('/' === $uri) {
            return false;
        }

        $uriArr = explode('/', trim($uri, '/'));

        $uri_0 = array_shift($uriArr);
        $uri_1 = array_shift($uriArr);
        $uri_2 = array_shift($uriArr);

        // 如果 uri_0 是 Module 名, 那么就路由到 Module 下
        if (in_array(ucfirst(strtolower($uri_0)), $this->_modules)) {
            $request->setModuleName(ucfirst(strtolower($uri_0)));

            if ($uri_1) {
                $request->setControllerName(ucfirst($uri_1));
            }

            if ($uri_2) {
                $request->setActionName(ucfirst($uri_2));
            }

            $count = count($uriArr);
            for ($i = 0; $i < $count; $i = $i + 2) {
                $request->setParam($uriArr[$i], isset($uriArr[$i + 1]) ? urldecode($uriArr[$i + 1]) : null);
            }

            return true;
        }

        return false;
    }

    public function assemble(array $info,array $query = null){
    }


}
