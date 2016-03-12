<?php
$fields = array(
	'text'		=> '单行文本',
	'textarea'	=> '多行文本',
	'editor'	=> '编辑器',
	'title'		=> '标题',
	'box'		=> '选项',
	'image'		=> '图片',
	'images'	=> '多图片',
	'number'	=> '数字',
	'datetime'	=> '日期和时间',
	'keyword'	=> '关键词',
	'author'	=> '作者',
	'copyfrom' 	=> '来源',
	'islink'	=> '转向链接',
	'readpoint'	=> '积分、点数',
	'linkage'	=> '联动菜单',
	// 'typeid'	=> '类别',
	// 'downfiles'=>'多文件上传',
	// 'posid'=>'推荐位',
	// 'groupid'=>'会员组',
	// 'template'=>'模板',
	// 'downfiles'=>'多文件上传',
	// 'map'=>'地图字段',
	// 'omnipotent'=>'万能字段'
	);
	//不允许删除的字段，这些字段讲不会在字段添加处显示
	$not_allow_fields = array('title', 'username');
	//允许添加但必须唯一的字段
	$unique_fields = array('readpoint', 'author', 'copyfrom', 'islink');
	//禁止被禁用的字段列表
	$forbid_fields = array('title','updatetime','inputtime','listorder','status','username');
	//禁止被删除的字段列表
	$forbid_delete = array('title','thumb','keywords','updatetime','inputtime','listorder','status','username');
	//可以追加 JS和CSS 的字段
	$att_css_js = array('text','textarea','box','number','keyword');
?>