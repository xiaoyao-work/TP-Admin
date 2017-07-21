<!DOCTYPE html>
<html>
<head>
    <title>TP-Admin 安装 - 红海螺</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/install.css">
</head>
<body class="wp-core-ui">
    <p id="logo"><a href="https://www.hhailuo.com/" tabindex="-1">TP-Admin</a></p>
    <h2 class="text-center">安装须知</h2>
    <h3>TP-Admin必须在具备以下条件的时候才能被运行起来，请确保以下所写的运行条件都具备：</h3>
    <ol>
        <li>
            <p>系统默认使用 <code> hhailuocms.com</code>作为域名；</p>
            <p>如需更改域名请到系统入口文件：<code><?php echo dirname(dirname(__DIR__)) . '/index.php'; ?></code> 中修改 BASE_DOMAIN 即可；</p>
        </li>
        <li>
            <p>将 *.hhailuocms.com 指向到 <code><?php echo dirname(dirname(__DIR__)); ?></code></p>
            <p>如果你不知道如何设置虚拟主机请参考 <a href="https://httpd.apache.org/docs/current/zh-cn/vhosts/" target="_blank">Apache 虚拟主机配置</a></p>
        </li>
        <li>
            开启重写; <p>如果你不知道如何设置重写请参考 <a href="http://www.hhailuo.com/tp-admin-v5/configure" target="_blank">重写配置</a></p>
        </li>
        <li>PHP版本大于5.4.0</li>
        <li>PHP扩展gd、curl、PDO、mb_string开启</li>
        <li>确保以下目录可写
            <dl>
                <dt><code><?php echo dirname(dirname(__DIR__)) . '/uploads'; ?></code></dt>
                <dt><code><?php echo dirname(dirname(__DIR__)) . '/Runtime'; ?></code></dt>
            </dl>
        </li>
    </ol>
    <p class="step"><a href="./index.php?action=requirement" class="button button-large next">我已知晓</a></p>
</body>
</html>
