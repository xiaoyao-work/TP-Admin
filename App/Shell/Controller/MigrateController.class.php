<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Shell\Controller;
use Think\Controller;

/**
* 数据迁移脚本
*/
class MigrateController extends Controller {
    public function addColumnRequestMethodForNode() {
        if (M()->execute("ALTER TABLE `" . C('DB_PREFIX') . "node` ADD `request_method` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' comment '是否验证请求方式；0: 否,1:是' AFTER `params`;") !== false) {
            echo "执行成功";
        } else {
            echo "执行失败";
        }
    }

    public function addColumnRequestMethodForAccess() {
        echo M()->execute("ALTER TABLE `" . C('DB_PREFIX') . "access` ADD `request_method` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' comment '请求方式；0: All, 1: Get 2: Post' AFTER `siteid`;") === false ? '执行失败' : '执行成功';
    }
}