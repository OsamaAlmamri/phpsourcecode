<?php

// 程序运行出现问题请先运行check.php文件！检测框架是否完整！！！

define('LyApi',dirname(__FILE__) . '/..');

require_once LyApi . '/vendor/autoload.php';

use LyApi\tools\Config;

//判读是否打开whoops

$debug_config = Config::getConfig('debug','');

if($debug_config['OPEN_WHOOPS']){
    $whoops = new \Whoops\Run();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}