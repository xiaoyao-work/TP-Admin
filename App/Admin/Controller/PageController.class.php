<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
* 页面管理
*/
class PageController extends CommonController {
    protected $db, $field_db;

    function __construct() {
        parent::__construct();
        $this->db = model("Page");
        $this->field_db = model('PageField');
    }

    public function index() {
        $page_data = $this->db->getList();
        $this->assign('pages', $page_data['data']);
        $this->assign('page_html', $page_data['page']);
        $this->display();
    }

    public function add() {
        if (IS_POST) {
            $data = I('post.page');
            if ($this->db->addPage($data)) {
                $this->success('添加成功!', U('Page/index'));
            } else {
                $this->error('添加失败！');
            }
        } else {
            $models = model('Model')->where(array('siteid' => $this->siteid))->select();
            $this->assign('models',$models);

            $template_list = get_page_templates();
            $this->assign('template_list',$template_list);
            $this->display();
        }
    }

    public function edit() {
        if (IS_POST) {
            $data = I('post.page');
            $meta_data = I('post.info');
            $page_id = I('post.id');
            $this->db->startTrans();
            if ($this->db->updatePage($page_id, $data, $meta_data)) {
                $this->db->commit();
                $this->success('更新成功!', U('Page/index'));
            } else {
                $this->db->rollback();
                $this->error('更新失败！');
            }
        } else {
            $page_id = I('get.id');
            // 设置模型，获取内容
            $page = $this->db->find($page_id);
            if (empty($page)) {
                $this->error('内容不存在！');
            }
            $page_meta = model('pagemeta')->where(array('page_id' => $page_id))->select();
            if (!empty($page_meta)) {
                $page_meta = array_translate($page_meta, 'meta_key', 'meta_value');
            }
            require MODEL_PATH.'content_form.class.php';
            $content_form = new \content_form($page['template'], 2);
            $forminfos = $content_form->get($page_meta);

            $this->assign('formValidator', $content_form->formValidator);
            $this->assign('forminfos', $forminfos);
            $this->assign('content', $page);

            $template_list = get_page_templates();
            $this->assign('template_list',$template_list);
            $this->display();
        }
    }

    public function delete() {
        $ids = IS_POST ? I('post.ids') : I('get.id');
        if (empty($ids)) {
            $this->error("您没有勾选信息");
        }
        $ids = is_array($ids) ? $ids : array($ids);
        if ($result = $this->db->where(array('id' => array('in', $ids)))->delete()) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * 页面模板字段操作
     */
    public function template() {
        $template_list = get_page_templates();
        $this->assign('template_list',$template_list);
        $this->display();
    }

    public function fields() {
        $template = I('get.template');
        if (empty($template)) {
            $this->error("模板参数缺少！");
        }
        $fields = $this->field_db->where(array('siteid' => $this->siteid, 'template' => $template))->order("listorder asc , fieldid asc")->select();
        $this->assign('fields', $fields);
        require MODEL_PATH.'fields.inc.php';
        $this->assign('template', $template);
        $this->assign('forbid_fields',$forbid_fields);
        $this->assign('forbid_delete',$forbid_delete);
        $this->assign('att_css_js', $att_css_js);
        $this->assign('not_allow_fields', $not_allow_fields);
        $this->assign('unique_fields', $unique_fields);
        $this->display();
    }

    /**
     * 字段添加
     */
    public function fieldadd() {
        if (IS_POST) {
            $data = I('post.info');
            if (!isset($data['template']) || empty($data['template'])) {
                $this->error("模板参数缺少！");
            }
            //附加属性值
            $data['setting'] = array2string($_POST['setting'], 0);
            $data['siteid'] = $this->siteid;
            if ($this->field_db->add($data)) {
                $this->success('添加成功');
            } else {
                $this->success('添加失败');
            }
        } else {
            require MODEL_PATH.'fields.inc.php';
            $template = I('get.template');
            $all_field = array();
            foreach($fields as $_k=>$_v) {
                if(in_array($_k, $not_allow_fields)) continue;
                $all_field[$_k] = $_v;
            }
            $this->assign('all_field', $all_field);
            $this->assign('template', $template);
            $this->assign('forbid_fields',$forbid_fields);
            $this->assign('forbid_delete',$forbid_delete);
            $this->assign('att_css_js', $att_css_js);
            $this->assign('not_allow_fields', $not_allow_fields);
            $this->assign('unique_fields', $unique_fields);
            $this->display();
        }

    }

    /**
     * 字段添加
     */
    public function fieldedit() {
        if (IS_POST) {
            $fieldid = intval($_POST['fieldid']);
            $data = I('post.info');
            //附加属性值
            $data['setting'] = array2string($_POST['setting'], 0);

            if ($this->field_db->where(array('fieldid'=>$fieldid,'siteid'=>$this->siteid))->save($data) !== false) {
                $this->success('更新成功');
            } else {
                $this->error('操作失败');
            }
        } else {
            require MODEL_PATH . 'fields.inc.php';
            $fieldid = intval($_GET['fieldid']);
            $page_field = $this->field_db->where(array('fieldid' => $fieldid))->find();
            extract($page_field);
            // 获取相关设置参数
            require MODEL_PATH.$formtype.DIRECTORY_SEPARATOR.'config.inc.php';
            $setting = string2array($setting);
            ob_start();
            include MODEL_PATH.$formtype.DIRECTORY_SEPARATOR.'field_edit_form.inc.php';
            $form_data = ob_get_contents();
            ob_end_clean();

            $this->assign('forbid_fields',$forbid_fields);
            $this->assign('forbid_delete',$forbid_delete);
            $this->assign('att_css_js', $att_css_js);
            $this->assign('not_allow_fields', $not_allow_fields);
            $this->assign('unique_fields', $unique_fields);

            $this->assign('fields', $fields);
            $this->assign('fieldid',$fieldid);
            $this->assign('page_field',$page_field);
            $this->assign('form_data',$form_data);
            $this->display();
        }
    }

    /**
     * 字段删除
     */
    public function fielddelete() {
        $fieldid = I('get.fieldid');
        if (empty($fieldid)) {
            $this->error('参数缺失！');
        }
        if($this->field_db->where(array('fieldid' => $fieldid,'siteid' => $this->siteid))->delete() !== false ) {
            $this->success('操作成功！');
        } else {
            $this->success('操作失败！');
        }
    }

    /**
     * 禁用字段
     */
    public function disabled() {
        $fieldid = I('get.fieldid');
        $disabled = $_GET['disabled'] ? 0 : 1;
        if ($this->field_db->where(array('fieldid'=>$fieldid,'siteid'=>$this->siteid))->save(array('disabled'=>$disabled)) !== false) {
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

    /**
     * 排序
     */
    public function listorder() {
        if(isset($_POST['dosubmit'])) {
            foreach($_POST['listorders'] as $id => $listorder) {
                $this->field_db->where(array('fieldid'=>$id))->save(array('listorder'=>$listorder));
            }
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

    /**
     * 检查字段是否存在
     */
    public function public_checkfield() {
        $field = I('get.field');
        $oldfield = I('get.oldfield');
        if($field == $oldfield) exit('1');

        $template = I('get.template');
        $field_exist = $this->field_db->where(array('template' => $template, 'field' => $field, 'siteid' => $this->siteid))->find();
        if(empty($field_exist)) {
            exit('1');
        } else {
            exit('0');
        }
    }

    /**
     * 字段属性设置
     */
    public function public_field_setting() {
        $fieldtype = $_GET['fieldtype'];
        require MODEL_PATH.$fieldtype.DIRECTORY_SEPARATOR.'config.inc.php';
        ob_start();
        include MODEL_PATH.$fieldtype.DIRECTORY_SEPARATOR.'field_add_form.inc.php';
        $data_setting = ob_get_contents();
        ob_end_clean();

        $settings = array(
            'field_basic_table'=>$field_basic_table,
            'field_minlength'=>$field_minlength,
            'field_maxlength'=>$field_maxlength,
            'field_allow_search'=>$field_allow_search,
            'field_allow_fulltext'=>$field_allow_fulltext,
            'field_allow_isunique'=>$field_allow_isunique,
            'setting'=>$data_setting);

        $this->ajaxReturn($settings);
    }
}