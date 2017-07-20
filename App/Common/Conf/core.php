<?php
/**
 * 自定义系统载入
 */
return [
	// 配置文件
	'configs' => [
		'config' . CONF_EXT, // 应用公共配置
		'app' . CONF_EXT, // 应用公共配置
		'database' . CONF_EXT,
		'space' . CONF_EXT,
	],

	// 函数和类文件
	'core'    => [
		THINK_PATH . 'Common/functions.php',
		COMMON_PATH . 'Common/function.php',
		COMMON_PATH . 'Common/iconv.func.php',
		CONF_PATH . APP_ENV . '/constants.php',
		CONF_PATH . 'constants.php',
		CONF_PATH . 'Common/iconv.func.php',
		CORE_PATH . 'Hook' . EXT,
		CORE_PATH . 'App' . EXT,
		CORE_PATH . 'Dispatcher' . EXT,
		//CORE_PATH . 'Log'.EXT,
		CORE_PATH . 'Route' . EXT,
		CORE_PATH . 'Controller' . EXT,
		CORE_PATH . 'View' . EXT,
		BEHAVIOR_PATH . 'ParseTemplateBehavior' . EXT,
		BEHAVIOR_PATH . 'ContentReplaceBehavior' . EXT,
	],

	// 行为扩展定义
	'tags'    => [
		'app_init'        => [
		],
		'app_begin'       => [
			'Behavior\ReadHtmlCacheBehavior', // 读取静态缓存
		],
		'app_end'         => [
			'Behavior\ShowPageTraceBehavior', // 页面Trace显示
		],
		'view_parse'      => [
			'Behavior\ParseTemplateBehavior', // 模板解析 支持PHP、内置模板引擎和第三方模板引擎
		],
		'template_filter' => [
			'Behavior\ContentReplaceBehavior', // 模板输出替换
		],
		'view_filter'     => [
			'Behavior\WriteHtmlCacheBehavior', // 写入静态缓存
		],
	],

	// 别名定义
	'alias'   => [
		'Think\Log'               => CORE_PATH . 'Log' . EXT,
		'Think\Log\Driver\File'   => CORE_PATH . 'Log/Driver/File' . EXT,
		'Think\Exception'         => CORE_PATH . 'Exception' . EXT,
		'Think\Model'             => CORE_PATH . 'Model' . EXT,
		'Think\Db'                => CORE_PATH . 'Db' . EXT,
		'Think\Template'          => CORE_PATH . 'Template' . EXT,
		'Think\Cache'             => CORE_PATH . 'Cache' . EXT,
		'Think\Cache\Driver\File' => CORE_PATH . 'Cache/Driver/File' . EXT,
		'Think\Storage'           => CORE_PATH . 'Storage' . EXT,
	],
];