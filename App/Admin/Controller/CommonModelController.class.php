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
* 公共模型控制器
*/
define('MODEL_PATH', APP_PATH.'Admin'.DIRECTORY_SEPARATOR.'Common'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR);
class CommonModelController extends CommonController {

    protected $db, $model_db;
    function __construct() {
        parent::__construct();
        $this->db = D("CommonModel");
        $this->model_db = D("Model");
    }

    public function index() {
        if (isset($_GET['model_id']) && $_GET['model_id']) {
            $this->db->set_model($_GET['model_id']);
            $search = array();
            if (isset($_GET['search'])) {
                if($_GET['start_time'] && !is_numeric($_GET['start_time'])) {
                    $search['inputtime'] = array('gt',strtotime($_GET['start_time']));
                }
                if($_GET['end_time'] && !is_numeric($_GET['end_time'])) {
                    $search['inputtime'] = array('lt',strtotime($_GET['end_time']));
                }
                if ($_GET['keyword']) {
                    switch (intval($_GET['searchtype'])) {
                        case 0:
                        $search['title'] = array('like', "%".safe_replace($_GET['keyword'])."%");
                        break;
                        case 1:
                        $search['description'] = array('like', "%".safe_replace($_GET['keyword'])."%");
                        break;
                        case 2:
                        $search['username'] = safe_replace($_GET['keyword']);
                        case 3:
                        $search['id'] = intval($_GET['keyword']);
                        break;
                        default:
                        break;
                    }
                }
            }
            $data = $this->db->content_list($search, "listorder desc, id desc");
            $this->assign('model_id', $_GET['model_id']);
            $this->assign('contents',$data['data']);
            $this->assign('pages',$data['page']);
            $this->display();
        } else {
            $this->error("请指定模型ID!");
        }
    }

    public function add() {
        if (IS_POST) {
            $hash[C('TOKEN_NAME')] = $_POST[C('TOKEN_NAME')];
            $this->db->set_model($_POST['modelid']);
            if ($id = $this->db->add_content()) {
                if ($_POST['dosubmit_continue']) {
                    $this->success('添加成功，ID='.$id);
                } else {
                    $returnjs = 'function set_time() {$("#secondid").html(1);}setTimeout("set_time()", 500);setTimeout("window.close()", 1200);';
                    $this->assign('closeWin', true);
                    $this->assign('returnjs', $returnjs);
                    $this->success('添加成功，<span id="secondid">2</span>秒后关闭！');
                }
            } else {
                $this->error('添加失败！');
            }
        } else {
            $catid = intval($_GET['catid']);
            $category = $this->category_db->where("siteid = %d and id = %d", $this->siteid, $catid)->order('listorder desc, id asc')->find();
            $categorys = $this->category_db->where('siteid = %d',$this->siteid)->select();
            $this->categorys = array();
            if (!empty($categorys)) {
                foreach ($categorys as $key => $r) {
                    $this->categorys[$r['id']] = $r;
                }
            }
            $modelid = intval($category['modelid']);
            require MODEL_PATH.'content_form.class.php';
            $content_form = new \content_form($modelid, $catid, $this->categorys);
            $forminfos = $content_form->get();
            $this->assign('formValidator', $content_form->formValidator);
            $this->assign('forminfos', $forminfos);
            $this->assign('category', $category);
            $this->display();
        }
    }

    public function edit() {
        if (IS_POST) {
            $hash[C('TOKEN_NAME')] = $_POST[C('TOKEN_NAME')];
            $this->db->set_model($_POST['modelid']);
            if (!$this->db->autoCheckToken($hash)) {
                $this->error('令牌验证失败！');
            }
            if ($id = $this->db->edit_content()) {
                if ($_POST['dosubmit_continue']) {
                    $this->success('更新成功');
                } else {
                    $returnjs = 'function set_time() {$("#secondid").html(1);}setTimeout("set_time()", 500);setTimeout("window.close()", 1200);';
                    $this->assign('closeWin', true);
                    $this->assign('returnjs', $returnjs);
                    $this->success('更新成功，<span id="secondid">2</span>秒后关闭！');
                }
            } else {
                $this->error('更新失败！');
            }
        } else {
            $catid = intval($_GET['catid']);
            $category = $this->category_db->where("siteid = %d and id = %d", $this->siteid, $catid)->find();
            $modelid = intval($category['modelid']);
            $this->db->set_model($modelid);
            $content = $this->db->get_content(intval($_GET['contentid']));
            $categorys = $this->category_db->where('siteid = %d',$this->siteid)->order('listorder desc, id asc')->select();
            $this->categorys = array();
            if (!empty($categorys)) {
                foreach ($categorys as $key => $r) {
                    $this->categorys[$r['id']] = $r;
                }
            }
            require MODEL_PATH.'content_form.class.php';
            $content_form = new \content_form($modelid, $catid, $this->categorys);
            $forminfos = $content_form->get($content);
            $this->assign('formValidator', $content_form->formValidator);
            $this->assign('catid', $catid);
            $this->assign('status', $content['status']);
            $this->assign('forminfos', $forminfos);
            $this->assign('content_id', $content['id']);
            $this->assign('modelid', $modelid);
            $this->display();
        }
    }

    public function listorder(){
        if (isset($_GET['catid']) && $_GET['catid'] && ($category = $this->category_db->where(array('siteid' => $this->siteid, 'id' => $_GET['catid']))->find())) {
            $this->db->set_model($category['modelid']);
            if (isset($_POST['listorders']) && is_array($_POST['listorders'])) {
                $sort = $_POST['listorders'];
                foreach ($sort as $k => $v) {
                    $this->db->where(array('id'=>$k))->save(array('listorder'=>$v));
                }
            }
            $this->success('排序成功');
        } else {
            $this->error('栏目不存在');
        }
    }

    public function delete() {
        if (isset($_GET['catid']) && $_GET['catid'] && ($category = $this->category_db->where(array('siteid' => $this->siteid, 'id' => $_GET['catid']))->find())) {
            $this->db->set_model($category['modelid']);
            if (IS_POST) {
                $ids = $_POST['ids'];
                if (!empty($ids) && is_array($ids)) {
                    if ($this->db->delete_content($ids)) {
                        $this->success('删除成功！');
                    } else {
                        $this->error('删除失败！');
                    }
                } else {
                    $this->error("您没有勾选信息");
                }
            } else {
                if ($this->db->delete_content(intval($_GET['id']))) {
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败！');
                }
            }
        } else {
            $this->error('栏目不存在');
        }
    }

    /**
    * 标题重复检测
    */
    public function public_check_title() {
        if($_GET['data']=='' || (!$_GET['modelid'])) return '';
        $modelid = intval($_GET['modelid']);
        $this->db->set_model($modelid);
        $title = $_GET['data'];
        $r = $this->db->where(array('title'=>$title))->find();
        if($r) {
            exit('1');
        } else {
            exit('0');
        }
    }
}