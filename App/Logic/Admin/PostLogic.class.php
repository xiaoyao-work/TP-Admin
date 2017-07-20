<?php
// +----------------------------------------------------------------------
// | TP-Admin [ 多功能后台管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2016 http://www.hhailuo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 逍遥·李志亮 <xiaoyao.working@gmail.com>
// +----------------------------------------------------------------------

namespace Logic\Admin;
use Logic\BaseLogic;

/**
 * POST 逻辑处理
 */
class PostLogic extends BaseLogic {
	protected $filters;
	protected $modelDb;
	protected $parentModelDb = '';

	public function getFields() {
		$modelDb       = $this->modelDb;
		$parentModelDb = $this->parentModelDb;
		if (empty($parentModelDb)) {
			$fields         = $modelDb->getListFields(['field', 'name']);
			$default_fields = ['id', 'listorder', 'updatetime'];
		} else {
			$model_fields        = $modelDb->getListFields(['field', 'name', 'listorder']);
			$parent_model_fields = $parentModelDb->getListFields(['field', 'name', 'listorder']);

			$model_db_name        = $modelDb->getTableName();
			$parent_model_db_name = $parentModelDb->getTableName();
			foreach ($model_fields as $key => $value) {
				$value['field'] = $model_db_name . '.' . $value['field'];
				$fields[]       = $value;
			}
			foreach ($parent_model_fields as $key => $value) {
				$value['field'] = $parent_model_db_name . '.' . $value['field'];
				$fields[]       = $value;
			}
			$fields = array_merge($model_fields, $parent_model_fields);
			uasort($fields, function ($a, $b) {
				if ($a['listorder'] == $b['listorder']) {
					return 0;
				}
				return ($a['listorder'] < $b['listorder']) ? -1 : 1;
			});
			$default_fields = [$model_db_name . '.id', $parent_model_db_name . '.listorder', $parent_model_db_name . '.updatetime'];
		}
		$select_fields = $default_fields;
		foreach ($fields as $key => $field) {
			$select_fields[] = $field['field'];
		}
		return ['list_fields' => $fields, 'select_fields' => $select_fields];
	}

	public function getOrder($order) {
		if (empty($this->parentModelDb) || is_string($order)) {
			return $order;
		}
		$fields                  = array_keys($order);
		$model_db_fields         = $this->modelDb->parseField($fields);
		$order_string            = '';
		$model_table_name        = $this->modelDb->getTableName();
		$parent_model_table_name = $this->parentModelDb->getTableName();
		foreach ($order as $key => $value) {
			$order_string .= ', ' . (isset($model_db_fields[$key]) ? $model_table_name . '.' . $key . ' ' . $value : $parent_model_table_name . '.' . $key . ' ' . $value);
		}
		return substr($order_string, 1);
	}

	/**
	 * 获取内容
	 * @param  string  $fields         [description]
	 * @param  string  $order          [description]
	 * @param  integer $limit          [description]
	 * @return [type]  [description]
	 */
	public function getPosts($fields = '*', $order = ['listorder' => 'desc', 'id' => 'desc'], $limit = 20) {
		$order = $this->getOrder($order);
		$this->execFilter();
		$pagenum = I('get.p', 1);
		// 站点过滤
		$this->conditionFilter(['siteid' => get_siteid()]);
		// 缓存查询条件
		$post_model    = clone $this->modelDb;
		$posts         = $this->modelDb->field($fields)->order($order)->page($pagenum . ', ' . $limit)->select();
		$this->filters = [];
		// 分页数据
		$count     = $post_model->count();
		$page      = new \Think\Page($count, $limit);
		$page_html = $page->show();
		return ['data' => $posts, 'page' => $page_html];
	}

	/**
	 * 获取内容
	 * @param  string  $fields         [description]
	 * @param  string  $order          [description]
	 * @param  integer $limit          [description]
	 * @return [type]  [description]
	 */
	public function getAllPosts($fields = '*', $order = ['listorder' => 'desc', 'id' => 'desc']) {
		$order = $this->getOrder($order);
		$this->execFilter();
		// 缓存查询条件
		$posts         = $this->modelDb->field($fields)->order($order)->select();
		$this->filters = [];
		// 分页数据
		return $posts;
	}

	public function parentModelJoin($parent_table_name) {
		$this->modelDb->join($parent_table_name . " ON " . $parent_table_name . ".id = " . $this->modelDb->getTableName() . ".id");
	}

	/**
	 * 过滤注册
	 * @param  [type] $type           [description]
	 * @param  [type] $data           [description]
	 * @return [type] [description]
	 */
	public function registerFilter($type, $data) {
		if (is_array($data)) {
			$data = array_filter($data);
		}
		if (empty($data)) {
			return;
		}
		$this->filters[$type] = $data;
	}

	protected function execFilter() {
		if (empty($this->filters)) {
			return;
		}
		foreach ($this->filters as $type => $value) {
			$func = $type . 'Filter';
			if (method_exists($this, $func)) {
				$this->$func($value);
			}
		}
	}

	/**
	 * 条件过滤
	 * @param string $date 日期
	 */
	protected function conditionFilter($condition) {
		if (is_array($condition) && !empty($condition)) {
			$condition = $this->conditionFieldAdatar($condition);
			$this->modelDb->where($condition);
		}
	}

	/**
	 * 对条件进行字段所属模型过滤
	 */
	protected function conditionFieldAdatar($condition) {
		foreach ($condition as $key => $value) {
			if (strpos($key, $this->modelDb->getTableName()) !== false || (!empty($this->parentModelDb) && strpos($key, $this->parentModelDb->getTableName()))) {
				continue;
			}
			if ($this->modelDb->fieldExist($key)) {
				unset($condition[$key]);
				$key             = $this->modelDb->getTableName() . '.' . $key;
				$condition[$key] = $value;
				continue;
			}
			if ($this->parentModelDb->fieldExist($key)) {
				unset($condition[$key]);
				$key             = $this->parentModelDb->getTableName() . '.' . $key;
				$condition[$key] = $value;
				continue;
			}
		}
		return $condition;
	}

	/**
	 * 日期过滤(按月) 格式 2016-01
	 * @param string $date 日期
	 */
	protected function dateFilter($date) {
		if (!empty($date)) {
			$this->modelDb->where(['_query' => "DATE_FORMAT(`inputtime`,'%Y-%m')=" . addslashes($date)]);
		}
	}

	protected function likeFilter($data) {
		foreach ($data as $key => $value) {
			if (empty($value)) {
				continue;
			}

			$this->modelDb->where([$key => ['like', '%' . $value . '%']]);
		}
	}

	protected function taxFilter($taxs) {
		$terms                     = [];
		$table_name                = $this->modelDb->getTableName();
		$category_posts_table_name = C('DB_PREFIX') . "category_posts";
		foreach (array_values($taxs) as $key => $value) {
			if (!empty($value)) {
				// $sql = "(select post_id from " . C('DB_PREFIX') . "category_posts where term_id = " . $value . ")";
				// $this->modelDb->where(array('id' => array('exp', ' in ' . $sql)));
				$this->modelDb->join($category_posts_table_name . " as c" . $key . " ON " . $table_name . ".id = c" . $key . ".post_id")->where(["c" . $key . ".term_id" => $value]);
			}
		}
	}

	function __set($name, $value) {
		$this->$name = $value;
	}

}