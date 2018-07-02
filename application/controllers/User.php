<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/7/2
 * Time: 21:35
 */
class UserController extends \Base\ApplicationController
{

    private $_accounts = array(1=>'行业短信',2=>'营销短信',3=>'特殊短信');
    private $_actions = array(1=>'充值',2=>'回退');

    /**
     * 用户列表
     */
    public function indexAction()
    {
        $where = array();
        $username = $this->getParam('username','','string');
        $company = $this->getParam('company','','string');
        if(!empty($username)){
            $where[] = "username like '%".$username."%'";
        }
        if(!empty($company)){
            $where[] = "name like '%".$company."%'";
        }
        $mapper = \Mapper\UsersModel::getInstance();
        $select = $mapper->select();
        $select->where($where);
        $select->order(array('created_at desc'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $mapper->getAdapter());
        $this->assign('pager', $pager);
        $this->assign('username',$username);
        $this->assign('name',$company);
    }

    /**
     *操作记录
     */
    public function recordsAction(){
        $where = [];
        $userid = $this->getParam('userid','','int');
        $acount = $this->getParam('acount',0,'int');
        $direction = $this->getParam('direction',0,'int');
        $time = $this->getParam('time',0,'string');
        if($userid){
            $where['user_id'] = $userid;
        }
        if($acount){
            $where['type'] = $acount;
        }
        if($direction){
            $where['direction'] = $direction;
        }
        if($time){
            $timeArr = explode('-',$time);
            $begin = date('Y-m-d',strtotime(trim($timeArr[0])));
            $end = date('Y-m-d',strtotime(trim($timeArr[1])));
            $where[] = "created_at >= '".$begin." 00:00:00' and created_at <= '".$end." 23:59:59'";
        }
        $mapper = \Mapper\RechargerecordsModel::getInstance();
        $select = $mapper->select();
        $select->where($where);
        $select->order(array('created_at desc'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $mapper->getAdapter());var_dump($pager->getList());die();
        $this->assign('pager', $pager);
        $this->assign('userid',$userid);
        $this->assign('acount',$acount);
        $this->assign('direction',$direction);
        $this->assign('time',$time);
        $this->assign('acounts',$this->_accounts);
        $this->assign('actions',$this->_actions);
    }


}
