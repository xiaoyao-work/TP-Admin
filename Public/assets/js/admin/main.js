// JavaScript Document
$(function() {
    var HeadHeight = $('#header').outerHeight();
    var LeftWidth = $('#leftcontent').outerWidth();
    var rbHeight = $('#right_topbar').outerHeight();
    var logoWidth = $('#logo').outerWidth();



    var resize = function() {
        var WinHeight = $(window).height();
        var WinWidth = $(window).width();
        var Cheight = WinHeight - HeadHeight;
        $('#content,#leftcontent,#leftmain,#leftsplit,#rightcontent').css('height', Cheight + 'px');
        $('#rightcontent,#right_topbar').css('width', (WinWidth - LeftWidth) + 'px');
        $('#header-main').css('width', (WinWidth - logoWidth - 5) + 'px');
        $('#right_main').css({'width': (WinWidth - LeftWidth - 14) + 'px', 'height': (Cheight - rbHeight - 12) + 'px'});
    };

    var oldLeftWidth = LeftWidth;

    var leftsplit = $('#leftsplit');
    leftsplit.click(function() {
        if ($('#leftmain').is(':visible')) {
            leftsplit.css('background-position', 'right center');
            $('#leftmain').hide();
            LeftWidth = $('#leftsplit').outerWidth();
            $('#leftcontent').css('width', LeftWidth + 'px');
        }
        else {
            leftsplit.css('background-position', '2px center');
            $('#leftmain').show();
            LeftWidth = oldLeftWidth;
            $('#leftcontent').css('width', LeftWidth + 'px');
        }
        resize();
    });

    $(window).resize(resize);
    resize();

    var navs = $('#header-nav a').click(function() {
        navs.removeClass('active');
        var $this = $(this);
        $this.addClass('active');
        var url = $this.attr('href');
        if (url.length > 0 && url !== '#') {
            $.get(url, null, function(html) {
                $('#leftmain').html(html);
            });
        }
        return false;
    });
    //第一个默认点中
    navs.first().click();

    var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    if (window.ActiveXObject) {
        Sys.ie = ua.match(/msie ([\d.]+)/)[1];
    }

    //IE6 兼容支持
    if (Sys.ie && Number(Sys.ie) < 7) {
        $('#leftmain dd,#leftmain li,#leftsplit').live('mouseenter', function() {
            $(this).addClass('hover');
        });
        $('#leftmain dd,#leftmain li,#leftsplit').live('mouseleave', function() {
            $(this).removeClass('hover');
        });
    }
    try {
        $('#leftmain dd a,#leftmain li a').live('click',
                function() {
                    $('#leftmain dd,#leftmain li').removeClass('active');
                    $(this).parent('dd,li').addClass('active');
                }
        );

        $('#leftmain dt').live('click', function() {
            var icon = $(this).find('i.icon');
            if (icon.is('.folder-open')) {
                icon.removeClass('folder-open').addClass('folder-close');
                $(this).siblings('dd').hide();
            }
            else {
                icon.removeClass('folder-close').addClass('folder-open');
                $(this).siblings('dd').show();
            }
        });

    } catch (e) {
    }

    var MainFrame = $('#Main');
    var lable = $('#Main_Title');
    MainFrame.load(function() {
        try {
            if (this.contentWindow.document.title == null || this.contentWindow.document.title == '') {
                var title = $('#leftmain dd.active a').text();
                if (title == null || title == '') {
                    title = '默认首页';
                }
                lable.text(title);
            }
            else {
                lable.text(this.contentWindow.document.title);
            }
        }
        catch (e) {
            var title = $('#leftmain dd.active a').text();
            if (title == null || title == '') {
                title = '默认首页';
            }
            lable.text(title);
        }
    });


});