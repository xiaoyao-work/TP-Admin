<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2015 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: XiaoYao <476552238li@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 系统站点管理
*/
class SiteController extends CommonController {
  protected $db;
  function __construct() {
    parent::__construct();
    $this->db = D('Site');
  }
  /**
   * 站点列表
   */
  public function index() {
    $sites = $this->db->select();
    $this->assign('sites',$sites);
    $this->display();
  }

  /**
   * 添加站点
   */
  public function add() {
    if (IS_POST) {
      $this->checkToken();
      $data = $_POST['info'];

      $data['name'] = isset($data['name']) && trim($data['name']) ? trim($data['name']) : showmessage('站点名称为空');
      $data['dirname'] = isset($data['dirname']) && trim($data['dirname']) ? strtolower(trim($data['dirname'])) : showmessage('站点目录为空');

      $data['domain'] = isset($data['domain']) && trim($data['domain']) ? trim($data['domain']) : '';

      $data['site_title'] = isset($data['site_title']) && trim($data['site_title']) ? trim($data['site_title']) : '';
      $data['keywords'] = isset($data['keywords']) && trim($data['keywords']) ? trim($data['keywords']) : '';
      $data['description'] = isset($data['description']) && trim($data['description']) ? trim($data['description']) : '';

      if ( $this->db->where( array( 'name' => $data['name'] ) )->find() ) {
        showmessage('站点名称已经存在');
      }
      if (!preg_match('/^\\w+$/i', $data['dirname'])) {
        showmessage('站点目录只能包括数字、字母、下划线三种类型。');
      }
      if ( $this->db->where( array( 'dirname' => $data['dirname'] ) )->find() ) {
        showmessage('站点目录名已经存在');
      }
      if (!empty($data['domain']) && !preg_match('/http:\/\/(.+)[^\/]$/i', $data['domain'])) {
        showmessage('站点域名格式应该为http://www.hhailuo.com，请不要以‘/’结束');
      }
      if (!empty( $data['domain'] ) && $this->db->where( array( 'domain' => $data['domain'] ) )->find() ) {
        showmessage( '站点域名已经存在' );
      }

      $data['setting'] = trim(array2string($_POST['setting']));

      if ( $this->db->add($data) ) {
        $this->db->set_cache();
        $this->success('添加成功');
      } else {
        $this->error('操作失败');
      }
    } else {
      /*$template_list = template_list();
      $setting = string2array($site['setting']);
      $setting['watermark_img'] = str_replace('statics/images/water/','',$setting['watermark_img']);
      $this->assign('template_list',$template_list);
      $this->assign('setting',$setting);
      $this->assign('data',$site);*/
      $this->display();
    }
  }

  /**
   * 编辑站点
   */
  public function edit() {
    $siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : $this->error('参数异常');
    if ($site = $this->db->where(array('id'=>$siteid))->find()) {
      if (IS_POST) {
        $this->checkToken();
        $data = $_POST['info'];
        $data['setting'] = array2string($_POST['setting']);
        if ($this->db->where(array('id'=> $siteid))->save($data) !== false) {
          $this->db->set_cache();
          $this->success('操作成功');
        } else {
          $this->error('操作失败');
        }
      } else {
        $template_list = template_list();
        $setting = string2array($site['setting']);
        $setting['watermark_img'] = str_replace('statics/images/water/','',$setting['watermark_img']);
        $this->assign('template_list',$template_list);
        $this->assign('setting',$setting);
        $this->assign('data',$site);
        $this->display();
      }
    } else {
      $this->error(L('notfound'));
    }
  }

  /**
   * 删除站点
   */
  public function delete() {
    $siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : $this->error('参数异常');
    if ($site = $this->db->where(array('id'=>$siteid))->delete() !== false) {
      $this->db->set_cache();
      $this->success('删除成功！');
    } else {
      $this->success('删除失败！');
    }
  }

  /**
   * 站点名称重复检测
   */
  public function public_name() {
    $name = isset($_GET['name']) && trim($_GET['name']) ? trim($_GET['name']) : exit('0');
    $siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : '';
    $site = (empty($siteid) ? $this->db->where(array('name' => $name))->find() : $this->db->where(array('id'=> array('not in', $siteid), 'name' => $name))->find() );
    if ( $site ) {
      exit('0');
    } else {
      exit('1');
    }
  }

  /**
   * 站点目录重名检测
   */
  public function public_dirname() {
    $dirname = isset($_GET['dirname']) && trim($_GET['dirname']) ? trim($_GET['dirname']) : exit('0');
    $siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : '';
    $site = (empty($siteid) ? $this->db->where(array('dirname' => $dirname))->find() : $this->db->where(array('id'=> array('not in', $siteid), 'name' => $name))->find() );
    if ( $site ) {
      exit('0');
    } else {
      exit('1');
    }
  }

}