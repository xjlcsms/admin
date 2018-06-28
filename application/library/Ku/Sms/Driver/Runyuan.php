<?php

/**
 * 短信SDK（润圆科技）
 *
 * @author wuzhihua
 */
namespace Ku\Sms\Driver;

class Runyuan extends \Ku\Sms\DriverAbstract{
    protected $mt = 'http://www.4001185185.com/sdk/smssdk!mt.action?sdk=%s&code=%s&phones=%s&msg=%s&resulttype=text&subcode=%s&pwdtype=md5';
    protected $_sender = '【联途旅游】';
    public function send() {
       $config = $this->getConfig()->get('resources.sms.runyuan');
        if (!$config) {
            throw new \Exception('短信接口信息未配置', 404);
        }
        $conf = $config->toArray();
        if (!$config||!isset($conf['sdk']) || !isset($conf['code']) || !isset($conf['subcode'])) {
            throw new \Exception("Invalid config");
        }
        $url = sprintf($this->mt, $conf['sdk'], md5($conf['code']), (string) $this->getPhones(), (string) urlencode($this->_sender.$this->getMsg()), $conf['subcode']);
        $http = new \Ku\Http();
        $http->setUrl($url);
        $http->setTimeout(3);
        try {
            $http->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

}
