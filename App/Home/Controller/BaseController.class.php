<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台基类
 */
class BaseController extends Controller {
    protected $siteid, $siteInfo;

    function __construct() {
        parent::__construct();
        // $this->route = $this->app->router->getCurrentRoute();
        set_site_by_domain();
        $this->siteid   = get_siteid();
        $this->sites    = get_site_info();
        $this->siteInfo = $this->sites[$this->siteid];
        $site_theme     = empty($this->siteInfo['template']) ? 'hhailuo' : $this->siteInfo['template'];
        C('DEFAULT_THEME', $site_theme);
        $this->assign('siteInfo', $this->siteInfo);
        $this->assign('sites', $this->sites);
        $this->assign('siteid', $this->siteid);
        $member_info = session('member_info');
        if (!empty($member_info)) {
            $this->assign('member_info', $member_info);
        }
    }

    protected function beforeFilter($method, $params = []) {
        if (empty($params)) {
            call_user_func([$this, $method]);
        } else {
            if (isset($params['only'])) {
                if (in_array(ACTION_NAME, $params['only'])) {
                    call_user_func([$this, $method]);
                }
            } elseif (isset($params['except'])) {
                if (!in_array(ACTION_NAME, $params['except'])) {
                    call_user_func([$this, $method]);
                }
            }
        }
    }

    protected function getTemplateFile($post_type, $default_view_filename = 'index', $default_view_dirname = 'Post') {
        $post_type_view_dir_name = parse_name($post_type, 1);
        $theme_path              = APP_PATH . 'Home/View/' . C('DEFAULT_THEME') . '/';
        $view_dir                = is_dir($theme_path . $post_type_view_dir_name) ? $theme_path . $post_type_view_dir_name . "/" : $theme_path . $default_view_dirname . '/';
        $template_file           = is_file($view_dir . $default_view_filename . '-' . $post_type . '.html') ? $view_dir . $default_view_filename . '-' . $post_type . '.html' : $view_dir . $default_view_filename . '.html';
        return $template_file;
    }

    protected function getPageTemplateFile($filename) {
        $view_dir = APP_PATH . 'Home/View/' . C('DEFAULT_THEME') . '/Post/';
        return is_file($view_dir . $filename . '.html') ? $view_dir . $filename . '.html' : $view_dir . 'page.html';
    }
}