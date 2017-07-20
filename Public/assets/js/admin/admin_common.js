function ConfirmDel(message) {
	message = message ? message :  "是否执行此操作，一旦删除将不可恢复！";
	if(confirm(message))
		return true;
	else
		return false;
}

function confirmurl(url,message) {
	if(confirm(message)) redirect(url);
}
function redirect(url) {
	window.location.href = url;
}

/**
* 全选checkbox,注意：标识checkbox id固定为为check_box
* @param string name 列表check名称,如 uid[]
*/
function selectall(name) {
	if ($("#check_box").prop("checked")) {
		$("input[name='"+name+"']").each(function() {
			$(this).prop("checked", true);
		});
	} else {
		$("input[name='"+name+"']").each(function() {
			$(this).prop("checked", false);
		});
	}
}

function openwinx(url,name,w,h) {
	if(!w) w=screen.width-4;
	if(!h) h=screen.height-95;
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

function change_list_rows(){
	var list_rows = $("select[name*='list_rows_select']").val();
	if(list_rows){
		$.cookie("list_rows_select", list_rows, {path: window.location.pathname});
		window.location.reload();
	}

}