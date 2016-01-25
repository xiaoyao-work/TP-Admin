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
* 角色操作表
*/
class RoleController extends CommonController {

    public function index() {
        $this->assign("roles", model('role')->roleList());
        $this->display();
    }

    public function add() {
        if(IS_POST) {
            $this->checkToken();
            $data = I('post.info');
            if(model('role')->add($data) !== false) {
                $this->success('操作成功！',__MODULE__.'/Role/index');
            } else {
                $this->error('操作失败！',__MODULE__.'/Role/index');
            }
        } else {
            $this->display();
        }
    }

    public function edit() {
        if(IS_POST) {
            $id = I('post.id');
            $this->checkToken();
            $data = I('post.info');
            if(model('role')->where(array('id' => $id))->save($data) !== false) {
                $this->success('操作成功！',__MODULE__.'/Role/index');
            } else {
                $this->error('操作失败！',__MODULE__.'/Role/index');
            }
        } else {
            $id = I('get.id');
            if(empty($id)) {
                $this->error('异常操作！',__MODULE__.'/Role/index');
            }
            $this->assign('id',$id);
            $role = model('role')->find($id);
            $this->assign("role", $role);
            $this->display();
        }
    }

    public function del() {
        $id = $_GET['id'];
        if(empty($id)) {
            $this->error('异常操作！',__MODULE__.'/Role/index');
        }
        $role_user = model('role_user');
        $count = $role_user->where("user_id = {$id}")->count();
        if($count > 0) {
            $this->error('请先删除该角色下的管理员帐号！',__MODULE__.'/Role/index');
        }
        if(model('role')->where(array('id' => $id))->delete() !== false) {
            $this->success('操作成功！',__MODULE__.'/Role/index');
        } else {
            $this->error('操作失败！',__MODULE__.'/Role/index');
        }
    }

    public function limits() {
        $role_id = intval($_REQUEST['role_id']);
        if(IS_POST) {
            $mod = model('Access');
            $site = I('post.site');
            $menuids = I('post.menuid');
            $menus = I('post.menu');
            if (!empty($menuids)) {
                $mod->startTrans();
                $sql = "INSERT INTO ". C("DB_PREFIX") ."access (`role_id`,`node_id`,`siteid`,`request_method`) VALUES ";
                foreach ($menuids as $key => $value) {
                    $value = intval($value);
                    $request_method = isset($menus[$value]) ? $menus[$value] : 0;
                    $sql .= "( {$role_id}, {$value}, {$site}, {$request_method} ),";
                }
                $sql = substr($sql, 0, strlen($sql) -1 ) . ';';
                if ($mod->where(array('role_id' => $role_id, 'siteid' => $site))->delete() !== false) {
                    if ($mod->execute($sql) !== false) {
                        $mod->commit();
                        $this->success('操作成功！', 'index');
                    } else {
                        $mod->rollback();
                        $this->error("操作失败");
                    }
                } else {
                    $mod->rollback();
                    $this->error("操作失败");
                }

            } else {
                $mod->where(array( 'role_id' => $role_id, 'siteid' => $site ))->delete();
                $this->success('操作成功！', 'index');
            }
        } else {
            $menus = model("Menu")->nodeList();
            $authorized = array();
            $sites = model('Site')->select();
            $siteid = I('get.siteid');
            if (!empty($siteid)) {
                $current_site = model('Site')->find($siteid);
            }
            if ( !isset( $current_site ) || empty($current_site) ) {
                $current_site = current($sites);
            }
            $access_list = model("Access")->where( array( 'role_id' => $role_id, 'siteid' => $current_site['id'] ) )->select();
            foreach ($access_list as $key => $value) {
                $authorized[$value['node_id']] = $value;
            }
            $this->assign('current_site',$current_site);
            $this->assign('sites', $sites);
            $this->assign('authorized',$authorized);
            $this->assign("menus", $menus);
            $this->assign("role_id", $role_id);
            $this->display();
        }
    }
}
?>