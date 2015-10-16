<?php
// 模型字段路径
define('MODEL_PATH', APP_PATH.'Admin'.DIRECTORY_SEPARATOR.'Common'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR);

// 加载站点文件
if(file_exists(CONF_PATH . "sitelist.php")){
    $sitelist = include CONF_PATH . "sitelist.php";
    if (is_array($sitelist)) {
        foreach ($sitelist as $key => $site) {
            if ( strpos($full_url, $site['domain'])) {
                define("SITEID", $site['siteid']);
                define("DEFAULT_STYLE", $site['default_style']);
                define("TEMPLATE", $site['template']);
                define('IS_SITE',true);
                break;
            }
        }
    }
}

// 定义站点相关信息
if (!defined("IS_SITE")) {
    define("SITEID", 1);
    if (isset($sitelist)) {
        define("DEFAULT_STYLE", $sitelist[SITEID]['default_style']);
        define("TEMPLATE", $sitelist[SITEID]['template']);
    }
}