<?php
/**
 * 缓存操作
*/
class CacheAction extends CommonAction {
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
    $this->success('缓存清理成功！',__GROUP__.'/Main/index');
  }
}