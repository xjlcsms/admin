<?php
class AbstractPlugin extends \Yaf\Plugin_Abstract{

	public function routerStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
            /* 在路由之前执行,这个钩子里，你可以做url重写等功能 */
        }
        public function routerShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
           /* 路由完成后，在这个钩子里，你可以做登陆检测等功能*/
        }
        public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        	//分发循环之前开始被触发
        }
        public function preDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        	//分发之前触发，如果在一次请求过程中，发生了forward, 则这个事件会被触发多次
        }
        public function postDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        	//分发结束之后被触发，此时动作已经执行结束，视图也已经渲染完成，和preDispatch类似，此事件也可以触发多次
        }
        public function dispatchLoopShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        	//分发循环结束之后被触发，此时表示所有的业务逻辑都已经运行完成，但是响应还为发送出去

        }


    /**
     * 
     * @return \Redis
     * @throws \Exception
     */
    public function getRedis() {
        $redis = \Yaf\Registry::get('redis');
        return $redis;
    }
} 