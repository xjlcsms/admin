<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/7/5
 * Time: 0:07
 */
//27000
class TemplateController extends \Base\ApplicationController
{
    private $_templateStatus = array('待审核','审核通过','审核不通过');
    private $_templateTypes = array(1=>'固定模板',2=>'变量模板');
    private $_accounts = array(1=>'行业短信',2=>'营销短信',3=>'特殊短信');

    /**
     * 模板列表
     */
    public function indexAction(){
        $where = [];
        $status = $this->getParam('status',100,'int');
        $type = $this->getParam('type',0,'int');
        $sign = $this->getParam('sign','','string');
        $userId = $this->getParam('userId','','int');
        if($status !=100){
            $where['status'] = $status;
        }
        if($type){
            $where['type'] = $type;
        }
        if($sign){
            $where[] = "sign like '%".$sign."%'";
        }
        if($userId){
            $where['user_id'] = $userId;
        }
        $mapper = \Mapper\TemplatesModel::getInstance();
        $select = $mapper->select();
        $select->where($where);
        $select->order(array('created_at desc'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $mapper->getAdapter());
        $this->assign('pager', $pager);
        $this->assign('userId', $userId);
        $this->assign('status', $status);
        $this->assign('type', $type);
        $this->assign('sign', $sign);
        $this->assign('types',$this->_templateTypes);
        $this->assign('templateStatus',$this->_templateStatus);
        $this->assign('accounts',$this->_accounts);
    }


    /**
     * 模板审核
     * @return false
     */
    public function aduitAction(){
        $id = $this->getParam('id',0,'int');
        $audit = $this->getParam('audit','not_adopted','string');
        $reason = $this->getParam('reason','','string');
        $mapper = \Mapper\TemplatesModel::getInstance();
        $template = $mapper->fetch(array('id'=>$id,'status'=>0));
        if(!$template instanceof \TemplatesModel){
            return $this->returnData('该审核模板未找到',27003);
        }
        if($audit !='not_adopted' && $audit !='adopted' ){
            return $this->returnData('审核结果不正确',27000);
        }
        if($audit == 'not_adopted' && empty($reason)){
            return $this->returnData('请输入不通过原因',27002);
        }
        $template->setReason($reason);
        $status = $audit == 'adopted'?1:2;
        $template->setStatus($status);
        $template->setUpdated_at(date('Y-m-d H:i:s'));
        $res = $mapper->update($template);
        if(!$res){
            return $this->returnData('审核失败，请重试！',27004);
        }
        return $this->returnData('审核成功',27001,true);
    }

}