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
class ContentModel extends CommonModel {
    protected $autoCheckFields = false;
    protected $category, $modelid, $my_fields;

    public function set_model($modelid) {
        $model = M('Model')->where("siteid = %d and id = %d",get_siteid(),$modelid)->find();
        $this->modelid = $modelid;
        $this->trueTableName = C("DB_PREFIX").strtolower($model['tablename']);
        $this->set_field();
    }

    public function content_list($where=array(), $order = "id desc", $limit=20, $field=true, $page_params=array()) {
        $data = $this->getList($where, $order, $limit, $field, $page_params);
        return $data;
    }

    public function mobile_content_list($where=array(), $order = "id desc", $limit=10, $page_params = array()) {
        $page_num = isset($_GET['p']) ? $_GET['p'] : 1;
        $news = $this->where(array_merge(array('siteid' => get_siteid()),$where))->order($order)->page($page_num .', '.$limit)->select();
        $news_temp = array();
        foreach ($news as $key => $value) {
            $value['url'] = U('Content/show',"catid=".$value['catid']."&id=".$value['id']);
            $news_temp[$value['id']] = $value;
        }
        unset($news);

        $count = $this->where(array_merge(array('siteid' => get_siteid()),$where))->count();
        $Page = new Page($count,$limit);
        if ($page_params) {
            foreach ($page_params as $key => $param) {
                $Page->setConfig($key, $param);
            }
        }
        $show = $Page->show();
        $next_page_num = ceil($count/$limit) - $page_num;

        return array('status' => 'success', 'message' => $news_temp, 'next_page_num' => $next_page_num, 'finished' => $finished, 'pages' => $show);
    }


    public function get_content( $id, $is_relation = false ) {
        $r = $this->where(array('id'=>$id))->find();
        $this->trueTableName = $this->trueTableName.'_data';
        $r2 = $this->where(array('id'=>$id))->find();
        if( !$r2 ) {
            if ($is_relation) {
                return false;
            }
            showmessage('该信息数据不完整，请删除后，重新添加','blank');
        }
        $data = array_merge($r,$r2);
        return $data;
    }

    public function add_content() {
        // 主表
        $modelid = $this->modelid;
        $tablename = $this->trueTableName;
        $data = $_POST['info'];

        $data['relation'] = array2string($data['relation']);
        require FIELDS_PATH.'content_input.class.php';
        $content_input = new \content_input($this->modelid);
        $inputinfo = $content_input->get($data);
        $systeminfo = $this->parse_field($inputinfo['system']);
        $systeminfo = array_merge($systeminfo,array('username' => $_SESSION['user_info']['account'], 'siteid' => get_siteid()));
        if($data['inputtime'] && !is_numeric($data['inputtime'])) {
            $systeminfo['inputtime'] = strtotime($data['inputtime']);
        } elseif(!$data['inputtime']) {
            $systeminfo['inputtime'] = time();
        } else {
            $systeminfo['inputtime'] = $data['inputtime'];
        }
        $systeminfo['sysadd'] = defined('IN_ADMIN') ? 1 : 0;

        // $systeminfo = array_map('strip_tags', $systeminfo);
        $this->startTrans();
        if (($contentid = $this->add($systeminfo)) !== false) {
            // 更新URL地址
            if($data['islink']==1) {
                $url = trim_script($_POST['linkurl']);
                $url = str_replace(array('select ',')','\\','#',"'"),' ',$urls[0]);
            } else {
                $siteinfo =  get_site_info($systeminfo['siteid']);
                $url = U( C("DEFAULT_GROUP") . '/Content/show@'.$siteinfo['url'] ,'catid='.$systeminfo['catid'].'&id='.$contentid);
            // $url = U('Content/show','catid='.$systeminfo['catid'].'&id='.$contentid);
            }
            $this->where(array('id' => $contentid))->save(array('url' => $url));

            // 附表
            $this->trueTableName = $this->trueTableName."_data";
            // $content_data = array('id' => $contentid ,'content' => $data['content'], 'relation' => $data['relation'], 'copyfrom' => $data['copyfrom'], 'allow_comment' => $data['allow_comment']);
            $this->set_field();
            $content_data = $this->parse_field($inputinfo['model']);
            $content_data['id'] = $contentid;
            if ($this->add($content_data) == false) {
                $this->rollback();
                return false;
            }

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

            //发布到其他栏目
            if($contentid && isset($_POST['othor_catid']) && is_array($_POST['othor_catid'])) {
                $linkurl = $url;
                foreach ($_POST['othor_catid'] as $cid=>$_v) {
                    $this->set_catid($cid);
                    $mid = $this->category[$cid]['modelid'];
                    echo $mid;
                    if($modelid==$mid) {
                        //相同模型的栏目插入新的数据
                        $systeminfo['catid'] = $cid;
                        $this->set_field();
                        $content_data = $this->parse_field($systeminfo);
                        $newid = $content_data['id'] = $this->add($systeminfo);
                        if ($newid == false) {
                            $this->rollback();
                            echo '11' . $this->getLastSql();
                            exit();
                            return false;
                        }
                        // echo $this->getLastSql();
                        $this->trueTableName = $this->trueTableName.'_data';
                        $this->set_field();
                        $content_data = $this->parse_field($inputinfo['model']);
                        if ($this->add($content_data) == false) {
                            $this->rollback();
                            echo '22' . $this->getLastSql();
                            exit();
                            return false;
                        };
                        if($data['islink']==1) {
                            $url = $_POST['linkurl'];
                            $url = str_replace(array('select ',')','\\','#',"'"),' ',$url);
                        } else {
                            $url = U( C("DEFAULT_GROUP") . '/Content/show','catid='.$systeminfo['catid'].'&id='.$newid);
                        }
                        $this->trueTableName = $tablename;
                        $this->set_field();
                        $this->where(array('id'=>$newid))->save(array('url'=>$url));
                    } else {
                        //不同模型插入转向链接地址
                        $systeminfo['catid'] = $cid;
                        $systeminfo['url'] = $linkurl;
                        $systeminfo['sysadd'] = 1;
                        $systeminfo['islink'] = 1;
                        $this->set_field();
                        $content_data = $this->parse_field($systeminfo);
                        $newid = $this->add($systeminfo);
                        if ($newid == false) {
                            $this->rollback();
                            return false;
                        }
                        $this->trueTableName = $this->trueTableName.'_data';
                        if ($this->add(array('id'=>$newid)) == fasle) {
                            $this->rollback();
                            return false;
                        };
                    }
                }
            }
            //END 发布到其他栏目
            $this->commit();
        } else {
            $this->rollback();
        }
        return $contentid;
    }

    public function edit_content() {
        $result = false;
        $modelid = $this->modelid;
        $contentid = intval($_POST['contentid']);
        $data = $_POST['info'];
        $data['relation'] = array2string($data['relation']);
        require FIELDS_PATH.'content_input.class.php';
        $content_input = new \content_input($this->modelid);
        $inputinfo = $content_input->get($data);
        $systeminfo = $this->parse_field($inputinfo['system']);
        $systeminfo['siteid'] = get_siteid();
        if($data['inputtime'] && !is_numeric($data['inputtime'])) {
            $systeminfo['inputtime'] = strtotime($data['inputtime']);
        } elseif(!$data['inputtime']) {
            $systeminfo['inputtime'] = time();
        } else {
            $systeminfo['inputtime'] = $data['inputtime'];
        }

        if($data['islink']==1) {
            $systeminfo['url'] = $_POST['linkurl'];
            $systeminfo['url'] = str_replace(array('select ',')','\\','#',"'"),' ',$systeminfo['url']);
        } else {
            $siteinfo =  get_site_info(get_siteid());
            $url = U( C("DEFAULT_GROUP") . '/Content/show@'.$siteinfo['url'] ,'catid='.$systeminfo['catid'].'&id='.$contentid);
            // $url = U('Content/show','catid='.$systeminfo['catid'].'&id='.$contentid);
            $systeminfo['url'] = $url;
        }
        // 开启事务
        $this->startTrans();
        if ($result = $this->where("id = %d", $contentid)->save($systeminfo) !== false) {
            // 附表
            $this->trueTableName = $this->trueTableName."_data";
            $this->set_field();
            $content_data = $this->parse_field($inputinfo['model']);
            if ($this->where("id = %d", $contentid)->save($content_data) === false) {
                $this->rollback();
                return false;
            }
            // 发布到推荐位
            $position_model = D('PositionData');
            if ($position_model->where( array("id" => $contentid , "modelid" => $modelid) )->delete() === false) {
                $this->rollback();
                return false;
            };
            if ($systeminfo['posids']) {
                foreach ($data['posids'] as $key => $posid) {
                    if ($posid > 0) {
                        $position_data[] = array('id' => $contentid, 'catid' => $systeminfo['catid'], 'posid' => $posid, 'modelid' => $modelid, 'module' => 'content', 'thumb' => $systeminfo['thumb'], 'siteid' => $systeminfo['siteid'], 'listorder' => $contentid, 'data' => var_export(array('title' => $systeminfo['title'], 'url' => $url, 'description' => $systeminfo['description'], 'inputtime' => $systeminfo['inputtime']), true));
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

    public function delete_content($ids) {
        $this->startTrans();
        if (is_array($ids)) {
            $result = (($this->where(array('id' => array('in', $ids)))->delete()) === false ? fasle : true);
            if ($result) {
                $this->trueTableName = $this->trueTableName.'_data';
                $this->where(array('id' => array('in', $ids)))->delete();
                if (D("PositionData")->where( array("id" => array('in', $ids), "modelid" => $this->modelid) )->delete() === false) {
                    $this->rollback();
                    return false;
                }
                $this->commit();
            } else {
                $this->rollback();
            }
            return $result;
        } else {
            $result = ($this->where(array('id' => $ids))->delete()) === false ? fasle : true;
            if ($result) {
                $this->trueTableName = $this->trueTableName.'_data';
                $this->where(array('id' => $ids))->delete();
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

    /**
    * 设置catid 所在的模型数据库
    *
    * @param $catid
    */
    public function set_catid($catid) {
        $catid = intval($catid);
        if(!$catid) return false;
        if(empty($this->category)) {
            $categorys = D('Category')->select();
            foreach ($categorys as $key => $r) {
                $this->category[$r['id']] = $r;
            }
        }
        if(isset($this->category[$catid]) && $this->category[$catid]['type'] == 1) {
            $modelid = $this->category[$catid]['modelid'];
            $this->set_model($modelid);
        }
    }

    public function parse_field($options) {
        $temp = array();
        foreach ($this->my_fields as $key => $field) {
            if (isset($options[$field])) {
                $temp[$field] = $options[$field];
            }
        }
        return $temp;
    }

    public function set_field() {
        $this->flush();
        $this->my_fields = $this->getDbFields();
    }
}
