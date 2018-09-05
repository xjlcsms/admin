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


}
