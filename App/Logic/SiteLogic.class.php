<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic;
use Lib\Log;

/**
* 站点Logic
*/
class SiteLogic extends BaseLogic {
    public function getAccessibleSites() {
        $site_model = model('site');
        if (session(C('ADMIN_AUTH_KEY'))) {
            $sites = $site_model->select();
        } else {
            $sites = $site_model->table('__ACCESS__ as access, __SITE__ as site' )
                ->where(array(
                    'access.siteid' => array('exp', ' = site.id'),
                    'access.role_id' => session('user_info.role_id'),
                    'access.node_id' => array('not in', array(59, 58, 5, 312))
                    ))
                ->group('access.siteid')
                ->select();
        }
        return $sites;
    }


    public function setCache() {
        $sites = model('site')->select();
        $data = array();
        foreach ($sites as $key=>$val) {
            $data[$val['id']] = $val;
            $url_parse = parse_url($val['domain']);
            $data[$val['id']]['url'] = $url_parse['host'];
        }
        $data = "<?php\nreturn ".var_export($data, true).";\n?>";
        $file_size = file_put_contents(COMMON_PATH . "Cache/sitelist.php", $data, LOCK_EX);
    }

}