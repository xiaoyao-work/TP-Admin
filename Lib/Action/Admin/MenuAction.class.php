<?php
/**
 * 后台菜单操作类
 */
class MenuAction extends CommonAction {
  private $db;
  function __construct() {
    parent::__construct();
    $this->db = D("Menu");
  }

  public function index() {
    $nodes = D('Menu')->nodeList();
    $this->assign("menus", $nodes);
    $this->display();
  }

  public function add() {
    if (IS_POST) {
      $this->checkToken();
      if (D("Menu")->addNode() > 0) {
        $this->success('操作成功！', "index");
      } else {
        $this->error('操作失败！');
      }
    } else {
      $this->assign("menus", D("Menu")->nodeList());
      $this->display();
    }
  }

  public function edit() {
    $nid = $_GET['nid'];
    if (IS_POST) {
      $this->checkToken();
      if (D("Menu")->editNode($nid) !== false) {
        $this->success('操作成功！', __GROUP__ . '/Menu/index');
      } else {
        $this->error('操作失败！', __GROUP__ . '/Menu/index');
      }
    } else {
      if (empty($nid)) {
        $this->error('异常操作！', __GROUP__ . '/Menu/index');
      }
      $this->assign('nid', $nid);
      $this->assign("node", D("Menu")->getNode($nid));
      $this->assign("menus", D("Menu")->nodeList());
      $this->display();
    }
  }

  public function del() {
    $nid = intval($_GET['nid']);
    if (empty($nid)) {
      $this->error('异常操作！', __GROUP__ . '/Menu/index');
    }
    $result = D('Menu')->drop_nodes($nid);
    if ( $result ) {
      $this->success('操作成功！', __GROUP__ . '/Menu/index');
    } else {
      $this->error('操作失败！', __GROUP__ . '/Menu/index');
    }
  }

  public function listorder() {
    if (isset($_POST['sort']) && is_array($_POST['sort'])) {
      $sort = $_POST['sort'];
      foreach ($sort as $k => $v) {
        $this->db->where(array('id'=>$k))->save(array('sort'=>$v));
      }
    }
    $this->success('排序成功');
  }
}