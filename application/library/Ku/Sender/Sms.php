<?php

namespace Ku\Sender;

final class Sms extends \Ku\Sender\SenderAbstract {

    /**
     * 短信验证码
     *
     * @param string $mobile
     * @return boolean
     */
    public function send() {
        $phonenumber  = $this->getDst();
        $channel = $this->getChannel();

        $key   = sprintf(\Ku\Consts::MOBILEPHONE_MESSAGECODE, md5(implode(':', array($channel, $phonenumber))));
        $code  = mt_rand(1000, 9999);
        $redis = \Yaf\Registry::get('redis');

        $redis->set($key, $code);
        $redis->expire($key, 1200);

        $queue = \Business\QueueModel::getInstance();
        $queue->setType('sms')
                ->setContent('channel', $channel)
                ->setContent('phone', $phonenumber)
                ->setContent('code', $code);

        return $queue->push();
    }
    
    public function sendMessage($params){
        $phonenumber  = $this->getDst();
        $channel = $this->getChannel();
        $queue = \Business\QueueModel::getInstance();
        $queue->setType('sms')
                ->setContent('channel', $channel)
                ->setContent('phone', $phonenumber)
                ->setContent('params', $params);

        return $queue->push();
    }
}
