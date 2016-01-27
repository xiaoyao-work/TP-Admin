<?php
/**
* 模板风格列表
* @param integer $siteid 站点ID，获取单个站点可使用的模板风格列表
* @param integer $disable 是否显示停用的{1:是,0:否}
*/

function template_list($siteid = '', $disable = 0) {
	$list = glob(TMPL_PATH. C("DEFAULT_GROUP") .DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);
	$arr = array();
	foreach ($list as $key=>$v) {
		$dirname = basename($v);
		if (file_exists($v.DIRECTORY_SEPARATOR.'config.php')) {
			$template_config = include $v.DIRECTORY_SEPARATOR.'config.php';
			$arr[$key]['name'] = $template_config['name'];
			if (!$disable && isset($template_config['disable']) && $template_config['disable'] == 1) {
				unset($arr[$key]);
				continue;
			}
		} else {
			$arr[$key]['name'] = $dirname;
		}
		$arr[$key]['dirname']= $dirname;
	}
	return $arr;
}


/**
* 获取站点的信息
* @param $siteid   站点ID
*/
function siteinfo($siteid) {
	static $sitelist;
	if (empty($sitelist)) $sitelist  = include COMMON_PATH . "Cache/sitelist.php";
	return isset($sitelist[$siteid]) ? $sitelist[$siteid] : '';
}

/**
* 加载后台模板
* @param string $file 文件名
* @param string $m 模型名
*/
function admin_tpl($file, $g='',$t='',$m='') {
	$path = TMPL_PATH;
	$path .= empty($g) ? GROUP_NAME.DIRECTORY_SEPARATOR : $g.DIRECTORY_SEPARATOR;
	if ($t) {
		$path .= $t.DIRECTORY_SEPARATOR;
	} elseif (C('DEFAULT_THEME')) {
		$path .= C('DEFAULT_THEME').DIRECTORY_SEPARATOR;
	}
	$path .= empty($m) ? MODULE_NAME.DIRECTORY_SEPARATOR : $m.DIRECTORY_SEPARATOR;
	return $path.$file.'.php';
}

function tree_to_array($tree, &$cat = array(), $level = 1) {
	foreach ($tree as $key => $value) {
		$temp = $value;
		if ($temp['_child']) {
			$temp['_child'] = true;
			$temp['level'] = $level;
			$cat[$value['id']] = $temp;
		} else {
			$temp['_child'] = false;
			$temp['level'] = $level;
			$cat[$value['id']] = $temp;
		}
		if ($value['_child']) {
			tree_to_array($value['_child'],$cat, ($level + 1));
		}
	}
}

function cat_empty_deal($cat, $next_parentid, $pid='parentid', $empty = "　") {
	$str = "";
	if ($cat[$pid]) {
		for ($i=2; $i < $cat['level']; $i++) {
			$str .= $empty."│";
		}
		if ($cat[$pid] != $next_parentid && !$cat['_child']) {
			$str .= $empty."└─&nbsp;";
		} else {
			$str .= $empty."├─&nbsp;";
		}
	}
	return $str;
}


/**
* 根据版位的类型，得到版位的配置信息。如广告类型等
* @param string  $type 版位的类型,默认情况下是一张图片或者动画
* return boolean
*/

function get_setting($type) {
	$data = $poster_template = array();
	$poster_template = $poster_template = include ROOT_PATH.'Conf'.DIRECTORY_SEPARATOR."Admin".DIRECTORY_SEPARATOR."space_config.php";
	if (is_array($poster_template) && !empty($poster_template)) {
		$data = $poster_template[$type];
	} else {
		switch($type) {
			case 'banner':
			$data['type'] = array('images' => L('photo'), 'flash' => L('flash'));
			$data['num'] = 1;
			break;

			case 'fixure':
			$data['type'] = array('images' => L('photo'), 'flash' => L('flash'));
			$data['num'] = 1;
			break;

			case 'float':
			$data['type'] = array('images' => L('photo'), 'flash' => L('flash'));
			$data['num'] = 1;
			break;

			case 'couplet':
			$data['type'] = array('images' => L('photo'), 'flash' => L('flash'));
			$data['num'] = 2;
			break;

			case 'imagechange':
			$data['type'] = array('images' => L('photo'));
			$data['num'] = 1;
			break;

			case 'imagelist':
			$data['type'] = array('images' => L('photo'));
			$data['num'] = 1;
			break;

			case 'text':
			$data['type'] = array('text' => L('title'));
			break;

			case 'code':
			$data['type'] = array('text' => L('title'));
			break;

			default :
			$data['type'] = array('images' => L('photo'), 'flash' => L('flash'));
			$data['num'] = 1;
		}
	}
	return $data;
}

/**
* 返回附件类型图标
* @param $file 附件名称
* @param $type png为大图标，gif为小图标
*/
function file_icon($file,$type = 'png') {
	$ext_arr = array('doc','docx','ppt','xls','txt','pdf','mdb','jpg','gif','png','bmp','jpeg','rar','zip','swf','flv');
	$ext = fileext($file);
	if($type == 'png') {
		if($ext == 'zip' || $ext == 'rar') $ext = 'rar';
		elseif($ext == 'doc' || $ext == 'docx') $ext = 'doc';
		elseif($ext == 'xls' || $ext == 'xlsx') $ext = 'xls';
		elseif($ext == 'ppt' || $ext == 'pptx') $ext = 'ppt';
		elseif ($ext == 'flv' || $ext == 'swf' || $ext == 'rm' || $ext == 'rmvb') $ext = 'flv';
		else $ext = 'do';
	}
	if(in_array($ext,$ext_arr)) return __ROOT__.'/Public/images/ext/'.$ext.'.'.$type;
	else return __ROOT__.'/statics/images/ext/blank.'.$type;
}

/**
* 读取upload配置类型
* @param array $args 上传配置信息
*/
function getUploadParams($args) {
	$siteid = get_siteid();
	$site_setting = get_site_setting($siteid);
	$site_allowext = $site_setting['upload_allowext'];
	$args = explode(',',$args);
	// $allowupload = empty($args[2]) ? $site_setting['upload_maxsize'] : $args[2];
	$allowupload = $site_setting['upload_maxsize'];
	$watermark_enable = empty($args[5]) ? $site_setting['watermark_enable'] : $args['5'];

	$arr['file_upload_limit'] = intval($args[0]) ? intval($args[0]) : '8';
	$args['1'] = empty($args[1]) ? $site_allowext : $args[1];
	$arr_allowext = explode('|', $args[1]);
	$allowexts = array();
	foreach($arr_allowext as $k=>$v) {
		$allowexts[] = '*.'.$v;
	}
	$upload_allowext = implode(';', $allowexts);
	$arr['file_types'] = $upload_allowext;
	$arr['file_types_post'] = $args[1];
	$arr['allowupload'] = sizecount($allowupload * 1024);
	$arr['thumb_width'] = intval($args[3]);
	$arr['thumb_height'] = intval($args[4]);
	$arr['watermark_enable'] = $watermark_enable;
	return $arr;
}

function my_explode($split, $string) {
	$arr = explode($split, $string);
	$temp = array();
	if (is_array($arr)) {
		foreach ($arr as $key => $value) {
			if (empty($value)) continue;
			$temp[] = $value;
		}
	}
	return $temp;
}

/**
* 调用关联菜单
* @param $linkageid 联动菜单id
* @param $id 生成联动菜单的样式id
* @param $defaultvalue 默认值
*/
function menu_linkage($linkageid = 0, $id = 'linkid', $defaultvalue = 0) {
  	$linkages = D('Linkage')->where(array('keyid' => $linkageid))->order('listorder asc')->field('id, name, parentid')->select();
  	$tree = list_to_tree($linkages, 'id', 'parentid');
  	$html = "";
  	if(!defined('LINKAGE_INIT_1')) {
  		define('LINKAGE_INIT_1', 1);
  		$html .= '<script type="text/javascript" src="'. asset('js/linkage/linkagesel.js') .'"></script>';
  	}
  	$html .= $defaultvalue ? '<input type="hidden" name="info[' . $id .']" value="'.$defaultvalue.'" id="'. $id . '-' . $linkageid .'">' : '<input type="hidden" name="info[' . $id .']" value="" id="'. $id . '-' . $linkageid .'">';
  	$html .='<select class="tp-admin-select-'.$id.'" id="'.$id.'" width="100"></select>';
  	$html .= '<script type="text/javascript">
  	$(function(){
	  		var opts = {
	  			data: ' . json_encode($tree) . ',
	  			selStyle: "margin-left: 3px;",
	  			select:  "#' . $id . '",
	  			dataReader: {id: "id", name: "name", cell: "_child"},
	  			defVal: [' . str_replace('-', ',', $defaultvalue) . '],
	  			head: false
	  		};
	  		var linkageSel_'.$linkageid.' = new LinkageSel(opts);
	  		linkageSel_'.$linkageid.'.onChange(function(){
	  			var input = $("#'. $id . '-' . $linkageid .'")
	  			ids = this.getSelectedDataArr("id");
	  			input.val(ids.join("-"));
	  		});
	});
	</script>';

	return $html;
}