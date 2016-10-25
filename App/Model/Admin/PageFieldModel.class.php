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
 * 页面字段Model
 */
class PageFieldModel extends BaseModel {
    /**
     * 获取页面附加字段通过page template name
     * @param  int     $template 模板名称
     * @return array
     */
    public function getPageExtraFields($template, $limit = 100) {
        $field_array = [];
        $fields      = $this->where(['siteid' => get_siteid(), 'template' => $template, 'disabled' => 0])->order("listorder asc, fieldid asc")->limit($limit)->select();
        foreach ($fields as $_value) {
            $setting                       = string2array($_value['setting']);
            $_value                        = array_merge($_value, $setting);
            $field_array[$_value['field']] = $_value;
        }
        return $field_array;
    }
}