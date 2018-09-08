<?php

namespace Cron;

class Sms extends \Cron\CronAbstract {

    public function main() {
        $mapper = \Mapper\QueueModel::getInstance();
        $begin = time();
        $business = \Business\SmsModel::getInstance();
        while(time() - $begin <60){
            $model = $mapper->pull();
            if(!$model instanceof \QueueModel){
                sleep(1);
                continue;
            }
            if($this->locked($model->getId(),__CLASS__,__FUNCTION__,1200) === false){
                sleep(1);
                continue;
            }
            $this->start($model->getId());
            $user = \Mapper\UsersModel::getInstance()->findById($model->getUser_id());
            $task = \Mapper\SendtasksModel::getInstance()->findById($model->getTask_id());
            $business->setMobiles($model->setMobiles());
            if(!empty($model->getSendTime())){
                $business->setSendTime($model->setSendTime());
            }
            $res = $business->sendAll($user,$task);
            if(!$res){
                $msg = $business->getMessage();
                $this->log($model->getId().'-'.$msg['msg']);
                $model->setStatus(3);
                $model->setCode($msg['code']);
                $model->setMsg($msg['msg']);
                $model->setUpdate_time(date('YmdHis'));
                $mapper->update($model);
                continue;
            }
            $model->setBatchId($res['batchId']);
            $model->setStatus(2);
            $model->setUpdate_time(date('YmdHis'));
            $mapper->update($model);
            $this->success($model->getId());
        }
        return false;
    }


    /**发送队列转订单记录
     * @return bool
     */
    public function addOrder(){
        $mapper = \Mapper\QueueModel::getInstance();
        $begin = time();
        $orderMapper = \Mapper\SmsorderModel::getInstance();
        while(time() - $begin <60) {
            $model = $mapper->pullSuccess();
            if (!$model instanceof \QueueModel) {
                sleep(1);
                continue;
            }
            if ($this->locked($model->getId(), __CLASS__, __FUNCTION__, 1200) === false) {
                sleep(1);
                continue;
            }
            $this->start($model->getId());
            $mobiles = explode(',',$model->getMobiles());
            $order= new \SmsorderModel();
            $order->setStatus(0);
            $order->setBatchId($model->getBatchId());
            $order->setUser_id($model->getUser_id());
            $order->setTask_id($model->getTask_id());
            $order->setCreate_time($model->getSendTime());
            if($model->getIsfail()){
                $order->setUid(0);
                $order->setResult('FAIL');
                $order->setStatus(1);
            }
            foreach ($mobiles as $mobile){
                $orderMapper->begin();
                $exist = $orderMapper->fetch(array('mobile'=>$mobile,'batchId'=>$model->getBatchId()));
                if($exist instanceof \SmsorderModel){
                    continue;
                }
                $order->setMobile($mobile);
                if($model->getSys_send() == 1){
                    $order->setShow_mobile(substr_replace($mobile,'******',2,-3));
                }else{
                    $order->setShow_mobile($mobile);
                }
                $orderMapper->insert($order);
                $orderMapper->commit();
            }
            $model->setStatus(12);
            $this->success($model->getId());
        }
        return false;
    }

}
