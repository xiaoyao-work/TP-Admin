<?php
return array(
	'TOKEN_ON'          => true, //是否开启令牌验证
	'TOKEN_NAME'        => '__hash__', //令牌验证的表单隐藏字段名称
	'TOKEN_TYPE'        => 'md5', //令牌哈希验证规则 默认为MD5
	'TOKEN_RESET'       => true, //令牌验证出错后是否重置令牌 默认为true
	'SESSION_EXPIRE'    => 3600,
	'URL_MODEL'         => 2,   //URL模式
	'MODULE_ALLOW_LIST' => array('Admin'),
	'DEFAULT_MODULE'    =>  'Admin',
    'IMAGE_UPLOAD_LIMIT'=> 5 * 1024 * 1024,
    'IMAGE_CROP_WIDTH'  => 275.00,
    'ALLOW_EXTS'        => 'jpg,jpeg,png,gif',
    'THUMBNAILS'        =>  array('small' => array('w' => 100, 'h' => 90), 'attachemnt-list' => array('w' => 80, 'h' => 44)),
);