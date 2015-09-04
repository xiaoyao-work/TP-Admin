<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;
/**
 * 附件控制器
*/
class UpfileController extends Controller {

  function index() {
    $this->display();
  }

  public function upload() {
    header('Content-Type:text/html;charset=utf-8');
    if (IS_POST) {
      $data = array();
      $upload = new \Think\Upload();  // 实例化上传类
      $site_setting = get_site_setting(get_siteid());
      $upload->maxSize  = $site_setting['upload_maxsize']*1024; // 设置附件上传大小
      $upload->saveRule = "time";  // 文件名设置
      $upload->allowExts  = array_intersect(explode('|', $_POST['filetype_post']), explode('|', $site_setting['upload_allowext'])); // 设置附件上传类型
      $upload->rootPath = UPLOAD_PATH;
      /*if (!is_dir(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH,0777,true);
      }*/

      if (isset($_FILES)) {
        $info = $upload->upload();
        if(!$info) {
          // 上传错误提示错误信息
          $data['status'] = 'error';
          $data['error_info'] = $upload->getError();
        } else {
          // 将附件插入附件表
          $attach_info = array(
            'name' => $info[0]["name"],
            'path' => UPLOAD_PATH . $info[0]["savepath"] . $info[0]["savename"],
            'url' => UPLOAD_URL . $info[0]["savepath"] . $info[0]["savename"],
            'size' => $info[0]['size'],
            'ext'  => $info[0]['ext'],
            'upload_time' => time(),
            'upload_ip' => get_client_ip(),
            );

          if (in_array( $info[0]['ext'], array('jpg','gif','png','jpeg'))) {
            $mine_type = mime_content_type($attach_info['path']);
            $compression_filename = D("Attachment")->gd_compression_image(UPLOAD_PATH . $info[0]["savepath"], $mine_type, $info[0]["savename"]);
            $attach_info['compression_image'] = UPLOAD_PATH . $info[0]["savepath"] . $compression_filename;
            $attach_info['compression_url'] = UPLOAD_URL . $info[0]["savepath"] . $compression_filename;
            $image_source = D("Attachment")->gd_create_image($mine_type, $attach_info['path']);

            if ( $image_source ) {
              $attach_info['width'] = imagesx($image_source);
              $attach_info['height'] = imagesy($image_source);
            }
          }

          if (isset($_SESSION['user_info'])) {
            $attach_info['user_id'] = $_SESSION['user_info']['id'];
            $attach_info['category_id'] = 1;
          }
          if($attachment_id = D("Attachment")->add($attach_info)) {
            $data['attachment_id'] = $attachment_id;
            $data['stutas'] = 'success';
            $info[0]['path'] = $attach_info['url'];
            $data['attachment_info'] = $info[0];
          } else {
            // echo M()->getLastSql();
            $data['status'] = 'error';
            $data['error_info'] = $upload->getError();
          }
        }
      } else {
        $data['stutas'] = 'error';
        $data['error_info'] = '请先选择图片!';
      }
      sleep(1);
      if (isset($_GET['CKEditor'])) {
        $funcNum = $_GET['CKEditorFuncNum'];
        $url = $data['attachment_info']['path'];
        $message = isset($data['error_info']) ? $data['error_info'] : "";
        $this->ajaxReturn("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>", 'EVAL');
      } else {
        $this->ajaxReturn(json_encode($data), 'EVAL');
        // exit(json_encode($data));
      }
    } else {
      $args = $_GET['args'];
      $args = getswfinit($_GET['args']);
      $site_setting = get_site_setting(get_siteid());
      $file_size_limit = sizecount($site_setting['upload_maxsize']*1024);
      $this->assign('file_size_limit',$file_size_limit);
      $this->assign('args',$args);
      $this->display();
    }
  }
}