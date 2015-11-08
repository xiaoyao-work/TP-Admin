<?php

class MenuModel extends Model {

  protected $tableName = 'node';

  public function accessList() {
    $sql = "SELECT a.*,b.id as b_id,b.title as b_title,b.name as b_name,b.sort as b_sort,b.status as b_stutas FROM
    ".C("DB_PREFIX")."node AS a,".C("DB_PREFIX")."group AS b WHERE a.level = 2 AND a.group_id = b.id AND a.status = 1 ORDER BY b.id ASC,a.sort desc";
    $list = $this->query($sql);
    return $list;
  }

  public function groupList() {
    $list = $this->where("pid = 0 and status = 1")->order("sort desc")->select();
    return $list;
  }

  public function nodeList() {
    load("extend");
    $nodes = $this->order("sort desc")->select();
    $list = list_to_tree($nodes,'id','pid');
    $nodes = array();
    tree_to_array($list,$nodes);
    return $nodes;
  }

  public function addNode() {
    $datas['module'] = trim($_POST['module']);
    $datas['action'] = trim($_POST['action']);
    $datas['title'] = trim($_POST['title']);
    $datas['params'] = trim($_POST['params']);
    $datas['status'] = trim($_POST['status']);
    $datas['remark'] = trim($_POST['remark']);
    $datas['sort'] = trim($_POST['sort']);
    $datas['pid'] = $_POST['pid'];
    return $this->add($datas);
  }

  public function getNode($nid) {
    $node = $this->find($nid);
    return $node;
  }

  public function editNode($nid) {
    $nid = $_POST['nid'];
    $datas['module'] = trim($_POST['module']);
    $datas['action'] = trim($_POST['action']);
    $datas['title'] = trim($_POST['title']);
    $datas['params'] = trim($_POST['params']);
    $datas['status'] = trim($_POST['status']);
    $datas['remark'] = trim($_POST['remark']);
    $datas['sort'] = trim($_POST['sort']);
    $datas['pid'] = $_POST['pid'];
    return $this->where("id = %d", $nid)->save($datas);
  }

  public function delNode($nid) {
    $nid = $_GET['nid'];
    return $this->where("id = %d", $nid)->delete();
  }

  /*
  删除节点和子节点
  */
  public function drop_nodes( $nid ) {
    $childs = $this->where( array( 'pid' => $nid ) )->select();
    $result = $this->where("id = %d", $nid)->delete();
    if ( is_array($childs) && !empty($childs) ) {
      foreach ($childs as $key => $child) {
        $this->drop_nodes( $child['id'] );
      }
    }
    return $result;
  }
}
?>