<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台首页
*/
class IndexController extends CommonController {

	public function index() {
		// 获取可访问站点
		$sites = logic('site')->getAccessibleSites();
		// 获取顶部可访问菜单
		$top_menu = logic('Menu')->getAccessibleTopMenu();
		$seo = get_site_seo_info();
		$role = model('Role')->find(session('user_info.role_id'));
		$_SESSION['user_info']['name'] = $role['name'];
		$site_info = get_site_info($this->siteid);
		$this->assign('seo', $seo);
		$this->assign('sites', $sites);
		$this->assign('site_info', $site_info);
		$this->assign('top_menu', $top_menu);
		$this->assign('user_info', session('user_info'));
		$this->display();
	}

	public function left() {
		// 菜单显示自定义方式
		$mid = I('get.mid', 1);
		$menulist = logic('Menu')->getAccessibleLeftMenu($mid);
		$this->assign('menulist', $menulist);
		$this->display();
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
		$this->display();
	}

	public function change_site() {
		$siteid = I('get.siteid');
		if (empty($siteid)) {
			$this->error( '参数错误', 'Index/index' );
		}
		set_siteid($siteid);
		$this->redirect('Index/index');
	}

	/**
	* 维持 session 登陆状态
	*/
	public function public_session_life() {
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
	}
}
