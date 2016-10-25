<?php
extract($fieldinfo);
$style_arr = isset($this->data['style']) ? explode(';', $this->data['style']) : '';
$style_color = isset($style_arr[0]) ? $style_arr[0] : '';
$style_font_weight = isset($style_arr[1]) ? $style_arr[1] : '';

if(!$value) $value = $defaultvalue;

$errortips_max = '标题应在' . $minlength . '-' . $maxlength . '个字符之间';
if($errortips) {
    $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:'.$minlength.',max:'.$maxlength.',onerror:"'.$errortips_max.'"});';
}

$str = '<input type="text" style="width:400px;' . ($style_color ? 'color:' . $style_color . ';' : '') . ($style_font_weight ? 'font-weight:' . $style_font_weight . ';' : '') . '" name="info[' . $field . ']" id="' . $field . '" value="' . $value . '" class="measure-input " onkeyup="strlen_verify(this, \'title_len\', ' . $maxlength . ');"/>';
    /*'<input type="hidden" name="style_color" id="style_color" value="'.$style_color.'">'.
    '<input type="hidden" name="style_font_weight" id="style_font_weight" value="'.$style_font_weight.'">';*/


if(defined('IN_ADMIN')) {
    $str .= '<input type="button" class="button" id="check_title_alt" value="检测重复" onclick="$.get(\'' . __CONTROLLER__ . '/public_check_title?modelid='.(is_array($this->modelid) ? current($this->modelid) : $this->modelid).'&sid=\'+Math.random()*5, {data:$(\'#title\').val()}, function(data){if(data==\'1\') {$(\'#check_title_alt\').val(\'标题重复\');$(\'#check_title_alt\').css(\'background-color\',\'#FFCC66\');} else if(data==\'0\') {$(\'#check_title_alt\').val(\'标题不重复\');$(\'#check_title_alt\').css(\'background-color\',\'#F8FFE1\')}})" style="width:73px;"/><span id="'.$field.'_colorpanel" style="position:absolute;" class="colorpanel"></span>';
}
$str .= '还可输入<B><span id="title_len">'.$maxlength.'</span></B> 个字符';
return $str;