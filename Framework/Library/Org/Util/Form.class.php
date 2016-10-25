<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Org\Util;
use Org\Util\Tree;
class Form {
    /**
    * 编辑器
    * @param int $textareaid
    * @param int $toolbar_type
    * @param int $color 编辑器颜色
    * @param boole $allowupload  是否允许上传
    * @param boole $allowbrowser 是否允许浏览文件
    * @param string $alowuploadexts 允许上传类型
    * @param string $height 编辑器高度
    */
    public static function editor($textareaid = 'content', $toolbar_type = 'basic', $color = '', $allowupload = 0, $allowbrowser = 1,$alowuploadexts = '',$height = 200) {
        $str = "<script type=\"text/javascript\">\r\n";
        $str .= "if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 ) { CKEDITOR.tools.enableHtml5Elements( document ) };\r\n";
        if($toolbar_type == 'basic') {
            $toolbar = "[\r\n";
            $toolbar .= defined('IN_ADMIN') ? " { name: 'document', items: [ 'Source' ] },\r\n" : '';
            $toolbar .= "{ name: 'basicstyles', items: ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ]},\r\n { name: 'tools', items: ['Maximize']}\r\n],\r\n";
            /*
            $toolbar .= defined('IN_ADMIN') ? " { name: 'document', items: [ 'Source' ] },\r\n" : '';
            $toolbar .= "{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },\r\n" .
            "{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },\r\n" .
            "'/'," .
            "{ name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },\r\n" .
            "{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },\r\n" .
            "{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },\r\n" .
            "'/'," .
            "{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },\r\n" .
            "{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] }\r\n" .
            "],\r\n";*/
        } elseif($toolbar_type == 'full') {
            $toolbar = "[\r\n";
            $toolbar .= defined('IN_ADMIN') ? "{ name: 'document', items: [ 'Source' ] },\r\n" : '';
            $toolbar .=  "{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },\r\n" .
                "{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },\r\n" .
                "'/'," .
                "{ name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },\r\n" .
                "{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },\r\n" .
                "{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },\r\n" .
                "'/'," .
                "{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },\r\n" .
                "{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },\r\n" .
                "{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] }\r\n" .
            "],\r\n";
        } else {
            $toolbar = '';
        }
        $ck_options = "{\r\nheight:{$height},\r\n";
            if($allowupload) {
                $ck_options .= "imageUploadUrl : '" . UPLOAD_IMAGE_URL  . "?type=ck_drag',\r\n";
                // $ck_options .= "filebrowserUploadUrl : '" . UPLOAD_IMAGE_URL  . "?type=ck_image',\r\n";
                if ($allowbrowser) {
                    $ck_options .= "filebrowserBrowseUrl: '" . __MODULE__  . "/Attachment/album_list',\r\n";
                }
            }
            if($color) {
                $ck_options .= "extraPlugins : 'uicolor',uiColor: '$color',";
            }
            $ck_options .= "toolbar :\r\n";
            $ck_options .= $toolbar;
            $ck_options .= "}\r\n";
        $str .= "( function() {\r\nvar wysiwygareaAvailable = isWysiwygareaAvailable();\r\n" .
            "if ( wysiwygareaAvailable ) {\r\n" .
                "CKEDITOR.replace( '".$textareaid."', ". $ck_options ." );\r\n" .
            "} else {\r\n" .
                "var editorElement = CKEDITOR.document.getById( '".$textareaid."' );\r\n" .
                "editorElement.setAttribute( 'contenteditable', 'true' );\r\n" .
                "CKEDITOR.inline( '".$textareaid."', ". $ck_options ." );\r\n" .
            "}\r\n" .
            "function isWysiwygareaAvailable() {\r\n" .
                "if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {\r\n" .
                    "return true;\r\n" .
                "}" .
                "return !!CKEDITOR.plugins.get( 'wysiwygarea' );\r\n" .
            "}\r\n" .
        "} )();";
        $str .= '</script>';
        return $str;
    }

    /**
    * 日期时间控件
    * @param $name 控件name，id
    * @param $value 选中值
    * @param $isdatetime 是否显示时间
    * @param $loadjs 是否重复加载js，防止页面程序加载不规则导致的控件无法显示
    * @param $showweek 是否显示周，使用，true | false
    */
    public static function date($name, $value = '', $isdatetime = 0, $loadjs = 0, $showweek = true, $timesystem = 1) {
        if($value == '0000-00-00 00:00:00') $value = '';
        $id = preg_match("/\[(.*)\]/", $name, $m) ? $m[1] : $name;
        if($isdatetime) {
            $size = 21;
            $format = '%Y-%m-%d %H:%M:%S';
            if($timesystem){
                $showsTime = 'true';
            } else {
                $showsTime = '12';
            }

        } else {
            $size = 10;
            $format = '%Y-%m-%d';
            $showsTime = 'false';
        }
        $str = '';
        if($loadjs || !defined('CALENDAR_INIT')) {
            defined('CALENDAR_INIT') or define('CALENDAR_INIT', 1);
            $str .= '<link rel="stylesheet" type="text/css" href="'.asset('css/JSCal/jscal2.css').'"/>
            <link rel="stylesheet" type="text/css" href="'.asset('css/JSCal/border-radius.css').'"/>
            <link rel="stylesheet" type="text/css" href="'.asset('css/JSCal/win2k/win2k.css').'"/>
            <script type="text/javascript" src="'. asset('js/JSCal/jscal2.js') .'"></script>
            <script type="text/javascript" src="'. asset('js/JSCal/lang/cn.js') .'"></script>';
        }
        $str .= '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" size="'.$size.'" class="date" readonly>&nbsp;';
        $str .= '<script type="text/javascript">Calendar.setup({weekNumbers: '.$showweek.',inputField : "'.$id.'",trigger    : "'.$id.'",dateFormat: "'.$format.'",showTime: '.$showsTime.',minuteStep: 1,onSelect   : function() {this.hide();}})</script>';
        return $str;
    }

    /**
     * 类目下拉选择
     * @param string $file 栏目缓存文件名
     * @param intval $termid 选中的ID
     * @param string $str 属性
     * @param string $default_option 默认选项
     */
    public static function term_select($terms='', $termid=0, $attr='', $default_option='') {
        $tree = new Tree();
        $tree->init($terms);
        $string = '<select ' . $attr . '>';
        $default_selected = (empty($id) && $default_option) ? 'selected' : '';
        if($default_option) $string .= "<option value='' $default_selected>$default_option</option>";
        $string .= $tree->get_tree(0, "<option value=\$id \$selected>\$spacer\$catname</option>", $termid);
        $string .= '</select>';
        return $string;
    }

    /**
     * 类目选择
     * @param string $terms 类目
     * @param string $taxonomy 分类
     * @param intval/array $termid 选中的ID，多选是可以是数组
     * @param string $str 属性
     */
    public static function taxonomy($terms = '', $taxonomy = 'category', $termid = 0) {
        if (is_numeric($termid)) {
            $termid = array($termid);
        }
        $tree = new Tree();
        $tree->init($terms);
        $string = '<ul id="'.$taxonomy.'checklist" class="'.$taxonomy.'checklist">';
        $string .= $tree->get_taxonomy_tree(0, $termid);
        $string .= '</ul>';
        return $string;
    }


    /**
    * 下拉选择框
    */
    public static function select($array = array(), $id = 0, $str = '', $default_option = '') {
        $string = '<select '.$str.'>';
        $default_selected = (empty($id) && $default_option) ? 'selected' : '';
        if($default_option) $string .= "<option value='' $default_selected>$default_option</option>";
        if(!is_array($array) || count($array)== 0) return false;
        $ids = array();
        if(isset($id)) $ids = explode(',', $id);
        foreach($array as $key=>$value) {
            $selected = in_array($key, $ids) ? 'selected' : '';
            $string .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        $string .= '</select>';
        return $string;
    }

    /**
    * 复选框
    *
    * @param $array 选项 二维数组
    * @param $id 默认选中值，多个用 '逗号'分割
    * @param $str 属性
    * @param $defaultvalue 是否增加默认值 默认值为 -99
    * @param $width 宽度
    */
    public static function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 0, $field = '') {
        $string = '';
        $id = trim($id);
        if($id != '') $id = strpos($id, ',') ? explode(',', $id) : array($id);
        if($defaultvalue) $string .= '<input type="hidden" '.$str.' value="-99">';
        $i = 1;
        foreach($array as $key=>$value) {
            $key = trim($key);
            $checked = ($id && in_array($key, $id)) ? 'checked' : '';
            if($width) $string .= '<label class="ib" style="width:'.$width.'px">';
            $string .= '<input type="checkbox" '.$str.' id="'.$field.'_'.$i.'" '.$checked.' value="'.htmlspecialchars($key).'"> '.htmlspecialchars($value);
            if($width) $string .= '</label>';
            $i++;
        }
        return $string;
    }

    /**
    * 单选框
    *
    * @param $array 选项 二维数组
    * @param $id 默认选中值
    * @param $str 属性
    */
    public static function radio($array = array(), $id = 0, $str = '', $width = 0, $field = '') {
        $string = '';
        foreach($array as $key=>$value) {
            $checked = trim($id)==trim($key) ? 'checked' : '';
            if($width) $string .= '<label class="ib" style="width:'.$width.'px">';
            $string .= '<input type="radio" '.$str.' id="'.$field.'_'.htmlspecialchars($key).'" '.$checked.' value="'.$key.'"> '.$value;
            if($width) $string .= '</label>';
        }
        return $string;
    }

    public static function image($field, $value='', $setting=array()) {
        $preview_img = $value ? $value : asset('images/admin/icon/upload-pic.png');
        $default_setting = array(
            'upload_allowext' => '',
            'isselectimage' => '',
            'images_width'  => 100,
            'images_height'  => 100,
            'watermark'  => 1,
            'upload_url'  => U("File/upload"),
            );
        $setting = array_merge($default_setting, $setting);
        extract($setting);
        $html = "<div class='upload-pic img-wrap'>".
            "<input type='hidden' name='info[$field]' id='$field' value='$value'>" .
            "<a href='javascript:void(0);' onclick=\"attachupload('".$field."_images', '附件上传','".$field."',thumb_images,'1,".$upload_allowext.",$isselectimage,$images_width,$images_height,$watermark','image','".$upload_url."');return false;\">" .
            "<img src='$preview_img' id='".$field."_preview' width='135' height='113' style='cursor:hand' /></a>" .
            "<input type=\"button\" style=\"width: 66px;\" class=\"button\" onclick=\"attachupload('".$field."_images', '附件上传','".$field."',thumb_images,'1,".$upload_allowext.",$isselectimage,$images_width,$images_height,$watermark','image','". $upload_url ."');return false;\" value=\"上传图片\">" .
            "<input type=\"button\" style=\"width: 66px;\" class=\"button\" onclick=\"$('#".$field."_preview').attr('src','".asset('images/admin/icon/upload-pic.png')."'); $('#".$field."').val(' ');return false;\" value=\"取消图片\">".
            "</div>";
        return $html;
    }

}