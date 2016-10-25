<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Service;

use Lib\ConcurrenceCurl;

/**
 * 服务接口基类
 */
class BaseService {

    static protected $concurrenceCurl = NULL;

    static public function get($request_url, $options = []) {
        return self::call($request_url, $options);
    }

    static public function post($request_url, $params = [], $options = []) {
        $options[CURLOPT_POST]       = true;
        $options[CURLOPT_POSTFIELDS] = $params;
        return self::call($request_url, $options);
    }

    static public function asyncGet($request_url, $params = []) {
        \Lib\AsyncCurl::get($request_url, $params);
    }

    static public function asyncPost($request_url, $params) {
        \Lib\AsyncCurl::post($request_url, $params);
    }

    static protected function getLoginCookie() {
        $userid      = isset($_COOKIE['userid']) ? $_COOKIE['userid'] : '';
        $sessionid   = isset($_COOKIE['sessionid']) ? $_COOKIE['sessionid'] : '';
        $operationid = isset($_COOKIE['operationid']) ? $_COOKIE['operationid'] : '';
        return 'userid=' . $userid . '; sessionid=' . $sessionid . '; operationid=' . $operationid;
    }

    static protected function getUserCookies() {
        return ['userid' => $_COOKIE['userid'], 'sessionid' => $_COOKIE['sessionid']];
    }

    /**
     * 接口调用
     * @param  string                   $request_url 接口请求地址
     * @param  array                    $options     CURL请求附加参数
     * @return ConcurrenceCurlManager
     */
    static private function call($request_url, $options = []) {
        if (empty(self::$concurrenceCurl)) {
            self::$concurrenceCurl = ConcurrenceCurl::getInstance();
        }
        return self::$concurrenceCurl->addUrl($request_url, $options);
    }

}