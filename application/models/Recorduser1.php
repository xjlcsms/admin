<?php

/**
 * Record_user_1
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: record_user_1
 */
class Recorduser1Model extends \Base\Model\AbstractModel {

    /**
     * Params
     * 
     * @var array
     */
    protected $_params = null;

    /**
     * Id
     * 
     * Column Type: int(10) unsigned
     * auto_increment
     * PRI
     * 
     * @var int
     */
    protected $_id = null;

    /**
     * 发送的用户手机
     * 
     * Column Type: varchar(30)
     * 
     * @var string
     */
    protected $_phone = null;

    /**
     * 当由系统发送时 不显示完全号码
     * 
     * Column Type: varchar(30)
     * 
     * @var string
     */
    protected $_masked_phone = null;

    /**
     * 外键 发送任务id
     * 
     * Column Type: int(10) unsigned
     * 
     * @var int
     */
    protected $_task_id = null;

    /**
     * 发送类型
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @var int
     */
    protected $_sms_type = null;

    /**
     * 计费条数
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @var int
     */
    protected $_billing_count = null;

    /**
     * 发送状态 0 等待发送 1 发送成功 2 发送失败 3 其他错误
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_status = 0;

    /**
     * 发送结果 0 未发送 其他参考API文档
     * 
     * Column Type: mediumint(9)
     * Default: 1
     * 
     * @var int
     */
    protected $_code = 1;

    /**
     * 发送的sid
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_sid = null;

    /**
     * 发送结果报告
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_report_status = null;

    /**
     * 发送结果描述
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_desc = null;

    /**
     * 是否是被抛弃的短信
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_droped = 0;

    /**
     * 发送错误的消息
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_message = null;

    /**
     * 发送成功时间
     * 
     * Column Type: datetime
     * 
     * @var string
     */
    protected $_arrivaled_at = null;

    /**
     * Created_at
     * 
     * Column Type: timestamp
     * 
     * @var string
     */
    protected $_created_at = null;

    /**
     * Updated_at
     * 
     * Column Type: timestamp
     * 
     * @var string
     */
    protected $_updated_at = null;

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
     * Column Type: int(10) unsigned
     * auto_increment
     * PRI
     * 
     * @param int $id
     * @return \Recorduser1Model
     */
    public function setId($id) {
        $this->_id = (int)$id;
        $this->_params['id'] = (int)$id;
        return $this;
    }

    /**
     * Id
     * 
     * Column Type: int(10) unsigned
     * auto_increment
     * PRI
     * 
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * 发送的用户手机
     * 
     * Column Type: varchar(30)
     * 
     * @param string $phone
     * @return \Recorduser1Model
     */
    public function setPhone($phone) {
        $this->_phone = (string)$phone;
        $this->_params['phone'] = (string)$phone;
        return $this;
    }

    /**
     * 发送的用户手机
     * 
     * Column Type: varchar(30)
     * 
     * @return string
     */
    public function getPhone() {
        return $this->_phone;
    }

    /**
     * 当由系统发送时 不显示完全号码
     * 
     * Column Type: varchar(30)
     * 
     * @param string $masked_phone
     * @return \Recorduser1Model
     */
    public function setMasked_phone($masked_phone) {
        $this->_masked_phone = (string)$masked_phone;
        $this->_params['masked_phone'] = (string)$masked_phone;
        return $this;
    }

    /**
     * 当由系统发送时 不显示完全号码
     * 
     * Column Type: varchar(30)
     * 
     * @return string
     */
    public function getMasked_phone() {
        return $this->_masked_phone;
    }

    /**
     * 外键 发送任务id
     * 
     * Column Type: int(10) unsigned
     * 
     * @param int $task_id
     * @return \Recorduser1Model
     */
    public function setTask_id($task_id) {
        $this->_task_id = (int)$task_id;
        $this->_params['task_id'] = (int)$task_id;
        return $this;
    }

    /**
     * 外键 发送任务id
     * 
     * Column Type: int(10) unsigned
     * 
     * @return int
     */
    public function getTask_id() {
        return $this->_task_id;
    }

    /**
     * 发送类型
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @param int $sms_type
     * @return \Recorduser1Model
     */
    public function setSms_type($sms_type) {
        $this->_sms_type = (int)$sms_type;
        $this->_params['sms_type'] = (int)$sms_type;
        return $this;
    }

    /**
     * 发送类型
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @return int
     */
    public function getSms_type() {
        return $this->_sms_type;
    }

    /**
     * 计费条数
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @param int $billing_count
     * @return \Recorduser1Model
     */
    public function setBilling_count($billing_count) {
        $this->_billing_count = (int)$billing_count;
        $this->_params['billing_count'] = (int)$billing_count;
        return $this;
    }

    /**
     * 计费条数
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @return int
     */
    public function getBilling_count() {
        return $this->_billing_count;
    }

    /**
     * 发送状态 0 等待发送 1 发送成功 2 发送失败 3 其他错误
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @param int $status
     * @return \Recorduser1Model
     */
    public function setStatus($status) {
        $this->_status = (int)$status;
        $this->_params['status'] = (int)$status;
        return $this;
    }

    /**
     * 发送状态 0 等待发送 1 发送成功 2 发送失败 3 其他错误
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getStatus() {
        return $this->_status;
    }

    /**
     * 发送结果 0 未发送 其他参考API文档
     * 
     * Column Type: mediumint(9)
     * Default: 1
     * 
     * @param int $code
     * @return \Recorduser1Model
     */
    public function setCode($code) {
        $this->_code = (int)$code;
        $this->_params['code'] = (int)$code;
        return $this;
    }

    /**
     * 发送结果 0 未发送 其他参考API文档
     * 
     * Column Type: mediumint(9)
     * Default: 1
     * 
     * @return int
     */
    public function getCode() {
        return $this->_code;
    }

    /**
     * 发送的sid
     * 
     * Column Type: varchar(191)
     * 
     * @param string $sid
     * @return \Recorduser1Model
     */
    public function setSid($sid) {
        $this->_sid = (string)$sid;
        $this->_params['sid'] = (string)$sid;
        return $this;
    }

    /**
     * 发送的sid
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getSid() {
        return $this->_sid;
    }

    /**
     * 发送结果报告
     * 
     * Column Type: varchar(191)
     * 
     * @param string $report_status
     * @return \Recorduser1Model
     */
    public function setReport_status($report_status) {
        $this->_report_status = (string)$report_status;
        $this->_params['report_status'] = (string)$report_status;
        return $this;
    }

    /**
     * 发送结果报告
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getReport_status() {
        return $this->_report_status;
    }

    /**
     * 发送结果描述
     * 
     * Column Type: varchar(191)
     * 
     * @param string $desc
     * @return \Recorduser1Model
     */
    public function setDesc($desc) {
        $this->_desc = (string)$desc;
        $this->_params['desc'] = (string)$desc;
        return $this;
    }

    /**
     * 发送结果描述
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getDesc() {
        return $this->_desc;
    }

    /**
     * 是否是被抛弃的短信
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @param int $droped
     * @return \Recorduser1Model
     */
    public function setDroped($droped) {
        $this->_droped = (int)$droped;
        $this->_params['droped'] = (int)$droped;
        return $this;
    }

    /**
     * 是否是被抛弃的短信
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getDroped() {
        return $this->_droped;
    }

    /**
     * 发送错误的消息
     * 
     * Column Type: varchar(191)
     * 
     * @param string $message
     * @return \Recorduser1Model
     */
    public function setMessage($message) {
        $this->_message = (string)$message;
        $this->_params['message'] = (string)$message;
        return $this;
    }

    /**
     * 发送错误的消息
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getMessage() {
        return $this->_message;
    }

    /**
     * 发送成功时间
     * 
     * Column Type: datetime
     * 
     * @param string $arrivaled_at
     * @return \Recorduser1Model
     */
    public function setArrivaled_at($arrivaled_at) {
        $this->_arrivaled_at = (string)$arrivaled_at;
        $this->_params['arrivaled_at'] = (string)$arrivaled_at;
        return $this;
    }

    /**
     * 发送成功时间
     * 
     * Column Type: datetime
     * 
     * @return string
     */
    public function getArrivaled_at() {
        return $this->_arrivaled_at;
    }

    /**
     * Created_at
     * 
     * Column Type: timestamp
     * 
     * @param string $created_at
     * @return \Recorduser1Model
     */
    public function setCreated_at($created_at) {
        $this->_created_at = (string)$created_at;
        $this->_params['created_at'] = (string)$created_at;
        return $this;
    }

    /**
     * Created_at
     * 
     * Column Type: timestamp
     * 
     * @return string
     */
    public function getCreated_at() {
        return $this->_created_at;
    }

    /**
     * Updated_at
     * 
     * Column Type: timestamp
     * 
     * @param string $updated_at
     * @return \Recorduser1Model
     */
    public function setUpdated_at($updated_at) {
        $this->_updated_at = (string)$updated_at;
        $this->_params['updated_at'] = (string)$updated_at;
        return $this;
    }

    /**
     * Updated_at
     * 
     * Column Type: timestamp
     * 
     * @return string
     */
    public function getUpdated_at() {
        return $this->_updated_at;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'            => $this->_id,
            'phone'         => $this->_phone,
            'masked_phone'  => $this->_masked_phone,
            'task_id'       => $this->_task_id,
            'sms_type'      => $this->_sms_type,
            'billing_count' => $this->_billing_count,
            'status'        => $this->_status,
            'code'          => $this->_code,
            'sid'           => $this->_sid,
            'report_status' => $this->_report_status,
            'desc'          => $this->_desc,
            'droped'        => $this->_droped,
            'message'       => $this->_message,
            'arrivaled_at'  => $this->_arrivaled_at,
            'created_at'    => $this->_created_at,
            'updated_at'    => $this->_updated_at
        );
    }

}
