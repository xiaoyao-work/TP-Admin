function posid($field, $value, $fieldinfo) {
		$setting = string2array($fieldinfo['setting']);
		$position = D('Position')->select();
		if(empty($position)) return '';
		$array = array();
		foreach($position as $_key=>$_value) {
			if($_value['modelid'] && ($_value['modelid'] !=  $this->modelid) || ($_value['catid'] && strpos(','.$this->categorys[$_value['catid']]['arrchildid'].',',','.$this->catid.',')===false)) continue;
			$array[$_value['id']] = $_value['name'];
		}
		$posids = array();
		if(ACTION_NAME=='edit') {
			$position_data = D('PositionData')->where('id = %d and modelid = %d', $content['id'], $category['modelid'])->field('posid')->group('posid')->select();
      $position_data_ids = array();
      foreach ($position_data as $key => $pos) {
        $position_data_ids[] = $pos['posid'];
      }
      $posids = implode(',', $position_data_ids);
		} else {
			$posids = $setting['defaultvalue'];
		}
		return "<input type='hidden' name='info[$field][]' value='-1'>".\Org\Util\Form::checkbox($array,$posids,"name='info[$field][]'",'',$setting['width']);
	}