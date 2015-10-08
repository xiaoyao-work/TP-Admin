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
 * 后台首页
*/
class IndexController extends Controller {
    public function v2_1() {
        M()->execute("ALTER TABLE `" . C('DB_PREFIX') . "model_field` ADD `islist` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `isadd`;");
    }
}