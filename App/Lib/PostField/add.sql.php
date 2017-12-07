<?php

$defaultvalue = isset($_POST['setting']['defaultvalue']) ? $_POST['setting']['defaultvalue'] : '';
$comment = isset($_POST['info']['comment']) ? addslashes($_POST['info']['comment']) : '';
//正整数 UNSIGNED && SIGNED
$minnumber = isset($_POST['setting']['minnumber']) ? $_POST['setting']['minnumber'] : 1;
$decimaldigits = isset($_POST['setting']['decimaldigits']) ? $_POST['setting']['decimaldigits'] : '';

switch($field_type) {
	case 'varchar':
		if(!$maxlength) $maxlength = 255;
		$maxlength = min($maxlength, 255);
		$sql = "ALTER TABLE `$tablename` ADD `$field` VARCHAR( $maxlength ) NOT NULL DEFAULT '$defaultvalue' COMMENT '{$comment}'";
		$this->db->execute($sql);
	break;

	case 'tinyint':
		if(!$maxlength) $maxlength = 3;
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		$this->db->execute("ALTER TABLE `$tablename` ADD `$field` TINYINT( $maxlength ) ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue' COMMENT '{$comment}'");
	break;

	case 'number':
		$minnumber = intval($minnumber);
		$defaultvalue = $decimaldigits == 0 ? intval($defaultvalue) : floatval($defaultvalue);
		$sql = "ALTER TABLE `$tablename` ADD `$field` ".($decimaldigits == 0 ? 'INT' : 'FLOAT')." ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue' COMMENT '{$comment}'";
		$this->db->execute($sql);
	break;

	case 'smallint':
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		$sql = "ALTER TABLE `$tablename` ADD `$field` SMALLINT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue' COMMENT '{$comment}'";
		$this->db->execute($sql);
	break;

	case 'int':
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		$sql = "ALTER TABLE `$tablename` ADD `$field` INT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue' COMMENT '{$comment}'";
		$this->db->execute($sql);
	break;

	case 'mediumint':
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		$sql = "ALTER TABLE `$tablename` ADD `$field` MEDIUMINT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue' COMMENT '{$comment}'";
		$this->db->execute($sql);
	break;

	case 'mediumtext':
		$this->db->execute("ALTER TABLE `$tablename` ADD `$field` MEDIUMTEXT NOT NULL COMMENT '{$comment}'");
	break;

	case 'text':
		$this->db->execute("ALTER TABLE `$tablename` ADD `$field` TEXT NOT NULL COMMENT '{$comment}'");
	break;

	case 'date':
		$this->db->execute("ALTER TABLE `$tablename` ADD `$field` DATE NOT NULL '". empty($defaultvalue) ? '1970-01-01' : $defaultvalue ."' COMMENT '{$comment}'");
	break;

	case 'datetime':
		$this->db->execute("ALTER TABLE `$tablename` ADD `$field` DATETIME NOT NULL DEFAULT '". (empty($defaultvalue) ? '1970-01-01 08:00:01' : $defaultvalue) ."' COMMENT '{$comment}'");
	break;

	case 'timestamp':
		$this->db->execute("ALTER TABLE `$tablename` ADD `$field` TIMESTAMP NOT NULL DEFAULT '". (empty($defaultvalue) ? '1970-01-01 08:00:01' : $defaultvalue) ."' COMMENT '{$comment}'");
	break;
}
?>