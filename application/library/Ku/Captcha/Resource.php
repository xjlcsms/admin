<?php

namespace Ku\Captcha;

final class Resource {

    private function __construct() {}
    private function __sleep() {}
    private function __clone() {}

    /**
     * 验证码字符集
     *
     * @return array
     */
    public static function getChar(){
        return array(
            '0','1','2','3','4','5','6','7','8','9'
        );
    }

    /**
     * 颜色
     *
     * @return array
     */
    public static function getColors(){
        array(
            'black' => array(0x00, 0x00, 0x00), // black
            'blue'  => array(0x28, 0x28, 0xFF), // blue
            'green' => array(0x00, 0xA6, 0x00), // green
            'red'   => array(0xCE, 0x00, 0x00)  // red
        );
    }

    /**
     * 字体颜色
     *
     * @return array
     */
    public static function getFontColors(){
        return array(
            array(0x00, 0x00, 0x00), // black
            array(0x28, 0x28, 0xFF), // blue
            array(0x00, 0xA6, 0x00), // green
            array(0xCE, 0x00, 0x00)  // red
        );
    }

    /**
     * 字体类型
     *
     * @return array
     */
    public static function getFontType(){
        return array('AntykwaBold', 'Duality', 'Jura');
    }

}
