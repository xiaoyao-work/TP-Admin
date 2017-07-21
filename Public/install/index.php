<?php
$root_path = dirname(dirname(__DIR__));
if (file_exists($root_path . "/install.lock")) {
	echo "系统已安装!";
	exit();
}
$action = isset($_GET['action']) ? $_GET['action'] : 'licence';

function mysql_check($db_config) {
	$link = mysqli_connect($db_config['DB_HOST'], $db_config['DB_USER'], $db_config['DB_PWD'], $db_config['DB_NAME'], $db_config['DB_PORT']) or die('Not connected : ' . mysqli_connect_error());
	if (mysqli_connect_error()) {
		echo '<p class="text-danger">Mysql链接错误 (' . mysqli_connect_errno() . ') ' . mysqli_connect_error() . '</p>';
		return false;
	}
	$version = mysqli_get_server_info($link);
	if ($version < '5.5') {
		echo '<p class="text-danger">Mysql 版本过低！你升级MySQL版本到5.5或者更高</p>';
		return false;
	}
	return $link;
}

function _sql_execute($link, $sql, $r_tablepre = '', $s_tablepre = 'xy_') {
	$sqls = _sql_split($sql, $r_tablepre, $s_tablepre);
	if (is_array($sqls)) {
		foreach ($sqls as $sql) {
			if (trim($sql) != '') {
				if (mysqli_query($link, $sql) == false) {
					echo "<p class='text-danger'>SQL: " . $sql . " 执行失败！</p>";
				}
			}
		}
	} else {
		if (mysqli_query($link, $sqls) == false) {
			echo "<p class='text-danger'>SQL: " . $sqls . " 执行失败！</p>";
		}
	}
	return true;
}

function _sql_split($sql, $r_tablepre = '', $s_tablepre = 'xy_') {
	if ($r_tablepre != $s_tablepre) {
		$sql = str_replace($s_tablepre, $r_tablepre, $sql);
	}

	$sql          = str_replace("\r", "\n", $sql);
	$ret          = [];
	$num          = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach ($queriesarray as $query) {
		$ret[$num] = '';
		$queries   = explode("\n", trim($query));
		$queries   = array_filter($queries);
		foreach ($queries as $query) {
			$str1 = substr($query, 0, 1);
			if ($str1 != '#' && $str1 != '-') {
				$ret[$num] .= $query;
			}
		}
		$num++;
	}
	return $ret;
}

switch ($action) {
case 'guide':
	require './step/guide.php';
	break;
case 'requirement':
	$extensions = get_loaded_extensions();
	require './step/requirement.php';
	break;
case 'configure':
	$database_info = require $root_path . '/App/Common/Conf/database.php';
	require './step/configure.php';
	break;
case 'database-check':
	extract($_POST);
	$link = @mysqli_connect($DB_HOST, $DB_USER, $DB_PWD, null, $DB_PORT);
	if (!$link) {
		exit(json_encode(['code' => 2, 'message' => '无法连接数据库服务器，请检查配置！']));
	}
	$server_info = mysqli_get_server_info($link);
	if ($server_info < '5.5') {
		exit(json_encode(['code' => 6, 'message' => 'TP-Admin 要求MySQL版本大于5.5，请升级你的MySQL版本']));
	}
	if (!mysqli_select_db($link, $DB_NAME)) {
		if (!@mysqli_query($link, "CREATE DATABASE `$DB_NAME`")) {
			exit(json_encode(['code' => 3, 'message' => '成功连接数据库，但是指定的数据库不存在并且无法自动创建，请先通过其他方式建立数据库！']));
		}
		mysqli_select_db($link, $DB_NAME);
	}
	$tables = [];
	$query  = mysqli_query($link, "SHOW TABLES FROM `$DB_NAME`");
	while ($r = mysqli_fetch_row($query)) {
		$tables[] = $r[0];
	}
	if ($tables && in_array($DB_PREFIX . 'model', $tables)) {
		exit(json_encode(['code' => 0, 'message' => '您已经安装过TP-Admin，系统会自动删除老数据！是否继续？']));
	} else {
		exit(json_encode(['code' => 200, 'message' => '']));
	}
	break;
case 'install':
	// 写数据库配置 && 基础配置
	$error           = false;
	$index_file_path = $root_path . '/public/index.php';
    $database_path = $root_path . '/App/Common/Conf/database.php';
	if (is_writable($index_file_path) === false) {
		$error           = true;
		$error_message[] = "项目入口文件 {$index_file_path} 不可写！";
	}
    if (is_writable($database_path) === false) {
        $error           = true;
        $error_message[] = "DB配置文件 {$database_path} 不可写！";
    }
	if (!$error) {
		$domain                 = $_POST['domain'];
		$uuid                   = $_POST['uuid'];
		$user_db_config         = $_POST['db'];
		$default_db_config      = require $database_path;
		$db_config              = [];
		$db_config['DB_PREFIX'] = $user_db_config['DB_PREFIX'];
		$db_config['DB_PORT']   = $user_db_config['DB_PORT'];
		$db_config['DB_USER']   = $user_db_config['DB_USER'];
		$db_config['DB_PWD']    = $user_db_config['DB_PWD'];
		$db_config['DB_HOST']   = $user_db_config['DB_HOST'];
		$db_config['DB_NAME']   = $user_db_config['DB_NAME'];
		$db_config = array_merge($default_db_config, $db_config);
		file_put_contents($database_path, "<?php \n return " . var_export($db_config, true) . ";");
        $index_file = file_get_contents($index_file_path);
        file_put_contents($index_file_path, str_replace('hhailuocms.com', $domain, $index_file));
	}
	require './step/install.php';
	break;
case 'grant':
	try {
		$grant = file_get_contents('http://api.hhailuo.com/grant-check.html?uuid=' . $_GET['uuid'] . '&domain=' . $_GET['domain']);
	} catch (Exception $e) {
		$grant = false;
	}
	$grant = ($grant === false ? ['code' => 500, 'message' => '授权检测失败！请重试'] : json_decode($grant, true));
	if ($grant['code'] == 0) {
		file_put_contents($root_path . '/install.lock', 'true');
	}
	require './step/grant.php';
	break;
case 'licence':
default:
	require './step/licence.php';
	break;
}
