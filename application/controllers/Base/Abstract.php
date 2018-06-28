<?php

namespace Base;

/**
 * 控制器的基类.
 */
class AbstractController extends \Yaf\Controller_Abstract {

    /**
     * 布局模板的名称
     *
     * @var sting
     */
    protected $layout = 'default';

    /**
     * 自动开启视图引擎及布局模块
     *
     * @var boolean
     */
    protected $autoRender = true;
    protected $autoLayout = true;

    /**
     * Layout 的目录是否跟随 Module 到对应的目录.
     *
     * @var boolean
     */
    protected $layoutFollowModule = false;
    protected $statVendor = array('kutongji', 'baidu', 'cnzz');

    /**
     * @return void
     */
    public function init() {
        $this->initStaticResources();
        $this->before();
        ($this->autoRender === true && $this->autoLayout === true) ? $this->initView() : ($this->disableView() && $this->disableLayout());
        $this->after();
    }

    protected function before() {

    }

    protected function after() {
    }

    /**
     * 视图路径配置
     *
     * @param array $options
     * @return \Ku\Layout
     */
    public function initView(array $options = NULL) {
        parent::initView($options);
        $view = $this->getView();

        if (!empty($this->layout)) {
            $config = \Yaf\Registry::get('config');
            $prePath = $config->get('application.directory');

            if ($this->layoutFollowModule and 'Index' !== $this->getModuleName()) {
                $prePath .= 'modules' . DS . $this->getModuleName() . DS;
            }

            $view->setLayoutPath($prePath . 'views' . DS . 'layouts');
            $view->setLayout($this->layout);
        }

        return $view;
    }

    /**
     * 取得当前配置
     *
     * @return \Yaf\Config\Ini
     */
    public function getConfig() {
        return \Yaf\Application::app()->getConfig();
    }

    /**
     * 向视图注册属性(变量)
     *
     * @param string  $name   属性名
     * @param mixed         $value  属性值
     * @return boolean
     */
    public function assign($name, $value) {
        $this->getView()->assign($name, $value);

        return $this;
    }

    /**
     * 向视图注册属性(变量)
     *
     * @param array|object  $options 属性
     * @return boolean
     */
    public function assigneach($options) {
        if (is_object($options)) {
            if (method_exists($options, 'toArray')) {
                $options = $options->toArray();
            } else if (!($options instanceof \Traversable)) {
                return $this;
            }
        } else if (!is_array($options)) {
            return $this;
        }

        $view = $this->getView();

        foreach ($options as $key => $value) {
            $view->assign($key, $value);
        }

        return $this;
    }

    /**
     * 关闭模板的默认渲染设置
     *
     * @return bool
     */
    public function disableView() {
        return \Yaf\Dispatcher::getInstance()->disableView();
    }

    /**
     * 关闭模板布局
     */
    public function disableLayout() {
        $this->getView()->setLayout(null);
    }

    /**
     * 跳转,直接重定向，退出程序
     *
     * @param string $url          要跳转的URL
     */
    public function redirect($url) {
        parent::redirect($url);
        exit();
    }

    /**
     * 处理统计代码
     *
     * @return array
     * @throws \Exception
     */
    protected function statCode() {
        $config = \Yaf\Registry::get('config');
        $resource = $config->get('resource.stat');
        $code = array();

        if ($resource instanceof \Yaf\Config\Ini) {
            foreach ($this->statVendor as $vendor) {
                $statCode = $resource->get($vendor);

                !empty($statCode) && array_push($code, $statCode);
            }
        }

        return $code;
    }

    /**
     * 使用限制处理
     *
     * @param string $key
     * @param int $limit
     * @param int $expire
     * @return boolean
     */
    protected function useLimit($key, $limit, $expire = 600) {
        if ($this->inIpWhitelist() === true) {
            return false;
        }

        $redis = \Yaf\Registry::get('redis');
        $count = (int) ($redis->incr($key));

        if ($count === 1 && $expire > 0) {
            $redis->expire($key, $expire);
        }

        return (bool) ($limit < $count);
    }

    /**
     * 返回数据处理
     *
     * @param string|array $msg
     * @param int $code
     * @param boolean $status
     * @param any $data
     * @param string $callback
     * @return false
     */
    protected function returnData($msg = null, $code = 0, $status = false, $data = null, $callback = null) {
        $this->disableLayout();
        $this->disableView();
        if(!is_array($msg)){
            $msg = array(
                'status' => (bool) $status === true ? true : false,
                'code' => (int) $code,
                'msg' => $msg,
            );
        }

        if (!empty($data)) {
            $msg['data'] = $data;
        }

        $callback = (!!$callback && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', (string) $callback)) ? $callback : null;

        if ($callback === null) {
            header('Content-type: application/json; charset=utf-8');
            echo \json_encode($msg,JSON_UNESCAPED_UNICODE );
        } else {
            header('Content-type: application/javascript; charset=utf-8');
            echo $callback, '(', \json_encode($msg), ');';
        }

        return false;
    }

    /**
     * 传递消息数据
     * @param string $msg
     * @param int $code
     * @param boolean $status
     * @param array $data
     * @param string $inputName 输入数据名词
     * @return boolean
     */
    protected function returnMsg($msg = null, $code = 0, $status = false, $data = null, $inputName = '') {
        $msgArr = array(
            'status' => (bool) $status === true ? true : false,
            'code' => (int) $code,
            'input' => $inputName,
            'msg' => $msg,
        );

        if (!empty($data)) {
            $msgArr['data'] = $data;
        }
        $this->assign('msg', $msgArr);
        return $status;
    }

    /**
     * Redirect Url
     *
     * @return string
     */
    protected function getRedirectUrl($redirectUrl = '') {
        $request = $this->getRequest();
        $redirectUrl = $redirectUrl ? $redirectUrl : trim(urldecode($request->getPost('redirect_url', null))) ? : trim($request->get('redirect_url', null));
        if (empty($redirectUrl) || strpos($redirectUrl, '//') === 0 || strpos($redirectUrl, '\\') !== false || strpos($redirectUrl, '@') !== false) {
            return '/';
        }

        if (strpos($redirectUrl, '/') === 0) {
            return \Ku\Tool::strReplace($redirectUrl);
        }

        $domainConfig = new \Yaf\Config\Ini(\APPLICATION_PATH . '/conf/domain.ini', \Yaf\Application::app()->environ());
        $domainIni = $domainConfig->get('list');

        if ($domainIni instanceof \Yaf\Config\Ini) {
            $domainList = $domainIni->toArray();
            $params = parse_url($redirectUrl);
            foreach ($domainList as $pattern) {
                if (preg_match('/' . $pattern . '$/', $params['host'])) {
                    return \Ku\Tool::strReplace($redirectUrl);
                }
            }
        }
        if($this->isMobile()) {
            return '/m/';
        }
        return '/';
    }

    protected function currentUrl() {
        return 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
    }

    /**
     * IP白名单
     *
     * @return boolean
     */
    protected function inIpWhitelist() {
        $whitelist = \Yaf\Registry::get('ipwhitelist')->get('list');

        if (!$whitelist instanceof \Yaf\Config\Ini) {
            return false;
        }

        return (bool) \Ku\Tool::isInNetwork(\Ku\Tool::getClientIp(), $whitelist->toArray());
    }
        /**
     * 
     * @return \Redis
     * @throws \Exception
     */
    public function getRedis() {
        $redis = \Yaf\Registry::get('redis');
        return $redis;
    }

    /**
     * 是否使用Captcha
     *
     * @param string $userKey
     * @return boolean
     */
    protected function useCaptcha($channel = null) {
        if ($this->inIpWhitelist() === true) {
            return false;
        }
        $key = sprintf(\Ku\Consts::USING_CAPTCHA,\Ku\Tool::getClientIp(true));
        $redis = $this->getRedis();
        if($redis->get($key) >3){
            return true;
        }
        return false;
    }

    /**
     * 获取参数
     * @param string $key
     * @param 默认值 $default
     * @return string|int|arra
     */
    protected function getParam($key, $default = '') {
        $request = $this->getRequest();
        $param = $request->get($key, $default);
        if (is_string($param)) {
            $param = trim($param);
        }
        return $param;
    }

    /**
     * 短信验证码发送
     *
     * @return boolean
     */
    protected function sms($phonenumber, $channel) {
        $sender = new \Ku\Sender\Sms();
        $sender->setDst($phonenumber)->setChannel($channel);
        return $sender->send();
    }

    /**
     * 邮件发送
     *
     * @return boolean
     */
    protected function email($email, $channel) {
        $sender = new \Ku\Sender\Email();
        $sender->setDst($email)->setChannel($channel);

        return $sender->send();
    }

    /**
     * 检查验证码
     *
     * @param string $captcha
     * @param string $channel 验证码频道
     * @return boolean
     */
    protected function checkCaptcha($captcha, $channel = null) {
        $session = \Yaf\Session::getInstance();
        $userKey = sprintf(\Ku\Consts::USING_CAPTCHA_CHANNEL,$channel);
        $userKeyTime = sprintf(\Ku\Consts::USING_CAPTCHA_CHANNEL_TIME,$channel);
        $useCaptcha = $session->get($userKey);
        //验证码有验证次数限制
        if ($session->get($userKeyTime) > 10) {
            return false;
        }
        if (empty($captcha)) {
            return false;
        }
        if (!(strcasecmp($useCaptcha, $captcha) === 0)) {
            $session->set($userKeyTime,$session->get($userKeyTime)+1);
            return false;
        }
        $session->del($userKeyTime);
        $key = sprintf(\Ku\Consts::USING_CAPTCHA,\Ku\Tool::getClientIp(true));
        $redis = $this->getRedis();
        $redis->del($key);
        return true;
    }

    /**
     * 处理资源路径
     *
     * @throws \Exception
     */
    protected function initStaticResources() {
        // 静态页面的配置
        $resource = \Yaf\Registry::get('apiini');
        if (!$resource instanceof \Yaf\Config\Ini) {
            $resource = new \Yaf\Config\Ini(APPLICATION_PATH . '/conf/api.ini',\Yaf\Application::app()->environ());
            \Yaf\Registry::set('apiini',$resource);
        }
        $this->assigneach($resource);
    }


    /**
     * 简易分页器
     *
     * @param \Mapper\Base\AbstractModel $mapper
     * @param array $where
     * @param type $limit
     * @params callback $callback
     * @param string $order
     * @return boolean
     */
    protected function paginator(\Mapper\Base\AbstractModel $mapper, array $where, $limit = 10, $callback = null, $order = 'id DESC') {
        $limit   = ((int)$limit < 0) ? 10 : $limit;
        $getPage = abs((int)$this->getRequest()->get('page', 1));
        $total   = $mapper->count($where);

        $totalPage = ceil($total / $limit);
        $page      = (($getPage <= 1) ? 1 : (($getPage > $totalPage) ? ($totalPage < 1 ? 1 : $totalPage) : $getPage));
        $offset    = $limit * ($page - 1);

        try {
            $data = $mapper->fetchAll($where, $order, $limit, $offset);
        } catch (\Exception $ex) {
            $data = array();
        }

        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('totalPage', $totalPage);

        if(!!$callback && is_callable($callback)){
            $data = $callback($data);
        }

        $this->assign('pageData', $data);

        return true;
    }
}
