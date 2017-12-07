<table cellpadding="2" cellspacing="1" width="98%">
    <tr>
        <td width="100">取值范围</td>
        <td><input type="text" name="setting[minnumber]" value="1" size="5" class="input-text"> - <input type="text" name="setting[maxnumber]" value="" size="5" class="input-text"></td>
    </tr>
    <tr>
        <td width="100">字段长度</td>
        <td><select name="setting[fieldtype]">
            <option value="number">number</option>
            <option value="int">int</option>
            <option value="mediumint">mediumint</option>
            <option value="smallint">smallint</option>
            <option value="tinyint">tinyint</option>
        </select></td>
    </tr>
    <tr>
        <td>小数位数：</td>
        <td>
            <select name="setting[decimaldigits]">
                <option value="-1">自动</option>
                <option value="0" selected>0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>输入框长度</td>
        <td><input type="text" name="setting[size]" value="" size="3" class="input-text"> px</td>
    </tr>
    <tr>
        <td>默认值</td>
        <td><input type="text" name="setting[defaultvalue]" value="" size="40" class="input-text"></td>
    </tr>

</table>