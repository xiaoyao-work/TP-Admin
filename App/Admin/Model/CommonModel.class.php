<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
use Think\Page as Page;

class CommonModel extends Model {
    /**
     * 获取内容
     * @param array $where 查询条件
     * @param string $order 排序条件
     * @param int $limit
     * @param mix $field
     * @param array $page_params 分页格式 具体格式参照Page类
    */
    public function getList($where=array(), $order = "id desc", $limit=20, $field=true, $page_params = array()) {
        $where['siteid'] = get_siteid();
        $page = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $list = $this->field($field)->where($where)->order($order)->page($page . ', ' . $limit)->select();
        $count = $this->where($where)->count();
        $Page = new Page($count,$limit);
        if (!empty($page_params) && is_array($page_params)) {
            foreach ($page_params as $key => $param) {
                $Page->setConfig($key, $param);
            }
        }
        $show = $Page->show();
        return array("data" => $list, "page" => $show);
    }

    /**
     * 获取字段信息 (不支持动态设置表)
     * @return array
    */
    public function getFields() {
        return $this->fields;
    }

    protected function  _before_insert(&$data,$options) {
        $fields = $this->fields;
        if (in_array('inputtime', $fields) && (!isset($data['inputtime']) || empty($data['inputtime']))) {
            $data['inputtime'] = strpos($fields['_type']['inputtime'], 'int') === false ? date("Y-m-d H:i:s") : time();
        }
        if (in_array('updatetime', $fields)  && (!isset($data['updatetime']) || empty($data['updatetime']))) {
            $data['updatetime'] = strpos($fields['_type']['updatetime'], 'int') === false ? date("Y-m-d H:i:s") : time();
        }
    }

    protected function  _before_update(&$data,$options) {
        $fields = $this->fields;
        if (in_array('updatetime', $fields)  && (!isset($data['updatetime']) || empty($data['updatetime']))) {
            $data['updatetime'] = strpos($fields['_type']['updatetime'], 'int') === false ? date("Y-m-d H:i:s") : time();
        }
    }
}