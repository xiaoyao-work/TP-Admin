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

class PostModel extends BaseModel {
    protected $autoCheckFields = false;
    protected $modelid, $myFields;

    public function setModel($modelid) {
        // 模型共享
        // $model = model('Model')->where("siteid = %d and id = %d",get_siteid(),$modelid)->find();
        $model = model('Model')->find($modelid);
        if (empty($model)) {
            return false;
        }
        $this->modelid       = $modelid;
        $this->model         = $model;
        $this->tableName     = strtolower($model['tablename']);
        $this->trueTableName = C("DB_PREFIX") . strtolower($model['tablename']);
        $this->resetFields();
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

    public function getContent($id) {
        return $this->find($id);
    }

    public function addContent($data) {
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
        $inputinfo['post_type'] = $this->tableName;

        if ($this->model['type'] == 2) {
            // 父模型
            $model = $this->model;
            $this->setModel($this->model['parentid']);
            // 匹配数据库字段，防止SQL语句出错
            $post_data = $this->parseField($inputinfo);
            $post_id   = $this->add($post_data);
            if ($post_id) {
                $this->where(['id' => $post_id])->save(['listorder' => $post_id]);
                $inputinfo['listorder'] = $post_id;
            } else {
                return false;
            }

            /**
             * 处理子模型
             */
            $this->setModel($model['id']);
            // 匹配数据库字段，防止SQL语句出错
            $post_data       = $this->parseField($inputinfo);
            $post_data['id'] = $post_id;
            $post_id         = $this->add($post_data);
        } else {
            // 匹配数据库字段，防止SQL语句出错
            $post_data = $this->parseField($inputinfo);
            $post_id   = $this->add($post_data);
            if ($post_id) {
                $this->where(['id' => $post_id])->save(['listorder' => $post_id]);
                $inputinfo['id'] = $post_id;
                $inputinfo['listorder'] = $post_id;
            } else {
                return false;
            }
        }

        if ($post_id) {
            $data['id']        = $post_id;
            $data['creator']   = $inputinfo['creator'];
            $data['siteid']    = $inputinfo['siteid'];
            $data['post_type'] = $inputinfo['post_type'];
            require MODEL_PATH . 'content_update.class.php';
            $content_update = new \content_update(current($modelid), $content_input->fields);
            $content_update->update($data);
        }
        return $inputinfo;
    }

    public function editContent($post_id, $data) {
        $modelid = [$this->modelid];
        if (!empty($this->model['parentid'])) {
            $modelid[] = $this->model['parentid'];
        }
        require MODEL_PATH . 'content_input.class.php';
        $content_input = new \content_input($modelid);
        $inputinfo     = $content_input->get($data);

        $post_data = $this->parseField($inputinfo);

        if ($this->where("id = %d", $post_id)->save($post_data) === false) {
            return false;
        }

        if ($this->model['type'] == 2) {
            $this->setModel($this->model['parentid']);
            // 匹配数据库字段，防止SQL语句出错
            $post_data = $this->parseField($inputinfo);
            if ($this->where("id = %d", $post_id)->save($post_data) === false) {
                return false;
            }
        }

        if ($post_id) {
            $data['id']        = $post_id;
            $data['creator']   = session('user_info.account');
            $data['siteid']    = get_siteid();
            $data['post_type'] = $this->tableName;
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

    public function parseField($options) {
        $temp = [];
        foreach ($this->myFields as $key => $field) {
            if (isset($options[$field])) {
                $temp[$field] = $options[$field];
            }
        }
        return $temp;
    }

    public function resetFields() {
        $this->flush();
        $this->myFields = $this->getDbFields();
    }

    public function fieldExist($field) {
        return in_array($field, $this->myFields);
    }
}
