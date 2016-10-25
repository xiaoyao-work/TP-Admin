<?php
namespace Api\Controller;
use Think\Controller;

class BaseController extends Controller {

    function __construct() {
        header('Access-Control-Allow-Origin: *');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit();
        }
        parent::__construct();
        $this->member = session('member_info');
        /*$api_access_token = session('api_access_token');
        $access_token = I('access_token');
        if (empty($access_token) || $access_token != $api_access_token) exit('非法请求！');*/
    }
}