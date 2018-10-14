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
//    protected $_savetime = 2592000;
    protected $_savetime = 604800;

    
    protected $_member = null;

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    private function __sleep() {
        
    }

    /**
     * @return LoginModel|null
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

        return (($model instanceof \AdminModel) ? $model->getUsername() : null);
    }
    

    /**
     * 当前登录用户
     *
     * @return \AdminModel|null
     */
    public function getCurrentUser() {
        $mid = $this->getMid();
        if ($mid <= 0) {
            return null;
        }
        if($this->_member === null){
            $this->_member = \Mapper\AdminModel::getInstance()->findById($mid);
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

        $memberMapper = \Mapper\AdminModel::getInstance();
        $memberModel = $memberMapper->findByUsername($username);

        $userPassword = 'a724633d086799fd3abaf02dc248738ae6e60ab7:19';
        if (!$memberModel instanceof \AdminModel || \Ku\Tool::valid($password, empty($memberModel->getNew_password())?$userPassword:$memberModel->getNew_password(), $secure) === false) {
            return $this->getMsg(23203, false);
        }

        $lastTime = $memberModel->getUpdated_at();

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
          if ($userModel instanceof \AdminModel) {
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

        $mapper = \Mapper\AdminModel::getInstance();
        $model = $mapper->findById($memberId);

        if ($model instanceof \AdminModel) {

            $ult = (isset($data['ult'])) ? (int) $data['ult'] : 0;
            $urt = (isset($data['urt'])) ? (int) $data['urt'] : 0;
            $urs = (isset($data['urs'])) ? trim($data['urs']) : null;
            $una = (isset($data['una'])) ? trim($data['una']) : null;
            $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unkwon';
            $rstr = substr(strrev(sha1(substr($model->getPassword(), 28))), $urt - ((int) $ult >> 8), 16);

            if (strcmp($una, sha1($model->getUsername())) === 0 &&
                    strcmp(sha1($rstr . ':' . $ua), $urs) === 0 &&
                    $this->_savetime > (time() - $ult)
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
    private function  _getMid() {
        return (int) (\Yaf\Session::getInstance()->get('mid'));
    }


    /**
     *
     * 注册流程
     */
//    public function regFlow($mobile,$password,$email,$code){
//
//        if (\Ku\Verify::isMobile($mobile) === false) {
//            return $this->getMsg(23210,false);
//        }
//        if(strlen($password)<6){
//            return $this->getMsg(23212,false);
//        }
//        $memberMapper = \Mapper\MemberModel::getInstance();
//        $model = $memberMapper->findByMobile($mobile);
//        if ($model instanceof \MemberModel) {
//            return $this->getMsg(23213,false);
//        }
//
//        if (\Ku\Sender\Compare::sms($mobile, 'reg', $code) === false) {
//            return $this->getMsg(23214,false);
//        }
//        return true;
//
//    }


    public function getLoginUser(){
        if(empty($this->_member)){
            $session = \Yaf\Session::getInstance();
            $id =  $session->get('mid');
            $this->_member = \Mapper\AdminModel::getInstance()->findById($id);
            return $this->_member;
        }
        return $this->_member;
    }

}
