<?php

/**
 * 短信SDK（玄武科技）
 *
 * @author wuzhihua
 */

namespace Ku\Sms\Driver;

class Xuanwu extends \Ku\Sms\DriverAbstract {

    protected $mt = 'http://211.147.239.62/Service/WebService.asmx?wsdl';

    public function send() {
        $config = $this->getConfig()->get('resources.sms.xuanwu');
        if (!$config) {
            throw new \Exception('短信接口信息未配置', 404);
        }
        $conf = $config->toArray();
        if (!$config || !isset($conf['account']) || !isset($conf['password']) || !isset($conf['subid'])) {
            throw new \Exception("Invalid config");
        }
        if(!class_exists('SoapClient')){
             throw new \Exception("未安装soap扩展库");
        }
        $client = new \SoapClient($this->mt);
        $messageData = array('Phone'=>  $this->getPhones(),'Content'=>  $this->getMsg(),'vipFlag'=>'false','customMsgID'=>'','customNum'=>'');
        $uuid = $this->guid();
        $mtpacktmp = array('uuid'=>$uuid,'batchID'=>$uuid,'sendType'=>1,'msgType'=>(isset($conf['msgtype'])?$conf['msgtype']:1),'msgs'=>array('MessageData'=>$messageData),'bizType'=>'','distinctFlag'=>'','scheduleTime'=>'','deadline'=>'');
        
        try {
            $points = $client->Post(array('account'=>$conf['account'],'password'=>$conf['password'],'mtpack'=>$mtpacktmp));
            $result = (array)$points;
	    if(isset($result['PostResult']) && $result['PostResult']->message == '成功'){     
                return true;
            }else{
	       $this->SetError(json_encode($result));
               return false;
            }
        } catch (\Exception $e) {
	    $this->setError('执行失败');
            return false;
        }
        return false;
    }

    protected function guid() {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12);


            return $uuid;
        }
    }

}
