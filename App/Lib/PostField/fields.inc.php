<?php
$fields = array(
	'text'		=> '单行文本',
	'textarea'	=> '多行文本',
	'editor'	=> '编辑器',
	'title'		=> '标题',
	'box'		=> '选项',
	'image'		=> '图片',
	'images'	=> '多文件(图片)',
	// 'posid'		=> '推荐位',
	// 'map'		=> '地图字段',
	'number'	=> '数字',
	'datetime'	=> '日期和时间',
	'keyword'	=> '关键词',
	'author'	=> '作者',
	'copyfrom' 	=> '来源',
	'linkage'	=> '联动菜单',
	'relationship'	=> '关联'
	);
	//不允许删除的字段，这些字段讲不会在字段添加处显示
	$not_allow_fields = array('title', 'username', 'post_type');
	//允许添加但必须唯一的字段
	$unique_fields = array('author', 'copyfrom');
	//禁止被禁用的字段列表
	$forbid_fields = array('title', 'updatetime', 'inputtime','listorder', 'status', 'username', 'post_type');
	//禁止被删除的字段列表
	$forbid_delete = array('title', 'updatetime', 'inputtime', 'listorder', 'status', 'username', 'post_type');
	//可以追加 JS和CSS 的字段
	$att_css_js = array('text','textarea','box','number','keyword');
?>