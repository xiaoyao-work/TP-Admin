<?php

namespace Logic\Admin;
use Lib\Log;
use Logic\BaseLogic;

/**
 * MemberLogic
 */
class MemberLogic extends BaseLogic {

    public function exportMemberCSV($member_info){
        /*
         * 生成excel文件
         * $title 表头
         * "\t"竖线
         * "\n"换行
         */
        header('Pragma: public');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: pre-check=0, post-check=0, max-age=0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Encoding: none');
        header("Content-type:application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition:filename=会员信息.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        $title = "ID" . "\t" . "UID" . "\t" . "手机号码" . "\t" . "昵称" . "\t" . "会员等级" . "\t" . "到期时间" . "\t" . "注册时间" . "\t" . "地区". "\t" . "来源" . "\n";
        echo(iconv("UTF-8", "GBK", $title)); //转码，防止excel内容乱码

        foreach ($member_info as $value) {
            $base_info = json_decode($value['base_info'], true);

            echo $value['id'] . "\t";
            echo $value['uid'] . "\t";
            echo $base_info['mobile'] . "\t";
            echo iconv("UTF-8", "gb2312//IGNORE", $base_info['nickname']) . "\t";
            echo iconv("UTF-8", "GBK", get_member_config("member.group", $value['vip_type'])) . "\t";
            echo $value['endtime'] ? date("Y-m-d H:i:s", $value['endtime']) : '----' . "\t";
            echo $value['inputtime'] ? date("Y-m-d H:i:s", $value['inputtime']) : '----' . "\t";
            echo iconv("UTF-8", "GBK", ($value['reg_location'] ? $value['reg_location'] : '未知')) . "\t";
            echo iconv("UTF-8", "GBK", get_member_config("member.reg_origin", $value['reg_origin'])) . "\t";
            echo "\n";
        }

        exit;
    }

}