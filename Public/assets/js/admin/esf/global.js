$(function(){
  /* 文件上传 */
  $('.photos').on( 'click', '.remove_photo', function( e ) {
    e.preventDefault();
    $(this).parent('.photo').remove();
  });

  $("#create_access").click(function(){
    attachupload('album_cover', '添加相片','imgurl',album_create,'50,jpg|jpeg|png|gif,50','image',GlobalParams.upload_url );
  })
  /* 文件上传结束 */

  /* 区域处理 */
  $("#area_top").change(function(){
    area_top_id = $(this).val();
    area_obj = $(this).siblings('#area');
    if ( area_top_id != 0 ) {
      html = '<option value=0>请选择</option>';
      $.each( GlobalParams.areas, function( index, value) {
        if ( value.pid == area_top_id ) {
          html += "<option value='" + value.id + "'>" + value.name + "</option>";
        }
      });
      $(area_obj).html( html );
    };
  });
  /* 区域处理结束 */

  /* 特色标签处理 */
  $("#add_customer_tag").click(function(){
    input_obj = $(this).siblings('#customer_tag');
    tag_string = input_obj.val();
    if ( customer_tag >=3 ) {
      alert( '自定义标签最多三个' );
    } else {
      if( tag_string.length <=0 || tag_string.length > 10 ) {
        alert( '自定义标签长度必须大于0且不超过十个汉字' );
      } else {
        $('#brightspot').append("<li class='actived'>" + tag_string +  "<a href='javascript:void(0);' class='cbdel'></a><input type='hidden' name='info[customer_tag][]' value='" + tag_string + "'/></li>");
        customer_tag++;
        input_obj.attr('placeholder', "最多还可增加" + ( 3 - customer_tag ) + "个特色标签").val('');
      };
    }
  })
  $('#brightspot').on('click', 'li .cbdel', function() {
    $(this).parent('li').remove();
    customer_tag--;
    $('#customer_tag').attr('placeholder', "最多还可增加" + ( 3 - customer_tag ) + "个特色标签").val('');
  });

  /* 特色标签处理结束 */

  // 全选
  $('.frptao').on( 'click', '.selectall', function() {
    $(this).attr('class', 'cancelall').val('取消全选').siblings('label').children('input').prop('checked', true);
  });

  $('.frptao').on( 'click', '.cancelall', function() {
    $(this).attr('class', 'selectall').val('全选').siblings('label').children('input').prop('checked', false);
  });


  /* 小区JS效果处理 */
  $("#community_name").keyup( function() {
    house_name = $(this).val();
    var items = [];
    if ( house_name != "" ) {
      $.each( GlobalParams.houses, function( key, val ) {
        if ( val.title.indexOf(house_name) != -1 ) {
          items.push( "<li data-id='" + val.id + "' data-title='" + val.title + "' data-price='" + val.price + "'><span class='fr'>" + val.address + "</span><span class='mc'>" + val.title +  "</span></li>" );
        };
      });
      if ( items.length > 0 ) {
        $("#lp_results").html( items.join( "" ) ).show();
      };
    } else {
      $("#lp_results").html( "" ).hide();
    }
  });
  $('#lp_results').on( "click", 'li', function(e) {
    e.stopPropagation();
    $(this).parent().hide().siblings('#community_name').val(  $(this).data('title').trim()  ).siblings('#community_id').val($(this).data('id'));
    $('#address').val($(this).children('.fr').text()).focus();
  });
  // 隐藏下拉
  if ( $("#community_name").length > 0 ) {
    $(document).bind('click', function(e) {
      var container = $("#lp_results");
      if (container.has(e.target).length === 0) {
        container.hide();
      }
    });
  }
  /* 小区结束 */

})