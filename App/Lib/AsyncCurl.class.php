<?php
namespace Lib;

/**
* 异步CURL处理类
* @author 逍遥·李志亮 <xiaoyao.working@gmail.com>
*/
class AsyncCurl {

    public static function get($url, $params=array()) {
        $request_query = self::parseParams($params);
        Log::info('exec shell: curl -v ' . $request_query . ' ' . $url . ' > null &');
        exec('curl -v ' . $request_query . ' ' . $url . ' > null &');
    }

    public static function post($url, $params) {
        $request_query = self::parseParams($params);
        Log::info('exec shell: curl -v -X POST ' . $request_query . ' ' . $url . ' > null &');
        exec('curl -v -X POST ' . $request_query . ' ' . $url . ' > null &');
    }

    /**
     * 解析linux CURL请求参数
     * 目前支持 cookie; post data; header;
     * 格式 array('cookie' => array('key' => 'value'), 'data' => array('key' => 'value'), 'header' => array('value1', 'value2') ),
     * @param  string $type  请求类型
     * @param  array  $params 参数
     * @return          [description]
     */
    protected static function parseParams($params) {
        if (empty($params)) {
            return '';
        }
        $request_query = '';
        foreach ($params as $key => $value) {
            $request_query .= call_user_func_array(array(self, 'parse'. ucfirst(strtolower($key))), array($value));
        }
        return $request_query;
    }

    /**
     * 解析cookie
     * @param  array $cookies cookie数组
     * @return string curl cookie
     */
    protected static function parseCookie($cookies) {
        if (empty($cookies)) {
            return '';
        }
        $cookie_string = '--cookie "';
        foreach ($cookies as $key => $value) {
            $cookie_string .= ($key . '=' . $value . '; ');
        }
        $cookie_string .= '" ';
        return $cookie_string;
    }

    /**
     * 解析POST data
     * @param  array $cookies cookie数组
     * @return string curl cookie
     */
    protected static function parseData($data) {
        if (empty($data)) {
            return '';
        }
        $data_string = '';
        foreach ($data as $key => $value) {
            $data_string .= '--data "'. $key . '=' . $value .'" ';
        }
        return $data_string;
    }

    /**
     * 解析header
     * @param  array $cookies cookie数组
     * @return string curl cookie
     */
    protected static function parseHeader($headers) {
        if (empty($headers)) {
            return '';
        }
        $header_string = '';
        foreach ($headers as $key => $value) {
            $header_string .= '--header "'. $key . ': ' . $value .'" ';
        }
        return $header_string;
    }
}