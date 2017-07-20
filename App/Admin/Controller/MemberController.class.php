<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Controller\CommonController;

class MemberController extends CommonController {
	protected $db;

	function __construct() {
		parent::__construct();
		$this->db = model('Member');
	}

	public function index() {
		$default_filter = [
			'id'         => '',
			'phone'      => '',
			'username'   => '',
			'email'      => '',
			'start_time' => '',
			'end_time'   => '',
		];
		$filter = array_merge($default_filter, I('request.filter', []));
		$where = [];
		if (!empty($filter['id'])) {
			$where['id'] = $filter['id'];
		}
		if (!empty($filter['phone'])) {
			$where['phone'] = $filter['phone'];
		}
		if (!empty($filter['username'])) {
			$where['username'] = ['like', '%' . $filter['username'] . '%'];
		}
		if (!empty($filter['email'])) {
			$where['email'] = $filter['email'];
		}
		if (!empty($filter['start_time'])) {
			if (!empty($filter['end_time'])) {
				$where['inputtime'] = array('between',"{$filter['start_time']},{$filter['end_time']}");
			} else {
				$where['inputtime'] = array('egt', $filter['start_time']);
			}
		} elseif (!empty($filter['end_time'])) {
			$where['inputtime'] = array('elt', $filter['end_time']);
		}
		$method = I('get.method', '');
		//导出会员信息
		if ($method == 'export_member') {
			$members = $this->db->order(['id' => 'desc'])->select($where);
			logic('member')->exportMemberCSV($members);
		} else {
			$limit           = cookie('list_rows_select') ? cookie('list_rows_select') : 20;
			$members = $this->db->getList($where, 'id desc', $limit);
		}
		$this->assign('members', $members['data']);
		$this->assign('pages', $members['page']);
		$this->assign('filter', $filter);
		$this->display();
	}

	public function edit() {
		if (IS_POST) {
			$data            = I('post.info');
			$data['endtime'] = strtotime($data['endtime']);
			$m_id            = I('post.id');
			if ($this->db->where(['id' => $m_id])->save($data)) {
				$this->success('更新成功!', U('Member/index'));
			} else {
				$this->error('更新失败！');
			}
		} else {
			$m_id   = I('get.id');
			$member = $this->db->find($m_id);
			if (empty($member)) {
				$this->error('用户不存在！');
			}
			$this->assign('member', $member);
			$this->display();
		}
	}

	public function rechargeRecord() {
		$records = model('MemberRechargeRecord')->getList();
		$this->assign('records', $records['data']);
		$this->assign('page_html', $records['page']);
		$this->display('rechargeRecord');
	}

	public function singleMemberOrder() {
		if ($uid = I('get.uid')) {
			$member = $this->db->where(['uid' => $uid])->find();
			if (empty($member)) {
				$this->error('用户不存在！');
			}
			$orders = model('order')->getList(['uid' => $uid]);

			$this->assign('member', $member);
			$this->assign('records', $orders['data']);
			$this->assign('page_html', $orders['page']);
			$this->display('singleMemberOrder');
		} else {
			$this->error('uid不能为空！');
		}
	}

}