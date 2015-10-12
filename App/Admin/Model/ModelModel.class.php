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
    /**
     * 资讯模型
     */
    const NEWS_MODEL = 0;
    /**
     * 房产模型
     */
    const HOUSE_MODEL = 1;
    /**
     * 通用模型
     */
    const COMMON_MODEL = 2;

    /**
     * 模型类别
     */
    public static $modelTypes = array(
        self::NEWS_MODEL => '内容模型',
        // self::HOUSE_MODEL => '房产模型',
        self::COMMON_MODEL => '通用模型'
        );

    public function getModelTypes() {
        return self::$modelTypes;
    }

    public function sql_execute($sql) {
        $sqls = $this->sql_split($sql);
        if(is_array($sqls)) {
            foreach($sqls as $sql) {
                if(trim($sql) != '') {
                    if ($this->execute($sql) === false) {
                        return false;
                    };
                }
            }
        } else {
            if ($this->execute($sqls) === false) {
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