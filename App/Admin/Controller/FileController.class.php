<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
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
                    // 同步到CDN源

                    // 将附件插入附件表
                    $attach_info = array(
                        'actor_id' => $this->user['id'],
                        'type' => 1,
                        'url' => $attach_info["savepath"] . $attach_info["savename"],
                        'path' => $attach_info["savepath"] . $attach_info["savename"],
                        'name' => $attach_info["savename"],
                        // 'size' => $attach_info['size'],
                        'ext'  => $attach_info['ext'],
                        'ip' => get_client_ip(),
                        'uploaded_at' => date('Y-m-d H:i:s'),
                        );

                    if($attachment_id = model("Attachment")->add($attach_info)) {
                        $attach_info['id'] = $attachment_id;
                        $attach_info['thumbs'] = AttachmentLogic::createThumb($attach_info);
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
            $this->ajaxReturn($result);
        } else {
            $args = $_GET['args'];
            $args = getswfinit($args);
            $site_setting = get_site_setting();
            $file_size_limit = sizecount($site_setting['upload_maxsize']*1024);
            $this->assign('file_size_limit',$file_size_limit);
            $this->assign('args',$args);
            $this->display();
           // abort(404);
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
            $attachment_origin = $result['origin'];
            $attachment = $result['crop'];
            $thumb = $result['thumb'];

            // 等待重构 to-do
            if ($type == 'avatar') {
                model('attachment')->startTrans();
                if (model('attachment')->where(array('id' => $attachment['id']))->save($attachment) === false) {
                    model('attachment')->rollback();
                    // 删除裁剪后的缩略图
                    unlink(UPLOAD_PATH . $thumb['path']);
                    // 删除裁剪后的图
                    unlink(UPLOAD_PATH . $attachment['path']);
                    // 删除原图
                    unlink(UPLOAD_PATH . $attachment_origin['path']);
                    $this->ajaxReturn(array('code' => 10011, 'message' => '附件路径更新失败'));
                }

                if (logic('actor')->updateAvatar($this->user['id'], $thumb['url']) === false) {
                    // 删除裁剪后的缩略图
                    unlink(UPLOAD_PATH . $thumb['path']);
                    // 删除裁剪后的图
                    unlink(UPLOAD_PATH . $attachment['path']);
                    // 删除原图
                    unlink(UPLOAD_PATH . $attachment_origin['path']);
                    model('attachment')->rollback();
                    $this->ajaxReturn(array('code' => 10014, 'message' => '艺人头像更新失败'));
                };
                model('attachment')->commit();
                $attachment['photo_id'] = $photo_id;
                $attachment['thumb'] = $thumb['url'];
            } else {
                unset($attachment['id']);
                model('attachment')->startTrans();
                $attachment['id'] = model('attachment')->add($attachment);
                if ($attachment['id'] === false) {
                    // 删除裁剪后的缩略图
                    unlink(UPLOAD_PATH . $thumb['path']);
                    // 删除裁剪后的图
                    unlink(UPLOAD_PATH . $attachment['path']);
                    // 删除原图
                    unlink(UPLOAD_PATH . $attachment_origin['path']);
                    model('attachment')->rollback();
                    $this->ajaxReturn(array('code' => 10013, 'message' => '保存裁剪后的图片失败'));
                }
                $photo_id = D('photo')->add(array('actor_id' => $this->user['id'], 'url' => $attachment['url'], 'thumb' => $thumb['url'], 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ));
                if ($photo_id === false) {
                    // 删除裁剪后的缩略图
                    unlink(UPLOAD_PATH . $thumb['path']);
                    // 删除裁剪后的图
                    unlink(UPLOAD_PATH . $attachment['path']);
                    // 删除原图
                    unlink(UPLOAD_PATH . $attachment_origin['path']);
                    model('attachment')->rollback();
                    $this->ajaxReturn(array('code' => 60009, 'message' => '才艺发布失败'));
                }

                // 更新艺人写真数目
                if (D('actor')->where(array('id' => $this->user['id']))->save(array('photos' => array('exp', '`photos` + 1'))) === false) {
                    // 删除裁剪后的缩略图
                    unlink(UPLOAD_PATH . $thumb['path']);
                    // 删除裁剪后的图
                    unlink(UPLOAD_PATH . $attachment['path']);
                    // 删除原图
                    unlink(UPLOAD_PATH . $attachment_origin['path']);
                    model('attachment')->rollback();
                    $this->ajaxReturn(array('code' => 60009, 'message' => '写真数目更新失败'));
                };
                model('attachment')->commit();
                $attachment['photo_id'] = $photo_id;
                $attachment['thumb'] = $thumb['url'];
            }
            $this->ajaxReturn(array('code' => 0, 'message' => '', 'data' => $attachment));
        } else {
           abort(404);
        }
    }

}