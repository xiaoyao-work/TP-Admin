<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
use Think\Page as Page;

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
        $count      = $this->where(array_merge(array('siteid' => get_siteid()),$where))->count();
        $Page       = new Page($count,$limit);
        $show       = $Page->show();
        return array("data" => $attachs, "pages" => $show);
    }

    static public function gd_compression_image($real_path, $mine_type, $filename, $quality=80) {
        $compression_filename = "compression-" . $filename;
        $image = self::gd_create_image( $mine_type, $real_path );
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