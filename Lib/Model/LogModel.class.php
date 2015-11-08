<?php
class LogModel extends Model {
  public function log_list($where="1=1") {
    $logs = $this->where($where)->page((isset($_GET['p']) ? $_GET['p'] : 0).',25')->order('id desc')->select();
    import("ORG.Util.Page");// 导入分页类
    $count      = $this->where($where)->count();// 查询满足要求的总记录数
    $Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
    $show       = $Page->show();// 分页显示输出
    return array("data" => $logs, "page" => $show);
  }

  public function addLog( $status = 1 ) {
    $data = array();
    $data['ip']    = get_client_ip();
    $data['date']  = date("Y-m-d H:i:s");
    $data['username'] = $_SESSION['user_info']['account'];
    $data['module'] = MODULE_NAME;
    $data['action'] = ACTION_NAME;
    $data['querystring'] = U( MODULE_NAME . '/' . ACTION_NAME );
    $data['status'] = $status;
    $data['userid'] = $_SESSION['user_info']['user_id'];
    return $this->add($data);
  }

  public function loginLog( $status = 1 ) {

  }

}

?>
