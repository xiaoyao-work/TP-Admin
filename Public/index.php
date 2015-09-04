<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);
define('BUILD_DIR_SECURE', false);
// 定义应用目录
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR);

// 获取并定义路径常量信息
$protocol  = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$port      = $_SERVER['SERVER_PORT'];
$disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
$domain    = $_SERVER['SERVER_NAME'];

$doc_root  = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']);
$base_url  = str_replace($doc_root, '', str_replace('\\', '/', __DIR__));
$full_url  = "{$protocol}://{$domain}{$disp_port}{$base_url}";
define("ROOT_PATH", __DIR__ . DIRECTORY_SEPARATOR);
define("DOC_PATH", $doc_root . DIRECTORY_SEPARATOR);
define("BASE_URL", $base_url . '/');
define("UPLOAD_URL", $full_url . '/Uploads/');
define("UPLOAD_PATH", ROOT_PATH . 'Uploads' . DIRECTORY_SEPARATOR);

// 引入ThinkPHP入口文件
require '../ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单