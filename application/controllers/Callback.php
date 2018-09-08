<?php
/**
 * Created by PhpStorm.
 * User: chendongqin
 * Date: 18-9-5
 * Time: 下午9:15
 */

class CallbackController extends \Base\ApplicationController{

    public function indexAction(){

    }

    /**短信回执接收
     * @return bool
     */
    public function reportAction(){
        $this->disableLayout();
        $this->disableView();
        $request =  $request = $this->getRequest();
        $parmas = $request->getPost();
        $gParam = $request->getQuery();
        $parmas = array_merge($parmas, $gParam);
        \Ku\Log\Adapter::getInstance()->Applog(array(json_encode($parmas), __CLASS__, __FUNCTION__, __LINE__));
        if(!isset($parmas['smUuid'])||!isset($parmas['deliverTime'])||!isset($parmas['deliverResult'])||!isset($parmas['mobile'])||!isset($parmas['batchId'])){
            return $this->returnPost(array('code'=>2,'msg'=>'FAIL'));
        }
        $mapper = \Mapper\SmsorderModel::getInstance();
        $exist = $mapper->fetch(array('mobile'=>$parmas['mobile'],'batchId'=>$parmas['batchId']));
        $mapper->begin();
        if($exist instanceof \SmsorderModel){
            $exist->setResult($parmas['deliverResult']);
            $exist->setDeliver_time(date('YmdHis',strtotime($parmas['deliverTime'])));
            $exist->setUid($parmas['smUuid']);
            $exist->setStatus(1);
            $res = $mapper->update($exist);
        }else{
            $order = new \SmsorderModel();
            $queue = \Mapper\QueueModel::getInstance()->findByBatchId($parmas['batchId']);
            $order->setBatchId($queue->getBatchId());
            $order->setUser_id($queue->getUser_id());
            $order->setTask_id($queue->getTask_id());
            $order->setCreate_time($queue->getCreate_time());
            $order->setMobile($parmas['mobile']);
            if($queue->getSys_send() == 1){
                $order->setShow_mobile(substr_replace($parmas['mobile'],'******',2,-3));
            }else{
                $order->setShow_mobile($parmas['mobile']);
            }
            $order->setResult($parmas['deliverResult']);
            $order->setDeliver_time(date('YmdHis',strtotime($parmas['deliverTime'])));
            $order->setUid($parmas['smUuid']);
            $order->setStatus(1);
            $res = $mapper->insert($order);
        }
        if($res === false){
            $mapper->rollback();
            return $this->returnPost(array('code'=>1,'msg'=>'FAIL'));
        }
        $data = array('code'=>0,'msg'=>'SUCCESS');
        $mapper->commit();
        return $this->returnPost($data);
    }

    /**
     * 回复消息接收
     */
    public function receiveAction(){
        $this->disableLayout();
        $this->disableView();
        $request =  $request = $this->getRequest();
        $parmas = $request->getPost();
        $gParam = $request->getQuery();
        $parmas = array_merge($parmas, $gParam);
        \Ku\Log\Adapter::getInstance()->Applog(array(json_encode($parmas), __CLASS__, __FUNCTION__, __LINE__));
        if(!isset($parmas['content'])||!isset($parmas['receiveTime'])||!isset($parmas['extNo'])||!isset($parmas['mobile'])){
            return $this->returnPost(array('code'=>2,'msg'=>'FAIL'));
        }
        $mapper = \Mapper\ReceivemsgModel::getInstance();
        $mapper->begin();
        $msg = new \ReceivemsgModel();
        $msg->setMobile($parmas['mobile']);
        $msg->setContent($parmas['content']);
        $msg->setReceive_time($parmas['receiveTime']);
        $msg->setExtNo($parmas['extNo']);
        $res = $mapper->insert($msg);
        if($res === false){
            $mapper->rollback();
            return $this->returnPost(array('code'=>1,'msg'=>'FAIL'));
        }
        $data = array('code'=>0,'msg'=>'SUCCESS');
        $mapper->commit();
        return $this->returnPost($data);
    }

    /**回调返回结果
     * @param $data
     * @return bool
     */
    public function returnPost($data){
        header('Content-type: application/json; charset=utf-8');
        echo \json_encode($data,JSON_UNESCAPED_UNICODE );
        return false;
    }


}