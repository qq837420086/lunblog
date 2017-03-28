<?php

define('CONF_PATH','./Application/Common/Conf/');
define('RUNTIME_PATH','./Application/Runtime/');


// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);


define('TODAY', date('Y-m-d', $_SERVER['REQUEST_TIME']));
// 定义应用目录
define('APP_PATH','./Application/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

