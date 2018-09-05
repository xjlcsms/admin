<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/6/30
 * Time: 10:25
 */
namespace Mapper;

class SendtasksModel extends \Mapper\AbstractModel
{

    use \Base\Model\InstanceModel;

    protected $modelClass = '\SendtasksModel';

    protected $table = 'send_tasks';

    protected $_sendTypes = array(1=>'验证码',2=>'通知',3=>'营销');

    public function getSendTypes(){
        return $this->_sendTypes;
    }

    public function getSendType($type){
        return $this->_sendTypes[$type];
    }

}