(function (factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as anonymous module.
    define(['jquery'], factory);
  } else if (typeof exports === 'object') {
    // Node / CommonJS
    factory(require('jquery'));
  } else {
    // Browser globals.
    factory(jQuery);
  }
})(function ($) {
    var Community = function(args) {
        this.timeout;
        this.params = $.extend({}, Community.DEFAULTS, $.isPlainObject(args) && args);
    };

    Community.DEFAULTS = {};

    Community.prototype.debounce = function(func, wait, immediate) {
        var _this = this, result;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                _this.timeout = null;  /* 回调函数得以顺利执行，手动释放 */
                if (!immediate) result = func.apply(context, args);
            };
            var callNow = immediate && !_this.timeout;
            clearTimeout(_this.timeout);
            _this.timeout = setTimeout(later, wait);
            if (callNow) result = func.apply(context, args);
            return result;
        }
    };

    Community.prototype.suggestionSearch = function(keywords, target, delay) {
        delay = delay ? delay : 500;
        var _this = this;
        _this.debounce(function(keywords, target) {
            $.ajax({
                url: window.globalParams.DOMAIN + '/community/suggestion-search.html',
                type: 'POST',
                data: {'keywords': keywords},
                dataType: 'json',
                success: function(res) {
                    if (res.code == 0) {
                        _this.constructHtml(res.data, target);
                    }
                }
            });
        }, delay)(keywords, target);
    };

    Community.prototype.constructHtml = function($data, target) {
        var html = '';
        $.each($data, function(index, ele) {
            html += '<li data-id="'+ ele.id +'"><span class="title">' + ele.title + '</span>' +
                '<span class="address">' + ele.address + '</span>'
            '</li>';
        });
        $(target).html(html);
    }

    Community.prototype.communityAutocomplete = function(keywords, target, delay) {
        delay = delay ? delay : 500;
        debounce(function(keywords, target) {
            console.log(keywords, target);
            return ;
            $(target).on( "click", 'li', function(e) {
                e.stopPropagation();
                var _this = $(this);
                _this.parent().hide();
                _this.siblings('#community_name').val(_this.data('title'));
                _this.siblings('#community_id').val(_this.data('id'));
                $('#address').val($(this).children('.fr').text()).focus();
            });

        }, delay)(keywords, target);
    };

    Community.prototype.getQueryString = function() {
        var query = [];
        $('.js-search a.active').each(function(index, obj) {
            var obj = $(obj);
            var val = $('input#' + obj.data('type')).val();
            if (val) {
                query.push(obj.data('type') + val);
            }
        });
        var keywords = $('#js-mykeyword').val();
        if (keywords) {
            query.push('k' + $('#js-mykeyword').val());
        }
        return query.join('-');
    }

    $(function() {
        var community_obj = new Community();
        $('#js-mykeyword').keyup(function() {
            var keywords = $.trim($(this).val());
            if (keywords.length > 0) {
                community_obj.suggestionSearch(keywords, '#keywords-match-community');
            }
        });

        $('.keywords-search-form form').submit(function() {
            window.location.href = window.globalParams.DOMAIN + '/community.html?search=' + community_obj.getQueryString();
            return false;
        });

        $('#keywords-match-community').on('click', 'li', function(e) {
            window.location.href = window.globalParams.DOMAIN + '/community/' + $(this).data('id') + '.html';
        });

        /*$('#js-mykeyword').blur(function() {
            $('#keywords-match-community').hide();
        }).focus(function() {
            $('#keywords-match-community').show();
        });*/

        $('.js-search a').click(function(e) {
            e.preventDefault();
            var _this = $(this);
            _this.addClass('active').siblings('a.active').removeClass('active');
            $('input#' + _this.data('type')).val(_this.data('val'));
            window.location.href = window.globalParams.DOMAIN + '/community.html?search=' + community_obj.getQueryString();
        });

    });
});