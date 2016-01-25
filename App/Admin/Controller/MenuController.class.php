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
* 后台菜单操作类
*/
class MenuController extends CommonController {

    public function index() {
        $menus = model('Menu')->nodeList();
        $this->assign("menus", $menus);
        $this->display();
    }

    public function add() {
        if (IS_POST) {
            $this->checkToken();
            if (model("Menu")->add($_POST['info']) > 0) {
                $this->success('操作成功！', "index");
            } else {
                $this->error('操作失败！');
            }
        } else {
            $menus = model('Menu')->nodeList();
            $this->assign("menus", $menus);
            $this->display();
        }
    }

    public function edit() {
        $nid = $_GET['nid'];
        if (IS_POST) {
            $this->checkToken();
            if (model("Menu")->where(array('id' => $_POST['nid']))->save($_POST['info']) !== false) {
                $this->success('操作成功！', __MODULE__ . '/Menu/index');
            } else {
                $this->error('操作失败！', __MODULE__ . '/Menu/index');
            }
        } else {
            if (empty($nid)) {
                $this->error('异常操作！', __MODULE__ . '/Menu/index');
            }
            $node =  model("Menu")->find($nid);
            $menus = model('Menu')->nodeList();

            $this->assign('nid', $nid);
            $this->assign("node", $node);
            $this->assign("menus", $menus);
            $this->display();
        }
    }

    public function del() {
        $nid = intval($_GET['nid']);
        if (empty($nid)) {
            $this->error('异常操作！', __MODULE__ . '/Menu/index');
        }
        $result = logic('Menu')->dropNodes($nid);
        if ($result) {
            $this->success('操作成功！', __MODULE__ . '/Menu/index');
        } else {
            $this->error('操作失败！', __MODULE__ . '/Menu/index');
        }
    }

    public function listorder() {
        if (isset($_POST['sort']) && is_array($_POST['sort'])) {
            $sort = $_POST['sort'];
            foreach ($sort as $k => $v) {
                model('Menu')->where(array('id'=>$k))->save(array('sort'=>$v));
            }
        }
        $this->success('排序成功');
    }
}