<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic;
use Lib\Log;

/**
* 站点Logic
*/
class UserLogic extends BaseLogic {

    public function addUser($data) {
        M()->startTrans();
        $data['password'] = md5($data['pwd']);
        $user_id = model('user')->add($data);
        if ($user_id) {
            $role_user_data = array(
                'role_id' => $data['role_id'],
                'user_id' => $user_id,
                );
            if(model('role_user')->add($role_user_data)) {
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

    public function delete($userid) {
        M()->startTrans();
        $user_model = model('user');
        if ($user_model->where(array('id' => $userid))->delete()) {
            if(model('role_user')->where(array('user_id' => $id))->delete()) {
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