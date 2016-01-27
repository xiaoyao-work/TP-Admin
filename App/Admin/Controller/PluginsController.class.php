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
    protected $pluginPath ;
    function __construct() {
        parent::__construct();
        $this->pluginPath = APP_PATH . 'Plugins/';
    }

    public function call() {
        $plugin = I('get.plugin');
        if (empty($plugin)) {
            exit();
        }
        file_exists($this->pluginPath . $plugin) ? require $this->pluginPath . $plugin : exit();
    }
}