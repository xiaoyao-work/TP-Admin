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
 *  联动菜单
*/
class LinkageController extends CommonController {
  protected $db;
  function __construct() {
    parent::__construct();
    $this->db = D("Linkage");
  }

  public function index() {
    $menus = $this->db->where( array('keyid' => 0, 'siteid' => array( 'IN', array( '0', $this->siteid ) ) ) )->order('listorder asc, id asc')->select();
    $big_menu = array('javascript:art.dialog.open(\''. U('add_top_menu'). '\', { id:\'add\', title:\'添加联动菜单\',  width:\'540px\', height:\'320px\', ok:function(){ var d = this.iframe.contentWindow; var form = d.document.getElementById(\'dosubmit\'); form.click(); return false; }, cancel: true, lock: true});void(0);', '添加联动菜单');
    $this->assign('menus', $menus);
    $this->assign('big_menu', $big_menu);
    $this->display();
  }

  public function show_sub_menu() {
    $parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
    $keyid = $_GET['keyid'];
    $menus = $this->db->where(array('parentid' => $parentid, 'keyid' => array( 'NEQ' , 0 ), 'keyid' => $keyid, 'siteid' => array( 'IN', array( '0', $this->siteid ) ) ))->order('listorder asc, id asc')->select();
      // $big_menu = array('javascript:art.dialog.open(\''. U('add','parentid=0'). '\', { id:\'add\', title:\'添加联动菜单\',  width:\'540px\', height:\'320px\', ok:function(){ var d = this.iframe.contentWindow; var form = d.document.getElementById(\'dosubmit\'); form.click(); return false; }, cancel: true, lock: true});void(0);', '添加联动菜单');
    $this->assign('menus', $menus);
      // $this->assign('big_menu', $big_menu);
    $this->display();
  }

  public function add() {
    if (IS_POST) {
      $this->checkToken();
      $names = explode("\n", $_POST['info']['name']);
      $data['description'] = $_POST['info']['description'];
      $data['child'] = 0;
      $data['parentid'] = intval($_REQUEST['parentid']);
      $data['keyid'] = intval($_REQUEST['keyid']);
      $data['siteid'] = $this->siteid;
      foreach ($names as $key => $name) {
        $data['name'] = $name;
        $linkageid = $this->db->add($data);
        $this->db->where("siteid = %d and id = %d",$this->siteid,$linkageid)->save(array('arrchildid' => $linkageid));
        if ($data['parentid']) {
          $data_parent['child'] = 1;
          $data_parent['arrchildid'] = $parentcat['arrchildid'] . "," . $linkageid;
          $this->db->where("siteid = %d and id = %d",$this->siteid,$data['parentid'])->save($data_parent);
        }
      }
      $this->assign('dialog','add');
      $this->success('添加成功');
    } else {
      if (intval($_GET['parentid']) == 0) {
        $linkage = array('name' => '作为一级菜单', 'id' => 0, 'keyid' => $_GET['keyid'] );
      } else {
        $linkage = $this->db->where(array('id' => intval($_GET['parentid'])))->find();
        if (!$linkage) {
          $linkage = array('name' => '作为一级菜单', 'id' => 0, 'keyid' => $_GET['keyid'] );
        }
      }
      $this->assign('linkage', $linkage);
      $this->display();
    }
  }

  public function add_top_menu() {
    if (IS_POST) {
      $this->checkToken();
      $data = $_POST['info'];
      $data['siteid'] = $this->siteid;
      $linkageid = $this->db->add($data);
      $this->assign('dialog','add');
      $this->success('添加成功');
    } else {
      $this->display();
    }
  }

  public function edit() {
    if (IS_POST) {
      $this->checkToken();
      $data = $_POST['info'];
      if($this->db->where(array('id' => intval($_POST['linkageid'])))->save($data) !== false ) {
        $this->assign('dialog','add');
        $this->success('操作成功');
      } else {
        $this->assign('dialog','add');
        $this->error('操作失败');
      }
    } else {
      $linkage = $this->db->where(array('id' => intval($_GET['id'])))->find();
      if (!$linkage) {
        $this->error('该信息不存在');
      }
      $this->assign('linkage', $linkage);
      $this->display();
    }

  }

  public function listorder() {
    if (isset($_POST['listorder']) && is_array($_POST['listorder'])) {
      $listorder = $_POST['listorder'];
      foreach ($listorder as $k => $v) {
        $this->db->where(array('id'=>$k))->save(array('listorder'=>$v));
      }
    }
    $this->success('操作成功');
  }

  public function delete() {
    $this->delete_operate($_GET['id']);
    $this->success('操作成功');
  }

  public function delete_top() {
    $this->db->where( array( 'id' => intval($_GET['id']) ) )->delete();
    $this->db->where( array( 'keyid' => intval($_GET['id']) ) )->delete();
    $this->success('操作成功');
  }

  public function ajax_linkage_select() {
    $parentid = intval($_GET['parentid']);
    if ($parentid > 0) {
      $menus = $this->db->where(array('parentid' => $parentid))->order('listorder asc, id asc')->select();
      $menus = array_translate($menus);
      $options = "";
      foreach ($menus as $key => $value) {
        $options .= "<option value='".$key."'>".$value."</option>";
      }
      echo $options;
    }
  }

  /**
   * 字段添加=>联动菜单
   */
  public function public_get_list() {
    $menus = $this->db->where( array('keyid' => 0, 'siteid' => array( 'IN', array( '0', $this->siteid ) ) ) )->order('listorder asc, id asc')->select();
    $this->assign('infos', $menus);
    $this->display();
  }

  private function delete_operate($id) {
    $linkages = $this->db->where(array('parentid' => $id))->select();
    foreach ($linkages as $key => $linkage) {
      if ($linkage['child']) {
        $this->delete_operate($linkage['id']);
      } else {
        $this->db->where(array('id' => $linkage['id']))->delete();
        continue;
      }
    }
    $this->db->where(array('id' => $id))->delete();
  }
}