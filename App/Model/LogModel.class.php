<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------
namespace Model;
use Think\Page as Page;

/**
 * 日志操作模型
*/
class LogModel extends BaseModel {
    protected $tableName = 'log';
    public function logList($where="1=1") {
        $logs = $this->where($where)->page((isset($_GET['p']) ? $_GET['p'] : 0).',25')->order('id desc')->select();
        $count      = $this->where($where)->count();
        $Page       = new Page($count,25);
        $show       = $Page->show();
        return array("data" => $logs, "page" => $show);
    }

    public function addLog( $status = 1 ) {
        $data = array();
        $data['ip']    = get_client_ip();
        $data['date']  = date("Y-m-d H:i:s");
        $data['username'] = $_SESSION['user_info']['account'];
        $data['module'] = CONTROLLER_NAME;
        $data['action'] = ACTION_NAME;
        $data['querystring'] = U( CONTROLLER_NAME . '/' . ACTION_NAME );
        $data['status'] = $status;
        $data['userid'] = $_SESSION['user_info']['id'];
        $this->add($data);
    }
}