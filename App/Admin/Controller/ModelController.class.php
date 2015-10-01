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
define('MODEL_PATH', APP_PATH.'Admin'.DIRECTORY_SEPARATOR.'Common'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR);
class ModelController extends CommonController {
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
    protected $modelTypes = array(
        self::NEWS_MODEL => '内容模型',
        // self::HOUSE_MODEL => '房产模型',
        self::COMMON_MODEL => '通用模型'
        );

    protected $db, $type_db, $field_db;

    function __construct() {
        parent::__construct();
        $this->db = D("Model");
        $this->field_db = D("ModelField");
        // $this->type_db = D("ModelType");
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
                $this->db->sql_execute($model_sql);
                $this->success("添加成功!", $_POST['forward']);
            } else {
                // $this->error("更新失败! 最后执行SQL:".$this->db->getLastSql());
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
        if ($this->db->where(array('siteid' => $this->siteid,'id' => $modelid))->delete() !== false) {
            $model_table = $model['tablename'];
            $this->field_db->where(array('modelid'=>$modelid,'siteid'=>$this->siteid))->delete();
            $this->db->drop_table($model_table);
            if ($model['typeid'] != 2) {
                $this->db->drop_table($model_table.'_data');
            }
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

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