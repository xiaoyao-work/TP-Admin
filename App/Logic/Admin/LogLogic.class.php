<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic;
use Logic\BaseLogic;

/**
 * 日志控制Logic
 */
class LogLogic extends BaseLogic {
    /**
     * 添加当前登录日志
     * @param string $username 登录账号
     * @param int    $status   登录状态
     * @param int    $userid   登录账号ID
     */
    public function add($username, $status = 0, $userid = 0) {
        $data                = [];
        $data['ip']          = get_client_ip();
        $data['date']        = date("Y-m-d H:i:s");
        $data['username']    = $username;
        $data['module']      = MODULE_NAME;
        $data['action']      = ACTION_NAME;
        $data['querystring'] = U(MODULE_NAME . '/' . ACTION_NAME);
        $data['status']      = $status;
        $data['userid']      = $userid;
        $result              = model('Log', 'Admin')->add($data);

        return $result;
    }
}