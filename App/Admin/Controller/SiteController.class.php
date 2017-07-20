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

/**
 * 系统站点管理
 */
class SiteController extends CommonController {
	protected $db;
	function __construct() {
		parent::__construct();
		$this->db = model('Site');
	}

	/**
	 * 站点列表
	 */
	public function index() {
		$sites = $this->db->select();
		$this->assign('sites', $sites);
		$this->display();
	}

	/**
	 * 添加站点
	 */
	public function add() {
		if (IS_POST) {
			$this->checkToken();
			$data = $_POST['info'];

			$data['name']        = isset($data['name']) && trim($data['name']) ? trim($data['name']) : showmessage('站点名称为空');
			$data['dirname']     = isset($data['dirname']) && trim($data['dirname']) ? strtolower(trim($data['dirname'])) : showmessage('站点目录为空');
			$data['site_title']  = isset($data['site_title']) && trim($data['site_title']) ? trim($data['site_title']) : '';
			$data['keywords']    = isset($data['keywords']) && trim($data['keywords']) ? trim($data['keywords']) : '';
			$data['description'] = isset($data['description']) && trim($data['description']) ? trim($data['description']) : '';

			if ($this->db->where(['name' => $data['name']])->find()) {
				showmessage('站点名称已经存在');
			}
			if (!preg_match('/^[a-z]+$/i', $data['dirname'])) {
				showmessage('站点目录只能包括小写字母。');
			}
			if ($this->db->where(['dirname' => $data['dirname']])->find()) {
				showmessage('站点目录名已经存在');
			}

			$base_domain = model('Options')->getOptions('base_domain');
			if (empty($base_domain) || empty($base_domain['value'])) {
				showmessage('请先到 设置>系统设置 填写站点主域！');
			}
			$data['base_domain'] = $base_domain['value'];
			$data['domain']      = 'http://' . $data['dirname'] . '.' . $base_domain['value'];

			if (!empty($data['domain']) && $this->db->where(['domain' => $data['domain']])->find()) {
				showmessage('站点域名已经存在');
			}

			$data['setting'] = trim(array2string($_POST['setting']));

			if ($this->db->add($data)) {
				logic('site')->setCache();
				$this->success('添加成功');
			} else {
				$this->error('操作失败');
			}
		} else {
			$template_list = template_list();
			$this->assign('template_list', $template_list);
			$this->display();
		}
	}

	/**
	 * 编辑站点
	 */
	public function edit() {
		$siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : $this->error('参数异常');
		if ($site = $this->db->where(['id' => $siteid])->find()) {
			if (IS_POST) {
				$this->checkToken();
				$data = $_POST['info'];

				$base_domain = model('Options')->getOptions('base_domain');
				if (empty($base_domain) || empty($base_domain['value'])) {
					showmessage('请先到 设置>系统设置 填写站点主域！');
				}
				$data['base_domain'] = $base_domain['value'];
				$data['domain']      = 'http://' . $data['dirname'] . '.' . $base_domain['value'];

				$data['setting'] = array2string($_POST['setting']);
				if ($this->db->where(['id' => $siteid])->save($data) !== false) {
					logic('site')->setCache();
					$this->success('操作成功');
				} else {
					$this->error('操作失败');
				}
			} else {
				$template_list = template_list();
				$setting       = string2array($site['setting']);
				// $setting['watermark_img'] = str_replace('statics/images/water/', '', $setting['watermark_img']);
				$this->assign('template_list', $template_list);
				$this->assign('setting', $setting);
				$this->assign('data', $site);
				$this->display();
			}
		} else {
			$this->error('站点不存在！');
		}
	}

	/**
	 * 删除站点
	 */
	public function delete() {
		$siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : $this->error('参数异常');
		if ($site = $this->db->where(['id' => $siteid])->delete() !== false) {
			logic('site')->setCache();
			$this->success('删除成功！');
		} else {
			$this->success('删除失败！');
		}
	}

	/**
	 * 站点名称重复检测
	 */
	public function public_name() {
		$name   = isset($_GET['name']) && trim($_GET['name']) ? trim($_GET['name']) : exit('0');
		$siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : '';
		$site   = empty($siteid) ? $this->db->where(['name' => $name])->find() : $this->db->where(['id' => ['neq', $siteid], 'name' => $name])->find();
		if ($site) {
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
		$siteid  = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : '';
		$site    = (empty($siteid) ? $this->db->where(['dirname' => $dirname])->find() : $this->db->where(['id' => ['neq', $siteid], 'dirname' => $dirname])->find());
		if ($site) {
			exit('0');
		} else {
			exit('1');
		}
	}

    public function sysSetting() {
        if (IS_POST) {
            $options = I('post.info');
            if (model('Options')->setOptions($options)) {
                $this->success('设置成功');
            } else {
                $this->error('设置失败');
            }
        } else {
            $options_key = ['base_domain'];
            $options     = logic('Options')->getOptionVals($options_key);

            $this->assign('options', $options);
            $this->display("sysSetting");
        }
    }

	/**
	 * 系统第三方账号设置
	 */
	public function oauthSetting() {
		if (IS_POST) {
			$this->checkToken();

			$options = I('post.info');
			if (logic('options')->setOptionVals($options, true) === false) {
				$this->error('第三方账号设置失败');
			} else {
				$this->success('第三方账号设置成功');
			}
		} else {
			$options_tab = [
				[

					'id'      => 'tabs-wb',
					'name'    => '微博应用设置',
					'options' => [
						[
							'name' => 'wb_app_key',
							'key'  => 'App Key',
						],
						[
							'name' => 'wb_app_secret',
							'key'  => 'App Secret',
						],
						[
							'name' => 'wb_app_callback',
							'key'  => '回调地址',
						],
					],
				],
				[
					'id'      => 'tabs-qq',
					'name'    => 'QQ应用设置',
					'options' => [
						[
							'name' => 'qq_app_id',
							'key'  => 'App Key',
						],
						[
							'name' => 'qq_app_key',
							'key'  => 'App Secret',
						],
						[
							'name' => 'qq_app_callback',
							'key'  => '回调地址',
						],
					],
				],
			];
			$option_keys = [];
			foreach ($options_tab as $tab) {
				foreach ($tab['options'] as $key => $value) {
					$option_keys[] = $value['key'];
				}
			}
			$option_vals = logic('Options')->getOptionVals(array_keys($option_keys));
			$this->assign('options_tab', $options_tab);
			$this->assign('options', $option_vals);
			$this->display('oauthSetting');
		}
	}

}