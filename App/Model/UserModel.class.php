<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------
namespace Model;

/**
 * 管理员模型
 */
class UserModel extends BaseModel {
    public function adminList() {
        $sql = "SELECT user.*, role.name, role.id as role_id FROM " . C('DB_PREFIX') . "user AS user, " . C('DB_PREFIX') . "role AS role, " . C('DB_PREFIX') . "role_user as ru WHERE user.id = ru.user_id and ru.role_id = role.id ORDER BY user.id ASC";
        $list = $this->query($sql);
        return $list;
    }

    public function roleList() {
        $role = model('role');
        $list = $role->field('id,name')->where('id > 1')->order('id ASC')->select();
        return $list;
    }

}