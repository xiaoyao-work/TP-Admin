<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
*  系统模型控制
*/
class ModelController extends CommonController {
    protected $db, $fieldDb, $modelTypes;

    function __construct() {
        parent::__construct();
        $this->db = D("Model");
        $this->fieldDb = D("ModelField");
        $this->modelTypes = $this->db->getModelTypes();
    }

    public function index() {
        $models =  $this->db->where("siteid = %d", $this->siteid)->select();
        // $types = $this->type_db->where("siteid = %d", $this->siteid)->select();
        $this->assign("types",$this->modelTypes);
        $this->assign("models",$models);
        $this->display();
    }

    public function add() {
        if (IS_POST) {
            $this->checkToken();
            $data = $_POST['model'];
            $data['siteid'] = (isset($this->siteid) ? $this->siteid : 1);
            if ($modelid = $this->db->add($data)) {
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
                $tablename = strtolower($_POST['model']['tablename']);
                $model_sql = str_replace('$basic_table', $tablepre.$tablename, $model_sql);
                $model_sql = str_replace('$table_data',$tablepre.$tablename.'_data', $model_sql);
                $model_sql = str_replace('$table_model_field',$tablepre.'model_field', $model_sql);
                $model_sql = str_replace('$modelid',$modelid,$model_sql);
                $model_sql = str_replace('$siteid',$this->siteid,$model_sql);
                if ($this->db->sql_execute($model_sql) === false) {
                    $this->error("添加失败! ");
                }
                $this->success("添加成功!", $_POST['forward']);
            } else {
                // $this->error("更新失败! 最后执行SQL:".$this->db->getLastSql());
                $this->db->rollback();
                $this->error("添加失败! ");
            }
        } else {
            // $types = $this->type_db->where("siteid = %d", $this->siteid)->select();
            $this->assign("types",$this->modelTypes);
            $this->display();
        }
    }

    public function edit() {
        if (IS_POST) {
            $this->checkToken();
            if ($this->db->where(array('siteid' => $this->siteid,'id' => $_POST['modelid']))->save($_POST['model']) !==false) {
                $this->success("更新成功!", $_POST['forward']);
            } else {
                // $this->error("更新失败! 最后执行SQL:".$this->db->getLastSql());
                $this->error("更新失败! ");
            }
        } else {
            // $types = $this->type_db->where("siteid = %d", $this->siteid)->select();
            $this->assign("types",$this->modelTypes);
            $model = $this->db->where(array('siteid' => $this->siteid))->find($_GET['modelid']);
            if (empty($model)) {
                $this->error('模型不存在！');
            }
            $this->assign('model',$model);
            $this->display();
        }
    }

    public function delete() {
        $modelid = intval($_GET['modelid']);
        $model = $this->db->find($modelid);
        if (empty($model)) {
            $this->error('模型不存在！');
        }
        $this->db->startTrans();
        if ($this->db->where(array('siteid' => $this->siteid,'id' => $modelid))->delete() !== false) {
            $model_table = $model['tablename'];
            if ($this->fieldDb->where(array('modelid'=>$modelid,'siteid'=>$this->siteid))->delete() === false) {
                $this->db->rollback();
                $this->error('删除失败');
            }
            if ($result = $this->db->drop_table($model_table) === false) {
                $this->db->rollback();
                $this->error('删除失败');
            }
            if ($model['typeid'] != 2) {
                if ($this->db->drop_table($model_table.'_data') === false) {
                    $this->db->rollback();
                    $this->error('删除失败');
                }
            }
            $this->db->commit();
            $this->success('删除成功');
        } else {
            $this->db->rollback();
            $this->error('删除失败');
        }
    }

    /**
     * 模型名称重复异步检测
     */
    public function public_check_name() {
        if (!isset($_GET['field']) || !($_GET[$_GET['clientid']])) exit("0");
        $where = array($_GET['field'] => $_GET[$_GET['clientid']]);
        if (isset($_GET['modelid'])) {
            $where['id'] = array('NEQ',$_GET['modelid']);
        }
        if($this->db->where($where)->find()) {
            exit("0");
        } else {
            exit("1");
        }
    }
}