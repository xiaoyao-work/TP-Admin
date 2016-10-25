<?php
if($value=='') $value = $fieldinfo['defaultvalue'];
$options = explode("\n",$fieldinfo['options']);
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
switch($fieldinfo['boxtype']) {
	case 'radio':
	$string = \Org\Util\Form::radio($option,$value,"name='info[$field]' $fieldinfo[formattribute]",$fieldinfo['width'],$field);
	break;

	case 'checkbox':
	$string = \Org\Util\Form::checkbox($option,$value,"name='info[$field][]' $fieldinfo[formattribute]",1,$fieldinfo['width'],$field);
	break;

	case 'select':
	$string = \Org\Util\Form::select($option,$value,"name='info[$field]' id='$field' $fieldinfo[formattribute]");
	break;

	case 'multiple':
	$string = \Org\Util\Form::select($option,$value,"name='info[$field][]' id='$field ' size=2 multiple='multiple' style='height:60px;' $fieldinfo[formattribute]");
	break;
}
return $string;