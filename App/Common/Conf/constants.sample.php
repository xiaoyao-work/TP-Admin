<?php
    defined('DS') or define('DS', DIRECTORY_SEPARATOR);
    // 系统版本
    defined('SYS_VERSION') or define('SYS_VERSION', '5.0.0');
    // 静态资源目录
    defined('ASSETS_DOMAIN')  or define('ASSETS_DOMAIN', BASE_URL . 'assets/');

    // 文件上传接受服务
    defined('UPLOAD_IMAGE_URL') or define('UPLOAD_IMAGE_URL', 'http://api.tp3.hhailuo.com/upload.html');
    // 附件根目录地址
    defined('IMAGE_DOMAIN')   or define('IMAGE_DOMAIN',  'http://www.tp3.hhailuo.com/uploads/');
    // 附件保存地址
    defined('UPLOAD_PATH')   or define("UPLOAD_PATH", ROOT_PATH . 'uploads' . DS);

    // 官方服务地址
    defined('HHAILUO_API_DOMAIN') or define('HHAILUO_API_DOMAIN', 'http://api.hhailuo.com/');
    // 插件目录
    defined('PLUGINS_PATH') or define('PLUGINS_PATH', APP_PATH . 'Plugins' . DS);
    // 插件目录
    defined('APP_LIB_PATH') or define('APP_LIB_PATH', APP_PATH . 'Lib' . DS);
    // 字段类型插件
    defined('MODEL_PATH') or define('MODEL_PATH', APP_LIB_PATH. 'PostField' . DS);
    // 邮件发送插件
    defined('EMAIL_PATH') or define('EMAIL_PATH', APP_LIB_PATH. 'PHPMailer' . DS);
    // QQ OAuth 插件
    defined('QQ_PATH')    or define('QQ_PATH', APP_LIB_PATH. 'QQAPI' . DS);
    // 微博 OAuth 插件
    defined('WEIBO_PATH') or define('WEIBO_PATH', APP_LIB_PATH . 'WeiboAPI' . DS);
