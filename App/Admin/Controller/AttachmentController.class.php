<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
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
        $this->db = D("Attachment");
    }
    public function index() {
        echo "等待开发中……";
    }

    public function album_list() {
        $search = array();
        if (isset($_GET['search'])) {
            if($_GET['start_time'] && !is_numeric($_GET['start_time'])) {
                $search['_string'] =  "uploadtime >= " . strtotime($_GET['start_time']);
            }
            if($_GET['end_time'] && !is_numeric($_GET['end_time'])) {
                if ( isset($search['_string'])) {
                    $search['_string'] .=  " and uploadtime <= " . strtotime($_GET['end_time']);
                } else {
                    $search['uploadtime'] = array( 'lt',strtotime($_GET['end_time']) );
                }
            }
            if ($_GET['filename']) {
                $search['name'] = array('like', "%".safe_replace($_GET['filename'])."%");
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
            $this->assign('params', explode(',', $_GET['args']));
            $this->display();
        }
    }
}