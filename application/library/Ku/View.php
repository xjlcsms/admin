<?php

namespace Ku;

use Yaf\Application;

/**
 *
 * @author ghost
 */
class View extends \Yaf\View\Simple {
	
	/**
	 * 模板中的变量
	 *
	 * @var array
	 */
	protected $_tpl_vars = array ();
	
	/**
	 *
	 * @var string
	 */
	protected $layoutPath;
	
	/**
	 * 用 htmlspecialchars 转码
	 *
	 * @param string $str
	 *        	需要转码的字符串
	 * @return string
	 */
	public function _($str = '') {
		return htmlspecialchars ( $str, ENT_QUOTES | ENT_COMPAT | ENT_HTML401 );
	}
	
	/**
	 * 渲染视图
	 *
	 * @param string $tpl
	 *        	模板的路经
	 * @param array $tpl_vars
	 *        	模板变量
	 * @return strinbg
	 */

	public function render($tpl, $tpl_vars = NULL) {
		if ($tpl_vars) {
			$this->_tpl_vars = array_merge ( $this->_tpl_vars, $tpl_vars );
		}
		if(!file_exists($this->getScriptPath().DS.$tpl)){
			$this->setScriptPath(str_replace('views/mobile', 'views/default', $this->getScriptPath()));
		}
		return parent::render ( $tpl, $tpl_vars );
		
	}
	
	
	/**
	 * 取得当前项目的运行环境
	 * 由PHP的 yaf.environ 决定
	 *
	 * @return string
	 */
	public function environ() {
		return Application::app ()->environ ();
	}
	
	/**
	 * 读取配置文件中 view.
	 * 前缀的配置
	 *
	 * @param string $key
	 *        	键名
	 *        	
	 * @return \Yaf\Config\Ini|null|string
	 */
	public function getConfig($key = null) {
		$config = Application::app ()->getConfig ()->get ( 'view' );
		
		if (! $key) {
			return $config;
		} else {
			return $config ? $config->get ( ( string ) $key ) : null;
		}
	}
	
	/**
	 * 读取Layout目录
	 * 
	 * @return string
	 */
	public function getLayoutPath() {
		return $this->layoutPath;
	}
	
	/**
	 * 设置Layout目录
	 *
	 * @param string $path        	
	 * @return \Ku\View
	 */
	public function setLayoutPath($path) {
		$this->layoutPath = $path;
		return $this;
	}
	
	/**
	 * 渲染其它的模板, 用于实现模板的嵌套功能.
	 *
	 * @param string $tpl
	 *        	The template to process.
	 * @param array $tpl_vars
	 *        	Additional variables to be assigned to template.
	 *        	
	 * @return string The view or include template output.
	 */
	public function renderTpl($tpl, $tpl_vars = array()) {
		/*
		 * 嵌套的模板, 为了防止里层的变量穿透到外层, 所以才用了 clone.
		 * 这与Layout的嵌套是不一样的.
		 */
		$view = clone $this;
		return $view->render ( $this->getScriptPath () . DIRECTORY_SEPARATOR . $tpl, $tpl_vars );
	}
	
	/**
	 * 渲染其它的Layout模板, 用于实现Layout模板的嵌套功能.
	 *
	 * @param string $tpl
	 *        	The template to process.
	 * @param array $tpl_vars
	 *        	Additional variables to be assigned to template.
	 *        	
	 * @return string The view or include template output.
	 */
	public function renderLayoutTpl($tpl, $tpl_vars = array()) {
		return $this->render ( $this->getLayoutPath () . DIRECTORY_SEPARATOR . $tpl, $tpl_vars );
	}
	
	/**
	 *
	 * @return \Yaf\Request\Http
	 */
	public function getRequest() {
		return \Yaf\Dispatcher::getInstance ()->getRequest ();
	}
	
	/**
	 * 生成URI
	 *
	 * @param array $reset
	 *        	需要重置的URI参数对, key === (null|false) 此参数将被注销
	 * @return string
	 */
	public function uri(array $reset = array()) {
		$request = $this->getRequest ();
		$baseURI = $request->getBaseUri ();
		$query = $request->getQuery ();
		$params = $request->getParams ();
		$prefix = array ();
		
		$prefix ['module'] = strtolower ( $request->getModuleName () );
		$prefix ['controller'] = strtolower ( $request->getControllerName () );
		$prefix ['action'] = strtolower ( $request->getActionName () );
		
		if (! empty ( $reset )) {
			foreach ( $reset as $key => $value ) {
				if (isset ( $prefix [$key] )) {
					$prefix [$key] = $value ?  : null;
				} else if (isset ( $params [$key] )) {
					$params [$key] = $value ?  : null;
				} else {
					$query [$key] = $value ?  : null;
				}
			}
		}
		
		$prefix_uri = '/' . trim ( $baseURI . implode ( '/', $prefix ), '/' );
		$params_uri = trim ( str_replace ( array (
				'&',
				'=' 
		), '/', http_build_query ( $params ) ) );
		$query_uri = trim ( http_build_query ( $query ) );
		
		if (empty ( $params_uri )) {
			$prefix_uri = str_ireplace ( '/index', '', $prefix_uri );
		}
		
		$uri = trim ( sprintf ( '%s/%s%s', $prefix_uri, $params_uri, (empty ( $query_uri ) ? '' : '?' . $query_uri) ) );
		
		return $uri;
	}
	
}
