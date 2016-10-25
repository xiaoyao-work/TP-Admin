<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic\Admin;
use Lib\Log;
use Logic\BaseLogic;

/**
 * 类别Logic
 */
class TaxonomyLogic extends BaseLogic {
    protected $siteid;
    function __construct() {
        $this->siteid = get_siteid();
        // $this->siteid = 1;
    }

    public function getTaxonomies($post_type = '') {
        /* 区分站点 */
        /*
        static $taxonomies = [];
        if (isset($taxonomies['taxonomy-' . $this->siteid])) {
            $site_taxonomies = $taxonomies['taxonomy-' . $this->siteid];
        } else {
            $site_taxonomies = F('taxonomy-' . $this->siteid);
            if (empty($site_taxonomies)) {
                $site_taxonomies = $this->setTaxonomies();
            }
            $taxonomies['taxonomy-' . $this->siteid] = $site_taxonomies;
        }
        return empty($post_type) ? $site_taxonomies : (isset($site_taxonomies[$post_type]) ? $site_taxonomies[$post_type] : []); */
        /* 不区分站点 */
        $taxonomies = S('taxonomies');
        if (empty($taxonomies)) {
            $taxonomies = $this->setTaxonomies();
        }
        return empty($post_type) ? $taxonomies : (isset($taxonomies[$post_type]) ? $taxonomies[$post_type] : []);
    }

    public function setTaxonomies() {
        /* 区分站点 */
        /* $taxonomies = model('taxonomy', 'Admin')->where(['siteid' => $this->siteid])->select();
        $temp       = [];
        if (is_array($taxonomies)) {
            foreach ($taxonomies as $key => $taxonomy) {
                $temp[$taxonomy['post_type']][$taxonomy['name']] = $taxonomy;
            }
        }
        F('taxonomy-' . $this->siteid, $temp);
        return $temp; */
        /* 不区分站点 */
        $taxonomies = model('taxonomy', 'Admin')->select();
        $temp       = [];
        if (is_array($taxonomies)) {
            foreach ($taxonomies as $key => $taxonomy) {
                $temp[$taxonomy['post_type']][$taxonomy['name']] = $taxonomy;
            }
        }
        S('taxonomies', $temp);
        return $temp;
    }

    public function getTaxonomy($post_type, $taxonomy_name) {
        $taxonomies = $this->getTaxonomies();
        return isset($taxonomies[$post_type][$taxonomy_name]) ? $taxonomies[$post_type][$taxonomy_name] : false;
    }

    public function getPostTaxonomy($post_types) {
        $taxonomies = $this->getTaxonomies();
        if (!is_array($post_types)) {
            $post_types = [$post_types];
        }
        $post_taxonomies = [];
        foreach ($post_types as $key => $post_type) {
            if (!empty($post_type) && isset($taxonomies[$post_type])) {
                $post_taxonomies = array_merge($post_taxonomies, $taxonomies[$post_type]);
            }
        }
        return $post_taxonomies;
    }

    public function registerTaxonomy($taxonomy) {
        if (!is_array($taxonomy) || empty($taxonomy['post_type']) || empty($taxonomy['name'])) {
            $this->errorCode    = 10001;
            $this->errorMessage = '参数不合法！';
            return false;
        }
        $taxonomy['label']     = $taxonomy['label'] ?: $taxonomy['name'];
        $taxonomy['menu_name'] = $taxonomy['menu_name'] ?: $taxonomy['name'];
        if (model('Taxonomy', 'Admin')->where(['post_type' => $taxonomy['post_type'], 'name' => $taxonomy['name']])->find()) {
            $this->errorCode    = 30001;
            $this->errorMessage = '分类已存在！';
            return false;
        }
        if (model('taxonomy')->add(['siteid' => get_siteid(), 'post_type' => $taxonomy['post_type'], 'label' => $taxonomy['label'], 'name' => $taxonomy['name']]) === false) {
            return false;
        };
        if ($this->addTaxonomyMenu($taxonomy) === false) {
            return false;
        };
        // $taxonomies[$taxonomy['post_type']][$taxonomy['name']] = $taxonomy;
        $this->setTaxonomies();
        return true;
    }

    public function addTaxonomyMenu($taxonomy) {
        $post_type_menu = model('menu', 'Admin')->where(['post_type' => $taxonomy['post_type']])->find();
        if (empty($post_type_menu)) {
            $model = model('model', 'Admin')->where(['tablename' => $taxonomy['post_type']])->find();
            if (empty($model) || $model['type'] != 1) {
                return false;
            }
            $child_model    = model('model', 'Admin')->where(['parentid' => $model['id']])->select();
            $taxonomy_menus = [];
            foreach ($child_model as $key => $value) {
                $post_type_menu = model('menu', 'Admin')->where(['post_type' => $value['tablename']])->find();
                if (empty($post_type_menu)) {
                    continue;
                }
                $taxonomy_menus[] = [
                    'request_method' => 0,
                    'pid'            => $post_type_menu['id'],
                    'title'          => $taxonomy['menu_name'],
                    'module'         => 'Category',
                    'action'         => 'index',
                    'params'         => http_build_query(['post_type' => $taxonomy['post_type'], 'taxonomy_name' => $taxonomy['name']]),
                    'sort'           => 0,
                    'status'         => 1,
                ];
            }
            if (empty($taxonomy_menus)) {
                return true;
            }
            return model('menu', 'Admin')->addAll($taxonomy_menus);
        } else {
            // 分类自动生成菜单
            $taxonomy_menu = [
                'request_method' => 0,
                'pid'            => $post_type_menu['id'],
                'title'          => $taxonomy['menu_name'],
                'module'         => 'Category',
                'action'         => 'index',
                'params'         => http_build_query(['post_type' => $taxonomy['post_type'], 'taxonomy_name' => $taxonomy['name']]),
                'sort'           => 0,
                'status'         => 1,
            ];
            return model('menu', 'Admin')->add($taxonomy_menu);
        }
    }

    public function deleteTaxonomyMenu($post_type, $taxonomy_name) {
        $params = http_build_query(['post_type' => $post_type, 'taxonomy_name' => $taxonomy_name]);
        if (!empty($params)) {
            return model('menu', 'Admin')->where(['params' => $params])->delete();
        } else {
            return false;
        }
    }

    public function deleteTaxonomy($post_type, $taxonomy_name) {
        if (empty($post_type) || empty($taxonomy_name)) {
            $this->errorCode    = 10001;
            $this->errorMessage = '参数不合法！';
            return false;
        }

        $taxonomies = $this->getTaxonomies();
        if (!isset($taxonomies[$post_type][$taxonomy_name])) {
            $this->errorCode    = 30002;
            $this->errorMessage = '分类不已存在！';
            return false;
        }

        $taxonomy = $taxonomies[$post_type][$taxonomy_name];

        if ($this->deleteTaxonomyMenu($post_type, $taxonomy_name) !== false && $this->removeTaxAndItems($post_type, $taxonomy_name) !== false) {
            unset($taxonomies[$post_type][$taxonomy_name]);
        } else {
            $this->errorCode    = 30003;
            $this->errorMessage = '分类移除失败！';
            return false;
        }

        $this->setTaxonomies();
        return true;
    }

    /**
     * 删除所有Items 及 Item Posts
     * @param  [type] $post_type      [description]
     * @param  [type] $taxonomy_name  [description]
     * @return [type] [description]
     */
    public function removeTaxAndItems($post_type, $taxonomy_name) {
        return model('taxonomy', 'Admin')->where(['post_type' => $post_type, 'name' => $taxonomy_name])->delete();
    }

}