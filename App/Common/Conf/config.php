<?php
return [
	'SESSION_OPTIONS'       => [
		'name'   => 'tp3_sessionid',
		'expire' => 864000,
		'domain' => '.' . BASE_DOMAIN,
		'path'   => '/',
	],

	// 'SHOW_PAGE_TRACE'   => true,
	/* Cookie设置 */
	'COOKIE_EXPIRE'         => 0, // Cookie有效期
	'COOKIE_DOMAIN'         => '' . BASE_DOMAIN, // Cookie有效域名
	'COOKIE_PATH'           => '/', // Cookie路径

	'TOKEN_ON'              => true, //是否开启令牌验证
	'TOKEN_NAME'            => '__hash__', //令牌验证的表单隐藏字段名称
	'TOKEN_TYPE'            => 'md5', //令牌哈希验证规则 默认为MD5
	'TOKEN_RESET'           => true, //令牌验证出错后是否重置令牌 默认为true

	//邮箱配置
	'EMAIL_CONF'            => [
		'language'   => 'zh_cn',
		'host'       => 'smtp.163.com',
		'port'       => 465,
		'user'       => 'tp3@163.com',
		'pass'       => 'tp3',
		'smtpsecure' => 'ssl',
		'ishtml'     => true,
		'smtpauth'   => true,
	],

	'DEFAULT_MODULE'        => 'Home',
	'URL_MODEL'             => 2, //URL模式
	'MODULE_ALLOW_LIST'     => ['Admin', 'Home'],

	'APP_SUB_DOMAIN_DEPLOY' => 1, // 开启子域名或者IP配置
	'APP_SUB_DOMAIN_RULES'  => [
		'admin.' . BASE_DOMAIN    => 'Admin',
		'{domain}.' . BASE_DOMAIN => 'Home',
	],

	'URL_CASE_INSENSITIVE'  => false,
	'UUID'                  => '',
	'BASE_DOMAIN'           => BASE_DOMAIN,

];