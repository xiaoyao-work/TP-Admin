<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Model;
use Think\Model;

class ModelModel extends BaseModel {

    public function execModelCreateSql($sql) {
        $sqls = $this->sql_split($sql);
        if(is_array($sqls)) {
            foreach($sqls as $sql) {
                if(trim($sql) != '') {
                    if ($this->execute($sql) === false) {
                        \Lib\Log::error('sql 执行失败: ' . $sql);
                        return false;
                    };
                }
            }
        } else {
            if ($this->execute($sqls) === false) {
                \Lib\Log::error('sqls 执行失败: ' . $sqls);
                return false;
            };
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
        return $this->execute("DROP TABLE `$tablename`;");
    }
}