<?php
class content_form {
    public $modelid;
    public $pageTemplate;
    public $fields;
    public $id;
    public $formValidator;
    public $siteid;

    public function __construct($data, $type = 1) {
        switch ($type) {
            case 1:
                $this->modelid = $data;
                $this->fields  = $this->getModelFields($data);
                break;
            case 2:
                $this->pageTemplate = $data;
                $this->fields       = $this->getPageFields($data);
                break;
            default:
                $this->modelid = $data;
                $this->fields  = $this->getModelFields($data);
                break;
        }
        $this->siteid = get_siteid();
    }

    public function getModelFields($modelid) {
        return model('ModelField')->getFieldsByModelID($modelid);
    }

    public function getPageFields($pageTemplate) {
        return model('PageField')->getPageExtraFields($pageTemplate);
    }

    public function get($data = []) {
        $this->data = $data;
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }

        $info = [];
        foreach ($this->fields as $field => $v) {
            if (defined('IN_ADMIN')) {
                if ($v['iscore']) {
                    continue;
                }

            }
            $func = $v['formtype'];

            $value = isset($data[$field]) ? $data[$field] : '';
            // if(!method_exists($this, $func)) continue;
            $form = $this->$func($field, $value, $v);
            if ($form === false) {
                continue;
            }
            $star         = $v['minlength'] || $v['pattern'] ? 1 : 0;
            $info[$field] = [
                'name'         => $v['name'],
                'tips'         => $v['tips'],
                'form'         => $form,
                'star'         => $star,
                'isomnipotent' => isset($v['isomnipotent']) ? $v['isomnipotent'] : '',
                'formtype'     => $v['formtype'],
                'setting'      => string2array($v['setting']),
            ];
        }
        return $info;
    }

    protected function title($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $style_arr         = isset($this->data['style']) ? explode(';', $this->data['style']) : '';
        $style_color       = isset($style_arr[0]) ? $style_arr[0] : '';
        $style_font_weight = isset($style_arr[1]) ? $style_arr[1] : '';

        if (!$value && isset($defaultvalue)) {
            $value = $defaultvalue;
        }

        $errortips_max = '标题应在' . $minlength . '-' . $maxlength . '个字符之间';
        if ($errortips) {
            $this->formValidator .= '$("#' . $field . '").formValidator({onshow:"",onfocus:"' . $errortips . '"}).inputValidator({min:' . $minlength . ',max:' . $maxlength . ',onerror:"' . $errortips_max . '"});';
        }

        $str = '<input type="text" style="width:400px;' . ($style_color ? 'color:' . $style_color . ';' : '') . ($style_font_weight ? 'font-weight:' . $style_font_weight . ';' : '') . '" name="info[' . $field . ']" id="' . $field . '" value="' . $value . '" class="measure-input " onkeyup="strlen_verify(this, \'title_len\', ' . $maxlength . ');"/>';
        /*'<input type="hidden" name="style_color" id="style_color" value="'.$style_color.'">'.
        '<input type="hidden" name="style_font_weight" id="style_font_weight" value="'.$style_font_weight.'">';*/

        if (defined('IN_ADMIN')) {
            $str .= '<input type="button" class="button" id="check_title_alt" value="检测重复" onclick="$.get(\'' . __CONTROLLER__ . '/public_check_title?modelid=' . (is_array($this->modelid) ? current($this->modelid) : $this->modelid) . '&sid=\'+Math.random()*5, {data:$(\'#title\').val()}, function(data){if(data==\'1\') {$(\'#check_title_alt\').val(\'标题重复\');$(\'#check_title_alt\').css(\'background-color\',\'#FFCC66\');} else if(data==\'0\') {$(\'#check_title_alt\').val(\'标题不重复\');$(\'#check_title_alt\').css(\'background-color\',\'#F8FFE1\')}})" style="width:73px;"/><span id="' . $field . '_colorpanel" style="position:absolute;" class="colorpanel"></span>';
        }
        $str .= '还可输入<B><span id="title_len">' . $maxlength . '</span></B> 个字符';
        return $str;
    }

    protected function textarea($field, $value, $fieldinfo) {
        extract($fieldinfo);

        if (!$value && isset($defaultvalue)) {
            $value = $defaultvalue;
        }

        $allow_empty = 'empty:true,';
        if ($minlength || $pattern) {
            $allow_empty = '';
        }

        if ($errortips) {
            $this->formValidator .= '$("#' . $field . '").formValidator({' . $allow_empty . 'onshow:"' . $errortips . '",onfocus:"' . $errortips . '"}).inputValidator({min:1,onerror:"' . $errortips . '"});';
        }

        $str = "<textarea name='info[{$field}]' id='$field' style='width:{$width}%;height:{$height}px;' $formattribute $css";
        if ($maxlength) {
            $str .= " onkeyup=\"strlen_verify(this, '{$field}_len', {$maxlength})\"";
        }

        $str .= ">{$value}</textarea>";
        if ($maxlength) {
            $str .= '还可输入<B><span id="' . $field . '_len">' . $maxlength . '</span></B> 个字符';
        }

        return $str;
    }

    protected function text($field, $value, $fieldinfo) {
        extract($fieldinfo);

        if (!$value && isset($defaultvalue)) {
            $value = $defaultvalue;
        }

        $type = isset($ishidden) && $ishidden ? 'hidden' : ($ispassword ? 'password' : 'text');

        if ($errortips || $minlength || $pattern) {
            $this->formValidator .= '$("#' . $field . '")';
        }

        if ($errortips || $minlength) {
            $this->formValidator .= '.formValidator({onfocus:"' . (empty($errortips) ? '请填写内容' : $errortips) . '"}).inputValidator({min:' . $minlength . ($maxlength ? ', max:' . $maxlength : '') . ', onerror:"' . (empty($errortips) ? $name . '不能为空' : $errortips) . '"})';
        }

        if (!empty($pattern)) {
            $this->formValidator .= '.functionValidator({fun: function(value, _this) { return ' . $pattern . '.test(value); }, onerror:\'' . (empty($errortips) ? $name . '格式不正确' : $errortips) . '\'})';
        }

        if ($errortips || $minlength || $pattern) {
            $this->formValidator .= ';';
        }
        return '<input type="' . $type . '" name="info[' . $field . ']" id="' . $field . '" size="' . $size . '" value="' . $value . '" class="input-text" ' . $formattribute . ' ' . $css . '>';
    }

    protected function relationship($field, $value, $fieldinfo) {
        $field_setting = string2array($fieldinfo['setting']);
        $models        = model('Model')->where(['siteid' => get_siteid(), 'id' => ['in', $field_setting['model']]])->select();
        $model_num     = count($models);
        $value         = empty($value) ? [] : string2array($value);
        $form_text     = '';
        if (!defined('RELATIONSHIP_STATIC')) {
            define('RELATIONSHIP_STATIC', true);
            $style  = '<link rel="stylesheet" type="text/css" href="' . asset('css/admin/postfield.css') . '">';
            $script = '<script type="text/javascript" src="' . asset('js/admin/postfield.js') . '"></script>';
        }
        $form_text .= '<div class="col-tab">' .
            '<ul class="tabBut cu-li">';
        foreach ($models as $key => $model) {
            $form_text .= '<li id="tab_setting_' . ($key + 1) . '" class="' . ($key == 0 ? 'on' : '') . '" onclick="SwapTab(\'setting\',\'on\',\'\', ' . $model_num . ' ,' . ($key + 1) . ');">' . $model['name'] . '</li>';
        }
        $form_text .= '</ul>';
        foreach ($models as $key => $model) {
            $form_text .= '<div id="div_setting_' . ($key + 1) . '" class="contentList pad-10 field ' . ($key == 0 ? '' : 'hidden') . '">' .
                '<div class="cfs_relationship">' .
                '<div class="filter_posts">' .
                '<input type="text" class="cfs_filter_input" autocomplete="off" placeholder="搜索文章...">' .
                '<input type="hidden" class="model_id" value="' . $model['id'] . '" />' .
                '</div>' .
                '<div class="available_posts post_list">' .
                '</div>' .
                '<div class="selected_posts post_list">' .
                '</div>' .
                '</div>' .
                '<input type="hidden" name="info[' . $field . '][' . $model['tablename'] . ']" class="relationship" value="' . (isset($value[$model['tablename']]) ? $value[$model['tablename']] : '') . '">' .
                '</div>';
        }
        $form_text .= '</div><script type="text/javascript">window.post_get_posts="' . U('Post/getPosts') . '?ms=' . time() . '";</script>';
        return $style . $form_text . $script;
    }

    protected function posid($field, $value, $fieldinfo) {
        $setting = string2array($fieldinfo['setting']);
        if ($value) {
            $pos_str = model('Position')->getPositionCheckbox($this->modelid, $field, $this->id);
        } else {
            $pos_str = model('Position')->getPositionCheckbox($this->modelid, $field, '', $setting['defaultvalue']);
        }
        return $pos_str;
    }

    protected function number($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $setting = string2array($setting);
        if (!$value && isset($defaultvalue)) {
            $value = $defaultvalue;
        }

        return "<input type='number' name='info[$field]' id='$field' value='$value' class='input-text' {$formattribute} {$css}>";
    }

    protected function map($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $setting   = string2array($setting);
        $errortips = $this->fields[$field]['errortips'];
        $modelid   = $this->fields[$field]['modelid'];
        return '<input type="text" name="info[' . $field . ']" value="' . $value . '" class="input-text" id="' . $field . '" >' .
        '<input type="button" name="' . $field . '_mark" id="' . $field . '_mark" value="' . $tips . '" class="map_button" onclick="omnipotent(\'' . $field . '\',\'' . url('Map/markPosition') . '?point=' . $value . '&fieldid=' . $fieldid . '\',\'地图标记\',1,900,500)">';
    }

    protected function linkage($field, $value, $fieldinfo) {
        $setting   = string2array($fieldinfo['setting']);
        $linkageid = $setting['linkageid'];
        return menu_linkage($linkageid, $field, $value);
    }

    protected function keyword($field, $value, $fieldinfo) {
        extract($fieldinfo);

        if (!$value && isset($defaultvalue)) {
            $value = $defaultvalue;
        }

        return "<input type='text' name='info[$field]' id='$field' value='$value' style='width:280px' {$formattribute} {$css} class='input-text'>";
    }

    protected function images($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $list_str = '';
        if ($value) {
            $value = string2array(html_entity_decode($value, ENT_QUOTES));
            if (is_array($value)) {
                foreach ($value as $_k => $_v) {
                    $list_str .= "<div id='image_{$field}_{$_k}' style='padding:1px'><input type='text' name='info[{$field}][url][]' value='{$_v[url]}' style='width:310px;' ondblclick='image_priview(this.value);' class='input-text'> <input type='text' name='info[{$field}][alt][]' value='{$_v[alt]}' style='width:160px;' class='input-text'> <a href=\"javascript:remove_div('image_{$field}_{$_k}')\">移除</a></div>";
                }
            }
        } else {
            $list_str .= "<center><div class='onShow' id='nameTip'>您最多可以同时上传 <font color='red'>{$upload_number}</font> 个</div></center>";
        }
        $string = '<input name="info[' . $field . ']" type="hidden" value="1">
		<fieldset class="blue pad-10">
		<legend>文件列表</legend>';
        $string .= $list_str;
        $string .= '<div id="info_' . $field . '" class="picList"></div>
		</fieldset>
		<div class="bk10"></div>';
        $string .= "<div class='picBut cu'><a herf='javascript:void(0);' onclick=\"javascript:attachupload('{$field}_images', '附件上传','info_{$field}',attaches,'{$upload_number},{$upload_allowext},{$isselectimage},,,,{$upload_maxsize}','images','" . U('File/upload') . "')\"/> 选择文件 </a></div>";
        return $string;
    }

    protected function image($field, $value, $fieldinfo) {
        $setting = string2array($fieldinfo['setting']);
        extract($setting);
        $js_callback = isset($js_callback) && !empty($js_callback) ? $js_callback : 'thumb_images';
        $html = '';
        if (defined('IN_ADMIN')) {
            $html = "<input type=\"button\" style=\"width: 66px;\" class=\"button\" onclick=\"$('#" . $field . "_preview').attr('src','" . asset('images/admin/icon/upload-pic.png') . "');$('#" . $field . "').val(' ');return false;\" value=\"取消附件\">";
        }
        if ($show_type && defined('IN_ADMIN')) {
            $preview_img = $value ? $value : asset('images/admin/icon/upload-pic.png');
            return "<div class='upload-pic img-wrap'><input type='hidden' name='info[$field]' id='$field' value='$value'><a href='javascript:void(0);' onclick=\"attachupload('{$field}_images', '附件上传','{$field}',".$js_callback.",'1,{$upload_allowext},{$isselectimage},{$images_width},{$images_height},{$watermark},{$upload_maxsize}','image','" . U("File/upload") . "');return false;\">
			<img src='$preview_img' id='{$field}_preview' width='135' height='113' style='cursor:hand' /></a>" . "<input type=\"button\" style=\"width: 66px;\" class=\"button\" onclick=\"attachupload('{$field}_images', '附件上传','{$field}',".$js_callback.",'1,{$upload_allowext},{$isselectimage},{$images_width},{$images_height},{$watermark},{$upload_maxsize}','image','" . U("File/upload") . "');return false;\" value=\"上传附件\">" . $html . "</div>";
        } else {
            return "<input type='text' name='info[$field]' id='$field' value='$value' size='$size' class='input-text' />  <input type='button' class='button' onclick=\"attachupload('{$field}_images', '附件上传','{$field}',".$js_callback.",'1,{$upload_allowext},{$isselectimage},{$images_width},{$images_height},{$watermark},{$upload_maxsize}','image','" . U("File/upload") . "');return false;\"/ value='上传附件'>" . $html;
        }
    }

    protected function editor($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $disabled_page = isset($disabled_page) ? $disabled_page : 0;
        if (!$height) {
            $height = 300;
        }

        $allowupload = defined('IN_ADMIN') ? 1 : 0;

        if (!$value && isset($defaultvalue)) {
            $value = $defaultvalue;
        }

        if ($minlength || $pattern) {
            $allow_empty = '';
        }

        if ($minlength) {
            $this->formValidator .= '$("#' . $field . '").formValidator({' . $allow_empty . 'onshow:"",onfocus:"' . $errortips . '"}).functionValidator({fun:function(val,elem){var oEditor = CKEDITOR.instances.' . $field . ';var data = oEditor.getData();if($(\'#islink\').attr(\'checked\')){return true;} else if(($(\'#islink\').attr(\'checked\')==false) && (data==\'\')){return "' . $errortips . '";} else if (data==\'\' || $.trim(data)==\'\') {return "' . $errortips . '";e}return true;}});';
        }

        return "<div id='{$field}_tip'></div>" . '<textarea name="info[' . $field . ']" id="' . $field . '" boxid="' . $field . '">' . stripslashes($value) . '</textarea>' . \Org\Util\Form::editor($field, $toolbar, '', $allowupload, 1, '', $height);
    }

    protected function datetime($field, $value, $fieldinfo) {
        extract(string2array($fieldinfo['setting']));
        $isdatetime = 0;
        $timesystem = 0;
        if ($fieldtype == 'datetime') {
            if (!$value) {
                $value = date($format);
            }
            $isdatetime = 1;
            $timesystem = 1;
        }
        return \Org\Util\Form::date("info[$field]", $value, $isdatetime, 0, true, $timesystem);
    }

    protected function copyfrom($field, $value, $fieldinfo) {
        $value_data = '';
        if ($value && strpos($value, '|') !== false) {
            $arr        = explode('|', $value);
            $value      = $arr[0];
            $value_data = $arr[1];
        }
        return "<input type='text' name='info[$field]' value='$value' style='width: 400px;' class='input-text'>";
    }

    protected function box($field, $value, $fieldinfo) {
        if (!$value && isset($defaultvalue)) {
            $value = $defaultvalue;
        }

        $options = explode("\n", $fieldinfo['options']);
        foreach ($options as $_k) {
            if (empty($_k)) {
                continue;
            }
            $v          = explode("|", $_k);
            $k          = trim($v[1]);
            $option[$k] = $v[0];
        }
        $values = explode(',', $value);
        $value  = [];
        foreach ($values as $_k) {
            if ($_k != '') {
                $value[] = $_k;
            }

        }
        $value = implode(',', $value);
        switch ($fieldinfo['boxtype']) {
            case 'radio':
                $string = \Org\Util\Form::radio($option, $value, "name='info[$field]' {$fieldinfo['formattribute']}", $fieldinfo['width'], $field);
                break;

            case 'checkbox':
                $string = \Org\Util\Form::checkbox($option, $value, "name='info[$field][]' {$fieldinfo['formattribute']}", 1, $fieldinfo['width'], $field);
                break;

            case 'select':
                $string = \Org\Util\Form::select($option, $value, "name='info[$field]' id='$field' {$fieldinfo['formattribute']}");
                break;

            case 'multiple':
                $string = \Org\Util\Form::select($option, $value, "name='info[$field][]' id='$field ' size=2 multiple='multiple' style='height:60px;' {$fieldinfo['formattribute']}");
                break;
        }
        return $string;
    }

    protected function author($field, $value, $fieldinfo) {
        return '<input type="text" name="info[' . $field . ']" value="' . $value . '" size="30">';
    }

    public function __call($name, $arguments) {
        list($field, $value, $fieldinfo) = $arguments;
        return file_exists(PLUGINS_PATH . 'PostField' . DS . $name . DS . 'form.inc.php') ? include PLUGINS_PATH . 'PostField' . DS . $name . DS . 'form.inc.php' : false;
    }
}