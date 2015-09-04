
function title($field, $value, $fieldinfo) {
	extract($fieldinfo);
	$style_arr = explode(';',$this->data['style']);
	$style_color = $style_arr[0];
	$style_font_weight = $style_arr[1] ? $style_arr[1] : '';

	$style = 'color:'.$this->data['style'];
	if(!$value) $value = $defaultvalue;
	$errortips = $this->fields[$field]['errortips'];
	$errortips_max = '标题不能为空';
	if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:'.$minlength.',max:'.$maxlength.',onerror:"'.$errortips_max.'"});';
	$str = '<input type="text" style="width:400px;'.($style_color ? 'color:'.$style_color.';' : '').($style_font_weight ? 'font-weight:'.$style_font_weight.';' : '').'" name="info['.$field.']" id="'.$field.'" value="'.$value.'" style="'.$style.'" class="measure-input " onkeyup="strlen_verify(this, \'title_len\', '.$maxlength.');"/><input type="hidden" name="style_color" id="style_color" value="'.$style_color.'">
	<input type="hidden" name="style_font_weight" id="style_font_weight" value="'.$style_font_weight.'">';
	if(defined('IN_ADMIN')) $str .= '<input type="button" class="button" id="check_title_alt" value="检测重复" onclick="$.get(\'' . __CONTROLLER__ . '/public_check_title?modelid='.$this->modelid.'&sid=\'+Math.random()*5, {data:$(\'#title\').val()}, function(data){if(data==\'1\') {$(\'#check_title_alt\').val(\'标题重复\');$(\'#check_title_alt\').css(\'background-color\',\'#FFCC66\');} else if(data==\'0\') {$(\'#check_title_alt\').val(\'标题不重复\');$(\'#check_title_alt\').css(\'background-color\',\'#F8FFE1\')}})" style="width:73px;"/>
	<span id="'.$field.'_colorpanel" style="position:absolute;" class="colorpanel"></span>';
	$str .= '还可输入<B><span id="title_len">'.$maxlength.'</span></B> 个字符';
	return $str;
}