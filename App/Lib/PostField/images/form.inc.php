<?php
extract($fieldinfo);
$list_str = '';
if($value) {
	$value = string2array(html_entity_decode($value,ENT_QUOTES));
	if(is_array($value)) {
		foreach($value as $_k=>$_v) {
			$list_str .= "<div id='image_{$field}_{$_k}' style='padding:1px'><input type='text' name='info[{$field}][url][]' value='{$_v[url]}' style='width:310px;' ondblclick='image_priview(this.value);' class='input-text'> <input type='text' name='info[{$field}][alt][]' value='{$_v[alt]}' style='width:160px;' class='input-text'> <a href=\"javascript:remove_div('image_{$field}_{$_k}')\">移除</a></div>";
		}
	}
} else {
	$list_str .= "<center><div class='onShow' id='nameTip'>您最多可以同时上传 <font color='red'>{$upload_number}</font> 个</div></center>";
}
$string = '<input name="info['.$field.']" type="hidden" value="1">
<fieldset class="blue pad-10">
<legend>文件列表</legend>';
$string .= $list_str;
$string .= '<div id="info_'.$field.'" class="picList"></div>
</fieldset>
<div class="bk10"></div>';
$string .= $str."<div class='picBut cu'><a herf='javascript:void(0);' onclick=\"javascript:attachupload('{$field}_images', '附件上传','info_{$field}',attaches,'{$upload_number},{$upload_allowext},{$isselectimage},,,,{$upload_maxsize}','images','".U('File/upload')."')\"/> 选择文件 </a></div>";
return $string;