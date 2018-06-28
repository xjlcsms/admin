<?php

namespace Business;

class AbstractModel {
    
    protected static $_message = null; 

    /**
     * 返回 Redis 实例
     *
     * @return \Redis|null
     */
    public function getRedis() {
        static $_redis = null;

        return $_redis ?: \Yaf\Registry::get('redis');
    }
    
    
    
    /**
     * 传递消息数据
     * @param int $code
     * @param string $msg
     * @param boolean $status
     * @param array $data
     * @param string $inputName 输入数据名词
     * @return boolean
     */
    protected function getMsg($code = 0, $msg = '',$status = false, $data = null) {
        if(!$msg){
            $msg = !isset(\Business\ErrorconstModel::ERROR_MSG[$code])?\Business\ErrorconstModel::ERROR_MSG[10000]:
                \Business\ErrorconstModel::ERROR_MSG[$code];
        }
        $msgArr = array(
            'status' => (bool) $status === true ? true : false,
            'code' => (int) $code,
            'msg' => $msg,
        );

        if (!empty($data)) {
            $msgArr['data'] = $data;
        }   
        self::$_message = $msgArr;
        
        return $status;
    }
    
    
    public function getMessage(){
        return self::$_message;
    }
}
