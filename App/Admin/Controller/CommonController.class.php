<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac as RBAC;

/**
* 后台基类
*/
class CommonController extends Controller {
	protected $siteid;
	function _initialize() {
		$this->siteid = get_siteid();
		// 用户权限检查
		if (C('USER_AUTH_ON') && !in_array(CONTROLLER_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
			if (!RBAC::AccessDecision()) {
				//检查认证识别号
				if (!$_SESSION[C('USER_AUTH_KEY')]) {
					//跳转到认证网关
					$this->assign('jumpUrl', __MODULE__ . C('USER_AUTH_GATEWAY'));
					$this->assign('waitSecond',3);
					$this->error('请先登录后台管理');
					// redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
				}
					// 没有权限 抛出错误
				if (C('RBAC_ERROR_PAGE')) {
					// 定义权限错误页面
					$this->assign('jumpUrl', __MODULE__ . C('RBAC_ERROR_PAGE'));
					$this->error('您没有权限操作该项');
					D('Log')->addLog(2);
					// redirect(C('RBAC_ERROR_PAGE'));
				} else {
					if (C('GUEST_AUTH_ON')) {
						$this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
					}
					// 提示错误信息
					$this->error(L('_VALID_ACCESS_'));
				}
			}
		}
		// 记录操作日志
		if ( !in_array(ACTION_NAME, array( 'public_session_life' )) ) {
			D('Log')->addLog(1);
		}
	}

	protected function checkToken() {
		if (IS_POST) {
			if (!M()->autoCheckToken($_POST)) {
				$this->error('[hash]数据验证失败');
			}
		}
	}

}