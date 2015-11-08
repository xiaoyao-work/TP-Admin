<?php
$system_config = require "Conf/system.php";
$database_config = require "Conf/database.php";
$config = array(
  'TOKEN_ON'          => true, //是否开启令牌验证
  'TOKEN_NAME'        => '__hash__', //令牌验证的表单隐藏字段名称
  'TOKEN_TYPE'        => 'md5', //令牌哈希验证规则 默认为MD5
  'TOKEN_RESET'       => false, //令牌验证出错后是否重置令牌 默认为true
  // 'SESSION_TYPE'      => 'Db',
  // 'SESSION_TABLE'     => 'tb_session',
  'SESSION_EXPIRE'    => 3600,
  'URL_MODEL'         => 2,   //URL模式
  'APP_GROUP_LIST'    => 'Admin', //项目分组设定
  'DEFAULT_GROUP'     => 'Admin', //默认分组

  /*'APP_SUB_DOMAIN_DEPLOY'=>1, // 开启子域名配置
  'APP_SUB_DOMAIN_RULES'=>array(
    'wap'=>array('Wap/'),
    'admin'=>array('Admin/'),
    'www'=>array('Home/')
    ),*/
  'SITE_URL' => FULL_URL,
  'LANG_SWITCH_ON' => true
  );
return array_merge($config, $system_config, $database_config);
?>