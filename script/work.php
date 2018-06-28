<?php
if (!isset($argv[1])){
    throw new \Exception("Invalid argv");
}

define('DS', '/'); // 不支持 \
define('APPLICATION_PATH', realpath(dirname(__DIR__)));

$app  = new \Yaf\Application(APPLICATION_PATH . DS . 'conf' . DS . 'application.ini');
$task = '\\Cron\\' . ucfirst($argv[1]);
$std  = new $task($argv, $app);

if (!$std instanceof \Cron\CronAbstract) {
    throw new \Exception("Invalid task");
}
$std->main();

unset($std);
unset($app);
