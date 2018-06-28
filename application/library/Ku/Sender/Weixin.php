<?php

namespace Ku\Sender;

 class Weixin extends \Ku\Sender\SenderAbstract {
	 protected static $appid = null;
	 protected static $appsecret = null;
	 protected static $access_token = NULL;
	 protected static $_weixin_openid = '';
	 protected static $_template_data = array (
		'warn_to_user' => array(
			'id' => 'YNcb4FnlvsynPvMt5ARJuBKNVJF-2MDDAQG4zBaZCdM',
//			'id' => 'ZYAk5CjCyKS54W7lvZcIIHZ0wRegTfdrMV50Y45xkpE',
			'first' => '您的任务有变更',
			'keyword1' => '2015-12-11 11:11:11',  //触发时间
			'keyword2' => 'whois',  //类型
			'keyword3' => '您的域名xxx备案失效',//警告内容
			'remark' => '',
			'url' => ''
		),
		 'saomiao_danhao' => array(
			 'id' => 'w9rwYOfQtUTWTGYRj7fFUzIhMwoTrTRGiypCnTICGxo',
//			'id' => 'ZYAk5CjCyKS54W7lvZcIIHZ0wRegTfdrMV50Y45xkpE',
			 'first' => '快递单号扫描成功',
			 'keyword1' => '2015年12月11日 11:11',  //扫描时间
			 'keyword2' => '快递监控',  //扫描类型
			 'keyword3' => '快递单号',//内容描述
			 'remark' => '感谢您的使用',
			 'url' => ''
		 ),
		 'saomiao_error' => array(
			 'id' => 'jyPl0HcUQq_eWbkHBFLi4SHgqxB84lLeKY9aFTAtn_U',
//			'id' => 'ZYAk5CjCyKS54W7lvZcIIHZ0wRegTfdrMV50Y45xkpE',
			 'first' => '快递单号扫描失败',
			 'keyword1' => '2015年12月11日 11:11',  //扫描时间
			 'keyword2' => '快递监控',  //扫描类型
			 'keyword3' => '当前未绑定账号',//内容描述
			 'remark' => '感谢您的使用',
			 'url' => ''
		 ),
		 'text' => array(
//			 'id' => 'jyPl0HcUQq_eWbkHBFLi4SHgqxB84lLeKY9aFTAtn_U',
			'id' => '8qX7zDpVwDTvo1CSrbncoRGCuro_ySDfJ1r0xwUV56w',
			 'first' => '快递任务添加成功',
			 'keyword1' => '2015年12月11日 11:11',  //扫描时间
			 'keyword2' => '快递监控',  //扫描类型
			 'keyword3' => '当前未绑定账号',//内容描述
			 'remark' => '感谢您的使用',
			 'url' => ''
		 ),
		 'text_error' => array(
//			 'id' => 'jyPl0HcUQq_eWbkHBFLi4SHgqxB84lLeKY9aFTAtn_U',
			'id' => 'PPbiUOU2cP6eCbV0XNAADrSkvHSpiPP3qnRYbqOkdb4',
			 'first' => '快递任务添加失败',
			 'keyword1' => '2015年12月11日 11:11',  //扫描时间
			 'keyword2' => '快递监控',  //扫描类型
			 'keyword3' => '当前未绑定账号',//内容描述
			 'remark' => '感谢您的使用',
			 'url' => ''
		 ),
		 );

	 protected static $_funcNames = array(
		 'whois' => '域名Whois',
		 'beian' => '域名备案',
		 'brank' => '百度关键词',
		 'http' => '网站监控',
		 'express' => '快递监控',
	 );


	 /***
	 *
	 * @param int $mid
	 * @param string $channel
	 * @param array $data warn类型跟内容
	 * @return boolean
	 */
	public  function send() {
		$config = \Yaf\Registry::get('config');
		$conf = $config->get('resources.weixin');
		if(!isset($conf['appid']) || !isset($conf['appsecret'])) {
			exit("微信 未配置\n");
		}
		self::$appid = $conf['appid'];
		self::$appsecret = $conf['appsecret'];
		try
		{
			$data = $this->getContent();
			$mid = isset($data['mid']) ? $data['mid'] : 0;
			self::getToken();
			$redis = self::getRedis();
			self::$_weixin_openid = $data['open_id'];
			if (empty ( self::$_weixin_openid )) {
				return false;
			}
			$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $redis->get ( 'access_token' );
			$params = self::$_template_data [$data['channel']];
			$data = self::template ( $data['channel'], $params,$data);

			if($data == false){
				return false;
			}
			$result = self::https_request ( $url, $data );
			$resultObj = json_decode ( $result );

			if ($resultObj->errcode == 0) {
				return true;
			} else {
				if($resultObj->errcode == 40001) {
					$redis->del('access_token');
					self::getToken();
					$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $redis->get ( 'access_token' );
					$result = self::https_request ( $url, $data );
					$resultObj = json_decode ( $result );
					if ($resultObj->errcode == 0) {
						return true;
					}

				}else {
					return false;
				}
			}
		}
		catch(Exception $e){
			return false;
		}

	}
	public static function template($channel = 'warn_to_user', $params,$data) {

		$method = 'template_' . $channel ;
		// 监控消息提醒
		$template = array (
			"touser" => self::$_weixin_openid,
			"template_id" => self::$_template_data [$channel]['id'],
			"url" => self::returnUrl($data),
			"topcolor" => "#FF0000",
			"data" => self::$method ($params,$data)
		);
		return json_encode ($template);
	}


	public static function template_warn_to_user($params,$data) {
		return array (
			"first" => array (
				"value" => self::returnTitle($data),
				"color" => "#173177"
			),
			"keyword1" => array (
				"value" => date('Y-m-d H:i',$data['addtime']),
				"color" => "#173177"
			),
			"keyword2" => array (
				"value" => self::$_funcNames[$data['func']],
				"color" => "#173177"
			),
			"keyword3" => array (
				"value" => $data['body'],
				"color" => "#173177"
			),
			"remark" => array (
				"value" => $data['remark'],
				"color" => ""
			),
		);
	}
	 public static function template_saomiao_danhao($params,$data) {
		 return array (
			 "first" => array (
				 "value" => self::returnTitle($data),
				 "color" => "#173177"
			 ),
			 "keyword1" => array (
				 "value" => date('Y年m月d日 H:i',$data['addtime']),
				 "color" => ""
			 ),
			 "keyword2" => array (
				 "value" => self::$_funcNames[$data['func']],
				 "color" => ""
			 ),
			 "keyword3" => array (
				 "value" => $data['body'],
				 "color" => ""
			 ),
			 "remark" => array (
				 "value" => $data['remark'],
				 "color" => "#173177"
			 ),
		 );
	 }
	 public static function template_saomiao_error($params,$data) {
		 return array (
			 "first" => array (
				 "value" => self::returnTitle($data),
				 "color" => "#173177"
			 ),
			 "keyword1" => array (
				 "value" => date('Y年m月d日 H:i',$data['addtime']),
				 "color" => ""
			 ),
			 "keyword2" => array (
				 "value" => self::$_funcNames[$data['func']],
				 "color" => ""
			 ),
			 "keyword3" => array (
				 "value" => $data['body'],
				 "color" => ""
			 ),
			 "remark" => array (
				 "value" => $data['remark'],
				 "color" => "#173177"
			 ),
		 );
	 }
	 public static function template_text_error($params,$data) {
		 return array (
			 "first" => array (
				 "value" => self::returnTitle($data),
				 "color" => "#173177"
			 ),
			 "keyword1" => array (
				 "value" => date('Y年m月d日 H:i',$data['addtime']),
				 "color" => ""
			 ),
			 "keyword2" => array (
				 "value" => self::$_funcNames[$data['func']],
				 "color" => ""
			 ),
			 "keyword3" => array (
				 "value" => $data['body'],
				 "color" => ""
			 ),
			 "remark" => array (
				 "value" => $data['remark'],
				 "color" => "#173177"
			 ),
		 );
	 }
	 public static function template_text($params,$data) {
		 return array (
			 "first" => array (
				 "value" => self::returnTitle($data),
				 "color" => "#173177"
			 ),
			 "keyword1" => array (
				 "value" => date('Y年m月d日 H:i',$data['addtime']),
				 "color" => ""
			 ),
			 "keyword2" => array (
				 "value" => self::$_funcNames[$data['func']],
				 "color" => ""
			 ),
			 "keyword3" => array (
				 "value" => $data['body'],
				 "color" => ""
			 ),
			 "remark" => array (
				 "value" => $data['remark'],
				 "color" => "#173177"
			 ),
		 );
	 }

	 public static function returnUrl($data) {
		 if($data['func'] == 'express') {
			 if($data['url']) {
				 return $data['url'];
			 }
			 else{
				 return null;
			 }
		 }
	 }

	 public static function returnTitle($data) {
		 if($data['func'] == 'express') {
			 return $data['title'];
		 }
	 }
	 public static function getOpenidFromDb($mid) {
		$memberWeixinMapper = \Mapper\MemberweixinModel::getInstance ();
		try {
			$where = array (
				'mid' => $mid
			);
			$result = $memberWeixinMapper->fetch ( $where );
			if (! empty ( $result )) {
				$result = $result->toArray ();
				return $result ['openid'];
			} else {
				return false;
			}
		} catch ( Exception $e ) {
			return false;
		}
	}
	 // 构造函数，获取Access Token
	 public  function getToken() {

		 $redis = self::getRedis();
		 if (empty ( $redis->get ( 'access_token' ) )) {
			 $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . self::$appid . "&secret=" . self::$appsecret;
			 $res = self::https_request ( $url );
			 //	echo '<pre>';var_dump(self::$appid);exit;
			 $result = json_decode ( $res, true );
			 // save to Redis
			 $redis->set ( 'access_token', $result ["access_token"] );
			 $redis->expire ( 'access_token', 7199 ); // 比7200小一秒
			 self::$access_token = $result ["access_token"];
		 }
	 }
	 public static function getXMLContent() {
		 $postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		 /*
          * $postStr = "<xml>
          * <ToUserName><![CDATA[toUser]]></ToUserName>
          * <FromUserName><![CDATA[123]]></FromUserName>
          * <CreateTime>1348831860</CreateTime>
          * <MsgType><![CDATA[lcation]]></MsgType>
          * <Content><![CDATA[3]]></Content>
          * <Location_X>31.23</Location_X>
          * <Location_Y>121.47</Location_Y>
          * <MsgId>1234567890123456</MsgId>
          * </xml>";
          */
		 if (! empty ( $postStr )) {
			 $postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			 $postArr ['from_user_name'] = ( string ) $postObj->FromUserName;
			 $postArr ['to_user_name'] = ( string ) $postObj->ToUserName;
			 $postArr ['location_X'] = ( string ) $postObj->Location_X;
			 $postArr ['location_Y'] = ( string ) $postObj->Location_Y;
			 $postArr ['msg_type'] = ( string ) $postObj->MsgType;
			 $postArr ['keyword'] = ( string ) trim ( $postObj->Content );
			 $postArr ['event'] = ( string ) $postObj->Event;
			 $postArr ['event_key'] = ( string ) $postObj->EventKey;
			 $postArr ['pic_url'] = ( string ) $postObj->PicUrl;
		 }

		 return $postArr;
	 }

	 public function send_template_message($data) {
		 $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . self::$access_token;
		 $res = self::https_request ( $url, $data );
		 return json_decode ( $res, true );
	 }

	 // https请求（支持GET和POST）
	 protected static function https_request($url, $data = null) {
		 $curl = curl_init ();
		 curl_setopt ( $curl, CURLOPT_URL, $url );
		 curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
		 curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
		 if (! empty ( $data )) {
			 curl_setopt ( $curl, CURLOPT_POST, 1 );
			 curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data );
		 }
		 curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		 $output = curl_exec ( $curl );
		 curl_close ( $curl );
		 return $output;
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
}
