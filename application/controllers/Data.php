<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/7/1
 * Time: 21:10
 */
//25000
class DataController extends \Base\ApplicationController
{

    /**
     * 获取待审核的模板数量
     * @return false
     */
    public function tempnumAction(){
        $templatesMapper = \Mapper\TemplatesModel::getInstance();
        $templateWhere = array('status'=>0);
        $num = (int)$templatesMapper->count($templateWhere);
        return $this->returnData('获取成功',25000,true,array('tempnum'=>$num));
    }

    /**
     * 获取待处理的任务数量
     * @return false
     */
    public function jobnumAction(){
        $mapper = \Mapper\SendtasksModel::getInstance();
        $where = array('status'=>0);
        $num = $mapper->count($where);
        return $this->returnData('获取成功',25001,true,array('jobnum'=>$num));
    }

    /**
     * 统计当天发送的短信数据
     * @return false
     */
    public function daysendAction(){
        $mapper =  \Mapper\SendtasksModel::getInstance();
        $date = date('Y-m-d');
        $generalWhere = array('sms_type in(1,2)','status in(1,2)',"updated_at >= '".$date." 00:00:00'","updated_at <= '".$date." 23:59:59'");
        $marketWhere = array('sms_type'=>3,'status in(1,2)',"updated_at >= '".$date." 00:00:00'","updated_at <= '".$date." 23:59:59'");
        $generalSend = $mapper->sum('billing_amount',$generalWhere);
        $marketSend = $mapper->sum('billing_amount',$marketWhere);
        $data = array('generalSend'=>$generalSend,'marketSend'=>$marketSend);
        return $this->returnData('获取成功',25002,true,$data);
    }

    /**
     * 获取当天的充值额度
     * @return false
     */
    public function dayrechargeAction(){
        $mapper = \Mapper\RechargerecordsModel::getInstance();
        $date = date('Y-m-d');
        $generalWhere = array('direction'=>1,'type'=>1,"created_at >= '".$date." 00:00:00'","created_at <= '".$date." 23:59:59'");
        $marketWhere = array('direction'=>1,'type'=>2,"created_at >= '".$date." 00:00:00'","created_at <= '".$date." 23:59:59'");
        $general = (float)$mapper->sum('amount',$generalWhere);
        $market = (float)$mapper->sum('amount',$marketWhere);
        $data = array('general'=>$general,'market'=>$market);
        return $this->returnData('获取成功',25003,true,$data);
    }

}