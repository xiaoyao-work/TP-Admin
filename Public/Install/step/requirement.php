<!DOCTYPE html>
<html>
<head>
    <title>TP-Admin 安装 - 红海螺</title>
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/install.css">
</head>
<body class="wp-core-ui">

    <p id="logo"><a href="https://www.hhailuo.com/" tabindex="-1">TP-Admin</a></p>
    <h2 class="text-center">环境检测</h2>
    <div class="entry-content">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th width="200px">类目</th>
                    <th width="100px">要求</th>
                    <th>检测结果</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>重写</td>
                    <td>开启</td>
                    <td>
                        <?php
                        if (function_exists('apache_get_modules')) {
                            if (in_array('mod_rewrite',apache_get_modules())) {
                                echo "<span class='text-success'>通过</span>";
                            } else {
                                echo "<span class='text-danger'>未通过</span>";
                            }
                        } else {
                            echo "<span class='text-warning'>未知</span><p>无法检测到系统是否开启重写，请参考<a href='http://www.hhailuo.com/tp-admin-v5/configure' target='_blank'>TP-Admin配置文档</a>进行设置</p>";
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>PHP版本</td>
                    <td> > 5.4.0 </td>
                    <td>
                        <?php
                        if (version_compare(PHP_VERSION, '5.4.0', '>')) {
                            echo "<span class='text-success'>通过</span>";
                        } else {
                            echo "<span class='text-danger'>未通过</span>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>GD库</td>
                    <td>开启</td>
                    <td>
                        <?php
                        if (extension_loaded('gd') && function_exists('gd_info')) {
                            echo "<span class='text-success'>通过</span>";
                        } else {
                            echo "<span class='text-danger'>未通过</span>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>CURL库</td>
                    <td>开启</td>
                    <td>
                        <?php
                        if (extension_loaded('curl') && function_exists('curl_version')) {
                            echo "<span class='text-success'>通过</span>";
                        } else {
                            echo "<span class='text-danger'>未通过</span>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>文件权限检测</td>
                    <td>可写</td>
                    <td>
                        <p>
                        附件可写检测：<?php
                            if (is_writable($root_path . '/Public/uploads')) {
                                echo "<span class='text-success'>通过</span>";
                            } else {
                                echo "<span class='text-danger'>" . $root_path . '/Public/uploads' . "不可写</span>";
                            }
                        ?>
                        </p>
                        <p>
                            系统运行缓存：<?php
                            if (is_writable($root_path . '/Public/Runtime')) {
                                echo "<span class='text-success'>通过</span>";
                            } else {
                                echo "<span class='text-danger'>" . $root_path . '/Public/Runtime' . "不可写</span>";
                            }
                        ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <p class="step"><a href="./index.php?action=configure" class="button button-large next">下一步</a></p>
    <script type="text/javascript" src="./js/jquery.min.js"></script>
    <script type="text/javascript">
        if ($('.text-danger').length > 0) {
            $('.next').addClass('disabled');
        }
        $('.next').click(function() {
            return ($('.text-danger').length <= 0);
        });
    </script>
</body>
</html>
