<?php
namespace Service;

use Lib\EpiCurl;
/**
* 服务接口基类
* @author  李志亮 <lizhiliang@kankan.com>
*/
class BaseService {

    static protected $epiCurl = NULL;
    /**
     * 接口调用
     * @param  string $request_url 接口请求地址
     * @param  array  $options     CURL请求附加参数
     * @return EpiCurlManager
     */
    static public function call($request_url, $options = array()) {
        if (empty(self::$epiCurl)) {
            self::$epiCurl = EpiCurl::getInstance();
        }
        return self::$epiCurl->addUrl($request_url, $options);
    }

    static public function post($request_url, $params=array(), $options=array()) {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $params;
        return self::call($request_url, $options);
    }

    /**
     * 增加星币
     * @param  int    $coins 星币
     * @return mix    增加新币信息
     */
    static public function get($request_url, $options=array()) {
        return self::call($request_url, $options);
    }

    static protected function getLoginCookie() {
        $userid = isset($_COOKIE['userid']) ? $_COOKIE['userid'] : '';
        $sessionid = isset($_COOKIE['sessionid']) ? $_COOKIE['sessionid'] : '';
        return 'userid=' . $userid . '; sessionid=' . $sessionid;
    }

    static protected function getUserCookies() {
        return array('userid' => $_COOKIE['userid'], 'sessionid' => $_COOKIE['sessionid']);
    }
}