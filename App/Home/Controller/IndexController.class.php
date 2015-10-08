<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }

    public function contact_us() {
        if (IS_POST) {
            if (!M()->autoCheckToken($_POST)) {
                $this->show('<p style="padding: 30px 0 0 10px;">hash 数据验证失效！</p>','utf-8');
                exit();
            }
            $data = I('post.info');
            if (D('feedback')->add($data)) {
                $this->show('<p style="padding-top: 30px 0 0 10px;">谢谢反馈，我们已收到并会尽快于你取得联系</p>','utf-8');
                exit();
            } else {
                $this->show('<p style="padding-top: 30px 0 0 10px;">发生了一些错误，我们会尽快修复！</p>','utf-8');
                exit();
            }
        } else {
            $this->display();
        }

    }
}