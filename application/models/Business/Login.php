<?php

/**
 * Description of Login
 *
 * @author wuzhihua
 */

namespace Business;

class LoginModel  extends \Business\AbstractModel{

    use \Base\Model\InstanceModel;
    
    
    /**
     * 是否是 “记住登录” 登录
     *
     * @var boolean
     */
    protected $_isRemberLogin = false;

    /**
     * 当前登录MID
     *
     * @var int
     */
    protected $_mid = 0;
    /**
     * 记住时间
     *
     * @var int
     */
    protected $_savetime = 2592000;
    
    
    protected $_member = null;

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    private function __sleep() {
        
    }

    /**
     * @return \Ku\Login
     */
    public static function getInstance() {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * 是否是记住登录的用户
     * 
     * @return boolean
     */
    public function isRemberLogin() {
        return (bool) $this->_isRemberLogin;
    }
    /**
     * 用户UID
     * UID 用户可用起始为65535， 65535前的数字保留
     *
     * @return int
     */
    public function getMid() {
        static $mid = 0;
        if ($mid <= 0) {
            $mid = $this->_getMid();

            if ($mid <= 0 && $this->checkRemberLogin() === true) {
                $mid = $this->_getMid();
            }
        }

        return $mid;
    }

    /**
     * Username
     *
     * @return string
     */
    public function getUsername() {
        $model = $this->getCurrentUser();

        return (($model instanceof \MemberModel) ? $model->getUsername() : null);
    }
    

    /**
     * 当前登录用户
     *
     * @return \MemberModel|null
     */
    public function getCurrentUser() {
        $mid = $this->_getMid();
        if ($mid <= 0) {
            return null;
        }
        if($this->_member === null){
            $this->_member = \Mapper\MemberModel::getInstance()->findById($mid);
        }
        return $this->_member;
    } 
    
    /**
     * 用户登录验证
     *
     * @param string $username
     * @param string $password
     * @param string $secure
     * @return bool
     */
    public function login($username, $password, $remember, $secure = null) {
        if (empty($username) || empty($password)) {
            return $this->getMsg(23201, false);
        }

        if (\Ku\Verify::isMobile($username) === false) {
            return $this->getMsg(23202, false);
        }

        $memberMapper = \Mapper\MemberModel::getInstance();
        $memberModel = $memberMapper->findByMobile($username);

        if (!$memberModel instanceof \MemberModel || \Ku\Tool::valid($password, $memberModel->getPassword(), $secure) === false) {
            return $this->getMsg(23203, false);
        }

        $lastTime = $memberModel->getLast_time();

        if ($memberModel->getDisabled() === 1) {
            return $this->getMsg(23204, false);
        }
        $memberMapper->lastLogin($memberModel);
        $this->setLogin($memberModel->getId(), array(\Ku\Consts::LAST_LOGIN_TIME => $lastTime));
        $this->_member = $memberModel;
        $this->rememberlogin($remember);

        return true;
    }

    /**
     * 退出登录
     *
     * @return boolean
     */
    public function logout() {
        $session = \Yaf\Session::getInstance();
        $session->del('mid');
        $session->del(\Ku\Consts::LAST_LOGIN_TIME);

        if (isset($_COOKIE['_umdata'])) {
            header("P3P: CP='CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR'");
            setcookie('_umdata', null, time() - 1, '/', null, false, true);
        }

        return true;
    }

    /**
     * 记住登录[仅member可以记住登录]
     */
    public function rememberLogin($remember) {
        if($remember){
             $userModel = $this->getCurrentUser();
          if ($userModel instanceof \MemberModel) {
            $data = array();
            $time = microtime(true);
            $rand = mt_rand(0, 16);
            $rstr = substr(strrev(sha1(substr($userModel->getPassword(), 28))), $rand, 16);
            $data['mid'] = (int) $userModel->getId();
            $data['una'] = sha1($userModel->getUsername());
            $data['urt'] = ((int) $time >> 8) + $rand;
            $data['urs'] = sha1($rstr . ':' . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unkwon'));
            $data['ult'] = $time;
            ksort($data);
            $data['code'] = $this->_sign($data);
            $umdata = \Ku\Tool::authCode($this->_implode($data, '&'), 'encode', \Yaf\Registry::get('config')->get('resource.user.rememberlogin.authcode'));
            header("P3P: CP='CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR'");
            setcookie('_umdata', $umdata, time() + $this->_savetime, '/', null, false, true);
        }
     }
    }

    /**
     * 检查已记住的登录
     *
     * @return boolean
     */
    public function checkRemberLogin() {
        if ($this->_getMid() > 0 || !isset($_COOKIE['_umdata'])) {
            return false;
        }

        $umdata = $_COOKIE['_umdata'];
        $string = \Ku\Tool::authCode($umdata, 'DECODE', \Yaf\Registry::get('config')->get('resource.user.rememberlogin.authcode'));
        $data = array();

        parse_str($string, $data);

        $memberId = (isset($data['mid'])) ? (int) $data['mid'] : 0;
        $signCode = (isset($data['code'])) ? $data['code'] : null;
        array_pop($data);

        if ($memberId <= 0 || !(strcmp($this->_sign($data), $signCode) === 0)) {
            return false;
        }

        $mapper = \Mapper\MemberModel::getInstance();
        $model = $mapper->findById($memberId);

        if ($model instanceof \MemberModel) {

            $ult = (isset($data['ult'])) ? (int) $data['ult'] : 0;
            $urt = (isset($data['urt'])) ? (int) $data['urt'] : 0;
            $urs = (isset($data['urs'])) ? trim($data['urs']) : null;
            $una = (isset($data['una'])) ? trim($data['una']) : null;
            $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unkwon';
            $rstr = substr(strrev(sha1(substr($model->getPassword(), 28))), $urt - ((int) $ult >> 8), 16);

            if (strcmp($una, sha1($model->getUsername())) === 0 &&
                    strcmp(sha1($rstr . ':' . $ua), $urs) === 0 &&
                    $this->_savetime > (time() - $ult) &&
                    $model->getDisabled() === 0
            ) {
                $this->setLogin($model->getId(), array());
                $this->_isRemberLogin = true;
                $this->_member = $model;
                return true;
            }
        } else {
            $this->logout();
            return false;
        }
    }

    /**
     * 设置登录
     *
     * @param int $mid 用户ID
     * @param string $from 登录用户类型
     * @return boolean
     */
    public function setLogin($mid, $other, $from = 'self') {
        $session = \Yaf\Session::getInstance();
        $session->set('mid', (int) $mid);
        $session->set('from',$from);
        if (!empty($other)) {
            foreach ($other as $key => $value) {
                $session->set($key, $value);
            }
        }

        return true;
    }

    /**
     * 签名
     *
     * @param string $string
     * @param array $dist
     * @return string
     */
    private function _sign(array $data) {
        return sha1($this->_implode($data) . ':' . \Yaf\Registry::get('config')->get('resource.member.rememberlogin.salt'));
    }

    /**
     * 数组组装
     *
     * @param array $data
     * @param string $gule
     * @return type
     */
    private function _implode(array $data, $gule = '') {
        $ret = array();

        foreach ($data as $key => $val) {
            $ret[] = $key . '=' . $val;
        }

        return implode($gule, $ret);
    }

    /**
     * MID
     *
     * @return int
     */
    private function _getMid() {
        return (int) (\Yaf\Session::getInstance()->get('mid'));
    }


    /**
     *
     * 注册流程
     */
    public function regFlow($mobile,$password,$email,$code){

        if (\Ku\Verify::isMobile($mobile) === false) {
            return $this->getMsg(23210,false);
        }
        if(strlen($password)<6){
            return $this->getMsg(23212,false);
        }
        $memberMapper = \Mapper\MemberModel::getInstance();
        $model = $memberMapper->findByMobile($mobile);
        if ($model instanceof \MemberModel) {
            return $this->getMsg(23213,false);
        }

        if (\Ku\Sender\Compare::sms($mobile, 'reg', $code) === false) {
            return $this->getMsg(23214,false);
        }
        return true;

    }


    public function getLoginUser(){

        $session = \Yaf\Session::getInstance();
        $info  = $session->get('userinfo');
        if($info){
            return $info;
        }else{
            $info = $this->checkOldLogin();
            if($info === false){
                return null;
            }
        }
        
        return $info;
    }


    public function checkOldLogin(){
        $userCookie = isset($_COOKIE['sfq_session'])?$_COOKIE['sfq_session']:0;
        if(empty($userCookie)){
            return false;
        }
        $cookieArray = unserialize($userCookie);
        $ip = \Ku\Tool::getClientIp();
        if($cookieArray['ip_address']!= $ip){
            return false;
        }
        if(\Ku\Tool::getClientUa() != $cookieArray['user_agent']){
            return false;
        }
        $mapper = \Mapper\SfqsessionModel::getInstance();
        $user = $mapper->findBySession_id($cookieArray['session_id']);
        if($user instanceof \SfqsessionModel){
            $session = \Yaf\Session::getInstance();
            $info = unserialize($user->getUser_data());
            $cookieArray['user'] = $info;
            $session->set('userinfo',$cookieArray);
            return $cookieArray;
        }
        return false;
    }
}
