<?php

class QrstatPlugin extends AbstractPlugin{

	public function routerShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
            /* 在路由之后执行,这个钩子里，你可以做url重写等功能 */
            //统计请求次数
            $uri = $request->getRequestUri();
            if($uri == '/qr/' || $uri == '/qr'){
                $redis = $this->getRedis();
                $type = $request->get('type',null);
                $id = $request->get('type',null);
                if($type && $id){
                	$key = sprintf(\Ku\Consts::QR_REQ,$type);            	
                	$redis->hincrby($key,$id,1);
                }
                
            }
        }
}