<?php

/**
 * 分页类处理数据
 */
namespace Ku;
use Yaf\Registry;

use \Zend\Db\Sql\Sql;
use \Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
class Page {


    //数据查询适配器
    protected $_dbAdapter = null;

    //页数
    protected $_page;

    public function getPage() {
        return $this->_page;
    }

    public function setPage($page) {
        $this->_page = $page;
    }

    //每页
    protected $_perpage;

    public function setPerpage($val) {
        $this->_perpage = $val;
    }

    public function getPerpage() {
        return $this->_perpage;
    }

    //第一页
    protected $_first;

    public function setFirst($val) {
        $this->_first = $val;
    }

    public function getFirst() {
        return $this->_first;
    }

    //最后一页
    protected $_last;

    public function setLast($val) {
        $this->_last = $val;
    }

    public function getLast() {
        return $this->_last;
    }

    //总页数
    protected $_pageCount;

    public function setPageCount($val) {
        $this->_pageCount = $val;
    }

    public function getPageCount() {
        return $this->_pageCount;
    }

    //总记录数
    protected $_itemTotal;

    public function setItemTotal($val) {
        $this->_itemTotal = $val;
    }

    public function getItemTotal() {
        return $this->_itemTotal;
    }

    //上一页
    protected $_prev;

    public function setPrev($val) {
        $this->_prev = $val;
    }

    public function getPrev() {
        return $this->_prev;
    }

    //下一页

    protected $_next;

    public function setNext($val) {
        $this->_next = $val;
    }

    public function getNext() {
        return $this->_next;
    }

    //展示页数
    protected $_rangPage = 6;

    public function setRangPage($val) {
        $this->_rangPage = $val;
    }

    public function getRangPage() {
        return $this->_rangPage;
    }

    //数据列
    protected $_list;

    public function setList($val) {
        $this->_list = $val;
    }

    public function getList() {
        return $this->_list;
    }

    //显示的页数
    protected $_rangList;

    public function setRangList($val) {
        $this->_rangList = $val;
    }

    public function getRangList() {
        return $this->_rangList;
    }


    /**
     * 设置适配器
     */
    public function setDbApater(\Zend\Db\Adapter\Adapter $dbApater){
        $this->_dbAdapter = $dbApater;  
    }

    /**
     * 获取适配器
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter(){
        return $this->_dbAdapter;
    }

    /**
     * 分页类
     * @param \Zend\Db\Sql\Select|array $source
     * @param int $page
     * @param int $perage
     * @param \Zend\Db\Adapter\Adapter $adapter 数据库适配器
     */
    public function __construct($source, $page = 1, $perage = 20,\Zend\Db\Adapter\Adapter $adapter = null) {
        if($adapter){
            $this->setDbApater($adapter);
        }else{
            $this->setDbApater(Registry::get('defaultAdapter'));
        }
        $this->setPage($page ? $page : 1);
        $this->setPerpage($perage);
        if (is_array($source)) {
            $this->setItemTotal(count($source));
            $list = array_slice($source, ($page - 1) * $perage, $perage);
            $this->setList($list);
            $this->setFirst(1);
            $this->setLast(ceil(count($source) / $perage));
            $this->setPageCount($this->getLast());
        } elseif ($source instanceof Select) {
            
            $select = $source;
            $select->limit($perage);
            $select->offset(($page-1)*$perage);
            $list = $this->fetchAll($select);
            $this->setList($list);
            //计算总数
            $select->reset('columns');
            $select->columns(array('total'=> new Expression('count(0)')));
            $select->limit(1);
            $select->offset(0);
            $select->reset('order');
            $select->reset('group');
            $tot = $this->fetch($select);
            $total = $tot['total'];
             $this->setItemTotal($total);
            $this->setFirst(1);
            $this->setLast(ceil($total / $perage));
            $this->setPageCount($this->getLast());
        }
        if ($this->getRangPage()) {
            $rangList = array();
            $length = $this->getPageCount();
            $step = intval($this->getRangPage()/2);
            $start = 1;
            $end = $start+$this->getRangPage();
            if($length<$this->getRangPage()){
                $start = 1;
                $end = $length;
            }else{
                if($this->getPage() <=$step){
                    $start = 1;
                    $end = $this->getRangPage();
                }elseif($this->getPage()>=$length-$step){
                    $start = $length - ($this->getRangPage()-1);
                    $end = $length;
                }else{
                    $start = $this->getPage() - $step;
                    $end = $this->getPage() + $step;
                }
            }
            for ($i = $start; $i <= $end; $i++) {
                $rangList[] = $i;
            }
            $this->setRangList($rangList);
        }
    }

    /**
     *查询数据
     * @param \Zend\Db\Sql\Select $select
     */
    protected function query(Select $select){
        $dbAdapter = $this->getDbAdapter();
        if(!$dbAdapter instanceof \Zend\Db\Adapter\Adapter){
            throw new \Exception($this->getDbAdapter() . ' is Not DbAdapter.', 500);
        }
        $sql    = new Sql($dbAdapter);
        $sqlStr = $sql->getSqlStringForSqlObject($select);
     
        $result = $dbAdapter->query($sqlStr,\Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $rows = array();
        foreach ($result as $row){
            $rows[] = (array) $row;
        }
        return $rows;
    }
    /**
     * 返回单数据集
     * @param \Zend\Db\Sql\Select $select
     * @return type
     */
    protected function fetch(Select $select){
        return current($this->query($select));
    }
    /**
     * 返回多数据集
     * @param \Zend\Db\Sql\Select $select
     * @return type
     */
    protected function fetchAll(Select $select){
        return $this->query($select);
    }


}
