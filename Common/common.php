<?php

  function pre( $args ) {
    echo "<pre>";
    var_dump($args);
    echo "</pre>";
  }

  /**
   * 获取站点配置信息
   * @param  $siteid 站点id
   */
  function get_site_setting($siteid) {
    // $siteinfo = getcache('sitelist', 'commons');
    $siteid = $siteid ? $siteid : 1;
    $siteinfo = get_site_info($siteid);
    return string2array($siteinfo['setting']);
  }

  function get_site_info($siteid = "") {
    $sitelist = include(CONF_PATH.'sitelist.php');
    if (empty($siteid)) {
      return $sitelist;
    } else {
      return $sitelist[$siteid];
    }
  }

  function get_site_seo_info($siteid = "") {
    $seo = array();
    $siteid = $siteid ? $siteid : 1;
    $siteinfo = get_site_info($siteid);
    $seo['title'] = $siteinfo['site_title'];
    $seo['keywords'] = $siteinfo['keywords'];
    $seo['description'] = $siteinfo['description'];
    return $seo;
  }

  /**
   * 获取当前的站点ID
   */
  function get_siteid() {
    static $siteid;
    if (!empty($siteid)) return $siteid;
    if (defined('IN_ADMIN')) {
      if ($d = $_SESSION['siteid']) {
        $siteid = $d;
      } else {
        return '';
      }
    } else {
      $siteid = SITEID;
    }
    if (empty($siteid)) $siteid = 1;
    return $siteid;
  }

  /**
   * 设置站点
   */
  function set_siteid( $id ) {
    if ( !empty($id) ) {
      session( 'siteid', $id );
    }
  }

  /**
   * 检查id是否存在于数组中
   *
   * @param $id
   * @param $ids
   * @param $s
   */
  function check_in($id, $ids = '', $s = ',') {
    if(!$ids) return false;
    $ids = explode($s, $ids);
    return is_array($id) ? array_intersect($id, $ids) : in_array($id, $ids);
  }

  /**
   * 取得文件扩展
   *
   * @param $filename 文件名
   * @return 扩展名
   */
  function fileext($filename) {
    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
  }
  /**
  * 转换字节数为其他单位
  *
  *
  * @param  string  $filesize 字节大小
  * @return string  返回大小
  */
  function sizecount($filesize) {
    if ($filesize >= 1073741824) {
      $filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
    } elseif ($filesize >= 1048576) {
      $filesize = round($filesize / 1048576 * 100) / 100 .' MB';
    } elseif($filesize >= 1024) {
      $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
    } else {
      $filesize = $filesize.' Bytes';
    }
    return $filesize;
  }
  /**
   * 生成随机字符串
   * @param int       $length  要生成的随机字符串长度
   * @param string    $type    随机码类型：0，数字+大写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
   * @return string
   */
  function randCode($length = 5, $type = 0) {
    $arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
    if ($type == 0) {
      array_pop($arr);
      $string = implode("", $arr);
    } else if ($type == "-1") {
      $string = implode("", $arr);
    } else {
      $string = $arr[$type];
    }
    $count = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
      $str[$i] = $string[rand(0, $count)];
      $code .= $str[$i];
    }
    return $code;
  }

  /**
   * 删除目录及目录下所有文件或删除指定文件
   * @param str $path   待删除目录路径
   * @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
   * @return bool 返回删除状态
   */
  function delDirAndFile($path, $delDir = FALSE) {
    $message = "";
    $handle = opendir($path);
    if ($handle) {
      while (false !== ( $item = readdir($handle) )) {
        if ($item != "." && $item != "..") {
          if (is_dir("$path/$item")) {
            $msg = delDirAndFile("$path/$item", $delDir);
            if ( $msg ){
              $message .= $msg;
            }
          } else {
            $message .= "删除文件" . $item;
            if (unlink("$path/$item")){
              $message .= "成功<br />";
            } else {
              $message .= "失败<br />";
            }
          }
        }
      }
      closedir($handle);
      if ($delDir){
        if ( rmdir($path) ){
          $message .= "删除目录" . dirname($path) . "<br />";
        } else {
          $message .= "删除目录" . dirname($path) . "失败<br />";
        }


      }
    } else {
      if (file_exists($path)) {
        if (unlink($path)){
          $message .= "删除文件" . basename($path) . "<br />";
        } else {
          $message .= "删除文件" + basename($path) . "失败<br />";
        }
      } else {
        $message .= "文件" + basename($path) . "不存在<br />";
      }
    }
    return $message;
  }

  /**
   * 将一个字符串部分字符用*替代隐藏
   * @param string    $string   待转换的字符串
   * @param int       $bengin   起始位置，从0开始计数，当$type=4时，表示左侧保留长度
   * @param int       $len      需要转换成*的字符个数，当$type=4时，表示右侧保留长度
   * @param int       $type     转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串
   * @param string    $glue     分割符
   * @return string   处理后的字符串
   */
  function hideStr($string, $bengin = 0, $len = 4, $type = 0, $glue = "@") {
    if (empty($string))
      return false;
    $array = array();
    if ($type == 0 || $type == 1 || $type == 4) {
      $strlen = $length = mb_strlen($string);
      while ($strlen) {
        $array[] = mb_substr($string, 0, 1, "utf8");
        $string = mb_substr($string, 1, $strlen, "utf8");
        $strlen = mb_strlen($string);
      }
    }
    switch ($type) {
      case 1:
      $array = array_reverse($array);
      for ($i = $bengin; $i < ($bengin + $len); $i++) {
        if (isset($array[$i]))
          $array[$i] = "*";
      }
      $string = implode("", array_reverse($array));
      break;
      case 2:
      $array = explode($glue, $string);
      $array[0] = hideStr($array[0], $bengin, $len, 1);
      $string = implode($glue, $array);
      break;
      case 3:
      $array = explode($glue, $string);
      $array[1] = hideStr($array[1], $bengin, $len, 0);
      $string = implode($glue, $array);
      break;
      case 4:
      $left = $bengin;
      $right = $len;
      $tem = array();
      for ($i = 0; $i < ($length - $right); $i++) {
        if (isset($array[$i]))
          $tem[] = $i >= $left ? "*" : $array[$i];
      }
      $array = array_chunk(array_reverse($array), $right);
      $array = array_reverse($array[0]);
      for ($i = 0; $i < $right; $i++) {
        $tem[] = $array[$i];
      }
      $string = implode("", $tem);
      break;
      default:
      for ($i = $bengin; $i < ($bengin + $len); $i++) {
        if (isset($array[$i]))
          $array[$i] = "*";
      }
      $string = implode("", $array);
      break;
    }
    return $string;
  }

  /**
   *  global.func.php 公共函数库
   *
   * @copyright     (C) 2005-2010 PHPCMS
   * @license       http://www.phpcms.cn/license/
   * @lastmodify      2010-6-1
   */

  /**
   * 返回经addslashes处理过的字符串或数组
   * @param $string 需要处理的字符串或数组
   * @return mixed
   */
  function new_addslashes($string){
    if(!is_array($string)) return addslashes($string);
    foreach($string as $key => $val) $string[$key] = new_addslashes($val);
    return $string;
  }

  /**
   * 返回经stripslashes处理过的字符串或数组
   * @param $string 需要处理的字符串或数组
   * @return mixed
   */
  function new_stripslashes($string) {
    if(!is_array($string)) return stripslashes($string);
    foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
    return $string;
  }

  /**
   * 返回经htmlspecialchars处理过的字符串或数组
   * @param $obj 需要处理的字符串或数组
   * @return mixed
   */
  function new_html_special_chars($string) {
    if(!is_array($string)) return htmlspecialchars($string);
    foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
    return $string;
  }


  /**
  * 将字符串转换为数组
  *
  * @param  string  $data 字符串
  * @return array 返回数组格式，如果，data为空，则返回空数组
  */
  function string2array($data) {
    if($data == '') return array();
    @eval("\$array = $data;");
    return $array;
  }
  /**
  * 将数组转换为字符串
  *
  * @param  array $data   数组
  * @param  bool  $isformdata 如果为0，则不使用new_stripslashes处理，可选参数，默认为1
  * @return string  返回字符串，如果，data为空，则返回空
  */
  function array2string($data, $isformdata = 1) {
    if($data == '') return '';
    if($isformdata) $data = new_stripslashes($data);
    return var_export($data, TRUE);
  }

  /**
   * 安全过滤函数
   *
   * @param $string
   * @return string
   */
  function safe_replace($string) {
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string = str_replace('"','&quot;',$string);
    $string = str_replace("'",'',$string);
    $string = str_replace('"','',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'',$string);
    $string = str_replace('}','',$string);
    $string = str_replace('\\','',$string);
    return $string;
  }

  /**
   * xss过滤函数
   *
   * @param $string
   * @return string
   */
  function remove_xss($string) {
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

    $parm = array_merge($parm1, $parm2);

    for ($i = 0; $i < sizeof($parm); $i++) {
      $pattern = '/';
      for ($j = 0; $j < strlen($parm[$i]); $j++) {
        if ($j > 0) {
          $pattern .= '('; $pattern .= '(&#[x|X]0([9][a][b]);?)?'; $pattern .= '|(&#0([9][10][13]);?)?'; $pattern .= ')?';
        }
        $pattern .= $parm[$i][$j];
      }
      $pattern .= '/i';
      $string = preg_replace($pattern, '', $string);
    }
    return $string;
  }
  /**
   * 过滤ASCII码从0-28的控制字符
   * @return String
   */
  function trim_unsafe_control_chars($str) {
    $rule = '/[' . chr ( 1 ) . '-' . chr ( 8 ) . chr ( 11 ) . '-' . chr ( 12 ) . chr ( 14 ) . '-' . chr ( 31 ) . ']*/';
    return str_replace ( chr ( 0 ), '', preg_replace ( $rule, '', $str ) );
  }

  /**
   * 格式化文本域内容
   *
   * @param $string 文本域内容
   * @return string
   */
  function trim_textarea($string) {
    $string = nl2br ( str_replace ( ' ', '&nbsp;', $string ) );
    return $string;
  }

  /**
   * 将文本格式成适合js输出的字符串
   * @param string $string 需要处理的字符串
   * @param intval $isjs 是否执行字符串格式化，默认为执行
   * @return string 处理后的字符串
   */
  function format_js($string, $isjs = 1) {
    $string = addslashes(str_replace(array("\r", "\n", "\t"), array('', '', ''), $string));
    return $isjs ? 'document.write("'.$string.'");' : $string;
  }

  /**
   * 转义 javascript 代码标记
   *
   * @param $str
   * @return mixed
   */
  function trim_script($str) {
    if(is_array($str)){
      foreach ($str as $key => $val){
        $str[$key] = trim_script($val);
      }
    }else{
      $str = preg_replace ( '/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str );
      $str = preg_replace ( '/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str );
      $str = preg_replace ( '/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str );
      $str = str_replace ( 'javascript:', 'javascript：', $str );
    }
    return $str;
  }

  /**
   * 字符截取 支持UTF8/GBK
   * @param $string
   * @param $length
   * @param $dot
   */
  function str_cut($string, $length, $dot = '...') {
    $strlen = strlen($string);
    if($strlen <= $length) return $string;
    $string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if(strtolower(CHARSET) == 'utf-8') {
      $length = intval($length-strlen($dot)-$length/3);
      $n = $tn = $noc = 0;
      while($n < strlen($string)) {
        $t = ord($string[$n]);
        if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
          $tn = 1; $n++; $noc++;
        } elseif(194 <= $t && $t <= 223) {
          $tn = 2; $n += 2; $noc += 2;
        } elseif(224 <= $t && $t <= 239) {
          $tn = 3; $n += 3; $noc += 2;
        } elseif(240 <= $t && $t <= 247) {
          $tn = 4; $n += 4; $noc += 2;
        } elseif(248 <= $t && $t <= 251) {
          $tn = 5; $n += 5; $noc += 2;
        } elseif($t == 252 || $t == 253) {
          $tn = 6; $n += 6; $noc += 2;
        } else {
          $n++;
        }
        if($noc >= $length) {
          break;
        }
      }
      if($noc > $length) {
        $n -= $tn;
      }
      $strcut = substr($string, 0, $n);
      $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
    } else {
      $dotlen = strlen($dot);
      $maxi = $length - $dotlen - 1;
      $current_str = '';
      $search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
      $replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
      $search_flip = array_flip($search_arr);
      for ($i = 0; $i < $maxi; $i++) {
        $current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
        if (in_array($current_str, $search_arr)) {
          $key = $search_flip[$current_str];
          $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
        }
        $strcut .= $current_str;
      }
    }
    return $strcut.$dot;
  }


  /**
   * 对用户的密码进行加密
   * @param $password
   * @param $encrypt //传入加密串，在修改密码时做认证
   * @return array/password
   */
  function password($password, $encrypt='') {
    $pwd = array();
    $pwd['encrypt'] =  $encrypt ? $encrypt : create_randomstr();
    $pwd['password'] = md5(md5(trim($password)).$pwd['encrypt']);
    return $encrypt ? $pwd['password'] : $pwd;
  }
  /**
   * 生成随机字符串
   * @param string $lenth 长度
   * @return string 字符串
   */
  function create_randomstr($lenth = 6) {
    return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
  }

  /**
   * 检查密码长度是否符合规定
   *
   * @param STRING $password
   * @return  TRUE or FALSE
   */
  function is_password($password) {
    $strlen = strlen($password);
    if($strlen >= 6 && $strlen <= 20) return true;
    return false;
  }

   /**
   * 检测输入中是否含有错误字符
   *
   * @param char $string 要检查的字符串名称
   * @return TRUE or FALSE
   */
   function is_badword($string) {
    $badwords = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n","#");
    foreach($badwords as $value){
      if(strpos($string, $value) !== FALSE) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * 检查用户名是否符合规定
   *
   * @param STRING $username 要检查的用户名
   * @return  TRUE or FALSE
   */
  function is_username($username) {
    $strlen = strlen($username);
    if(is_badword($username) || !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)){
      return false;
    } elseif ( 20 < $strlen || $strlen < 2 ) {
      return false;
    }
    return true;
  }

  /**
   * 功能：检测一个目录是否存在，不存在则创建它
   * @param string    $path      待检测的目录
   * @return boolean
   */
  function makeDir($path) {
    return is_dir($path) or (makeDir(dirname($path)) and @mkdir($path, 0777));
  }

  /**
   * 功能：检测一个字符串是否是邮件地址格式
   * @param string $value    待检测字符串
   * @return boolean
   */
  function is_email($value) {
    return preg_match("/^[0-9a-zA-Z]+(?:[\_\.\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i", $value);
  }


  /**
   * 文件下载
   * @param $filepath 文件路径
   * @param $filename 文件名称
   */

  function file_down($filepath, $filename = '') {
    if(!$filename) $filename = basename($filepath);
    if(is_ie()) $filename = rawurlencode($filename);
    $filetype = fileext($filename);
    $filesize = sprintf("%u", filesize($filepath));
    if(ob_get_length() !== false) @ob_end_clean();
    header('Pragma: public');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: pre-check=0, post-check=0, max-age=0');
    header('Content-Transfer-Encoding: binary');
    header('Content-Encoding: none');
    header('Content-type: '.$filetype);
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Content-length: '.$filesize);
    readfile($filepath);
    exit;
  }

  /**
   * Function dataformat
   * 时间转换
   * @param $n INT时间
   */
  function dataformat($n) {
    $hours = floor($n/3600);
    $minite = floor($n%3600/60);
    $secend = floor($n%3600%60);
    $minite = $minite < 10 ? "0".$minite : $minite;
    $secend = $secend < 10 ? "0".$secend : $secend;
    if($n >= 3600){
      return $hours.":".$minite.":".$secend;
    }else{
      return $minite.":".$secend;
    }
  }

  /**
    * 检查GD库是否支持
   */
  function check_gd() {
    if(!function_exists('imagepng') && !function_exists('imagejpeg') && !function_exists('imagegif')) {
      $gd = L('gd_unsupport');
    } else {
      $gd = L('gd_support');
    }
    return $gd;
  }

  /**
   * 按照尺寸生成缩略图
   * @param $image 原图片地址
   * @param $width   缩略图宽度
   * @param $height   缩略图高度
   */
  function thumb($imgurl, $width=100, $height=100, $smallpic = 'nopic.gif') {
    import("ORG.Util.Image");
    if(empty($imgurl)) return __PUBLIC__. DIRECTORY_SEPARATOR . "images". DIRECTORY_SEPARATOR ."home". DIRECTORY_SEPARATOR . $smallpic;
    $imgurl_replace = str_replace(C('SITE_URL'), '', $imgurl);
    if(!extension_loaded('gd') || strpos($imgurl_replace, '://')) return $imgurl;
    $imagepath = APP_PATH . $imgurl_replace;
    // return $imagepath;
    if(!file_exists($imagepath)) return __PUBLIC__. DIRECTORY_SEPARATOR . "images". DIRECTORY_SEPARATOR ."home". DIRECTORY_SEPARATOR . $smallpic;

    list($width_t, $height_t, $type, $attr) = getimagesize($imagepath);

    if($width>=$width_t || $height>=$height_t) return $imgurl;
    $fileext = fileext(basename($imgurl_replace));
    $file_name = basename($imgurl_replace, ".". $fileext);
    $newimgurl = dirname($imgurl_replace). '/' . $file_name . '_thumb_'.$width.'_'.$height.".".$fileext; // .'-'.;basename($imgurl_replace);
    $newimgpath = APP_PATH . $newimgurl;
    if(file_exists($newimgurl)) return C('SITE_URL').$newimgurl;

    return Image::thumb($imagepath, $newimgpath, '', $width, $height) ? C('SITE_URL').$newimgurl : $imgurl;
  }

  function check_image_thumb($image) {
    return extension_loaded('gd') && preg_match("/\.(jpg|jpeg|gif|png)/i", $image, $m) && file_exists($image) && function_exists('imagecreatefrom'.($m[1] == 'jpg' ? 'jpeg' : $m[1]));
  }

  function get_location($ip) {
    import('ORG.Net.IpLocation');// 导入IpLocation类
    $Ip = new IpLocation(); // 实例化类 参数表示IP地址库文件
    $iplocation = $Ip->getlocation($ip); // 获取某个IP地址所在的位置
    $area = iconv('GB2312','UTF-8', $iplocation['country'].$iplocation['area']);
    return $area;
  }

  /**
   * 提示信息页面跳转，跳转地址如果传入数组，页面会提示多个地址供用户选择，默认跳转地址为数组的第一个值，时间为5秒。
   * showmessage('登录成功', array('默认跳转地址'=>'http://www.phpcms.cn'));
   * @param string $msg 提示信息
   * @param mixed(string/array) $url_forward 跳转地址
   * @param int $ms 跳转等待时间
   */
  function showmessage($msg, $url_forward = 'goback', $ms = 1250, $dialog = '', $returnjs = '') {
    if(defined('IN_ADMIN')) {
      include(TMPL_PATH.GROUP_NAME.DIRECTORY_SEPARATOR."Public".DIRECTORY_SEPARATOR."error.html");
    } else {
      include(TMPL_PATH.GROUP_NAME.DIRECTORY_SEPARATOR."Public".DIRECTORY_SEPARATOR."error.html");
    }
    exit;
  }

  /**
   * 当前路径
   * 返回指定栏目路径层级
   * @param $catid 栏目id
   * @param $symbol 栏目间隔符
   */
  function catpos($catid, $symbol=' > ', $category_arr){
    $sites = include(CONF_PATH.'sitelist.php');
    $siteid = get_siteid();
    if(!isset($category_arr[$catid])) return '';
    $pos = '';
    $siteurl = str_replace('http://', '', $sites[$category_arr[$catid]['siteid']]['domain']);
    $arrparentid = array_filter(explode(',', $category_arr[$catid]['arrparentid'].','.$catid));
    $pos = '<ol class="breadcrumb">';
    foreach($arrparentid as $catid) {
      $url = U(GROUP_NAME.'/Content/lists@'.$siteurl, 'catid='.$catid);
      $pos .= '<li><a href="'.$url.'">'.$category_arr[$catid]['catname'].$symbol.'</a></li>';
    }
    $pos .= '</ol>';
    return $pos;
  }

  /**
   *  将数组转换成以主键值为key的多维数组
   *  @param array $array  需要转换的数组
   *  @param string $_key  主键名称
   */

  function array_key_translate($array, $_key = 'id') {
    if (is_array($array)) {
      $temp = array();
      foreach ($array as $value) {
        $temp[$value[$_key]] = $value;
      }
      return $temp;
    }
    return $array;
  }

  /**
   *  将数组转换成 主键 => 值 的键值对(一位数组)，适应select
   *  @param array $array  需要转换的数组
   *  @param string $_key  主键名称
   *  @param string $_value  字段名称
   */

  function array_translate($array, $_key = 'id', $_value= 'name') {
    if (is_array($array)) {
      $temp = array();
      foreach ($array as $value) {
        $temp[$value[$_key]] = $value[$_value];
      }
      return $temp;
    }
    return $array;
  }

  function parsehousetype($housetype) {
    $array = explode(",", $housetype);
    return $array[0] . "室" . $array[1] . "厅" . $array[2] . "卫" . $array[3] . "厨" . $array[4] . "阳台";
  }
