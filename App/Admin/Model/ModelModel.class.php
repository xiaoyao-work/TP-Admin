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

class ModelModel extends Model {
    public function sql_execute($sql) {
        $sqls = $this->sql_split($sql);
        if(is_array($sqls)) {
            foreach($sqls as $sql) {
                if(trim($sql) != '') {
                    $this->execute($sql);
                }
            }
        } else {
            $this->execute($sqls);
        }
        return true;
    }

    public function sql_split($sql) {
        $db_charset = C('DB_CHARSET') ? C('DB_CHARSET') : 'utf8';
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=".$db_charset,$sql);
        if(C('DB_PREFIX') != "tb_") $sql = str_replace("tb_", C('DB_PREFIX'), $sql);
        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach($queries as $query) {
                $str1 = substr($query, 0, 1);
                if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
            }
            $num++;
        }
        return($ret);
    }

    /**
    * 删除表
    * @param string $tablename 表名
    */
    public function drop_table($tablename) {
        $tablename = C('DB_PREFIX').$tablename;
        return $this->execute("DROP TABLE $tablename;", true);
    }
}