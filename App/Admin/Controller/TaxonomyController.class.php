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
    public function index() {
        $fields = array('name' => '名称', 'description' => '描述');
        $taxonomies = D('Taxonomy')->getList();
        $this->assign('fields', $fields);
        $this->assign('contents', $taxonomies['data']);
        $this->assign('pages', $taxonomies['page']);
        $this->display("Public:list_layout");
    }

    public function add() {
        $this->assign();
    }
}