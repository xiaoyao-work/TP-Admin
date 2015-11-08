<?php
/**
 * 新闻内容操作类
*/
defined('IN_ADMIN') or exit('No permission resources.');
define('MODEL_PATH',COMMON_PATH.'Admin'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR);
class ContentAction extends CommonAction {
  /**
  * 生成树型结构所需修饰符号，可以换成图片
  * @var array
  */
  public $icon = array('│','├','└');
  public $nbsp = "&nbsp;";

  /**
  * @access private
  */
  public $ret = '';
  protected $db, $model_db, $category_db, $categorys, $treeview_str;
  function __construct() {
    parent::__construct();
    $this->db = D("Content");
    $this->model_db = D("Model");
    $this->category_db = D("Category");
    // $this->categorys = D('Category')->where("siteid = %d", $this->siteid)->select();
  }

  public function init() {
    $this->display();
  }

  public function index() {
    if (isset($_GET['catid']) && $_GET['catid'] && ($category = $this->category_db->where(array('siteid' => $this->siteid, 'id' => $_GET['catid']))->find())) {
      $this->db->set_model($category['modelid']);
      $search = array();
      if (isset($_GET['search'])) {
        if($_GET['start_time'] && !is_numeric($_GET['start_time'])) {
          $search['inputtime'] = array('gt',strtotime($_GET['start_time']));
        }
        if($_GET['end_time'] && !is_numeric($_GET['end_time'])) {
          $search['inputtime'] = array('lt',strtotime($_GET['end_time']));
        }
        if ($_GET['posids'] != "") {
          $search['posids'] = intval($_GET['posids']);
        }
        if ($_GET['keyword']) {
          switch (intval($_GET['searchtype'])) {
            case 0:
            $search['title'] = array('like', "%".safe_replace($_GET['keyword'])."%");
            break;
            case 1:
            $search['description'] = array('like', "%".safe_replace($_GET['keyword'])."%");
            break;
            case 2:
            $search['username'] = safe_replace($_GET['keyword']);
            case 3:
            $search['id'] = intval($_GET['keyword']);
            break;
            default:
            break;
          }
        }
      }
      $data = $this->db->content_list(array_merge(array('catid' => $_GET['catid'], 'status' => 99),$search), "listorder desc, id desc");
      $this->assign('catid', $_GET['catid']);
      $this->assign('contents',$data['data']);
      $this->assign('pages',$data['page']);
      $this->display();
    } else {
      $this->display("init");
    }
  }

  public function add() {
    if (IS_POST) {
      $hash[C('TOKEN_NAME')] = $_POST[C('TOKEN_NAME')];
      $this->db->set_model($_POST['modelid']);
      if (!$this->db->autoCheckToken($hash)) {
        $this->error('令牌验证失败！');
      }
      if ($id=$this->db->add_content()) {
        if ($_POST['dosubmit_continue']) {
          $this->success('添加成功，ID='.$id);
        } else {
          $returnjs = 'function set_time() {$("#secondid").html(1);}setTimeout("set_time()", 500);setTimeout("window.close()", 1200);';
          $this->assign('closeWin', true);
          $this->assign('returnjs', $returnjs);
          $this->success('添加成功，<span id="secondid">2</span>秒后关闭！');
        }
      } else {
        echo $this->db->getLastSql();
        // $this->error('添加失败！');
      }
    } else {
      import('ORG.Util.Form');
      $catid = intval($_GET['catid']);
      $category = $this->category_db->where("siteid = %d and id = %d", $this->siteid, $catid)->order('listorder desc, id asc')->find();
      $categorys = $this->category_db->where('siteid = %d',$this->siteid)->select();
      $this->categorys = array();
      if (!empty($categorys)) {
        foreach ($categorys as $key => $r) {
          $this->categorys[$r['id']] = $r;
        }
      }
      $modelid = intval($category['modelid']);
      require MODEL_PATH.'content_form.class.php';
      $content_form = new content_form($modelid, $catid, $this->categorys);
      $forminfos = $content_form->get();
      $this->assign('formValidator', $content_form->formValidator);
      $this->assign('forminfos', $forminfos);
      $this->assign('category', $category);
      $this->display();

      /*load('extend');
      $catid = intval($_GET['catid']);
      $category = $this->category_db->where("siteid = %d and id = %d", $this->siteid, $catid)->find();

      // 推荐位载入
      import("ORG.Util.Form");
      $categorys = $this->category_db->where('siteid = %d',$this->siteid)->field('id, arrchildid')->select();
      $this->categorys = array();
      if (!empty($categorys)) {
        foreach ($categorys as $key => $r) {
          $this->categorys[$r['id']] = $r;
        }
      }
      $position = D('Position')->select();
      if(empty($position)) return '';
      $array = array();
      foreach($position as $_key=>$_value) {
        if($_value['modelid'] && ($_value['modelid'] !=  $category['modelid']) || ($_value['catid'] && strpos(','.$this->categorys[$_value['catid']]['arrchildid'].',',','.$catid.',')===false)) {
          continue;
        }
        $array[$_value['id']] = $_value['name'];
      }
      $posidstr = form::checkbox($array,'',"name='info[posids][]'",'',125);
      // END 推荐位载入

      $this->assign('posidstr', $posidstr);
      $this->assign('category', $category);
      $this->assign('catid', $catid);
      $this->display();*/
    }
  }

  public function edit() {
    if (IS_POST) {
      $hash[C('TOKEN_NAME')] = $_POST[C('TOKEN_NAME')];
      $this->db->set_model($_POST['modelid']);
      if (!$this->db->autoCheckToken($hash)) {
        $this->error('令牌验证失败！');
      }
      if ($id = $this->db->edit_content()) {
        if ($_POST['dosubmit_continue']) {
          $this->success('更新成功');
        } else {
          $returnjs = 'function set_time() {$("#secondid").html(1);}setTimeout("set_time()", 500);setTimeout("window.close()", 1200);';
          $this->assign('closeWin', true);
          $this->assign('returnjs', $returnjs);
          $this->success('更新成功，<span id="secondid">2</span>秒后关闭！');
        }
      } else {
        // echo $this->db->getLastSql();
        $this->error('更新失败！');
      }
    } else {
      import('ORG.Util.Form');
      $catid = intval($_GET['catid']);
      $category = $this->category_db->where("siteid = %d and id = %d", $this->siteid, $catid)->find();
      $modelid = intval($category['modelid']);
      $this->db->set_model($modelid);
      $content = $this->db->get_content(intval($_GET['contentid']));
      $categorys = $this->category_db->where('siteid = %d',$this->siteid)->order('listorder desc, id asc')->select();
      $this->categorys = array();
      if (!empty($categorys)) {
        foreach ($categorys as $key => $r) {
          $this->categorys[$r['id']] = $r;
        }
      }
      require MODEL_PATH.'content_form.class.php';
      $content_form = new content_form($modelid, $catid, $this->categorys);
      $forminfos = $content_form->get($content);
      $this->assign('formValidator', $content_form->formValidator);
      $this->assign('catid', $catid);
      $this->assign('forminfos', $forminfos);
      $this->assign('content_id', $content['id']);
      $this->assign('modelid', $modelid);
      /*load('extend');
      $catid = intval($_GET['catid']);
      $category = $this->category_db->where("siteid = %d and id = %d", $this->siteid, $catid)->find();
      $this->db->set_model($category['modelid']);
      $content = $this->db->get_content(intval($_GET['contentid']),$category['id']);

      // 推荐位载入
      import("ORG.Util.Form");
      $categorys = $this->category_db->where('siteid = %d',$this->siteid)->field('id, arrchildid')->select();
      $this->categorys = array();
      if (!empty($categorys)) {
        foreach ($categorys as $key => $r) {
          $this->categorys[$r['id']] = $r;
        }
      }
      $position = D('Position')->select();
      if(empty($position)) return '';
      $array = array();
      foreach($position as $_key=>$_value) {
        if($_value['modelid'] && ($_value['modelid'] !=  $category['modelid']) || ($_value['catid'] && strpos(','.$this->categorys[$_value['catid']]['arrchildid'].',',','.$catid.',')===false))  {
          continue;
        }
        $array[$_value['id']] = $_value['name'];
      }
      $position_data = D('PositionData')->where('id = %d and modelid = %d', $content['id'], $category['modelid'])->field('posid')->group('posid')->select();
      $position_data_ids = array();
      foreach ($position_data as $key => $pos) {
        $position_data_ids[] = $pos['posid'];
      }
      $posids = implode(',', $position_data_ids);
      $posidstr = form::checkbox($array,$posids,"name='info[posids][]'",'',125);
      // END 推荐位载入

      $this->assign('posidstr',$posidstr);
      $this->assign('content', $content);
      $this->assign('category', $category);
      $this->assign('catid', intval($_GET['catid']));*/
      $this->display();
    }
  }

  public function listorder(){
    if (isset($_GET['catid']) && $_GET['catid'] && ($category = $this->category_db->where(array('siteid' => $this->siteid, 'id' => $_GET['catid']))->find())) {
      $this->db->set_model($category['modelid']);
      if (isset($_POST['listorders']) && is_array($_POST['listorders'])) {
        $sort = $_POST['listorders'];
        foreach ($sort as $k => $v) {
          $this->db->where(array('id'=>$k))->save(array('listorder'=>$v));
        }
      }
      $this->success('排序成功');
    } else {
      $this->error('栏目不存在');
    }
  }

  public function delete() {
    if (isset($_GET['catid']) && $_GET['catid'] && ($category = $this->category_db->where(array('siteid' => $this->siteid, 'id' => $_GET['catid']))->find())) {
      $this->db->set_model($category['modelid']);
      if (IS_POST) {
        $ids = $_POST['ids'];
        if (!empty($ids) && is_array($ids)) {
          if ($this->db->delete_content($ids)) {
            $this->success('删除成功！');
          } else {
            $this->error('删除失败！');
          }
        } else {
          $this->error("您没有勾选信息");
        }
      } else {
        if ($this->db->delete_content(intval($_GET['id']))) {

          // 删除推荐位数据

          //

          $this->success('删除成功！');
        } else {
          $this->error('删除失败！');
        }
      }
    } else {
      $this->error('栏目不存在');
    }
  }

  public function public_categorys() {
    $categorys = $this->category_db->where('siteid = %d',$this->siteid)->order('listorder desc, id asc')->field('id, catname, type, parentid')->select();
    $this->categorys = array();
    if (!empty($categorys)) {
      foreach ($categorys as $key => $r) {
        if (!$r['type']) {
          $r['icon_type'] = 'file';
          $r['add_icon'] = '';
          $r['type'] = U('Content/add');
        } else {
          $r['icon_type'] = '';
          $r['type'] = U('Content/index');
          $r['add_icon'] = "<a target='right' href='". U('Content/index') ."?catid=".$r['id']."' onclick=javascript:openwinx('". U('Content/add') ."?catid=".$r['id']."','')><img src='". C('SITE_URL') ."Public/images/admin/add_content.gif' alt='添加内容'></a> ";
        }
        $this->categorys[$r['id']] = $r;
      }
    }
    if (!empty($this->categorys)) {
      $strs = "<span class='\$icon_type'>\$add_icon<a href='\$type?catid=\$id' target='right' onclick='open_list(this)'>\$catname</a></span>";
      $strs2 = "<span class='folder'>\$catname</span>";
      $this->get_treeview(0,'category_tree',$strs,$strs2);
    } else {
      $categorys = "请先添加栏目";
    }
    $this->assign('categorys',$this->treeview_str);
    $this->display();
  }

  public function add_othors() {
    $this->assign('siteid',$this->siteid);
    $this->display();
  }


  /**
   * 同时发布到其他栏目 异步加载栏目
   */
  public function public_getsite_categorys() {
    $siteid = intval($_GET['siteid']);
    $categorys = $this->category_db->where('siteid = %d and type = 1',$this->siteid)->order('listorder desc, id asc')->field('id, catname, siteid, modelid, type, parentid, child')->select();
    $models = array();
    $models_origin = $this->model_db->where(array('siteid' => $this->siteid, 'status' => 1))->select();
    foreach ($models_origin as $key => $value) {
      $models[$value['id']] = $value;
    }
    $this->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
    $this->nbsp = '&nbsp;&nbsp;&nbsp;';
    $this->categorys = array();
    foreach($categorys as $r) {
      if($r['siteid']!= $this->siteid || $r['type'] !=1) continue;
      $r['modelname'] = $models[$r['modelid']]['name'];
      $r['style'] = $r['child'] ? 'color:#8A8A8A;' : '';
      $r['click'] = $r['child'] ? '' : "onclick=\"select_list(this,'".safe_replace($r['catname'])."',".$r['id'].")\" class='cu' title='".L('click_to_select')."'";
      $this->categorys[$r['id']] = $r;
    }
    $str  = "<tr \$click >
    <td align='center'>\$id</td>
    <td style='\$style'>\$spacer\$catname</td>
    <td align='center'>\$modelname</td>
    </tr>";
    $categorys = $this->get_tree(0, $str);
    echo $categorys;
  }


  public function show_relation() {
    $modelid = intval($_GET['modelid']);
    $categorys = $this->category_db->where('siteid = %d',$this->siteid)->select();
    $this->categorys = array();
    if (!empty($categorys) && is_array($categorys)) {
      foreach ($categorys as $key => $r) {
        $this->categorys[$r['id']] = $r;
      }
    }
    $id = intval($_GET['id']);
    $model = D("Content");
    $model->set_model($modelid);
    $r = $model->get_content( $id );

    if( !empty($r['relation'])) {
      $relation = $r['relation']['ids'];
      $relation_ids = explode( '|', $relation['ids'] );
      if ( empty($relation_ids) ) {
        exit( json_encode( array() ) );
      }
      if ( !empty($relation['cats']) ) {
        $relation_cats = explode( '|', $relation['cats'] );
        $datas = array();
        foreach ($relation_ids as $key => $value) {
          $model->set_model( $this->categorys[$relation_cats[$key]]['modelid'] );
          $content = $model->get_content($value, true);
          if ( !empty($content) ) {
            $datas[] = $content;
          }
        }
      } else {
        $model->set_model($modelid);
        $where = "id IN(".join(',',$relation_ids).")";
        $datas = $model->where($where)->select();
      }

      $infos = array();
      foreach($datas as $_v) {
        $_v['sid'] = 'v'.$_v['catid'].$_v['id'];
        $infos[] = $_v;
      }
      echo json_encode($infos);
    }
  }

  public function public_relationlist() {
    $catid = intval($_GET['catid']);
    $modelid = intval($_GET['modelid']);
    if ( isset($catid) && !empty($catid) ) {
      $category = $this->category_db->where('siteid = %d and id = %d',$this->siteid, $catid)->find();
    } else {
      $category['modelid'] = $modelid ? $modelid : 1;
    }
    $categorys = $this->category_db->where('siteid = %d and type = 1',$this->siteid)->field('id, catname, type, parentid, child')->select();
    $this->categorys = array();
    if (!empty($categorys) && is_array($categorys)) {
      foreach ($categorys as $key => $r) {
        $r['selected'] = '';
        if(is_array($catid)) {
          $r['selected'] = in_array($r['id'], $catid) ? 'selected' : '';
        } elseif(is_numeric($catid)) {
          $r['selected'] = ($catid==$r['id'] ? 'selected' : '');
        }
        $r['html_disabled'] = "0";
        if ($r['child'] != 0) {
          $r['html_disabled'] = "1";
        }
        $this->categorys[$r['id']] = $r;
      }
    }
    $string = "<select name='catid'><option value='0'>不限栏目</option>";
    $str  = "<option value='\$id' \$selected>\$spacer \$catname</option>;";
    $str2 = "<optgroup label='\$spacer \$catname'></optgroup>";
    $string .= $this->get_tree_category(0, $str, $str2);
    $string .= '</select>';
    $where = array();
    if ($catid) {
      $where['catid'] = $catid;
    }
    if ($_GET['keywords']) {
      $where['title'] = array('like', "%{$_GET['keywords']}%");
    }
    $this->db->set_model($category['modelid']);
    $data = $this->db->content_list($where);
    $this->assign('infos',$data['data']);
    $this->assign('pages',$data['page']);
    $this->assign('modelid', $category['modelid']);
    $this->assign('categorys',$this->categorys);
    $this->assign('catstring', $string);
    $this->display();
  }

  public function public_check_title() {
    if($_GET['data']=='' || (!$_GET['modelid'])) return '';
    $modelid = intval($_GET['modelid']);
    $this->db->set_model($modelid);
    $title = $_GET['data'];
    $r = $this->db->where(array('title'=>$title))->find();
    if($r) {
      exit('1');
    } else {
      exit('0');
    }
  }

  protected function get_treeview($myid,$effected_id='example',$str="<span class='file'>\$name</span>", $str2="<span class='folder'>\$name</span>" ,$showlevel = 0 ,$style='filetree ' , $currentlevel = 1,$recursion=FALSE) {
    $child = $this->get_child($myid);
    if(!defined('EFFECTED_INIT')){
      $effected = ' id="'.$effected_id.'"';
      define('EFFECTED_INIT', 1);
    } else {
      $effected = '';
    }
    if(!$recursion) $this->treeview_str .= '<ul'.$effected.'  class="'.$style.'">';
    foreach($child as $key=>$a) {
      @extract($a);
      $this->treeview_str .= $recursion ? '<ul><li id=\''.$id.'\'>' : '<li id=\''.$id.'\'>';
      $recursion = FALSE;
      if ($this->get_child($id)) {
        eval("\$nstr = \"$str2\";");
        $this->treeview_str .= $nstr;
        $this->get_treeview($id,$effected_id,$str, $str2,$showlevel,$style,$currentlevel,true);
      } else {
        eval("\$nstr = \"$str\";");
        $this->treeview_str .= $nstr;
      }
      $this->treeview_str .=$recursion ? '</li></ul>': '</li>';
    }
    if(!$recursion)  $this->treeview_str .='</ul>';
    // return $this->treeview_str;
  }

  /**
  * 得到树型结构
  * @param int ID，表示获得这个ID下的所有子级
  * @param string 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
  * @param int 被选中的ID，比如在做树型下拉框的时候需要用到
  * @return string
  */
  protected function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = ''){
    $number=1;
    $child = $this->get_child($myid);
    if(is_array($child)){
      $total = count($child);
      foreach($child as $id=>$value){
        $j=$k='';
        if($number==$total){
          $j .= $this->icon[2];
        }else{
          $j .= $this->icon[1];
          $k = $adds ? $this->icon[0] : '';
        }
        $spacer = $adds ? $adds.$j : '';
        $selected = $id==$sid ? 'selected' : '';
        @extract($value);
        $parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
        $this->ret .= $nstr;
        $nbsp = $this->nbsp;
        $this->get_tree($id, $str, $sid, $adds.$k.$nbsp,$str_group);
        $number++;
      }
    }
    return $this->ret;
  }

  /**
  * 得到子级数组
  * @param int
  * @return array
  */
  protected function get_child($myid){
    $newarr = array();
    if(is_array($this->categorys)){
      foreach($this->categorys as $id => $a){
        if($a['parentid'] == $myid) $newarr[$id] = $a;
      }
    }
    return $newarr ? $newarr : false;
  }


  /**
  * @param integer $myid 要查询的ID
  * @param string $str   第一种HTML代码方式
  * @param string $str2  第二种HTML代码方式
  * @param integer $sid  默认选中
  * @param integer $adds 前缀
  */
  protected function get_tree_category($myid, $str, $str2, $sid = 0, $adds = ''){
    $number=1;
    $child = $this->get_child($myid);
    if(is_array($child)){
      $total = count($child);
      foreach($child as $id=>$a){
        $j=$k='';
        if($number==$total){
          $j .= $this->icon[2];
        }else{
          $j .= $this->icon[1];
          $k = $adds ? $this->icon[0] : '';
        }
        $spacer = $adds ? $adds.$j : '';

        $selected = $this->have($sid,$id) ? 'selected' : '';
        @extract($a);
        if (empty($html_disabled)) {
          eval("\$nstr = \"$str\";");
        } else {
          eval("\$nstr = \"$str2\";");
        }
        $this->ret .= $nstr;
        $this->get_tree_category($id, $str, $str2, $sid, $adds.$k.'&nbsp;');
        $number++;
      }
    }
    return $this->ret;
  }

  protected function have($list,$item){
    return(strpos(',,'.$list.',',','.$item.','));
  }
}
?>