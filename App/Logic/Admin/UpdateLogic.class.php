<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic\Admin;

use Logic\BaseLogic;

class UpdateLogic extends BaseLogic {
    protected $update_url;

    public function __construct() {
        $this->update_url = HHAILUO_API_DOMAIN . 'update.html';
        $this->uuid       = $this->check_uuid();
    }

    public function check() {
        $url          = $this->url('check');
        $curl_request = Service\BaseService::get($url);
        return $this->getInterfaceData($curl_request);
    }

    public function url($action = 'check') {
        $data = http_build_query($this->getSysBaseInfo($action));
        return $this->update_url . '?' . $data;
    }

    public function getSysBaseInfo($action) {
        $site        = get_site_info();
        $sitename    = $site['1']['name'];
        $siturl      = $site['1']['domain'];
        $base_domain = $site['1']['base_domain'];
        $sitelist    = '';
        foreach ($site as $list) {
            $sitelist .= $list['domain'] . ',';
        }
        $pars = [
            'action'      => $action,
            'sitename'    => $sitename,
            'base_domain' => $base_domain,
            'siteurl'     => $siturl,
            'version'     => SYS_VERSION,
            'os'          => PHP_OS,
            'php'         => phpversion(),
            'mysql'       => function_exists("mysql_close") ? mysql_get_client_info() : '不支持',
            'browser'     => urlencode($_SERVER['HTTP_USER_AGENT']),
            'username'    => urlencode($_SESSION['user_info']['realname']),
            'email'       => urlencode($_SESSION['user_info']['email']),
            'sitelist'    => urlencode($sitelist),
            'uuid'        => urlencode($this->uuid),
            'verify'      => md5($this->uuid),
        ];
        return $pars;
    }

    public function notice() {
        return $this->url('notice');
    }

    public function check_uuid() {
        $site_db = model('site');
        $info    = $site_db->field('uuid')->find(1);
        if ($info['uuid']) {
            return $info['uuid'];
        } else {
            $uuid = uniqid();
            if ($site_db->where(['id' => 1])->save(['uuid' => $uuid])) {
                logic('site', 'Admin')->setCache();
                return $uuid;
            }
        }
    }

    function cycheck($result) {
        if (!is_array($result) || empty($result) || !isset($result['code'])) {
            return;
        }
        $jumpurl = 'goback';
        if ($result['code'] == '10403') {
            $jumpurl = $result['data']['jumpurl'];
        }
        showmessage($result['message'], $jumpurl);
    }
}