<?php

class LogoutController extends \Base\ApplicationController {

    /**
     * 自动开启视图引擎及布局模块
     *
     * @var boolean
     */
    protected $autoRender = false;
    protected $autoLayout = false;

    public function indexAction() {
        \Business\LoginModel::getInstance()->logout();
        $this->redirect($this->getRedirectUrl(\Bootstrap::getApiDomain('wwwUrl')));
        return false;
    }

    public function iAction() {
        \Business\LoginModel::getInstance()->logout();

        $callback = $this->getRequest()->get('callback', null);
        $ouput    = (preg_match('/^[a-zA-Z_][a-zA-Z0-9_\.]*$/', $callback)) ? sprintf("%s && %s();", $callback, $callback) : '';

        header("Content-Type: application/x-javascript");
        echo $ouput;
        return false;
    }
}
