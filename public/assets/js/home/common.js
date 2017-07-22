(function($){
    $(function() {
        if ($('#sel-search').length > 0) {
            $("#sel-search").selectmenu({
                width: 90,
            });
        }
        $('input').placeholder();
    });
}(jQuery));