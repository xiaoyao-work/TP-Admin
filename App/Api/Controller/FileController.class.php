<?php
namespace Api\Controller;
use Logic\AttachmentLogic;

class FileController extends BaseController {

    public function upload() {
        if (IS_POST) {
            $result = array('code' => 0, 'message' => '');
            $options = array();
            if (I('post.fileTypeExts')) {
                $options['exts'] = str_replace('|', ',', I('post.fileTypeExts'));
            }
            if (I('post.fileSizeLimit')) {
                $options['maxSize'] = size2int(I('post.fileSizeLimit'));
            }
            $logic = logic('Attachment', 'Admin');
            $data = $logic->upload($options);
            if ($data == false) {
                $result['code'] = $logic->getErrorCode();
                $result['message'] = $logic->getErrorMessage();
            } else {
                $result['data'] = $data;
            }

            $type = I('get.type', 'file');
            switch ($type) {
                case 'ck_image':
                    $funcNum = $_GET['CKEditorFuncNum'];
                    $url = IMAGE_DOMAIN . $data['url'];
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
        } else {
            abort(404);
        }
    }

    public function crop() {
        if (IS_POST) {
            $src_x = I('x');
            $src_y = I('y');
            // 裁剪区域宽度
            $width = I('width');
            // 裁剪区域高度
            $height = I('height');
            $attachment_id = I('attachment_id');

            $attachment_logic = logic('Attachment', 'Admin');
            $result = $attachment_logic->crop($src_x, $src_y, $width, $height, $attachment_id);
            if ($result === false) {
                $this->ajaxReturn(array('code' => $attachment_logic->getErrorCode(), 'message' => $attachment_logic->getErrorMessage()));
            }
            $attachment = $result['crop'];

            $thumbs_size = I('thumbs_size');
            if (!empty($thumbs_size)) {
                $attachment['thumbs'] = $attachment_logic->thumb($attachment['path'], $thumbs_size);
            }
            $this->ajaxReturn(array('code' => 0, 'message' => '', 'data' => $attachment));
        } else {
            http_response_code(404);
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('code' => 404, 'message' => 'system error!')));
        }
    }

}