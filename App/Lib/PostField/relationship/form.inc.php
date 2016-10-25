<?php
$field_setting = string2array($fieldinfo['setting']);
$models = model('Model')->where(array('siteid' => get_siteid(), 'id' => array('in', $field_setting['model'])))->select();
$model_num = count($models);
$value = empty($value) ? array() : json_decode($value, true);
$form_text = '';
if (!defined('RELATIONSHIP_STATIC')) {
    define('RELATIONSHIP_STATIC', true);
    $style = '<link rel="stylesheet" type="text/css" href="'.asset('css/admin/postfield.css').'">';
    $script = '<script type="text/javascript" src="'.asset('js/admin/postfield.js').'"></script>';
}
$form_text .= '<div class="col-tab">' .
    '<ul class="tabBut cu-li">';
        foreach ($models as $key => $model) {
        $form_text .= '<li id="tab_setting_'.($key +1).'" class="' . ($key == 0 ? 'on' : '') . '" onclick="SwapTab(\'setting\',\'on\',\'\', '. $model_num. ' ,' . ($key + 1) . ');">'. $model['name'] .'</li>';
        }
    $form_text .= '</ul>';
    foreach ($models as $key => $model) {
    $form_text .= '<div id="div_setting_' . ($key + 1) . '" class="contentList pad-10 field ' . ($key == 0 ? '' : 'hidden') . '">' .
        '<div class="cfs_relationship">' .
            '<div class="filter_posts">' .
                '<input type="text" class="cfs_filter_input" autocomplete="off" placeholder="搜索文章...">' .
                '<input type="hidden" class="model_id" value="'. $model['id'] .'" />' .
            '</div>' .
            '<div class="available_posts post_list">' .
            '</div>' .
            '<div class="selected_posts post_list">' .
            '</div>' .
        '</div>' .
        '<input type="hidden" name="info['.$field.']['. $model['tablename'] .']" class="relationship" value="' . (isset($value[$model['tablename']]) ? $value[$model['tablename']] : '') . '">' .
        '</div>';
    }
$form_text .= '</div><script type="text/javascript">window.post_get_posts="' . U('Post/getPosts') . '?ms=' . time() . '";</script>';
return $style . $form_text . $script;