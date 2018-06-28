<?php

namespace Config;

class Db extends \Config\ConfigAbstract {

    public function init() {
        return array(
            'host'     => '127.0.0.1',
            'dbname'   => 'jiankong',
            'username' => 'root',
            'passwd'   => 'p123456',
            'port'     => '3306',
            'options'  => array("SET NAMES 'utf8'")
        );
    }

}
