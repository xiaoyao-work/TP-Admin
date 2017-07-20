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

/**
 * 站点模型
 */
class SiteModel extends BaseModel {
    // protected $connection = 'store';

    public function single($siteid) {
        return siteinfo($siteid);
    }

    public function getAccessibleSites() {
        $table_prefix = C('store.DB_PREFIX');
        $sites = $this->query('select site.* from ' . $table_prefix . 'site as site join ' . $table_prefix . 'access as access on site.id = access.siteid where access.role_id = ' . session('user_info.role_id') . ' and node_id not in (' . join([59, 58, 5, 312]) . ') group by site.id');
        return $sites;
    }

}