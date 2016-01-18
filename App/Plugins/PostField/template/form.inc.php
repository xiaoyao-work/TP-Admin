	function template($field, $value, $fieldinfo) {
		$sitelist = getcache('sitelist','commons');
		$default_style = $sitelist[$this->siteid]['default_style'];
		return \Org\Util\Form::select_template($default_style,'content',$value,'name="info['.$field.']" id="'.$field.'"','show');
	}
