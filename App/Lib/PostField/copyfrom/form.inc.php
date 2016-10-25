<?php
$value_data = '';
if($value && strpos($value,'|')!==false) {
    $arr = explode('|',$value);
    $value = $arr[0];
    $value_data = $arr[1];
}
return "<input type='text' name='info[$field]' value='$value' style='width: 400px;' class='input-text'>";
