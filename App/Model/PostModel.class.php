<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Model;
use Think\Page as Page;

class PostModel extends BaseModel {
    protected $autoCheckFields = false;
    protected $modelid, $my_fields;

    public function setModel($modelid) {
        $this->model = model('Model')->where("siteid = %d and id = %d",get_siteid(),$modelid)->find();
        if (empty($this->model)) {
            showmessage('模型不存在！');
        }
        $this->modelid = $modelid;
        $this->trueTableName = C("DB_PREFIX").strtolower($this->model['tablename']);
        $this->setField();
        return $this->model;
    }

    public function contentList($where=array(), $order = "id desc", $limit=20, $page_params = array()) {
        $module_fields = $this->getListFields();
        $list_fields = array('id', 'listorder');
        $list_fields = array_merge($list_fields, array_translate($module_fields, 'fieldid', 'field'));
        array_push($list_fields, 'inputtime', 'updatetime');
        return $this->getList($where, $order, $limit, $list_fields, $page_params);
    }

    /**
     * 获取列表页所需字段
     * @param mix $field 需要获取的ModelField表的字段
     * @param int $modelid 模型ID
    */
    public function getListFields($field=true, $modelid=null) {
        if (is_null($modelid)) {
            $modelid = $this->modelid;
        }
        $module_fields = D('ModelField')->field($field)->where(array('modelid' => $modelid, 'islist' => 1 ,'siteid' => get_siteid()))->order('listorder asc')->select();
        return $module_fields;
    }

    public function getContent($id) {
        return $this->where(array('id'=>$id))->find();
    }

    public function addContent($data) {
        // 主表
        $modelid = $this->modelid;
        $tablename = $this->trueTableName;
        if (isset($data['relation'])) {
            $data['relation'] = array2string($data['relation']);
        }
        // 获取所有字段
        require MODEL_PATH . 'content_input.class.php';
        $content_input = new \content_input($this->modelid);
        $inputinfo = $content_input->get($data);
        $inputinfo = $inputinfo['system'];
        // 匹配数据库字段，防止SQL语句出错
        $systeminfo = $this->parseField($inputinfo);
        $systeminfo = array_merge($systeminfo,array('username' => $_SESSION['user_info']['account'], 'siteid' => get_siteid()));

        // 设置更新时间 统一到CommonModel中通过CallBack函数设置
        /*if(isset($content_input->fields['updatetime'])) {
            $setting = string2array($content_input->fields['updatetime']['setting']);
            if ($setting['fieldtype'] == "int") {
                $systeminfo['updatetime'] = time();
            } else {
                $systeminfo['updatetime'] = date($setting['format']);
            }
        }*/
        return $this->add($systeminfo);

    }

    public function editContent($post_id, $data) {
        $modelid = $this->modelid;
        if (isset($data['relation'])) {
            $data['relation'] = array2string($data['relation']);
        }
        require MODEL_PATH . 'content_input.class.php';
        $content_input = new \content_input($this->modelid);
        $inputinfo = $content_input->get($data);
        $inputinfo = $inputinfo['system'];
        $systeminfo = $this->parseField($inputinfo);
        $systeminfo['siteid'] = get_siteid();

        // 设置更新时间 统一到CommonModel中通过CallBack函数设置
        /*if(isset($content_input->fields['updatetime'])) {
            $setting = string2array($content_input->fields['updatetime']['setting']);
            if ($setting['fieldtype'] == "int") {
                $systeminfo['updatetime'] = time();
            } else {
                $systeminfo['updatetime'] = date($setting['format']);
            }
        }*/
        return $this->where("id = %d", $post_id)->save($systeminfo);
    }

    public function deleteContent($ids) {
        $this->startTrans();
        if (is_array($ids)) {
            if ($this->where(array('id' => array('in', $ids)))->delete() === false || model('category_posts')->where(array('post_id' => array('in', $ids)))->delete() === false) {
                $this->rollback();
                return false;
            } else {
                $this->commit();
                return true;
            }
        } else {
            if ($this->where(array('id' => $ids))->delete() === false || model('category_posts')->where(array('post_id' => $ids))->delete() === false) {
                $this->rollback();
                return false;
            } else {
                $this->commit();
                return true;
            }
        }
    }

    public function parseField($options) {
        $temp = array();
        foreach ($this->my_fields as $key => $field) {
            if (isset($options[$field])) {
                $temp[$field] = $options[$field];
            }
        }
        return $temp;
    }

    public function setField() {
        $this->flush();
        $this->my_fields = $this->getDbFields();
    }
}
