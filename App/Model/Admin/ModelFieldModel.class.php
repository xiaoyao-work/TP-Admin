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
 * 模型字段Model
 */
class ModelFieldModel extends BaseModel {
    /**
     * 获取模型字段通过modelid
     * @param  int     $modelid 模型ID
     * @return array
     */
    public function getFieldsByModelID($modelid, $limit = 100) {
        $field_array = [];
        if (!is_array($modelid)) {
            $modelid = [$modelid];
        }
        // $fields = $this->where(array('siteid' => get_siteid(), 'modelid' => array('in', $modelid), 'disabled'=>0))->order("listorder asc, fieldid asc")->limit($limit)->select();
        $fields = $this->where(['modelid' => ['in', $modelid], 'disabled' => 0])->order("listorder asc, fieldid asc")->limit($limit)->select();
        foreach ($fields as $_value) {
            $setting                       = string2array($_value['setting']);
            $_value                        = array_merge($_value, $setting);
            $field_array[$_value['field']] = $_value;
        }
        return $field_array;
    }

    public function fieldExist($field_name, $modelids) {
        if (!is_array($modelids)) {
            $modelids = [$modelids];
        }
        return $this->where(['field' => $field_name, 'modelid' => ['in', $modelids]])->count();
    }

    /**
     * 获取模型对应表字段，通过tablename
     * @param  string  $tablename 表名
     * @return array
     */
    public function get_fields($tablename) {
        $variable = $this->query("SHOW COLUMNS FROM `%s`", $tablename);
        $fields   = [];
        foreach ($variable as $key => $r) {
            $fields[$r['field']] = $r['type'];
        }
        return $fields;
    }

    /**
     * 删除字段
     */
    public function drop_field($tablename, $field) {
        $fields = $this->get_fields($tablename);
        if (array_key_exists($field, $fields)) {
            return $this->db->execute("ALTER TABLE `$tablename` DROP `$field`;");
        } else {
            return false;
        }
    }

}