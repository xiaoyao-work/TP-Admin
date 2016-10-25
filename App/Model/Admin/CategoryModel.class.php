<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Model\Admin;
use Model\BaseModel;

class CategoryModel extends BaseModel {

    public function getTerm($id, $siteid) {
        $term = $this->find($id);
        if (empty($term) || $term['siteid'] != $siteid) {
            return [];
        }
        return $term;
    }

    public function getTerms($post_type, $taxonomy_name, $siteid, $level = 2) {
        $where = [
            'siteid'    => $siteid,
            'post_type' => $post_type,
            'taxonomy'  => $taxonomy_name,
        ];
        if ($level > 0) {
            $where['level'] = ['elt', $level];
        }
        $taxs = $this->where($where)
            ->order('listorder desc, id asc')
            ->select();
        return $taxs;
    }

    static public function wxj_category($list, $cat_id = 0, $format = "<option %s>%s</option>") {
        $select = "";
        foreach ($list as $key => $value) {
            $empty = "";
            for ($i = 1; $i < $value[level]; $i++) {
                $empty .= '&nbsp;&nbsp;';
            }
            $select .= sprintf($format, "value='" . $value['id'] . "' " . ($cat_id == $value['id'] ? 'selected' : ''), $empty . '├─' . $value['catname']);
            if ($value['_child']) {
                $select .= self::wxj_category($value['_child'], $cat_id);
            }
        }
        return $select;
    }

    public function getTermsRecusion($term_id, $siteid) {
        $first_term_id = is_array($term_id) ? current($term_id) : $term_id;
        $term          = $this->getTerm($first_term_id, $siteid);
        if (empty($term)) {
            return $term_id;
        }
        $similar_terms  = $this->getTerms($term['post_type'], $term['taxonomy'], $siteid, 0);
        $similar_terms  = array_key_translate($similar_terms, 'id');
        $recusion_terms = [];
        if (is_array($term_id)) {
            foreach ($term_id as $key => $value) {
                $recusion_terms += $this->_getTermsRecusion($similar_terms, $value);
            }
        } else {
            $recusion_terms = $this->_getTermsRecusion($similar_terms, $term_id);
        }
        return $recusion_terms;
    }

    private function _getTermsRecusion($terms, $term_id) {
        $temp = [$term_id];
        if (isset($terms[$term_id]) && $terms[$term_id]['child']) {
            foreach ($terms as $key => $value) {
                if ($value['parentid'] == $term_id) {
                    $temp = array_merge($temp, $this->_getTermsRecusion($terms, $value['id']));
                }
            }
        }
        return $temp;
    }
}
