<?php
extract(string2array($fieldinfo['setting']));
$isdatetime = 0;
$timesystem = 0;
if($fieldtype=='int') {
	if(!$value) $value = time();
	$format_txt = $format == 'm-d' ? 'm-d' : $format;
	if($format == 'Y-m-d Ah:i:s') $format_txt = 'Y-m-d h:i:s';
	$value = date($format_txt,$value);

	$isdatetime = strlen($format) > 6 ? 1 : 0;
	if($format == 'Y-m-d Ah:i:s') {

		$timesystem = 0;
	} else {
		$timesystem = 1;
	}
} elseif($fieldtype=='datetime') {
	if(!$value) $value = date($format);
	$isdatetime = 1;
	$timesystem = 1;
} elseif($fieldtype=='datetime_a') {
	if(!$value) $value = date($format);
	$isdatetime = 1;
	$timesystem = 0;
}
return \Org\Util\Form::date("info[$field]",$value,$isdatetime,1,'true',$timesystem);
