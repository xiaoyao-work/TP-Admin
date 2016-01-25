<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic;

use Lib\Log;
/**
* Logic 基类
* @author 李志亮 <lizhiliang@kankan.com>
*/
class BaseLogic {
    protected $errorCode = 0;
    protected $errorMessages = array('0' => '');
    protected $errorMessage = '';

    /**
     * 接口返回的错误信息
     * @var null
     */
    protected $serviceErrorInfo = null;

    public function getInterfaceData($epi_curl_manager) {
        $response = $epi_curl_manager->getResponse();
        $temp = false;
        if ($response['code'] == 200) {
            $response_data = json_decode($response['data'], true);
            if ($response_data['rtn'] === 0) {
                $temp = isset($response_data['data']) ? $response_data['data'] : '';
                if ($response['time'] > 0.2) {
                    // 记录慢查询接口
                    Log::notice("CURL REQUEST ERROR : HTTP_CODE=" . $response['code'] . '; TOTAL_TIME=' . $response['time'] . "; EFFECTIVE_URL=" . $response['url'] . '; Data :' . $response['data']);
                }
            } else {
                // 记录接口返回错误数据
                $this->errorCode = $response_data['rtn'];
                $this->serviceErrorInfo = $response_data;
                Log::warn("CURL REQUEST ERROR : HTTP_CODE=" . $response['code'] . '; TOTAL_TIME=' . $response['time'] . "; EFFECTIVE_URL=" . $response['url'] . '; Data :' . $response['data']);
                return false;
            }
        } else {
            // 记录接口请求错误
            Log::error("CURL REQUEST ERROR : HTTP_CODE=" . $response['code'] . '; TOTAL_TIME=' . $response['time'] . "; EFFECTIVE_URL=" . $response['url'] . '; Data :' . $response['data']);
            return false;
        }
        return $temp;
    }

    public function getErrorMessage() {
        return empty($this->errorMessage) ? (isset($errorMessages[$this->errorCode]) ? $errorMessages[$this->errorCode] : '') : $this->errorMessage;
    }

    public function getErrorCode() {
        return $this->errorCode;
    }

    public function getServiceErrorInfo() {
        return empty($this->serviceErrorInfo) ? false : $this->serviceErrorInfo;
    }
}