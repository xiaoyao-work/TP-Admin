<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Model\Admin;
use Model\BaseModel;

class CommunityModel extends BaseModel {

    public function setModel($model_name) {
        $model = model('Model')->where(['tablename' => $model_name])->find();
        if (empty($model)) {
            return false;
        }
        $this->modelid = $model['id'];
        $this->model   = $model;
        return $model;
    }

    /**
     * 获取内容月份列表
     * @return [type] [description]
     */
    public function getMonths($parent_model_table_name = '') {
        $join_sql = "";
        if (!empty($parent_model_table_name)) {
            $join_sql = " JOIN " . $parent_model_table_name . " ON " . $parent_model_table_name . ".id = " . $this->trueTableName . ".id ";
        }
        return $this->query("select DATE_FORMAT(inputtime,'%Y-%m') month from " . $this->trueTableName . $join_sql . " group by month order by month desc");
    }

    /**
     * 获取列表页所需字段
     * @param mix $field 需要获取的ModelField表的字段
     */
    public function getListFields($field = true) {
        // 多站点模型共享
        // $module_fields = model('ModelField')->field($field)->where(array('modelid' => $this->modelid, 'islist' => 1 ,'siteid' => get_siteid()))->order('listorder asc')->select();
        $module_fields = model('ModelField')->field($field)->where(['modelid' => $this->modelid, 'islist' => 1])->order('listorder asc')->select();
        return $module_fields;
    }

    public function addContent($data, $parent_model = '') {
        $modelid = [$this->modelid];
        if (!empty($this->model['parentid'])) {
            $modelid[] = $this->model['parentid'];
        }
        // 获取所有字段
        require MODEL_PATH . 'content_input.class.php';
        $content_input = new \content_input($modelid);
        $inputinfo     = $content_input->get($data);

        $inputinfo['creator']   = session('user_info.account');
        $inputinfo['siteid']    = get_siteid();
        $inputinfo['post_type'] = $this->tablename;

        // 匹配数据库字段，防止SQL语句出错
        $post_id = $this->add($inputinfo);

        if ($post_id === false) {
            return false;
        }

        if ($post_id && $this->model['type'] == 2) {
            // 父模型
            if (empty($parent_model)) {
                return false;
            }
            $inputinfo['id'] = $post_id;
            $post_id         = model($parent_model['tablename'])->add($inputinfo);
            if ($post_id) {
                model($parent_model['tablename'])->where(['id' => $post_id])->save(['listorder' => $post_id]);
            }
        } else {
            if ($post_id) {
                $this->where(['id' => $post_id])->save(['listorder' => $post_id]);
            }
        }
        if ($post_id) {
            $data['id'] = $post_id;
            require MODEL_PATH . 'content_update.class.php';
            $content_update = new \content_update(current($modelid), $content_input->fields);
            $content_update->update($data);
        }
        return $post_id;
    }

    public function editContent($post_id, $data, $parent_model = '') {
        $modelid = [$this->modelid];
        if (!empty($this->model['parentid'])) {
            $modelid[] = $this->model['parentid'];
        }
        require MODEL_PATH . 'content_input.class.php';
        $content_input = new \content_input($modelid);
        $inputinfo     = $content_input->get($data);

        if ($this->where("id = %d", $post_id)->save($inputinfo) === false) {
            return false;
        }
        if ($this->model['type'] == 2) {
            // 父模型
            if (empty($parent_model)) {
                return false;
            }
            $inputinfo['id'] = $post_id;
            if (model($parent_model['tablename'])->add($inputinfo) === false) {
                return false;
            }
        }

        if ($post_id) {
            $data['id'] = $post_id;
            require MODEL_PATH . 'content_update.class.php';
            $content_update = new \content_update(current($modelid), $content_input->fields);
            $content_update->update($data);
        }
        return true;
    }

    public function deleteContent($ids) {
        if (is_array($ids)) {
            if ($this->where(['id' => ['in', $ids]])->delete() === false || model('category_posts')->where(['post_id' => ['in', $ids], 'post_type' => $this->tableName])->delete() === false) {
                return false;
            } else {
                return true;
            }
        } else {
            if ($this->where(['id' => $ids])->delete() === false || model('category_posts')->where(['post_id' => $ids, 'post_type' => $this->tableName])->delete() === false) {
                return false;
            } else {
                return true;
            }
        }
    }

}