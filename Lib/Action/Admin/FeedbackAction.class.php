<?php
/**
 * 用户反馈
*/
class FeedbackAction extends CommonAction {
  private $db;
  function __construct() {
    parent::__construct();
    $this->db = D("Feedback");
  }

  public function index() {
    $feedbacks = $this->db->order("id desc")->page((isset($_GET['p']) ? $_GET['p'] : 0).', 20')->select();
    import("ORG.Util.Page");
    $count = $this->db->count();
    $Page = new Page($count,20);
    $show = $Page->show();
    $this->assign("pages", $show);
    $this->assign("feedbacks", $feedbacks);
    $this->display();
  }

  public function delete() {
    if (IS_POST) {
      $valueid = $_POST['valueid'];
      if ($valueid && is_array($valueid)) {
        if ($this->db->where(array('id' => array('in', $valueid)))->delete() !== false) {
          $this->success('删除成功！');
        } else {
          $this->error('删除失败！');
        }
      } else {
        $this->error("您没有勾选信息");
      }
    } else {
      if ($this->db->where(array('id' => $_GET['valueid']))->delete() !== false) {
        $this->success('删除成功');
      } else {
        $this->error('删除失败');
      }
    }
  }

}
?>