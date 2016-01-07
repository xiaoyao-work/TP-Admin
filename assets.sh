#!/bin/sh
cd /f/wamp/www/
images=(
    '/assets/images/admin/admin_img/left_menu_openClose.png'
    '/assets/images/admin/logo.png'
    '/assets/images/admin/admin_img/left_menu.png'
    '/assets/images/admin/admin_img/left_bg.png'
    '/assets/images/admin/login_bg.png'
    '/assets/images/admin/ipt_bg.jpg'
    '/assets/images/admin/login_dl_btn.jpg'
    '/assets/images/admin/login_ts140x89.gif'
    '/assets/images/admin/msg_img/msg.png'
    '/assets/images/admin/msg_img/msg_bg.png'
    '/assets/images/admin/msg_bg.png'
    '/assets/images/admin/admin_img/bnt_bg.png'
    '/assets/images/admin/admin_img/input.png'
    '/assets/js/admin/jquery.treetable.js'
    '/assets/css/admin/jquery.treetable.theme.default.css'
    '/assets/css/admin/jquery.treetable.css'
    '/assets/js/JSCal/jscal2.js'
    '/assets/css/JSCal/jscal2.css'
    '/assets/js/JSCal/lang/cn.js'
    '/assets/css/JSCal/border-radius.css'
    '/assets/css/JSCal/win2k/win2k.css'
    #'/assets/images/admin/input_date.png'
    '/assets/css/JSCal/img/time-up.png'
    )
for data in ${images[@]}
do
    des="TP-Admin-V3.0/Public${data}"
    origin="TP-Admin-V2.0/Public${data}"
    file_dir=$(dirname $des)
    test -d "$file_dir" || mkdir -p "$file_dir" && cp $origin $des
done