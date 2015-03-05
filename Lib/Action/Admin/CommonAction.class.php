<?php
/**
 * 后台基类
*/
defined('THINK_PATH') or exit('No permission resources.');
class CommonAction extends Action {
  protected $siteid;
  function _initialize() {
    $this->siteid = get_siteid();
    // import('ORG.Util.Cookie');
    // 用户权限检查
    if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
      import('ORG.Util.RBAC');
      if (!RBAC::AccessDecision(GROUP_NAME)) {
        //检查认证识别号
        if (!$_SESSION[C('USER_AUTH_KEY')]) {
          //跳转到认证网关
          $this->assign('jumpUrl', __GROUP__ . C('USER_AUTH_GATEWAY'));
          $this->assign('waitSecond',1);
          $this->error(L('admin_login'));
          // redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
        }
        // 没有权限 抛出错误
        if (C('RBAC_ERROR_PAGE')) {
          // 定义权限错误页面
          $this->assign('jumpUrl', __GROUP__ . C('RBAC_ERROR_PAGE'));
          $this->error(L('permission_to_operate'));
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
        $this->error(L('hash_check_false'));
      }
      unset($_POST[C("TOKEN_NAME")]);
    }
  }

}