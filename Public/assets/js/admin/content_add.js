function strlen_verify(obj, checklen, maxlen) {
  var v = obj.value, charlen = 0, maxlen = !maxlen ? 200 : maxlen, curlen = maxlen, len = strlen(v);
  for(var i = 0; i < v.length; i++) {
    if(v.charCodeAt(i) < 0 || v.charCodeAt(i) > 255) {
      curlen -= 2;
    }
  }
  if(curlen >= len) {
    $('#'+checklen).html(curlen - len);
  } else {
    obj.value = mb_cutstr(v, maxlen, true);
  }
}
function mb_cutstr(str, maxlen, dot) {
  var len = 0;
  var ret = '';
  var dot = !dot ? '...' : '';
  maxlen = maxlen - dot.length;
  for(var i = 0; i < str.length; i++) {
    len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ?  3 : 1;
    if(len > maxlen) {
      ret += dot;
      break;
    }
    ret += str.substr(i, 1);
  }
  return ret;
}
function strlen(str) {
  return ($.browser.msie && str.indexOf('\n') != -1) ? str.replace(/\r?\n/g, '_').length : str.length;
}

function close_window() {
  if($('#title').val() !='') {
    art.dialog({content:'内容已经录入，确定离开将不保存数据！', fixed:true,yesText:'我要关闭',noText:'返回保存数据',style:'confirm', id:'bnt4_test'}, function(){
      window.close();
    }, function(){

    });
  } else {
    window.close();
  }
  return false;
}

function ruselinkurl() {
  if($('#islink').attr('checked')=='checked') {
    $('#linkurl').attr('disabled',false);
    var oEditor = CKEDITOR.instances.editor;
    oEditor.insertHtml('　');
    return false;
  } else {
    $('#linkurl').attr('disabled','true');
  }
}

//移除相关文章
function remove_relation(sid,id) {
  var relation_ids = $('#relation').val();
  var relation_cats_obj = $('#relation_cats');
  if (relation_cats_obj.length > 0) {
    relation_cats = relation_cats_obj.val();
  };
  if(relation_ids !='' ) {
    var newsrelation_cats = '';
    $('#'+sid).remove();
    var r_arr = relation_ids.split('|');

    if (relation_cats_obj.length > 0) {
      var r_c_arr = relation_cats.split('|');
    };

    var newrelation_ids = '';
    $.each(r_arr, function(i, n){
      if(n!=id) {
        if(i==0) {
          newrelation_ids = n;
          if (r_c_arr) {
            newsrelation_cats = r_c_arr[i];
          }
        } else {
          newrelation_ids = newrelation_ids+'|'+n;
          if (r_c_arr) {
            newsrelation_cats = newsrelation_cats + '|' + r_c_arr[i];
          }
        }
      }
    });
    $('#relation').val(newrelation_ids);
    relation_cats_obj.val(newsrelation_cats);
  }
}

//移除相关文章
function remove_relation_developer(did,id) {
  var relation_ids = $('#relation_developer').val();
  if(relation_ids !='' ) {
    $('#'+did).remove();
    var r_arr = relation_ids.split('|');
    var newrelation_ids = '';
    $.each(r_arr, function(i, n){
      if(n!=id) {
        if(i==0) {
          newrelation_ids = n;
        } else {
          newrelation_ids = newrelation_ids+'|'+n;
        }
      }
    });
    $('#relation_developer').val(newrelation_ids);
  }
}

//显示相关文章
function show_relation(url,modelid,id) {
  $.getJSON(url + "?modelid="+modelid+"&id="+id, function(json){
    var newrelation_ids = '';
    if(json==null) {
      alert('没有添加相关文章');
      return false;
    }
    $.each(json, function(i, n){
      newrelation_ids += "<li id='"+n.sid+"'>·<span>"+n.title+"</span><a href='javascript:;' class='close' onclick=\"remove_relation('"+n.sid+"',"+n.id+")\"></a></li>";
    });

    $('#relation_text').html(newrelation_ids);
  });
}

//移除ID
function remove_id(id) {
  $('#'+id).remove();
}

function input_font_bold() {
  if($('#title').css('font-weight') == '700' || $('#title').css('font-weight')=='bold') {
    $('#title').css('font-weight','normal');
    $('#style_font_weight').val('');
  } else {
    $('#title').css('font-weight','bold');
    $('#style_font_weight').val('bold');
  }
}

function check_content(obj) {
  if($.browser.msie) {
    CKEDITOR.instances[obj].insertHtml('');
    CKEDITOR.instances[obj].focusManager.hasFocus;
  }
  top.art.dialog({id:'check_content_id'}).close();
  return true;
}
