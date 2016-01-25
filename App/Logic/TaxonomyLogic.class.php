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
* 类别Logic
*/
class TaxonomyLogic extends BaseLogic {
    protected $siteid;
    function __construct() {
        $this->siteid = get_siteid();
    }

    public function getTaxonomies() {
        $file = COMMON_PATH . 'Cache/' . 'taxonomy-' . $this->siteid  . '.php';
        return file_exists($file) ? (require $file) : array();
    }

    public function setTaxonomies($taxonomies) {
        $taxonomies = '<?php return ' . var_export($taxonomies, true) . "; ?>";
        $file_name = 'taxonomy-' . $this->siteid  . '.php';
        file_put_contents(COMMON_PATH . 'Cache/' . $file_name,   $taxonomies);
    }

    public function getTaxonomy($post_type, $taxonomy_name) {
        $taxonomies = $this->getTaxonomies();
        return isset($taxonomies[$post_type][$taxonomy_name]) ? $taxonomies[$post_type][$taxonomy_name] : false;
    }

    public function getPostTaxonomy($post_type) {
        $taxonomies = $this->getTaxonomies();
        return isset($taxonomies[$post_type]) ? $taxonomies[$post_type] : array();
    }

    public function registerTaxonomy($taxonomy) {
        if (empty($taxonomy['post_type']) || empty($taxonomy) || !is_array($taxonomy) || empty($taxonomy['name'])) {
            $this->errorCode = 10001;
            $this->errorMessage = '参数不合法！';
            return false;
        }
        $taxonomy['label'] = $taxonomy['label'] ? : $taxonomy['name'];
        $taxonomy['menu_name'] = $taxonomy['menu_name'] ? : $taxonomy['name'];
        $taxonomies = $this->getTaxonomies();
        if (isset($taxonomies[$taxonomy['post_type']][$taxonomy['name']])) {
            $this->errorCode = 30001;
            $this->errorMessage = '分类已存在！';
            return false;
        }

        $this->addTaxonomyMenu($taxonomy);

        $taxonomies[$taxonomy['post_type']][$taxonomy['name']] = $taxonomy;
        $this->setTaxonomies($taxonomies);
        return true;
    }

    public function addTaxonomyMenu($taxonomy) {
        $post_type_menu = model('menu')->where(array('post_type' => $taxonomy['post_type']))->find();
        // 分类自动生成菜单
        $taxonomy_menu = array(
            'request_method' => 0,
            'pid' => $post_type_menu['id'],
            'title' => $taxonomy['menu_name'],
            'module' => 'Category',
            'action' => 'index',
            'params' => http_build_query(array('post_type' => $taxonomy['post_type'], 'taxonomy_name' => $taxonomy['name'])),
            'sort' => 0,
            'status' => 1,
            );
        return model('menu')->add($taxonomy_menu);
    }

    public function deleteTaxonomyMenu($post_type, $taxonomy_name) {
        $params = http_build_query(array('post_type' => $post_type, 'taxonomy_name' => $taxonomy_name));
        if (!empty($params)) {
            return model('menu')->where(array('params' => $params))->delete();
        } else {
            return false;
        }
    }

    public function deleteTaxonomy($post_type, $taxonomy_name) {
        if (empty($post_type) || empty($taxonomy_name)) {
            $this->errorCode = 10001;
            $this->errorMessage = '参数不合法！';
            return false;
        }

        $taxonomies = $this->getTaxonomies();
        if (!isset($taxonomies[$post_type][$taxonomy_name])) {
            $this->errorCode = 30002;
            $this->errorMessage = '分类不已存在！';
            return false;
        }

        $taxonomy = $taxonomies[$post_type][$taxonomy_name];

        if ($this->deleteTaxonomyMenu($post_type, $taxonomy_name) !== false && $this->removeTaxItems($post_type, $taxonomy_name)) {
            unset($taxonomies[$post_type][$taxonomy_name]);
        } else {
            $this->errorCode = 30003;
            $this->errorMessage = '分类移除失败！';
            return false;
        }

        $this->setTaxonomies($taxonomies);
        return true;
    }

    /**
     * 删除所有Items 及 Item Posts
     * @param  [type] $post_type     [description]
     * @param  [type] $taxonomy_name [description]
     * @return [type]                [description]
     */
    public function removeTaxItems($post_type, $taxonomy_name) {
        return true;
    }

}