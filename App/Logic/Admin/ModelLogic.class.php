<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic\Admin;
use Lib\Log;
use Logic\BaseLogic;

/**
 * 模型Logic
 */
class ModelLogic extends BaseLogic {
    // 内容菜单ID
    private $menuContentId = 245;

    public function execModelCreateSql($tablename, $modelid, $siteid, $model_type) {
        if (empty($tablename)) {
            return false;
        }
        $tablepre  = C('DB_PREFIX');
        $tablename = strtolower($tablename);
        switch ($model_type) {
            case 1:
                $model_sql = file_get_contents(MODEL_PATH . 'model_parent.sql');
                break;
            case 2:
                $model_sql = file_get_contents(MODEL_PATH . 'model_child.sql');
                break;
            default:
                $model_sql = file_get_contents(MODEL_PATH . 'model_common.sql');
                break;
        }
        $model_sql = str_replace('$basic_table', $tablepre . $tablename, $model_sql);
        $model_sql = str_replace('$table_data', $tablepre . $tablename . '_data', $model_sql);
        $model_sql = str_replace('$table_model_field', $tablepre . 'model_field', $model_sql);
        $model_sql = str_replace('$modelid', $modelid, $model_sql);
        $model_sql = str_replace('$siteid', $siteid, $model_sql);
        return model('Model', 'Admin')->execModelCreateSql($model_sql);
    }

    public function deleteModel($model_data) {
        $model       = model('model', 'Admin');
        $model_field = model('ModelField', 'Admin');
        $model->startTrans();
        // 模型共享
        // $model->where(array('siteid' => get_siteid(),'id' => $model_data['id']))->delete() !== false && $model_field->where(array('modelid' => $model_data['id'],'siteid'=>get_siteid()))->delete() !== false && $this->deleteModelMenu($model_data['tablename']) !== false && $model->drop_table($model_data['tablename']) !== false

        if ($model->where(['id' => $model_data['id']])->delete() !== false &&
            $model_field->where(['modelid' => $model_data['id']])->delete() !== false &&
            $this->deleteModelMenu($model_data['tablename']) !== false &&
            $model->drop_table($model_data['tablename']) !== false
        ) {
            $model->commit();
            return true;
        } else {
            $model->rollback();
            return false;
        }
    }

    public function addModel($data) {
        $model = model('model', 'Admin');
        $model->startTrans();
        $modelid = $model->add($data);
        if ($modelid !== false) {
            $data['id']            = $modelid;
            $add_model_menu_status = true;
            if ($data['type'] != 1) {
                $add_model_menu_status = $this->addModelMenu($data);
            }
            if ($add_model_menu_status && $this->execModelCreateSql($data['tablename'], $modelid, $data['siteid'], $data['type'])) {
                $model->commit();
                return true;
            } else {
                $model->rollback();
                return false;
            }
        } else {
            $model->rollback();
            return false;
        }
    }

    public function deleteModelMenu($post_type) {
        if (empty($post_type)) {
            return false;
        }
        return model('Menu', 'Admin')->where(['post_type' => $post_type])->delete();
    }

    /**
     * 模型创建自动添加菜单
     */
    public function addModelMenu($model) {
        $second_menu_data = [
            'module'    => 'Post',
            'action'    => 'index',
            'title'     => $model['name'] . '管理',
            'status'    => 1,
            'post_type' => $model['tablename'],
            'pid'       => $this->menuContentId,
            'sort'      => 0,
        ];

        $second_menu_id = model('Menu', 'Admin')->add($second_menu_data);
        if ($second_menu_id === false) {
            return false;
        }

        $third_menu_data = [
            'module' => 'Post',
            'action' => 'index',
            'title'  => $model['name'],
            'status' => 1,
            'pid'    => $second_menu_id,
            'sort'   => 0,
            'params' => 'moduleid=' . $model['id'],
        ];
        return model('Menu', 'Admin')->add($third_menu_data);
    }
}