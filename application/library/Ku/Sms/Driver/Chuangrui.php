<?php

/**
 * 短信SDK（玄武科技）
 *
 * @author wuzhihua
 */

namespace Ku\Sms\Driver;

class Chuangrui extends \Ku\Sms\DriverAbstract {

    protected $mt = 'http://api.1cloudsp.com/%s';

    /**
     * 短信群发
     */
    public function send() {
        $ht = 'api/v2/send';
        $url = sprintf($this->mt,$ht);
        $params = array(
            'accesskey'=>$this->getAccesskey(),'secret'=>$this->getSecret(),
            'sign'=>$this->getSign(),'templateId'=>$this->getTemplateId(),
            'mobile'=>$this->getMobiles(),'content'=>$this->getContent(),
            'data'=>$this->getData(),'scheduleSendTime'=>$this->getScheduleSendTime()
        );
        $http = new \Ku\Http();
        $http->setUrl($url);
        $http->setParam($params,true);
        $http->setTimeout(3);
        $res = $http->send();
        return $res;
    }

    /**获取签名
     * @return array|\Ku\json|null|Object|string
     */
    public function signList(){
        $ht = '/query/signlist';
        $url = sprintf($this->mt,$ht);
        $params = array(
            'accesskey'=>$this->getAccesskey(),'secret'=>$this->getSecret(),
        );
        $http = new \Ku\Http();
        $http->setUrl($url);
        $http->setParam($params,true);
        $http->setTimeout(3);
        $res = $http->send();
        return $res;
    }


    /**
     * 验证码发送
     */
    public function sendApi(){

    }

    /**
     * 国际短信
     */
    public function sendIntl(){

    }

}
