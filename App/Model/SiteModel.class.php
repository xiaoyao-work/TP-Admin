<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------
namespace Model;

/**
 * 站点模型
 */
class SiteModel extends BaseModel {

    function get_by_id($siteid) {
        return siteinfo($siteid);
    }

}