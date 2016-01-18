<?php
    defined('IMAGE_DOMAIN')   or define('IMAGE_DOMAIN', BASE_URL . 'uploads/');
    defined('ASSETS_DOMAIN')  or define('ASSETS_DOMAIN', BASE_URL . 'assets/');
    defined('VIDEO_UPLOAD_URL') or define('VIDEO_UPLOAD_URL', BASE_URL . 'upload');
    defined('PLUGINS_PATH') or define('PLUGINS_PATH', APP_PATH . 'Plugins' . DIRECTORY_SEPARATOR);
    // 模型字段路径
    define('MODEL_PATH', PLUGINS_PATH. 'PostField' . DIRECTORY_SEPARATOR);