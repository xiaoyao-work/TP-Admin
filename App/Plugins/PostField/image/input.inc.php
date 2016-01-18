	function image($field, $value) {
		$value = str_replace(array("'",'"','(',')'),'',$value);
		return trim($value);
	}
