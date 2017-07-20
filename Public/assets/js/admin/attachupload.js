function submit_attachment(returnid) {
    var in_content = this.$("#att-status").html().substring(1);
    if (in_content) {
        var in_content = in_content.split('|');
        $('#'+returnid).attr("value",in_content[0]);
    }
}

function attaches(returnid) {
    var in_content = this.$("#att-status").html().substring(1);
    if (in_content) {
        var in_content = in_content.split('|');
        var in_name = this.$("#att-name").html().substring(1);
        var in_name = in_name.split("|");
        var input_name = returnid.split("_");
        var input_name_str = input_name[0];
        for (var i = 1; i < input_name.length; i++) {
            input_name_str += "[" + input_name[i] + "]";
        }
        console.log(input_name_str);
        var input_name_alt = input_name_str;
        input_name_str += "[url][]";
        input_name_alt += "[alt][]";
        console.log(in_content);
        if (in_content) {
            var html = "";
            for (var i = 0; i < in_content.length; i++) {
                html += '<li id="' + in_content[i].substr(-14,10) + '"><input type="text" name="'+ input_name_str + '" value="' + in_content[i] + '" style="width:310px;" ondblclick="image_priview(this.value);" class="input-text"> <input type="text" name="' + input_name_alt + '" value="' + in_name[i] + '" style="width:160px;" class="input-text" onfocus="if(this.value == this.defaultValue) this.value = \'\'" onblur="if(this.value.replace(\' \',\'\') == \'\') this.value = this.defaultValue;"> <a href="javascript:remove_div(\'' + in_content[i].substr(-14,10) + '\')">移除</a> </li>'
            }
            $("#"+returnid).append(html);
        }
    };
}

function thumb_images(returnid) {
    var in_content = this.$("#att-status").html().substring(1);
    if (in_content) {
        $('#'+returnid).val(in_content);
        $('#'+returnid+'_preview').attr('src', in_content);
    }
}

function resource_pack(returnid) {
    var in_content = this.$("#att-status").html().substring(1);
    var att_name = this.$("#att-name").html().substring(1);
    if (in_content) {
        $('#'+returnid).val(in_content);
        $('#'+returnid+'_preview').attr('src', in_content);
    }
    $('#title').val(att_name);
}

function preview(returnid) {
    var in_content = this.$("#att-status").html().substring(1);
    if (in_content) {
        $('#'+returnid).val(in_content);
        $('#'+returnid+'_s').attr('src', in_content);
    };
}

function showPhotos(file, data, response) {
    // console.log(file, data, response);
    data = $.parseJSON(data);
    if (data.code != 0) {
        $('#'+file.id).html('<p>' + data.message + '</p>').fadeOut(3);
    } else {
        var html = '<div class="wrap-image">' +
            '<img src="' + window.IMAGE_DOMAIN + data.data.thumb + '">' +
            '</div>' +
            '<textarea class="edit-photo-title" name="photo['+data.data.id+']">'+data.data.title+'</textarea>' +
            '<div class="op clear">' +
                '<a href="javascript:void(0);" data-id="'+data.data.id+'" class="delete deleteBtn">删除</a>' +
                '<a href="javascript:void(0);" data-id="'+data.data.id+'" class="save saveBtn">保存</a>' +
            '</div>';
        $('#'+file.id).html(html);
    }
}

function quick_upload(url, setting) {
    var default_setting = {
        'auto'            : true,
        'swf' : '/assets/js/admin/uploadify/uploadify.swf?&token=' + Math.random(),
        'formData'        : {},
        'buttonImage'     : '',
        'buttonClass'     : 'addnew',
        'buttonText'      : '',
        'height'          : '28',
        'width'           : '75',
        'fileSizeLimit'   : '2MB',
        'fileTypeDesc'    : '图片',
        'fileTypeExts'    : '*.gif; *.jpg; *.png;',
        'removeTimeout'   : 0,
        'queueSizeLimit'  : 999,
        'uploadLimit'     : 999,
        'targetDom'       : 'quick_upload',
        'callback'        : showPhotos
    }
    setting = setting ? setting : {};
    setting = $.extend(default_setting, setting);
    var html = '<form>' +
        '<input id="file_upload" name="file_upload" type="file" multiple="true">' +
        (setting.auto ? '' : '<a href="javascript:$(\'#file_upload\').uploadify(\'upload\',\'*\');" class="btupload">开始上传</a>') +
    '</form>';
    $('#quick_upload').html(html);

    $('#file_upload').uploadify({
        'auto'            : setting.auto,
        'formData'        : $.extend({
            "thumb_width": 150,
            "thumb_height": 100,
            "watermark_enable": true
        }, setting.formData),
        'removeCompleted' : false,
        'swf'             : setting.swf,
        'uploader'        : url,
        'buttonImage'     : setting.buttonImage,
        'buttonClass'     : setting.buttonClass,
        'buttonText'      : setting.buttonText,
        'height'          : setting.height,
        'width'           : setting.width,
        'fileSizeLimit'   : setting.fileSizeLimit,
        'fileTypeDesc'    : setting.fileTypeDesc,
        'fileTypeExts'    : setting.fileTypeExts,
        'removeTimeout'   : 0,
        'queueSizeLimit'  : setting.queueSizeLimit,
        'uploadLimit'     : setting.uploadLimit,
        'onSelect'        : function(file) {
            var html = '<li id="' + file.id + '"><div class="progress"><p>'+ file.name +'</p><div class="progress-bar" style="width: 0;"></div></div></li>';
            $('.photos ul').prepend(html);
        },
        'onUploadProgress': function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
            if ($('#' + file.id).length > 0) {
                $('#' + file.id).find('.progress-bar').css({width: Math.floor(100*(bytesUploaded/bytesTotal)) + '%'});
            }
        },
        'onUploadError'   : function(file, errorCode, errorMsg, errorString) {
            $('#file_upload').uploadify('settings', 'uploadLimit', $('#file_upload').uploadify('settings', 'uploadLimit') + 1);
            $('#'+file.id).html('<p>上传失败，错误原因: ' + errorString + '</p>').fadeOut(3);
        },
        'onUploadSuccess' : function(file, data, response) {
            setting.callback.apply(document,[file, data, response]);
        }
    });
}

/**
 *
 * @art_id       artDialog ID
 * @title        artDialog 标题
 * @textareaid   结果回显区域ID号，供回调函数调用显示处理结果的标识
 * @funcName     回调函数名
 * @setting      上传参数设置  队列个数,上传扩展名,允许上传个数,缩略图宽度,缩略图高度,是否加水印
 * @type         已废弃 上传类型 (image代表单张图片，images 多张图片，swf 动画上传， video 媒体文件上传)
 * @url          上传地址
 */
function attachupload(art_id,title,textareaid,funcName,setting,type,url) {
    var setting = setting ? '?args='+setting : '';
    art.dialog.open(url + setting, {
        id: art_id,
        width: "500px",
        height: "420px",
        title: title,
        ok: function() {
            var iframe = this.iframe.contentWindow;
            if (!iframe.document.body) {
                alert('窗口还没加载完毕！')
                return false;
            };
            if(funcName) {
                funcName.apply(iframe,[textareaid]);
            } else {
                preview.apply(iframe,textareaid);
            }
            return true;
        },
        cancel: true,
        lock: true,
        background: "#000"
    });
}