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
 * 内容控制器
 */
class PostController extends CommonController {
	protected $db;

	function __construct() {
		parent::__construct();
		$this->beforeFilter('filterPostTypeAuth');
		$this->db = model("Post");
	}

	public function index() {
		// 分类，日期过滤
		$tax  = I('post.tax');
		$date = I('post.date');
		// 标题检索
		$title   = I('post.title');
		$modelid = I('get.moduleid', false);
		if (IS_POST) {
			unset($_GET['p']);
		}
		if (!$modelid) {
			$this->error('模型参数缺失！');
		}
		// 设置表模型
		$model = $this->db->setModel($modelid);
		if (empty($model)) {
			$this->error('模型不存在！');
		}
		// 初始化logic
		$post_logic          = logic('Post');
		$post_logic->modelDb = $this->db;
		$search_title_field  = 'title';
		// 初始化父模型
		if ($model['type'] == 2) {
			$this->parentModelDb = new \Model\Admin\PostModel;
			$parentModel         = $this->parentModelDb->setModel($model['parentid']);
			if (!$parentModel) {
				$this->error('父模型初始化失败！');
			}
			$parent_model_table_name = $this->parentModelDb->getTableName();
			$post_logic->parentModelJoin($parent_model_table_name);
			$post_logic->parentModelDb = $this->parentModelDb;
		}

		// 获取list fields
		$fields = $post_logic->getFields();
		$post_logic->registerFilter('tax', $tax);
		$post_logic->registerFilter('date', $date);
		$post_logic->registerFilter('like', [$search_title_field => $title]);

		// 获取文章
		$limit = cookie('list_rows_select') ? cookie('list_rows_select') : 20;
		$data  = $post_logic->getPosts($fields['select_fields'], ['listorder' => 'desc', 'id' => 'desc'], $limit);
		// 获取日期、分类信息
		$months     = (isset($parent_model_table_name) ? $this->db->getMonths($parent_model_table_name) : $this->db->getMonths());
		$post_types = [$model['tablename']];
		if (isset($parentModel)) {
			array_push($post_types, $parentModel['tablename']);
		}
		$taxonomies           = logic('taxonomy')->getPostTaxonomy($post_types);
		$termsGroupByTaxonomy = logic('category')->getPostTermsGroupByTaxonomy($post_types);

		// 搜索条件
		$this->assign('tax', $tax);
		$this->assign('date', $date);
		$this->assign('title', $title);
		// filter values
		$this->assign('months', $months);
		$this->assign('taxonomies', $taxonomies);
		$this->assign('termsGroupByTaxonomy', $termsGroupByTaxonomy);
		// contents
		$this->assign('module', $model);
		$this->assign('contents', $data['data']);
		$this->assign('list_fields', $fields['list_fields']);
		$this->assign('pages', $data['page']);
		$this->display();
	}

	public function add() {
		if (IS_POST) {
			$data  = I('post.info');
			$model = $this->db->setModel(I('post.moduleid'));
			$this->db->startTrans();
			if ($post = $this->db->addContent($data)) {
				$post_types = [$model['tablename']];
				if ($model['type'] == 2) {
					$parentModel = model('Model')->find($model['parentid']);
					array_push($post_types, $parentModel['tablename']);
				}
				// 分类处理 [ 可优化 ]
				$taxonomies = logic('taxonomy')->getPostTaxonomy($post_types);
				if (!empty($taxonomies)) {
					$category_post_datas = [];
					foreach ($taxonomies as $taxonomy) {
						$key       = $taxonomy['post_type'] . '_' . $taxonomy['name'];
						$tax_terms = I('post.' . $key, []);
						if (!empty($tax_terms)) {
							foreach ($tax_terms as $key => $value) {
								$category_post_datas[] = ['term_id' => $value, 'post_id' => $post['id'], 'post_type' => $taxonomy['post_type'], 'status' => $data['status'], 'listorder' => $post['listorder']];
							}
						}
					}
					if (!empty($category_post_datas)) {
						if (model('category_posts')->addAll($category_post_datas) === false) {
							$this->db->rollback();
							$this->error('添加失败！');
						}
					}
				}
				// 分类处理结束

				$this->db->commit();
				$this->success('添加成功!', U('Post/index', ['moduleid' => $_POST['moduleid']]));
			} else {
				$this->db->rollback();
				$this->error('添加失败！');
			}
		} else {
			$modelid = I('get.moduleid', false);
			if (!$modelid) {
				$this->error('模型参数缺失！');
			}
			$model = $this->db->setModel($modelid);
			if (empty($model)) {
				$this->error('模型不存在！');
			}

			// 初始化父模型
			if ($model['type'] == 2) {
				$this->parentModelDb = new \Model\Admin\PostModel;
				$parentModel         = $this->parentModelDb->setModel($model['parentid']);
				if (!$parentModel) {
					$this->error('父模型初始化失败！');
				}
			}
			$modelids   = [$modelid];
			$post_types = [$model['tablename']];
			if (isset($parentModel)) {
				array_push($post_types, $parentModel['tablename']);
				array_push($modelids, $parentModel['id']);
			}
			$taxonomies           = logic('taxonomy')->getPostTaxonomy($post_types);
			$termsGroupByTaxonomy = logic('category')->getPostTermsGroupByTaxonomy($post_types);

			require MODEL_PATH . 'content_form.class.php';
			$content_form = new \content_form($modelids);
			$forminfos    = $content_form->get();

			if (isset($content_form->fields['template'])) {
				$default_template = 'post-' . $model['tablename'];
				$template_list    = get_post_templates();
				$this->assign('template_list', $template_list);
				$this->assign('default_template', $default_template);
			}
			$this->assign('taxonomies', $taxonomies);
			$this->assign('termsGroupByTaxonomy', $termsGroupByTaxonomy);
			$this->assign('formValidator', $content_form->formValidator);
			$this->assign('forminfos', $forminfos);
			$this->assign('module', $model);
			$this->display();
		}
	}

	public function edit() {
		if (IS_POST) {
			/*$hash[C('TOKEN_NAME')] = $_POST[C('TOKEN_NAME')];
				            if (!$this->db->autoCheckToken($hash)) {
				            $this->error('令牌验证失败, 请刷新页面');
			*/
			$modelid = I('post.moduleid', false);
			$post_id = I('post.id', false);
			if (empty($modelid) || empty($post_id)) {
				$this->error('参数缺失！');
			}
			// 设置表模型
			$model = $this->db->setModel($modelid);
			if (empty($model)) {
				$this->error('模型不存在！');
			}
			$data = I('post.info');
			$post = $this->db->getContent($post_id);

			//资源包、内置资源再次上传
			if (in_array($model['tablename'], ['pack', 'default_effects']) && $post['resource_package'] != $data['resource_package']) {
				$data['cdn_status'] = 1;
			}

			$post_id = I('post.id');
			$this->db->startTrans();
			if ($this->db->editContent($post_id, $data)) {
				// 分类处理
				$post_types = [$model['tablename']];
				if ($model['type'] == 2) {
					$parentModel = model('Model')->find($model['parentid']);
					array_push($post_types, $parentModel['tablename']);
				}

				if (model('category_posts')->where(['post_id' => $post_id, 'post_type' => ['in', $post_types]])->delete() === false) {
					$this->db->rollback();
					$this->error('更新失败！');
				}

				// 分类处理 [ 可优化 ]
				$taxonomies = logic('taxonomy')->getPostTaxonomy($post_types);
				if (!empty($taxonomies)) {
					$category_post_datas = [];
					foreach ($taxonomies as $taxonomy) {
						$key       = $taxonomy['post_type'] . '_' . $taxonomy['name'];
						$tax_terms = I('post.' . $key, []);
						if (!empty($tax_terms)) {
							foreach ($tax_terms as $key => $value) {
								$category_post_datas[] = ['term_id' => $value, 'post_id' => $post_id, 'post_type' => $taxonomy['post_type'], 'status' => $data['status']];
							}
						}
					}
					if (!empty($category_post_datas)) {
						if (model('category_posts')->addAll($category_post_datas) === false) {
							$this->db->rollback();
							$this->error('更新失败！');
						}
					}
				}
				// 分类处理结束
				$this->db->commit();
				$this->success('更新成功!', U('Post/index', ['moduleid' => $model['id']]));
			} else {
				$this->db->rollback();
				$this->error('更新失败！');
			}
		} else {
			$modelid = I('get.moduleid', false);
			$post_id = I('get.id', false);
			if (!$modelid || !$post_id) {
				$this->error('模型参数缺失！');
			}
			$model = $this->db->setModel($modelid);
			if (empty($model)) {
				$this->error('模型不存在！');
			}
			$post = $this->db->getContent($post_id);
			if (empty($post)) {
				$this->error('内容不存在！');
			}
			$modelids   = [$modelid];
			$post_types = [$model['tablename']];
			// 初始化父模型
			if ($model['type'] == 2) {
				$this->parentModelDb = new \Model\Admin\PostModel;
				$parentModel         = $this->parentModelDb->setModel($model['parentid']);
				if (!$parentModel) {
					$this->error('父模型初始化失败！');
				}
				$parent_post = $this->parentModelDb->find($post_id);
				if (empty($parent_post)) {
					$this->error('内容不完整，请删除后重新添加!');
				}
				$post = array_merge($post, $parent_post);
				array_push($post_types, $parentModel['tablename']);
				array_push($modelids, $parentModel['id']);
			}

			$taxonomies           = logic('taxonomy')->getPostTaxonomy($post_types);
			$termsGroupByTaxonomy = logic('category')->getPostTermsGroupByTaxonomy($post_types);

			$category_posts = model('category_posts')->where(['post_id' => $post['id']])->select();
			$post_terms     = [];
			foreach ($category_posts as $key => $value) {
				$post_terms[] = $value['term_id'];
			}
			require MODEL_PATH . 'content_form.class.php';
			$content_form = new \content_form($modelids);
			$forminfos    = $content_form->get($post);

			if (isset($content_form->fields['template'])) {
				$template_list = get_post_templates();
				$this->assign('template_list', $template_list);
			}
			$this->assign('taxonomies', $taxonomies);
			$this->assign('termsGroupByTaxonomy', $termsGroupByTaxonomy);
			$this->assign('formValidator', $content_form->formValidator);
			$this->assign('forminfos', $forminfos);
			$this->assign('content', $post);
			$this->assign('module', $model);
			$this->assign('post_terms', $post_terms);
			$this->display();
		}
	}

	public function listorder() {
		$modelid = I('get.moduleid', false);
		if (!$modelid) {
			$this->error('模型参数缺失！');
		}
		$model = $this->db->setModel($modelid);
		if (empty($model)) {
			$this->error('模型不存在！');
		}
		$post_types = [$model['tablename']];
		if ($model['type'] == 2) {
			$parent_model = $this->db->setModel($model['parentid']);
			$post_types[] = $parent_model['tablename'];
		}

		if (isset($_POST['listorders']) && is_array($_POST['listorders'])) {
			$sort = $_POST['listorders'];
			$this->db->startTrans();
			$sql = "UPDATE `" . $this->db->getTableName() . "` SET `listorder` = CASE ";
			foreach ($sort as $k => $v) {
				$sql .= " WHEN `id` = " . $k . " THEN " . $v;
			}
			$sql .= " END WHERE `id` in (" . implode(',', array_keys($sort)) . ")";

			if ($this->db->execute($sql) === false) {
				$this->db->rollback();
				$this->error('操作失败！');
			};

			$category_posts_sql = "UPDATE `" . $this->db->getTablePrefix() . 'category_posts' . "` SET `listorder` = CASE ";
			foreach ($sort as $k => $v) {
				$category_posts_sql .= " WHEN `post_id` = " . $k . " THEN " . $v;
			}
			$category_posts_sql .= " END WHERE `post_type` in ('" . implode("','", $post_types) . "') and `post_id` in (" . implode(',', array_keys($sort)) . ")";

			if ($this->db->execute($category_posts_sql) === false) {
				$this->db->rollback();
				$this->error('操作失败！');
			};

			$this->db->commit();
		}
		$this->success('排序成功！');
	}

	public function delete() {
		$modelid = I('moduleid', false);
		if (!$modelid) {
			$this->error('模型参数缺失！');
		}
		$model = $this->db->setModel($modelid);
		if (empty($model)) {
			$this->error('模型不存在！');
		}
		// 初始化父模型
		if ($model['type'] == 2) {
			$parentModelDb = new \Model\Admin\PostModel;
			$parentModel   = $parentModelDb->setModel($model['parentid']);
			if (!$parentModel) {
				$this->error('父模型初始化失败！');
			}
		}
		$ids = IS_POST ? I('post.ids') : I('get.id');
		if (empty($ids)) {
			$this->error("您没有勾选信息");
		}
		$this->db->startTrans();
		if (isset($parentModel)) {
			$result = $this->db->deleteContent($ids) && $parentModelDb->deleteContent($ids);
		} else {
			$result = $this->db->deleteContent($ids);
		}
		if ($result) {
			$this->db->commit();
			$this->success('删除成功！');
		} else {
			$this->db->rollback();
			$this->error('删除失败！');
		}
	}

	/**
	 * 标题重复检测
	 */
	public function public_check_title() {
		if ($_GET['data'] == '' || (!$_GET['modelid'])) {
			exit('0');
		}

		$model = intval($_GET['modelid']);
		$model = $this->db->setModel($model);
		if ($model['type'] == 2) {
			$this->db->setModel($model['parentid']);
		}
		$title = $_GET['data'];
		$r     = $this->db->where(['title' => $title])->find();
		if ($r) {
			exit('1');
		} else {
			exit('0');
		}
	}

	public function getPosts() {
		$modelid = I('post.modelid', '');
		if (empty($modelid)) {
			$this->ajaxReturn(['code' => 10001, 'message' => '参数缺失！']);
		}
		$model = $this->db->setModel($modelid);
		if (empty($model)) {
			$this->ajaxReturn(['code' => 10002, 'message' => '参数错误！']);
		}

		$post_logic          = logic('Post');
		$post_logic->modelDb = $this->db;

		// 初始化父模型
		if ($model['type'] == 2) {
			$this->parentModelDb = new \Model\Admin\PostModel;
			$parentModel         = $this->parentModelDb->setModel($model['parentid']);
			if (!$parentModel) {
				$this->error('父模型初始化失败！');
			}
			$parent_model_table_name = $this->parentModelDb->getTableName();
			$post_logic->parentModelJoin($parent_model_table_name);
			$post_logic->parentModelDb = $this->parentModelDb;
		}

		$fields = $post_logic->getFields();
		$title  = I('post.title', '');
		$post_logic->registerFilter('like', ['title' => $title]);
		// 获取文章
		$limit             = cookie('list_rows_select') ? cookie('list_rows_select') : 20;
		$data              = $post_logic->getPosts($fields['select_fields'], ['listorder' => 'desc', 'id' => 'desc'], $limit);
		$selected_post_ids = I('post.selected_post_ids', []);
		foreach ($selected_post_ids as $key => $value) {
			if (empty($value)) {
				unset($selected_post_ids[$key]);
			}
		}
		$selected_posts = [];
		if (!empty($selected_post_ids)) {
			if ($model['type'] == 2) {
				$post_logic->parentModelJoin($parent_model_table_name);
			}
			$post_logic->registerFilter('condition', ['id' => ['in', $selected_post_ids]]);
			$selected_posts = $post_logic->getAllPosts($fields['select_fields'], ['id' => 'desc']);
		}
		$this->ajaxReturn(['code' => 0, 'message' => '', 'data' => ['avaliable_posts' => $data['data'], 'selected_posts' => $selected_posts]]);
	}

}