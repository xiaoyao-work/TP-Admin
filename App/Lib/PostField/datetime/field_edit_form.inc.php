
<table cellpadding="2" cellspacing="1" bgcolor="#ffffff">
	<tr>
      <td><strong>时间格式：</strong></td>
      <td>
	  <input type="radio" name="setting[fieldtype]" value="date" <?php if($setting['fieldtype']=='date') echo 'checked';?>>日期（<?=date('Y-m-d')?>）<br />
	  <input type="radio" name="setting[fieldtype]" value="datetime" <?php if($setting['fieldtype']=='datetime') echo 'checked';?>>日期时间（<?=date('Y-m-d H:i:s')?>）<br />
	  </td>
    </tr>
	<tr>
      <td><strong>默认值：</strong></td>
      <td>
	  <input type="radio" name="setting[defaulttype]" value="0" checked/>无<br />
	 </td>
    </tr>
</table>