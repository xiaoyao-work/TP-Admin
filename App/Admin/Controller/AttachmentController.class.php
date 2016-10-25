<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 附件操作
 */
class AttachmentController extends CommonController {
    protected $db;
    function __construct() {
        parent::__construct();
        $this->db = model("Attachment");
    }
    public function index() {
        echo "等待开发中……";
    }

    public function album_list() {
        $search = [];
        if (isset($_GET['search'])) {
            $start_time = I('get.start_time');
            $end_time   = I('get.end_time');
            $filename   = safe_replace(I('get.filename'));
            if ($start_time) {
                $search['_string'] = "uploaded_at >= '{$start_time}'";
            }
            if ($end_time) {
                if (isset($search['_string'])) {
                    $search['_string'] .= " and uploaded_at <= '{$end_time}'";
                } else {
                    $search['uploaded_at'] = ['lt', $end_time];
                }
            }
            if ($filename) {
                $search['name'] = ['like', "%" . $filename . "%"];
            }
        }

        if (isset($_GET['CKEditor'])) {
            $data = $this->db->attachment_list($search, "id desc", '32');
            $this->assign('attachs', $data['data']);
            $this->assign('pages', $data['pages']);
            $this->display("album_for_ckeditor");
        } else {
            $data = $this->db->attachment_list($search);
            $this->assign('attachs', $data['data']);
            $this->assign('pages', $data['pages']);
            $this->assign('params', explode(',', I('get.args')));
            $this->display();
        }
    }
}