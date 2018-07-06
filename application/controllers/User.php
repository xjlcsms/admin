<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/7/2
 * Time: 21:35
 */
//21000
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
        $this->assign('accounts',$this->_accounts);
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


    /**
     * 创瑞云开户
     * @return false
     */
    public function insertAction(){
        $username = $this->getParam('username','','string');
        $passward = $this->getParam('password','','string');
        $companyName = $this->getParam('companyName','','string');
        $type = $this->getParam('type',0,'int');
        $access_key = $this->getParam('access_key','','string');
        $secret = $this->getParam('secret','','string');
        if(empty($username) || empty($passward)|| empty($access_key)|| empty($secret)|| empty($companyName)){
            return $this->returnData('数据不能为空',21000);
        }
        if(strlen($passward)<6){
            return $this->returnData('密码长度不能小于6位',21002);
        }
        if(!$type){
            return $this->returnData('用户类型错误',21003);
        }
        $mapper = \Mapper\UsersModel::getInstance();
        $model = new \UsersModel();
        $model->setUsername($username);
        $model->setAccess_key($access_key);
        $model->setSecret($secret);
        $model->setPassword(\Ku\Tool::encryption($passward));
        $model->setCreated_at(date('Y-m-d H:i:s'));
        $model->setUpdated_at(date('Y-m-d H:i:s'));
        $model->setType($type);
        $model->setName($companyName);
        $res = $mapper->insert($model);
        if(!$res){
            return $this->returnData('开户失败，请重试',21004);
        }
        return $this->returnData('开户成功',21001,true);
    }


    /**
     * 用户充值
     * @return false
     */
    public function rechargeAction(){
        $userid = $this->getParam('userid',0,'int');
        $recharge = $this->getParam('recharge',0,'int');
        if(empty($recharge)){
            return $this->returnData('请输入正确的充值数目',21006);
        }
        $mapper = \Mapper\UsersModel::getInstance();
        $user = $mapper->findById($userid);
        if(!$user instanceof \UsersModel){
            return $this->returnData('充值用户不存在',21007);
        }
        $update = array(
            'normal_balance'=>'normal_balance+'.$recharge,
            'marketing_balance'=>'marketing_balance+'.$recharge,
            'show_normal_balance'=>'show_normal_balance+'.$recharge,
            'show_marketing_balance'=>'show_marketing_balance+'.$recharge,
            );
        $where = array('id'=>$userid);
        $res = $mapper->update($update,$where);
        if(!$res){
            return $this->returnData('充值失败，请重试!',21008);
        }
        return $this->returnData('充值成功',21009,true);
    }


}
