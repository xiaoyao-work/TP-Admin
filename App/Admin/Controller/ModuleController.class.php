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
class ModuleController extends CommonController {

    protected $db;
    function __construct() {
        parent::__construct();
        $this->db = D("Module");
    }

    public function index() {
        if (!isset($_GET['moduleid'])) {
            $this->error('模型参数缺失！');
        }
        $module = D('Model')->find($_GET['moduleid']);
        if (empty($module)) {
            $this->error('模型不存在！');
        }
        $this->db->setModel($module['id']);
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

        $list_fields = $this->db->getListFields(array('name', 'field'));
        $contentFields = array('id', 'updatetime');
        foreach ($list_fields as $key => $field) {
            $contentFields[] = $field['field'];
        }
        $data = $this->db->contentList($search, "listorder desc, id desc", 10, $contentFields);
        $this->assign('module', $module);
        $this->assign('contents',$data['data']);
        $this->assign('list_fields',$list_fields);
        $this->assign('pages',$data['page']);
        $this->display();
    }

    public function add() {
        if (IS_POST) {
            $hash[C('TOKEN_NAME')] = $_POST[C('TOKEN_NAME')];
            $this->db->setModel($_POST['moduleid']);
            if (!$this->db->autoCheckToken($hash)) {
                $this->error('令牌验证失败, 请刷新页面');
            }
            if ($id = $this->db->addContent()) {
                $this->success('添加成功!', U('Module/index', array('moduleid' => $_POST['moduleid'])));
            } else {
                $this->error('添加失败！');
            }
        } else {
            if (!isset($_GET['moduleid'])) {
                $this->error('模型参数缺失！');
            }
            $module = D('Model')->find($_GET['moduleid']);
            if (empty($module)) {
                $this->error('模型不存在！');
            }
            require MODEL_PATH.'content_form.class.php';
            $content_form = new \content_form($module['id']);
            $forminfos = $content_form->get();
            // 合并基本和高级属性
            $forminfos = array_merge($forminfos['base'], $forminfos['senior']);
            $this->assign('formValidator', $content_form->formValidator);
            $this->assign('forminfos', $forminfos);
            $this->assign('module', $module);
            $this->display();
        }
    }

    public function edit() {
        if (IS_POST) {
            $hash[C('TOKEN_NAME')] = $_POST[C('TOKEN_NAME')];
            if (!isset($_POST['moduleid']) || !isset($_POST['id'])) {
                $this->error('模型参数缺失！');
            }
            $module = D('Model')->find($_POST['moduleid']);
            if (empty($module)) {
                $this->error('模型不存在！');
            }
            $this->db->setModel($module['id']);
            if (!$this->db->autoCheckToken($hash)) {
                $this->error('令牌验证失败, 请刷新页面');
            }
            if ($this->db->editContent()) {
                $this->success('更新成功!', U('Module/index', array('moduleid' => $module['id'])));
            } else {
                $this->error('更新失败！');
            }
        } else {
            if (!isset($_GET['moduleid']) || !isset($_GET['id'])) {
                $this->error('模型参数缺失！');
            }
            $module = D('Model')->find($_GET['moduleid']);
            if (empty($module)) {
                $this->error('模型不存在！');
            }
            // 设置模型，获取内容
            $this->db->setModel($module['id']);
            $content = $this->db->getContent($_GET['id']);
            if (empty($content)) {
                $this->error('内容不存在！');
            }
            require MODEL_PATH.'content_form.class.php';
            $content_form = new \content_form($module['id']);
            $forminfos = $content_form->get($content);
            $forminfos = array_merge($forminfos['base'], $forminfos['senior']);
            $this->assign('formValidator', $content_form->formValidator);
            $this->assign('forminfos', $forminfos);
            $this->assign('content', $content);
            $this->assign('module', $module);
            $this->display();
        }
    }

    public function listorder(){
        if (!isset($_GET['moduleid'])) {
            $this->error('模型参数缺失！');
        }
        $module = D('Model')->find($_GET['moduleid']);
        if (empty($module)) {
            $this->error('模型不存在！');
        }
        $this->db->setModel($module['id']);
        if (isset($_POST['listorders']) && is_array($_POST['listorders'])) {
            $sort = $_POST['listorders'];
            foreach ($sort as $k => $v) {
                $this->db->where(array('id'=>$k))->save(array('listorder'=>$v));
            }
        }
        $this->success('排序成功！');
    }

    public function delete() {
        if (!isset($_REQUEST['moduleid'])) {
            $this->error('模型参数缺失！');
        }
        $module = D('Model')->find($_REQUEST['moduleid']);
        if (empty($module)) {
            $this->error('模型不存在！');
        }
        $this->db->setModel($module['id']);
        if (IS_POST) {
            $ids = $_POST['ids'];
            if (!empty($ids) && is_array($ids)) {
                if ($this->db->deleteContent($ids)) {
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error("您没有勾选信息");
            }
        } else {
            if ($this->db->deleteContent(intval($_GET['id']))) {
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
        $this->db->setModel($moduleid);
        $title = $_GET['data'];
        $r = $this->db->where(array('title'=>$title))->find();
        if($r) {
            exit('1');
        } else {
            exit('0');
        }
    }
}