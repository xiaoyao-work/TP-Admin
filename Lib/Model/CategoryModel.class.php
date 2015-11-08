<?php
class CategoryModel extends Model
{
  public function cat_list() {
    $list = $this->where("siteid = %d", get_siteid())->order('arrparentid asc, listorder desc, id asc')->select();
    return $list;
  }

  static public function wxj_category($list,$cat_id=0,$format="<option %s>%s</option>") {
    $select = "";
    foreach ($list as $key => $value) {
      $empty = "";
      for ($i=1; $i < $value[level]; $i++) {
        $empty .= '&nbsp;&nbsp;';
      }
      $select .= sprintf($format, "value='" . $value['id'] . "' " . ($cat_id == $value['id'] ? 'selected' : ''), $empty.'├─'.$value['catname']);
      if ($value['_child']) {
        $select .= CategoryModel::wxj_category($value['_child'],$cat_id);
      }
    }
    return $select;
  }
}
