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
*  推荐位
*/
class PositionController extends CommonController {
    protected $db, $modelModel, $dataModel, $modelTypes;
    function __construct() {
        parent::__construct();
        $this->db = D('Position');
        $this->dataModel = D('PositionData');
        $this->modelModel = D('Model');
        $this->modelTypes = $this->modelModel->getModelTypes();
    }

    public function index() {
        $typeid = isset($_GET['typeid']) ? intval($_GET['typeid']) : NULL;
        $positions = ($typeid === NULL) ? $this->db->where("siteid = %d", $this->siteid)->order('listorder desc, id desc')->select() : $this->db->where("siteid = %d and typeid = %d", $this->siteid, $typeid)->order('listorder desc, id desc')->select();
        // 此处需要判断站点权限
        $this->assign("types",$this->modelTypes);
        $this->assign('positions', $positions);
        $this->display();
    }

    public function add() {
        if (IS_POST) {
            $this->checkToken();
            if(!is_array($_POST['info']) || empty($_POST['info']['name'])){
                $this->error("操作失败！");
            }
            $_POST['info']['siteid'] = $this->siteid;
            if ($_POST['info']['typeid'] != 0) {
                $_POST['info']['modelid'] = $_POST['info']['catid'] = -1;
            }
            if ($this->db->add($_POST['info']) !== false) {
                $this->success('添加成功！', "index");
            } else {
                $this->error('添加失败！');
            }
        } else {
            $this->assign("types",$this->modelTypes);
            // $models = $this->modelModel->where(array('siteid' => $this->siteid))->select();
            // $this->assign('models', $models);
            $this->display();
        }
    }

    public function edit() {
        if (IS_POST) {
            $this->checkToken();
            if(!is_array($_POST['info']) || empty($_POST['info']['name'])){
                $this->error("操作失败！");
            }
            if ($_POST['info']['typeid'] != 0) {
                $_POST['info']['modelid'] = $_POST['info']['catid'] = -1;
            }
            // 改变类型删除原有数据
            $pos = $this->db->find($_POST['posid']);

            if ($this->db->where(array('id' => intval($_POST['posid'])))->save($_POST['info']) !== false) {
                $this->success('更新成功！', "index");
            } else {
                $this->error('更新失败！');
            }
        } else {
            $position = $this->db->find(intval($_GET['posid']));
            $models = $this->modelModel->where(array('siteid' => $this->siteid, 'typeid' => $position['typeid']))->select();
            foreach ($models as $key => $model) {
                $model_array[$model['id']] = $model['name'];
            }
            $this->assign("types",$this->modelTypes);
            $modelstr = \Org\Util\Form::select($model_array,$position['modelid'],'name="info[modelid]" onchange="category_load(this);"', '请选择模型');
            $this->assign('modelstr', $modelstr);
            $this->assign('position', $position);
            $this->display();
        }
    }

    public function delete() {
        if ($this->db->where("id = %d", $_GET['posid'])->delete() !== false) {
            $this->dataModel->where( array( 'posid' => $_GET['posid'] ) )->delete();
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }
    }

    public function listorder() {
        if (isset($_POST['listorders']) && is_array($_POST['listorders'])) {
            $sort = $_POST['listorders'];
            foreach ($sort as $k => $v) {
                $this->db->where(array('id'=>$k))->save(array('listorder'=>$v));
            }
        }
        $this->success('排序成功');
    }

    /**
    * 推荐位文章列表
    */
    public function public_item() {
        $posid = intval($_GET['posid']);
        // $models = $this->modelModel->where('siteid = %d', $this->siteid)->select();
        // $models = array_key_translate($models);
        $pos_data = $this->dataModel->where(array('posid' => $posid))->select();
        // $pos_data = $this->db->get_position_data($posid);
        // var_dump($pos_data);
        $infos = array();
        foreach ($pos_data as $_k => $_v) {
            $r = string2array($_v['data']);
            $r['catname'] = $_v['catname'];
            $r['modelid'] = $_v['modelid'];
            $r['posid'] = $_v['posid'];
            $r['id'] = $_v['id'];
            $r['listorder'] = $_v['listorder'];
            $r['catid'] = $_v['catid'];
            $key = $r['id'].'-'.$r['catid'].'-'.$r['modelid'];
            $infos[$key] = $r;
        }
        $this->assign('posid', $posid);
        $this->assign('infos', $infos);
        $this->display();
    }

    /**
    * 推荐位文章排序
    */
    public function public_item_listorder() {
        if (isset($_POST['posid']) && isset($_POST['listorders']) && is_array($_POST['listorders'])) {
            $listorders = $_POST['listorders'];
            foreach ($listorders as $_k => $v) {
                $pos = array();
                $pos = explode('-', $_k);
                $this->db->where(array('id'=>$pos[0],'catid'=>$pos[1],'posid'=>$_POST['posid']))->save(array('listorder'=>$v));
            }
        }
        $this->success('排序成功');
    }

    public function public_item_remove() {
        if(IS_POST) {
            $items = count($_POST['items']) > 0  ? $_POST['items'] : $this->error('请选择要移出的文章');
            if(is_array($items)) {
                $sql = array();
                foreach ($items as $item) {
                    $_v = explode('-', $item);
                    $sql['id'] = $_v[0];
                    $sql['catid']= $_v[1];
                    $sql['modelid']= $_v[2];
                    $sql['posid'] = intval($_POST['posid']);
                    $sql['siteid'] = $this->siteid;
                    $this->dataModel->where($sql)->delete();
                    $this->content_pos($sql['id'],$_v[1],$sql['modelid']);
                }
            }
            $this->success('操作成功！');
        } else {
            $item = $_GET['item'];
            $_v = explode('-', $item);
            $sql = array('id' => $_v[0], 'catid' => $_v[1], 'modelid' => $_v[2]);
            $sql['posid'] = intval($_GET['posid']);
            $sql['siteid'] = $this->siteid;
            $this->dataModel->where($sql)->delete();
            $this->content_pos($sql['id'],$_v[1],$sql['modelid']);
            $this->success('操作成功！');
        }
    }

    /**
    * 推荐位添加栏目加载
    */
    public function public_category_load() {
        $modelid = intval($_GET['modelid']);
        $category = \Org\Util\Form::select_category('','','name="info[catid]"','≡ 所有栏目 ≡',$modelid);
        echo $category;
    }

    /**
    * 推荐位添加模型加载
    */
    public function public_model_load() {
        $typeid = intval($_GET['typeid']);
        $models = D('Model')->where(array('typeid' => $typeid, 'siteid' => $this->siteid))->select();
        $modelstr = \Org\Util\Form::select(array_translate($models),'','name="info[modelid]" onchange="category_load(this);"','所有模型');
        echo $modelstr;
    }

    private function content_pos($id, $catid, $modelid) {
        // $model = $this->modelModel->find($modelid);
        $contentModel = D('Content');
        $contentModel->set_model($modelid);
        $posids = $this->dataModel->where(array('id'=>$id))->find() ? 1 : 0;
        return $contentModel->where("id = %d", $id)->save(array('posids'=>$posids));
    }

}
