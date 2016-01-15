<?php
namespace Logic;
use Logic\BaseLogic;
use Lib\Log;
use Model\AttachmentModel;

/**
 * 附件
 * @author 李志亮 <lizhiliang@kankan.com>
 */
class AttachmentLogic extends BaseLogic {
    static private $thumbs = array(
        array('w' => 100, 'h' => 90),
        array('w' => 80, 'h' => 44),
        );

    /**
     * 图片缩略
     * @param  array $attach_info 附件信息
     * @return array / boolean    缩略图集合或者false
     * @author 李志亮 <lizhiliang@kankan.com>
     */
    static public function createThumb($attach_info) {
        $attach_info['path'] = preg_replace('/\\/|\\\\/', DIRECTORY_SEPARATOR, $attach_info["path"]);
        try {
            $thumbs = array();
            $image = new \Think\Image(1, UPLOAD_PATH . $attach_info['path']);
            foreach (static::$thumbs as $key => $thumb) {
                $thumb_url = str_replace('.' . $attach_info['ext'], $thumb['w'].'x'.$thumb['h'] . '.' . $attach_info['ext'], $attach_info['url']);
                $thumb_path = UPLOAD_PATH . str_replace('.' . $attach_info['ext'], $thumb['w'].'x'.$thumb['h'] . '.' . $attach_info['ext'], $attach_info['path']);
                $image->thumb($thumb['w'], $thumb['h'], \Think\Image::IMAGE_THUMB_FILLED)->save($thumb_path);
                $thumbs[$thumb['w'] . 'x' . $thumb['h']] = $thumb_url;
            }
            return $thumbs;
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
            Log::info('缩略图生成失败; ' . json_encode($attach_info). '; ' . $e->getMessage());
        }
    }

    /**
     * 图片裁剪
     * @param  int    $src_x         原图裁剪点的x坐标
     * @param  int    $src_y         原图裁剪点的y坐标
     * @param  int    $width         裁剪宽度
     * @param  int    $height        裁剪高度
     * @param  int    $save_width    保存宽度
     * @param  int    $save_height   保存高度
     * @param  int    $attachment_id 裁剪附件ID
     * @return array                 裁剪后图片
     * @author 李志亮 <lizhiliang@kankan.com>
     */
    public function crop(int $src_x, int $src_y, int $width, int $height, int $save_width, $save_height, int $attachment_id) {
        if (empty($width) || empty($height) || empty($attachment_id)) {
            $this->errorCode = 10001;
            $this->errorMessage = '参数不合法';
            return false;
        }
        $attachment_model = new AttachmentModel();
        $attach_origin = $attachment = $attachment_model->find($attachment_id);
        if (empty($attachment) || !file_exists(UPLOAD_PATH . $attachment['path'])) {
            $this->errorCode = 10010;
            $this->errorMessage = '附件不存在';
            return false;
        }
        try {
            $image_size = getimagesize(UPLOAD_PATH . $attachment['path']);
        } catch (Exception $e) {
            $this->errorCode = 10040;
            $this->errorMessage = '原图信息获取失败！';
            return false;
        }
        // 计算缩放比
        $ratio = $image_size[0] / C('IMAGE_CROP_WIDTH');
        $src_x = intval($src_x * $ratio);
        $src_y = intval($src_y * $ratio);
        $width = intval($width * $ratio);
        $height = intval($height * $ratio);

        try {
            // 图片裁剪处理
            $image = new \Think\Image(1, UPLOAD_PATH . $attachment['path']);
            $name = uniqid() . '.' .$attachment['ext'];
            $des_path = str_replace($attachment['name'], $name, UPLOAD_PATH . $attachment['path']);
            $image->crop($width, $height, $src_x, $src_y)->save($des_path);
            $attachment['url'] = str_replace($attachment['name'], $name, $attachment['url']);
            $attachment['path'] = str_replace($attachment['name'], $name, $attachment['path']);
            $attachment['name'] = $name;

            // 缩略图处理
            $thumb_obj = new \Think\Image(1, UPLOAD_PATH . $attachment['path']);
            $thumb_name = uniqid() . '.' .$attachment['ext'];
            $thumb_des_path = str_replace($attachment['name'], $thumb_name, UPLOAD_PATH . $attachment['path']);
            $thumb_obj->thumb($save_width, $save_height, 2)->save(str_replace($attachment['name'], $thumb_name, UPLOAD_PATH . $attachment['path']));


            $thumb['url'] = str_replace($attachment['name'], $thumb_name, $attachment['url']);
            $thumb['path'] = str_replace($attachment['name'], $thumb_name, $attachment['path']);
            $thumb['name'] = $thumb_obj;

            return array('origin' => $attach_origin, 'crop' => $attachment, 'thumb' => $thumb);
        } catch (Exception $e) {
            $this->errorCode = 10012;
            $this->errorMessage = '图片裁剪失败';
            return false;
        }
    }

}