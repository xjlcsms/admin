<?php

/**
 * Queue
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: queue
 */
class QueueModel extends \Base\Model\AbstractModel {

    /**
     * Params
     * 
     * @var array
     */
    protected $_params = null;

    /**
     * Id
     * 
     * Column Type: int(11)
     * auto_increment
     * PRI
     * 
     * @var int
     */
    protected $_id = null;

    /**
     * 发送任务id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @var int
     */
    protected $_task_id = 0;

    /**
     * User_id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @var int
     */
    protected $_user_id = 0;

    /**
     * Send_num
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @var int
     */
    protected $_send_num = 0;

    /**
     * Success_num
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @var int
     */
    protected $_success_num = 0;

    /**
     * 短信发送手机号
     * 
     * Column Type: text
     * 
     * @var string
     */
    protected $_mobiles = null;

    /**
     * Create_time
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @var int
     */
    protected $_create_time = 0;

    /**
     * Update_time
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @var int
     */
    protected $_update_time = 0;

    /**
     * 队列状态
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @var int
     */
    protected $_status = 0;

    /**
     * 是否不到达手机号
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @var int
     */
    protected $_isfail = 0;

    /**
     * 批次id
     * 
     * Column Type: varchar(45)
     * 
     * @var string
     */
    protected $_batchId = '';

    /**
     * 发送状态码
     * 
     * Column Type: varchar(10)
     * Default: 0
     * 
     * @var string
     */
    protected $_code = 0;

    /**
     * 发送提示
     * 
     * Column Type: varchar(255)
     * 
     * @var string
     */
    protected $_msg = '';

    /**
     * 系统发送?1:0
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @var int
     */
    protected $_sys_send = 0;

    /**
     * 发送短信类型
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @var int
     */
    protected $_type = 0;

    /**
     * 发送时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_sendTime = 0;

    /**
     * Params
     * 
     * Column Type: array
     * Default: null
     * 
     * @var array
     */
    public function getParams() {
        return $this->_params;
    }

    /**
     * Id
     * 
     * Column Type: int(11)
     * auto_increment
     * PRI
     * 
     * @param int $id
     * @return \QueueModel
     */
    public function setId($id) {
        $this->_id = (int)$id;
        $this->_params['id'] = (int)$id;
        return $this;
    }

    /**
     * Id
     * 
     * Column Type: int(11)
     * auto_increment
     * PRI
     * 
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * 发送任务id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @param int $task_id
     * @return \QueueModel
     */
    public function setTask_id($task_id) {
        $this->_task_id = (int)$task_id;
        $this->_params['task_id'] = (int)$task_id;
        return $this;
    }

    /**
     * 发送任务id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @return int
     */
    public function getTask_id() {
        return $this->_task_id;
    }

    /**
     * User_id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @param int $user_id
     * @return \QueueModel
     */
    public function setUser_id($user_id) {
        $this->_user_id = (int)$user_id;
        $this->_params['user_id'] = (int)$user_id;
        return $this;
    }

    /**
     * User_id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @return int
     */
    public function getUser_id() {
        return $this->_user_id;
    }

    /**
     * Send_num
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @param int $send_num
     * @return \QueueModel
     */
    public function setSend_num($send_num) {
        $this->_send_num = (int)$send_num;
        $this->_params['send_num'] = (int)$send_num;
        return $this;
    }

    /**
     * Send_num
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @return int
     */
    public function getSend_num() {
        return $this->_send_num;
    }

    /**
     * Success_num
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @param int $success_num
     * @return \QueueModel
     */
    public function setSuccess_num($success_num) {
        $this->_success_num = (int)$success_num;
        $this->_params['success_num'] = (int)$success_num;
        return $this;
    }

    /**
     * Success_num
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @return int
     */
    public function getSuccess_num() {
        return $this->_success_num;
    }

    /**
     * 短信发送手机号
     * 
     * Column Type: text
     * 
     * @param string $mobiles
     * @return \QueueModel
     */
    public function setMobiles($mobiles) {
        $this->_mobiles = (string)$mobiles;
        $this->_params['mobiles'] = (string)$mobiles;
        return $this;
    }

    /**
     * 短信发送手机号
     * 
     * Column Type: text
     * 
     * @return string
     */
    public function getMobiles() {
        return $this->_mobiles;
    }

    /**
     * Create_time
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @param int $create_time
     * @return \QueueModel
     */
    public function setCreate_time($create_time) {
        $this->_create_time = (int)$create_time;
        $this->_params['create_time'] = (int)$create_time;
        return $this;
    }

    /**
     * Create_time
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @return int
     */
    public function getCreate_time() {
        return $this->_create_time;
    }

    /**
     * Update_time
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @param int $update_time
     * @return \QueueModel
     */
    public function setUpdate_time($update_time) {
        $this->_update_time = (int)$update_time;
        $this->_params['update_time'] = (int)$update_time;
        return $this;
    }

    /**
     * Update_time
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @return int
     */
    public function getUpdate_time() {
        return $this->_update_time;
    }

    /**
     * 队列状态
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @param int $status
     * @return \QueueModel
     */
    public function setStatus($status) {
        $this->_status = (int)$status;
        $this->_params['status'] = (int)$status;
        return $this;
    }

    /**
     * 队列状态
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @return int
     */
    public function getStatus() {
        return $this->_status;
    }

    /**
     * 是否不到达手机号
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @param int $isfail
     * @return \QueueModel
     */
    public function setIsfail($isfail) {
        $this->_isfail = (int)$isfail;
        $this->_params['isfail'] = (int)$isfail;
        return $this;
    }

    /**
     * 是否不到达手机号
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @return int
     */
    public function getIsfail() {
        return $this->_isfail;
    }

    /**
     * 批次id
     * 
     * Column Type: varchar(45)
     * 
     * @param string $batchId
     * @return \QueueModel
     */
    public function setBatchId($batchId) {
        $this->_batchId = (string)$batchId;
        $this->_params['batchId'] = (string)$batchId;
        return $this;
    }

    /**
     * 批次id
     * 
     * Column Type: varchar(45)
     * 
     * @return string
     */
    public function getBatchId() {
        return $this->_batchId;
    }

    /**
     * 发送状态码
     * 
     * Column Type: varchar(10)
     * Default: 0
     * 
     * @param string $code
     * @return \QueueModel
     */
    public function setCode($code) {
        $this->_code = (string)$code;
        $this->_params['code'] = (string)$code;
        return $this;
    }

    /**
     * 发送状态码
     * 
     * Column Type: varchar(10)
     * Default: 0
     * 
     * @return string
     */
    public function getCode() {
        return $this->_code;
    }

    /**
     * 发送提示
     * 
     * Column Type: varchar(255)
     * 
     * @param string $msg
     * @return \QueueModel
     */
    public function setMsg($msg) {
        $this->_msg = (string)$msg;
        $this->_params['msg'] = (string)$msg;
        return $this;
    }

    /**
     * 发送提示
     * 
     * Column Type: varchar(255)
     * 
     * @return string
     */
    public function getMsg() {
        return $this->_msg;
    }

    /**
     * 系统发送?1:0
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @param int $sys_send
     * @return \QueueModel
     */
    public function setSys_send($sys_send) {
        $this->_sys_send = (int)$sys_send;
        $this->_params['sys_send'] = (int)$sys_send;
        return $this;
    }

    /**
     * 系统发送?1:0
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @return int
     */
    public function getSys_send() {
        return $this->_sys_send;
    }

    /**
     * 发送短信类型
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @param int $type
     * @return \QueueModel
     */
    public function setType($type) {
        $this->_type = (int)$type;
        $this->_params['type'] = (int)$type;
        return $this;
    }

    /**
     * 发送短信类型
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @return int
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * 发送时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @param int $sendTime
     * @return \QueueModel
     */
    public function setSendTime($sendTime) {
        $this->_sendTime = (int)$sendTime;
        $this->_params['sendTime'] = (int)$sendTime;
        return $this;
    }

    /**
     * 发送时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getSendTime() {
        return $this->_sendTime;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'          => $this->_id,
            'task_id'     => $this->_task_id,
            'user_id'     => $this->_user_id,
            'send_num'    => $this->_send_num,
            'success_num' => $this->_success_num,
            'mobiles'     => $this->_mobiles,
            'create_time' => $this->_create_time,
            'update_time' => $this->_update_time,
            'status'      => $this->_status,
            'isfail'      => $this->_isfail,
            'batchId'     => $this->_batchId,
            'code'        => $this->_code,
            'msg'         => $this->_msg,
            'sys_send'    => $this->_sys_send,
            'type'        => $this->_type,
            'sendTime'    => $this->_sendTime
        );
    }

}
