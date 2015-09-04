<?php
class content_input {
	var $modelid;
	var $fields;
	var $data;

	function __construct($modelid) {
		$this->modelid = $modelid;
		$this->fields = $this->get_fields($modelid);
	}

	function get_fields($modelid) {
		$field_array = array();
		$fields = D("ModelField")->where(array('siteid' => get_siteid(), 'modelid' => $modelid, 'disabled'=>0))->order("listorder asc, fieldid asc")->limit(100)->select();
		foreach($fields as $_value) {
			$setting = string2array($_value['setting']);
			$_value = array_merge($_value,$setting);
			$field_array[$_value['field']] = $_value;
		}
		return $field_array;
	}

	function get($data,$isimport = 0) {
		$this->data = $data = trim_script($data);
		$info = array();
		foreach($data as $field=>$value) {
			if(!isset($this->fields[$field]) && !check_in($field,'paytype,paginationtype,maxcharperpage,id')) continue;
			$name = $this->fields[$field]['name'];
			$minlength = $this->fields[$field]['minlength'];
			$maxlength = $this->fields[$field]['maxlength'];
			$pattern = $this->fields[$field]['pattern'];
			$errortips = $this->fields[$field]['errortips'];
			if(empty($errortips)) $errortips = $name.' 不符合要求';
			$length = empty($value) ? 0 : (is_string($value) ? strlen($value) : count($value));

			if($minlength && $length < $minlength) {
				if($isimport) {
					return false;
				} else {
					showmessage($name.' 不得少于 '.$minlength. ' 字符');
				}
			}
			if($maxlength && $length > $maxlength) {
				if($isimport) {
					$value = str_cut($value,$maxlength,'');
				} else {
					showmessage($name.' 不得多于 '.$maxlength. ' 字符');
				}
			} elseif($maxlength) {
				$value = str_cut($value,$maxlength,'');
			}
			if($pattern && $length && !preg_match($pattern, $value) && !$isimport) {
				showmessage($errortips);
			}
			// 唯一性判断，和附加函数验证
			/*$MODEL = getcache('model', 'commons');
			$this->db->table_name = $this->fields[$field]['issystem'] ? $this->db_pre.$MODEL[$this->modelid]['tablename'] : $this->db_pre.$MODEL[$this->modelid]['tablename'].'_data';
			if($this->fields[$field]['isunique'] && $this->db->get_one(array($field=>$value),$field) && ROUTE_A != 'edit') showmessage($name.L('the_value_must_not_repeat'));*/
			$func = $this->fields[$field]['formtype'];
			if(method_exists($this, $func)) $value = $this->$func($field, $value);
			// var_dump($this->fields);
			if($this->fields[$field]['issystem']) {
				$info['system'][$field] = $value;
			} else {
				$info['model'][$field] = $value;
			}
		}
		//颜色选择为隐藏域 在这里进行取值
		$info['system']['style'] = $_POST['style_color'] ? strip_tags($_POST['style_color']) : '';
		if($_POST['style_font_weight']) $info['system']['style'] = $info['system']['style'].';'.strip_tags($_POST['style_font_weight']);
		return $info;
	}

	function box($field, $value) {
		if($this->fields[$field]['boxtype'] == 'checkbox') {
			if(!is_array($value) || empty($value)) return false;
			array_shift($value);
			$value = ','.implode(',', $value).',';
			return $value;
		} elseif($this->fields[$field]['boxtype'] == 'multiple') {
			if(is_array($value) && count($value)>0) {
				$value = ','.implode(',', $value).',';
				return $value;
			}
		} else {
			return $value;
		}
	}

	function copyfrom($field, $value) {
		$field_data = $field.'_data';
		if(isset($_POST[$field_data])) {
			$value .= '|'.safe_replace($_POST[$field_data]);
		}
		return $value;
	}

	function datetime($field, $value) {
		$setting = string2array($this->fields[$field]['setting']);
		if($setting['fieldtype']=='int') {
			$value = strtotime($value);
		}
		return $value;
	}

	function downfiles($field, $value) {
		$files = $_POST[$field.'_fileurl'];
		$files_alt = $_POST[$field.'_filename'];
		$array = $temp = array();
		if(!empty($files)) {
			foreach($files as $key=>$file) {
				$temp['fileurl'] = $file;
				$temp['filename'] = $files_alt[$key];
				$array[$key] = $temp;
			}
		}
		$array = array2string($array);
		return $array;
	}

	/*function editor($field, $value) {
		$setting = string2array($this->fields[$field]['setting']);
		$enablesaveimage = $setting['enablesaveimage'];
		if(isset($_POST['spider_img'])) $enablesaveimage = 0;
		if($enablesaveimage) {
			$site_setting = string2array($this->site_config['setting']);
			$watermark_enable = intval($site_setting['watermark_enable']);
			$value = $this->attachment->download('content', $value,$watermark_enable);
		}
		return $value;
	}*/

	function groupid($field, $value) {
		$datas = '';
		if(!empty($_POST[$field]) && is_array($_POST[$field])) {
			$datas = implode(',',$_POST[$field]);
		}
		return $datas;
	}

	function image($field, $value) {
		$value = str_replace(array("'",'"','(',')'),'',$value);
		return trim($value);
	}

	function images($field, $value) {
		//取得图片列表
		$pictures = $_POST[$field]['url'];
		//取得图片说明
		$pictures_alt = isset($_POST[$field]['alt']) ? $_POST[$field]['alt'] : array();
		$array = $temp = array();
		if(!empty($pictures)) {
			foreach($pictures as $key=>$pic) {
				$temp['url'] = $pic;
				$temp['alt'] = str_replace(array('"',"'"),'`',$pictures_alt[$key]);
				$array[$key] = $temp;
			}
		}
		$array = array2string($array);
		return $array;
	}

	function posid($field, $value) {
		$number = count($value);
		$value = $number==1 ? 0 : 1;
		return $value;
	}

	function textarea($field, $value) {
		if(!$this->fields[$field]['enablehtml']) $value = strip_tags($value);
		return $value;
	}



}?>