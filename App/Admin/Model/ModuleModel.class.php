<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Page as Page;

define('FIELDS_PATH', APP_PATH.'Admin'.DIRECTORY_SEPARATOR.'Common'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR);
class ModuleModel extends CommonModel {
    protected $autoCheckFields = false;
    protected $modelid, $my_fields;

    public function setModel($modelid) {
        $model = M('Model')->where("siteid = %d and id = %d",get_siteid(),$modelid)->find();
        $this->modelid = $modelid;
        $this->trueTableName = C("DB_PREFIX").strtolower($model['tablename']);
        $this->setField();
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

    public function addContent() {
        // 主表
        $modelid = $this->modelid;
        $tablename = $this->trueTableName;
        $data = $_POST['info'];
        if (isset($data['relation'])) {
            $data['relation'] = array2string($data['relation']);
        }
        // 获取所有字段
        require FIELDS_PATH.'content_input.class.php';
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
        // 开启事务
        $this->startTrans();
        if (($contentid = $this->add($systeminfo)) !== false) {
            // 发布到推荐位
            if ($systeminfo['posids']) {
                foreach ($data['posids'] as $key => $posid) {
                    if ($posid > 0) {
                        $position_data[] = array('id' => $contentid, 'catid' => $systeminfo['catid'], 'posid' => $posid, 'modelid' => $modelid, 'module' => 'content', 'thumb' => $systeminfo['thumb'], 'siteid' => $systeminfo['siteid'], 'listorder' => $contentid, 'data' => array2string(array('title' => $systeminfo['title'], 'url' => $url, 'description' => $systeminfo['description'], 'inputtime' => $systeminfo['inputtime']), true));
                    }
                }
                if (!empty($position_data)) {
                    if (D("PositionData")->addAll($position_data) === false) {
                        $this->rollback();
                        return false;
                    }
                }
            }
            // END 发布到推荐位
            $this->commit();
        } else {
            $this->rollback();
        }
        return $contentid;
    }

    public function editContent() {
        $result = false;
        $modelid = $this->modelid;
        $contentid = intval($_POST['id']);
        $data = $_POST['info'];
        if (isset($data['relation'])) {
            $data['relation'] = array2string($data['relation']);
        }
        require FIELDS_PATH.'content_input.class.php';
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

        if ($result = $this->where("id = %d", $contentid)->save($systeminfo) !== false) {
            // 发布到推荐位
            $position_model = D('PositionData');
            if ($position_model->where( array("id" => $contentid , "modelid" => $modelid) )->delete() === false) {
                $this->rollback();
                return false;
            }
            if ($systeminfo['posids']) {
                foreach ($data['posids'] as $key => $posid) {
                    if ($posid > 0) {
                        $position_data[] = array('id' => $contentid, 'catid' => $systeminfo['catid'], 'posid' => $posid, 'modelid' => $modelid, 'module' => 'content', 'thumb' => $systeminfo['thumb'], 'siteid' => $systeminfo['siteid'], 'listorder' => $contentid, 'data' => var_export(array('title' => $systeminfo['title'], 'url' => $url, 'description' => $systeminfo['description'], 'inputtime' => $systeminfo['inputtime']), true));
                        $position_model->add($position_data);
                    }
                }
                if (!empty($position_data)) {
                    if (D("PositionData")->addAll($position_data) === false) {
                        $this->rollback();
                        return false;
                    }
                }
            }
            // END 发布到推荐位
            $this->commit();
        } else {
            $this->rollback();
        }
        return $result;
    }

    public function deleteContent($ids) {
        $this->startTrans();
        if (is_array($ids)) {
            $result = (($this->where(array('id' => array('in', $ids)))->delete()) === false ? fasle : true);
            if ($result) {
                if (D("PositionData")->where( array("id" => array('in', $ids), "modelid" => $this->modelid) )->delete() === false) {
                    $this->rollback();
                    return false;
                }
            }
            $this->commit();
            return $result;
        } else {
            $result = ($this->where(array('id' => $ids))->delete()) === false ? fasle : true;
            if ($result) {
                if (D("PositionData")->where( array("id" => $ids, "modelid" => $this->modelid) )->delete() === false) {
                    $this->rollback();
                    return false;
                }
                $this->commit();
            } else {
                $this->rollback();
            }
            return $result;
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
