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
    <form method="post" action="index.php?action=install" id="install">
        <h2>数据库配置</h2>
        <table class="form-table">
            <tbody><tr>
                <th scope="row"><label for="DB_NAME">数据库名</label></th>
                <td>
                    <input name="db[DB_NAME]" id="DB_NAME" type="text" size="25" value="<?php echo $database_info['DB_NAME']; ?>">
                    <div class="onShow">将TP-Admin安装到哪个数据库？</div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="DB_USER">用户名</label></th>
                <td>
                    <input name="db[DB_USER]" id="DB_USER" type="text" size="25" value="<?php echo $database_info['DB_USER']; ?>">
                    <div class="onShow">您的数据库用户名。</div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="DB_PWD">密码</label></th>
                <td>
                    <input name="db[DB_PWD]" id="DB_PWD" type="text" size="25" value="<?php echo $database_info['DB_PWD']; ?>" autocomplete="off">
                    <div class="onShow">您的数据库密码。</div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="DB_HOST">数据库主机</label></th>
                <td><input name="db[DB_HOST]" id="DB_HOST" type="text" size="25" value="<?php echo $database_info['DB_HOST']; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="DB_PORT">端口</label></th>
                <td><input name="db[DB_PORT]" id="DB_PORT" type="text" value="<?php echo $database_info['DB_PORT']; ?>" size="10"></td>
            </tr>
            <tr>
                <th scope="row"><label for="DB_PREFIX">表前缀</label></th>
                <td><input name="db[DB_PREFIX]" id="DB_PREFIX" type="text" value="<?php echo $database_info['DB_PREFIX']; ?>" size="25"></td>
            </tr>
        </tbody></table>

        <h2>系统配置</h2>
        <table class="form-table">
            <tbody><tr>
                <th scope="row"><label for="username">超级管理员帐号：</label></th>
                <td><input name="user[username]" id="username" type="text" size="25" value=""></td>
            </tr>
            <tr>
                <th scope="row"><label for="password">管理员密码：</label></th>
                <td><input name="user[password]" id="password" type="text" size="25" value=""></td>
            </tr>
            <tr>
                <th scope="row"><label for="pwdconfirm">确认密码：</label></th>
                <td><input name="user[pwdconfirm]" id="pwdconfirm" type="text" size="25" value="" autocomplete="off"></td>
            </tr>
            <tr>
                <th scope="row"><label for="email">管理员E-mail：</label></th>
                <td><input name="user[email]" id="email" type="text" size="25" value=""></td>
            </tr>
            <tr>
                <th scope="row"><label for="domain">主域：</label></th>
                <td>
                    <input name="domain" id="domain" type="text" size="25" value="hhailuocms.com">
                </td>
            </tr>
            <tr>
                <th></th>
                <td>将TP-Admin部署到哪个域名下？<span class='text-danger'>此处填写的是主域名(如 www.hhailuo.com的主域为hhailuo.com)</span></td>
            </tr>
            <tr>
                <th scope="row"><label for="uuid">授权码：</label></th>
                <td>
                    <input name="uuid" id="uuid" type="text" size="32" value="">
                </td>
            </tr>
            <tr>
                <th></th>
                <td><a href="http://u.tp3.hhailuo.com/grant/apply.html" target="_blank">获取授权码</a></td>
            </tr>
        </tbody></table>

        <p class="step"><a href="#" onclick="check_database();return false;" class="button button-large">下一步</a></p>
    </form>
    <script type="text/javascript" src="./js/jquery.min.js"></script>
    <script type="text/javascript" src="./js/formvalidator.js"></script>
    <script type="text/javascript" src="./js/formvalidatorregex.js"></script>
    <script type="text/javascript">
        function check_database() {
            $.ajax({
                url: '?action=database-check'+'&sid='+Math.random()*5 ,
                type: 'POST',
                data: {
                    'DB_HOST'   : $('#DB_HOST').val(),
                    'DB_PORT'   : $('#DB_PORT').val(),
                    'DB_USER'   : $('#DB_USER').val(),
                    'DB_PWD'    : $('#DB_PWD').val(),
                    'DB_PREFIX' : $('#DB_PREFIX').val(),
                    'DB_NAME'   : $('#DB_NAME').val(),
                },
                dataType: 'json',
                success: function(res) {
                    if (res.code != 200 && res.code != 0) {
                        alert(res.message);
                        return false;
                    }
                    if (res.code == 200 || (res.code == 0 && confirm(res.message))) {
                        $('#install').submit();
                    }
                }
            });
            return false;
        }

        $(document).ready(function() {
            $.formValidator.initConfig({autotip:true,formid:"install",onerror:function(msg, obj){
                alert(msg);
                $(obj).focus();
            }});
            $("#username").formValidator({onshow:"2到20个字符，不含非法字符！",onfocus:"请输入用户名3至20位"}).inputValidator({min:3,max:20,onerror:"用户名长度应为3至20位"})
            $("#password").formValidator({onshow:"6到20个字符",onfocus:"密码合法长度为6至20位"}).inputValidator({min:6,max:20,onerror:"密码合法长度为6至20位"});
            $("#pwdconfirm").formValidator({onshow:"请再次输入密码",onfocus:"请输入确认密码",oncorrect:"两次密码相同"}).compareValidator({desid:"password",operateor:"=",onerror:"两次密码输入不同"});
            $("#email").formValidator({onshow:"请输入email",onfocus:"请输入email",oncorrect:"email格式正确"}).regexValidator({regexp:"email",datatype:"enum",onerror:"email格式错误"});
            $("#domain").formValidator({onshow:"请输入主域",onfocus:"请输入主域"}).inputValidator({min:1, onerror:"主域不能为空"});

            $("#uuid").formValidator({onshow:"请输入授权码",onfocus:"请输入授权码",oncorrect:"输入正确",empty:false}).inputValidator({min:32, max: 32, onerror:"授权码为32位字符串"});

            $("#DB_HOST").formValidator({onshow:"数据库服务器地址, 一般为 localhost",onfocus:"数据库服务器地址, 一般为 localhost",oncorrect:"数据库服务器地址正确",empty:false}).inputValidator({min:1,onerror:"数据库服务器地址不能为空"}).defaultPassed();

        });
    </script>
</body>
</html>