<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
* 栏目操作类
*/
class CategoryController extends CommonController {
    private $db, $model_db;
    function __construct() {
        parent::__construct();
        $this->db = model("Category");
        $this->model_db = model('Model');
    }

    public function index() {
        $taxonomy_name = I('get.taxonomy_name');
        $post_type = I('get.post_type');
        // 获取分类
        $terms = logic('Category')->getTerms($post_type, $taxonomy_name);
        $this->assign('terms', $terms);
        $this->assign('query_string', http_build_query(array('post_type' => $post_type, 'taxonomy_name' => $taxonomy_name)));
        $this->display();
    }

    public function add() {
        if(IS_POST) {
            $this->checkToken();
            $data = I('post.info');
            $data['post_type'] = I('post.post_type');
            $data['taxonomy'] = I('post.taxonomy_name');
            $data['siteid'] = $this->siteid;
            $data['child'] = 0;

            $query_string = http_build_query(array('post_type' => $data['post_type'], 'taxonomy_name' => $data['taxonomy']));
            if ($data['parentid']) {
                $parentcat = $this->db->where("siteid = %d and id = %d", $this->siteid, $data['parentid'])->find();
                if (!$parentcat) {
                    $this->error('父栏目不存在');
                }
                $data['arrparentid'] = $parentcat['arrparentid'] . "," .$parentcat['id'];
                $data['level'] = $parentcat['level'] + 1;
                $this->db->startTrans();
                if($catid = $this->db->add($data)) {
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
                    $this->success('操作成功！', __MODULE__.'/Category/index?' . $query_string);
                } else {
                    $this->db->rollback();
                    $this->error('操作失败！');
                }
            } else {
                $data['level'] = 1;
                $data['arrparentid'] = "0";
                $this->db->startTrans();
                if ($catid = $this->db->add($data)) {
                    if ($this->db->where("siteid = %d and id = %d",$this->siteid,$catid)->save(array('arrchildid' => $catid)) !== false) {
                        $this->db->commit();
                        $this->success('操作成功！',__MODULE__.'/Category/index?' . $query_string);
                    } else {
                        $this->db->rollback();
                        $this->error('操作失败！');
                    }
                } else {
                    $this->db->rollback();
                    $this->error('操作失败！');
                }
            }
        } else {
            $post_type = I('get.post_type');
            $taxonomy_name = I('get.taxonomy_name');
            // 获取分类
            $terms = logic('Category')->getTerms($post_type, $taxonomy_name);
            if ($terms === false) {
                $this->error(logic('Category')->getErrorMessage());
            }
            $this->assign('terms', $terms);
            $this->assign('post_type',$post_type);
            $this->assign('taxonomy_name',$taxonomy_name);
            $this->assign('query_string', http_build_query(array('post_type' => $post_type, 'taxonomy_name' => $taxonomy_name)));
            $this->display();
        }
    }

    public function edit() {
        if (IS_POST) {
            $this->checkToken();
            $term = $this->db->where("siteid = %d and id = %d",$this->siteid, $_POST['id'])->find();
            $query_string = http_build_query(array('post_type' => $term['post_type'], 'taxonomy_name' => $term['taxonomy']));
            $data = $_POST['info'];

            // 是否修改父栏目
            if ($data['parentid'] == $term['parentid']) {
                if ($this->db->where("siteid = %d and id = %d",$this->siteid, $_POST['id'])->save($data) !== false) {
                    $this->success('操作成功！',__MODULE__.'/Category/index?' . $query_string);
                } else {
                    $this->error('操作失败!');
                }
            } else {
                if ($data['parentid']) {
                    // 更新 'parentid' and 'arrparentid'
                    $parentcat = $this->db->where("siteid = %d and id = %d",$this->siteid, $data['parentid'])->find();
                    if (!$parentcat) {
                        $this->error('父栏目不存在');
                    }

                    $data['arrparentid'] = $parentcat['arrparentid'] . "," .$parentcat['id'];
                    $data['level'] = $parentcat['level'] + 1;

                    $this->db->startTrans();
                    if ($this->db->where("siteid = %d and id = %d",$this->siteid, $_POST['id'])->save($data) !== false) {
                        /* 更新原父栏目 */
                        $origin_parentcat = $this->db->where("siteid = %d and id = %d",$this->siteid,$term['parentid'])->find();
                        if ($origin_parentcat) {
                            $arrchildid = explode(',', $origin_parentcat['arrchildid']);
                            foreach ($arrchildid as $key => $value) {
                                if ($value == $term['id']) {
                                    unset($arrchildid[$key]);
                                    break;
                                }
                            }
                            $arrchildid = join(',',$arrchildid);
                            $origin_parent_data = array('arrchildid' => $arrchildid);
                            if ($arrchildid == $origin_parentcat['id']) {
                                $origin_parent_data['child'] = 0;
                            }
                            // 更新原父栏目
                            if ($this->db->where("siteid = %d and id = %d",$this->siteid, $origin_parentcat['id'])->save($origin_parent_data) === false) {
                                $this->db->rollback();
                                $this->error("更新原父栏目失败!");
                            }
                        }

                        /* 更新现父栏目 */
                        $data_parent = array('child' => 1, 'arrchildid' => $parentcat['arrchildid'] . "," . $term['id']);
                        if ($this->db->where("siteid = %d and id = %d",$this->siteid, $data['parentid'])->save($data_parent) !== false) {
                            $this->db->commit();
                            $this->success('操作成功！',__MODULE__.'/Category/index?'. $query_string);
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
                    if ($this->db->where("siteid = %d and id = %d", $this->siteid, $_POST['id'])->save($data) !== false) {
                        $origin_parentcat = $this->db->where("siteid = %d and id = %d",$this->siteid,$term['parentid'])->find();
                        $arrchildid = explode(',', $origin_parentcat['arrchildid']);
                        foreach ($arrchildid as $key => $value) {
                            if ($value == $term['id']) {
                                unset($arrchildid[$key]);
                                break;
                            }
                        }
                        $arrchildid = join(',',$arrchildid);
                        $origin_parent_data = array('arrchildid' => $arrchildid);
                        if ($arrchildid == $origin_parentcat['id']) {
                            $origin_parent_data['child'] = 0;
                        }

                        if ($this->db->where("siteid = %d and id = %d", $this->siteid, $origin_parentcat['id'])->save($origin_parent_data) !== false) {
                            $this->db->commit();
                            $this->success('操作成功！',__MODULE__.'/Category/index?'. $query_string);
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
            $term_id = I('get.id');
            if (empty($term_id)) {
                $this->error('参数缺失！');
            }

            $term = $this->db->where("siteid = %d and id = %d", $this->siteid, $term_id)->find();
            if (empty($term)) {
                $this->error('栏目不存在！');
            }

            // 获取分类
            $terms = logic('Category')->getTerms($term['post_type'], $term['taxonomy']);
            if ($terms === false) {
                $this->error(logic('Category')->getErrorMessage());
            }

            $this->assign('term',$term);
            $this->assign('terms', $terms);
            $this->assign('query_string', http_build_query(array('post_type' => $term['post_type'], 'taxonomy_name' => $term['taxonomy'])));
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