<?php
defined('IN_ADMIN')   or define('IN_ADMIN', 'true');
$config = require 'Conf/config.php';
$array=array(
  'SESSION_AUTO_START'        =>  true,
  'TMPL_ACTION_ERROR'         =>  'Public:success',   // 默认错误跳转对应的模板文件
  'TMPL_ACTION_SUCCESS'       =>  'Public:success',   // 默认成功跳转对应的模板文件
  'USER_AUTH_ON'              =>  true,
  'USER_AUTH_TYPE'            =>  2,          // 默认认证类型 1 登录认证 2 实时认证
  'USER_AUTH_KEY'             =>  'authId',     // 用户认证SESSION标记
  'ADMIN_AUTH_KEY'            =>  'administrator',    // 超级管理员标志
  'USER_AUTH_MODEL'           =>  'User',       // 默认验证数据表模型
  'AUTH_PWD_ENCODER'          =>  'md5',        // 用户认证密码加密方式
  'USER_AUTH_GATEWAY'         =>  '/Public/login',  // 默认认证网关
  'NOT_AUTH_MODULE'           =>  'Public',     // 默认无需认证模块
  'REQUIRE_AUTH_MODULE'       =>  '',         // 默认需要认证模块
  'NOT_AUTH_ACTION'           =>  'public_session_life,change_site',         // 默认无需认证操作
  'REQUIRE_AUTH_ACTION'       =>  '',         // 默认需要认证操作
  'GUEST_AUTH_ON'             =>  false,          // 是否开启游客授权访问
  'GUEST_AUTH_ID'             =>  0,              // 游客的用户ID
  'RBAC_ROLE_TABLE'           =>  'xy_role',
  'RBAC_USER_TABLE'           =>  'xy_role_user',
  'RBAC_ACCESS_TABLE'         =>  'xy_access',
  'RBAC_NODE_TABLE'           =>  'xy_node',
  // 'URL_CASE_INSENSITIVE'      =>  true,
  'AD_TYPE'                   => array('banner'=>'矩形横幅', 'fixure'=>'固定位置', 'float'=>'漂浮', 'couplet'=>'对联', 'imagechange'=>'图片轮换',  'imagelist'=>'图片列表', 'text'=>'文字', 'code'=>'代码'),
  'TMPL_PARSE_STRING'         => array(
    '__PUBLIC__' => __APP__.'/Public',
    'IMG_PATH' => __APP__."/Public/images",
    'CSS_PATH' => __APP__."/Public/css",
    'JS_PATH' => __APP__."/Public/js"
    )
  );
return array_merge($config,$array);
?>