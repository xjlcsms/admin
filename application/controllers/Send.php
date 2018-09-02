<?php
/**
 * Created by PhpStorm.
 * User: chendongqin
 * Date: 18-9-2
 * Time: 下午11:19
 */
class SendController extends \Base\ApplicationController{



    public function indexAction(){

    }


    public  function sendAction(){

    }


    public function testAction(){
        $business = \Business\SmsModel::getInstance();
        $user = \Mapper\UsersModel::getInstance()->findById(1);
        $task = \Mapper\SendtasksModel::getInstance()->findById(1);
        $business->setMobiles(13386936061);
        $res = $business->sendAll($user,$task);
        if(!$res){
            $msg = $business->getMessage();
            var_dump($msg);
        }
        var_dump($res);
    }

}