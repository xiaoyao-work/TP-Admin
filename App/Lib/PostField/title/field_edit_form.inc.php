<table cellpadding="2" cellspacing="1" width="98%">
  <tr>
    <td width="100">文本框长度</td>
    <td><input type="text" name="setting[size]" value="<?php echo isset($setting['size']) ? $setting['size'] : 10;?>" size="" class="input-text"></td>
  </tr>
  <tr>
    <td>默认值</td>
    <td><input type="text" name="setting[defaultvalue]" value="<?php echo isset($setting['defaultvalue']) ? $setting['defaultvalue'] : '';?>" size="40" class="input-text"></td>
  </tr>
</table>