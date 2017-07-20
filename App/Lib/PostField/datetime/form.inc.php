<?php
extract(string2array($fieldinfo['setting']));
$isdatetime = 0;
if ($fieldtype == 'datetime') {
    if (!$value) {
        $value = date($format);
    }
    $isdatetime = 1;
}
return \Org\Util\Form::date("info[$field]", $value, $isdatetime, "class='Wdate' placeholder='" . $fieldinfo['name'] . "'");