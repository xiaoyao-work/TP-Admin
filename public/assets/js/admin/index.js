// 窗口修改
if(!Array.prototype.map) {
    Array.prototype.map = function(fn,scope) {
        var result = [],ri = 0;
        for (var i = 0,n = this.length; i < n; i++){
            if(i in this){
                result[ri++]  = fn.call(scope ,this[i],i,this);
            }
        }
        return result;
    };
}
var getWindowSize = function(){
    return ["Height","Width"].map(function(name){
        return window["inner"+name] ||
        document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
    });
}
window.onload = function (){
    if(!+"\v1" && !document.querySelector) {
        // for IE6 IE7
        document.body.onresize = resize;
    } else {
        window.onresize = resize;
    }
    function resize() {
        wSize();
        return false;
    }
}
function wSize() {
    var str=getWindowSize();
    var strs= new Array();
    strs=str.toString().split(",");
    var heights = strs[0] - 118,
    Body = $('body');
    $('#rightMain').height(heights);
    if(strs[1]<980){
        $('.header').css('width',980+'px');
        $('#content').css('width',980+'px');
        Body.attr('scroll','');
        Body.removeClass('objbody');
    }else{
        $('.header').css('width','auto');
        $('#content').css('width','auto');
        Body.attr('scroll','no');
        Body.addClass('objbody');
    }

    $("#openClose").height(heights + 36);
    $("#Scroll").height(heights + 36);
    windowW();
}
function windowW(){
    if($('#Scroll').height()<$("#leftMain").height()){
        $(".scroll").show();
    }else{
        $(".scroll").hide();
    }
}
wSize();
windowW();

(function(){
    var addEvent = (function(){
        if (window.addEventListener) {
            return function(el, sType, fn, capture) {
                el.addEventListener(sType, fn, (capture));
            };
        } else if (window.attachEvent) {
            return function(el, sType, fn, capture) {
                el.attachEvent("on" + sType, fn);
            };
        } else {
            return function(){};
        }
    })(),
    Scroll = document.getElementById('Scroll');
    // IE6/IE7/IE8/Opera 10+/Safari5+
    addEvent(Scroll, 'mousewheel', function(event){
        event = window.event || event ;
        if(event.wheelDelta <= 0 || event.detail > 0) {
            Scroll.scrollTop = Scroll.scrollTop + 29;
        } else {
            Scroll.scrollTop = Scroll.scrollTop - 29;
        }
    }, false);

    // Firefox 3.5+
    addEvent(Scroll, 'DOMMouseScroll',  function(event){
        event = window.event || event ;
        if(event.wheelDelta <= 0 || event.detail > 0) {
            Scroll.scrollTop = Scroll.scrollTop + 29;
        } else {
            Scroll.scrollTop = Scroll.scrollTop - 29;
        }
    }, false);
})();

function menuScroll(num){
    var Scroll = document.getElementById('Scroll');
    if(num==1){
        Scroll.scrollTop = Scroll.scrollTop - 60;
    }else{
        Scroll.scrollTop = Scroll.scrollTop + 60;
    }
}

//站点下拉菜单
$(function(){
    //默认载入左侧菜单
    $("#leftMain").load(module + "/Index/left/mid/59");

    //左侧开关
    $("#openClose").click(function(){
        if($(this).data('clicknum')==1) {
            $("html").removeClass("on");
            $(".left_menu").removeClass("left_menu_on");
            $(this).removeClass("close");
            $(this).data('clicknum', 0);
            $(".scroll").show();
        } else {
            $(".left_menu").addClass("left_menu_on");
            $(this).addClass("close");
            $("html").addClass("on");
            $(this).data('clicknum', 1);
            $(".scroll").hide();
        }
        return false;
    });
});

function _M(menuid,targetUrl) {
    $("#menuid").val(menuid);
    $("#bigid").val(menuid);
    $("#paneladd").html('<a class="panel-add" href="javascript:add_panel();"><em>添加</em></a>');
    $("#leftMain").load(module + "/Index/left/mid/"+menuid, {limit: 25}, function(responseText, textStatus, XHR){
        windowW();
    });

    $('#_M'+menuid).addClass("active").siblings('li').removeClass('active');
    //当点击顶部菜单后，隐藏中间的框架
    $('#display_center_id').css('display','none');

    //显示左侧菜单，当点击顶部时，展开左侧
    $(".left_menu").removeClass("left_menu_on");
    $("#openClose").removeClass("close");

    $("#openClose").data('clicknum', 0);
    $("#current_pos").data('clicknum', 1);
}

function _MP(menuid,targetUrl) {
    $("#menuid").val(menuid);
    $("#paneladd").html('<a class="panel-add" href="javascript:add_panel();"><em>添加</em></a>');
    $("#rightMain").attr('src', targetUrl+'&menuid='+menuid);
    $("#current_pos").data('clicknum', 1).text($("#top_menu .active a").text() + " > " + $('#_MP'+menuid).text());
}

setInterval(function() {
    $.get(module + "/Index/public_session_life", function(res) {
        eval(res);
    });
}, 60000);