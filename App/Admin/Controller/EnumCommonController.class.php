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
 * 公共枚举字段生成器类
 * 提供基本的枚举字段操作功能
*/
class EnumCommonController extends CommonController {
  protected $db, $node;
  function __construct() {
    parent::__construct();
    $this->node = M('Node')->where("id = %d", $_GET['cid'])->find();
    if (isset($_GET['tablename'])) {
      $this->db = D(trim($_GET['tablename']));
    } else {
      $this->db = D($node['module']);
    }
  }

  public function index() {
    $infos = $this->db->select();
    $this->assign('node',$this->node);
    $this->assign('infos',$infos);
    $big_menu = array('javascript:art.dialog.open(\''. U('add',"cid=".$this->node['id']."&".$this->node['params']). '\', { id:\'add\', title:\''.$this->node['title'].'\',  width:\'540px\', height:\'320px\', ok:function(){ var d = this.iframe.contentWindow; var form = d.document.getElementById(\'dosubmit\'); form.click(); return false; }, cancel: true, lock: true});void(0);', '添加'.$this->node['title']);
    $this->assign('big_menu',$big_menu);
    $this->display();
  }

  public function add() {
    if (IS_POST) {
      $this->checkToken();
      $data = $_POST['info'];
      $data['siteid'] = $this->siteid;
      if ($id = $this->db->add($data)) {
        $this->assign('dialog','add');
        $this->success(L('add_success'),HTTP_REFERER);
      } else {
        $this->assign('dialog','add');
        $this->error(L('operation_failure'),HTTP_REFERER);
      }
    } else {
      $this->assign('node',$this->node);
      $this->display();
    }
  }

  public function edit() {
    if (IS_POST) {
      $this->checkToken();
      $data = $_POST['info'];
      if ($this->db->where("id = %d",$_POST['id'])->save($data) !== false) {
        $this->assign('dialog','edit');
        $this->success("更新成功！",HTTP_REFERER);
      } else {
        $this->assign('dialog','edit');
        $this->error("更新失败! ",HTTP_REFERER);
      }
    } else {
      $info = $this->db->find($_GET['id']);
      $this->assign('info', $info);
      $this->assign('node',$this->node);
      $this->display();
    }
  }

  public function delete() {
    if (isset($_POST['ids']) && is_array($_POST['ids'])) {
      if ($this->db->where(array('id' => array('in', $_POST['ids'])))->delete() !== false) {
        $this->success('删除成功！');
      } else {
        $this->error('删除失败！');
      }
    } else {
      if ($this->db->where(array('id' => $_GET['hxid']))->delete() !== false) {
        $this->success('删除成功');
      } else {
        $this->error('删除失败');
      }
    }
  }
}
?>