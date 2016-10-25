var getWindowSize = function() {
    return ["Height","Width"].map(function(name){
        return window["inner"+name] ||
        document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
    });
}

var Index = function () {
    return {
        //main function to initiate the module
        init: function () {
            $(window).resize(function() {
                Index.resize();
            });
            setInterval(function() {
                $.get("/Index/public_session_life", function(res) {
                    eval(res);
                });
            }, 60000);
            //默认载入左侧菜单
            this._M(59);
            this.resize();
        },
        resize: function() {
            var str = getWindowSize();
            var strs= new Array();
            strs=str.toString().split(",");
            $('.page-content').height(strs[0] - 75).css('min-height', strs[0] - 75 > 600 ? strs[0] - 75 : 600);
            $('#rightMain').height($('.page-content').height() - 72);
        },
        _M: function(menuid) {
            $("#JS-left_menu").load("/Index/left/mid/"+menuid);
            $('#_M' + menuid).addClass("active").siblings('li').removeClass('active');
        },

        _MP: function (menuid,targetUrl) {
            $("#rightMain").attr('src', targetUrl + '&menuid='+menuid);
            var menu_obj = $('#_MP' + menuid);
            var parent_li = menu_obj.parents('li');

            parent_li.siblings('li.active').find('a > span.selected').remove();
            $('.page-sidebar-menu').find('.active').removeClass('active');

            menu_obj.addClass('active');
            parent_li.addClass('active').children('a').append('<span class="selected "></span>');

            // 更新面包屑
            $("#JS-breadcrumb").html(
            '<li>' +
                '<i class="icon-home"></i>' +
                '<a href="/Index/index.html">Home</a>' +
            '</li>' +
            '<li>' +
                '<i class="icon-angle-right"></i>' +
                '<a href="javascript:void(0);">' + $("#JS-top_menu .active a").text() + '</a>' +
            '</li>' +
            '<li>' +
                '<i class="icon-angle-right"></i>' +
                '<a href="javascript:void(0);">' + $('#_MP'+menuid).text() + '</a>' +
            '</li>');
        }
    };
}();
