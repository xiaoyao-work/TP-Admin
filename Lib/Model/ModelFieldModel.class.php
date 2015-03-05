<?php
  /**
  * 模型字段Model
  */
  class ModelFieldModel extends Model {
    public function get_fields($tablename) {
      $variable = $this->query("SHOW COLUMNS FROM %s", $tablename);
      $fields = array();
      foreach ($variable as $key => $r) {
        $fields[$r['Field']] = $r['Type'];
      }
      return $fields;
    }
    /**
     * 删除字段
     * 
     */
    public function drop_field($tablename,$field) {
      $fields = $this->get_fields($tablename);
      if(array_key_exists($field, $fields)) {
        return $this->db->query("ALTER TABLE `$tablename` DROP `$field`;");
      } else {
        return false;
      }
    }

  }
  ?>