<table cellpadding="2" cellspacing="1" bgcolor="#ffffff">
	<tr>
      <td><strong>时间格式：</strong></td>
      <td>
    	  <input type="radio" name="setting[fieldtype]" value="date" checked>日期（<?php echo date('Y-m-d');?>）<br />
    	  <input type="radio" name="setting[fieldtype]" value="datetime">日期时间（<?php echo date('Y-m-d H:i:s');?>）<br />
	  </td>
    </tr>
	<tr>
      <td><strong>默认值：</strong></td>
      <td>
	  <input type="radio" name="setting[defaulttype]" value="0" checked/>无<br />
	 </td>
    </tr>
</table>