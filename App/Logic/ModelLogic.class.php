<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------
namespace Logic;
use Lib\Log;

/**
 * 模型Logic
 */
class ModelLogic extends BaseLogic {

    public function execModelCreateSql($tablename, $modelid) {
        if (empty($tablename)) {
            return false;
        }
        $tablepre = C('DB_PREFIX');
        $tablename = strtolower($tablename);
        $model_sql = file_get_contents(MODEL_PATH.'model_common.sql');
        $model_sql = str_replace('$basic_table', $tablepre.$tablename, $model_sql);
        $model_sql = str_replace('$table_data', $tablepre.$tablename.'_data', $model_sql);
        $model_sql = str_replace('$table_model_field', $tablepre.'model_field', $model_sql);
        $model_sql = str_replace('$modelid', $modelid, $model_sql);
        $model_sql = str_replace('$siteid',$this->siteid, $model_sql);
        return model('Model')->execModelCreateSql($model_sql) === false;
    }

    public function dropModel($tablename, $modelid) {
    }
}