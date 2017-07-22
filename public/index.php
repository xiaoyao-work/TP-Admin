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

if (!file_exists(realpath("../install.lock"))) {
    header('Location: ./install/index.php');
    exit();
}

define("APP_ENV", isset($_SERVER['APP_ENV']) ? strtolower($_SERVER['APP_ENV']) : 'local');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', in_array(APP_ENV, ['local', 'testing']));

define('BASE_DOMAIN', 'hhailuocms.com');

// 引入框架入口文件
require '../Framework/ThinkPHP.php';
