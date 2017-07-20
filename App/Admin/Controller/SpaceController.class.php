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

class SpaceController extends CommonController {
	private $db, $poster_db;
	function __construct() {
		parent::__construct();
		$this->db        = model("Space");
		$this->poster_db = model("Poster");
	}

	public function index() {
		$spaces = $this->db->getList([], 'spaceid desc');
		$this->assign('types', C("AD_TYPE"));
		$this->assign('spaces', $spaces['data']);
		$this->assign('pages', $spaces['page']);
		$this->display();
	}

	public function add() {
		if (isset($_POST['dosubmit'])) {
			$this->checkToken();
			$_POST['space']['name']    = trim($_POST['space']['name']);
			$_POST['space']['width']   = intval($_POST['space']['width']);
			$_POST['space']['height']  = intval($_POST['space']['height']);
			$_POST['space']['setting'] = var_export($_POST['setting'], TRUE);
			$_POST['space']['siteid']  = $this->siteid;
			if ($this->db->add($_POST['space']) !== false) {
				$this->success("添加成功");
			} else {
				$this->error("版位添加失败");
			}
		} else {
			$this->assign('types', C("AD_TYPE"));
			$this->assign('poster_template', C('poster_template'));
			$this->display();
		}
	}

	public function edit() {
		if (isset($_POST['dosubmit'])) {
			$this->checkToken();
			$space = $this->db->where(['siteid' => $this->siteid])->find($_POST['spaceid']);
			if (!$space) {
				$this->error("修改的版位不存在", "index");
			}
			$_POST['space']['name']    = trim($_POST['space']['name']);
			$_POST['space']['width']   = intval($_POST['space']['width']);
			$_POST['space']['height']  = intval($_POST['space']['height']);
			$_POST['space']['setting'] = var_export($_POST['setting'], TRUE);
			if ($space['type'] != $_POST['space']['type']) {
				$_POST['space']['items'] = 0;
				$this->poster_db->where(['siteid' => $this->siteid, 'spaceid' => $_POST['spaceid']])->delete();
			}
			if ($this->db->where(['siteid' => $this->siteid, 'spaceid' => $_POST['spaceid']])->save($_POST['space']) !== false) {
				// echo $this->db->getLastSql();
				$this->success("修改成功！");
			} else {
				$this->error("修改失败！");
			}
		} else {
			$this->assign('types', C("AD_TYPE"));
			$this->assign('poster_template', C('poster_template'));
			$space            = $this->db->where(['siteid' => $this->siteid, 'spaceid' => $_GET['spaceid']])->find();
			$space['setting'] = string2array($space['setting']);
			$this->assign('space', $space);
			$this->display();
		}
	}

	public function delete() {
		if (!isset($_POST['spaceid'])) {
			$this->error("没有选中任何版位");
		}
		if (IS_POST) {
			if (!is_array($_POST['spaceid'])) {
				$this->error("数据格式不正确");
			}
			if ($this->db->where(['siteid' => $this->siteid, 'id' => ['in', $_POST['spaceid']]])->delete() !== false) {
				$this->success('删除成功！');
			} else {
				$this->error('删除失败！');
			}
		} else {
			if ($this->db->where(['siteid' => $this->siteid, 'spaceid' => $_GET['spaceid']])->delete() !== false) {
				$this->poster_db->where(['siteid' => $this->siteid, 'spaceid' => $_GET['spaceid']])->delete();
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}

	public function poster_template() {
		$templatedir = APP_PATH . 'Admin/' . C('DEFAULT_V_LAYER') . '/' . C('DEFAULT_THEME') . '/Poster/poster_template/';
		$templates   = glob($templatedir . '*.html');
		if (is_array($templates) && !empty($templates)) {
			foreach ($templates as $k => $tem) {
				$templates[$k] = basename($tem, ".html");
			}
		}
		$this->assign('templates', $templates);
		$this->assign('poster_template', C('poster_template'));
		$this->display();
	}

	public function public_template_setting() {
		if (!isset($_GET['template'])) {
			$this->error('参数错误');
		} else {
			$template = $_GET['template'];
		}
		$poster_template = C('poster_template');
		if ($poster_template[$template]) {
			$info = $poster_template[$template];
			if (is_array($info['type']) && !empty($info['type'])) {
				$type = [];
				$type = array_keys($info['type']);
				unset($info['type']);
				$info['type'] = $type;
			}
		}
		$this->assign('template', $template);
		$this->assign('info', $info);
		$this->display();
	}

	public function public_preview() {
		$html = "<html><head><title></title></head><body><script src=" . url('/Poster/show_poster', 'Home', ['id' => I('get.spaceid')]) . "></script></body></html>";
		echo $html;
	}

	public function public_call() {
		$this->display();
	}

	/**
	 * 检测版位名称是否存在
	 */
	public function public_check_space() {
		if (!$_GET['name']) {
			exit("0");
		}

		$name  = $_GET['name'];
		$where = ['name' => $name];
		if (isset($_GET['spaceid'])) {
			$where['spaceid'] = ['NEQ', $_GET['spaceid']];
		}
		if ($this->db->where($where)->find()) {
			exit("0");
		} else {
			exit("1");
		}
	}
}
?>