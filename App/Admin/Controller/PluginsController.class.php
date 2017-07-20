<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 插件
 */
class PluginsController extends CommonController {

    public function index() {
        $this->display();
    }

    public function call() {
        $plugin = I('request.plugin');
        if (empty($plugin)) {
            $this->error('参数异常！');
        }
        $plugin_file = PLUGINS_PATH . $plugin . DS . $plugin . ".php";
        if (file_exists($plugin_file)) {
            require_once $plugin_file;
        }
        exit();
    }
}