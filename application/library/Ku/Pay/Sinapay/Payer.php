<?php

namespace Ku\Pay\Sinapay;

/**
 * 新浪支付
 *
 * @author zhihuawu
 */
final class Payer extends \Ku\Pay\PayAbstract {

    protected $_https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    protected $_http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
    protected $_baseUrl = '';
    protected $_sign_type = 'MD5';
    protected $_payment_type = 1;
    protected $_charset = 'utf-8';
    
    
    public function __construct() {
        $this->_pay_type = 'sinapay';
    }

    /**
     * 订单生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    public function buildOrderRequest() {
        $config = $this->getConfig();
        $params = $this->buildRequestParam();
        $url = $this->appendURI($config['mas_url'], $params);
        return $url;
    }

    /**
     * 订单生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    public function buildUserRequest() {
        $params = $this->buildRequestParam();
        $url = $this->appendURI($config['mgs_url'], $params);
        return $url;
    }

    /**
     * 用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
     * 注意：该功能PHP5环境及以上支持，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
     * return 时间戳字符串
     */
    public function queryTimestamp() {
        $url = $this->getBaseUrl() . "?service=query_timestamp&partner=" . $this->getConfig()->get('partner') . "&_input_charset=" . $this->_charset;
        $doc = new \DOMDocument();
        $doc->load($url);
        $itemEncrypt_key = $doc->getElementsByTagName("encrypt_key");
        $encrypt_key = $itemEncrypt_key->length ? $itemEncrypt_key->item(0)->nodeValue : '';

        return $encrypt_key;
    }

    /**
     * 生成要请求的参数数组
     * @return 要请求的参数数组
     */
    public function buildRequestParam() {
        $config = $this->getConfig();
        $this->addParam('version', $config['version']);
        $this->addParam('partner_id', $config['partner']);
        $this->addParam('_input_charset',$this->_charset);
        $this->addParam('request_time', date('YmdHis'));
        $params = $this->paramsFilter();
        $sign = $this->createSign($params,$this->_sign_type);
        //签名结果与签名方式加入请求提交参数组中
        $params['sign'] = $sign;
        $params['sign_type'] = $this->_sign_type;
        
        return $params;
    }

    /**
     * 回调处理支付完车
     * @param string $sign
     * @param string $notifyId
     * @param int $mid
     * @return boolean
     */
    public function turnback($sign, $notifyId,$mid) {
        if (empty($notifyId) || empty($sign)) {
            return false;
        }
        return $this->volidSign($sign);
    }

    public function notify($sign) {
        return $this->volidSign($sign);
    }

    public function volidSign($sign) {
        $params = $this->paramsFilter();
        $newSign = $this->createSign($params, $this->getConfig());
        if ($sign === $newSign) {
            return true;
        }
        return false;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notifyId 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    public function getResponse($notifyId) {
        $verifyUrl = $this->_http_verify_url . "partner=" . $this->getConfig()->get('partner') . "&notify_id=" . $notifyId;
        $responseTxt = $this->getHttpResponseGET($verifyUrl, $this->getCacert());
        return $responseTxt;
    }

    /**
     * 获取证书地址
     * @return string
     */
    protected function getCacert() {
        return getcwd() . '\\key\\cacert.pem';
    }
    /**
     * [curlPost 模拟表单提交]
     * @param string $url           
     * @param string $data          
     * @return string $data
     */
    public function httpRequest($post = true)
    {
        $http = new \Ku\Http();
        $http->setTimeout(5);
        $url = $post === true ? $this->getBaseUrl() : $this->assemble();
        $http->setUrl($url);
        $http->setParam($this->buildRequestParam(),true);
        return $http->send();
    }


    private function headerSina($url){
        $html = "<html><head><meta http-equiv=\"Content-Type\" content=\"text\/html; charset=UTF-8\" /></head><body></body><script language=\"javascript\"> location.href =\"".$url."\";</script></html>";
        echo $html;

    }


        /**
     * 通过公钥进行rsa加密
     * @param type $name
     *          Descriptiondata
     *          $data 需要进行rsa公钥加密的数据 必传
     *          $pu_key 加密所使用的公钥 必传
     * @return 加密好的密文
     */
    private function rsaEncrypt($data)
    {
        $encrypted = '';
        $config = $this->getConfig();
        $cert = file_get_contents ($config['ssl_key_dir'].'/rsa_public.pem');
        $pu_key = openssl_pkey_get_public ($cert ); // 这个函数可用来判断公钥是否是可用的
        openssl_public_encrypt ( $data, $encrypted, $pu_key ); // 公钥加密
        $encrypted = base64_encode ( $encrypted ); // 进行编码
        return $encrypted;
    }

















}