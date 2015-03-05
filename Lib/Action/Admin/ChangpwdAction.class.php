<?php
/**
 * 修改管理员密码
*/
class ChangpwdAction extends CommonAction
{
  public function index()
  {
    $this->assign('account',$_SESSION['user_info']['account']);
    $this->display();
  }

  public function edit() {
    if (IS_POST) {
      $old_pwd = trim($_POST['old_pwd']);
      $new_pwd = trim($_POST['new_pwd']);
      $re_pwd  = trim($_POST['re_pwd']);
      $res = D("Changpwd")->eheckpwd($old_pwd,$new_pwd,$re_pwd);
      switch ($res) {
        case a:
        $this->error('原始密码错误！', __GROUP__ . '/Changpwd/index');
        break;
        case b:
        $this->error('新密码格式错误！', __GROUP__ . '/Changpwd/index');
        break;
        case 0:
        $this->error('操作失败！', __GROUP__ . '/Changpwd/index');
        break;
        case 1:
        $this->success('操作成功！', __GROUP__ . '/Changpwd/index');
        break;
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
    if ($this->db->get_one(array('username'=>$username),'userid')){
      exit('0');
    }
    exit('1');
  }

  /**
   * 异步检测密码
   */
  function public_password_ajx() {
    $userid = $_SESSION['user_info']['id'];
    $r = array();
    $r = M('User')->find($userid);
    if ( md5($_GET['old_password']) == $r['password'] ) {
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
    $check = $this->db->get_one(array('email'=>$email),'userid');
    if ($check && $check['userid']!=$userid){
      exit('0');
    }else{
      exit('1');
    }
  }

}