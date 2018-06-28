<?php
class iController extends \Base\AbstractController
{
    
    /**
     * 自动开启视图引擎及布局模块
     *
     * @var boolean
     */
    protected $autoRender = false;
    protected $autoLayout = false;
    public function indexAction()
    {
    }
    
    /**
     * 展示验证码图片
     */
    public function captchaAction()
    {
         $channel = $this->getParam('channel',null);
        $limitKey = 'user.resource.captchalimit.' . \Ku\Tool::getClientIp(true);
        
        if ($this->useLimit($limitKey, 10, 60) === true) {
            sleep(3);
        }
        $userKey = sprintf(\Ku\Consts::USING_CAPTCHA_CHANNEL,$channel);
        $userKeyTime = sprintf(\Ku\Consts::USING_CAPTCHA_CHANNEL_TIME,$channel);
        
        $captcha = \Ku\Captcha\Captcha::getInstance();
        $captcha->setHeight(40)->setWidth(98);
        $captcha->setInterfere('pixel')->setRandLength(4)->create();
        $captchVal = $captcha->getCaptcha();
        $session = \Yaf\Session::getInstance();       
        $session->set($userKey, $captchVal);
        //一个验证码有验证次数限制，超过会被要求刷新
        $session->set($userKeyTime,0);
        $captcha->show();
        return false;
    }
    
    /**
     * 检查邮箱是否占用
     *
     * @return boolean
     */
    public function preEmailAction()
    {
        $email = $this->getRequest()->get('email', null);
        
        if (empty($email) || \Ku\Verify::isEmail($email) === false) {
            $this->returnData('请输入正确的密保邮箱', 31100);
            return false;
        }
        
        $ip = \Ku\Tool::getClientIp(true);
        
        if ($this->useLimit(sprintf(\Ku\Consts::EMAIL_IKEY, $ip), 6, 1800) === true) {
            $this->returnData('通信错误', 22100);
            return false;
        }
        
        $this->returnData('密保邮箱已被其他会员占用', 31103, $this->checkEmail($email));
        return false;
    }
    public function premobileAction()
    {
        $username = $this->getRequest()->get('username', null);
        $callback = $this->getRequest()->get('callback', null);
        if (empty($username) || \Ku\Verify::isMobile($username) === false) {
            // 判断是否跨域，来决定采用json 或 jsonp
//          if(stripos($_SERVER['HTTP_HOST'],'my') != false){
//              $this->returnData ( '手机格式有误', 31100 );
//          }else{
                $this->returnData('手机格式有误', 31100, false, null, $callback);
//          }
            return false;
        }
        
        $ip = \Ku\Tool::getClientIp(true);
        
        if ($this->useLimit(sprintf(\Ku\Consts::MOBILE_IKEY, $ip), 10, 1800) === true) {
            $this->returnData('操作太频繁了，请稍候重试', 31101, false, null, $callback);
            return false;
        }
        $result = $this->checkMobile($username);
        if ($result == false) {
            return $this->returnData('用户不存在', 31103, false, null, $callback);
        }
        if ($this->isWeixin()) {
            $MerResult = $this->checkMerchant($username);
            if ($MerResult == false) {
                return $this->returnData('您不是商户', 31103, false, null, $callback);
            }
        }
        
        return $this->returnData(null, 31104, true, null, $callback);
    }
    
    public function showCaptchaAction()
    {
        $callback = \Ku\Tool::filter($this->getRequest()->get('callback', null));
        $result = $this->useCaptcha() === true ? 1 : 0 ;
        $this->returnData($result==0?'不需要显示图形验证码':'需要显示图形验证码', 10000, (bool)$result, array('using_captcha'=>$result), $callback);
    }
}
