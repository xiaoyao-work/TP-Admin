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
 * 缓存操作
*/
class CacheController extends CommonController {
	public function index() {
    $caches = array(
      "HomeCache"	 => array("name" => "网站前台缓存文件", "path" => ROOT_PATH . C("DEFAULT_GROUP")."/Runtime/"),
      "AdminCache" => array("name" => "网站后台缓存文件", "path" => ROOT_PATH . "Admin/Runtime/"),
      );

    foreach ($caches as $val) {
      if (isset($val['path'])) {
        delDirAndFile($val['path']);
      }
    }
    $this->success('缓存清理成功！',__MODULE__.'/Main/index');
  }
}