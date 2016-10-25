<?php
namespace Logic\Home;
use Logic\BaseLogic;

/**
 * 页面逻辑
 */
class PageLogic extends BaseLogic {

    public function getPage($slug_or_id) {
        $page_model = model('page');
        if (is_numeric($slug_or_id)) {
            $page = $page_model->find($slug_or_id);
        } else {
            $page = $page_model->where(['slug' => $slug_or_id])->find();
        }
        return $page;
    }
}