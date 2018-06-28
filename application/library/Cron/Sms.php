<?php

namespace Cron;

class Sms extends \Cron\CronAbstract {

    protected $phones = null;
    protected $sendMsg = null;
    protected $_driver = 'xuanwu';

    protected function setDriver($driver) {
        $this->_driver = $driver;
    }

    public function main() {
	$redis = $this->getRedis();
        $key = 'sms_fail';
        $beginTime = time();
        $queue = \Business\QueueModel::getInstance();
        $queue->setType('sms');
        while (time() - $beginTime < 60) {
            $queueModel = $queue->pop();
            if ($queueModel === null) {
                sleep(1);
                continue;
            }
            $this->parseContent($queueModel);
            $smser = new \Ku\Sms\Adapter($this->_driver);
            $driver = $smser->getDriver();
            $driver->setMsg($this->getSendMsg());
            $driver->setPhones($this->phones);
            $result = $driver->send();
            $queueModel->setStatus($result ? 'successed' : 'failed');
            $this->sendMsg($queueModel);
	    if($result ===  false){
		$redis->incr($key);
	    	$this->log($driver->getError());
                if($redis->get($key)==3 ||$redis->get($key)==30){
       		    $queue->setType('email')
                     ->setContent('address', '89932965@qq.com')
                ->setContent('channel', 'smsfail')
                ->setContent('username', 'liantu.com内部系统监控警告')
                ->setContent('msg', 'liantu.com短信发送失败了');
                $queue->push();
  		$queue->setType('sms');
                } 
	    }else{
           	$redis->del($key);
	   }
        }
    }

    /**
     * 注册激活
     */
    public function regChannel($code) {
        $this->sendMsg = $code . '（注册短信验证码）。如非本人操作，请无视';
    }

    /**
     * 找回密码
     */
    public function resetpasswordChannel($code) {
        $this->sendMsg = $code . '（密码找回验证码）。如非本人操作，请无视。';
    }

    /**
     * 　发送消息
     *
     * @return string
     */
    protected function getSendMsg() {
        return $this->sendMsg;
    }

    /**
     * 解析队列内容　
     *
     * @param \QueueModel $queue
     */
    protected function parseContent(\QueueModel $queue) {
        $queueContent = $queue->getContent();

        if (!$queueContent) {
            return false;
        }

        $json = \json_decode($queueContent, true);

        if (!isset($json['channel'])) {
            return false;
        }

        $funcName = strtolower($json['channel']) . 'Channel';

        if (method_exists($this, $funcName)) {
            $this->phones = isset($json['phone']) ? $json['phone'] : null;
            if (isset($json['params'])) {
                $val = $json['params'];
            } else {
                $val = isset($json['code']) ? $json['code'] : null;
            }

            $this->{$funcName}($val);
        }
    }

    protected function sendMsg(\QueueModel $queue) {
        $mapper = \Mapper\QueueModel::getInstance();
        try {
            $mapper->update($queue);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}
