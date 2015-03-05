<?php
return array(
  'DB_TYPE'         => 'pdo', // 数据库类型
  'DB_PREFIX'       => 'xy_', // 数据库表前缀
  'DB_PORT'         => 3306,
  // 普通配置
  'DB_USER'         => 'user', // 用户名
  'DB_PWD'          => 'pass', // 密码
  'DB_HOST'         => 'localhost',
  'DB_NAME'         =>'DB_NAME',
  // DNS 配置 采用DNS配置，必须也同时普通配置。
  'DB_DSN'          => 'mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;port=3306;dbname=DB_NAME;',
  // 二手房配置
  'esf'        => array(
    'DB_TYPE'           => 'pdo', // 数据库类型
    'DB_USER'           => 'root', // 用户名
    'DB_PREFIX'         => 'sl_', // 数据库表前缀
    'DB_PORT'           => 3306,
    'DB_PWD'            => 'root', // 密码
    'DB_HOST'           => 'localhost',
    'DB_NAME'           =>'cdfdc_usercenter',
    'DB_DSN'            => 'mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;port=3306;dbname=cdfdc_usercenter;',
  ),
  'fdc'        => array(
    'DB_TYPE'           => 'pdo', // 数据库类型
    'DB_USER'           => 'root', // 用户名
    'DB_PREFIX'         => 'sl_', // 数据库表前缀
    'DB_PORT'           => 3306,
    'DB_PWD'            => 'root', // 密码
    'DB_HOST'           => 'localhost',
    'DB_NAME'           =>'cdfdc',
    'DB_DSN'            => 'mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;port=3306;dbname=cdfdc;',
  ),
  'online'        => array(
    'DB_TYPE'           => 'pdo', // 数据库类型
    'DB_USER'           => 'root', // 用户名
    'DB_PREFIX'         => 'xy_', // 数据库表前缀
    'DB_PORT'           => 3306,
    'DB_PWD'            => 'cdfdc_mysql_xiaoli', // 密码
    'DB_HOST'           => 'localhost',
    'DB_NAME'           =>'cdfdc_admin',
    'DB_DSN'            => 'mysql:host=localhost;port=3306;dbname=cdfdc_admin;',
  )
);