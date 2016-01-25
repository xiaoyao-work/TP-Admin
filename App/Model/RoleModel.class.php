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
 * 角色模型
 */
class RoleModel extends BaseModel {

    public function roleList() {
        $list = $this->order('id ASC')->select();
        return $list;
    }
}