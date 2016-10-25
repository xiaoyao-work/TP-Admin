<?php
namespace Logic\Home;
use Logic\BaseLogic;

/**
 * POST逻辑
 */
class PostLogic extends BaseLogic {
    protected $db;
    protected $parentDb;
    protected $model;
    protected $parentModel;

    public function setModel($post_type) {
        $model = model('Model')->where(['tablename' => $post_type])->find();
        if (empty($model)) {
            return false;
        }
        $this->model = $model;
        $this->db    = model($model['tablename']);
        if ($model['type'] == 2) {
            $parentModel = model('Model')->where(['id' => $model['parentid']])->find();
            if (empty($parentModel)) {
                return false;
            }
            $this->parentModel = $parentModel;
            $this->parentDb    = model($parentModel['tablename']);
        } else {
            $this->parentModel = '';
            $this->parentDb    = '';
        }
        return $model;
    }

    public function getPost($id) {
        $post = $this->db->find($id);
        if ($this->model['type'] == 2) {
            $post_parent_data = $this->parentDb->find($id);
            $post             = array_merge($post_parent_data, $post);
        }
        return $post;
    }

    public function getPosts($where = [], $fields = true, $order = 'id desc', $limit = 20) {
        $model   = $this->model['type'] == 2 ? $this->parentDb : $this->db;
        $pagenum = I('get.p', 1);
        // 查询条件
        if (!isset($where['siteid'])) {
            $where['siteid'] = get_siteid();
        }
        if (!isset($where['status'])) {
            $where['status'] = 99;
        }
        $posts = $model->where($where)->field($fields)->order($order)->page($pagenum . ', ' . $limit)->select();
        // 分页数据
        $count     = $model->where($where)->count();
        $page      = new \Think\Page($count, $limit);
        $page_html = $page->bootcssPager();
        return ['data' => $posts, 'page' => $page_html, 'total' => $count];
    }

    public function getPostByTerm($term_id, $params = [], $is_page = true) {
        $default_params = [
            'recusion' => false,
            'limit'    => 20,
            'pagenum'  => 1,
            'order'    => 'id desc',
            'siteid'   => get_siteid(),
        ];
        $params = array_merge($default_params, $params);
        extract($params);
        if ($recusion) {
            $term_id = model('Category', 'Admin')->getTermsRecusion($term_id, $siteid);
        }
        if (is_array($term_id)) {
            $where = ['term_id' => ['in', $term_id]];
        } else {
            $where = ['term_id' => $term_id];
        }
        return $this->_getPostByTerms($term_id, ['limit' => $limit, 'order' => $order, 'pagenum' => $pagenum], $is_page);
    }

    private function _getPostByTerms($term_id, $params = [], $is_page = true) {
        $default_params = [
            'limit'   => 20,
            'pagenum' => 1,
            'order'   => 'id desc',
        ];
        $params = array_merge($default_params, $params);
        extract($params);
        $category_posts = model('categoryPosts')->where(['term_id' => ['in', $term_id]])->order($order)->page($pagenum . ', ' . $limit)->select();
        $temp           = [];
        foreach ($category_posts as $key => $value) {
            $temp[$value['post_type']][] = $value['post_id'];
        }
        $posts = [];
        foreach ($temp as $key => $value) {
            if ($this->setModel($key) === false) {
                continue;
            }

            $post_type_posts = $this->getPostsAll(['id' => ['in', $value]]);
            $posts           = array_merge($posts, $post_type_posts);
        }
        if (!$is_page) {
            return $posts;
        }
        $count     = model('categoryPosts')->where(['term_id' => ['in', $term_id]])->count();
        $page      = new \Think\Page($count, $limit);
        $page_html = $page->bootcssPager();
        return ['data' => $posts, 'page' => $page_html];
    }

    public function getPostByTermAndPostType($term_id, $post_type, $limit = 20) {
        if ($this->setModel($post_type) === false) {
            return ['data' => [], 'page_html' => ''];
        }

        if (is_array($term_id)) {
            $where = ['term_id' => ['in', $term_id]];
        } else {
            $where = ['term_id' => $term_id];
        }
        $where['post_type'] = $post_type;
        $category_posts     = model('categoryPosts')->where($where)->order('id desc')->page($pagenum . ', ' . $limit)->select();
        if (empty($categoryPosts)) {
            return ['data' => [], 'page_html' => ''];
        }
        $post_ids = [];
        foreach ($category_posts as $key => $value) {
            $post_ids[] = $value['id'];
        }
        $count     = model('categoryPosts')->where($where)->count();
        $page      = new \Think\Page($count, $limit);
        $page_html = $page->bootcssPager();
        $posts     = $this->getPostsAll(['id' => ['in', $post_ids]]);
        return ['data' => $posts, 'page_html' => $page_html];
    }

    public function getParentModel() {
        return $this->parentModel;
    }

    public function getModel() {
        return $this->Model;
    }

    /**
     * 获取所有符合条件的Posts，不分页，不区分站点
     * @param  array   $where          [description]
     * @param  boolean $fields         [description]
     * @param  string  $order          [description]
     * @return [type]  [description]
     */
    public function getPostsAll($where = [], $fields = true, $order = '') {
        $model = $this->model['type'] == 2 ? $this->parentDb : $this->db;
        // 缓存查询条件
        $model = $model->where($where)->field($fields);
        if (!empty($order)) {
            $model = $model->order($order);
        }
        return $model->select();
    }

}