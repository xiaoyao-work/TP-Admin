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
* 后台日志管理类
*/
class LogController extends CommonController {
    public function index() {
        $page_data = model("Log")->logList();
        $this->assign("list", $page_data["data"]);
        $this->assign("page", $page_data["page"]);
        $this->display();
    }

    public function search() {
        $datas['start_time'] = empty(I('request.start_time')) ? 0 : I('request.start_time');
        $datas['end_time'] = empty(I('request.end_time')) ? date("Y-m-d H:i:s") : I('request.start_time');
        if (!empty(I('request.account'))) {
            $datas['username'] = I('request.account');
        }
        if (!empty(I('request.ip'))) {
            $datas['ip'] = I('request.ip');
        }
        if (I('request.status') != 'all') {
            $datas['status'] = I('request.status');
        }
        $datas['date'] = array('between',"{$datas['start_time']},{$datas['end_time']}");
        $page_data = model("Log")->logList($datas);
        $this->assign("list", $page_data["data"]);
        $this->assign("page", $page_data["page"]);
        $this->display("Log:index");
    }
}