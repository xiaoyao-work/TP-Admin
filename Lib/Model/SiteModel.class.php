<?php
  /**
  * 站点模型类
  */
  class SiteModel extends Model {
    protected $_validate = array();

    function get_by_id($siteid) {
      return siteinfo($siteid);
    }

    function set_cache() {
      $list = $this->select();
      $data = array();
      foreach ($list as $key=>$val) {
        $data[$val['id']] = $val;
        $url_parse = parse_url($val['domain']);
        $data[$val['id']]['url'] = $url_parse['host'];
      }
      $data = "<?php\nreturn ".var_export($data, true).";\n?>";
      $file_size = file_put_contents(CONF_PATH."sitelist.php", $data, LOCK_EX);
      // S('sitelist', $data);
    }
  }
?>