<?php
/**
 * Created by PhpStorm.
 * User: chendongqin
 * Date: 18-9-2
 * Time: 下午11:19
 */
class SendController extends \Base\ApplicationController{



    public function indexAction(){
        $where = ['status'=>0,'type'=>2];
        $uwhere = [];
        $username = $this->getParam('username','','string');
        $company = $this->getParam('company','','string');
        if(!empty($username)){
            $uwhere[] = "username like '%".$username."%'";
        }
        if(!empty($company)){
            $uwhere[] = "name like '%".$company."%'";
        }
        if(!empty($uwhere)){
            $userids = \Mapper\UsersModel::getInstance()->fetchAll($uwhere,null,0,0,array('id'),false);
            $ids = [];
            foreach ($userids as $userid){
                $ids = $userid['id'];
            }
            if(empty($ids)){
                $where[] = '1=2';
            }else{
                $where[] = 'user_id in('.implode(',',$ids).')';
            }
        }
        $mapper = \Mapper\SendtasksModel::getInstance();
        $select = $mapper->select();
        $select->where($where);
        $select->order(array('created_at desc'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $mapper->getAdapter());
        $this->assign('pager', $pager);
        $this->assign('pagelimit', $pagelimit);
        $this->assign('username',$username);
        $this->assign('company',$company);
        $this->assign('sendTypes',$mapper->getSendTypes());
    }

    /**
     * 处理记录
     */
    public function dealAction(){
        $mapper = \Mapper\SendtasksModel::getInstance();
        $where = array();
        $uwhere = [];
        $userId = $this->getParam('userid','','int');
        if(!empty($userId)){
            $where['user_id'] = $userId;
        }
        $this->assign('userid',$userId);
        $company = $this->getParam('company','','string');
        if(!empty($company)){
            $uwhere[] = "company like '%".$company."%'";
        }
        if(!empty($uwhere)){
            $userids = \Mapper\UsersModel::getInstance()->fetchAll($uwhere,null,0,0,array('id'),false);
            $ids = [];
            foreach ($userids as $userid){
                $ids = $userid['id'];
            }
            if(empty($ids)){
                $where[] = '1=2';
            }else{
                $where[] = 'user_id in('.implode(',',$ids).')';
            }
        }
        $time = $this->getParam('time','','string');
        if(!empty($time)){
            $timeArr = explode('-',$time);
            $begin = date('Y-m-d H:i:s',strtotime(trim($timeArr[0])));
            $end = date('Y-m-d H:i:s',strtotime(trim($timeArr[1])));
            $where[] = "created_at >='".$begin." 00:00:00' and created_at <= '".$end." 23:59:59'";
        }
        $type = $this->getParam('type','','int');
        if(!empty($type)){
            $where['sms_type'] = $type;
        }
        $sign = $this->getParam('sign','','string');
        if(!empty($sign)){
            $where[] = "sign like'%".$sign."%'";
        }
        $content = $this->getParam('content','','string');
        if(!empty($content)){
            $where[] = "content like'%".$sign."%'";
        }
        $select = $mapper->select();
        $select->where($where);
        $select->order(array('created_at deac'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $mapper->getAdapter());
        $this->assign('pager', $pager);
        $this->assign('pagelimit', $pagelimit);
        $this->assign('time',$time);
        $this->assign('company',$company);
        $this->assign('sign',$sign);
        $this->assign('type',$type);
        $this->assign('content',$content);
        $this->assign('sendTypes',$mapper->getSendTypes());
        $users = \Mapper\UsersModel::getInstance()->fetchAll(array('isdel'=>0));
        $userData = [];
        foreach ($users as $user){
            $userData[$user->getId()] = $user->getUsername();
        }
        $this->assign('users',$userData);
        $this->assign('status',$mapper->getStatus());
    }


    /**发送界面
     * @throws ErrorException
     */
    public  function sendAction(){
        $taskid = $this->getParam('id',1,'int');
        $mapper = \Mapper\SendtasksModel::getInstance();
        $task = $mapper->fetch(array('id'=>$taskid,'status'=>0,'type'=>2));
        if(!$task instanceof \SendtasksModel){
            throw new ErrorException('发送任务不存在');
        }
        $this->assign('task',$task->toArray());
        if($task->getIs_template() == 1){
            $temp = \Mapper\TemplatesModel::getInstance()->fetch(array('Id'=>$task->getTemplate_id(),'status'=>1));
            if($temp instanceof \TemplatesModel){
                $this->assign('temp',$temp->toArray());
            }
        }
        $this->assign('sendTypes',$mapper->getSendTypes());
    }


    /**短信发放入队列
     * @return false
     * @throws Exception
     */
    public function smsAction(){
        $type = $this->getParam('type',0,'int');
        $smstype = $this->getParam('smstype',0,'int');
        $taskid = $this->getParam('taskid',0,'int');
        $content = $this->getParam('content','','string');
        $sign = $this->getParam('sign','','string');
        $sendTime = $this->getParam('sendTime','','string');
        $sendTimeType = $this->getParam('sendTimeType',0,'int');
        if($sendTimeType !=0 and strtotime($sendTime)<time()+1800){
            return $this->returnData('预发送时间需设置在半小时以上');
        }
        $task = \Mapper\SendtasksModel::getInstance()->findById($taskid);
        if(!$task instanceof \SendtasksModel){
            return $this->returnData('发送任务不存在',29204);
        }
        $user = \Mapper\UsersModel::getInstance()->findById($task->getUser_id());
        if(!$user instanceof \UsersModel){
            return $this->returnData('发送任务用户不存在',29205);
        }
        $smsBusiness = \Business\SmsModel::getInstance();
        if($smstype == 1){
            $smsfile = $this->getParam('smsfile','','string');
            if(!file_exists(APPLICATION_PATH.'/public/uploads/sms/'.$smsfile) || empty($smsfile)){
                return $this->returnData('发送文件不存在',29200);
            }
            $mobiles = $smsBusiness->importMobiles($smsfile);
        }else{
            $mobilesStr = $this->getParam('mobiles','','string');
            $mobiles = explode(',',$mobilesStr);
        }
        if(empty($mobiles)){
            return $this->returnData('没有获取到有效的手机号',29202);
        }
        //发送的总数
        $fee = $smsBusiness->oneFee($content);
        $total = count($mobiles);
        $account = $type == 3?'market_balance':'normal_balance';
        $virefy = $smsBusiness->virefy($user,$fee,$total,$account);
        if(!$virefy){
            $message = $smsBusiness->getMessage();
            return $this->returnData($message['msg'],$message['code']);
        }
        $data = $smsBusiness->trueMobiles($user,$mobiles);
        $mobiles = $data['true'];
        $mobiles = $smsBusiness->divideMobiles($mobiles);
        $smsMapper = \Mapper\QueueModel::getInstance();
        $smsMapper->begin();
        $model = new \QueueModel();
        $model->setTask_id($taskid);
        $model->setType($type);
        $model->setUser_id($user->getId());
        if($sendTimeType != 0){
            $sendTime = date('Y-m-d H:i:s',strtotime($sendTime));
            $model->setSendTime($sendTime);
        }
        foreach ($mobiles as $mobile){
            $model->setCreate_time(date('Ymdhis'));
            $mobileStr = implode(',',$mobile);
            $model->setMobiles($mobileStr);
            $model->setSend_num(count($mobile));
            $res = $smsMapper->insert($model);
            if($res === false){
                $smsMapper->rollback();
                return $this->returnData('发送失败',29200);
            }
        }
        if(!empty($data['fail'])){
            $failMobiles = $smsBusiness->divideMobiles($data['fail']);
            foreach ($failMobiles as $mobile){
                $model->setCreate_time(date('Ymdhis'));
                $mobileStr = implode(',',$mobile);
                $model->setMobiles($mobileStr);
                $model->setSend_num(count($mobile));
                $model->setIsfail(1);
                $model->setStatus(2);
                $res = $smsMapper->insert($model);
                if($res === false){
                    $smsMapper->rollback();
                    return $this->returnData('发送失败',29200);
                }
            }
        }
        $userBusiness = \Business\UserModel::getInstance();
        $res = $userBusiness->flow($user,0,$total*$fee,$account);
        if(!$res){
            $msg = $userBusiness->getMessage();
            return $this->returnData($msg['msg'],29202);
        }
        $task->setStatus(1);
        $task->setContent($content);
        $task->setSign($sign);
        $task->setUpdated_at(date('Y-m-d H:i:s'));
        \Mapper\SendtasksModel::getInstance()->update($task);
        $smsMapper->commit();
        return $this->returnData('发送成功',29201,true);
    }


//    public function testAction(){
//        $business = \Business\SmsModel::getInstance();
//        $user = \Mapper\UsersModel::getInstance()->findById(1);
//        $task = \Mapper\SendtasksModel::getInstance()->findById(1);
//        $business->setMobiles(13386936061);
//        $res = $business->sendAll($user,$task);
//        if(!$res){
//            $msg = $business->getMessage();
//            var_dump($msg);
//        }
//        var_dump($res);
//    }


    /**批量发送文件上传
     * @return false
     * @throws Exception
     * @throws \PHPExcel\PHPExcel\Reader_Exception
     */
    public function smsfileAction(){
        $fileInfo = $_FILES['smsfile'];
        $taskid = $this->getParam('taskid',0,'int');
        $task = \Mapper\SendtasksModel::getInstance()->findById($taskid);
        if(!$task instanceof \SendtasksModel){
            return $this->returnData('发送任务不存在',291004);
        }
        if (empty($fileInfo)) {
            return $this->returnData('没有文件上传！', 29100);
        }
        $name=explode('.',$fileInfo['name']);
        $lastName=end($name);
        if(strtolower($lastName) != 'xls' and strtolower($lastName) !='xlsx' and strtolower($lastName) !='xlsb'){
            return $this->returnData('上传文件格式必须为/xls/xlsx/xlsb等文件！', 28101);
        }
        if ($fileInfo['error'] > 0) {
            $errors = array(1=>'文件大小超过了PHP.ini中的文件限制！',2=>'文件大小超过了浏览器限制！',3=>'文件部分被上传！','没有找到要上传的文件！',4=>'服务器临时文件夹丢失，请重新上传！',5=>'文件写入到临时文件夹出错！');
            $error = isset($errors[$fileInfo['error']])?$errors[$fileInfo['error']]:'未知错误！';
            return $this->returnData($error, 29102);
        }
        $d = date("YmdHis");
        $randNum = rand((int)50000000, (int)10000000000);
        $filesname = $d . $randNum . '_'.$taskid.'.' .$lastName;
        $dir = APPLICATION_PATH . '/public/uploads/sms/';
        if(!file_exists($dir)){
            \Ku\Tool::makeDir($dir);
        }
        if (!copy($fileInfo['tmp_name'], $dir. $filesname)) {
            return $this->returnData('文件上传失败！', 29103);
        }
        try{
            $read = \PHPExcel\IOFactory::createReader('Excel2007');
            $obj = $read->load($dir. $filesname);
            $dataArray =$obj->getActiveSheet()->toArray();
            $mobiles = [];
            $fail = 0;
            unset($dataArray[0]);
            foreach ($dataArray as $key=> $item){
                if(!\Ku\Verify::isMobile($item[0])){
                    $fail++;
                    continue;
                }
                $mobiles[] = $item[0];
            }
            $isMobile = count($mobiles);
            $mobiles = array_unique($mobiles);
            $true = count($mobiles);
            $key = md5($filesname);
            $redis = $this->getRedis();
            $redis->set($key,json_encode($mobiles),3600*2);
            return $this->returnData('文件上传成功！', 29101,true,array('filename'=>$filesname,'total'=>$fail+$isMobile,'true'=>$true,'repeat'=>$isMobile-$true,'fail'=>$fail));
        }catch (ErrorException $errorException){
            return $this->returnData('读取文件错误',29105);
        }
    }


    /**文件删除
     * @return false
     * @throws Exception
     */
    public function delsmsfAction(){
        $fileName = $this->getParam('fileName','','string');
        $dir = APPLICATION_PATH.'/public/uploads/sms/'.$fileName;
        if(file_exists($dir)){
            @unlink($dir);
        }
        $redis = $this->getRedis();
        $redis->del(md5($fileName));
        return $this->returnData('删除成功',29011,true);
    }

    /**
     * 下载批量短信模板
     */
    public function downtempAction(){
        header('Content-Type:application/xlsx');
        header('Content-Disposition:attachment;filename=sms_template.xlsx');
        header('Cache-Control:max-age=0');
        readfile(APPLICATION_PATH.'/data/sms_template.xlsx');
        exit();
    }


    /**任务驳回
     * @return false
     */
    public function rejectAction(){
        $taskid = $this->getParam('taskid',0,'int');
        $mapper = \Mapper\SendtasksModel::getInstance();
        $task = $mapper->findById($taskid);
        if(!$task instanceof \SendtasksModel){
            return $this->returnData('处理的任务不存在',29400);
        }
        $task->setStatus(4);
        $task->setUpdated_at(date('Y-m-d H:i:s'));
        $res = $mapper->update($task);
        if(!$res){
            return $this->returnData('驳回任务失败，请重试',29402);
        }
        return $this->returnData('处理成功',29401,true);
    }


    public function orderAction(){
        $mapper = \Mapper\SmsorderModel::getInstance();
        $mobile = $this->getParam('mobile','','string');
        $userId = $this->getParam('userId','','int');
        $taskId = $this->getParam('taskId','','int');
        $result = $this->getParam('result','','string');
        $where = array();
        if($mobile){
            $where['mobile'] = $mobile;
        }
        if($userId){
            $where['user_id'] = $userId;
        }
        if($taskId){
            $where['task_id'] = $taskId;
        }
        if($result){
            if($result == 1){
                $where['result'] = 'DELIVRD';
            }else{
                $where[] = "result != 'DELIVRD'";
            }
        }
        $select = $mapper->select();
        $select->where($where);
        $select->order(array('create_time desc'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $mapper->getAdapter());
        $this->assign('pager', $pager);
        $this->assign('result',$result);
        $this->assign('taskId',$taskId);
        $this->assign('userId',$userId);
        $this->assign('mobile',$mobile);
    }


}