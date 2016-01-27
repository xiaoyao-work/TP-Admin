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

class UserController extends CommonController {
    protected $db;

    function __construct() {
        parent::__construct();
        $this->db = model('User');
    }

    public function index() {
        $users = $this->db->adminList();
        $this->assign("users", $users);
        $this->display();
    }

    public function add() {
        if(IS_POST) {
            $this->checkToken();
            $data = I('post.info');
            if(logic('user')->addUser($data)) {
                $this->success('操作成功！',__MODULE__.'/User/index');
            } else {
                $this->error('操作失败！',__MODULE__.'/User/index');
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
            if ( !empty($datas['pwd']) ) {
                $datas['password'] = md5(trim($datas['pwd']));
            }
            $this->db->startTrans();
            if($this->db->where(array('id' => $_POST['id']))->save($datas) !== false && model('role_user')->where(array('user_id' => $_POST['id']))->save(array('role_id' => $datas['role_id'])) !== false ) {
                $this->db->commit();
                $this->success('操作成功！',__MODULE__.'/User/index');
            } else {
                $this->db->rollback();
                $this->error('操作失败！',__MODULE__.'/User/index');
            }
        } else {
            $id = I('get.id');
            if(empty($id)) {
                $this->error('异常操作！',__MODULE__.'/User/index');
            }
            $user = $this->db->table('__USER__ as user, __ROLE_USER__ as role_user')
                ->where(sprintf("user.id = %d and user.id = role_user.user_id", $id))
                ->field("user.*, role_user.role_id")
                ->find();
            if (empty($user)) {
                $this->error('管理员不存在',__MODULE__.'/User/index');
            }
            $roles = $this->db->roleList();
            $this->assign("roles", $roles);
            $this->assign('user',$user);
            $this->display();
        }
    }

    public function del() {
        $id = I('get.id');
        if(empty($id)) {
            $this->error('异常操作！',__MODULE__.'/User/index');
        }
        if(logic('user')->delete($id) !== fasle) {
            $this->success('操作成功！',__MODULE__.'/User/index');
        } else {
            $this->error('操作失败！',__MODULE__.'/User/index');
        }
    }

    public function changePassword() {
        if (IS_POST) {
            $old_pwd = I('post.old_pwd');
            $new_pwd = I('post.new_pwd');
            $re_pwd = I('post.re_pwd');
            if($new_pwd != $re_pwd || strlen($new_pwd) < 5 || strlen($new_pwd) > 20) {
                $this->error('新密码格式错误！');
            }
            $id = session('user_info.id');
            $user = $this->db->find($id);
            if(md5($old_pwd) != $user['password']) {
                $this->error('原始密码错误！');
            }
            if ($this->db->where(array('id' => $id))->save(array('password' => md5($new_pwd))) !== false) {
                $this->success('操作成功！');
            } else {
                $this->error('操作失败！');
            }
        } else {
            $this->assign('account', session('user_info'));
            $this->display();
        }
    }

    /**
    * 异步检测用户名
    */
    function public_checkname_ajx() {
        $username = isset($_GET['username']) && trim($_GET['username']) ? trim($_GET['username']) : exit(0);
        if ($this->db->where(array('account'=>$username))->field('id')->find()) {
            exit('0');
        }
        exit('1');
    }

    /**
    * 异步检测密码
    */
    function public_password_ajx() {
        $userid = session('user_info.id');
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
        $email = I('get.email');
        $userid = session('userid');
        $check = $this->db->where(array('email'=>$email))->field('id')->find();
        if ($check && $check['id'] != $userid){
            exit('0');
        }else{
            exit('1');
        }
    }
}