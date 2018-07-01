<?php

/**
 * Failed_jobs
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: failed_jobs
 */
class FailedjobsModel extends \Base\Model\AbstractModel {

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
     * Connection
     * 
     * Column Type: text
     * 
     * @var string
     */
    protected $_connection = null;

    /**
     * Queue
     * 
     * Column Type: text
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
     * Exception
     * 
     * Column Type: longtext
     * 
     * @var string
     */
    protected $_exception = null;

    /**
     * Failed_at
     * 
     * Column Type: timestamp
     * Default: CURRENT_TIMESTAMP
     * 
     * @var string
     */
    protected $_failed_at = 'CURRENT_TIMESTAMP';

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
     * @return \FailedjobsModel
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
     * Connection
     * 
     * Column Type: text
     * 
     * @param string $connection
     * @return \FailedjobsModel
     */
    public function setConnection($connection) {
        $this->_connection = (string)$connection;
        $this->_params['connection'] = (string)$connection;
        return $this;
    }

    /**
     * Connection
     * 
     * Column Type: text
     * 
     * @return string
     */
    public function getConnection() {
        return $this->_connection;
    }

    /**
     * Queue
     * 
     * Column Type: text
     * 
     * @param string $queue
     * @return \FailedjobsModel
     */
    public function setQueue($queue) {
        $this->_queue = (string)$queue;
        $this->_params['queue'] = (string)$queue;
        return $this;
    }

    /**
     * Queue
     * 
     * Column Type: text
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
     * @return \FailedjobsModel
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
     * Exception
     * 
     * Column Type: longtext
     * 
     * @param string $exception
     * @return \FailedjobsModel
     */
    public function setException($exception) {
        $this->_exception = (string)$exception;
        $this->_params['exception'] = (string)$exception;
        return $this;
    }

    /**
     * Exception
     * 
     * Column Type: longtext
     * 
     * @return string
     */
    public function getException() {
        return $this->_exception;
    }

    /**
     * Failed_at
     * 
     * Column Type: timestamp
     * Default: CURRENT_TIMESTAMP
     * 
     * @param string $failed_at
     * @return \FailedjobsModel
     */
    public function setFailed_at($failed_at) {
        $this->_failed_at = (string)$failed_at;
        $this->_params['failed_at'] = (string)$failed_at;
        return $this;
    }

    /**
     * Failed_at
     * 
     * Column Type: timestamp
     * Default: CURRENT_TIMESTAMP
     * 
     * @return string
     */
    public function getFailed_at() {
        return $this->_failed_at;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'         => $this->_id,
            'connection' => $this->_connection,
            'queue'      => $this->_queue,
            'payload'    => $this->_payload,
            'exception'  => $this->_exception,
            'failed_at'  => $this->_failed_at
        );
    }

}
