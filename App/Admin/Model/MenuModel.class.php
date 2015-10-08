<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

class MenuModel extends Model {
    protected $tableName = 'node';

    public function groupList() {
        $list = $this->where("pid = 0 and status = 1")->order("sort desc")->select();
        return $list;
    }

    public function nodeList() {
        $nodes = $this->order("sort desc")->select();
        $list = list_to_tree($nodes,'id','pid');
        $nodes = array();
        tree_to_array($list,$nodes);
        return $nodes;
    }

    public function addNode() {
        $datas['module'] = trim($_POST['module']);
        $datas['action'] = trim($_POST['action']);
        $datas['title'] = trim($_POST['title']);
        $datas['params'] = trim($_POST['params']);
        $datas['status'] = trim($_POST['status']);
        $datas['remark'] = trim($_POST['remark']);
        $datas['sort'] = trim($_POST['sort']);
        $datas['pid'] = $_POST['pid'];
        return $this->add($datas);
    }

    public function getNode($nid) {
        $node = $this->find($nid);
        return $node;
    }

    public function editNode($nid) {
        $nid = $_POST['nid'];
        $datas['module'] = trim($_POST['module']);
        $datas['action'] = trim($_POST['action']);
        $datas['title'] = trim($_POST['title']);
        $datas['params'] = trim($_POST['params']);
        $datas['status'] = trim($_POST['status']);
        $datas['remark'] = trim($_POST['remark']);
        $datas['sort'] = trim($_POST['sort']);
        $datas['pid'] = $_POST['pid'];
        return $this->where("id = %d", $nid)->save($datas);
    }

    public function delNode($nid) {
        $nid = $_GET['nid'];
        return $this->where("id = %d", $nid)->delete();
    }

    /*
    删除节点和子节点
    */
    public function drop_nodes( $nid ) {
        $childs = $this->where( array( 'pid' => $nid ) )->select();
        $result = $this->where("id = %d", $nid)->delete();
        if ( is_array($childs) && !empty($childs) ) {
              foreach ($childs as $key => $child) {
                $this->drop_nodes( $child['id'] );
            }
        }
        return $result;
    }
}