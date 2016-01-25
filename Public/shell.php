<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);
define('BUILD_DIR_SECURE', false);
// 定义应用目录
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR);
define('RUNTIME_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Runtime' . DIRECTORY_SEPARATOR);

// 引入框架入口文件
require dirname(__DIR__) . '/ThinkPHP/ThinkPHP.php';
