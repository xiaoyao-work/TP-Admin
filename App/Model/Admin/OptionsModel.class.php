<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------
namespace Model\Admin;

use Model\BaseModel;

/**
 * 系统设置
 */
class OptionsModel extends BaseModel {

    public function getOptions($keys) {
        if (empty($keys)) {
            return [];
        }
        if (!is_array($keys)) {
            return $this->where(['key' => $keys])->find();
        } else {
            return $this->where(['key' => ['in', $keys]])->select();
        }

    }

    /**
     * 设置options表内容
     * @param array $options  表中 key和val
     * @param bool  $autoload 表中autoload
     */
    public function setOptions($options, $autoload = false) {
        if (empty($options) || !is_array($options)) {
            return false;
        }
        foreach ($options as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            if ($this->where(['key' => $key])->count()) {
                $this->where(['key' => $key])->save(['value' => $value, 'autoload' => (int) $autoload]);
            } else {
                $this->add(['key' => $key, 'value' => $value, 'autoload' => (int) $autoload]);
            }
        }
        return true;
    }
}