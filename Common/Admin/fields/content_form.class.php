<?php
class content_form {
	var $modelid;
	var $fields;
	var $id;
	var $formValidator;
	var $siteid;

	function __construct($modelid,$catid = 0,$categorys = array()) {
		$this->modelid = $modelid;
		$this->catid = $catid;
		$this->categorys = $categorys;
		$this->fields = $this->get_fields($modelid);
		$this->siteid = get_siteid();
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

	function get($data = array()) {
		$this->data = $data;
		if(isset($data['id'])) $this->id = $data['id'];
		$info = array();
		$this->content_url = $data['url'];
		foreach($this->fields as $field=>$v) {
			if(defined('IN_ADMIN')) {
				if($v['iscore']) continue;
			} else {
				if($v['iscore'] || !$v['isadd']) continue;
			}
			$func = $v['formtype'];

			if ($field == "relation" ) {
				$value = string2array($data[$field]);
				if (empty($value)) {
					$value = array("IDS" => "", "CATS" => "", "TITLE" => "");
				}
			} else {
				$value = isset($data[$field]) ? htmlspecialchars($data[$field], ENT_QUOTES) : '';
			}

			if($func=='pages' && isset($data['maxcharperpage'])) {
				$value = $data['paginationtype'].'|'.$data['maxcharperpage'];
			}
			if(!method_exists($this, $func)) continue;
			$form = $this->$func($field, $value, $v);
			if($form !== false) {
				if(defined('IN_ADMIN')) {
					if($v['isbase']) {
						$star = $v['minlength'] || $v['pattern'] ? 1 : 0;
						$info['base'][$field] = array('name'=>$v['name'], 'tips'=>$v['tips'], 'form'=>$form, 'star'=>$star,'isomnipotent'=>$v['isomnipotent'],'formtype'=>$v['formtype']);
					} else {
						$star = $v['minlength'] || $v['pattern'] ? 1 : 0;
						$info['senior'][$field] = array('name'=>$v['name'], 'tips'=>$v['tips'], 'form'=>$form, 'star'=>$star,'isomnipotent'=>$v['isomnipotent'],'formtype'=>$v['formtype']);
					}
				} else {
					$star = $v['minlength'] || $v['pattern'] ? 1 : 0;
					$info[$field] = array('name'=>$v['name'], 'tips'=>$v['tips'], 'form'=>$form, 'star'=>$star,'isomnipotent'=>$v['isomnipotent'],'formtype'=>$v['formtype']);
				}
			}
		}
		return $info;
	}

	function text($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = string2array($setting);
		$size = $setting['size'];
		if(!$value) $value = $defaultvalue;
		$type = $ispassword ? 'password' : 'text';
		$errortips = $this->fields[$field]['errortips'];
		if($errortips || $minlength) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		return '<input type="text" name="info['.$field.']" id="'.$field.'" size="'.$size.'" value="'.$value.'" class="input-text" '.$formattribute.' '.$css.'>';
	}
	function textarea($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = string2array($setting);
		extract($setting);
		if(!$value) $value = $defaultvalue;
		$allow_empty = 'empty:true,';
		if($minlength || $pattern) $allow_empty = '';
		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({'.$allow_empty.'onshow:"'.$errortips.'",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		$value = empty($value) ? $setting[defaultvalue] : $value;
		$str = "<textarea name='info[{$field}]' id='$field' style='width:{$width}%;height:{$height}px;' $formattribute $css";
		if($maxlength) $str .= " onkeyup=\"strlen_verify(this, '{$field}_len', {$maxlength})\"";
		$str .= ">{$value}</textarea>";
		if($maxlength) $str .= L('can_enter').'<B><span id="'.$field.'_len">'.$maxlength.'</span></B> '.L('characters');
		return $str;
	}
	function editor($field, $value, $fieldinfo) {
		extract($fieldinfo);
		extract(string2array($setting));
		$disabled_page = isset($disabled_page) ? $disabled_page : 0;
		if(!$height) $height = 300;
		$allowupload = defined('IN_ADMIN') ? 1 : 0 ;
		if(!$value) $value = $defaultvalue;
		if($minlength || $pattern) $allow_empty = '';
		if($minlength) $this->formValidator .= '$("#'.$field.'").formValidator({'.$allow_empty.'onshow:"",onfocus:"'.$errortips.'"}).functionValidator({fun:function(val,elem){var oEditor = CKEDITOR.instances.'.$field.';var data = oEditor.getData();if($(\'#islink\').attr(\'checked\')){return true;} else if(($(\'#islink\').attr(\'checked\')==false) && (data==\'\')){return "'.$errortips.'";} else if (data==\'\' || $.trim(data)==\'\') {return "'.$errortips.'";e}return true;}});';
		return "<div id='{$field}_tip'></div>".'<textarea name="info['.$field.']" id="'.$field.'" boxid="'.$field.'">'.stripslashes($value).'</textarea>'.form::editor($field,$toolbar,'content',$this->catid,'',$allowupload,1,'',$height,$disabled_page);
	}
	function catid($field, $value, $fieldinfo) {
		if(!$value) $value = $this->catid;
		$publish_str = '';
		if(defined('IN_ADMIN')) $publish_str = " <a href='javascript:;' onclick=\"omnipotent('selectid','".U('Content/add_othors')."','".L('publish_to_othor_category')."',1);return false;\" style='color:#B5BFBB'>[".L('publish_to_othor_category')."]</a><ul class='list-dot-othors' id='add_othors_text'></ul>";
		return '<input type="hidden" name="info['.$field.']" value="'.$value.'">'.$this->categorys[$value]['catname'].$publish_str;
	}
	function title($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$style_arr = explode(';',$this->data['style']);
		$style_color = $style_arr[0];
		$style_font_weight = $style_arr[1] ? $style_arr[1] : '';

		$style = 'color:'.$this->data['style'];
		if(!$value) $value = $defaultvalue;
		$errortips = $this->fields[$field]['errortips'];
		$errortips_max = L('title_is_empty');
		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:'.$minlength.',max:'.$maxlength.',onerror:"'.$errortips_max.'"});';
		$str = '<input type="text" style="width:400px;'.($style_color ? 'color:'.$style_color.';' : '').($style_font_weight ? 'font-weight:'.$style_font_weight.';' : '').'" name="info['.$field.']" id="'.$field.'" value="'.$value.'" style="'.$style.'" class="measure-input " onkeyup="strlen_verify(this, \'title_len\', '.$maxlength.');"/><input type="hidden" name="style_color" id="style_color" value="'.$style_color.'">
		<input type="hidden" name="style_font_weight" id="style_font_weight" value="'.$style_font_weight.'">';
		if(defined('IN_ADMIN')) $str .= '<input type="button" class="button" id="check_title_alt" value="'.L('check_title').'" onclick="$.get(\'__URL__/public_check_title?modelid='.$this->modelid.'&sid=\'+Math.random()*5, {data:$(\'#title\').val()}, function(data){if(data==\'1\') {$(\'#check_title_alt\').val(\''.L('title_repeat').'\');$(\'#check_title_alt\').css(\'background-color\',\'#FFCC66\');} else if(data==\'0\') {$(\'#check_title_alt\').val(\''.L('title_not_repeat').'\');$(\'#check_title_alt\').css(\'background-color\',\'#F8FFE1\')}})" style="width:73px;"/><img src="'.IMG_PATH.'/admin/icon/colour.png" width="15" height="16" onclick="colorpicker(\''.$field.'_colorpanel\',\'set_title_color\');" style="cursor:hand"/> 
		<img src="'.IMG_PATH.'/admin/icon/bold.png" width="10" height="10" onclick="input_font_bold()" style="cursor:hand"/> <span id="'.$field.'_colorpanel" style="position:absolute;" class="colorpanel"></span>';
		$str .= L('can_enter').'<B><span id="title_len">'.$maxlength.'</span></B> '.L('characters');
		return $str;
	}
	function box($field, $value, $fieldinfo) {
		$setting = string2array($fieldinfo['setting']);
		if($value=='') $value = $this->fields[$field]['defaultvalue'];
		$options = explode("\n",$this->fields[$field]['options']);
		foreach($options as $_k) {
			$v = explode("|",$_k);
			$k = trim($v[1]);
			$option[$k] = $v[0];
		}
		$values = explode(',',$value);
		$value = array();
		foreach($values as $_k) {
			if($_k != '') $value[] = $_k;
		}
		$value = implode(',',$value);
		switch($this->fields[$field]['boxtype']) {
			case 'radio':
			$string = form::radio($option,$value,"name='info[$field]' $fieldinfo[formattribute]",$setting['width'],$field);
			break;

			case 'checkbox':
			$string = form::checkbox($option,$value,"name='info[$field][]' $fieldinfo[formattribute]",1,$setting['width'],$field);
			break;

			case 'select':
			$string = form::select($option,$value,"name='info[$field]' id='$field' $fieldinfo[formattribute]");
			break;

			case 'multiple':
			$string = form::select($option,$value,"name='info[$field][]' id='$field ' size=2 multiple='multiple' style='height:60px;' $fieldinfo[formattribute]");
			break;
		}
		return $string;
	}
	function image($field, $value, $fieldinfo) {
		$setting = string2array($fieldinfo['setting']);
		extract($setting);
		$html = '';
		if (defined('IN_ADMIN')) {
			$html = "<input type=\"button\" style=\"width: 66px;\" class=\"button\" onclick=\"crop_cut_".$field."($('#$field').val());return false;\" value=\"".L('cut_the_picture')."\"><input type=\"button\" style=\"width: 66px;\" class=\"button\" onclick=\"$('#".$field."_preview').attr('src','".IMG_PATH."/admin/icon/upload-pic.png');$('#".$field."').val(' ');return false;\" value=\"".L('cancel_the_picture')."\"><script type=\"text/javascript\">function crop_cut_".$field."(id){ if (id=='') { alert('".L('upload_thumbnails')."');return false;} window.top.art.dialog({title:'".L('cut_the_picture')."', id:'crop', iframe:'index.php?m=content&c=content&a=public_crop&module=content&catid='+catid+'&picurl='+encodeURIComponent(id)+'&input=$field&preview=".($show_type && defined('IN_ADMIN') ? $field."_preview" : '')."', width:'680px', height:'480px'}, 	function(){var d = window.top.art.dialog({id:'crop'}).data.iframe; d.uploadfile();return false;}, function(){window.top.art.dialog({id:'crop'}).close()});};</script>";
		}
		if($show_type && defined('IN_ADMIN')) {
			$preview_img = $value ? $value : IMG_PATH.'/admin/icon/upload-pic.png';
			return $str."<div class='upload-pic img-wrap'><input type='hidden' name='info[$field]' id='$field' value='$value'>
			<a href='javascript:void(0);' onclick=\"attachupload('{$field}_images', '".L('attachment_upload')."','{$field}',thumb_images,'1,{$upload_allowext},$isselectimage,$images_width,$images_height,$watermark','image','".U("Upfile/upload")."');return false;\">
			<img src='$preview_img' id='{$field}_preview' width='135' height='113' style='cursor:hand' /></a>".$html."</div>";
		} else {
			return $str."<input type='text' name='info[$field]' id='$field' value='$value' size='$size' class='input-text' />  <input type='button' class='button' onclick=\"attachupload('{$field}_images', '".L('attachment_upload')."','{$field}',thumb_images,'1,{$upload_allowext},$isselectimage,$images_width,$images_height,$watermark','image','".U("Upfile/upload")."');return false;\"/ value='".L('upload_pic')."'>".$html;
		}
	}
	function images($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$list_str = '';
		if($value) {
			$value = string2array(html_entity_decode($value,ENT_QUOTES));
			if(is_array($value)) {
				foreach($value as $_k=>$_v) {
					$list_str .= "<div id='image_{$field}_{$_k}' style='padding:1px'><input type='text' name='{$field}[url][]' value='{$_v[url]}' style='width:310px;' ondblclick='image_priview(this.value);' class='input-text'> <input type='text' name='{$field}[alt][]' value='{$_v[alt]}' style='width:160px;' class='input-text'> <a href=\"javascript:remove_div('image_{$field}_{$_k}')\">".L('remove_out')."</a></div>";
				}
			}
		} else {
			$list_str .= "<center><div class='onShow' id='nameTip'>".L('upload_pic_max')." <font color='red'>{$upload_number}</font> ".L('tips_pics')."</div></center>";
		}
		$string = '<input name="info['.$field.']" type="hidden" value="1">
		<fieldset class="blue pad-10">
		<legend>'.L('pic_list').'</legend>';
		$string .= $list_str;
		$string .= '<div id="'.$field.'" class="picList"></div>
		</fieldset>
		<div class="bk10"></div>';
		$string .= $str."<div class='picBut cu'><a herf='javascript:void(0);' onclick=\"javascript:attachupload('{$field}_images', '".L('attachment_upload')."','{$field}',attaches,'{$upload_number},{$upload_allowext},{$isselectimage}','images','".U('Upfile/upload')."')\"/> ".L('select_pic')." </a></div>";
		return $string;
	}	
	function number($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = string2array($setting);
		$size = $setting['size'];		
		if(!$value) $value = $defaultvalue;
		return "<input type='text' name='info[$field]' id='$field' value='$value' class='input-text' size='$size' {$formattribute} {$css}>";
	}
	function datetime($field, $value, $fieldinfo) {
		extract(string2array($fieldinfo['setting']));
		$isdatetime = 0;
		$timesystem = 0;
		if($fieldtype=='int') {
			if(!$value) $value = time();
			$format_txt = $format == 'm-d' ? 'm-d' : $format;
			if($format == 'Y-m-d Ah:i:s') $format_txt = 'Y-m-d h:i:s';
			$value = date($format_txt,$value);

			$isdatetime = strlen($format) > 6 ? 1 : 0;
			if($format == 'Y-m-d Ah:i:s') {

				$timesystem = 0;
			} else {
				$timesystem = 1;
			}			
		} elseif($fieldtype=='datetime') {
			$isdatetime = 1;
			$timesystem = 1;
		} elseif($fieldtype=='datetime_a') {
			$isdatetime = 1;
			$timesystem = 0;
		}
		return form::date("info[$field]",$value,$isdatetime,1,'true',$timesystem);
	}

	function posid($field, $value, $fieldinfo) {
		$setting = string2array($fieldinfo['setting']);
		$position = D('Position')->select();
		if(empty($position)) return '';
		$array = array();
		foreach($position as $_key=>$_value) {
			if($_value['modelid'] && ($_value['modelid'] !=  $this->modelid) || ($_value['catid'] && strpos(','.$this->categorys[$_value['catid']]['arrchildid'].',',','.$this->catid.',')===false)) continue;
			$array[$_value['id']] = $_value['name'];
		}
		$posids = array();
		if(ACTION_NAME=='edit') {
			$position_data = D('PositionData')->where('id = %d and modelid = %d', $this->id, $this->modelid)->field('posid')->group('posid')->select();
			$position_data_ids = array();
			foreach ($position_data as $key => $pos) {
				$position_data_ids[] = $pos['posid'];
			}
			$posids = implode(',', $position_data_ids);
		} else {
			$posids = $setting['defaultvalue'];
		}
		return "<input type='hidden' name='info[$field][]' value='-1'>".form::checkbox($array,$posids,"name='info[$field][]'",'',$setting['width']);
	}
	
	function keyword($field, $value, $fieldinfo) {
		extract($fieldinfo);
		if(!$value) $value = $defaultvalue;
		return "<input type='text' name='info[$field]' id='$field' value='$value' style='width:280px' {$formattribute} {$css} class='input-text'>";
	}
	function author($field, $value, $fieldinfo) {
		return '<input type="text" name="info['.$field.']" value="'.$value.'" size="30">';
	}

	function copyfrom($field, $value, $fieldinfo) {
		$value_data = '';
		if($value && strpos($value,'|')!==false) {
			$arr = explode('|',$value);
			$value = $arr[0];
			$value_data = $arr[1];
		}
		return "<input type='text' name='info[$field]' value='$value' style='width: 400px;' class='input-text'>";
	}

	function islink($field, $value, $fieldinfo) {
		if($value) {
			$url = $this->data['url'];
			$checked = 'checked';
			$_GET['islink'] = 1;
		} else {
			$disabled = 'disabled';
			$url = $checked = '';
			$_GET['islink'] = 0;
		}
		$size = $fieldinfo['size'] ? $fieldinfo['size'] : 25;
		return '<input type="hidden" name="info[islink]" value="0"><input type="text" name="linkurl" id="linkurl" value="'.$url.'" size="'.$size.'" maxlength="255" '.$disabled.' class="input-text"> <input name="info[islink]" type="checkbox" id="islink" value="1" onclick="ruselinkurl();" '.$checked.'> <font color="red">'.L('islink_url').'</font>';
	}

	function template($field, $value, $fieldinfo) {
		$sitelist = get_site_info();
		$default_style = $sitelist[$this->siteid]['default_style'];
		$template = form::select_template($default_style,'content',$value,'name="info['.$field.']" id="'.$field.'"','show');
		return $template;
	}

	function pages($field, $value, $fieldinfo) {
		extract($fieldinfo);
		if($value) {
			$v = explode('|', $value);
			$data = "<select name=\"info[paginationtype]\" id=\"paginationtype\" onchange=\"if(this.value==1)\$('#paginationtype1').css('display','');else \$('#paginationtype1').css('display','none');\">";
			$type = array(L('page_type1'), L('page_type2'), L('page_type3'));
			if($v[0]==1) $con = 'style="display:"';
			else $con = 'style="display:none"';
			foreach($type as $i => $val) {
				if($i==$v[0]) $tag = 'selected';
				else $tag = '';
				$data .= "<option value=\"$i\" $tag>$val</option>";
			}
			$data .= "</select><span id=\"paginationtype1\" $con><input name=\"info[maxcharperpage]\" type=\"text\" id=\"maxcharperpage\" value=\"$v[1]\" size=\"8\" maxlength=\"8\">".L('page_maxlength')."</span>";
			return $data;
		} else {
			return "<select name=\"info[paginationtype]\" id=\"paginationtype\" onchange=\"if(this.value==1)\$('#paginationtype1').css('display','');else \$('#paginationtype1').css('display','none');\">
			<option value=\"0\">".L('page_type1')."</option>
			<option value=\"1\">".L('page_type2')."</option>
			<option value=\"2\">".L('page_type3')."</option>
			</select>
			<span id=\"paginationtype1\" style=\"display:none\"><input name=\"info[maxcharperpage]\" type=\"text\" id=\"maxcharperpage\" value=\"10000\" size=\"8\" maxlength=\"8\">".L('page_maxlength')."</span>";
		}
	}
	function typeid($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = string2array($setting);
		if(!$value) $value = $setting['defaultvalue'];
		if($errortips) {
			$errortips = $this->fields[$field]['errortips'];
			$this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		}
		$usable_type = $this->categorys[$this->catid]['usable_type'];
		$usable_array = array();
		if($usable_type) $usable_array = explode(',',$usable_type);

		//获取站点ID
		if(intval($_GET['siteid'])){
			$siteid = intval($_GET['siteid']);
		}else{
			$siteid = $this->siteid;
		}
		return form::select($data,$value,'name="info['.$field.']" id="'.$field.'" '.$formattribute.' '.$css,L('copyfrom_tips'));
	}
	function readpoint($field, $value, $fieldinfo) {
		$paytype = $this->data['paytype'];
		if($paytype) {
			$checked1 = '';
			$checked2 = 'checked';
		} else {
			$checked1 = 'checked';
			$checked2 = '';
		}
		return '<input type="text" name="info['.$field.']" value="'.$value.'" size="5"><input type="radio" name="info[paytype]" value="0" '.$checked1.'> '.L('point').' <input type="radio" name="info[paytype]" value="1" '.$checked2.'>'.L('money');
	}
	function linkage($field, $value, $fieldinfo) {
		$setting = string2array($fieldinfo['setting']);
		$linkageid = $setting['linkageid'];
		return menu_linkage($linkageid,$field,$value);
	}
	function downfiles($field, $value, $fieldinfo) {
		extract(string2array($fieldinfo['setting']));
		$list_str = '';
		if($value) {
			$value = string2array(html_entity_decode($value,ENT_QUOTES));
			if(is_array($value)) {
				foreach($value as $_k=>$_v) {
					$list_str .= "<div id='multifile{$_k}'><input type='text' name='{$field}_fileurl[]' value='{$_v[fileurl]}' style='width:310px;' class='input-text'> <input type='text' name='{$field}_filename[]' value='{$_v[filename]}' style='width:160px;' class='input-text'> <a href=\"javascript:remove_div('multifile{$_k}')\">".L('remove_out')."</a></div>";
				}
			}
		}
		$string = '<input name="info['.$field.']" type="hidden" value="1">
		<fieldset class="blue pad-10">
		<legend>'.L('file_list').'</legend>';
		$string .= $list_str;
		$string .= '<ul id="'.$field.'" class="picList"></ul>
		</fieldset>
		<div class="bk10"></div>
		';
		$string .= $str."<input type=\"button\"  class=\"button\" value=\"".L('multiple_file_list')."\" onclick=\"javascript:flashupload('{$field}_multifile', '".L('attachment_upload')."','{$field}',change_multifile,'{$upload_number},{$upload_allowext},{$isselectimage}','content','$this->catid','{$authkey}')\"/>    <input type=\"button\" class=\"button\" value=\"".L('add_remote_url')."\" onclick=\"add_multifile('{$field}')\">";
		return $string;
	}
	function map($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = string2array($setting);
		$size = $setting['size'];
		$errortips = $this->fields[$field]['errortips'];
		$modelid = $this->fields[$field]['modelid'];
		$tips = $value ? L('editmark','','map') : L('addmark','','map');
		return '<input type="button" name="'.$field.'_mark" id="'.$field.'_mark" value="'.$tips.'" class="button" onclick="omnipotent(\'selectid\',\''.APP_PATH.'api.php?op=map&field='.$field.'&modelid='.$modelid.'\',\''.L('mapmark','','map').'\',1,700,420)"><input type="hidden" name="info['.$field.']" value="'.$value.'" id="'.$field.'" >';
	}

	function omnipotent($field, $value, $fieldinfo) {
		extract($fieldinfo);
		if (is_array($value)) {
			foreach ($value as $key => $val) {
				$formtext = str_replace('{FIELD_VALUE_'.strtoupper($key).'}', $val, $formtext);
			}
		} else {
			$formtext = str_replace('{FIELD_VALUE}',$value,$formtext);
		}
		// $formtext = str_replace('{FIELD_VALUE}',$value,$formtext);
		$formtext = str_replace('{URL_ADD_RELATION}',U("Content/public_relationlist"),$formtext);
		$formtext = str_replace('{URL_SHOW_RELATION}',U("Content/show_relation"),$formtext);
		$formtext = str_replace('{MODELID}',$this->modelid,$formtext);
		preg_match_all('/{FUNC\((.*)\)}/',$formtext,$_match);
		foreach($_match[1] as $key=>$match_func) {
			$string = '';
			$params = explode('~~',$match_func);
			$user_func = $params[0];
			$string = $user_func($params[1]);
			$formtext = str_replace($_match[0][$key],$string,$formtext);
		}
		$id  = $this->id ? $this->id : 0;
		$formtext = str_replace('{ID}',$id,$formtext);
		$errortips = $this->fields[$field]['errortips'];
		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:'.$minlength.',max:'.$maxlength.',onerror:"'.$errortips.'"});';
		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"'.$errortips.'",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		return $formtext;
	}
}?>