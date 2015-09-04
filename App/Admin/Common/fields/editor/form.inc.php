
	function editor($field, $value, $fieldinfo) {
		extract($fieldinfo);
		extract(string2array($setting));
		$disabled_page = isset($disabled_page) ? $disabled_page : 0;
		if(!$height) $height = 300;
		$allowupload = defined('IN_ADMIN') ? 1 : 0 ;
		if(!$value) $value = $defaultvalue;
		if($minlength || $pattern) $allow_empty = '';
		if($minlength) $this->formValidator .= '$("#'.$field.'").formValidator({'.$allow_empty.'onshow:"",onfocus:"'.$errortips.'"}).functionValidator({fun:function(val,elem){var oEditor = CKEDITOR.instances.'.$field.';var data = oEditor.getData();if($(\'#islink\').attr(\'checked\')){return true;} else if(($(\'#islink\').attr(\'checked\')==false) && (data==\'\')){return "'.$errortips.'";} else if (data==\'\' || $.trim(data)==\'\') {return "'.$errortips.'";e}return true;}});';
		return "<div id='{$field}_tip'></div>".'<textarea name="info['.$field.']" id="'.$field.'" boxid="'.$field.'">'.stripslashes($value).'</textarea>'.\Org\Util\Form::editor($field,$toolbar,'content',$this->catid,'',$allowupload,1,'',$height,$disabled_page);
	}