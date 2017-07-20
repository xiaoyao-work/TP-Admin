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
 *  模型字段管理类
 */
class ModelFieldController extends CommonController {
	protected $db, $model_db;
	function __construct() {
		parent::__construct();
		$this->db       = model('ModelField');
		$this->model_db = model('Model');
	}

	public function index() {
		$modelid = I('get.modelid');
		if (empty($modelid)) {
			$this->error('参数异常');
		}
		// 多站点模型共享
		// $fields = $this->db->where(array('siteid' => $this->siteid, 'modelid' => $modelid))->order("listorder asc , fieldid asc")->select();
		$fields = $this->db->where(['modelid' => $modelid])->order("listorder asc , fieldid asc")->select();
		$model  = $this->model_db->find($modelid);
		$this->assign('model', $model);
		$this->assign('fields', $fields);

		require MODEL_PATH . 'fields.inc.php';

		$this->assign('forbid_fields', $forbid_fields);
		$this->assign('forbid_delete', $forbid_delete);
		$this->assign('att_css_js', $att_css_js);
		$this->assign('not_allow_fields', $not_allow_fields);
		$this->assign('unique_fields', $unique_fields);

		$this->display();
	}

	public function add() {
		if (IS_POST) {
			$this->checkToken();

			$modelid   = $_POST['info']['modelid']   = intval($_POST['info']['modelid']);
			$model     = $this->model_db->find($modelid);
			$tablename = C('DB_PREFIX') . $model['tablename'];

			$field      = $_POST['info']['field'];
			$minlength  = $_POST['info']['minlength'] ? $_POST['info']['minlength'] : 0;
			$maxlength  = $_POST['info']['maxlength'] ? $_POST['info']['maxlength'] : 0;
			$field_type = $_POST['info']['formtype'];

			require MODEL_PATH . $field_type . DIRECTORY_SEPARATOR . 'config.inc.php';
			if (isset($_POST['setting']['fieldtype'])) {
				$field_type = $_POST['setting']['fieldtype'];
			}
			require MODEL_PATH . 'add.sql.php';

			//附加属性值
			$_POST['info']['setting'] = array2string($_POST['setting'], 0);
			$_POST['info']['siteid']  = $this->siteid;

			if ($this->db->add($_POST['info'])) {
				$this->success('添加成功');
			} else {
				$this->success('添加失败');
			}
		} else {
			require MODEL_PATH . 'fields.inc.php';
			$modelid = I('get.modelid');

			$f_datas = $this->db->where(['modelid' => $modelid])
				->order('listorder ASC')
				->field('field, name')
				->select();

			$model = $this->model_db->find($modelid);
			foreach ($f_datas as $_k => $_v) {
				$exists_field[] = $_v['field'];
			}

			$all_field = [];
			foreach ($fields as $_k => $_v) {
				if (in_array($_k, $not_allow_fields) || (in_array($_k, $exists_field) && in_array($_k, $unique_fields))) {
					continue;
				}

				$all_field[$_k] = $_v;
			}

			$this->assign('modelid', $modelid);
			$this->assign('model', $model);
			$this->assign('all_field', $all_field);

			$this->assign('forbid_fields', $forbid_fields);
			$this->assign('forbid_delete', $forbid_delete);
			$this->assign('att_css_js', $att_css_js);
			$this->assign('not_allow_fields', $not_allow_fields);
			$this->assign('unique_fields', $unique_fields);
			$this->display();
		}
	}

	public function edit() {
		if (IS_POST) {
			$this->checkToken();
			$modelid    = $_POST['info']['modelid']    = intval($_POST['info']['modelid']);
			$model      = $this->model_db->find($modelid);
			$tablename  = C('DB_PREFIX') . $model['tablename'];
			$field      = $_POST['info']['field'];
			$minlength  = $_POST['info']['minlength'] ? $_POST['info']['minlength'] : 0;
			$maxlength  = $_POST['info']['maxlength'] ? $_POST['info']['maxlength'] : 0;
			$field_type = $_POST['info']['formtype'];
			$issystem   = I('post.info.issystem');
			require MODEL_PATH . $field_type . DIRECTORY_SEPARATOR . 'config.inc.php';
			if (isset($_POST['setting']['fieldtype'])) {
				$field_type = $_POST['setting']['fieldtype'];
			}
			$oldfield = $_POST['oldfield'];
			require MODEL_PATH . 'edit.sql.php';
			//附加属性值
			$_POST['info']['setting'] = array2string(I('post.setting'), 0);
			$fieldid                  = intval($_POST['fieldid']);

			/*$_POST['info']['unsetgroupids'] = isset($_POST['unsetgroupids']) ? implode(',',$_POST['unsetgroupids']) : '';
			$_POST['info']['unsetroleids'] = isset($_POST['unsetroleids']) ? implode(',',$_POST['unsetroleids']) : '';*/
			// 多站点模型共享
			// $this->db->where(array('fieldid'=>$fieldid,'siteid'=>$this->siteid))->save($_POST['info'])
			if ($this->db->where(['fieldid' => $fieldid])->save($_POST['info']) !== false) {
				$this->success('更新成功');
			} else {
				$this->error('操作失败');
			}
		} else {
			require MODEL_PATH . 'fields.inc.php';
			$modelid     = intval($_GET['modelid']);
			$fieldid     = intval($_GET['fieldid']);
			$model       = $this->model_db->find($modelid);
			$model_field = $this->db->where(['fieldid' => $fieldid])->find();
			extract($model_field);
			// 获取相关设置参数
			require MODEL_PATH . $formtype . DIRECTORY_SEPARATOR . 'config.inc.php';
			$setting = string2array($setting);
			ob_start();
			include MODEL_PATH . $formtype . DIRECTORY_SEPARATOR . 'field_edit_form.inc.php';
			$form_data = ob_get_contents();
			ob_end_clean();

			$this->assign('forbid_fields', $forbid_fields);
			$this->assign('forbid_delete', $forbid_delete);
			$this->assign('att_css_js', $att_css_js);
			$this->assign('not_allow_fields', $not_allow_fields);
			$this->assign('unique_fields', $unique_fields);

			$this->assign('fields', $fields);
			$this->assign('modelid', $modelid);
			$this->assign('fieldid', $fieldid);
			$this->assign('model', $model);
			$this->assign('model_field', $model_field);
			$this->assign('form_data', $form_data);
			$this->display();
		}
	}

	public function delete() {
		$fieldid = intval(I('get.fieldid'));
		// 多站点模型共享
		//  $r = $this->db->where(array('fieldid'=>$_GET['fieldid'],'siteid'=>$this->siteid))->find();
		//  $this->db->where(array('fieldid'=>$_GET['fieldid'],'siteid'=>$this->siteid))->delete();
		$field = $this->db->find($fieldid);
		if (empty($fieldid)) {
			$this->error('字段不存在！');
		}
		$this->db->startTrans();
		$this->db->where(['fieldid' => $fieldid])->delete();
		$model     = $this->model_db->find($field['modelid']);
		$tablename = C('DB_PREFIX') . $model['tablename'];
		if ($this->db->drop_field($tablename, $field['field']) === false) {
			$this->db->rollback();
			$this->error('删除失败！');
		}
		$this->db->commit();
		$this->success('操作成功！');
	}

	/**
	 * 禁用字段
	 */
	public function disabled() {
		$fieldid  = I('get.fieldid');
		$disabled = I('get.disabled') ? 0 : 1;
		// 多站点模型共享
		// $this->db->where(array('fieldid'=>$fieldid,'siteid'=>$this->siteid))->save(array('disabled'=>$disabled))
		if ($this->db->where(['fieldid' => $fieldid])->save(['disabled' => $disabled]) !== false) {
			$this->success('操作成功！');
		} else {
			$this->error('操作失败！');
		}
	}

	/**
	 * 排序
	 */
	public function listorder() {
		if (isset($_POST['dosubmit'])) {
			foreach ($_POST['listorders'] as $id => $listorder) {
				$this->db->where(['fieldid' => $id])->save(['listorder' => $listorder]);
			}
			$this->success('操作成功！');
		} else {
			$this->error('操作失败！');
		}
	}

	/**
	 * 检查字段是否存在
	 */
	public function public_checkfield() {
		$field    = I('get.field');
		$oldfield = I('get.oldfield');
		if ($field == $oldfield) {
			exit('1');
		}

		$modelid = I('get.modelid');
		$model   = $this->model_db->find($modelid);
		if ($model['type'] == 2) {
			$modelid = [$model['parentid'], $modelid];
		}
		if ($this->db->fieldExist($field, $modelid) > 0) {
			exit('0');
		} else {
			exit('1');
		}
	}

	/**
	 * 字段属性设置
	 */
	public function public_field_setting() {
		$fieldtype = I('get.fieldtype');
		require MODEL_PATH . $fieldtype . DIRECTORY_SEPARATOR . 'config.inc.php';
		if ($fieldtype == 'relationship') {
			// 多站点模型共享
			// $models = model('Model')->where(array('siteid' => $this->siteid))->field('id', 'name', 'tablename')->select();
			$models = model('Model')->field('id', 'name', 'tablename')->select();
		}
		ob_start();
		include MODEL_PATH . $fieldtype . DIRECTORY_SEPARATOR . 'field_add_form.inc.php';
		$data_setting = ob_get_contents();
		ob_end_clean();

		$settings = [
			'field_basic_table'    => $field_basic_table,
			'field_minlength'      => $field_minlength,
			'field_maxlength'      => $field_maxlength,
			'field_allow_search'   => $field_allow_search,
			'field_allow_fulltext' => $field_allow_fulltext,
			'field_allow_isunique' => $field_allow_isunique,
			'setting'              => $data_setting];

		$this->ajaxReturn($settings);
	}
}