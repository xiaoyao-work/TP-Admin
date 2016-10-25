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
use Think\Cache as Cache;

/**
 * 日志操作模型
 */
class LinkageModel extends BaseModel {
    protected $tableName     = 'linkage';
    protected $cache_key_pre = 'linkage_';

    public function getLinkage($id) {
        $cache_obj = Cache::getInstance();
        $linkage   = $cache_obj->get($this->cache_key_pre . $id);
        if (!empty($linkage)) {
            return $linkage;
        } else {
            $linkage = $this->_getLinkage($id);
            $linkage = array_key_translate($linkage);
            $cache_obj->set($this->cache_key_pre . $id, $linkage);
            return $linkage;
        }
    }

    private function _getLinkage($id) {
        return $this->where(['keyid' => $id])->select();
    }
}