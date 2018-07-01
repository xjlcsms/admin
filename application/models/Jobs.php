<?php

/**
 * Jobs
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: jobs
 */
class JobsModel extends \Base\Model\AbstractModel {

    /**
     * Params
     * 
     * @var array
     */
    protected $_params = null;

    /**
     * Id
     * 
     * Column Type: bigint(20) unsigned
     * auto_increment
     * PRI
     * 
     * @var int
     */
    protected $_id = null;

    /**
     * Queue
     * 
     * Column Type: varchar(191)
     * MUL
     * 
     * @var string
     */
    protected $_queue = null;

    /**
     * Payload
     * 
     * Column Type: longtext
     * 
     * @var string
     */
    protected $_payload = null;

    /**
     * Attempts
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @var int
     */
    protected $_attempts = null;

    /**
     * Reserved_at
     * 
     * Column Type: int(10) unsigned
     * 
     * @var int
     */
    protected $_reserved_at = null;

    /**
     * Available_at
     * 
     * Column Type: int(10) unsigned
     * 
     * @var int
     */
    protected $_available_at = null;

    /**
     * Created_at
     * 
     * Column Type: int(10) unsigned
     * 
     * @var int
     */
    protected $_created_at = null;

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
     * Column Type: bigint(20) unsigned
     * auto_increment
     * PRI
     * 
     * @param int $id
     * @return \JobsModel
     */
    public function setId($id) {
        $this->_id = (int)$id;
        $this->_params['id'] = (int)$id;
        return $this;
    }

    /**
     * Id
     * 
     * Column Type: bigint(20) unsigned
     * auto_increment
     * PRI
     * 
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Queue
     * 
     * Column Type: varchar(191)
     * MUL
     * 
     * @param string $queue
     * @return \JobsModel
     */
    public function setQueue($queue) {
        $this->_queue = (string)$queue;
        $this->_params['queue'] = (string)$queue;
        return $this;
    }

    /**
     * Queue
     * 
     * Column Type: varchar(191)
     * MUL
     * 
     * @return string
     */
    public function getQueue() {
        return $this->_queue;
    }

    /**
     * Payload
     * 
     * Column Type: longtext
     * 
     * @param string $payload
     * @return \JobsModel
     */
    public function setPayload($payload) {
        $this->_payload = (string)$payload;
        $this->_params['payload'] = (string)$payload;
        return $this;
    }

    /**
     * Payload
     * 
     * Column Type: longtext
     * 
     * @return string
     */
    public function getPayload() {
        return $this->_payload;
    }

    /**
     * Attempts
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @param int $attempts
     * @return \JobsModel
     */
    public function setAttempts($attempts) {
        $this->_attempts = (int)$attempts;
        $this->_params['attempts'] = (int)$attempts;
        return $this;
    }

    /**
     * Attempts
     * 
     * Column Type: tinyint(3) unsigned
     * 
     * @return int
     */
    public function getAttempts() {
        return $this->_attempts;
    }

    /**
     * Reserved_at
     * 
     * Column Type: int(10) unsigned
     * 
     * @param int $reserved_at
     * @return \JobsModel
     */
    public function setReserved_at($reserved_at) {
        $this->_reserved_at = (int)$reserved_at;
        $this->_params['reserved_at'] = (int)$reserved_at;
        return $this;
    }

    /**
     * Reserved_at
     * 
     * Column Type: int(10) unsigned
     * 
     * @return int
     */
    public function getReserved_at() {
        return $this->_reserved_at;
    }

    /**
     * Available_at
     * 
     * Column Type: int(10) unsigned
     * 
     * @param int $available_at
     * @return \JobsModel
     */
    public function setAvailable_at($available_at) {
        $this->_available_at = (int)$available_at;
        $this->_params['available_at'] = (int)$available_at;
        return $this;
    }

    /**
     * Available_at
     * 
     * Column Type: int(10) unsigned
     * 
     * @return int
     */
    public function getAvailable_at() {
        return $this->_available_at;
    }

    /**
     * Created_at
     * 
     * Column Type: int(10) unsigned
     * 
     * @param int $created_at
     * @return \JobsModel
     */
    public function setCreated_at($created_at) {
        $this->_created_at = (int)$created_at;
        $this->_params['created_at'] = (int)$created_at;
        return $this;
    }

    /**
     * Created_at
     * 
     * Column Type: int(10) unsigned
     * 
     * @return int
     */
    public function getCreated_at() {
        return $this->_created_at;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'           => $this->_id,
            'queue'        => $this->_queue,
            'payload'      => $this->_payload,
            'attempts'     => $this->_attempts,
            'reserved_at'  => $this->_reserved_at,
            'available_at' => $this->_available_at,
            'created_at'   => $this->_created_at
        );
    }

}
