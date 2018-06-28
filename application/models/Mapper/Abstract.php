<?php

namespace Mapper;

use \Zend\Db\Sql\Predicate\Expression as PredicateExpression;
use \Zend\Db\Adapter\Driver\ConnectionInterface;
use \Zend\Db\TableGateway\TableGateway;
use \Zend\Db\Adapter\Adapter;
use \Zend\Db\Sql\Expression;
use \Zend\Db\Sql\Select;
use \Zend\Db\Sql\Where;
use \Zend\Db\Sql\Sql;

class AbstractModel {

    protected $table = null;
    protected $dbAdapter = 'defaultAdapter';
    protected $modelClass = null;
    private $connection = null;
    private $inTransaction = true;

    final public function __construct() {
        $this->setAdapter($this->dbAdapter);

        if (empty($this->modelClass)) {
            $table = str_replace('_', '', $this->table);

            $this->setModelClass('\\' . ucfirst(strtolower($table)) . 'Model');
        }
    }

    /**
     *  提供findBy*方法
     *
     * @param string $name
     * @param string $arguments
     * @return \Base\Model\AbstractModel|null
     */
    final public function __call($name, $arguments) {
        $ret = null;

        if (strpos($name, 'findBy') === 0) {
            if (isset($arguments[0])) {
                $field = strtolower(trim(str_replace('findBy', '', $name)));
                $ret = $this->fetch(array($field => addslashes($arguments[0])));
            }
        } else {
            throw new \Exception(sprintf('Call to undefined method %s::%s', __CLASS__, $name));
        }

        return $ret;
    }

    /**
     * 设置 Zend 的适配器
     *
     * @param string $adapterName
     * @return \Mapper\Model\AbstractModel
     */
    final public function setAdapter($adapterName) {
        $adapter = \Yaf\Registry::get($adapterName);

        if ($adapter instanceof \Zend\Db\Adapter\AdapterInterface) {
            $this->dbAdapter = $adapter;
        }

        return $this;
    }

    /**
     * 设置表
     *
     * @param string $table
     * @return \Mapper\Model\AbstractModel
     */
    final public function setTable($table) {
        $this->table = (string) $table;

        return $this;
    }

    /**
     * 表名
     *
     * @return string
     */
    final public function getTable() {
        return $this->table;
    }

    /**
     * 返回 Zend 的适配器
     *
     * @staticvar Object $dbAdapter
     * @return \Zend\Db\Adapter\Adapter
     * @throws \Exception
     */
    final public function getAdapter() {
        if (!$this->dbAdapter instanceof \Zend\Db\Adapter\AdapterInterface) {
            throw new \Exception('invalid Adapter');
        }

        return $this->dbAdapter;
    }

    /**
     * 数据写入 $this->modelClass, ORM 处理
     *
     * @param array $data
     * @return \Base\Model\AbstractModel
     */
    final public function map($data) {
        $modelClass = $this->modelClass;

        return new $modelClass($data);
    }

    /**
     * Model Class
     *
     * @return string
     */
    final public function getModelClass() {
        return $this->modelClass;
    }

    /**
     * Model Class
     *
     * @return \
     */
    final public function setModelClass($modelClass) {
        $this->modelClass = $modelClass;

        return $this;
    }

    /**
     * Zend 连接器
     *
     * @return ConnectionInterface
     * @throws \Exception
     */
    final public function getConnection() {
        if (!$this->connection instanceof ConnectionInterface) {
            $this->connection = $this->getAdapter()->getDriver()->getConnection();

            if (!$this->connection instanceof ConnectionInterface) {
                throw new \Exception("Invalid Connection.");
            }
        }

        return $this->connection;
    }

    /**
     * 事务开启
     *
     * @return \Mapper\Model\AbstractModel
     */
    final public function begin() {
        $this->inTransaction = true;
        $this->getConnection()->beginTransaction();

        return $this;
    }

    /**
     * 提交
     *
     * @throws \Exception
     * @return \Mapper\Model\AbstractModel
     */
    final public function commit() {
        if ($this->inTransaction === true) {
            $this->connection->commit();
            $this->inTransaction = false;
        }

        return $this;
    }

    /**
     * 是否在一个事务中
     *
     * @throws \Exception
     * @return \Mapper\Model\AbstractModel
     */
    final public function isInTransaction() {
        return $this->getConnection()->inTransaction();
    }

    /**
     * rollback
     *
     * @throws \Exception
     * @return \Mapper\Model\AbstractModel
     */
    final public function rollback() {
        if ($this->inTransaction === true) {
            $this->connection->rollback();
            $this->inTransaction = false;
        }

        return $this;
    }

    /**
     * 返回 Zend 的 TableGateway
     *
     * @staticvar array $tableGateway
     * @return \Zend\Db\TableGateway\TableGateway
     */
    final public function getTableGateway() {
        static $tableGateway = array();

        $tableName = $this->table;

        if (!isset($tableGateway[$tableName])) {
            $tableGateway[$tableName] = new TableGateway($tableName, $this->getAdapter());
        }

        return $tableGateway[$tableName];
    }

    /**
     * 返回 Zend 的 sql
     *
     * @staticvar array $select
     * @return \Zend\Db\Sql\Select
     */
    final public function sql($cached = true) {
        static $sqlArr = array();

        $tableName = $this->table;

        if ($cached === false || !isset($sqlArr[$tableName])) {
            $sqlArr[$tableName] = new Sql($this->getAdapter(), $tableName);
        }

        return $sqlArr[$tableName];
    }

    /**
     * 执行 SQL
     *
     * @param string $sql
     * @param string|array|\Zend\Db\Adapter\ParameterContainer $parametersOrQueryMode
     * @return \Zend\Db\Adapter\Driver\StatementInterface|\Zend\Db\ResultSet\ResultSet
     */
    final public function query($sql, $parametersOrQueryMode = Adapter::QUERY_MODE_EXECUTE) {
        return $this->getAdapter()->query($sql, $parametersOrQueryMode);
    }

    /**
     * 复杂的 sql 语句必须通过 Expression 类来创建 sql 语句
     *
     * @param string $expression
     * @param string $parameters
     * @param array $types
     * @return \Zend\Db\Sql\Expression
     */
    final public function expression($expression = '', $parameters = null, array $types = array()) {
        return new Expression($expression, $parameters, $types);
    }

    /**
     * where
     *
     * @param array $predicates
     * @param type $defaultCombination
     * @return \Zend\Db\Sql\Where
     */
    final public function where(array $predicates = null, $defaultCombination = Where::COMBINED_BY_AND) {
        $where = new Where();

        if (!empty($predicates)) {
            foreach ($predicates as $key => $value) {
                $key = (is_string($key) && strpos($key, '?') !== false) ? $key : '`' . (string) $key . '` = ?';
                $where->addPredicate(new PredicateExpression($key, $value), $defaultCombination);
            }
        }

        return $where;
    }

    /**
     * 获得语句的SQL字符串
     *
     * @param \Zend\Db\Sql\Select $select
     * @return string
     */
    final public function getSqlString(Select $select) {
        $sqlPlatform = $this->sql()->getSqlPlatform();
        $sqlPlatform->setSubject($select);

        return $sqlPlatform->getSqlString($this->getAdapter()->getPlatform());
    }

    /**
     * fetch , only one
     *
     * @param Where|\Closure|string|array|Predicate\PredicateInterface\PredicateInterface $predicate
     * @param string|array $order
     * @param int $offset
     * @return null|\Base\Model\AbstractModel
     */
    public function fetch($predicate, $order = null, $offset = 0, $columns = array(), $orm = true) {
        $sql = $this->sql();
        $select = $sql->select();
        $select->limit(1);
        $select->where($predicate);

        if (!empty($order)) {
            $select->order($order);
        }

        if ($offset >= 0) {
            $select->offset($offset);
        }

        if (!empty($columns)) {
            $select->columns($columns);
        }
        $rows = $this->query($this->getSqlString($select))->toArray();


        $data = $this->query($this->getSqlString($select))->current();

        if ($orm) {
            return (!empty($data) ? $this->map($data) : null);
        } else {
            return (!empty($data) ? $data : null);
        }
    }

    /**
     * fetch All
     *
     * @param Where|\Closure|string|array|Predicate\PredicateInterface\PredicateInterface $predicate
     * @param string|array $order
     * @param int $offset
     * @return null|ModelClass
     */
    public function fetchAll($predicate = null, $order = null, $limit = 0, $offset = 0, $columns = array(), $orm = true) {
        $sql = $this->sql();
        $select = $sql->select();
        $select->where($predicate);

        if (!empty($order)) {
            $select->order($order);
        }

        if ($offset > 0) {
            $select->offset($offset);
        }

        if ($limit > 0) {
            $select->limit($limit);
        }
        if (!empty($columns)) {
            $select->columns($columns);
        }
        $rows = $this->query($this->getSqlString($select))->toArray();

        if ($orm) {
            $entries = array();
            foreach ($rows as $row) {
                $entries[] = $this->map($row);
            }

            return $entries;
        } else {
            return $rows;
        }
    }

    /**
     * 插入数据
     *
     * @param \Base\Model\AbstractModel $model
     * @return int
     */
    public function insert(\Base\Model\AbstractModel $model) {
        $modelData = $model->toArray();
        unset($modelData['id']);
        $this->getTableGateway()->insert($modelData);

        return (int) $this->getLastInsertId();
    }

    /**
     * 取得最写入的ID
     *
     * @return int (last id)
     */
    public function getLastInsertId() {
        return $this->getTableGateway()->getLastInsertValue();
    }



    public function save(\Base\Model\AbstractModel $model){
        $data = $model->toArray();
        if(isset($data['id']) && $data['id']>0){
            return $this->update($model);
        }else{
            return $this->insert($model);
        }
    }

    /**
     * Updates existing model.
     *
     * @param \Base\Model\AbstractModel $model
     * @return int The number of rows updated.
     */
    public function update($model, $where = array()) {
        if($model instanceof \Base\Model\AbstractModel){
            $data = $model->toArray();
            unset($data['id']);
        }else{
            $data = $model;
            if(is_array($data)){
                foreach ($data as $key => $value) {
                    if(preg_match('/(\+|\-)/', $value,$matchs)){
                        $data[$key] = $this->expression($value);
                    }
                }
            }else{
                new \Exception('invalid where');
            }
        }
        if (!$where) {
            $where = array("`id` = ?" => $model->getId());
        }
        return $this->getTableGateway()->update($data, $where);
    }

    /**
     * remove existing model.
     *
     * @param array|string $where
     * @return int The number of rows deleted.
     */
    public function del($where) {
        return $this->getTableGateway()->delete($where);
    }

    /**
     * 统计
     *
     * @param Where|\Closure|string|array|Predicate\PredicateInterface\PredicateInterface $predicate
     * @param string $column
     * @return int
     */
    public function count($predicate, $column = null) {
        $sql = $this->sql();
        $select = $sql->select();
        $select->reset('columns');
        $select->columns(array('count' => $this->expression(sprintf('count(%s)', ($column ? : '*')))));
        $select->where($predicate);

        try {
            $query = $this->query($this->getSqlString($select))->current();
            $total = (isset($query['count'])) ? $query['count'] : 0;
        } catch (\Exception $ex) {
            $total = 0;
        }

        return $total;
    }

    /**
     * 统计(求和)
     *
     * @params string $column
     * @param Where|\Closure|string|array|Predicate\PredicateInterface\PredicateInterface $predicate
     * @return int
     */
    public function sum($column, $predicate) {
        $sql = $this->sql();
        $select = $sql->select();
        $select->reset('columns');
        $select->columns(array('total' => $this->expression('sum(`' . $column . '`)')));
        $select->where($predicate);

        try {
            $query = $this->query($this->getSqlString($select))->current();
            $total = (isset($query['total'])) ? $query['total'] : 0;
        } catch (\Exception $ex) {
            $total = 0;
        }

        return $total;
    }

    public function entriToArray($result) {
        $entries = array();
        foreach ($result as $row) {
            $entries[] = (array) $row;
        }

        return $entries;
    }

    /**
     * 执行Select
     *
     * @param \Zend\Db\Sql\Select $select
     * @return Driver\StatementInterface|ResultSet\ResultSet
     */
    public function queryforselect(Select $select) {
        $adapter = $this->getAdapter();
        $sqlString = $this->sql()->getSqlStringForSqlObject($select, $adapter->getPlatform());
        return $adapter->query($sqlString, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function entriToStd($result) {
        $entries = array();
        foreach ($result as $row) {
            $entries[] = new \Ku\Std((array) $row);
        }

        return $entries;
    }

    /**
     * 返回 Zend 的 Select
     *
     * @staticvar array $select
     * @return \Zend\Db\Sql\Select
     */
    public function select() {
        return $this->sql()->select();
    }

    /**
     * 返回 Redis 实例
     *
     * @return \Redis|null
     */
    public function getRedis() {
        static $_redis = null;

        return $_redis ? : \Yaf\Registry::get('redis');
    }

}
