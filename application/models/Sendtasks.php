<?php

/**
 * Send_tasks
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: send_tasks
 */
class SendtasksModel extends \Base\Model\AbstractModel {

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
     * 外键 用户id
     * 
     * Column Type: int(10) unsigned
     * 
     * @var int
     */
    protected $_user_id = null;

    /**
     * 发送方式 1 自行发送 2 系统代发
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_type = 0;

    /**
     * 外键 本平台内模板id
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_template_id = 0;

    /**
     * 是否选择模板
     * 
     * Column Type: tinyint(1)
     * Default: 1
     * 
     * @var int
     */
    protected $_is_template = 1;

    /**
     * 短信类型 1 验证码 2 通知 3 营销
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @var int
     */
    protected $_sms_type = 1;

    /**
     * 短信签名
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_sign = null;

    /**
     * 短信内容
     * 
     * Column Type: text
     * 
     * @var string
     */
    protected $_content = null;

    /**
     * 地区
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_area = null;

    /**
     * 发送数量
     * 
     * Column Type: int(10) unsigned
     * 
     * @var int
     */
    protected $_quantity = null;

    /**
     * 计费条数
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @var int
     */
    protected $_billing_count = 1;

    /**
     * 发送数量
     * 
     * Column Type: int(10) unsigned
     * 
     * @var int
     */
    protected $_billing_amount = null;

    /**
     * 任务状态 0 处理中 1 已完成 2 部分失败
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_status = 0;

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
     * 同步状态
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @var int
     */
    protected $_sync_status = 0;

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
     * @return \SendtasksModel
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
     * 外键 用户id
     * 
     * Column Type: int(10) unsigned
     * 
     * @param int $user_id
     * @return \SendtasksModel
     */
    public function setUser_id($user_id) {
        $this->_user_id = (int)$user_id;
        $this->_params['user_id'] = (int)$user_id;
        return $this;
    }

    /**
     * 外键 用户id
     * 
     * Column Type: int(10) unsigned
     * 
     * @return int
     */
    public function getUser_id() {
        return $this->_user_id;
    }

    /**
     * 发送方式 1 自行发送 2 系统代发
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @param int $type
     * @return \SendtasksModel
     */
    public function setType($type) {
        $this->_type = (int)$type;
        $this->_params['type'] = (int)$type;
        return $this;
    }

    /**
     * 发送方式 1 自行发送 2 系统代发
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * 外键 本平台内模板id
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @param int $template_id
     * @return \SendtasksModel
     */
    public function setTemplate_id($template_id) {
        $this->_template_id = (int)$template_id;
        $this->_params['template_id'] = (int)$template_id;
        return $this;
    }

    /**
     * 外键 本平台内模板id
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getTemplate_id() {
        return $this->_template_id;
    }

    /**
     * 是否选择模板
     * 
     * Column Type: tinyint(1)
     * Default: 1
     * 
     * @param int $is_template
     * @return \SendtasksModel
     */
    public function setIs_template($is_template) {
        $this->_is_template = (int)$is_template;
        $this->_params['is_template'] = (int)$is_template;
        return $this;
    }

    /**
     * 是否选择模板
     * 
     * Column Type: tinyint(1)
     * Default: 1
     * 
     * @return int
     */
    public function getIs_template() {
        return $this->_is_template;
    }

    /**
     * 短信类型 1 验证码 2 通知 3 营销
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @param int $sms_type
     * @return \SendtasksModel
     */
    public function setSms_type($sms_type) {
        $this->_sms_type = (int)$sms_type;
        $this->_params['sms_type'] = (int)$sms_type;
        return $this;
    }

    /**
     * 短信类型 1 验证码 2 通知 3 营销
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @return int
     */
    public function getSms_type() {
        return $this->_sms_type;
    }

    /**
     * 短信签名
     * 
     * Column Type: varchar(191)
     * 
     * @param string $sign
     * @return \SendtasksModel
     */
    public function setSign($sign) {
        $this->_sign = (string)$sign;
        $this->_params['sign'] = (string)$sign;
        return $this;
    }

    /**
     * 短信签名
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getSign() {
        return $this->_sign;
    }

    /**
     * 短信内容
     * 
     * Column Type: text
     * 
     * @param string $content
     * @return \SendtasksModel
     */
    public function setContent($content) {
        $this->_content = (string)$content;
        $this->_params['content'] = (string)$content;
        return $this;
    }

    /**
     * 短信内容
     * 
     * Column Type: text
     * 
     * @return string
     */
    public function getContent() {
        return $this->_content;
    }

    /**
     * 地区
     * 
     * Column Type: varchar(191)
     * 
     * @param string $area
     * @return \SendtasksModel
     */
    public function setArea($area) {
        $this->_area = (string)$area;
        $this->_params['area'] = (string)$area;
        return $this;
    }

    /**
     * 地区
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getArea() {
        return $this->_area;
    }

    /**
     * 发送数量
     * 
     * Column Type: int(10) unsigned
     * 
     * @param int $quantity
     * @return \SendtasksModel
     */
    public function setQuantity($quantity) {
        $this->_quantity = (int)$quantity;
        $this->_params['quantity'] = (int)$quantity;
        return $this;
    }

    /**
     * 发送数量
     * 
     * Column Type: int(10) unsigned
     * 
     * @return int
     */
    public function getQuantity() {
        return $this->_quantity;
    }

    /**
     * 计费条数
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @param int $billing_count
     * @return \SendtasksModel
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
     * Default: 1
     * 
     * @return int
     */
    public function getBilling_count() {
        return $this->_billing_count;
    }

    /**
     * 发送数量
     * 
     * Column Type: int(10) unsigned
     * 
     * @param int $billing_amount
     * @return \SendtasksModel
     */
    public function setBilling_amount($billing_amount) {
        $this->_billing_amount = (int)$billing_amount;
        $this->_params['billing_amount'] = (int)$billing_amount;
        return $this;
    }

    /**
     * 发送数量
     * 
     * Column Type: int(10) unsigned
     * 
     * @return int
     */
    public function getBilling_amount() {
        return $this->_billing_amount;
    }

    /**
     * 任务状态 0 处理中 1 已完成 2 部分失败
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @param int $status
     * @return \SendtasksModel
     */
    public function setStatus($status) {
        $this->_status = (int)$status;
        $this->_params['status'] = (int)$status;
        return $this;
    }

    /**
     * 任务状态 0 处理中 1 已完成 2 部分失败
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
     * Created_at
     * 
     * Column Type: timestamp
     * 
     * @param string $created_at
     * @return \SendtasksModel
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
     * @return \SendtasksModel
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
     * 同步状态
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @param int $sync_status
     * @return \SendtasksModel
     */
    public function setSync_status($sync_status) {
        $this->_sync_status = (int)$sync_status;
        $this->_params['sync_status'] = (int)$sync_status;
        return $this;
    }

    /**
     * 同步状态
     * 
     * Column Type: tinyint(1)
     * Default: 0
     * 
     * @return int
     */
    public function getSync_status() {
        return $this->_sync_status;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'             => $this->_id,
            'user_id'        => $this->_user_id,
            'type'           => $this->_type,
            'template_id'    => $this->_template_id,
            'is_template'    => $this->_is_template,
            'sms_type'       => $this->_sms_type,
            'sign'           => $this->_sign,
            'content'        => $this->_content,
            'area'           => $this->_area,
            'quantity'       => $this->_quantity,
            'billing_count'  => $this->_billing_count,
            'billing_amount' => $this->_billing_amount,
            'status'         => $this->_status,
            'created_at'     => $this->_created_at,
            'updated_at'     => $this->_updated_at,
            'sync_status'    => $this->_sync_status
        );
    }

}
