<?php
	function omnipotent($field, $value, $fieldinfo) {
		extract($fieldinfo);
		var_dump($formtext);
		$formtext = eval($formtext);
		exit();
		$formtext .= '<input type="text" name="info['.$field.']" id="'.$field.'" value="'.$value.'" class="omnipotent-'.$field.'" '.$formattribute.'>';

		$errortips = $this->fields[$field]['errortips'];
		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:'.$minlength.',max:'.$maxlength.',onerror:"'.$errortips.'"});';

		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"'.$errortips.'",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		return $formtext;
	}
