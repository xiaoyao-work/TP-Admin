<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Model;
use Think\Page as Page;

/**
*  附件模型类
*/
class AttachmentModel extends BaseModel{

    public function attachment_list($where=array(),$order='id desc',$limit=8) {
        $attachs = $this->where(array_merge(array('siteid' => get_siteid()),$where))->order($order)->page((isset($_GET['p']) ? $_GET['p'] : 0).', '.$limit)->select();
        $count      = $this->where(array_merge(array('siteid' => get_siteid()),$where))->count();
        $Page       = new Page($count,$limit);
        $show       = $Page->show();
        return array("data" => $attachs, "pages" => $show);
    }
}