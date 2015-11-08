<?php
/**
* 推荐位模型
*/
class PositionModel extends Model {
  protected $autoCheckFields = false;

  public function get_position_data($posid, $order = "posdata.listorder desc, posdata.id desc", $field = "posdata.*", $limit=10) {
    $data = $this->table( array(C('DB_PREFIX')."position_data" => "posdata", C('DB_PREFIX')."position" => "pos") )->where("pos.id = posdata.posid and pos.id = %d", $posid)->order($order)->limit($limit)->field($field)->select();
    return $data;
  }

  public function dropByPosid( $posid ) {
    return $this->where(array( 'posid' => $posid ))->delete();
  }

  // 获取模型推荐位
  function getPositionCheckbox( $model, $id = NULL, $type = 'house' ) {
    import("ORG.Util.Form");
    // 载入推荐位
    $positions = $this->table( array(C('DB_PREFIX')."model_type" => "type", C('DB_PREFIX')."position" => "pos") )->where( "type.id = pos.typeid and type.module = '%s'", $model )->order( "pos.listorder desc" )->field( 'pos.*' )->select();
    $position_array = array_translate($positions);

    if ( !empty($id) ) {
      // 获取选中推荐位
      $posids = D('PositionData')->where( array( 'type' => $type, 'module' => $model, 'id' => $id ) )->group("posid")->field('posid')->select();
    }

    $position_data_ids = array();
    foreach ($posids as $key => $pos) {
      $position_data_ids[] = $pos['posid'];
    }
    $posids = implode(',', $position_data_ids);

    $posidstr = form::checkbox($position_array, $posids, "name='info[posids][]'", '', 125);
    // END 推荐位载入
    return $posidstr;
  }

}