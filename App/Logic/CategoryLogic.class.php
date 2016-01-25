<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic;
use Lib\Log;

/**
 * POST 逻辑处理
 */
class CategoryLogic extends BaseLogic {
    protected $siteid;
    function __construct() {
        $this->siteid = get_siteid();
    }

    public function getTerms($post_type, $taxonomy_name) {
        if (empty($taxonomy_name) || empty($post_type)) {
            $this->errorCode = 10001;
            $this->errorMessage = '参数不合法！';
            return false;
        }

        $taxs = model('Category')->getTerms($post_type, $taxonomy_name, $this->siteid);
        $cats = list_to_tree($taxs,'id','parentid');
        $list = array();
        tree_to_array($cats,$list);
        return array_values($list);
    }

    public function getPostTermsGroupByTaxonomy($post_type) {
        $terms = $this->getPostTermsOriginData($post_type);
        if (empty($terms)) {
            return array();
        }
        $termsGroupByTaxonomy = array();
        foreach ($terms as $key => $value) {
            $termsGroupByTaxonomy[$value['taxonomy']][] = $value;
        }
        return $termsGroupByTaxonomy;
    }

    /**
     * 获取原生Terms数据
     * @param  string $post_type 模型表名
     * @return array
     */
    public function getPostTermsOriginData($post_type) {
        $taxonomies = logic('taxonomy')->getPostTaxonomy($post_type);
        if (empty($taxonomies)) {
            return array();
        }
        $taxonomy_names = array();
        foreach ($taxonomies as $key => $value) {
            $taxonomy_names[] = $value['name'];
        }
        if (empty($taxonomy_names)) {
            return array();
        }

        $terms = model('category')->getTerms($post_type, array('in', $taxonomy_names), $this->siteid);
        return $terms;
    }

    /**
     * 获取经过Tree处理的Terms
     * @param  string $post_type 模型表名
     * @return array
     */
    public function getPostTerms($post_type) {
        $terms = $this->getPostTermsOriginData($post_type);
        if (empty($terms)) {
            return array();
        }
        $cats = list_to_tree($terms,'id','parentid');
        $list = array();
        tree_to_array($cats,$list);
        unset($cats);
        unset($terms);
        $temp = array();
        foreach ($list as $key => $value) {
            $temp[$value['taxonomy']][] = $value;
        }
        foreach ($taxonomies as $key => $value) {
            $taxonomies[$key]['terms'] = isset($temp[$value['name']]) ? $temp[$value['name']] : array();
        }
        return $taxonomies;
    }

}

