<?php

namespace Ku\Sender;

abstract class SenderAbstract {

    protected $_dst = null;
    protected $_channel = null;
    protected $_content = null;

    /**
     * 允许发送的频道列表
     *
     * @var array
     */
    protected $_channelList = array(
        'resetpassword' => 1,
        'reg'         => 1,
        'bind'         => 1,
        'bindwx' =>1,
    );

    final public function __construct() {}
    private function __clone() {}
    private function __sleep() {}

    /**
     * 发送目标
     *
     * @param string $dst
     * @return \Ku\Sender\SenderAbstract
     */
    public function setDst($dst) {
        $this->_dst = (string)$dst;

        return $this;
    }

    /**
     * 发送目标
     *
     * @return string
     */
    public function getDst() {
        return $this->_dst;
    }

    /**
     * 频道
     *
     * @param string $channel
     * @return \Ku\Sender\SenderAbstract
     */
    public function setChannel($channel) {
        if (!isset($this->_channelList[$channel])) {
            throw new \Exception('invalid channel');
        }

        $this->_channel = (string)$channel;

        return $this;
    }

    /**
     * 频道
     *
     * @return string
     */
    public function getChannel() {
        return $this->_channel;
    }
    public function setContent($content) {
        $this->_content = $content;
    }

    /**
     * @return \Ku\Sender\Parse\ParseAbstract
     */
    public function getContent() {
        return $this->_content;
    }
    abstract public function send();
}
