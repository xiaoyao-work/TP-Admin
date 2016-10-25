<?php
namespace Logic\Admin;
use Logic\BaseLogic;

/**
 * 系统设置Logic
 */
class OptionsLogic extends BaseLogic {
    /**
     * 获取指定名称的设置内容
     * @param array $option_keys 设置名称
     */
    public function getOptionVals($option_keys = []) {
        $options = model('Options', 'Admin')->getOptions($option_keys);
        $options = array_translate($options, 'key', 'value');

        return $options;
    }

    /**
     *  设置指定内容的系统设置
     * @param array $options  系统设置内容
     * @param bool  $autoload 是否缓存此系统设置
     */
    public function setOptionVals($options, $autoload = false) {
        $result = model('Options', 'Admin')->setOptions($options, $autoload);
        if ($result === false) {
            return false;
        }
        $result = true;
        // 如果设置了自动加载的选项后，重新生成缓存文件
        if ($autoload) {
            $result = $this->setCache();
        }
        return $result;
    }

    /**
     * 将需要缓存的系统设置进行缓存
     */
    public function setCache() {
        $model  = model('Options', 'Admin');
        $result = $model->field('key,value')->where(['autoload' => 1])->select();
        if ($result === false) {
            return false;
        }
        $cached_options = array_translate($result, 'key', 'value');
        F('SysOptions', $cached_options);
    }
}