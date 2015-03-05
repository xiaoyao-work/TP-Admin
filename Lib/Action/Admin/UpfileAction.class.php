<?php
class UpfileAction extends Action {

  function index() {
    $this->display();
  }

  public function upload() {
    header('Content-Type:text/html;charset=utf-8');
    if (IS_POST) {
      import("ORG.Net.UploadFile");
      $savefolder =  '/uploads/' . date("Y/m/d")."/";
      $path = UPLOAD_PATH . $savefolder;
      $upload = new UploadFile();  // 实例化上传类
      $site_setting = get_site_setting(get_siteid());
      $upload->maxSize  = $site_setting['upload_maxsize']*1024; // 设置附件上传大小
      $upload->saveRule = "time";  // 文件名设置
      $upload->allowExts  = array_intersect(explode('|', $_POST['filetype_post']), explode('|', $site_setting['upload_allowext'])); // 设置附件上传类型
      $upload->uploadReplace = $overflow;
      if (!is_dir($path)) {
        mkdir($path,0777,true);
      }
      $data = array();
      $upload->savePath =  $path;
      if (isset($_FILES)) {
        if(!$upload->upload()) {
          // 上传错误提示错误信息
          $data['status'] = 'error';
          $data['error_info'] = $upload->getErrorMsg();
        } else {
          // 上传成功 获取上传文件信息
          $info =  $upload->getUploadFileInfo();
          // 将附件插入附件表
          $attach_info = array(
            'name' => $info[0]["name"],
            'path' => $savefolder.$info[0]["savename"],
            'url' => UPLOAD_URL . $savefolder.$info[0]["savename"],
            'size' => $info[0]['size'],
            'ext'  => $info[0]['extension'],
            'upload_time' => time(),
            'upload_ip' => get_client_ip(),
            );


          if (in_array( $info[0]['extension'], array('jpg','gif','png','jpeg'))) {
            $mine_type = mime_content_type( $path . $info[0]["savename"] );
            $compression_filename = D("Attachment")->gd_compression_image( $path, $mine_type, $info[0]["savename"] );
            $attach_info['compression_image'] = $savefolder . $compression_filename;

            $attachment['compression_url'] = UPLOAD_URL . $savefolder . $compression_filename;

            $image_source = D("Attachment")->gd_create_image( $mine_type, $path.$info[0]["savename"] );

            if ( $image_source ) {
              $attach_info['width'] = imagesx($image_source);
              $attach_info['height'] = imagesy($image_source);
            }
          }

          if (isset($_SESSION['user_info'])) {
            $attach_info['userid'] = $_SESSION['user_info']['id'];
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
            $data['error_info'] = $upload->getErrorMsg();
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
        exit("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>");
      } else {
        exit(json_encode($data));
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
?>

