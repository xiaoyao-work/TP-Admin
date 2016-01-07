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

class UserModel extends Model
{
    public function adminList() {
        $sql = "SELECT user.*, role.name, role.id as role_id FROM " . C('DB_PREFIX') . "user AS user, " . C('DB_PREFIX') . "role AS role, " . C('DB_PREFIX') . "role_user as ru WHERE user.id = ru.user_id and ru.role_id = role.id ORDER BY user.id ASC";
        $list = $this->query($sql);
        return $list;
    }

    public function roleList() {
        $role = M('role');
        $list = $role->field('id,name')->where('id > 1')->order('id ASC')->select();
        return $list;
    }

    public function addUser() {
        M()->startTrans();
        $datas['account']       = trim($_POST['account']);
        $datas['password']      = md5(trim($_POST['pwd']));
        $datas['status']        = $_POST['status'];
        $datas['role_id'] = intval($_POST['role_id']);
        $user_id = $this->add($datas);
        if ($user_id) {
            $data['role_id']        = $_POST['role_id'];
            $data['user_id']        = $user_id;
            if(M('role_user')->add($data)) {
                M()->commit();
                return true;
            } else {
                M()->rollback();
                return false;
            }
        } else {
            M()->rollback();
            return false;
        }
    }
}