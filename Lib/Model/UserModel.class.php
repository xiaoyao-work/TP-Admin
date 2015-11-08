<?php
class UserModel extends Model
{
	public function adminList() {
		$user = M('user');
		$sql = "SELECT user.*, role.name, role.id as role_id FROM ".C("DB_PREFIX")."user AS user, ".C("DB_PREFIX")."role AS role, ".C("DB_PREFIX")."role_user as ru WHERE user.id = ru.user_id and ru.role_id = role.id ORDER BY user.id ASC";
		$list = $user->query($sql);
		// return M('user')->select();
		return $list;
	}

	public function roleList() {
		$role = M('role');
		$list = $role->field('id,name')->where('id > 1')->order('id ASC')->select();
		return $list;
	}

	public function addUser() {
		$user = M('user');
		$datas['account'] 		= trim($_POST['account']);
		$datas['update_time'] 	= time();
		$datas['password'] 		= md5(trim($_POST['pwd']));
		$datas['status'] 		= $_POST['status'];
		$datas['create_time'] 	= time();
		$datas['role_id'] = intval($_POST['role_id']);
		$res1 = $user->add($datas);

		$role_user = M('role_user');
		$data['role_id'] 		= $_POST['role_id'];
		$data['user_id'] 		= $res1;
		$res2 = $role_user->add($data);
		return $res2;
	}
}
?>
