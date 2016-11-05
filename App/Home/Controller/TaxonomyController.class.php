<?php
namespace Home\Controller;

class TaxonomyController extends BaseController {

    public function getTerms() {
        $post_type = I('post.post_type');
        $taxonomy  = I('post.taxonomy');
        if (empty($post_type) || empty($taxonomy)) {
            $this->ajaxReturn(['code' => 10001, 'message' => '参数异常']);
        }
        $terms = model('Category', 'Admin')->getTerms($taxonomy, $this->siteid);
        $tree  = new \Org\Util\Tree();
        $tree->init($terms);
        $terms_html = $tree->get_tree_ul(0);
        $this->ajaxReturn(['code' => 0, 'message' => '', 'data' => $terms_html]);
    }
}