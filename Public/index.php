<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------
// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);
define('BUILD_DIR_SECURE', false);
// 定义应用目录
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR);
define('RUNTIME_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Runtime' . DIRECTORY_SEPARATOR);

// Server params
$scriptName = $_SERVER['SCRIPT_NAME']; // <-- "/foo/index.php"
$requestUri = $_SERVER['REQUEST_URI']; // <-- "/foo/bar?test=abc" or "/foo/index.php/bar?test=abc"
// Physical path
if (strpos($requestUri, $scriptName) !== false) {
    $physicalPath = $scriptName; // <-- Without rewriting
} else {
    $physicalPath = str_replace('\\', '', dirname($scriptName)); // <-- With rewriting
}
$script_name = rtrim($physicalPath, '/'); // <-- Remove trailing slashes

// 获取并定义路径常量信息
define("ROOT_PATH", __DIR__ . DIRECTORY_SEPARATOR);
define("UPLOAD_PATH", ROOT_PATH . 'Uploads' . DIRECTORY_SEPARATOR);
define("BASE_URL", $script_name . '/');
define("UPLOAD_URL", $script_name . '/Uploads/');

// 引入框架入口文件
require '../ThinkPHP/ThinkPHP.php';