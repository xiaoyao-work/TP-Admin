<?php
/**
 * 后台首页
*/
class IndexAction extends CommonAction {
  public function index() {
    // 菜单显示自定义方式
    $model = D("Menu");
    if ($_SESSION[C('ADMIN_AUTH_KEY')]) {
      $top_menu = $model->where( array( 'siteid' => $this->siteid, 'pid' => 0, 'status' => 1 ) )->order('sort desc, id asc')->select();
      $sites = D('Site')->select();
    } else {
      $top_menu = $model->table( C("DB_PREFIX") . 'access as access, ' . C("DB_PREFIX") . 'node as node')->where("node.pid = 0 and node.status = 1 and node.id = access.node_id and access.role_id = {$_SESSION['user_info']['role_id']} and access.siteid=" . $this->siteid)->order('node.sort desc, node.id asc')->select();

      $sites = M()->table( C("DB_PREFIX") . 'access as access, ' . C("DB_PREFIX") . 'site as site' )->where( "access.siteid = site.id and access.role_id = {$_SESSION['user_info']['role_id']}" )->group('access.siteid')->select();
    }

    $site_info = get_site_info( get_siteid() );
    $this->assign('sites', $sites);
    $this->assign('site_info', $site_info);
    $this->assign('top_menu', $top_menu);
    $this->assign('user_info', $_SESSION['user_info']);
    $this->display('Admin:index');
  }

  public function left() {
    // 菜单显示自定义方式
    $mid = empty($_GET['mid']) ? 1 : $_GET['mid'];
    $model = D("Menu");
    if ($_SESSION[C('ADMIN_AUTH_KEY')]) {
      $menulist = $model->where("pid = {$mid} and status = 1")->order('sort desc')->select();
    } else {
      $menulist = $model->table( C("DB_PREFIX") . 'access as access, ' . C("DB_PREFIX") . 'node as node')->where("node.pid = {$mid} and node.status = 1 and node.id = access.node_id and access.role_id = {$_SESSION['user_info']['role_id']} and access.siteid=" . $this->siteid)->order('node.sort desc')->select();
    }

    foreach ($menulist as $key => $value) {
      if ($_SESSION[C('ADMIN_AUTH_KEY')]) {
        $childs = $model->where( array( 'siteid' => $this->siteid, 'pid' => $value['id'], 'status' => 1 ) )->order('sort desc')->select();
      } else {
        $childs = $model->table( C("DB_PREFIX") . 'access as access, ' . C("DB_PREFIX") . 'node as node')->where("node.pid = {$value['id']} and node.status = 1 and node.id = access.node_id and access.role_id = {$_SESSION['user_info']['role_id']} and access.siteid=" . $this->siteid)->order('node.sort desc')->select();
      }
      $menulist[$key]['childs'] = $childs;
    }
    $this->assign('menulist', $menulist);
    $this->display('Admin:left');
  }

  public function main() {
     if (function_exists('gd_info')) {
      $gd = gd_info();
      $gd = $gd['GD Version'];
    } else {
      $gd = "不支持";
    }
    $system_info = array(
      '0' => PHP_OS,
      '1' => php_sapi_name(),
      '2' => function_exists("mysql_close") ? mysql_get_client_info() : '不支持',
      '3' => PHP_VERSION,
      '4' => ini_get('upload_max_filesize'),
      '5' => $gd,
      );
      $this->assign('system_info', $system_info);
      $this->assign('area',get_location($_SESSION['user_info']['last_login_ip']));
      $this->assign('user_info', $_SESSION['user_info']);
      $this->display('Admin:main');
  }

  public function change_site() {
    if (!$_SESSION[C('USER_AUTH_KEY')]) {
      $this->assign('jumpUrl', __GROUP__ . C('USER_AUTH_GATEWAY'));
      $this->assign('waitSecond',1);
      $this->error(L('admin_login'));
    }
    $siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : '';
    if (empty($siteid)) {
      $this->error( '参数错误', 'Index/index' );
    }
    set_siteid( $siteid );
    $this->redirect('Index/index');
  }


  /**
   * 维持 session 登陆状态
   */
  public function public_session_life() {
    if (!$_SESSION[C('USER_AUTH_KEY')]) {
      $this->assign('jumpUrl', __GROUP__ . C('USER_AUTH_GATEWAY'));
      $this->assign('waitSecond',1);
      $this->error(L('admin_login'));
    }
    return true;
  }

  /**
   * 清理缓存，待完善
  */
  public function cache_clean() {
    echo "<span style='color: red;'>缓存清理中……</span><br/>";
    $path = RUNTIME_PATH . "Cache/";
    echo delDirAndFile($path);
    echo "<br/><span style='color: red;'>缓存清理完毕。</span>";
    /*$message = "";
    $path = RUNTIME_PATH . "Cache/Admin/";
    $handle = opendir($path);
    if ($handle) {
      while (false !== ( $item = readdir($handle) )) {
        echo "filename: " . $item . "<br />";
        if ($item != "." && $item != "..") {
          if (is_dir("$path/$item")) {
            // $msg = delDirAndFile("$path/$item", $delDir);
            // if ( $msg ){
            //     $message .= $msg;
            // }
            $message .= "目录";
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
    } else {
      if (file_exists($path)) {
        if (unlink($path)){
          $message .= "删除文件" + basename($path) . "<br />";
        } else {
          $message .= "删除文件" + basename($path) . "失败<br />";
        }
      } else {
        $message .= "文件" + basename($path) . "不存在<br />";
      }
    }
    echo $message;
    echo "<br/><span style='color: red;'>缓存清理完毕。</span>";*/
  }
}