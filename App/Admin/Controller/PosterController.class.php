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

class PosterController extends CommonController {
	private $db, $space_db;
	function __construct() {
		parent::__construct();
		$this->db       = model("Poster");
		$this->space_db = model("Space");
	}

	public function index() {
		$spaceid = I('get.spaceid');
		$space   = $this->space_db->where(['spaceid' => $spaceid, 'siteid' => $this->siteid])->find();
		$posters = $this->db->getList(['spaceid' => $spaceid, 'siteid' => $this->siteid], 'listorder desc');
		$this->assign('types', ['images' => '图片', 'flash' => '动画', 'text' => '代码']);
		$this->assign('space', $space);
		$this->assign('posters', $posters['data']);
        $this->assign('pages', $posters['page']);
		$this->display();
	}

	public function add() {
		if (IS_POST) {
			$data              = $_POST['poster'];
			$data['setting']   = array2string($this->check_setting($_POST['setting'], $data['type']));
			$data['startdate'] = strtotime($data['startdate']);
			if ($data['enddate']) {
				$data['enddate'] = strtotime($data['enddate']);
			} else {
				$data['enddate'] = strtotime(date("Y-m-d", strtotime("+30 day")));
			}
			$data['addtime'] = time();
			$data['siteid']  = $this->siteid;
			$space           = $this->space_db->where(["spaceid" => $data['spaceid'], "siteid" => $this->siteid])->find();
			if ($this->db->autoCheckToken($_POST)) {
				if ($this->db->add($data) !== false) {
					$this->space_db->query("update " . C('DB_PREFIX') . "poster_space set items = items+1 where spaceid = {$data['spaceid']} and siteid = {$this->siteid} ");
					$this->success("添加成功", url('Poster/index', ['spaceid' => $data['spaceid']]));
				} else {
					// echo $poster->getLastSql();
					$this->error("添加失败！");
				}
			} else {
				$this->error("表单验证失败！");
			}
		} else {
			$spaceid = intval($_GET['spaceid']);
			$sinfo   = $this->space_db->where(['spaceid' => $spaceid, 'siteid' => $this->siteid])->find();
			$this->assign('sinfo', $sinfo);
			$this->assign('spaceid', $spaceid);
			$this->assign('types', C("AD_TYPE"));
			$setting = get_setting($sinfo['type']);
			$this->assign('setting', $setting);
			$this->display();
		}
	}

	public function edit() {
		if (IS_POST) {
			$data              = $_POST['poster'];
			$data['setting']   = array2string($this->check_setting($_POST['setting'], $data['type']));
			$data['startdate'] = strtotime($data['startdate']);
			if ($data['enddate']) {
				$data['enddate'] = strtotime($data['enddate']);
			} else {
				$data['enddate'] = strtotime(date("Y-m-d", strtotime("+30 day")));
			}
			if ($this->db->autoCheckToken($_POST)) {
				if ($this->db->where(["id" => $_POST['posterid'], "siteid" => $this->siteid])->save($data) !== false) {
					$this->success("修改成功", url('Poster/index', ['spaceid' => $data['spaceid']]));
				} else {
					$this->error("修改失败！");
				}
			} else {
				$this->error("表单验证失败！");
			}
		} else {
			$posterid = intval($_GET['posterid']);
			$poster   = $this->db->where(['id' => $posterid, 'siteid' => $this->siteid])->find();
			if (!$poster) {
				exit("输入非法！");
			}
			$poster['setting'] = string2array($poster['setting']);
			$sinfo             = $this->space_db->where(['spaceid' => $poster['spaceid'], 'siteid' => $this->siteid])->find();
			$this->assign('sinfo', $sinfo);
			$this->assign('poster', $poster);
			$this->assign('spaceid', $poster['spaceid']);
			$this->assign('types', C("AD_TYPE"));
			$setting = get_setting($sinfo['type']);
			$this->assign('setting', $setting);
			$this->display();
		}
	}

	public function listorder() {
		if (isset($_POST['listorder']) && is_array($_POST['listorder'])) {
			$listorder = $_POST['listorder'];
			foreach ($listorder as $k => $v) {
				$this->db->where(['id' => $k])->save(['listorder' => $v]);
			}
		}
		$this->success('排序成功');
	}

	public function delete() {
		if (isset($_POST['ids']) && is_array($_POST['ids'])) {
			if ($this->db->where(['siteid' => $this->siteid, 'id' => ['in', $_POST['ids']]])->delete() !== false) {
				$this->success('删除成功！');
			} else {
				$this->error('删除失败！');
			}
		} else {
			if ($this->db->where(['siteid' => $this->siteid, 'id' => $_GET['posterid']])->delete() !== false) {
				$this->success('删除成功', "index");
			} else {
				$this->error('删除失败', "index");
			}
		}
	}

	public function public_approval() {
		if (isset($_POST['ids']) && is_array($_POST['ids'])) {
			foreach ($_POST['ids'] as $k => $v) {
				$this->db->where(['siteid' => $this->siteid, 'id' => $v])->save(['disabled' => $_GET['passed']]);
			}
			$this->success('操作成功');
		} else {
			if ($this->db->where(['siteid' => $this->siteid, 'id' => $_GET['posterid']])->save(['disabled' => $_GET['passed']]) !== false) {
				$this->success('操作成功');
			} else {
				// echo $this->db->getLastSql();
				$this->error('操作失败');
			}
		}
	}

	/**
	 * ajax检查广告名是否存在
	 */
	public function public_check_poster() {
		if (!$_GET['name']) {
			echo "0";
		}

		$name  = $_GET['name'];
		$where = ['name' => $name];
		if (isset($_GET['posterid'])) {
			$where['id'] = ['NEQ', $_GET['spaceid']];
		}
		if ($this->db->where($where)->find()) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * 检查广告的内容信息，如图片、flash、文字
	 * @param array $setting
	 * @param string $type 广告的类型
	 * @return array
	 */
	private function check_setting($setting = [], $type = 'images') {
		switch ($type) {
		case 'images':
			unset($setting['flash'], $setting['text']);
			if (is_array($setting['images'])) {
				$tag = 0;
				foreach ($setting['images'] as $k => $s) {
					if ($s['linkurl'] == 'http://') {
						$setting['images'][$k]['linkurl'] = '';
					}
					if (!$s['imageurl']) {
						unset($setting['images'][$k]);
					} else {
						$tag = 1;
					}

				}
				if (!$tag) {
					showmessage(L('no_setting_photo'), HTTP_REFERER);
				}

			}
			break;

		case 'flash':
			unset($setting['images'], $setting['text']);
			if (is_array($setting['flash'])) {
				$tag = 0;
				foreach ($setting['flash'] as $k => $s) {
					if (!$s['flashurl']) {
						unset($setting['flash'][$k]);
					} else {
						$tag = 1;
					}

				}
				if (!$tag) {
					showmessage(L('no_flash_path'), HTTP_REFERER);
				}

			}
			break;

		case 'text':
			unset($setting['images'], $setting['flash']);
			if ((!isset($setting['text'][1]['title']) || empty($setting['text'][1]['title'])) && (!isset($setting['text']['code']) || empty($setting['text']['code']))) {
				showmessage(L('no_title_info'), HTTP_REFERER);
			}
			break;
		}
		return $setting[$type];
	}

}
?>