<!DOCTYPE html>
<html>
<head>
    <title>TP-Admin 安装 - 红海螺</title>
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/install.css">
</head>
<body class="wp-core-ui">
    <p id="logo"><a href="https://www.hhailuo.com/" tabindex="-1">TP-Admin</a></p>
    <h2 class="text-center">恭喜您，安装成功！</h2>
    <div>
    <?php if ($grant['code'] == 0) { ?>
        <ul>
            <?php if ($grant['data']['type'] == 1) { ?>
            <li><span style="margin-right:8px;">*</span>您的授权方式为个人授权，个人授权有效期为3个月。如若你需要部署项目上线，请尽快申请商业授权，并更换授权码！</li>
            <?php } else { ?>
            <li><span style="margin-right:8px;">*</span>尊敬的<?php echo $grant['data']['name']; ?>，感谢您选着TP-Admin。请保管好您的授权码<?php echo $grant['data']['uuid']; ?>，切莫将其透露给他人使用，以免给你造成不必要的麻烦。</li>
            <?php } ?>
            <li><span style="margin-right:8px;">*</span>为了您站点的安全，安装完成后即可将网站Public目录下的“install”文件夹删除。</li>
        </ul>
        <p class="text-center">
            <a href="http://www.<?php echo $grant['data']['domain']; ?>" title="站点首页" class="btn btn-info">站点首页</a>
            <a href="http://admin.<?php echo $grant['data']['domain']; ?>" title="后台管理" class="btn btn-info">后台管理</a>
        </p>
    <?php } else { ?>
        <p class="text-danger">您的授权码验证失败，未能成功授权！敬请先到官网授权，以免影响你接下来的系统使用。</p>
        <p class="text-center">
            <a href="?action=configure" title="重新配置" class="btn btn-info">重新配置</a>
            <a href="http://u.tp3.hhailuo.com/grant/apply" title="申请授权" class="btn btn-info">申请授权</a>
        </p>
    <?php } ?>
        <p class="text-center">
            <img class="img-rounded" src="../../assets/images/wechat.jpg?ms=5.0.0" width="250px"></br>
            <span>你的支持是我永远的动力！</span>
        </p>
    </div>
</body>
</html>