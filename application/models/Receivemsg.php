<?php

/**
 * Receive_msg
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: receive_msg
 */
class ReceivemsgModel extends \Base\Model\AbstractModel {

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
     * Mobile
     * 
     * Column Type: varchar(11)
     * 
     * @var string
     */
    protected $_mobile = '';

    /**
     * Content
     * 
     * Column Type: varchar(100)
     * 
     * @var string
     */
    protected $_content = '';

    /**
     * Receive_time
     * 
     * Column Type: bigint(11) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_receive_time = 0;

    /**
     * ExtNo
     * 
     * Column Type: varchar(45)
     * 
     * @var string
     */
    protected $_extNo = '';

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
     * @return \ReceivemsgModel
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
     * Mobile
     * 
     * Column Type: varchar(11)
     * 
     * @param string $mobile
     * @return \ReceivemsgModel
     */
    public function setMobile($mobile) {
        $this->_mobile = (string)$mobile;
        $this->_params['mobile'] = (string)$mobile;
        return $this;
    }

    /**
     * Mobile
     * 
     * Column Type: varchar(11)
     * 
     * @return string
     */
    public function getMobile() {
        return $this->_mobile;
    }

    /**
     * Content
     * 
     * Column Type: varchar(100)
     * 
     * @param string $content
     * @return \ReceivemsgModel
     */
    public function setContent($content) {
        $this->_content = (string)$content;
        $this->_params['content'] = (string)$content;
        return $this;
    }

    /**
     * Content
     * 
     * Column Type: varchar(100)
     * 
     * @return string
     */
    public function getContent() {
        return $this->_content;
    }

    /**
     * Receive_time
     * 
     * Column Type: bigint(11) unsigned
     * Default: 0
     * 
     * @param int $receive_time
     * @return \ReceivemsgModel
     */
    public function setReceive_time($receive_time) {
        $this->_receive_time = (int)$receive_time;
        $this->_params['receive_time'] = (int)$receive_time;
        return $this;
    }

    /**
     * Receive_time
     * 
     * Column Type: bigint(11) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getReceive_time() {
        return $this->_receive_time;
    }

    /**
     * ExtNo
     * 
     * Column Type: varchar(45)
     * 
     * @param string $extNo
     * @return \ReceivemsgModel
     */
    public function setExtNo($extNo) {
        $this->_extNo = (string)$extNo;
        $this->_params['extNo'] = (string)$extNo;
        return $this;
    }

    /**
     * ExtNo
     * 
     * Column Type: varchar(45)
     * 
     * @return string
     */
    public function getExtNo() {
        return $this->_extNo;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'           => $this->_id,
            'mobile'       => $this->_mobile,
            'content'      => $this->_content,
            'receive_time' => $this->_receive_time,
            'extNo'        => $this->_extNo
        );
    }

}
