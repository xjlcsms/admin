<?php

/**
 * Recharge_records
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: recharge_records
 */
class RechargerecordsModel extends \Base\Model\AbstractModel {

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
     * 外键 进行这个操作的管理员id
     * 
     * Column Type: int(10) unsigned
     * 
     * @var int
     */
    protected $_admin_id = null;

    /**
     * 充值方向 1 充值 2 回退
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @var int
     */
    protected $_direction = null;

    /**
     * 充值类型 1 普通短信 2 营销短信
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @var int
     */
    protected $_type = null;

    /**
     * 充值金额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_amount = 0;

    /**
     * 充值显示金额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_show_amount = 0;

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
     * @return \RechargerecordsModel
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
     * @return \RechargerecordsModel
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
     * 外键 进行这个操作的管理员id
     * 
     * Column Type: int(10) unsigned
     * 
     * @param int $admin_id
     * @return \RechargerecordsModel
     */
    public function setAdmin_id($admin_id) {
        $this->_admin_id = (int)$admin_id;
        $this->_params['admin_id'] = (int)$admin_id;
        return $this;
    }

    /**
     * 外键 进行这个操作的管理员id
     * 
     * Column Type: int(10) unsigned
     * 
     * @return int
     */
    public function getAdmin_id() {
        return $this->_admin_id;
    }

    /**
     * 充值方向 1 充值 2 回退
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @param int $direction
     * @return \RechargerecordsModel
     */
    public function setDirection($direction) {
        $this->_direction = (int)$direction;
        $this->_params['direction'] = (int)$direction;
        return $this;
    }

    /**
     * 充值方向 1 充值 2 回退
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @return int
     */
    public function getDirection() {
        return $this->_direction;
    }

    /**
     * 充值类型 1 普通短信 2 营销短信
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @param int $type
     * @return \RechargerecordsModel
     */
    public function setType($type) {
        $this->_type = (int)$type;
        $this->_params['type'] = (int)$type;
        return $this;
    }

    /**
     * 充值类型 1 普通短信 2 营销短信
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @return int
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * 充值金额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @param int $amount
     * @return \RechargerecordsModel
     */
    public function setAmount($amount) {
        $this->_amount = (int)$amount;
        $this->_params['amount'] = (int)$amount;
        return $this;
    }

    /**
     * 充值金额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getAmount() {
        return $this->_amount;
    }

    /**
     * 充值显示金额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @param int $show_amount
     * @return \RechargerecordsModel
     */
    public function setShow_amount($show_amount) {
        $this->_show_amount = (int)$show_amount;
        $this->_params['show_amount'] = (int)$show_amount;
        return $this;
    }

    /**
     * 充值显示金额
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getShow_amount() {
        return $this->_show_amount;
    }

    /**
     * Created_at
     * 
     * Column Type: timestamp
     * 
     * @param string $created_at
     * @return \RechargerecordsModel
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
     * @return \RechargerecordsModel
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
            'user_id'     => $this->_user_id,
            'admin_id'    => $this->_admin_id,
            'direction'   => $this->_direction,
            'type'        => $this->_type,
            'amount'      => $this->_amount,
            'show_amount' => $this->_show_amount,
            'created_at'  => $this->_created_at,
            'updated_at'  => $this->_updated_at
        );
    }

}
