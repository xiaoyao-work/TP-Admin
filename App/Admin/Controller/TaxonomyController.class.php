<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Controller\CommonController;

class TaxonomyController extends CommonController {

    public function index() {
        $taxonomy_group = logic('taxonomy')->getTaxonomies();
        $this->assign('taxonomy_group', $taxonomy_group);
        $this->display();
    }

    public function register() {
        if (IS_POST) {
            $taxonomy = I('post.info');
            if (logic('taxonomy')->registerTaxonomy($taxonomy) !== false) {
                $this->success('添加成功！');
            } else {
                $this->error(logic('taxonomy')->getErrorMessage());
            }
        } else {
            $post_types = model('model')->select();
            $this->assign('post_types', $post_types);
            $this->display();
        }
    }

    public function delete() {
        $post_type = I('get.post_type', '');
        $taxonomy_name = I('get.taxonomy_name');
        if (logic('taxonomy')->deleteTaxonomy($post_type, $taxonomy_name) !== false) {
            $this->success('操作成功！');
        } else {
            $this->error(logic('taxonomy')->getErrorMessage());
        }
    }

}