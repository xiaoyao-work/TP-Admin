<?php
/**
 * 模板风格列表
 */
function template_list() {
	$template_path = APP_PATH . 'Home/View';
	$list = glob($template_path . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR | GLOB_NOSORT);
	$arr = array();
	foreach ($list as $key=>$v) {
		$dirname = basename($v);
		if (file_exists($v.DIRECTORY_SEPARATOR.'config.php')) {
			$template_config = include $v.DIRECTORY_SEPARATOR.'config.php';
			$arr[$key]['name'] = $template_config['name'];
		} else {
			$arr[$key]['name'] = $dirname;
		}
		$arr[$key]['dirname']= $dirname;
	}
	ksort($arr);
	return $arr;
}

/**
 * 页面模板
 */
function get_page_templates() {
	$site_id = get_siteid();
	$site_info = get_site_info($site_id);
	$template_path = APP_PATH . 'Home/View/' . $site_info['template'];
	$template_files = glob($template_path . '/Post/page*.html', GLOB_NOSORT | GLOB_NOSORT);
	$arr = array();
	foreach ($template_files as $key => $file) {
		$file_name = basename($file, '.html');
		if (file_exists($template_path.DIRECTORY_SEPARATOR.'config.php')) {
			$template_config = include $template_path.DIRECTORY_SEPARATOR.'config.php';
			if (isset($template_config['Post'][$file_name])) {
				$arr[$file_name] = $template_config['Post'][$file_name];
			} else {
				$arr[$file_name] = $file_name;
			}
		} else {
			$arr[$file_name] = $file_name;
		}
	}
	ksort($arr);
	return $arr;
}


/**
 * 模型详情页模板
 */
function get_post_templates() {
	$site_id = get_siteid();
	$site_info = get_site_info($site_id);
	$template_path = APP_PATH . 'Home/View/' . $site_info['template'];
	$template_files = glob($template_path . '/Post/post*.html', GLOB_NOSORT | GLOB_NOSORT);
	$arr = array();
	foreach ($template_files as $key => $file) {
		$file_name = basename($file, '.html');
		if (file_exists($template_path.DIRECTORY_SEPARATOR.'config.php')) {
			$template_config = include $template_path.DIRECTORY_SEPARATOR.'config.php';
			if (isset($template_config['Post'][$file_name])) {
				$arr[$file_name] = $template_config['Post'][$file_name];
			} else {
				$arr[$file_name] = $file_name;
			}
		} else {
			$arr[$file_name] = $file_name;
		}
	}
	ksort($arr);
	return $arr;
}

/**
 * 设置站点
 */
function set_siteid($id) {
    if (!empty($id)) session('siteid', $id);
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

	$args = explode(',',$args);

	// 允许上传文件大小
	$allowupload = $site_setting['upload_maxsize'];
	// 是否加水印
	$watermark_enable = empty($args[5]) ? $site_setting['watermark_enable'] : $args['5'];
	// 允许上传文件数目
	$arr['file_upload_limit'] = intval($args[0]) ? intval($args[0]) : '8';
	// 允许上传文件类型
	$upload_allowext = empty($args[1]) ? $site_setting['upload_allowext'] : $args[1];

	$arr['file_types'] = $upload_allowext;
	$arr['file_types_post'] = $args[1];
	$arr['allowupload'] = sizecount($allowupload * 1024);
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


function createModelFormHtml($modelids, $data='') {
    require MODEL_PATH.'content_form.class.php';
    $content_form = new \content_form($modelids);
    $forminfos = $content_form->get($data);
    if(is_array($forminfos)) {
        foreach($forminfos as $field=>$info) {
            if($info['isomnipotent']) continue;
            if($info['formtype']=='omnipotent') {
                foreach($forminfos as $_fm=>$_fm_value) {
                    if($_fm_value['isomnipotent']) {
                        $info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
                    }
                }
            }
            ?>
            <tr <?php if (isset($info['setting']['ishidden']) && $info['setting']['ishidden']) { echo 'style="display: none;"'; } ?>>
                <th width="80"><?php if($info['star']){ ?> <font color="red">*</font><?php } ?> <?php echo $info['name']?></th>
                <td><?php echo "\n" . $info['form'] . $info['tips'] . "\n"; ?></td>
            </tr>
            <?php
        }
    }
    ?>
    <script type="text/javascript">
        <?php echo $content_form->formValidator; ?>
    </script>
    <?php
}

function system_information($data) {
	$update = logic('update');
	$notice_url = $update->notice();
	$string = base64_decode('PHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiIHNyYz0iTk9USUNFX1VSTCI+PC9zY3JpcHQ+');
	echo $data.str_replace('NOTICE_URL',$notice_url,$string);
}



