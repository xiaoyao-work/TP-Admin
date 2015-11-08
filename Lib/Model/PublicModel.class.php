<?php
class PublicModel extends Model {

  public function userInfo() {
    $table_prefix = C("DB_PREFIX");
    $datas = $_POST;
    $M = D('User');
    if ($M->where("`account`='" . $datas['account'] . "'")->count() >= 1)  {
      $sql = "SELECT a.*,b.name,c.* FROM {$table_prefix}user AS a, {$table_prefix}role AS b, {$table_prefix}role_user AS c WHERE a.id = c.user_id AND c.role_id = b.id AND a.account = '{$datas["account"]}'";
      $info = $M->query($sql);
      $_SESSION['user_info'] = $info[0];
    }
  }

}