<?php
use \Ku\Ueditor;

/**
 *
 * @author laixiang
 */
class UeditorController extends \Base\ApplicationController {
	
	
	public function indexAction() {

		$action= \Ku\Tool::filter( $this->getParam('action',''));
		$callback= \Ku\Tool::filter( $this->getParam('callback',''));
		$size = \Ku\Tool::filter( $this->getParam('size',''));
		$start = \Ku\Tool::filter( $this->getParam('start',''));
		// header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
		// header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
		date_default_timezone_set ( "PRC" );
// 		error_reporting ( E_ERROR );
		header ( "Content-Type: text/html; charset=utf-8" );
		$CONFIG = json_decode ( preg_replace ( "/\/\*[\s\S]+?\*\//", "", file_get_contents ( APPLICATION_PATH . "/application/library/Ku/Ueditor/config.json" ) ), true );
		switch ($action) {
			case 'config' :
				$result = json_encode ( $CONFIG );
				break;
			
			/* 上传图片 */
			case 'uploadimage' :
				$result = Ueditor\Ueupload::ueupload($CONFIG);
				break;
			
			/* 列出图片 */
			case 'listimage' :
				$result = Ueditor\Uelist::uelist($CONFIG);
				break;
			/* 列出文件 */
			case 'listfile' :
				$result = Ueditor\Uelist::uelist($CONFIG);
				break;
			case 'catchimage':
				$result = Ueditor\Uecrawler::uecrawler($CONFIG);
				break;
			default :
				$result = json_encode ( array (
						'state' => '请求地址出错' 
				) );
				break;
		}
		
		/* 输出结果 */
		if (!empty ( $callback )) {
			if (preg_match ( "/^[\w_]+$/", $callback )) {
				echo htmlspecialchars ( $callback ) . '(' . $result . ')';
			} else {
				echo json_encode ( array (
						'state' => 'callback参数不合法' 
				) );
			}
		} else {
			echo $result;
		}
		
		$this->disableLayout();
		$this->disableView();
	}
	
	
}



