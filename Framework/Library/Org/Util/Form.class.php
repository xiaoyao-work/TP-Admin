<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Org\Util;
use Org\Util\Tree;
class Form {
  /**
   * 编辑器
   * @param int $textareaid
   * @param int $toolbar
   * @param string $module 模块名称
   * @param int $catid 栏目id
   * @param int $color 编辑器颜色
   * @param boole $allowupload  是否允许上传
   * @param boole $allowbrowser 是否允许浏览文件
   * @param string $alowuploadexts 允许上传类型
   * @param string $height 编辑器高度
   * @param string $disabled_page 是否禁用分页和子标题
   */
  public static function editor($textareaid = 'content', $toolbar = 'basic', $module = '', $catid = '', $color = '', $allowupload = 0, $allowbrowser = 1,$alowuploadexts = '',$height = 200,$disabled_page = 0, $allowuploadnum = '10') {
    $str ='';
    if($toolbar == 'basic') {
      $toolbar = defined('IN_ADMIN') ? "['Source']," : '';
      $toolbar .= "['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],['Maximize'],\r\n";
    } elseif($toolbar == 'full') {
      if(defined('IN_ADMIN')) {
        $toolbar = "['Source',";
      } else {
        $toolbar = '[';
      }
      $toolbar .= "'-','Templates'],
      ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print'],
      ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],['ShowBlocks'],['Image','Capture','Flash','MyVideo'],['Maximize'],
      '/',
      ['Bold','Italic','Underline','Strike','-'],
      ['Subscript','Superscript','-'],
      ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
      ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
      ['Link','Unlink','Anchor'],
      ['Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
      '/',
      ['Styles','Format','Font','FontSize'],
      ['TextColor','BGColor'],
      ['attachment'],\r\n";
    } elseif($toolbar == 'desc') {
      $toolbar = "['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-', 'Image', '-','Source'],['Maximize'],\r\n";
    } else {
      $toolbar = '';
    }
    $str .= "<script type=\"text/javascript\">\r\n";
    $str .= "CKEDITOR.replace( '$textareaid',{"; $str .= "height:{$height},";
      if($allowupload) {
        $str .="flashupload:true,alowuploadexts:'".$alowuploadexts."',allowbrowser:'".$allowbrowser."',allowuploadnum:'".$allowuploadnum."',\r\n";
      }
      if($allowupload) {
        $str .= "filebrowserUploadUrl : '" . __MODULE__  . "/Upfile/upload',\r\n";
        $str .= "filebrowserBrowseUrl: '" . __MODULE__  . "/Attachment/album_list',\r\n";
      }
      if($color) {
        $str .= "extraPlugins : 'uicolor',uiColor: '$color',";
      }
      $str .= "toolbar :\r\n";
      $str .= "[\r\n";
      $str .= $toolbar;
      $str .= "]\r\n";
      $str .= "});\r\n";
$str .= '</script>';
return $str;
}

  /**
   *
   * @param string $name 表单名称
   * @param int $id 表单id
   * @param string $value 表单默认值
   * @param string $moudle 模块名称
   * @param int $catid 栏目id
   * @param int $size 表单大小
   * @param string $class 表单风格
   * @param string $ext 表单扩展属性 如果 js事件等
   * @param string $alowexts 允许图片格式
   * @param array $thumb_setting
   * @param int $watermark_setting  0或1
   */
  public static function images($name, $id = '', $value = '', $moudle='', $catid='', $size = 50, $class = '', $ext = '', $alowexts = '',$thumb_setting = array(),$watermark_setting = 0 ) {
    if(!$id) $id = $name;
    if(!$size) $size= 50;
    if(!empty($thumb_setting) && count($thumb_setting)) $thumb_ext = $thumb_setting[0].','.$thumb_setting[1];
    else $thumb_ext = ',';
    if(!$alowexts) $alowexts = 'jpg|jpeg|gif|bmp|png';
    return $str."<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $ext/>  <input type=\"button\" class=\"button\" onclick=\"javascript:attachupload('{$id}_images', '附件上传','{$id}',attaches,'1,{$alowexts},1,{$thumb_ext},{$watermark_setting}','images','". U('Upfile/upload') ."')\"/ value=\"上传图片\">";
  }

  /**
   *


   * @param string $name 表单名称
   * @param int $id 表单id
   * @param string $value 表单默认值
   * @param string $moudle 模块名称
   * @param int $catid 栏目id
   * @param int $size 表单大小
   * @param string $class 表单风格
   * @param string $ext 表单扩展属性 如果 js事件等
   * @param string $alowexts 允许上传的文件格式
   * @param array $file_setting
   */
  public static function upfiles($name, $id = '', $value = '', $moudle='', $catid='', $size = 50, $class = '', $ext = '', $alowexts = '',$file_setting = array() ) {
    if(!$id) $id = $name;
    if(!$size) $size= 50;
    if(!empty($file_setting) && count($file_setting)) $file_ext = $file_setting[0].','.$file_setting[1];
    else $file_ext = ',';
    if(!$alowexts) $alowexts = 'rar|zip';
    $authkey = upload_key("1,$alowexts,1,$file_ext");
    return $str."<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $ext/>  <input type=\"button\" class=\"button\" onclick=\"javascript:flashupload('{$id}_files', '附件上传','{$id}',submit_attachment,'1,{$alowexts},1,{$file_ext}','{$moudle}','{$catid}','{$authkey}')\"/ value=\"上传文件\">";
  }

  /**
   * 日期时间控件
   * @param $name 控件name，id
   * @param $value 选中值
   * @param $isdatetime 是否显示时间
   * @param $loadjs 是否重复加载js，防止页面程序加载不规则导致的控件无法显示
   * @param $showweek 是否显示周，使用，true | false
   */
  public static function date($name, $value = '', $isdatetime = 0, $loadjs = 0, $showweek = 'true', $timesystem = 1) {
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
    if($loadjs && !defined('CALENDAR_INIT')) {
      define('CALENDAR_INIT', 1);
      $str .= '<link rel="stylesheet" type="text/css" href="'.C('TMPL_PARSE_STRING.CSS_PATH').'/JSCal/jscal2.css"/>
      <link rel="stylesheet" type="text/css" href="'.C('TMPL_PARSE_STRING.CSS_PATH').'/JSCal/border-radius.css"/>
      <link rel="stylesheet" type="text/css" href="'.C('TMPL_PARSE_STRING.CSS_PATH').'/JSCal/win2k/win2k.css"/>
      <script type="text/javascript" src="'.C('TMPL_PARSE_STRING.JS_PATH').'/JSCal/jscal2.js"></script>
      <script type="text/javascript" src="'.C('TMPL_PARSE_STRING.JS_PATH').'/JSCal/lang/cn.js"></script>';
    }
    $str .= '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" size="'.$size.'" class="date" readonly>&nbsp;';
    $str .= '<script type="text/javascript">Calendar.setup({weekNumbers: '.$showweek.',inputField : "'.$id.'",trigger    : "'.$id.'",dateFormat: "'.$format.'",showTime: '.$showsTime.',minuteStep: 1,onSelect   : function() {this.hide();}})</script>';
    return $str;
  }

  /**
   * 栏目选择
   * @param string $file 栏目缓存文件名
   * @param intval/array $catid 别选中的ID，多选是可以是数组
   * @param string $str 属性
   * @param string $default_option 默认选项
   * @param intval $modelid 按所属模型筛选
   * @param intval $type 栏目类型
   * @param intval $onlysub 只可选择子栏目
   * @param intval $siteid 如果设置了siteid 那么则按照siteid取
   */
  public static function select_category($file = '',$catid = 0, $str = '', $default_option = '', $modelid = 0, $type = -1, $onlysub = 0,$siteid = 0,$is_push = 0) {
    $tree = new Tree();
    if(!$siteid) $siteid = get_siteid();
    if ($modelid) {
      $result = D('Category')->where('siteid = %d and modelid = %d', $siteid, $modelid)->select();
    } else {
      $result = D('Category')->where('siteid = %d', $siteid)->select();
    }
    $string = '<select '.$str.'>';
    if($default_option) $string .= "<option value='0'>$default_option</option>";
    if (is_array($result)) {
      foreach($result as $r) {
        if($siteid != $r['siteid'] || ($type >= 0 && $r['type'] != $type)) continue;
        $r['selected'] = '';
        if(is_array($catid)) {
          $r['selected'] = in_array($r['id'], $catid) ? 'selected' : '';
        } elseif(is_numeric($catid)) {
          $r['selected'] = $catid==$r['id'] ? 'selected' : '';
        }
        $r['html_disabled'] = "0";
        if (!empty($onlysub) && $r['child'] != 0) {
          $r['html_disabled'] = "1";
        }
        $categorys[$r['id']] = $r;
        if($modelid && $r['modelid']!= $modelid ) unset($categorys[$r['catid']]);
      }
    }
    $str  = "<option value='\$id' \$selected>\$spacer \$catname</option>;";
    $str2 = "<optgroup label='\$spacer \$catname'></optgroup>";

    $tree->init($categorys);
    $string .= $tree->get_tree_category(0, $str, $str2);

    $string .= '</select>';
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
  /**
   * 模板选择
   *
   * @param $style  风格
   * @param $module 模块
   * @param $id 默认选中值
   * @param $str 属性
   * @param $pre 模板前缀
   */
  public static function select_template($style, $module, $id = '', $str = '', $pre = '') {
    $tpl_root = TMPL_PATH;
    $templatedir = $tpl_root.DIRECTORY_SEPARATOR. C("DEFAULT_GROUP") .DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR;
    $confing_path = $tpl_root.DIRECTORY_SEPARATOR. C("DEFAULT_GROUP") .DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.'config.php';
    $localdir = $style.'|'.$module;
    $templates = glob($templatedir.$pre.'*.html');
    if(empty($templates)) {
      $style = 'default';
      $templatedir = $tpl_root.DIRECTORY_SEPARATOR. C("DEFAULT_GROUP") .DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR;
      $confing_path = $tpl_root.DIRECTORY_SEPARATOR. C("DEFAULT_GROUP") .DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.'config.php';
      $localdir = $style.'|'.$module;
      $templates = glob($templatedir.$pre.'*.html');
    }
    if(empty($templates)) return false;
    $files = @array_map('basename', $templates);
    $names = array();
    if(file_exists($confing_path)) {
      $names = include $confing_path;
    }
    $templates = array();
    if(is_array($files)) {
      foreach($files as $file) {
        $key = substr($file, 0, -5);
        $templates[$key] = isset($names['file_explan'][$localdir][$file]) && !empty($names['file_explan'][$localdir][$file]) ? $names['file_explan'][$localdir][$file].'('.$file.')' : $file;
      }
    }
    ksort($templates);
    return self::select($templates, $id, $str,'请选择');
  }
}