<table cellpadding="2" cellspacing="1" width="98%">
    <tr>
        <td width="100">附加HTML</td>
        <td>
            <textarea name="setting[formtext]" rows="2" cols="20" id="options" style="height:100px;width:400px;"></textarea><BR>
            注意：输入框内请不要输入字符 "（双引号）否则将导致程序错误。如需输入"请用' | \' 替代！！！
        </td>
    </tr>
    <tr>
        <td>是否隐藏输入框</td>
        <td><input type="radio" name="setting[ishide]" value="1" checked> 是 <input type="radio" name="setting[ishide]" value="0"> 否</td>
    </tr>
    <tr>
        <td>字段类型</td>
        <td>
            <select name="setting[fieldtype]">
                <option value="text">文本 TEXT</option>
                <option value="varchar">字符 VARCHAR</option>
                <option value="tinyint">整数 TINYINT(3)</option>
                <option value="smallint">整数 SMALLINT(5)</option>
                <option value="mediumint">整数 MEDIUMINT(8)</option>
                <option value="int">整数 INT(10)</option>
            </select> <span id="minnumber" style="display:none"><input type="radio" name="setting[minnumber]" value="1" checked/> <font color='red'>正整数</font> <input type="radio" name="setting[minnumber]" value="-1" /> 整数</span>
        </td>
    </tr>
</table>