<?php

/**
 * Admin
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: admin
 */
class AdminModel extends \Base\Model\AbstractModel {

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
     * Username
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_username = null;

    /**
     * Password
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_password = null;

    /**
     * Remember_token
     * 
     * Column Type: varchar(100)
     * 
     * @var string
     */
    protected $_remember_token = null;

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
     * @return \AdminModel
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
     * Username
     * 
     * Column Type: varchar(191)
     * 
     * @param string $username
     * @return \AdminModel
     */
    public function setUsername($username) {
        $this->_username = (string)$username;
        $this->_params['username'] = (string)$username;
        return $this;
    }

    /**
     * Username
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getUsername() {
        return $this->_username;
    }

    /**
     * Password
     * 
     * Column Type: varchar(191)
     * 
     * @param string $password
     * @return \AdminModel
     */
    public function setPassword($password) {
        $this->_password = (string)$password;
        $this->_params['password'] = (string)$password;
        return $this;
    }

    /**
     * Password
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getPassword() {
        return $this->_password;
    }

    /**
     * Remember_token
     * 
     * Column Type: varchar(100)
     * 
     * @param string $remember_token
     * @return \AdminModel
     */
    public function setRemember_token($remember_token) {
        $this->_remember_token = (string)$remember_token;
        $this->_params['remember_token'] = (string)$remember_token;
        return $this;
    }

    /**
     * Remember_token
     * 
     * Column Type: varchar(100)
     * 
     * @return string
     */
    public function getRemember_token() {
        return $this->_remember_token;
    }

    /**
     * Created_at
     * 
     * Column Type: timestamp
     * 
     * @param string $created_at
     * @return \AdminModel
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
     * @return \AdminModel
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
            'id'             => $this->_id,
            'username'       => $this->_username,
            'password'       => $this->_password,
            'remember_token' => $this->_remember_token,
            'created_at'     => $this->_created_at,
            'updated_at'     => $this->_updated_at
        );
    }

}
