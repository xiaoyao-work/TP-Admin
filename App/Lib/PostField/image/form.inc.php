<?php
$setting = string2array($fieldinfo['setting']);
extract($setting);
$html = '';
if (defined('IN_ADMIN')) {
	$html = "<input type=\"button\" style=\"width: 66px;\" class=\"button\" onclick=\"$('#".$field."_preview').attr('src','".asset('images/admin/icon/upload-pic.png')."');$('#".$field."').val(' ');return false;\" value=\"取消图片\">";
}
if($show_type && defined('IN_ADMIN')) {
	$preview_img = $value ? $value : asset('images/admin/icon/upload-pic.png');
	return $str."<div class='upload-pic img-wrap'><input type='hidden' name='info[$field]' id='$field' value='$value'><a href='javascript:void(0);' onclick=\"attachupload('{$field}_images', '附件上传','{$field}',thumb_images,'1,{$upload_allowext},$isselectimage,$images_width,$images_height,$watermark','image','".U("File/upload")."');return false;\">
	<img src='$preview_img' id='{$field}_preview' width='135' height='113' style='cursor:hand' /></a>" . "<input type=\"button\" style=\"width: 66px;\" class=\"button\" onclick=\"attachupload('{$field}_images', '附件上传','{$field}',thumb_images,'1,{$upload_allowext},$isselectimage,$images_width,$images_height,$watermark','image','".U("File/upload")."');return false;\" value=\"上传图片\">" .$html."</div>";
} else {
	return $str."<input type='text' name='info[$field]' id='$field' value='$value' size='$size' class='input-text' />  <input type='button' class='button' onclick=\"attachupload('{$field}_images', '附件上传','{$field}',thumb_images,'1,{$upload_allowext},$isselectimage,$images_width,$images_height,$watermark','image','".U("File/upload")."');return false;\"/ value='上传图片'>".$html;
}