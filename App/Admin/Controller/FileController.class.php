<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Logic\AttachmentLogic;
use Lib\Log;

class FileController extends CommonController {

    public function upload() {
        if (IS_POST) {
            $result = array('code' => 0, 'message' => '');
            $data = array();
            if (isset($_FILES)) {
                $upload = new \Think\Upload(array(
                    'maxSize' =>  C('IMAGE_UPLOAD_LIMIT'),
                    'exts' => C('ALLOW_EXTS'),
                    'rootPath' => UPLOAD_PATH,
                    'savePath' => '',
                ));
                $info = $upload->upload();
                if(!$info) {
                    // 上传错误提示错误信息
                    $result['code'] = 10016;
                    $result['message'] = $upload->getError();
                } else {
                    $attach_info = current($info);
                    $attach_info = array(
                        'title' => $attach_info['name'],
                        'siteid' => $this->siteid,
                        'url'   => $attach_info["savepath"] . $attach_info["savename"],
                        'path'  => $attach_info["savepath"] . $attach_info["savename"],
                        'name'  => $attach_info["savename"],
                        'size'  => $attach_info['size'],
                        'ext'   => $attach_info['ext'],
                        'ip'    => get_client_ip(),
                        'uploaded_at' => date('Y-m-d H:i:s'),
                        );
                    $thumbs = AttachmentLogic::createThumb($attach_info);
                    $attach_info['thumbs'] = json_encode($thumbs);
                    // 将附件插入附件表
                    if($attachment_id = model("Attachment")->add($attach_info)) {
                        $attach_info['id'] = $attachment_id;
                        $attach_info['thumbs'] = json_decode($attach_info['thumbs']);
                        $result['data'] = $attach_info;
                    } else {
                        $result['code'] = 10014;
                        $result['message'] = '附件保存失败!';
                    }
                }
            } else {
                $result['code'] = 10015;
                $result['message'] = '请先选择图片!';
            }
            $type = I('get.type', 'file');
            switch ($type) {
                case 'ck_image':
                    $funcNum = $_GET['CKEditorFuncNum'];
                    $url = IMAGE_DOMAIN . $attach_info['url'];
                    $message = $result['message'];
                    $this->ajaxReturn("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>", 'EVAL');
                    break;
                case 'ck_drag':
                    if ($result['code'] === 0) {
                        $ck_drag_res = array('uploaded' => 1, 'fileName' => $result['data']['title'], 'url' => IMAGE_DOMAIN . $result['data']['url']);
                    } else {
                        $ck_drag_res = array('uploaded' => 1, 'error' => array('message' => $result['message']));
                    }
                    $this->ajaxReturn($ck_drag_res);
                    break;
                case 'file':
                default:
                    $this->ajaxReturn($result);
                    break;
            }
            if (isset($_GET['CKEditor'])) {

            } else {
            }
        } else {
            $args = $_GET['args'];
            $args = getUploadParams($args);
            $this->assign('args',$args);
            $this->display();
        }
    }

    public function crop() {
        if (IS_POST) {
            $src_x = I('src_x');
            $src_y = I('src_y');
            // 裁剪区域宽度
            $width = I('w');
            // 裁剪区域高度
            $height = I('h');
            $type = I('type');
            // 保存图像宽度
            $save_width = I('width', $width);
            // 保存图片高度
            $save_height = I('height', $height);
            $attachment_id = I('attachment_id');
            $attachment_logic = logic('Attachment');
            $result = $attachment_logic->crop($src_x, $src_y, $width, $height, $save_width, $save_height, $attachment_id);
            if ($result === false) {
                $this->ajaxReturn(array('code' => $attachment_logic->getErrorCode(), 'message' => $attachment_logic->getErrorMessage()));
            }

            $attachment = $result['crop'];
            $attachment['thumb'] = $result['thumb']['url'];

            $this->ajaxReturn(array('code' => 0, 'message' => '', 'data' => $attachment));
        } else {
           abort(404);
        }
    }

}