<?php
namespace Home\Controller;

class PageController extends BaseController {

    public function index() {
        $this->display();
    }

    public function single($slug) {
        if (empty($slug)) {
            abort(404);
        }
        $page = logic('page')->getPage($slug);
        if (empty($page)) {
            abort(404);
        }
        $this->assign('page', $page);
        $template_file = $this->getPageTemplateFile($page['template']);
        $this->display($template_file);
    }

}