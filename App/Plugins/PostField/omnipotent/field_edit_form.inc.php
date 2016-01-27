<table cellpadding="2" cellspacing="1" width="98%">
    <tr>
        <td width="100">表单</td>
        <td><textarea name="setting[formtext]" rows="2" cols="20" id="options" style="height:100px;width:400px;"><?php echo htmlspecialchars($setting['formtext']);?></textarea><BR>
            注意：输入框内请不要输入字符 "（双引号）否则将导致程序错误。如需输入"请用' | \' 替代！！！
        </tr>
        <tr>
            <td>是否隐藏输入框</td>
            <td><input type="radio" name="setting[ishide]" value="1" <?php if($setting['ishide']==1) echo 'checked';?>> 是 <input type="radio" name="setting[ishide]" value="0" <?php if($setting['ishide']===0) echo 'checked';?>> 否</td>
        </tr>
        <tr>
            <td>字段类型</td>
            <td>
                <select name="setting[fieldtype]">
                    <option value="text" <?php if($setting['fieldtype']=='text') echo 'selected';?>>文本 TEXT</option>
                    <option value="varchar" <?php if($setting['fieldtype']=='varchar') echo 'selected';?>>字符 VARCHAR</option>
                    <option value="tinyint" <?php if($setting['fieldtype']=='tinyint') echo 'selected';?>>整数 TINYINT(3)</option>
                    <option value="smallint" <?php if($setting['fieldtype']=='smallint') echo 'selected';?>>整数 SMALLINT(5)</option>
                    <option value="mediumint" <?php if($setting['fieldtype']=='mediumint') echo 'selected';?>>整数 MEDIUMINT(8)</option>
                    <option value="int" <?php if($setting['fieldtype']=='int') echo 'selected';?>>整数 INT(10)</option>
                </select> <span id="minnumber" style="display:none"><input type="radio" name="setting[minnumber]" value="1" <?php if($setting['minnumber']==1) echo 'checked';?>/> <font color='red'>正整数</font> <input type="radio" name="setting[minnumber]" value="-1" <?php if($setting['minnumber']==-1) echo 'checked';?>/> 整数</span>
            </td>
        </tr>
    </table>