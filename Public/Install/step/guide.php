<!DOCTYPE html>
<html>
<head>
    <title>TP-Admin 安装 - 红海螺</title>
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/install.css">
</head>
<body class="wp-core-ui">
    <p id="logo"><a href="https://www.hhailuo.com/" tabindex="-1">TP-Admin</a></p>
    <h2 class="text-center">安装须知</h2>
    <h3>TP-Admin必须在具备以下条件的时候才能被运行起来，请确保以下所写的运行条件都具备：</h3>
    <ol>
        <li>开启虚拟主机并且确定你的主域以及子域名(至少包含api, admin, u, www)已经解析到<code><?php echo dirname(dirname(__DIR__)); ?></code>目录下；<p>如果你不知道如何设置虚拟主机请参考 <a href="https://httpd.apache.org/docs/current/zh-cn/vhosts/" target="_blank">虚拟主机配置</a></p></li>
        <li>开启重写; <p>如果你不知道如何设置重写请参考 <a href="http://www.hhailuo.com/tp-admin-v5/configure" target="_blank">重写配置</a></p></li>
        <li>PHP版本大于5.4.0</li>
        <li>PHP扩展GD，CURL，PDO开启</li>
        <li>确保以下目录 <?php
            echo $root_path . '/Public/uploads' . "; " . $root_path . '/Public/Runtime' . "; " . $root_path . '/App/Common/Cache';
            ?> 可写</li>
    </ol>
    <p class="step"><a href="./index.php?action=requirement" class="button button-large next">我已知晓</a></p>
</body>
</html>
