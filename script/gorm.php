<?php
if(!isset($argv[0])){
    echo 'invalid request';
    return false;
}

unset($argv[0]);
if(!isset($argv[1])){
    $argv[1] = '+H';
}
define('DS', '/'); // 不支持 \
define('APPLICATION_PATH', realpath(dirname(__DIR__)));

$app  = new \Yaf\Application(APPLICATION_PATH . DS . 'conf' . DS . 'application.ini');

$build  = new \Ormbuild\Build($argv);
$build->before();
$build->process();
$build->after();
unset($build);

