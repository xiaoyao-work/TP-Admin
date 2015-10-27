function ConfirmDel(message) {
	message = message ? message :  "是否执行此操作，一旦删除将不可恢复！";
	if(confirm(message))
		return true;
	else
		return false;
}

function confirmurl(url,message) {
	// url = url+'&pc_hash='+pc_hash;
	if(confirm(message)) redirect(url);
}
function redirect(url) {
	location.href = url;
}
//滚动条
$(function(){
	$(":text").addClass('input-text');
})

/**
* 全选checkbox,注意：标识checkbox id固定为为check_box
* @param string name 列表check名称,如 uid[]
*/
function selectall(name) {
	if ($("#check_box").attr("checked")=='checked') {
		$("input[name='"+name+"']").each(function() {
			$(this).attr("checked","checked");

		});
	} else {
		$("input[name='"+name+"']").each(function() {
			$(this).removeAttr("checked");
		});
	}
}

function openwinx(url,name,w,h) {
	if(!w) w=screen.width-4;
	if(!h) h=screen.height-95;
	// url = url+'&pc_hash='+pc_hash;
	window.open(url,name,"top=100,left=400,width=" + w + ",height=" + h + ",toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no");
}
//弹出对话框
function omnipotent(id,linkurl,title,close_type,w,h) {
	if(!w) w=700;
	if(!h) h=500;
	art.dialog.open(linkurl, {
		id: id,
		width: w,
		height: h,
		title: title,
		ok: function() {
			var iframe = this.iframe.contentWindow;
			if (!iframe.document.body) {
				alert('窗口还没加载完毕！')
				return false;
			};
			if(close_type==1) {
				return true;
			} else {
				var form = iframe.$('#dosubmit');
				form.click();
			}
			return false;
		},
		cancel: true,
		lock: true,
		background: "#000"
	});
	/*art.dialog.open(linkurl, {id:id, title:title, width:w, height:h, lock:true},
		function(){
			if(close_type==1) {
				art.dialog({id:id}).close()
			} else {
				var d = art.dialog({id:id}).data.iframe;
				var form = d.document.getElementById('dosubmit');form.click();
			}
			return false;
		},
		function(){
			art.dialog({id:id}).close()
		});void(0);*/
}

function SwapTab(name,cls_show,cls_hide,cnt,cur){
	for(i=1;i<=cnt;i++){
		if(i==cur){
			$('#div_'+name+'_'+i).show();
			$('#tab_'+name+'_'+i).attr('class',cls_show);
		}else{
			$('#div_'+name+'_'+i).hide();
			$('#tab_'+name+'_'+i).attr('class',cls_hide);
		}
	}
}

function remove_div(id) {
	$('#'+id).remove();
}
$(function(){
	if (!$('body').hasClass('objbody')) {
		var copyright_html = $('<div class="copyright cr"></div>').html('Copyright @ 2012 - 2015 <a href="http://www.hhailuo.com" target="_blank">红海螺</a> All Rights Reserved. 官网: <a href="http://tp-admin.hhailuo.com" target="_blank">TP-Admin</a>');
		$('body').append(copyright_html);
	};
});
