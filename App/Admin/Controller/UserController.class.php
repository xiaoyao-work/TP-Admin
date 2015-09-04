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

class UserController extends CommonController {
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
            if($this->db->addUser()) {
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
            if ( !empty($_POST['pwd']) ) {
                $datas['password'] = md5(trim($_POST['pwd']));
            }
            if($this->db->where(array('id' => $_POST['id']))->save($datas)) {
                $this->success('操作成功！',__MODULE__.'/User/index');
            } else {
                $this->error('操作失败！',__MODULE__.'/User/index');
            }
        } else {
            $nid = $_GET['nid'];
            if(empty($nid)) {
                $this->error('异常操作！',__MODULE__.'/User/index');
            }
            if (!( $user = $this->db->table( C("DB_PREFIX").'user as user, '.C("DB_PREFIX").'role_user as role_user')->where("user.id = {$nid} and user.id = role_user.user_id")->field("user.*, role_user.role_id")->find()) ) {
                $this->error('管理员不存在',__MODULE__.'/User/index');
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
            $this->error('异常操作！',__MODULE__.'/User/index');
        }
        if($this->db->where("id = {$nid}")->delete() !== fasle) {
            $this->success('操作成功！',__MODULE__.'/User/index');
        } else {
            $this->error('操作失败！',__MODULE__.'/User/index');
        }
    }
}