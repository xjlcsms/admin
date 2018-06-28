<?php

namespace Ku\Pay\Alipay;

/**
 * 支付宝支付
 *
 * @author zhihuawu
 */
final class Payer extends \Ku\Pay\PayAbstract {

    protected $_https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    protected $_http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
    protected $_baseUrl = 'https://mapi.alipay.com/gateway.do';
    protected $_sign_type = 'MD5';
    protected $_payment_type = 1;
    protected $_charset = 'utf-8';
    
    
    public function __construct() {
        $this->_pay_type = 'alipay';
    }

    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    public function buildRequest() {
        $params = $this->buildRequestParam();
        $url = $this->appendURI($this->getBaseUrl(), $params);
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
     * 生成要请求给支付宝的参数数组
     * @return 要请求的参数数组
     */
    public function buildRequestParam() {
        $this->addParam('payment_type', $this->_payment_type);
        //$this->queryTimestamp() === ''? : $this->addParam('anti_phishing_key', $this->queryTimestamp());
        $this->addParam('_input_charset', $this->_charset);
        $params = $this->paramsFilter();
        $sign = $this->createSign($params, $this->getConfig()->get('key'));
        //签名结果与签名方式加入请求提交参数组中
        $params['sign'] = $sign;
        $params['sign_type'] = $this->_sign_type;
        return $params;
    }

    /**
     * 远程获取数据，POST模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * @param $para 请求的数据
     * @param $input_charset 编码格式。默认值：空值
     * @return 远程输出的数据
     */
    public function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '') {

        if (trim($input_charset) != '') {
            $url = $url . "_input_charset=" . $input_charset;
        }
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
        curl_setopt($curl, CURLOPT_CAINFO, $cacert_url); //证书地址
        curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
        curl_setopt($curl, CURLOPT_POST, true); // post传输数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $para); // post传输数据
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
    }

    /**
     * 远程获取数据，GET模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * return 远程输出的数据
     */
    public function getHttpResponseGET($url, $cacert_url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
        curl_setopt($curl, CURLOPT_CAINFO, $cacert_url); //证书地址
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
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
        $volid = $this->volidSign($sign);
	    $responseTxt = $this->getResponse($notifyId);
	    if ($volid === true && preg_match("/true$/i", $responseTxt)) {
            $params = $this->getParams();
            return \Ku\Pay::getInstance()->doRecharge($mid,'alipay',$params['out_trade_no'],$params['trade_no']) ;
        }
        return false;
    }

    public function notify($sign, $notifyId) {
        if (empty($notifyId) || empty($sign)) {
            return false;
        }
        $volid = $this->volidSign($sign);
        $responseTxt = $this->getResponse($notifyId);
        if ($volid === true && preg_match("/true$/i", $responseTxt)) {
//            $params = $this->getParams();
//            return \Ku\Pay::getInstance()->doRecharge($params['mid'],'alipay',$params['out_trade_no'],$params['trade_no']);
            return true;
        }
        return false;
    }

    public function volidSign($sign) {
        $params = $this->paramsFilter();
        $newSign = $this->createSign($params, $this->getConfig()->get('key'));
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
        return getcwd() . '\\cacert.pem';
    }

}
