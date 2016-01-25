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
* 栏目操作类
*/
class CategoryController extends CommonController {
    private $db, $model_db;
    function __construct() {
        parent::__construct();
        $this->db = D("Category");
        $this->model_db = D('Model');
    }

    public function index() {
        load('extend');
        $cats = list_to_tree($this->db->cat_list(),'id','parentid');
        $list = array();
        tree_to_array($cats,$list);
        $this->assign('cats',$list);
        $this->display();
    }

    public function add() {
        if(IS_POST) {
            $this->checkToken();
            $data = $_POST['info'];
            $data['letter'] = join('', gbk_to_pinyin($data['catname']));
            $data['setting'] = var_export($_POST['setting'],true);
            $data['siteid'] = $this->siteid;
            $model = $this->model_db->find(intval($data['modelid']));
            $data['module'] = $model['name'];
            $data['child'] = 0;
            if ($data['parentid']) {
                $parentcat = $this->db->where("siteid = %d and id = %d",$this->siteid,$data['parentid'])->find();
                if (!$parentcat) {
                    $this->error('父栏目不存在');
                    exit(0);
                }
                $data['arrparentid'] = $parentcat['arrparentid'] . "," .$parentcat['id'];
                $data['parentdir'] = $parentcat['parentdir'] . $parentcat['catdir'] . "/";
                $data['level'] = $parentcat['level'] + 1;
                if($catid = $this->db->add($data)){
                    if ($this->db->where("siteid = %d and id = %d",$this->siteid,$catid)->save(array('arrchildid' => $catid)) === false) {
                        $this->db->rollback();
                        $this->error('操作失败！');
                    }
                    $data_parent = array();
                    $data_parent['child'] = 1;
                    $data_parent['arrchildid'] = $parentcat['arrchildid'] . "," . $catid;
                    if ($this->db->where("siteid = %d and id = %d",$this->siteid,$data['parentid'])->save($data_parent) === false) {
                        $this->db->rollback();
                        $this->error('操作失败！');
                    };
                    $this->db->commit();
                    $this->success('操作成功！',__MODULE__.'/Category/index');
                } else {
                    $this->db->rollback();
                    $this->error('操作失败！');
                }
            } else {
                $data['level'] = 1;
                $data['arrparentid'] = "0";
                if ($catid = $this->db->add($data)) {
                    $this->db->where("siteid = %d and id = %d",$this->siteid,$catid)->save(array('arrchildid' => $catid));
                    $this->success('操作成功！',__MODULE__.'/Category/index');
                } else {
                    $this->error('操作失败！');
                }
            }
        } else {
            $cat_id = (isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0);
            if ($cat_id) {
                $category = $this->db->where(array('id' => $cat_id))->find();
                $this->assign('category',$category);
            }
            $cats = list_to_tree($this->db->cat_list(),'id','parentid');
            $list = array();
            tree_to_array($cats,$list);
            $this->assign('cat_id',$cat_id);
            $this->assign('cats',$list);
            $this->assign("model_list", $this->model_db->where(array('siteid'=>$this->siteid, 'typeid' => 0))->select());
            $this->display();
        }
    }

    public function edit() {
        if (IS_POST) {
            $this->checkToken();
            $cat = $this->db->where("siteid = %d and id = %d",$this->siteid,$_POST['id'])->find();
            $data = $_POST['info'];
            $data['letter'] = join('',gbk_to_pinyin($data['catname']));
            $model = $this->model_db->find(intval($data['modelid']));
            $data['module'] = $model['name'];
            $data['setting'] = var_export($_POST['setting'],true);
            // 是否修改父栏目
            if ($data['parentid'] == $cat['parentid']) {
                // echo "没有修改父栏目";
                if ($this->db->where("siteid = %d and id = %d",$this->siteid,$_POST['id'])->save($data) !== false) {
                    // echo $this->db->getLastSql();
                    $this->success('操作成功！',__MODULE__.'/Category/index');
                } else {
                    $this->error('操作失败!');
                }
            } else {
                if ($data['parentid']) {
                    // 更新 'parentid' and 'arrparentid'
                    $parentcat = $this->db->where("siteid = %d and id = %d",$this->siteid,$data['parentid'])->find();
                    if (!$parentcat) {
                        $this->error('父栏目不存在');
                        exit(0);
                    }
                    $data['arrparentid'] = $parentcat['arrparentid'] . "," .$parentcat['id'];
                    $data['parentdir'] = $parentcat['parentdir'] . $parentcat['catdir'] . "/";
                    $data['level'] = $parentcat['level'] + 1;
                    $this->db->startTrans();
                    if ($this->db->where("siteid = %d and id = %d",$this->siteid,$_POST['id'])->save($data) !== false) {
                        /* 更新原父栏目 */
                        $origin_parentcat = $this->db->where("siteid = %d and id = %d",$this->siteid,$cat['parentid'])->find();
                        if ($origin_parentcat) {
                            $arrchildid = explode(',', $origin_parentcat['arrchildid']);
                            foreach ($arrchildid as $key => $value) {
                                if ($value == $cat['id']) {
                                    unset($arrchildid[$key]);
                                    break;
                                }
                            }
                            $arrchildid = join(',',$arrchildid);
                            $origin_parent_data = array('arrchildid' => $arrchildid);
                            if ($arrchildid == $origin_parentcat['id']) {
                                $origin_parent_data['child'] = 0;
                            }
                            if ($this->db->where("siteid = %d and id = %d",$this->siteid,$origin_parentcat['id'])->save($origin_parent_data) === false) {
                                $this->db->rollback();
                                $this->error("更新原父栏目失败!");
                            };
                        }
                        /* 更新现父栏目 */
                        $data_parent = array('child' => 1, 'arrchildid' => $parentcat['arrchildid'] . "," . $cat['id']);
                        if ($this->db->where("siteid = %d and id = %d",$this->siteid,$data['parentid'])->save($data_parent) !== false) {
                            // echo '更新现父栏目成功';
                            $this->db->commit();
                            $this->success('操作成功！',__MODULE__.'/Category/index');
                        } else {
                            $this->db->rollback();
                            $this->error("更新现父栏目失败!");
                        }
                    } else {
                        $this->db->rollback();
                        $this->error("栏目更新失败!");
                    }
                } else {
                    $data['level'] = 1;
                    $data['arrparentid'] = "0";
                    $this->db->startTrans();
                    if ($this->db->where("siteid = %d and id = %d",$this->siteid,$_POST['id'])->save($data) !== false) {
                        $origin_parentcat = $this->db->where("siteid = %d and id = %d",$this->siteid,$cat['parentid'])->find();
                        $arrchildid = explode(',', $origin_parentcat['arrchildid']);
                        foreach ($arrchildid as $key => $value) {
                            if ($value == $cat['id']) {
                                unset($arrchildid[$key]);
                                break;
                            }
                        }
                        $arrchildid = join(',',$arrchildid);
                        $origin_parent_data = array('arrchildid' => $arrchildid);
                        if ($arrchildid == $origin_parentcat['id']) {
                            $origin_parent_data['child'] = 0;
                        }
                        if ($this->db->where("siteid = %d and id = %d",$this->siteid,$origin_parentcat['id'])->save($origin_parent_data) !== false) {
                            $this->db->commit();
                            $this->success('操作成功！',__MODULE__.'/Category/index');
                        } else {
                            $this->db->rollback();
                            $this->error("更新原父栏目失败!");
                        }
                    } else {
                        $this->db->rollback();
                        $this->error("栏目更新失败!");
                    }
                }
            }
        } else {
            $cats = list_to_tree($this->db->cat_list(),'id','parentid');
            $list = array();
            tree_to_array($cats,$list);
            $cat = $this->db->where("siteid = %d and id = %d",$this->siteid,$_GET['id'])->find();
            $model_list = $this->model_db->where(array('siteid'=>$this->siteid, 'typeid' => 0))->select();
            $cat['setting'] = string2array($cat['setting']);
            $this->assign('cat',$cat);
            $this->assign('cat_id',$cat_id);
            $this->assign('cats',$list);
            $this->assign("model_list", $model_list);
            $this->display();
        }
    }

    public function del() {
        if ($this->db->where("parentid = %d",$_GET['id'])->count()) {
            $this->error('请先删除子栏目！');
        } elseif (M('News')->where("catid = %d ", $_GET['id'])->count()) {
            $this->error('请先删除该栏目下的文章！');
        }
        if ($this->db->where("id = %d", $_GET['id'])->delete() !== false) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }
    }

    public function listorder() {
        if (isset($_POST['listorder']) && is_array($_POST['listorder'])) {
            $listorder = $_POST['listorder'];
            foreach ($listorder as $k => $v) {
                $this->db->where(array('id'=>$k))->save(array('listorder'=>$v));
            }
        }
        $this->success('排序成功');
    }

    /**
    * 快速进入搜索
    */
    public function public_ajax_search() {
        if($_GET['catname']) {
            if(preg_match('/([a-z]+)/i',$_GET['catname'])) {
                $field = 'letter';
                $catname = strtolower(trim($_GET['catname']));
            } else {
                $field = 'catname';
                $catname = trim($_GET['catname']);
            }
            $result = $this->db->where("{$field} LIKE('%{$catname}%') AND siteid={$this->siteid} AND child=0")->field('id,type,catname,letter')->limit(10)->select();
            echo json_encode($result);
        }
    }

    /**
    * ajax检查栏目是否存在
    */
    public function public_check_catdir() {
        if (!$_GET['catdir']) exit("0");
        $catdir = $_GET['catdir'];
        if (isset($_GET['catid'])) {
            $cat = $this->db->where("catdir = '%s' and id != %d",$catdir,$_GET['catid'])->find();
        } else {
            $cat = $this->db->where("catdir = '%s'",$catdir)->find();
        }
        if($cat) {
            exit("0");
        } else {
            exit("1");
        }
    }
}