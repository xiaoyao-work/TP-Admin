<?php
  /**
  * 楼盘 Model
  */
  class HouseModel extends Model {
    public function house_list($where=array(), $order = "id desc") {
      $houses = $this->join( "{$this->trueTableName}_data as data On {$this->trueTableName}.id = data.id" )->where( array_merge(array('siteid' => get_siteid()),$where) )->order($order)->page((isset($_GET['p']) ? $_GET['p'] : 0).',20')->field("{$this->trueTableName}.* ,data.relation, data.album, data.huxing, data.intent")->select();
      import("ORG.Util.Page");
      // 查询满足要求的总记录数
      $count      = $this->where(array_merge(array('siteid' => get_siteid()),$where))->count();
      // 实例化分页类 传入总记录数和每页显示的记录数
      $Page       = new Page($count,20);
      // 分页显示输出
      $show       = $Page->show();
      return array("data" => $houses, "page" => $show);
    }

    public function mobile_content_list($where=array(), $order = "id desc", $limit=10, $page_params = array()) {
      $page_num = isset($_GET['p']) ? $_GET['p'] : 1;
      $houses = $this->where(array_merge(array('siteid' => get_siteid()),$where))->order($order)->page($page_num .', '.$limit)->select();

      /* 分页 */
      // 导入分页类
      import("ORG.Util.Page");
      // 查询满足要求的总记录数
      $count = $this->where(array_merge(array('siteid' => get_siteid()),$where))->count();
      // 实例化分页类 传入总记录数和每页显示的记录数
      $Page = new Page($count,$limit);
      if ($page_params) {
        foreach ($page_params as $key => $param) {
          $Page->setConfig($key, $param);
        }
      }
      // 分页显示输出
      $show = $Page->show();
      $next_page_num = ceil($count/$limit) - $page_num;

      return array('status' => 'success', 'message' => $houses, 'next_page_num' => $next_page_num, 'finished' => $finished, 'pages' => $show);
    }

    public function get_house($id) {
      $this->translate2main_table();
      $r = $this->where(array('id'=>$id))->find();
      $this->translate2data_table();
      $r2 = $this->where(array('id'=>$id))->find();
      if(!$r2) showmessage(L('subsidiary_table_datalost'),'blank');
      $data = array_merge($r,$r2);
      return $data;
    }

    public function translate2data_table() {
      if ( !strpos($this->trueTableName, '_data') ) {
        $this->trueTableName = $this->trueTableName . "_data";
      // $this->set_field();
      }
    }

    public function translate2main_table() {
      if ( strpos($this->trueTableName, '_data') ) {
        $this->trueTableName = substr($this->trueTableName, 0, -5);
      // $this->set_field();
      }
    }

    public function delete_house($ids) {
      if (is_array($ids)) {
        $result = (($this->where(array('id' => array('in', $ids)))->delete()) === false ? fasle : true);
        if ($result) {
          $this->trueTableName = $this->trueTableName.'_data';
          $this->where(array('id' => array('in', $ids)))->delete();
          D("PositionData")->where( array("id" => array('in', $ids), "module" => "House" ) )->delete();
        }
        return $result;
      } else {
        $result = ($this->where(array('id' => $ids))->delete()) === false ? fasle : true;
        if ($result) {
          $this->trueTableName = $this->trueTableName.'_data';
          $this->where(array('id' => $ids))->delete();
          D("PositionData")->where( array("id" => $ids, "module" => "House" ) )->delete();
        }
        return $result;
      }

    }

    public function set_field() {
      $fields = $this->query("DESC ".$this->trueTableName);
      $this->my_fields = array();
      foreach ($fields as $key => $value) {
        $this->my_fields[$key] = $value['Field'];
      }
    }
  }
  ?>