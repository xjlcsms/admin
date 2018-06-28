<?php

/**
 * ErrorController
 *
 * 1xxxx : 表示管理/系统错误
 * 9xxxx : 表示Api错误
 * [2-8]xxxx : 表示用户错误
 *
 */
class ErrorController extends \Base\ApplicationController {

    public function errorAction($exception) {
    //打印完整报错信息，方便调试
        $code = $exception->getCode();
        $msg  = $exception->getMessage();
        $this->assign('msg', $msg);
        $this->assign('code', $code);
    }
}

