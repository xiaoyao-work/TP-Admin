<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;
use Org\Util\Image as Image;

class PublicController extends Controller {
	public function index() {
		header("Location:".__MODULE__."/Public/login");
	}

	//验证码
	public function verify() {
		$type = I('type', 'gif');
		Image::buildImageVerify( 4, 1, $type);
	}

	public function login() {
		$is_login = session(C('USER_AUTH_KEY'));
		if(empty($is_login)) {
			$this->display();
		} else {
			header("Location:".__MODULE__.'/Index');
		}
	}

	// 用户登出
	public function loginout() {
		session(null);
		session_destroy();
		$this->success('登出成功！',__CONTROLLER__.'/login/');
	}

	// 登录检测
	public function checkLogin() {
		$login_logic = logic('login');
		$result = $login_logic->checkLogin(I('account'), I('password'), I('verify'));
		if ($result === false) {
			$this->assign('jumpUrl', $login_logic->getJumpUrl());
			$this->error($login_logic->getErrorMessage());
		} else {
			$this->success('登录成功！',__MODULE__.'/Index');
		}
	}
}