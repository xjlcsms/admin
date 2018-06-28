<?php

namespace Ku\Pay\Weixinnativepay;

/**
 * 微信公众号支付
 */
final class Payer extends \Ku\Pay\PayAbstract {

    protected $signType = 'MD5';  //签名方式，目前只有MD5
    public $_params = '';

    //统一下单url
    const POST_ORDER_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    //订单查询url
    const ORDER_QUERY_URL = 'https://api.mch.weixin.qq.com/pay/orderquery';

    public function __construct() {
        $this->_pay_type = 'weixinnativepay';
    }

    /**
     *
     * 生成直接支付url，支付url有效期为2小时,模式二
     * @param UnifiedOrderInput $input
     */
    public function getPayUrl() {
        $this->_params['spbill_create_ip'] = \Ku\Tool::getClientIp();
        $this->_params['nonce_str'] = $this->getRandomStr();
        $this->_params['sign'] = $this->sign();
        $xml = $this->arrayToXml();

        $response = $this->postUrl(self::POST_ORDER_URL, $xml);
        $res = $this->xmlToArray($response);
        $codeUrl = $res['code_url'];
        return $codeUrl;
    }


    /**
     * 验证异步通知
     * @return boolean
     */
    public function verifyNotify() {
        $this->_params = $this->xmlToArray($this->_params);
        if (empty($this->_params['sign'])) {
            return false;
        }
        $sign = $this->sign();
        return $this->_params['sign'] == $sign;
    }

    /**
     * 取成功响应
     * @return string
     */
    public function getSucessXml() {
        $xml = '<xml>';
        $xml .= '<return_code><![CDATA[SUCCESS]]></return_code>';
        $xml .= '<return_msg><![CDATA[OK]]></return_msg>';
        $xml .= '</xml>';
        return $xml;
    }

    public function getFailXml() {
        $xml = '<xml>';
        $xml .= '<return_code><![CDATA[FAIL]]></return_code>';
        $xml .= '<return_msg><![CDATA[OK]]></return_msg>';
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * 数组转成xml字符串
     *
     * @return string
     */
    protected function arrayToXml() {
        $xml = '<xml>';
        foreach ($this->_params as $key => $value) {
            $xml .= "<{$key}>";
            $xml .= "<![CDATA[{$value}]]>";
            $xml .= "</{$key}>";
        }
        $xml .= '</xml>';

        return $xml;
    }

    /**
     * xml 转换成数组
     * @param string $xml
     * @return array
     */
    protected function xmlToArray($xml) {
        $xmlObj = simplexml_load_string(
            $xml, 'SimpleXMLIterator', //可迭代对象
            LIBXML_NOCDATA
        );

        $arr = [];
        $xmlObj->rewind(); //指针指向第一个元素
        while (1) {
            if (!is_object($xmlObj->current())) {
                break;
            }
            $arr[$xmlObj->key()] = $xmlObj->current()->__toString();
            $xmlObj->next(); //指向下一个元素
        }

        return $arr;
    }

    /**
     * 签名
     * 规则：
     * 先按照参数名字典排序
     * 用&符号拼接成字符串
     * 最后拼接上API秘钥，str&key=密钥
     * md5运算，全部转换为大写
     *
     * @return string
     */
    protected function sign() {
        ksort($this->_params);
        $signStr = $this->arrayToString();
        $signStr .= '&key=' . $this->getConfig()->get('key');
        if ($this->signType == 'MD5') {
            return $this->signMd5($signStr);
        }

        throw new \InvalidArgumentException('Unsupported sign method');
    }

    /**
     * 数组转成字符串
     * @return string
     */
    protected function arrayToString() {
        $_params = $this->filter($this->_params);
        $str = '';
        foreach ($_params as $key => $value) {
            $str .= "{$key}={$value}&";
        }

        return substr($str, 0, strlen($str) - 1);
    }

    /*
     * 过滤待签名数据，sign和空值不参加签名
     *
     * @return array
     */

    protected function filter($_params) {
        $tmp_params = [];
        foreach ($_params as $key => $value) {
            if ($key != 'sign' && !empty($value)) {
                $tmp_params[$key] = $value;
            }
        }

        return $tmp_params;
    }

    /**
     * MD5签名
     *
     * @param string $str 待签名字符串
     * @return string 生成的签名，最终数据转换成大写
     */
    protected function signMd5($str) {
        $sign = md5($str);

        return strtoupper($sign);
    }

    /**
     * 获取随机字符串
     * @return string 不长于32位
     */
    protected function getRandomStr() {
        return substr(rand(10, 999) . strrev(uniqid()), 0, 15);
    }

    /**
     * 通过POST方法请求URL
     * @param string $url
     * @param array|string $data post的数据
     *
     * @return mixed
     */
    protected function postUrl($url, $data) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //忽略证书验证
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($curl);
        return $result;
    }

}
