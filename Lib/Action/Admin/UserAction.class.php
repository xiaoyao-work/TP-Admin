<?php
class UserAction extends CommonAction {
  protected $db;

  function __construct() {
    parent::__construct();
    $this->db = D("User");
  }

  public function index() {
    $users = $this->db->adminList();
    $this->assign("users", $users);
    $this->display();
  }
  public function add() {
    if(IS_POST) {
      $this->checkToken();
      if( $this->db->addUser() > 0) {
        $this->success('操作成功！',__GROUP__.'/User/index');
      } else {
        $this->error('操作失败！',__GROUP__.'/User/index');
      }
    } else {
      $roles = $this->db->roleList();
      $this->assign("roles", $roles);
      $this->display();
    }
  }

  public function edit() {
    if(IS_POST) {
      $this->checkToken();
      $datas = $_POST['info'];
      if ( !empty($_POST['pwd']) ) {
        $datas['password'] = md5(trim($_POST['pwd']));
      }
      if($this->db->where("id = {$_POST['id']}")->save($datas)) {
        $this->success('操作成功！',__GROUP__.'/User/index');
      } else {
        $this->error('操作失败！',__GROUP__.'/User/index');
      }
    } else {
      $nid = $_GET['nid'];
      if(empty($nid)) {
        $this->error('异常操作！',__GROUP__.'/User/index');
      }
      if (!( $user = $this->db->table( C("DB_PREFIX").'user as user, '.C("DB_PREFIX").'role_user as role_user')->where("user.id = {$nid} and user.id = role_user.user_id")->field("user.*, role_user.role_id")->find()) ) {
        $this->error('管理员不存在',__GROUP__.'/User/index');
      }
      $roles = $this->db->roleList();
      $this->assign("roles", $roles);
      $this->assign('user',$user);
      $this->display();
    }
  }
  public function del() {
    $nid = intval($_GET['nid']);
    if(empty($nid)) {
      $this->error('异常操作！',__GROUP__.'/User/index');
    }
    if($this->db->where("id = {$nid}")->delete() !== fasle) {
      $this->success('操作成功！',__GROUP__.'/User/index');
    } else {
      $this->error('操作失败！',__GROUP__.'/User/index');
    }
  }
}