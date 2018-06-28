<?php

namespace Cron;

use \Zend\Mail;
use \Zend\Mail\Transport\Smtp as SmtpTransport;
use \Zend\Mail\Transport\SmtpOptions;
use \Zend\Mime\Part as MimePart;
use \Zend\Mime\Message as MimeMessage;

final class Email extends \Cron\CronAbstract  {

    protected $options = null;

    /**
     * 目标邮件地址
     *
     * @var string
     */
    protected $send_address = null;

    /**
     * 邮件内容
     *
     * @var string
     */
    protected $send_content = null;

    /**
     * 发送者名称
     *
     * @var string
     */
    protected $send_from    = null;

    /**
     * 目标名称
     *
     * @var string
     */
    protected $send_name    = null;

    /**
     * 邮件主题
     *
     * @var string
     */
    protected $send_subject = null;

    /**
     * 基础URL
     *
     * @var string
     */
    protected $_baseURI = '/email/%s%s';

    public function main() {
        $beginTime = time();
        $queue     = \Ku\Queue::getInstance();

        $transport = new SmtpTransport();
        $transport->setOptions($this->sendOptions());

        $queue->setType('email');

        while (time() - $beginTime < 59){
            $queueModel = $queue->pop();

            if($queueModel === null){
                sleep(1);
                continue;
            }

            $this->sendEmail($queueModel, $transport);
        }
    }


    protected function smsfailChannel($msg){
	
	$this->send_subject = 'ip138内部系统报错提醒';
        $this->send_from    = '415694797@qq.com';
        $this->send_content = 'ip138内部系统报错提醒：'.$msg;
    }

    /**
     * 邮箱重新绑定频道
     *
     * @param string $url
     */
    protected function rebindChannel($url) {
        $this->bindChannel($url);
    }

    /**
     * 发送
     *
     * @param \Zend\Mail\Transport\Smtp $transport
     */
    public function sendEmail(\QueueModel $queueModel, SmtpTransport $transport){
        $this->parseContent($queueModel);

        try {
            $transport->send($this->sendMessage());
            $queueModel->setStatus('successed');
        } catch (\Exception $e){
           $queueModel->setStatus('failed');
        }

        try {
            \Mapper\QueueModel::getInstance()->update($queueModel);
        } catch (\Exception $e){
        }

        $this->reset();
    }

    /**
     * 发送内容
     *
     * @return \Zend\Mail\Message
     * @throws \Exception
     */
    protected function sendMessage(){
        $html = new MimePart($this->send_content);

        //邮件内容格式
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));

        $message = new Mail\Message();
        $connection_config = $this->options->getConnectionConfig();

        if(!isset($connection_config['username'])){
           return $message;
        }

        $message->setEncoding('utf-8');
        $message->setBody($body);
        $message->setFrom($connection_config['username'], $this->send_from);

        $message->addTo($this->send_address, $this->send_name);
        $message->setSubject($this->send_subject);

        return $message;
    }

    /**
     * 配置
     */
    protected function sendOptions(){
        if(!$this->options instanceof SmtpOptions){
            $config = \Yaf\Registry::get('config');
            $iniOptions = $config->get('resources.smtps');

            if(!$iniOptions) {
                return false;
            }

            $option = $iniOptions->toArray();
            $this->options = new SmtpOptions($option[1]);
        }

        if(!$this->options instanceof SmtpOptions) {
            throw new \Exception("Invalid Options");
        }

        return $this->options;
    }

    /**
     * 解析队列内容　
     *
     * @param \QueueModel $queue
     */
    protected function parseContent(\QueueModel $queue){
        $queueContent = $queue->getContent();

        if(!$queueContent){
            return false;
        }

        $json = \json_decode($queueContent, true);

        if(!isset($json['channel'])) {
            return false;
        }

        $funcName = strtolower($json['channel']) . 'Channel';

        if(method_exists($this, $funcName)) {
            $address = isset($json['address']) ? $json['address'] : null;
            $msg = isset($json['msg']) ? $json['msg'] : null;
            $username = isset($json['username']) ? $json['username'] : $address;
            $this->send_address = $address;
            $this->send_name    = $username;
            $this->{$funcName}($msg);
        }
    }


    /**
     * 重置　
     */
    protected function reset(){
        $this->send_address = null;
        $this->send_content = null;
        $this->send_name = null;
    }

}
