<?php
/**
 * Created by PhpStorm.
 * User: chendongqin
 * Date: 18-9-1
 * Time: 下午8:08
 */
namespace Business;

final class SmsModel  extends \Business\AbstractModel{

    use \Base\Model\InstanceModel;

    private $_mobiles = '';
    private $_dataJson = '';
    private $_sendTime = '';

    public function sendAll(\UsersModel $user,\SendtasksModel $task ,$useTemplate = false,$jsonData = false){
        if(empty($task->getSign())){
            return $this->getMsg(21402,'未设置签名');
        }
        $smser = new \Ku\Sms\Adapter('chuangrui');
        $driver = $smser->getDriver();
        $driver->setSign('【'.$task->getSign().'】');
        if($useTemplate === true){
            if(empty($task->getTemplate_id())){
                return $this->getMsg(21401,'使用模板发送,短信发送未设置模板ID');
            }
            $driver->setTemplateId($task->getTemplate_id());
        }
        if($jsonData === true){
            if(empty($this->_dataJson)){
                return $this->getMsg(21400,'未设置dataJson');
            }
            $driver->setData($jsonData);
        }else{
            if(empty($this->_mobiles)){
                return $this->getMsg(21403,'未设置发送手机号');
            }
            if(empty($task->getContent())){
                return $this->getMsg(21404,'未设置');
            }
            $driver->setContent($task->getContent());
        }
    }

    public function setSendTime($sendTime){
        $this->_sendTime = $sendTime;
    }

    public function setMobiles($mobiles){
        $this->_mobiles = $mobiles;
        return $this;
    }

    public function setDataJson($dataJson){
        $this->_dataJson = $dataJson;
        return $this;
    }

    public function errorChuangRui($code){
        $error = '未知错误';
        if(isset(self::$chuangruiCode[$code])){
            $error = self::$chuangruiCode[$code];
        }
        return $error;
    }

    public function sendErrorCode($code){
        $error = '未知错误';
        if(isset(self::$sendErrorCode[$code])){
            $error = self::$sendErrorCode[$code];
        }
        return $error;
    }

    private static $chuangruiCode = array(
        9001=>'签名格式不正确',
        9002=>'参数未赋值',
        9003=>'手机号码格式不正确',
        9006=> '用户accessKey不正确',
        9007=> 'IP白名单限制',
        9009=> '短信内容参数不正确',
        9010=> '用户短信余额不足',
        9011=> '用户帐户异常',
        9012=> '日期时间格式不正确',
        9013=> '不合法的语音验证码，4~8位的数字',
        9014=> '超出了最大手机号数量',
        9015=> '不支持的国家短信',
        9016=> '无效的签名或者签名ID',
        9017=> '无效的模板ID',
        9018=> '单个变量限制为1-20个字',
        9019=> '内容不可以为空',
        9021=> '主叫和被叫号码不能相同',
        9022=> '手机号码不能为空',
        9023=> '手机号码黑名单',
        9024=> '手机号码超频',
        10001=> '内容包含敏感词',
        10002=> '内容包含屏蔽词',
        10003=> '错误的定时时间',
        10004=> '自定义扩展只能是数字且长度不能超过4位',
        10005=> '模版类型不存在',
        10006=> '模版和内容不匹配',
    );

    private static $sendErrorCode = array(
        'BLACK' =>'黑名单号码',
        'CA:0051'=> '尚未建立连接;移动内部错误',
        'CA:0052'=> '尚未成功登录;移动内部错误',
        'CA:0054'=> '超时未接收到响应消息;移动内部错误',
        'CA:0111'=> 'SCP厂家自定义的错误码;移动内部错误',
        'CB:0001'=> '非神州行预付费用户;号码无效或者空号',
        'CB:0005'=> 'PPS用户状态异常（包括未头次使用、储值卡被封锁、储值卡进入保留期、储值卡挂失）;移动用户帐户数据异常',
        'CB:0007'=> '用户余额不足;不能扣费，影响包月话单',
        'CB:0016'=> '参数错误;移动内部错误',
        'CB:0018'=> '重复发送消息序列号msgid相同的计费请求消息;移动内部错误'
    );

}