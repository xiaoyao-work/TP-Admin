<?php $models = model('Model')->where(array('siteid' => $this->siteid))->field('id, name, tablename')->select(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo asset('js/multiselect/css/multi-select.css'); ?>">
<table cellpadding="2" cellspacing="1" width="98%">
    <tr>
        <td width="100">选择模型</td>
        <td>
            <select multiple="multiple" id="model-select" name="setting[model][]">
                <?php foreach ($models as $key => $model) { ?>
                <option value='<?php echo $model['id']; ?>'><?php echo $model['name']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
</table>
<script type="text/javascript" src="<?php echo asset('js/multiselect/jquery.multi-select.js'); ?>"></script>
<script type="text/javascript">
    $('#model-select').multiSelect();
</script>