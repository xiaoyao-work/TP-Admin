<?php
return [
    'SESSION_OPTIONS'       => [
        'name'   => 'hhailuo_sessionid',
        'expire' => 864000,
        'domain' => '.tp3.hhailuo.com',
        'path'   => '/',
    ],
    /**
     * Cookie设置
     */
    'COOKIE_EXPIRE'         => 0,                 // Cookie有效期
    'COOKIE_DOMAIN'         => 'tp3.hhailuo.com', // Cookie有效域名
    'COOKIE_PATH'           => '/',               // Cookie路径

    'TOKEN_ON'              => true,       //是否开启令牌验证
    'TOKEN_NAME'            => '__hash__', //令牌验证的表单隐藏字段名称
    'TOKEN_TYPE'            => 'md5',      //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'           => true,       //令牌验证出错后是否重置令牌 默认为true

    'URL_MODEL'             => 2, //URL模式
    'MODULE_ALLOW_LIST'     => ['Admin', 'Home', 'User', 'Api'],
    'DEFAULT_MODULE'        => 'Home',

    'APP_SUB_DOMAIN_DEPLOY' => 1, // 开启子域名或者IP配置
    'APP_SUB_DOMAIN_RULES'  => [
        'admin.tp3.hhailuo.com'    => 'Admin',
        'u.tp3.hhailuo.com'        => 'User',
        'api.tp3.hhailuo.com'      => 'Api',
        '{domain}.tp3.hhailuo.com' => 'Home',
    ],

    'IMAGE_UPLOAD_LIMIT'    => 5 * 1024 * 1024,
    'IMAGE_CROP_WIDTH'      => 275.00,
    'ALLOW_EXTS'            => 'jpg,jpeg,png,gif',
    'THUMBNAILS'            => ['small' => ['w' => 100, 'h' => 90], 'attachemnt-list' => ['w' => 80, 'h' => 44]],

    /**
     * DB配置
     */
    'DB_TYPE'               => 'Mysql',
    'DB_PREFIX'             => 'hhailuocms_',
    'DB_PORT'               => '3306',
    'DB_USER'               => 'root',
    'DB_PWD'                => '',
    'DB_HOST'               => '127.0.0.1',
    'DB_NAME'               => 'tp3',
];