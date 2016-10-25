<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic\Admin;
use Logic\BaseLogic;

/**
 * POST 逻辑处理
 */
class CategoryLogic extends BaseLogic {
    protected $siteid;
    function __construct() {
        $this->siteid = get_siteid();
    }

    public function getTerm($id, $siteid = '') {
        $siteid = empty($siteid) ? $this->siteid : $siteid;
        return model('Category', 'Admin')->getTerm($id, $this->siteid);
    }

    public function getTerms($post_type, $taxonomy_name, $siteid = '') {
        if (empty($taxonomy_name) || empty($post_type)) {
            $this->errorCode    = 10001;
            $this->errorMessage = '参数不合法！';
            return false;
        }
        $siteid = empty($siteid) ? $this->siteid : $siteid;
        $taxs   = model('Category', 'Admin')->getTerms($post_type, $taxonomy_name, $this->siteid);
        $cats   = list_to_tree($taxs, 'id', 'parentid');
        $list   = [];
        tree_to_array($cats, $list);
        return array_values($list);
    }

    public function getPostTermsGroupByTaxonomy($post_type) {
        $terms = $this->getPostTermsOriginData($post_type);
        if (empty($terms)) {
            return [];
        }
        $termsGroupByTaxonomy = [];
        foreach ($terms as $key => $value) {
            $termsGroupByTaxonomy[$value['taxonomy']][] = $value;
        }
        return $termsGroupByTaxonomy;
    }

    /**
     * 获取原生Terms数据
     * @param  string  $post_type 模型表名
     * @return array
     */
    public function getPostTermsOriginData($post_type) {
        $taxonomies = logic('taxonomy', 'Admin')->getPostTaxonomy($post_type);
        if (empty($taxonomies)) {
            return [];
        }
        $taxonomy_names = [];
        foreach ($taxonomies as $key => $value) {
            $taxonomy_names[] = $value['name'];
        }
        if (empty($taxonomy_names)) {
            return [];
        }
        $terms = model('category', 'Admin')->getTerms(['in', $post_type], ['in', $taxonomy_names], $this->siteid);
        return $terms;
    }

}
