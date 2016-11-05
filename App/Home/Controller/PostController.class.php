<?php
namespace Home\Controller;

class PostController extends BaseController {

    public function index($post_type = 'news') {
        if (empty($post_type)) {
            abort(404);
        }
        $post_logic = logic('Post');
        $model      = $post_logic->setModel($post_type);
        if ($model['type'] == 2) {
            $parent_model = $post_logic->getParentModel();
            $this->assign('parent_post_type', $parent_model['tablename']);
        }
        $this->assign('post_type', $post_type);
        /*$posts = $post_logic->getPosts(array('siteid' => $this->siteid, 'post_type' => $post_type), true, 'listorder desc');
        $this->assign('posts', $posts);*/
        $this->assign('siteid', $this->siteid);
        $template_file = $this->getTemplateFile($post_type);
        $this->display($template_file);
    }

    public function single($post_type, $id) {
        if (empty($post_type) || empty($id)) {
            abort(404);
        }
        $post_logic = logic('Post');
        $model      = $post_logic->setModel($post_type);
        if ($model['type'] == 2) {
            $parent_model = $post_logic->getParentModel();
            $this->assign('parent_post_type', $parent_model['tablename']);
        }
        $post = $post_logic->getPost($id);
        if (empty($post)) {
            abort(404);
        }
        $this->assign('post', $post);
        $post_template_file = $this->getTemplateFile($post_type, 'post');
        $this->display($post_template_file);
    }

    public function taxonomy($name, $id) {
        $term = model('category', 'Admin')->getTerm($id, $this->siteid);
        if (empty($term)) {
            abort(404);
        }
        $post_logic = logic('Post');

        $posts      = $post_logic->getPostByTerm($id, ['recusion' => true, 'pagenum' => I('get.p', 1), 'siteid' => $this->siteid]);
        $taxonomy   = logic('taxonomy', 'Admin')->getTaxonomy($term['post_type'], $term['taxonomy']);
        $this->assign('taxonomy', $taxonomy);
        $this->assign('post_type', $term['post_type']);
        $this->assign('term', $term);
        $this->assign('posts', $posts);

        $template_file = $this->getTemplateFile($term['taxonomy'], 'taxonomy');
        $this->display($template_file);
    }

    public function search() {
        $post_type = I('get.post_type');
        $keyword   = I('get.keyword');
        if (empty($post_type) || empty($keyword)) {
            $this->display();
        }
        $post_logic = logic('Post');
        $model      = $post_logic->setModel($post_type);
        $posts      = $post_logic->getPosts(['siteid' => $this->siteid, 'post_type' => $post_type, 'title' => ['like', '%' . $keyword . '%']], true, 'listorder desc');
        $this->assign('posts', $posts);
        $this->assign('keyword', $keyword);
        $this->display();
    }
}