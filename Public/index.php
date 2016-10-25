<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------
// 应用入口文件

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    die('require PHP > 5.4.0 !');
}
if (!file_exists(dirname(__DIR__) . '/install.lock')) {
    header("Location: ./install/index.php");
}
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);

$scriptName = $_SERVER['SCRIPT_NAME'];
$requestUri = $_SERVER['REQUEST_URI'];
if (strpos($requestUri, $scriptName) !== false) {
    $physicalPath = $scriptName;
} else {
    $physicalPath = str_replace('\\', '', dirname($scriptName));
}
$script_name = rtrim($physicalPath, '/');
define("BASE_URL", $script_name . '/');

// 引入框架入口文件
require '../Framework/ThinkPHP.php';