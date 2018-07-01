<?php

/**
 * Templates
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: templates
 */
class TemplatesModel extends \Base\Model\AbstractModel {

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
     * 云之讯对应模板id
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_template_id = 0;

    /**
     * 模板状态 0 待审 1 审核通过 2 审核不通过
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_status = 0;

    /**
     * 模板类型 1 固定模板 2 变量模板
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @var int
     */
    protected $_type = 1;

    /**
     * 模板属性 1 行业短信 2 营销短信
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @var int
     */
    protected $_classify = 1;

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
     * 审核原因
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_reason = null;

    /**
     * 外键 用户id
     * 
     * Column Type: int(10) unsigned
     * 
     * @var int
     */
    protected $_user_id = null;

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
     * @return \TemplatesModel
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
     * 云之讯对应模板id
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @param int $template_id
     * @return \TemplatesModel
     */
    public function setTemplate_id($template_id) {
        $this->_template_id = (int)$template_id;
        $this->_params['template_id'] = (int)$template_id;
        return $this;
    }

    /**
     * 云之讯对应模板id
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
     * 模板状态 0 待审 1 审核通过 2 审核不通过
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @param int $status
     * @return \TemplatesModel
     */
    public function setStatus($status) {
        $this->_status = (int)$status;
        $this->_params['status'] = (int)$status;
        return $this;
    }

    /**
     * 模板状态 0 待审 1 审核通过 2 审核不通过
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
     * 模板类型 1 固定模板 2 变量模板
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @param int $type
     * @return \TemplatesModel
     */
    public function setType($type) {
        $this->_type = (int)$type;
        $this->_params['type'] = (int)$type;
        return $this;
    }

    /**
     * 模板类型 1 固定模板 2 变量模板
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @return int
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * 模板属性 1 行业短信 2 营销短信
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @param int $classify
     * @return \TemplatesModel
     */
    public function setClassify($classify) {
        $this->_classify = (int)$classify;
        $this->_params['classify'] = (int)$classify;
        return $this;
    }

    /**
     * 模板属性 1 行业短信 2 营销短信
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 1
     * 
     * @return int
     */
    public function getClassify() {
        return $this->_classify;
    }

    /**
     * 短信签名
     * 
     * Column Type: varchar(191)
     * 
     * @param string $sign
     * @return \TemplatesModel
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
     * @return \TemplatesModel
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
     * 审核原因
     * 
     * Column Type: varchar(191)
     * 
     * @param string $reason
     * @return \TemplatesModel
     */
    public function setReason($reason) {
        $this->_reason = (string)$reason;
        $this->_params['reason'] = (string)$reason;
        return $this;
    }

    /**
     * 审核原因
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getReason() {
        return $this->_reason;
    }

    /**
     * 外键 用户id
     * 
     * Column Type: int(10) unsigned
     * 
     * @param int $user_id
     * @return \TemplatesModel
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
     * Created_at
     * 
     * Column Type: timestamp
     * 
     * @param string $created_at
     * @return \TemplatesModel
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
     * @return \TemplatesModel
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
            'id'          => $this->_id,
            'template_id' => $this->_template_id,
            'status'      => $this->_status,
            'type'        => $this->_type,
            'classify'    => $this->_classify,
            'sign'        => $this->_sign,
            'content'     => $this->_content,
            'reason'      => $this->_reason,
            'user_id'     => $this->_user_id,
            'created_at'  => $this->_created_at,
            'updated_at'  => $this->_updated_at
        );
    }

}
