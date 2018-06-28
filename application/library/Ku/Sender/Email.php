<?php

namespace Ku\Sender;

final class Email extends \Ku\Sender\SenderAbstract {

    protected $_baseURI = '/member/email/%s';

    /**
     * 邮箱验证链接发送
     *
     * @param string $email
     * @param int $channel
     * @return boolean
     */
    public function send() {
        $redis    = \Yaf\Registry::get('redis');
        $username = $this->getUsername();
        $mid = $this->getMid();
        $email    = $this->getDst();
        $channel  = $this->getChannel();
        $signCode = $this->signCode(array($channel, $email));
        $key      = sprintf(\Ku\Consts::EMAIL_LINK, implode(':', array($channel, $email,$mid)));

        $redis->set($key, $signCode);
        $redis->expire($key, 7200);

        $queue = \Ku\Queue::getInstance();
        $queue->setType('email')
                ->setContent('address', $email)
                ->setContent('mid', $mid)
                ->setContent('channel', $channel)
                ->setContent('username', $username)
                ->setContent('code', $signCode);
        return $queue->push();
    }

    /**
     * 创建CODE
     *
     * @param array $data
     * @return string
     */
    protected function signCode(array $data = array()) {
        $k1 = sha1(implode(':)', $data) . ':)' . uniqid(mt_rand(10, 999999)));

        return md5($k1 . ':' . strrev($k1) . ':strrve:' . mt_rand(10, 999999));
    }
}
