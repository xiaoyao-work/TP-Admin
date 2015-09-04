<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
/**
 * 站点模型类
*/
class SiteModel extends Model {
    protected $_validate = array();

    function get_by_id($siteid) {
        return siteinfo($siteid);
    }

    function set_cache() {
        $list = $this->select();
        $data = array();
        foreach ($list as $key=>$val) {
            $data[$val['id']] = $val;
            $url_parse = parse_url($val['domain']);
            $data[$val['id']]['url'] = $url_parse['host'];
        }
        $data = "<?php\nreturn ".var_export($data, true).";\n?>";
        $file_size = file_put_contents(CONF_PATH . "sitelist.php", $data, LOCK_EX);
    }
}