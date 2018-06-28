<?php
/**
 * 简单表单验证
 * 表单抽象类
 *
 * @author wuzhihua
 */

namespace Ku\Form;

class FormAbstract {

    protected $_elements = array();
    protected $_rval = array();
    protected $_rerr = array();
    protected $_request = null;
    protected $_name = array(
      'domain'=>'域名',
        'title' =>'标题',
        'keyword'=>'关键词',
        'device' => '设备类型',
        'nu' =>'快递单号',
        'com' => '快递公司名字',
        'nodeid' => '节点服务器id',
        'url' => 'url',


    );
    /**
     * 运行
     *
     * @param array $elements
     * @return \Ku\Escort
     */
    public function runing(array $elements = array()) {
        $request = \Yaf\Dispatcher::getInstance()->getRequest();
        $this->_request = $request;
        $elements = (empty($elements)) ? $this->_elements : $elements;
        foreach ($elements as $element) {
            $model = new \Ku\Std($element);
            //过滤
            $param = trim($request->get($model->name, ''));
            if(isset($model->filter)){
                $filters = $model->filter;
                if(is_string($filters)){
                    $filters = array($model->filter);
                }
                foreach ($filters as $filter):
                    switch ($filter):
                        case 'int':
                            $param = intval($param);
                            break;
                        case 'char':
                            $param = \Ku\Tool::filter($param);
                            break;
                        case 'space':
                            $data = array();
                            $nus = explode("\n",$param);
                            foreach($nus as $nu) {
                                $param = \Ku\Tool::deleteSpace($nu);
                                if($nu == null)
                                    continue;
                                $data[] = $param;
                            }
                            $data = array_unique($data);
                            $param = implode("\n",$data);
                            break;
                        case 'domain':
                            $data = array();
                            $domains = explode("\n",$param);
                            foreach($domains as $domain) {
                                $param = \Ku\Domain::domain($domain);
                                $data[] = $param;
                                }
                            $data = array_unique($data);
                            $param = implode("\n",$data);
                            break;
                        case 'strtolower':
                            $param = strtolower($param);
                            break;

                    endswitch;
                endforeach;
            }
            //验证
            if (isset($model->verify)) {
                $verifys = $model->verify;
                if(is_string($model->verify)){
                    $verifys = array($model->verify =>0);
                }
                foreach ($verifys as $verify=>$value){
                    if (!method_exists($this, $verify)) {
                        if (!method_exists('\Ku\Verify', $verify)) {
                            throw new Exception('\Ku\Verify not exist ' . $model->verify);
                        }
                        if ($param && \Ku\Verify::{$verify}($param,$value) === false) {
                            $this->_rerr[$model->name] = $model->msg;
                        }
                    } else {
                        if ($param && !$this->{$verify}($param)) {
                            $this->_rerr[$model->name] = $model->msg;
                        }
                    }
                }
            }
            //必填
            if (isset($model->required) && $model->required) {
                if (empty($param)) {
                    $this->_rerr[$model->name] = $this->_name[$model->name].'不能为空';
                }
            }
            $this->_rval[$model->name] = $param;
        }
        return $this;
    }

    /**
     * 重置
     */
    public function reset() {
        $this->_rerr = array();
        $this->_rval = array();
    }
    
    
    public function getRval(){
        return $this->_rval;
    }
    
    public function getRerr(){
        return $this->_rerr;
    }

}
