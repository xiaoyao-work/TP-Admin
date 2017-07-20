<?php
	/**
	 * 模板风格列表
	 */
	function template_list() {
		$template_path = APP_PATH . 'Home/View';
		$list          = glob($template_path . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR | GLOB_NOSORT);
		$arr           = [];
		foreach ($list as $key => $v) {
			$dirname = basename($v);
			if (file_exists($v . DIRECTORY_SEPARATOR . 'config.php')) {
				$template_config   = include $v . DIRECTORY_SEPARATOR . 'config.php';
				$arr[$key]['name'] = $template_config['name'];
			} else {
				$arr[$key]['name'] = $dirname;
			}
			$arr[$key]['dirname'] = $dirname;
		}
		ksort($arr);
		return $arr;
	}

	/**
	 * 页面模板
	 */
	function get_page_templates() {
		$site_id        = get_siteid();
		$site_info      = get_site_info($site_id);
		$template_path  = APP_PATH . 'Home/View/' . $site_info['template'];
		$template_files = glob($template_path . '/Post/page*.html', GLOB_NOSORT | GLOB_NOSORT);
		$arr            = [];
		foreach ($template_files as $key => $file) {
			$file_name = basename($file, '.html');
			if (file_exists($template_path . DIRECTORY_SEPARATOR . 'config.php')) {
				$template_config = include $template_path . DIRECTORY_SEPARATOR . 'config.php';
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
		$site_id        = get_siteid();
		$site_info      = get_site_info($site_id);
		$template_path  = APP_PATH . 'Home/View/' . $site_info['template'];
		$template_files = glob($template_path . '/Post/post*.html', GLOB_NOSORT | GLOB_NOSORT);
		$arr            = [];
		foreach ($template_files as $key => $file) {
			$file_name = basename($file, '.html');
			if (file_exists($template_path . DIRECTORY_SEPARATOR . 'config.php')) {
				$template_config = include $template_path . DIRECTORY_SEPARATOR . 'config.php';
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
		if (!empty($id)) {
			session('siteid', $id);
		}

	}

	function cat_empty_deal($cat, $next_parentid, $pid = 'parentid', $empty = "　") {
		$str = "";
		if ($cat[$pid]) {
			for ($i = 2; $i < $cat['level']; $i++) {
				$str .= $empty . "│";
			}
			if ($cat[$pid] != $next_parentid && !$cat['_child']) {
				$str .= $empty . "└─&nbsp;";
			} else {
				$str .= $empty . "├─&nbsp;";
			}
		}
		return $str;
	}

	/**
	 * 返回附件类型图标
	 * @param $file 附件名称
	 * @param $type png为大图标，gif为小图标
	 */
	function file_icon($file, $type = 'png') {
		$ext_arr = ['doc', 'docx', 'ppt', 'xls', 'txt', 'pdf', 'mdb', 'jpg', 'gif', 'png', 'bmp', 'jpeg', 'rar', 'zip', 'swf', 'flv'];
		$ext     = fileext($file);
		if ($type == 'png') {
			if ($ext == 'zip' || $ext == 'rar') {
				$ext = 'rar';
			} elseif ($ext == 'doc' || $ext == 'docx') {
				$ext = 'doc';
			} elseif ($ext == 'xls' || $ext == 'xlsx') {
				$ext = 'xls';
			} elseif ($ext == 'ppt' || $ext == 'pptx') {
				$ext = 'ppt';
			} elseif ($ext == 'flv' || $ext == 'swf' || $ext == 'rm' || $ext == 'rmvb') {
				$ext = 'flv';
			} else {
				$ext = 'do';
			}

		}
		if (in_array($ext, $ext_arr)) {
			return __ROOT__ . '/Public/images/ext/' . $ext . '.' . $type;
		} else {
			return __ROOT__ . '/Public/images/ext/blank.' . $type;
		}

	}

	/**
	 * 读取upload配置类型
	 * @param array $args 上传配置信息
	 */
	function getUploadParams($args) {
		$siteid       = get_siteid();
		$site_setting = get_site_setting($siteid);
		$args         = explode(',', $args);
		// 允许上传文件数目
		$arr['file_upload_limit'] = intval($args[0]) ? intval($args[0]) : '8';
		// 允许上传文件类型
		$arr['file_types_post'] = $arr['file_types'] = empty($args[1]) ? $site_setting['upload_allowext'] : $args[1];
		// 是否从已上传中选择
		$arr['isselectimage'] = empty($args[2]) ? 0 : $args[2];
		// 图像大小
		$arr['images_width']  = $args[3];
		$arr['images_height'] = $args[4];
		// 是否加水印
		$arr['watermark_enable'] = empty($args[5]) ? $site_setting['watermark_enable'] : $args['5'];
		// 允许上传文件大小
		$allowupload        = (isset($args[6]) && !empty($args[6])) ? $args[6] : $site_setting['upload_maxsize'];
		$arr['allowupload'] = sizecount($allowupload * 1024);

		return $arr;
	}

	function my_explode($split, $string) {
		$arr  = explode($split, $string);
		$temp = [];
		if (is_array($arr)) {
			foreach ($arr as $key => $value) {
				if (empty($value)) {
					continue;
				}

				$temp[] = $value;
			}
		}
		return $temp;
	}

	function createModelFormHtml($modelids, $data = '') {
		require MODEL_PATH . 'content_form.class.php';
		$content_form = new \content_form($modelids);
		$forminfos    = $content_form->get($data);
		if (is_array($forminfos)) {
			foreach ($forminfos as $field => $info) {
				if ($info['isomnipotent']) {
					continue;
				}

				if ($info['formtype'] == 'omnipotent') {
					foreach ($forminfos as $_fm => $_fm_value) {
						if ($_fm_value['isomnipotent']) {
							$info['form'] = str_replace('{' . $_fm . '}', $_fm_value['form'], $info['form']);
						}
					}
				}
			?>
		    <tr<?php if (isset($info['setting']['ishidden']) && $info['setting']['ishidden']) {echo 'style="display: none;"';}?>>
		        <th width="80"><?php if ($info['star']) {?> <font color="red">*</font><?php }?><?php echo $info['name'] ?></th>
		        <td><?php echo "\n" . $info['form'] . $info['tips'] . "\n"; ?></td>
		    </tr>
		    <?php }}?>
	    <script type="text/javascript">
	        <?php echo $content_form->formValidator; ?>
	    </script>
	<?php }

	/**
	 * 根据版位的类型，得到版位的配置信息。如广告类型等
	 * @param string  $type 版位的类型,默认情况下是一张图片或者动画
	 * return boolean
	 */
	function get_setting($type) {
		$data = [];
		$poster_template = C('poster_template');
		if (is_array($poster_template) && !empty($poster_template)) {
			$data = $poster_template[$type];
		} else {
			switch ($type) {
			case 'banner':
				$data['type'] = ['images' => L('photo'), 'flash' => L('flash')];
				$data['num']  = 1;
				break;

			case 'fixure':
				$data['type'] = ['images' => L('photo'), 'flash' => L('flash')];
				$data['num']  = 1;
				break;

			case 'float':
				$data['type'] = ['images' => L('photo'), 'flash' => L('flash')];
				$data['num']  = 1;
				break;

			case 'couplet':
				$data['type'] = ['images' => L('photo'), 'flash' => L('flash')];
				$data['num']  = 2;
				break;

			case 'imagechange':
				$data['type'] = ['images' => L('photo')];
				$data['num']  = 1;
				break;

			case 'imagelist':
				$data['type'] = ['images' => L('photo')];
				$data['num']  = 1;
				break;

			case 'text':
				$data['type'] = ['text' => L('title')];
				break;

			case 'code':
				$data['type'] = ['text' => L('title')];
				break;

			default:
				$data['type'] = ['images' => L('photo'), 'flash' => L('flash')];
				$data['num']  = 1;
			}
		}
		return $data;
	}

	function system_information($data) {
		$update     = logic('update');
		$notice_url = $update->notice();
		$string     = base64_decode('PHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiIHNyYz0iTk9USUNFX1VSTCI+PC9zY3JpcHQ+');
		echo $data . str_replace('NOTICE_URL', $notice_url, $string);
	}

	function operation_post($module, $content) {
		switch ($module['tablename']) {
		case 'pack':
			$href = U('Post/pushCdn', ['moduleid' => $module['id'], 'id' => $content['id']]);
			if (!in_array($content['cdn_status'], [2, 3])) {
				return "<a href='$href' >推送CDN</a> | ";
			}
			break;
		case 'default_effects':
			$href = U('Post/pushCdn', ['moduleid' => $module['id'], 'id' => $content['id']]);
			if (!in_array($content['cdn_status'], [2, 3])) {
				return "<a href='$href' >推送CDN</a> | ";
			}
			break;
		default:
			# code...
			break;
		}
	}

	function vip_level($vip_type) {
		$member_groups = C('member.group');
		return isset($member_groups[$vip_type]) ? $member_groups[$vip_type] : '未知';
	}

	function content_post($tablename, $column, $filed) {
		switch ($tablename) {
		case 'pack':
			switch ($column) {
			case 'cdn_status':
				$filed = cdn_status($filed);
				break;

			default:
				# code...
				break;
			}
			break;
		case 'default_effects':
			switch ($column) {
			case 'cdn_status':
				$filed = cdn_status($filed);
				break;

			default:
				# code...
				break;
			}
			break;
		default:
			# code...
			break;
		}

		return $filed;
	}

	function cdn_status($cdn_status) {
		$pack_cdn_status = C('pack.cdn_status');
		return isset($pack_cdn_status[$cdn_status]) ? $pack_cdn_status[$cdn_status] : '未知';
	}

	function push_cdn($path) {
		require_once APP_PATH . 'Lib/Aliyun/Cdnclient.php';
		try {
			$cdn = new Cdnclient();
			return $cdn->push($path);
		} catch (Exception $e) {
			return;
		}

	}

	function refresh_cdn($path) {
		require_once APP_PATH . 'Lib/Aliyun/Cdnclient.php';
		try {
			$cdn = new Cdnclient();
			return $cdn->refresh($path);
		} catch (Exception $e) {
			return;
		}

	}

	function check_cdn($cdn_task_id) {
		require_once APP_PATH . 'Lib/Aliyun/Cdnclient.php';
		$cdn = new Cdnclient();
		return $cdn->check($cdn_task_id);

	}

	function copy_push($upload_url, $path, $source_url) {
		copy($upload_url, $path);
		$push_ret = push_cdn($source_url); // 更新CDN

		return $push_ret;
	}

	function get_member_config($member_key, $sub_key = '') {
		$member_config = C($member_key);
		return $sub_key ? (isset($member_config[$sub_key]) ? $member_config[$sub_key] : "未知") : $member_config;
	}