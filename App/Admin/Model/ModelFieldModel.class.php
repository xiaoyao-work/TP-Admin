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

/**
* 模型字段Model
*/
class ModelFieldModel extends Model {
    public function get_fields($tablename) {
        $variable = $this->query("SHOW COLUMNS FROM `%s`", $tablename);
        $fields = array();
        foreach ($variable as $key => $r) {
            $fields[$r['field']] = $r['type'];
        }
        return $fields;
    }

    /**
    * 删除字段
    */
    public function drop_field($tablename,$field) {
        $fields = $this->get_fields($tablename);
        if(array_key_exists($field, $fields)) {
            return $this->db->execute("ALTER TABLE `$tablename` DROP `$field`;");
        } else {
            return false;
        }
    }

}