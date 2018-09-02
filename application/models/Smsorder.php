<?php

/**
 * Sms_order
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: sms_order
 */
class SmsorderModel extends \Base\Model\AbstractModel {

    /**
     * Params
     * 
     * @var array
     */
    protected $_params = null;

    /**
     * Id
     * 
     * Column Type: bigint(20)
     * auto_increment
     * PRI
     * 
     * @var int
     */
    protected $_id = null;

    /**
     * User_id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_user_id = 0;

    /**
     * Task_id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_task_id = 0;

    /**
     * 短信唯一标示
     * 
     * Column Type: varchar(50)
     * 
     * @var string
     */
    protected $_uid = '';

    /**
     * Mobile
     * 
     * Column Type: varchar(12)
     * 
     * @var string
     */
    protected $_mobile = '';

    /**
     * Show_mobile
     * 
     * Column Type: varchar(12)
     * 
     * @var string
     */
    protected $_show_mobile = '';

    /**
     * 状态码回执时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_deliver_time = 0;

    /**
     * 注：DELIVRD 为成功，其他均为失败
短信状态码
     * 
     * Column Type: varchar(45)
     * 
     * @var string
     */
    protected $_result = '';

    /**
     * BatchId
     * 
     * Column Type: varchar(45)
     * 
     * @var string
     */
    protected $_batchId = '';

    /**
     * Create_time
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_create_time = 0;

    /**
     * 状态
     * 
     * Column Type: tinyint(1) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_status = 0;

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
     * Column Type: bigint(20)
     * auto_increment
     * PRI
     * 
     * @param int $id
     * @return \SmsorderModel
     */
    public function setId($id) {
        $this->_id = (int)$id;
        $this->_params['id'] = (int)$id;
        return $this;
    }

    /**
     * Id
     * 
     * Column Type: bigint(20)
     * auto_increment
     * PRI
     * 
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * User_id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @param int $user_id
     * @return \SmsorderModel
     */
    public function setUser_id($user_id) {
        $this->_user_id = (int)$user_id;
        $this->_params['user_id'] = (int)$user_id;
        return $this;
    }

    /**
     * User_id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getUser_id() {
        return $this->_user_id;
    }

    /**
     * Task_id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @param int $task_id
     * @return \SmsorderModel
     */
    public function setTask_id($task_id) {
        $this->_task_id = (int)$task_id;
        $this->_params['task_id'] = (int)$task_id;
        return $this;
    }

    /**
     * Task_id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getTask_id() {
        return $this->_task_id;
    }

    /**
     * 短信唯一标示
     * 
     * Column Type: varchar(50)
     * 
     * @param string $uid
     * @return \SmsorderModel
     */
    public function setUid($uid) {
        $this->_uid = (string)$uid;
        $this->_params['uid'] = (string)$uid;
        return $this;
    }

    /**
     * 短信唯一标示
     * 
     * Column Type: varchar(50)
     * 
     * @return string
     */
    public function getUid() {
        return $this->_uid;
    }

    /**
     * Mobile
     * 
     * Column Type: varchar(12)
     * 
     * @param string $mobile
     * @return \SmsorderModel
     */
    public function setMobile($mobile) {
        $this->_mobile = (string)$mobile;
        $this->_params['mobile'] = (string)$mobile;
        return $this;
    }

    /**
     * Mobile
     * 
     * Column Type: varchar(12)
     * 
     * @return string
     */
    public function getMobile() {
        return $this->_mobile;
    }

    /**
     * Show_mobile
     * 
     * Column Type: varchar(12)
     * 
     * @param string $show_mobile
     * @return \SmsorderModel
     */
    public function setShow_mobile($show_mobile) {
        $this->_show_mobile = (string)$show_mobile;
        $this->_params['show_mobile'] = (string)$show_mobile;
        return $this;
    }

    /**
     * Show_mobile
     * 
     * Column Type: varchar(12)
     * 
     * @return string
     */
    public function getShow_mobile() {
        return $this->_show_mobile;
    }

    /**
     * 状态码回执时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @param int $deliver_time
     * @return \SmsorderModel
     */
    public function setDeliver_time($deliver_time) {
        $this->_deliver_time = (int)$deliver_time;
        $this->_params['deliver_time'] = (int)$deliver_time;
        return $this;
    }

    /**
     * 状态码回执时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getDeliver_time() {
        return $this->_deliver_time;
    }

    /**
     * 注：DELIVRD 为成功，其他均为失败
短信状态码
     * 
     * Column Type: varchar(45)
     * 
     * @param string $result
     * @return \SmsorderModel
     */
    public function setResult($result) {
        $this->_result = (string)$result;
        $this->_params['result'] = (string)$result;
        return $this;
    }

    /**
     * 注：DELIVRD 为成功，其他均为失败
短信状态码
     * 
     * Column Type: varchar(45)
     * 
     * @return string
     */
    public function getResult() {
        return $this->_result;
    }

    /**
     * BatchId
     * 
     * Column Type: varchar(45)
     * 
     * @param string $batchId
     * @return \SmsorderModel
     */
    public function setBatchId($batchId) {
        $this->_batchId = (string)$batchId;
        $this->_params['batchId'] = (string)$batchId;
        return $this;
    }

    /**
     * BatchId
     * 
     * Column Type: varchar(45)
     * 
     * @return string
     */
    public function getBatchId() {
        return $this->_batchId;
    }

    /**
     * Create_time
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @param int $create_time
     * @return \SmsorderModel
     */
    public function setCreate_time($create_time) {
        $this->_create_time = (int)$create_time;
        $this->_params['create_time'] = (int)$create_time;
        return $this;
    }

    /**
     * Create_time
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getCreate_time() {
        return $this->_create_time;
    }

    /**
     * 状态
     * 
     * Column Type: tinyint(1) unsigned
     * Default: 0
     * 
     * @param int $status
     * @return \SmsorderModel
     */
    public function setStatus($status) {
        $this->_status = (int)$status;
        $this->_params['status'] = (int)$status;
        return $this;
    }

    /**
     * 状态
     * 
     * Column Type: tinyint(1) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getStatus() {
        return $this->_status;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'           => $this->_id,
            'user_id'      => $this->_user_id,
            'task_id'      => $this->_task_id,
            'uid'          => $this->_uid,
            'mobile'       => $this->_mobile,
            'show_mobile'  => $this->_show_mobile,
            'deliver_time' => $this->_deliver_time,
            'result'       => $this->_result,
            'batchId'      => $this->_batchId,
            'create_time'  => $this->_create_time,
            'status'       => $this->_status
        );
    }

}
