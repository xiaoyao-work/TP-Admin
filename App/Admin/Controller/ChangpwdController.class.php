<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 修改管理员密码
*/
class ChangpwdController extends CommonController {
  public function index() {
    $this->assign('account',$_SESSION['user_info']['account']);
    $this->display();
  }

  public function edit() {
    if (IS_POST) {
      $old_pwd = trim($_POST['old_pwd']);
      $new_pwd = trim($_POST['new_pwd']);
      $re_pwd  = trim($_POST['re_pwd']);
      if($new_pwd != $re_pwd || strlen($new_pwd) < 5 || strlen($new_pwd) > 20) {
        $this->error('新密码格式错误！', __MODULE__ . '/Changpwd/index');
      }
      $id = $_SESSION['user_info']['id'];
      $user = D("User")->find($id);
      if(md5($old_pwd) != $user['password']) {
        $this->error('原始密码错误！', __MODULE__ . '/Changpwd/index');
      }
      if (D("User")->where(array('id' => $id))->save(array('password' => md5($new_pwd))) !== false) {
        $this->success('操作成功！', __MODULE__ . '/Changpwd/index');
      } else {
        $this->error('操作失败！', __MODULE__ . '/Changpwd/index');
      }
    } else {
      $this->assign('account',$_SESSION['user_info']['account']);
      $this->display("index");
    }
  }

  /**
   * 异步检测用户名
   */
  function public_checkname_ajx() {
    $username = isset($_GET['username']) && trim($_GET['username']) ? trim($_GET['username']) : exit(0);
    if (D("User")->where(array('account'=>$username))->field('id')->find()) {
      exit('0');
    }
    exit('1');
  }

  /**
   * 异步检测密码
   */
  function public_password_ajx() {
    $userid = $_SESSION['user_info']['id'];
    $user = D('User')->find($userid);
    if ( md5($_GET['old_password']) == $user['password'] ) {
      exit('1');
    }
    exit('0');
  }

  /**
   * 异步检测emial合法性
   */
  function public_email_ajx() {
    $email = $_GET['email'];
    $userid = $_SESSION['userid'];
    $check = D("User")->where(array('email'=>$email))->field('id')->find();
    if ($check && $check['id'] != $userid){
      exit('0');
    }else{
      exit('1');
    }
  }
}