# TP-Admin #
TP-Admin 是一个开源的，以ThinkPHP为底层架构的一个通用后台。

# 主要功能介绍 #
已经集成功能有：主分站、内容模型、CMS发布系统(包括 动态字段、多层级栏目、内容推荐等在内的功能充裕的内容发布功能)、基于RBAC的多站点权限控制、广告、后台菜单动态配置、联动菜单、操作日志等功能。后续会陆续开源其他扩展。( 如：微信嵌入，前台主题，房地产模块 )

# 演示站点 #
[在线演示站点](http://tp-admin.hhailuo.com/)
用户名: tp-admin 密码: tp-admin

# 安装 #
* 下载最新版本的TP-Admin源码。
* 创建数据库，导入Docs/tp-admin.sql。
* 重命名Conf/database.example.php => Conf/database.php，并修改其中具体参数。
* 安装成功，登陆后台。默认用户名密码为 admin / admin。
* 点击 设置 -> 站点管理 任意打开一个站点点击保存(此步骤是为了生成站点静态文件，在其他的模块中需要用到这个站点静态文件。生成文件存放在 Conf/sitelist.php)

# 问题反馈 #
Have a bug or an issue with this system? Open a  [ here on ](https://github.com/476552238li/TP-Admin/issues)  GitHub。

# Creator #
TP-Admin 是由 [李志亮](http://www.hhailuo.com) 创建和维护。

# Copyright and License #
遵循Apache2开源协议发布。Apache Licence是著名的非盈利开源组织Apache采用的协议。该协议和BSD类似，鼓励代码共享和尊重[原作者](http://www.hhailuo.com)的著作权，同样允许代码修改，再作为开源或商业软件发布。
