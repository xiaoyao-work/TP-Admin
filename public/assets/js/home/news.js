$(function() {
    function load_terms(obj, post_type, taxonomy) {
        $.ajax({
            url: window.urls.getTerms,
            data: {post_type: post_type, 'taxonomy': taxonomy},
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res.code == 0) {
                    obj.html('<ul>' + res.data + '</ul>');
                } else {
                    obj.html('<p class="error-tips">类目加载失败</p>');
                }
            },
            error: function() {
                obj.html('<p class="error-tips">类目加载失败</p>');
            }
        });
    }

    $( "#accordion" ).accordion({
        header: "> div > h3",
        heightStyle: 'content',
        activate: function( event, ui ) {
            if(ui.newPanel.find('ul').length == 0) {
                var post_type = ui.newPanel.data('post_type');
                var taxonomy = ui.newPanel.data('taxonomy');
                load_terms(ui.newPanel, post_type, taxonomy);
            }
        },
        create: function( event, ui ) {
            var post_type = ui.panel.data('post_type');
            var taxonomy = ui.panel.data('taxonomy');
            load_terms(ui.panel, post_type, taxonomy);
        }
    });

    function setContentSidebarHeight() {
        $sidebar_height = Math.max($('.content-sidebar').innerHeight(), $('.content-body').innerHeight());
        $('.content-sidebar').css('height', $sidebar_height);
    }

    if($('.content-sidebar').length > 0) {
        setContentSidebarHeight();
    }
});