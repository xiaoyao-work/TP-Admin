<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic\Admin;
use Lib\Log;
use Logic\BaseLogic;

/**
 * 附件
 * @author 李志亮 <lizhiliang@kankan.com>
 */
class AttachmentLogic extends BaseLogic {
    static private $thumbs = [
        ['w' => 100, 'h' => 90],
        ['w' => 80, 'h' => 44],
    ];

    /**
     * 图片缩略
     * @author 李志亮 <lizhiliang@kankan.com>
     *
     * @param  array $attach_info 附件信息
     * @return array / boolean 缩略图集合或者false
     */
    static public function createThumb($attach_info) {
        $attach_info['path'] = preg_replace('/\\/|\\\\/', DIRECTORY_SEPARATOR, $attach_info["path"]);
        try {
            $thumbs = [];
            $image  = new \Think\Image(1, UPLOAD_PATH . $attach_info['path']);
            foreach (static::$thumbs as $key => $thumb) {
                $thumb_url  = str_replace('.' . $attach_info['ext'], $thumb['w'] . 'x' . $thumb['h'] . '.' . $attach_info['ext'], $attach_info['url']);
                $thumb_path = UPLOAD_PATH . str_replace('.' . $attach_info['ext'], $thumb['w'] . 'x' . $thumb['h'] . '.' . $attach_info['ext'], $attach_info['path']);
                $image->thumb($thumb['w'], $thumb['h'], \Think\Image::IMAGE_THUMB_FILLED)->save($thumb_path);
                $thumbs[$thumb['w'] . 'x' . $thumb['h']] = $thumb_url;
            }
            return $thumbs;
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
            Log::info('缩略图生成失败; ' . json_encode($attach_info) . '; ' . $e->getMessage());
        }
    }

    /**
     * 图片裁剪
     * @author 李志亮 <lizhiliang@kankan.com>
     *
     * @param  int   $src_x            原图裁剪点的x坐标
     * @param  int   $src_y            原图裁剪点的y坐标
     * @param  int   $width            裁剪宽度
     * @param  int   $height           裁剪高度
     * @param  int   $attachment_id    裁剪附件ID
     * @return array 裁剪后图片
     */
    public function crop(int $src_x, int $src_y, int $width, int $height, int $attachment_id) {
        if (empty($width) || empty($height) || empty($attachment_id)) {
            $this->errorCode    = 10001;
            $this->errorMessage = '参数不合法';
            return false;
        }
        $attachment_model  = model('Attachment', 'Admin');
        $attachment_origin = $attachment = $attachment_model->find($attachment_id);
        if (empty($attachment) || !file_exists(UPLOAD_PATH . $attachment['path'])) {
            $this->errorCode    = 10010;
            $this->errorMessage = '附件不存在';
            return false;
        }
        try {
            // 图片裁剪处理
            $image           = new \Think\Image(1, UPLOAD_PATH . $attachment['path']);
            $name            = uniqid() . '.' . $attachment['ext'];
            $crop_image_path = str_replace($attachment['name'], $name, $attachment['path']);
            $image->crop($width, $height, $src_x, $src_y)->save(UPLOAD_PATH . $crop_image_path);
            $attachment['url']  = str_replace($attachment['name'], $name, $attachment['url']);
            $attachment['path'] = $crop_image_path;
            $attachment['name'] = $name;
            return ['origin' => $attachment_origin, 'crop' => $attachment];
        } catch (Exception $e) {
            $this->errorCode    = 10012;
            $this->errorMessage = '图片裁剪失败';
            return false;
        }
    }

    public function thumb($type = 3) {
        $args_num = func_num_args();
        $args     = func_get_args();
        if ($args_num < 2 || ($args_num == 2 && !is_array($args[1]))) {
            $this->errorCode    = 10001;
            $this->errorMessage = '参数不合法';
            return false;
        }
        $source_path          = $args[0];
        $absolute_source_path = UPLOAD_PATH . $source_path;
        if (!is_file($absolute_source_path)) {
            return false;
        }
        $thumbs = [];
        if (!is_array($args[1])) {
            $args[1] = [['width' => $args[1], 'height' => $args[2]]];
        }
        try {
            // 缩略图处理
            $thumb_obj = new \Think\Image(1, $absolute_source_path);
            foreach ($args[1] as $key => $value) {
                $source_path_info = pathinfo($source_path);
                $save_path        = $source_path_info['dirname'] . '/' . $source_path_info['filename'] . '-' . $value['width'] . 'x' . $value['height'] . '.' . $source_path_info['extension'];
                if (!is_file(UPLOAD_PATH . $save_path)) {
                    $thumb_obj->thumb($value['width'], $value['height'], $type)->save(UPLOAD_PATH . $save_path);
                }
                $thumbs[$value['width'] . 'x' . $value['height']] = $save_path;
            }
        } catch (Exception $e) {
            $this->errorCode    = 10013;
            $this->errorMessage = '缩略图生成失败';
            return false;
        }
        return $thumbs;
    }

    public function upload($options = []) {
        if (isset($_FILES)) {
            $options = array_merge([
                'maxSize'  => C('IMAGE_UPLOAD_LIMIT'),
                'exts'     => C('ALLOW_EXTS'),
                'rootPath' => UPLOAD_PATH,
                'savePath' => '',
            ], $options);
            $upload = new \Think\Upload($options);
            $info   = $upload->upload();
            if (!$info) {
                // 上传错误提示错误信息
                $this->errorCode    = 10016;
                $this->errorMessage = $upload->getError();
                return false;
            } else {
                $attach_info = current($info);
                // 将附件插入附件表
                $attach_info = [
                    'title'       => str_replace('.' . $attach_info['ext'], '', $attach_info['name']),
                    'url'         => $attach_info["savepath"] . $attach_info["savename"],
                    'path'        => $attach_info["savepath"] . $attach_info["savename"],
                    'name'        => $attach_info["savename"],
                    'ext'         => $attach_info['ext'],
                    'ip'          => get_client_ip(),
                    'siteid'      => get_siteid(),
                    'uploaded_at' => date('Y-m-d H:i:s'),
                ];

                if ($attachment_id = D("Attachment")->add($attach_info)) {
                    $attach_info['id'] = $attachment_id;
                    // $attach_info['thumbs'] = AttachmentLogic::createThumb($attach_info);
                    $result = $attach_info;
                    return $result;
                } else {
                    $this->errorCode    = 10014;
                    $this->errorMessage = '附件保存失败!';
                    return false;
                }
            }
        } else {
            $this->errorCode    = 10015;
            $this->errorMessage = '请先选择图片!';
            return false;
        }
    }

}