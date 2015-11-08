function preview(returnid) {
  var in_content = this.$("#att-status").html().substring(1);
  if (in_content) {
    $('#'+returnid).val(in_content);
    $('#'+returnid+'_s').attr('src', in_content);
  };
}

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
    var input_name_alt = input_name_str;
    input_name_str += "[url][]";
    input_name_alt += "[alt][]";
    if (in_content) {
      var html = "";
      for (var i = 0; i < in_content.length; i++) {
        html += '<li id="' + in_content[i].substr(-14,10) + '"><input type="text" name="'+ input_name_str + '" value="' + in_content[i] + '" style="width:310px;" ondblclick="image_priview(this.value);" class="input-text"> <input type="text" name="' + input_name_alt + '" value="' + in_name[i] + '" style="width:160px;" class="input-text" onfocus="if(this.value == this.defaultValue) this.value = \'\'" onblur="if(this.value.replace(\' \',\'\') == \'\') this.value = this.defaultValue;"> <a href="javascript:remove_div(\'' + in_content[i].substr(-14,10) + '\')">移除</a> </li>'
      }
      $("#"+returnid).append(html);
    }
  };
}

/*function attaches(returnid) {
  var in_content = this.$("#att-status").html().substring(1);
  var in_content = in_content.split('|');
  var in_name = this.$("#att-name").html().substring(1);
  var in_name = in_name.split("|");
  if (in_content) {
    var html = "";
    for (var i = 0; i < in_content.length; i++) {
      html += '<li id="' + in_content[i].substr(-14,10) + '"><input type="text" name="pictureurls_url[]" value="' + in_content[i] + '" style="width:310px;" ondblclick="image_priview(this.value);" class="input-text"> <input type="text" name="pictureurls_alt[]" value="' + in_name[i] + '" style="width:160px;" class="input-text" onfocus="if(this.value == this.defaultValue) this.value = \'\'" onblur="if(this.value.replace(\' \',\'\') == \'\') this.value = this.defaultValue;"> <a href="javascript:remove_div(\'' + in_content[i].substr(-14,10) + '\')">移除</a> </li>'
    }
    $("#"+returnid).append(html);
  }
}*/

function thumb_images(returnid) {
  var in_content = this.$("#att-status").html().substring(1);
  if (in_content) {
    $('#'+returnid).val(in_content);
    $('#'+returnid+'_preview').attr('src', in_content);
  }
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