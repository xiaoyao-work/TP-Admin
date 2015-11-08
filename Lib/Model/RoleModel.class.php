<?php
class RoleModel extends Model 
{
	public function roleList()
	{
		$M = M('role');
	    $list = $M->order('id ASC')->select();
	    //print_r($list);
		return $list;
	}
	public function addRole() 
	{
        $M = M('role');
        $datas['name'] 		= trim($_POST['name']);
        $datas['status'] 	= $_POST['status'];
        $datas['remark'] 	= trim($_POST['remark']);
        return $M->add($datas);
    }
	public function delRole($nid)
    {
    	$role = M('role');
    	$nid  = $_GET['nid'];
		return $role->where("id = '{$nid}'")->delete();
    }
	public function getRole($nid)
    {
    	$role = M('role');
    	$nid  = $_GET['nid'];
		$list = $role->where("id = '{$nid}'")->find();
		return $list;
    }
	public function editRole($nid)
    {		
		$M = M('role');
        $datas['name'] 		= trim($_POST['name']);
        $datas['status'] 	= $_POST['status'];
        $datas['remark'] 	= trim($_POST['remark']);
        $res = $M->where("id = '{$nid}'")->save($datas);
        return $res;
    }
}

?>
