<table cellpadding="2" cellspacing="1" width="98%">
  <tr>
    <td>菜单ID</td>
    <td>
      <input type="text" id="linkageid" name="setting[linkageid]" value="0" size="5" class="input-text">
      <input type='button' value="在列表中选择" onclick="omnipotent('selectid','<?php echo U('Linkage/public_get_list'); ?>','在列表中选择', 1)" class="button">请到导航 扩展 > 联动菜单 > 添加联动菜单
    </td>
  </tr>
  <tr>
    <td>是否作为筛选字段</td>
    <td>
      <input type="radio" name="setting[filtertype]" value="1"/> 是
      <input type="radio" name="setting[filtertype]" value="0"/> 否
    </td>
  </tr>
</table>