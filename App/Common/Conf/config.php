<?php
require dirname(__FILE__) . "/bootstrap.php";
$db_conf = require dirname(__FILE__) . "/database.php";
$base_conf = array(
	'TOKEN_ON'          => true, //是否开启令牌验证
	'TOKEN_NAME'        => '__hash__', //令牌验证的表单隐藏字段名称
	'TOKEN_TYPE'        => 'md5', //令牌哈希验证规则 默认为MD5
	'TOKEN_RESET'       => false, //令牌验证出错后是否重置令牌 默认为true
	'SESSION_EXPIRE'    => 3600,
	'URL_MODEL'         => 2,   //URL模式
	'MODULE_ALLOW_LIST'     =>  array('Admin','Home'),
	'DEFAULT_MODULE'        =>  'Admin'
);
return array_merge($db_conf, $base_conf);