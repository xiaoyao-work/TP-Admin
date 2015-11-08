<?php
  /**
   *  附件模型类
   */
  class AttachmentModel extends Model{
    public static function attachment_type($type) {
      switch ($type) {
        case 'video':
        return array('mp3','flv','mp4');
        break;
        case 'swf':
        return array('swf');
        break;
        case 'image':
        default:
        return array('jpg', 'gif', 'png', 'jpeg');
        break;
      }
    }

    public function attachment_list($where=array(),$order='id desc',$limit=8) {
      $attachs = $this->where(array_merge(array('siteid' => get_siteid()),$where))->order($order)->page((isset($_GET['p']) ? $_GET['p'] : 0).', '.$limit)->select();
      import("ORG.Util.Page");// 导入分页类
      $count      = $this->where(array_merge(array('siteid' => get_siteid()),$where))->count();// 查询满足要求的总记录数
      $Page       = new Page($count,$limit);// 实例化分页类 传入总记录数和每页显示的记录数
      $show       = $Page->show();// 分页显示输出
      return array("data" => $attachs, "pages" => $show);
    }

    static public function gd_compression_image( $real_path, $mine_type, $filename, $quality=80 ) {
      $original_file = $real_path . $filename;
      $compression_filename = "compression-" . $filename;
      $image = self::gd_create_image( $mine_type, $original_file );
      if ( isset($image) ) {
        if ( imagejpeg( $image, $real_path . $compression_filename , $quality ) )  return $compression_filename;
      }
      return $filename;
    }

    static public function gd_create_image( $mine_type, $file_path ) {
      switch ($mine_type) {
        case 'image/gif':
        $image = imagecreatefromgif( $file_path );
        break;
        case 'image/jpeg':
        $image = imagecreatefromjpeg( $file_path );
        break;
        case 'image/png':
        $image = imagecreatefrompng( $file_path );
        break;
        default:
        return false;
        break;
      }
      return $image;
    }

  }
  ?>