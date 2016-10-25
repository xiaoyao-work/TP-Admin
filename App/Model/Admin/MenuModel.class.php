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

/**
 * 菜单模型
 */
class MenuModel extends BaseModel {
    protected $tableName = 'node';

    public function getMenus($where, $order, $limit) {
        return $this->where($where)->order($order)->limit($limit)->select();
    }

    public function nodeList() {
        $nodes = $this->order("sort desc")->select();
        $list  = list_to_tree($nodes, 'id', 'pid');
        $nodes = [];
        tree_to_array($list, $nodes);
        return $nodes;
    }
}