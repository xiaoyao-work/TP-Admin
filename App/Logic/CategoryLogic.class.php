<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
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
        return $list;
    }
}

