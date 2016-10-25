<?php
extract($fieldinfo);
if(!$value) $value = $defaultvalue;
$type = $ishidden ? 'hidden' : ($ispassword ? 'password' : 'text');

if($errortips || $minlength || $pattern) {
    $this->formValidator .= '$("#'.$field.'")';
}

if($errortips || $minlength) {
    $this->formValidator .= '.formValidator({onfocus:"'.(empty($errortips) ? '请填写内容' : $errortips).'"}).inputValidator({min:'.$minlength. ($maxlength ? ', max:'.$maxlength : '') .', onerror:"'.(empty($errortips) ? $name.'不能为空' : $errortips).'"})';
}

if (!empty($pattern)) {
	$this->formValidator .= '.functionValidator({fun: function(value, _this) { return '.$pattern.'.test(value); }, onerror:\''. (empty($errortips) ? $name.'格式不正确' : $errortips) .'\'})';
}

if($errortips || $minlength || $pattern) {
    $this->formValidator .= ';';
}
return '<input type="' . $type . '" name="info['.$field.']" id="'.$field.'" size="'.$size.'" value="'.$value.'" class="input-text" '.$formattribute.' '.$css.'>';