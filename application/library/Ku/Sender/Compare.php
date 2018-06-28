<?php

namespace Ku\Sender;

final class Compare {

    /**
     * 短信验证码对比
     *
     * @param string $phonenumber
     * @param string $channel
     * @param int $code
     * @param boolean $del
     * @return boolean
     */
    public static function sms($phonenumber, $channel, $code,$del = TRUE) {
        $redis   = \Yaf\Registry::get('redis');
        $codeKey = sprintf(\Ku\Consts::MOBILEPHONE_MESSAGECODE, md5(implode(':', array($channel, $phonenumber))));
        $source  = $redis->get($codeKey);
        $result = (bool)(strcmp($code, $source) === 0 && !!$code && !!$source);
        if($del !==false && $result === true){
            $redis->del($codeKey);
        }
        return $result;
    }

    /**
     * 邮件验证
     *
     * @param string $email
     * @param string $channel
     * @param string $mid
     * @param string $code
     * @return boolean
     */
    public static function email($email, $channel,$mid, $code) {
        $redis    = \Yaf\Registry::get('redis');
        $codeKey  = sprintf(\Ku\Consts::EMAIL_LINK, implode(':', array($channel, $email,$mid)));
        $signcode = $redis->get($codeKey);
        $redis->del($codeKey);
        return (bool)(strcmp($signcode, $code) === 0 && !!$code && !!$signcode);
    }

}
