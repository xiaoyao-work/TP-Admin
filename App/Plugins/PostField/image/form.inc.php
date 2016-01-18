
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