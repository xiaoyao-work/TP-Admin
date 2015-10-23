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
 * 分类管理
*/
class TaxonomyController extends CommonController {
    protected $db, $dbName, $moduleDb;
    function __construct() {
        parent::__construct();
        $this->db = D('Taxonomy');
        $this->moduleDb = D('Module');
        $this->dbName = 'taxonomy';
    }

    public function index() {
        // 获取模型信息
        $model_db = D('Model');
        $model = $model_db->where(array('tablename' => $this->dbName))->find();
        if (empty($model)) {
            $this->error('模型不存在！');
        }
        // 获取模型字段信息
        $list_fields = $this->moduleDb->getListFields(array('name', 'field'), $model['id']);
        $fields = array('id', 'listorder', 'updatetime');
        foreach ($list_fields as $key => $field) {
            $fields[] = $field['field'];
        }
        $taxonomies = $this->db->getList('', 'listorder asc, id desc', 10, $fields);
        $this->assign('list_fields', $list_fields);
        $this->assign('contents', $taxonomies['data']);
        $this->assign('pages', $taxonomies['page']);
        $this->display();
    }

    public function add() {
        // 获取模型信息
        $model_db = D('Model');
        $model = $model_db->where(array('tablename' => $this->dbName))->find();
        if (empty($model)) {
            $this->error('模型不存在！');
        }

        if (IS_POST) {
            $hash[C('TOKEN_NAME')] = $_POST[C('TOKEN_NAME')];
            $this->moduleDb->setModel($_POST['moduleid']);
            if (!$this->db->autoCheckToken($hash)) {
                $this->error('令牌验证失败, 请刷新页面');
            }
            if ($id = D('Module')->addContent()) {
                $this->success('添加成功!', 'index');
            } else {
                $this->error('添加失败！');
            }
        } else {
            require MODEL_PATH.'content_form.class.php';
            $content_form = new \content_form($model['id']);
            $forminfos = $content_form->get();
            // 合并基本和高级属性
            $forminfos = array_merge($forminfos['base'], $forminfos['senior']);
            $this->assign('formValidator', $content_form->formValidator);
            $this->assign('forminfos', $forminfos);
            $this->assign('module', $model);
            $this->display('Module:add');
        }
    }


    public function edit() {
        // 获取模型信息
        $model_db = D('Model');
        $model = $model_db->where(array('tablename' => $this->dbName))->find();
        if (empty($model)) {
            $this->error('模型不存在！');
        }
        if (IS_POST) {
            $hash[C('TOKEN_NAME')] = $_POST[C('TOKEN_NAME')];
            if (!$this->db->autoCheckToken($hash)) {
                $this->error('令牌验证失败, 请刷新页面');
            }
            if (!isset($_POST['id'])) {
                $this->error('参数缺失！');
            }
            $this->moduleDb->setModel($model['id']);
            if ($this->moduleDb->editContent()) {
                $this->success('更新成功!', 'index');
            } else {
                $this->error('更新失败！');
            }
        } else {
            if (!isset($_GET['id'])) {
                $this->error('参数缺失！');
            }
            // 设置模型，获取内容
            $this->moduleDb->setModel($model['id']);
            $content = $this->moduleDb->getContent($_GET['id']);
            if (empty($content)) {
                $this->error('内容不存在！');
            }
            require MODEL_PATH.'content_form.class.php';
            $content_form = new \content_form($model['id']);
            $forminfos = $content_form->get($content);
            $forminfos = array_merge($forminfos['base'], $forminfos['senior']);
            $this->assign('formValidator', $content_form->formValidator);
            $this->assign('forminfos', $forminfos);
            $this->assign('content', $content);
            $this->assign('module', $model);
            $this->display('Module:edit');
        }
    }

    public function listorder(){
        $model_db = D('Model');
        $model = $model_db->where(array('tablename' => $this->dbName))->find();
        if (empty($model)) {
            $this->error('模型不存在！');
        }
        $this->moduleDb->setModel($model['id']);
        if (isset($_POST['listorders']) && is_array($_POST['listorders'])) {
            $sort = $_POST['listorders'];
            foreach ($sort as $k => $v) {
                $this->moduleDb->where(array('id'=>$k))->save(array('listorder'=>$v));
            }
        }
        $this->success('排序成功！');
    }

    public function delete() {
        $model_db = D('Model');
        $model = $model_db->where(array('tablename' => $this->dbName))->find();
        if (empty($model)) {
            $this->error('模型不存在！');
        }
        $this->moduleDb->setModel($model['id']);
        if (IS_POST) {
            $ids = $_POST['ids'];
            if (!empty($ids) && is_array($ids)) {
                if ($this->moduleDb->deleteContent($ids)) {
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error("您没有勾选信息");
            }
        } else {
            if ($this->moduleDb->deleteContent(intval($_GET['id']))) {
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！');
            }
        }
    }

    /**
     * 标题重复检测
    */
    public function public_check_title() {
        if($_GET['data']=='' || (!$_GET['modelid'])) return '';
        $moduleid = intval($_GET['modelid']);
        $this->moduleDb->setModel($moduleid);
        $title = $_GET['data'];
        $r = $this->moduleDb->where(array('title'=>$title))->find();
        if($r) {
            exit('1');
        } else {
            exit('0');
        }
    }

}