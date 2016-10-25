<?php
extract($fieldinfo);
if(!$value) $value = $defaultvalue;
$allow_empty = 'empty:true,';
if($minlength || $pattern) $allow_empty = '';
if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({'.$allow_empty.'onshow:"'.$errortips.'",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
$str = "<textarea name='info[{$field}]' id='$field' style='width:{$width}%;height:{$height}px;' $formattribute $css";
if($maxlength) $str .= " onkeyup=\"strlen_verify(this, '{$field}_len', {$maxlength})\"";
$str .= ">{$value}</textarea>";
if($maxlength) $str .= '还可输入<B><span id="'.$field.'_len">'.$maxlength.'</span></B> 个字符';
return $str;