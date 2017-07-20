<?php
$setting = string2array($fieldinfo['setting']);
extract($setting);
$js_callback = isset($js_callback) && !empty($js_callback) ? $js_callback : 'thumb_images';
$html = '';
if (defined('IN_ADMIN')) {
    $html = "<input type=\"button\" style=\"width: 66px;\" class=\"button\" onclick=\"$('#" . $field . "_preview').attr('src','" . asset('images/admin/icon/upload-pic.png') . "');$('#" . $field . "').val(' ');return false;\" value=\"取消附件\">";
}
if ($show_type && defined('IN_ADMIN')) {
    $preview_img = $value ? $value : asset('images/admin/icon/upload-pic.png');
    return "<div class='upload-pic img-wrap'><input type='hidden' name='info[$field]' id='$field' value='$value'><a href='javascript:void(0);' onclick=\"attachupload('{$field}_images', '附件上传','{$field}',".$js_callback.",'1,{$upload_allowext},{$isselectimage},{$images_width},{$images_height},{$watermark},{$upload_maxsize}','image','" . U("File/upload") . "');return false;\">
    <img src='$preview_img' id='{$field}_preview' width='135' height='113' style='cursor:hand' /></a>" . "<input type=\"button\" style=\"width: 66px;\" class=\"button\" onclick=\"attachupload('{$field}_images', '附件上传','{$field}',".$js_callback.",'1,{$upload_allowext},{$isselectimage},{$images_width},{$images_height},{$watermark},{$upload_maxsize}','image','" . U("File/upload") . "');return false;\" value=\"上传附件\">" . $html . "</div>";
} else {
    return "<input type='text' name='info[$field]' id='$field' value='$value' size='$size' class='input-text' />  <input type='button' class='button' onclick=\"attachupload('{$field}_images', '附件上传','{$field}',".$js_callback.",'1,{$upload_allowext},{$isselectimage},{$images_width},{$images_height},{$watermark},{$upload_maxsize}','image','" . U("File/upload") . "');return false;\"/ value='上传附件'>" . $html;
}