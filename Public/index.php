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

// 获取并定义路径常量信息
$protocol  = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$port      = $_SERVER['SERVER_PORT'];
$disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
$domain    = $_SERVER['SERVER_NAME'];

// 网站相对路径
$script_name_info = pathinfo($_SERVER['SCRIPT_NAME']);
$base_url = ($script_name_info['dirname'] == DIRECTORY_SEPARATOR ? '' : $script_name_info['dirname']);

// Apache Or Nginx 配置根目录
$script_filename_info = pathinfo($_SERVER['SCRIPT_FILENAME']);
$doc_root  = str_replace($base_url, '', $script_filename_info['dirname']);

// 网站完整首页URL
$full_url  = "{$protocol}://{$domain}{$disp_port}{$base_url}";
define("ROOT_PATH", __DIR__ . DIRECTORY_SEPARATOR);
define("DOC_PATH", $doc_root . DIRECTORY_SEPARATOR);
define("BASE_URL", $base_url . '/');
define("UPLOAD_URL", $full_url . '/Uploads/');
define("UPLOAD_PATH", ROOT_PATH . 'Uploads' . DIRECTORY_SEPARATOR);
// 引入框架入口文件
require '../ThinkPHP/ThinkPHP.php';