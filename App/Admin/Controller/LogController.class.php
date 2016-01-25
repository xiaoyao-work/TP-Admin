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

/**
 * 后台日志管理类
*/
class LogController extends CommonController {
  public function index() {
    $page_data = D("Log")->log_list();
    $this->assign("list", $page_data["data"]);
    $this->assign("page", $page_data["page"]);
    $this->display();
  }

  public function search() {
    if (empty($_REQUEST["start_time"])) {
      $datas['start_time'] = 0;
    } else {
      $datas['start_time'] = $_REQUEST['start_time'];
    }
    if (empty($_REQUEST["end_time"])) {
      $datas['end_time'] = date("Y-m-d H:i:s");
    } else {
      $datas['end_time'] = $_REQUEST['end_time'];
    }
    if (!empty($_REQUEST["account"])) {
      $datas['username'] = $_REQUEST['account'];
    }
    if (!empty($_REQUEST["ip"])) {
      $datas['ip'] = $_REQUEST['ip'];
    }
    if ($_REQUEST['status']!='all') {
      $datas['status'] = $_REQUEST['status'];
    }
    $datas['date'] = array('between',"{$datas['start_time']},{$datas['end_time']}");
    $page_data = D("Log")->log_list($datas);
    $this->assign("list", $page_data["data"]);
    $this->assign("page", $page_data["page"]);
    $this->display("Log:index");
  }
}