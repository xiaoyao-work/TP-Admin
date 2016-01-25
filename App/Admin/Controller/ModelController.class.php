<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
*  系统模型控制
*/
class ModelController extends CommonController {
    protected $db, $fieldDb;

    function __construct() {
        parent::__construct();
        $this->db = model("Model");
        $this->fieldDb = model("ModelField");
    }

    public function index() {
        $models =  $this->db->where("siteid = %d", $this->siteid)->select();
        $this->assign("models",$models);
        $this->display();
    }

    public function add() {
        if (IS_POST) {
            $this->checkToken();
            $data = $_POST['model'];
            $data['siteid'] = (isset($this->siteid) ? $this->siteid : 1);

            if (logic('model')->addModel($data)) {
                $this->success('添加成功！');
            } else {
                $this->error("添加失败! ");
            }
        } else {
            $this->display();
        }
    }

    public function edit() {
        if (IS_POST) {
            $this->checkToken();
            if ($this->db->where(array('siteid' => $this->siteid, 'id' => $_POST['modelid']))->save($_POST['model']) !==false) {
                $this->success("更新成功!", $_POST['forward']);
            } else {
                // $this->error("更新失败! 最后执行SQL:".$this->db->getLastSql());
                $this->error("更新失败! ");
            }
        } else {
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
        if (logic('Model')->deleteModel($model)) {
            $this->success('删除成功');
        } else {
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