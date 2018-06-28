<?php

namespace Ku\Log;

final class Data {

    private function __construct() {}
    private function __clone() {}
    private function __sleep() {}

    public static function encode($data) {
        return (time() . str_replace('=', '', base64_encode($data)));
    }

    public static function dencode($data) {
        $ret = array();

        $ret['time'] = substr($data, 0, 10);
        $ret['data'] = base64_decode(substr($data, 11));

        return $ret;
    }
}
