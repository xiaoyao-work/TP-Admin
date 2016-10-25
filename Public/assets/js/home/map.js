function MapOP(BMap, options) {
    this.BMap = BMap;
    this.options = $.extend({}, MapOP.DEFAULTS, $.isPlainObject(options) && options);
    // this.init();
}
MapOP.prototype = {
    constructor: MapOP,
    query: function (element, keywords, callback) {
        var $element = $(element);
        var $url = "http://api.map.baidu.com/place/v2/search?query=" + keywords + "&page_size="+ this.options.page_size +"&page_num="+ this.options.page_num +"&scope="+ this.options.scope +"&location="+ this.options.location +"&radius="+ this.options.radius +"&output="+ this.options.output +"&ak="+ this.options.ak;
        _BMap = this.BMap;
        $.ajax({
            url : $url,
            type: 'get',
            dataType: 'jsonp',
            success: function(res) {
                // console.log(res);
                var allOverlay = _BMap.getOverlays();
                $.each(allOverlay, function(index, obj) {
                    if (obj.type == 'marker_point') {
                        _BMap.removeOverlay(obj);
                    }
                });

                if (res.status == 0) {
                    var html = '<table>';
                    $.each(res.results, function(index, obj) {
                        html += '<tr><th>[' + obj.name + ']' + obj.address + '</th><td>' + obj.detail_info.distance + '米, 步行' + Math.ceil(obj.detail_info.distance/40) + '分钟</td></tr>';
                        var marker = new BMap.Marker(new BMap.Point(obj.location.lng, obj.location.lat));
                        marker.type = 'marker_point';
                        _BMap.addOverlay(marker);
                    });
                    html += '</table>';
                    $element.html(html);
                }
            }
        });
    }
}

MapOP.DEFAULTS = {
    page_size: 10,
    page_num: 1,
    scope: 2,
    location: '',
    radius: 1500,
    output: 'json',
    ak: ''
};

MapOP.setDefaults = function (options) {
    $.extend(MapOP.DEFAULTS, options);
};


// 复杂的自定义覆盖物
function ComplexCustomOverlay(point, text, mouseoverText){
    this._point = point;
    this._text = text;
    this._overText = mouseoverText;
}

ComplexCustomOverlay.prototype = new BMap.Overlay();
ComplexCustomOverlay.prototype.initialize = function(map){
    this._map = map;
    var div = this._div = document.createElement("div");
    div.style.position = "absolute";
    div.style.zIndex = BMap.Overlay.getZIndex(this._point.lat);
    div.style.backgroundColor = "#EE5D5B";
    div.style.border = "1px solid #BC3B3A";
    div.style.color = "white";
    div.style.height = "24px";
    div.style.padding = "2px";
    div.style.lineHeight = "18px";
    div.style.whiteSpace = "nowrap";
    div.style.MozUserSelect = "none";
    div.style.fontSize = "12px"
    var span = this._span = document.createElement("span");
    div.appendChild(span);
    span.appendChild(document.createTextNode(this._text));
    var that = this;

    var arrow = this._arrow = document.createElement("div");
    arrow.style.background = "url(http://map.baidu.com/fwmap/upload/r/map/fwmap/static/house/images/label.png) no-repeat";
    arrow.style.position = "absolute";
    arrow.style.width = "11px";
    arrow.style.height = "10px";
    arrow.style.top = "22px";
    arrow.style.left = "10px";
    arrow.style.overflow = "hidden";
    div.appendChild(arrow);

    div.onmouseover = function(){
        this.style.backgroundColor = "#6BADCA";
        this.style.borderColor = "#0000ff";
        this.getElementsByTagName("span")[0].innerHTML = that._overText;
        arrow.style.backgroundPosition = "0px -20px";
    }

    div.onmouseout = function(){
        this.style.backgroundColor = "#EE5D5B";
        this.style.borderColor = "#BC3B3A";
        this.getElementsByTagName("span")[0].innerHTML = that._text;
        arrow.style.backgroundPosition = "0px 0px";
    }

    this._map.getPanes().labelPane.appendChild(div);

    return div;
}

ComplexCustomOverlay.prototype.draw = function(){
    var map = this._map;
    var pixel = map.pointToOverlayPixel(this._point);
    this._div.style.left = pixel.x - parseInt(this._arrow.style.left) + "px";
    this._div.style.top  = pixel.y - 30 + "px";
}