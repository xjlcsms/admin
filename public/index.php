<?php

/**
 * 路径分隔符
 *
 * window = \
 * linux  = /
 *
 * 避免因环境不同而产生麻烦
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * 应用的根目录
 */
define('APPLICATION_PATH', realpath('..' . DS));

/**
 * Web入口目录
 */
define('PUBLIC_PATH', realpath(dirname(__FILE__)));

$app = new \Yaf\Application(APPLICATION_PATH . DS . 'conf' . DS . 'application.ini');
$app->bootstrap();
$app->run();
