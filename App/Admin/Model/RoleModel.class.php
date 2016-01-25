<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

class RoleModel extends Model {
    public function roleList() {
        $list = $this->order('id ASC')->select();
        return $list;
    }

    public function addRole() {
        $datas['name']      = trim($_POST['name']);
        $datas['status']    = $_POST['status'];
        $datas['remark']    = trim($_POST['remark']);
        return $this->add($datas);
    }

    public function delRole($nid) {
        $role = M('role');
        $nid  = $_GET['nid'];
        return $role->where("id = '{$nid}'")->delete();
    }

    public function getRole($nid) {
        $nid  = $_GET['nid'];
        $list = $this->where("id = '{$nid}'")->find();
        return $list;
    }

    public function editRole($nid) {
        $datas['name']      = trim($_POST['name']);
        $datas['status']    = $_POST['status'];
        $datas['remark']    = trim($_POST['remark']);
        $res = $this->where("id = '{$nid}'")->save($datas);
        return $res;
    }
}
