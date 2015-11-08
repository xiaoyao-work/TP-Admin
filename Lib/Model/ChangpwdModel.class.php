<?php
class ChangpwdModel extends Model {
	public function eheckpwd($old_pwd,$new_pwd,$re_pwd) {
		$M = M('user');
		$id = $_SESSION['user_info']['id'];
		$res = $M->find($id);
		if(md5($old_pwd) != $res['password']) {
			return $rs = 'a';
		} elseif($new_pwd != $re_pwd || strlen($new_pwd) < 5 || strlen($new_pwd) > 50) {
			return $rs = 'b';
		}	else {
			$data['password'] = md5($new_pwd);
			return $M->where("id = '{$id}'")->save($data) === false ? 0 : 1;
		}
	}
}
?>
