<?php

/**
 * Migrations
 * 
 * @Table Schema: sms_chuangrui
 * @Table Name: migrations
 */
class MigrationsModel extends \Base\Model\AbstractModel {

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
     * Migration
     * 
     * Column Type: varchar(191)
     * 
     * @var string
     */
    protected $_migration = null;

    /**
     * Batch
     * 
     * Column Type: int(11)
     * 
     * @var int
     */
    protected $_batch = null;

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
     * @return \MigrationsModel
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
     * Migration
     * 
     * Column Type: varchar(191)
     * 
     * @param string $migration
     * @return \MigrationsModel
     */
    public function setMigration($migration) {
        $this->_migration = (string)$migration;
        $this->_params['migration'] = (string)$migration;
        return $this;
    }

    /**
     * Migration
     * 
     * Column Type: varchar(191)
     * 
     * @return string
     */
    public function getMigration() {
        return $this->_migration;
    }

    /**
     * Batch
     * 
     * Column Type: int(11)
     * 
     * @param int $batch
     * @return \MigrationsModel
     */
    public function setBatch($batch) {
        $this->_batch = (int)$batch;
        $this->_params['batch'] = (int)$batch;
        return $this;
    }

    /**
     * Batch
     * 
     * Column Type: int(11)
     * 
     * @return int
     */
    public function getBatch() {
        return $this->_batch;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'        => $this->_id,
            'migration' => $this->_migration,
            'batch'     => $this->_batch
        );
    }

}
