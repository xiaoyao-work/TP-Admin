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
* 菜单Logic
*/
class MenuLogic extends BaseLogic {
    /**
     * 获取顶部可访问菜单
     * @return array
     */
    public function getAccessibleTopMenu() {
        $menu_model = model('Menu');
        $db_prefix = C('DB_PREFIX');
        if (session(C('ADMIN_AUTH_KEY'))) {
            $top_menu = $menu_model->where(array('pid' => 0, 'status' => 1))->order('sort desc, id asc')->select();
        } else {
            $role_id = session('user_info.role_id');
            $sql = "SELECT * FROM %s AS access, %s AS node where node.pid = 0 AND node.id = access.node_id AND access.role_id = %d AND access.siteid = %d";
            $top_menu = $menu_model->query(sprintf($sql, $db_prefix . 'access', $db_prefix . 'node', $role_id, get_siteid()));
        }
        return $top_menu;
    }

    /**
     * 获取左导航可访问菜单
     * @return array
     */
    public function getAccessibleLeftMenu($pid) {
        $menu_model = model('Menu');
        $models = model('model')->where(array('siteid' => get_siteid()))->field('tablename')->select();
        $post_type = array();
        foreach ($models as $key => $value) {
            $post_type[] = $value['tablename'];
        }
        if (session(C('ADMIN_AUTH_KEY'))) {
            $menulist = $menu_model->where(array('pid' => $pid, 'status' => 1))->order('sort desc, id asc')->select();
        } else {
            $role_id = session('user_info.role_id');
            $menulist = $menu_model->table('__ACCESS__ as access, __NODE__ as node')
            ->where(array(
                'node.pid' => $pid,
                'node.id' => array('exp', ' = access.node_id'),
                'access.role_id' => $role_id,
                'access.siteid' => get_siteid()
                ))
            ->order('node.sort desc, node.id asc')
            ->select();
        }
        // 过滤不属于当前站点的POST TYPE 菜单
        foreach ($menulist as $key => $value) {
            if (empty($value['post_type']) || in_array($value['post_type'], $post_type)) {
                continue ;
            }
            unset($menulist[$key]);
        }
        foreach ($menulist as $key => $value) {
            if (session(C('ADMIN_AUTH_KEY'))) {
                $childs = $menu_model->where(array('pid' => $value['id'], 'status' => 1))->order('sort desc')->select();
            } else {
                $childs = $menu_model->table('__ACCESS__ as access, __NODE__ as node')
                ->where(array(
                    'node.pid' => $value['id'],
                    'node.id' => array('exp', ' = access.node_id'),
                    'access.role_id' => $role_id,
                    'access.siteid' => get_siteid()
                    ))
                ->order('node.sort desc, node.id asc')
                ->select();
            }
            $menulist[$key]['childs'] = $childs;
        }
        return $menulist;
    }

    /**
     * 删除节点和子节点
     */
    public function dropNodes($nid) {
        $model = model('Menu');
        $childs = $model->where( array( 'pid' => $nid ) )->select();
        $result = $model->where("id = %d", $nid)->delete();
        if ( is_array($childs) && !empty($childs) ) {
              foreach ($childs as $key => $child) {
                $this->dropNodes( $child['id'] );
            }
        }
        return $result;
    }

}