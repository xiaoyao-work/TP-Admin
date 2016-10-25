<?php
/**
 * 内容添加处理
 */
class content_input {
    public $modelid;
    public $fields;

    protected $responseData = ['code' => 0, 'message' => '', 'data' => []];
    // 字段入库处理接口地址
    protected $contentInputUrl;

    public function __construct($data, $type = 1) {
        switch ($type) {
            case 1:
                $this->modelid = $data;
                $this->fields  = $this->getModelFields($data);
                break;
            case 2:
                $this->pageTemplate = $data;
                $this->fields       = $this->getPageFields($data);
                break;
            default:
                $this->modelid = $data;
                $this->fields  = $this->getModelFields($data);
                break;
        }
        $this->contentInputUrl = HHAILUO_API_DOMAIN . 'post/input.html';
    }

    public function getModelFields($modelid) {
        return model('ModelField')->getFieldsByModelID($modelid);
    }

    public function getPageFields($pageTemplate) {
        return model('PageField')->getPageExtraFields($pageTemplate);
    }

    function get($data) {
        $result = $this->fieldValues($data);
        if ($result === false) {
            logic('Update', 'Admin')->cycheck($this->responseData);
        }
        return $result;
    }

    protected function fieldValues($data) {
        $params = [
            'fields'   => $this->fields,
            'data'     => $data,
            'sys_info' => logic('update', 'Admin')->getSysBaseInfo('post_field_request'),
        ];
        $concurrence_curl_obj = \Service\BaseService::post($this->contentInputUrl, http_build_query($params));
        $response             = $concurrence_curl_obj->getResponse();

        $result = false;
        if ($response['code'] == 200) {
            $response_data = json_decode($response['data'], true);
            if ($response_data['code'] === 0) {
                $result = isset($response_data['data']) ? $response_data['data'] : '';
                if ($response['time'] > 0.2) {
                    // 记录慢查询接口
                    \Lib\Log::notice("慢查询接口: HTTP_CODE=" . $response['code'] . "; TOTAL_TIME=" . $response['time']);
                }
                return $result;
            } else {
                // 记录接口返回错误数据
                $this->responseData = $response_data;
                \Lib\Log::warn("CURL REQUEST WARN : HTTP_CODE=" . $response['code'] . '; TOTAL_TIME=' . $response['time'] . '; Data :' . json_encode($response_data, JSON_UNESCAPED_UNICODE));
                return false;
            }
        } else {
            // 记录接口请求错误
            $this->responseData = ['code' => 10500, 'message' => '接口请求失败！', 'data' => $response];
            \Lib\Log::error("CURL REQUEST ERROR : HTTP_CODE=" . $response['code'] . "; TOTAL_TIME=" . $response['time'] . "; Data :" . $response['data']);
            return false;
        }
    }
}
