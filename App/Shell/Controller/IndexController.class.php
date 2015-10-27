<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Shell\Controller;
use Think\Controller;

/**
 * 后台首页
*/
class IndexController extends Controller {
    public function v2_0_alpha() {
        M()->execute("ALTER TABLE `" . C('DB_PREFIX') . "model_field` ADD `islist` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `isadd`;");
    }

    public function v2_0_bate() {
        $tables = M()->query("show tables;");
        foreach ($tables as $key => $value) {
            M()->execute("ALTER TABLE `" . $value['tables_in_'.C('DB_NAME')] . "` ENGINE = INNODB");
        }
    }

    public function v2_1_alpha() {
        // 创建分类表
        $data = array('name' => '分类', 'typeid' => 2, 'tablename' => 'taxonomy', 'description' => '模型方式创建分类表');
        $data['siteid'] = get_siteid();
        $model = new \Admin\Model\ModelModel();
        if ($modelid = $model->add($data)) {
            switch ($data['typeid']) {
                case 0:
                    $model_sql = file_get_contents(MODEL_PATH.'model.sql');
                    break;
                case 1:
                    $model_sql = file_get_contents(MODEL_PATH.'model_house.sql');
                    break;
                case 2:
                    $model_sql = file_get_contents(MODEL_PATH.'model_common.sql');
                    break;
                default:
                    $model_sql = file_get_contents(MODEL_PATH.'model_common.sql');
                    break;
            }
            $tablepre = C('DB_PREFIX');
            $tablename = strtolower($data['tablename']);
            $model_sql = str_replace('$basic_table', $tablepre.$tablename, $model_sql);
            $model_sql = str_replace('$table_data',$tablepre.$tablename.'_data', $model_sql);
            $model_sql = str_replace('$table_model_field',$tablepre.'model_field', $model_sql);
            $model_sql = str_replace('$modelid',$modelid,$model_sql);
            $model_sql = str_replace('$siteid',get_siteid(),$model_sql);

            if ($model->sql_execute($model_sql) === false) {
                echo "create table taxonomy fail!\n";
            }
        } else {
            echo "create table taxonomy fail!\n";
        }

        // 创建目录表
        $terms_table_sql = sprintf("CREATE TABLE `%sterms` (
                    `id` mediumint(8) unsigned primary key AUTO_INCREMENT,
                    `name` varchar(50),
                    `description` text,
                    `siteid` smallint(5) NOT NULL,
                    `updatetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `inputtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;", C('DB_PREFIX'));
        if (M()->execute($terms_table_sql) !== false) {
            echo "create table terms success!\n";
        } else {
            echo "create table terms fail!\n";
        }
        // 创建分类目录关联表
        $term_taxonomy_table_sql = sprintf("CREATE TABLE `%sterm_taxonomy` (
                    `id` mediumint(8) unsigned primary key AUTO_INCREMENT,
                    `term_id` mediumint(8),
                    `taxonomy_id` mediumint(8),
                    `parent` mediumint(8),
                    `siteid` smallint(5) NOT NULL,
                    KEY taxonomy_term (taxonomy_id, term_id),
                    KEY term (term_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;", C('DB_PREFIX'));
        if (M()->execute($term_taxonomy_table_sql) !== false) {
            echo "create table term_taxonomy success!\n";
        } else {
            echo "create table term_taxonomy fail!\n";
        }

        // 创建目录文章关联表
        $term_contents_table_sql = sprintf("CREATE TABLE `%sterm_contents` (
                    `term_taxonomy_id` mediumint(8) unsigned,
                    `content_id` int(11) unsigned,
                    primary key (term_taxonomy_id, content_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;", C('DB_PREFIX'));
        if (M()->execute($term_contents_table_sql) !== false) {
            echo "create table term_contents success!\n";
        } else {
            echo "create table term_contents fail!\n";
        }
    }
}