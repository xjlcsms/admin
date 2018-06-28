<?php

namespace Ku\Pay;

abstract class PayAbstract {

    /**
     * Authorizer Config [only read]
     *
     * @var \Yaf\Config\Ini
     */
    protected $_pay_config = null;

    /**
     * 支付方式
     * @var string 
     */
    protected $_pay_type = null;

    /**
     * 参数
     *
     * @var array
     */
    protected $_params = array();

    /**
     * 基础URL
     *
     * @var string
     */
    protected $_baseUrl = null;


    /*     * *
     * 加密方式
     */
    protected $_sign_type = null;

    /**
     * Authorizer
     *
     * @return string
     */
    public function getPayType() {
        return $this->_pay_type;
    }

    /**
     * 组装URI(语法糖)
     *
     * @return string
     */
    public function assemble() {
        return $this->appendURI($this->getBaseUrl(), $this->_params);
    }

    /**
     * 附加URI参数对$url后
     *
     * @param string $url
     * @param array $append_uri
     * @return string
     */
    public function appendURI($url, array $append_uri) {
        $url = trim($url);

        if (!(strpos($url, 'http') === 0 || strpos($url, 'https') === 0)) {
            throw new \Exception('invalid url', 32104);
        }

        return $url . (strpos($url, '?') === false ? '?' : '&') . http_build_query($append_uri);
    }

    /**
     * 基础URL
     *
     * @param string $base_url
     * @return \Oauth\Client\OauthAbstract
     */
    public function setBaseUrl($base_url) {
        $this->_baseUrl = (string) $base_url;

        return $this;
    }

    /**
     * 基础URL
     *
     * @return $base_url
     */
    public function getBaseUrl() {
        return $this->_baseUrl;
    }

    /**
     * 参数
     *
     * @param string $key
     * @param string $value
     * @return \Oauth\Client\OauthAbstract
     */
    public function addParam($key, $value) {
        $this->_params[$key] = (string) $value;

        return $this;
    }

    /**
     * 参数
     *
     * @return array
     */
    public function getParams() {
        return $this->_params;
    }

    /**
     * 跳转处理
     */
    public function jumpto() {
        header('Location:' . $this->assemble(), true, 302);
        return false;
    }

    /**
     * 隐式处理 [curl]
     *
     * @return boolean
     */
    public function implicit() {
        return (bool) ($this->implicitParase($this->httpRequest()));
    }

    /**
     * 获取参数
     *
     * @param string $name
     * @param string $default
     * @return string|null
     */
    public function getQuery($name, $default = null) {
        return \Yaf\Dispatcher::getInstance()->getRequest()->get($name, $default);
    }

    /**
     * 模拟Http请求
     *
     * @param boolean $post
     * @return string
     */
    public function httpRequest($post = true) {
        $http = new \Ku\Http();
        $http->setTimeout(5);
        $url = $post === true ? $this->getBaseUrl() : $this->assemble();
        $http->setUrl($url);
        $http->setParam($this->getParams(),true);

        return $http->send();
    }

    /**
     * 重置参数组
     */
    protected function resetParams() {
        $this->_params = array();
    }

    /**
     * Oauth Client Config [only read]
     *
     * @return \Yaf\Config\Ini
     * @throws \Exception
     */
    public function getConfig() {
        if (!$this->_pay_config instanceof \Yaf\Config\Ini) {
            $config = new \Yaf\Config\Ini(\APPLICATION_PATH . '/conf/pay.ini', \Yaf\Application::app()->environ());
            $this->_pay_config = $config->get('pay.' . strtolower($this->_pay_type));

            if (!$this->_pay_config instanceof \Yaf\Config\Ini) {
                throw new \Exception('invalid configure');
            }
        }

        return $this->_pay_config;
    }

    /**
     * 实现多种字符编码方式
     * @param $input 需要编码的字符串
     * @param $outCharset 输出的编码格式
     * @param $inCharset 输入的编码格式
     * return 编码后的字符串
     */
    public function charsetEncode($input, $outCharset, $inCharset) {
        $output = "";
        isset($outCharset)? : $outCharset = $inCharset;
        if ($inCharset == $outCharset || $input == null) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input, $outCharset, $inCharset);
        } elseif (function_exists("iconv")) {
            $output = iconv($inCharset, $outCharset, $input);
        } else {
            die("sorry, you have no libs support for charset change.");
        }
        return $output;
    }

    /**
     * 实现多种字符解码方式
     * @param string $input 需要解码的字符串
     * @param string $outCharset 输出的解码格式
     * @param string $inCharset 输入的解码格式
     * return 解码后的字符串
     */
    public function charsetDecode($input, $inCharset, $outCharset) {
        $output = "";
        isset($inCharset)? : $inCharset = $inCharset;
        if ($inCharset == $outCharset || $input == null) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input, $outCharset, $inCharset);
        } elseif (function_exists("iconv")) {
            $output = iconv($inCharset, $outCharset, $input);
        } else {
            die("sorry, you have no libs support for charset changes.");
        }
        return $output;
    }

    /**
     * 签名字符串
     * @param $prestr 需要签名的字符串
     * @param $key 私钥
     * return 签名结果
     */
    public function md5Sign($prestr, $key) {
        $prestr = $prestr . $key;
        return md5($prestr);
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $params 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    public function paramsFilter() {
        $paramsFilter = array();
        $params = $this->getParams();
        while (list ($key, $val) = each($params)) {
            if ($key == "sign" || $key == "sign_type" || $val == ""){
                continue;
            }else{
                $paramsFilter[$key] = $params[$key];
            }
        }
        $this->_params = $paramsFilter;
        return $this->_params;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    public function argSort($para) {
        ksort($para);
     //   reset($para);
        return $para;
    }
    /**
     * 获取sign
     * @param array $params
     * @param string $key
     * @return string
     */
    public function createSign($params,$config){
        $params = $this->argSort($params);
        $signType = strtoupper(isset($params['sign_type'])?$params['sign_type']:'MD5');
        $signStr = '';
        unset($params['sign']);
        unset($params['sign_type']);
        unset($params['sign_version']);
        foreach ( $params as $k => $val ) {
            $signStr .= "&" . $k . "=" . $val;
        }
        $signStr = trim($signStr,'&');
        if($signType == 'MD5'){
            return $this->md5Sign($signStr,$config['key']);
        }elseif($signType == 'RSA'){
            $key = file_get_contents($config['ssl_key_dir'].'/rsa_sign_private.pem');
            return $this->rsaSign($signStr,$key);
        }
        
    }

    public function rsaSign($signStr,$key){
        $sign = '';
        $pkeyid = openssl_pkey_get_private ( $key );
        openssl_sign ( $signStr, $sign, $pkeyid, OPENSSL_ALGO_SHA1 );
        openssl_free_key ( $pkeyid );
        $sign = base64_encode ( $sign );
        return $sign;
    }

}
