/* 添加房源图片回调函数 */
function album_create(returnid) {
  var in_content_str = this.$("#att-status").html().substring(1);
  if (in_content_str) {
    var in_content = in_content_str.split('|');
    var ids_str = this.$("#att-ids").html().substring(1);
    var ids = ids_str.split('|');
    var html = "";
    for (var i = 0; i < in_content.length; i++) {
      html += '<div class="photo">' +
      '<img src="' + in_content[i] + '" data-url="' + in_content[i] + '" />' +
      '<input type="hidden" name="info[room_images][' + ids[i] + '][id]" value="' + ids[i] + '" />'+
      '<input type="hidden" name="info[room_images][' + ids[i] + '][url]" value="' + in_content[i] + '" />' + '<a class="remove_photo" href="#"></a>' +
      '</div>';
    }
    $(html).insertAfter("#create_access");
  }
}