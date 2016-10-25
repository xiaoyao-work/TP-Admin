<?php

function get_posts($post_type, $where=array(), $fields=true, $order='id desc', $limit=20) {
    return logic('post')->getPosts($where, $fields, $order, $limit);
}

function get_post_by_term($term_id, $limit=20) {
    return logic('post')->getPostByTerm($term_id, array('limit' => 20));
}

function get_post_by_term_and_post_type($term_id, $post_type, $limit=20) {
    return logic('post')->getPostByTermAndPostType($term_id, $post_type, $limit);
}

function get_post_type_taxonomies($post_type) {
    return logic('taxonomy', 'Admin')->getPostTaxonomy($post_type);
}

function get_taxonomy_terms($post_type, $taxonomy, $siteid='') {
    return logic('category', 'Admin')->getTerms($post_type, $taxonomy, $siteid);
}

function get_terms_menu($post_type, $taxonomy, $siteid='', $default='', $level=2, $sub_menu_class='sub-menu', $has_child_class="menu-item-has-children") {
    if (empty($siteid)) {
        $siteid = get_siteid();
    }
    $terms = get_terms($post_type, $taxonomy, $siteid, $level);
    $tree = new \Org\Util\Tree();
    $tree->init($terms);
    $terms_html = $tree->get_tree_ul(0, $sub_menu_class, $has_child_class, $default);
    return $terms_html;
}

function get_terms($post_type, $taxonomy, $siteid, $level=1) {
    return model('Category', 'Admin')->getTerms($post_type, $taxonomy, $siteid, $level);
}

function get_term_posts($term_id, $recusion=false, $order='id desc', $limit=10, $is_page=false, $siteid='') {
    $params = array('recusion' => $recusion, 'limit' => $limit);
    if (!empty($siteid)) { $params['siteid'] = $siteid; }
    return logic('Post')->getPostByTerm($term_id, $params, $is_page);
}

function get_relation_posts($relation, $group=true) {
    if (empty($relation)) {
        return array();
    }
    $posts = array();
    $relation = is_array($relation) ? $relation : json_decode($relation, true);
    foreach ($relation as $post_type => $postids) {
        if (empty($postids)) {
            if ($group) {
                $posts[$post_type] = array();
            }
            continue;
        }
        $post_logic = logic('Post');
        if ($post_logic->setModel($post_type) === false) {
            if ($group) {
                $posts[$post_type] = array();
            }
            continue;
        }
        $post_type_posts = $post_logic->getPostsAll(array('id' => array('in', $postids)));
        if ($group) {
            $posts[$post_type] = $post_type_posts;
        } else {
            $posts = array_merge($posts, $post_type_posts);
        }
    }
    return $posts;
}

function get_around_community($community_id, $region) {
    $around_community = logic('community', 'Home')->getAroundCommunity($community_id, $region);
    return $around_community ? $around_community : array();
}