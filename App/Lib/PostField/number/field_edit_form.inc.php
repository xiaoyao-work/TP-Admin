<table cellpadding="2" cellspacing="1" width="98%">
    <tr>
        <td width="100">取值范围</td>
        <td><input type="text" name="setting[minnumber]" value="<?php echo $setting['minnumber'];?>" size="5" class="input-text"> - <input type="text" name="setting[maxnumber]" value="<?php echo $setting['maxnumber'];?>" size="5" class="input-text"></td>
    </tr>
    <tr>
        <td width="100">字段长度</td>
        <td><select name="setting[fieldtype]">
            <option value="number" <?php if($setting['fieldtype']=='number') echo 'selected';?>)>number</option>
            <option value="int" <?php if($setting['fieldtype']=='int') echo 'selected';?>)>int</option>
            <option value="mediumint" <?php if($setting['fieldtype']=='mediumint') echo 'selected';?>>mediumint</option>
            <option value="smallint" <?php if($setting['fieldtype']== 'smallint') echo 'selected';?>>smallint</option>
            <option value="tinyint" <?php if($setting['fieldtype']== 'tinyint') echo 'selected';?>>tinyint</option>
        </select></td>
    </tr>
    <tr>
        <td>小数位数：</td>
        <td>
            <select name="setting[decimaldigits]">
                <option value="-1" <?php if($setting['decimaldigits']==-1) echo 'selected';?>)>自动</option>
                <option value="0" <?php if($setting['decimaldigits']==0) echo 'selected';?>>0</option>
                <option value="1" <?php if($setting['decimaldigits']==1) echo 'selected';?>>1</option>
                <option value="2" <?php if($setting['decimaldigits']==2) echo 'selected';?>>2</option>
                <option value="3" <?php if($setting['decimaldigits']==3) echo 'selected';?>>3</option>
                <option value="4" <?php if($setting['decimaldigits']==4) echo 'selected';?>>4</option>
                <option value="5" <?php if($setting['decimaldigits']==5) echo 'selected';?>>5</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>输入框长度</td>
        <td><input type="text" name="setting[size]" value="<?php echo isset($setting['size']) ? $setting['size'] : '';?>" size="3" class="input-text"> px</td>
    </tr>
    <tr>
        <td>默认值</td>
        <td><input type="text" name="setting[defaultvalue]" value="<?php echo $setting['defaultvalue'];?>" size="40" class="input-text"></td>
    </tr>
</table>