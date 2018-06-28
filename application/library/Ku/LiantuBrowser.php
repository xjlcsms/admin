<?php
namespace Ku;
class LiantuBrowser extends \Ku\Browser{

	protected function checkPlatform(){
        if (stripos($this->_agent, 'android') !== false) {
            $this->_platform = parent::PLATFORM_ANDROID;
            $this->setMobile(true);
        } elseif (stripos($this->_agent, 'linux') !== false) {
            $this->_platform = parent::PLATFORM_LINUX;
        } elseif (stripos($this->_agent, 'Nokia') !== false) {
            $this->_platform = parent::PLATFORM_NOKIA;
            $this->setMobile(true);
        } elseif (stripos($this->_agent, 'BlackBerry') !== false) {
            $this->_platform = parent::PLATFORM_BLACKBERRY;
            $this->setMobile(true);
        } elseif (stripos($this->_agent, 'Windows Phone') !== false) {
            $this->_platform = parent::PLATFORM_WINDOWSPHONE;
            $this->setMobile(true);
        }elseif (stripos($this->_agent, 'like Mac OS X') !== false) {
            $this->_platform = parent::PLATFORM_IOS;
        }
	}

	    /**
     * Protected routine to determine the browser type.
     *
     * @return bool True if the browser was detected otherwise false
     */
    protected function checkBrowsers()
    {
        return (
            $this->checkQQ() ||
            $this->checkTaobao() ||
            $this->checkAlipay() ||
            $this->checkWechat() ||
            $this->checkWeibo() ||
            $this->checkDouban() ||
            $this->checkBrowserAndroid()
        );
    }

}