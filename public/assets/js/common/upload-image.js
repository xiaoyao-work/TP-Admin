var ImageUploader = function(args) {
    var $ = jQuery;
    this.params = $.extend({}, ImageUploader.DEFAULTS, $.isPlainObject(args) && args);
};

ImageUploader.DEFAULTS = {
    auto: true,
    swf: window.ASSETS_DOMAIN + 'js/webuploader/Uploader.swf',
    server: window.UPLOAD_IMAGE_URL,
    pick: '.js-upload-avatar',
    fileSingleSizeLimit: 5242880,
    fileNumLimit: 1,
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,png',
        mimeTypes: 'image/*'
    },
    fileQueued: function(file) {},
    uploadSuccess: function(file, response, uploader) {}
}

ImageUploader.prototype.beforeFileQueued = function(uploader) {
    uploader.on('beforeFileQueued', function(file) {
        if (file.size > uploader.option('fileSingleSizeLimit')) {
            dialog({
                width: 440,
                height: 80,
                title: '提示',
                content: '<p style="text-align: center;padding: 30px;">' + '图片大小不能超过' + WebUploader.Base.formatSize(uploader.option('fileSingleSizeLimit')) + '</p>',
                okValue: '确定',
                ok: function() {
                    return true;
                },
                fixed: true
            }).showModal();
            return false;
        };
    });
}

// 上传结果处理 (公共)
ImageUploader.prototype.uploadProcess = function(uploader) {
    var $ = jQuery,
        _this = this;
    // 文件上传过程中创建进度条实时显示。
    uploader.on('uploadProgress', function(file, percentage) {
        var $li = $('#' + file.id),
            $percent = $li.find('p');
        // 避免重复创建
        if (!$percent.length) {
            $percent = $('<p class="progress"></p>')
                .appendTo($li);
        }
        $percent.text('上传中' + percentage * 100 + '%');
    });
    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on('uploadSuccess', function(file, response) {
        if (response.code != 0) {
            dialog({
                width: 440,
                height: 80,
                title: '提示',
                content: '<p style="text-align: center;padding: 30px;">' + response.message + '</p>',
                okValue: '确定',
                ok: function() {
                    return true;
                },
                fixed: true
            }).showModal();
        } else {
            _this.params.uploadSuccess(file, response, uploader);
        }
    });

    // 文件上传失败，显示上传出错。
    uploader.on('uploadError', function(file, reason) {
        dialog({
            width: 440,
            height: 80,
            title: '提示',
            content: '<p style="text-align: center;padding: 30px;">' + reason + '</p>',
            okValue: '确定',
            ok: function() {
                return true;
            },
            fixed: true
        }).showModal();

        // to-do 失败回调
        var $obj = $('#' + file.id);
        $obj.html('<p class="error">上传失败！~'+reason+'</p>');
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on('uploadComplete', function(file) {
        $('#' + file.id).find('.progress').remove();
    });

    uploader.on('uploadFinished', function(file) {
        uploader.reset();
    });

    // 文件上传失败，显示上传出错。
    uploader.on('error', function(type) {
        if (type == 'Q_TYPE_DENIED') {
            var message = '上传文件类型不匹配！';
            dialog({
                width: 440,
                height: 80,
                title: '提示',
                content: '<p style="text-align: center;padding: 30px;">' + message + '</p>',
                okValue: '确定',
                ok: function() {
                    return true;
                },
                fixed: true
            }).showModal();
        } else if (type == 'Q_EXCEED_NUM_LIMIT') {
            var message = '上传队列已满，请等待队列中的文件上传完成！';
            dialog({
                width: 440,
                height: 80,
                title: '提示',
                content: '<p style="text-align: center;padding: 30px;">' + message + '</p>',
                okValue: '确定',
                ok: function() {
                    return true;
                },
                fixed: true
            }).showModal();
        }
    });
}

// 创建上传器
ImageUploader.prototype.createUploader = function() {
    var $ = jQuery,
        _this = this;
    // this.uploader_base = WebUploader.Base;
    return WebUploader.create({
        auto: _this.params.auto,
        swf: _this.params.swf,
        server: _this.params.server,
        pick: _this.params.pick,
        fileSingleSizeLimit: _this.params.fileSingleSizeLimit,
        fileNumLimit: _this.params.fileNumLimit,
        accept: _this.params.accept
    });
}

// 头像上传
ImageUploader.prototype.upload = function() {
    var $ = jQuery,
        _this = this;
    var uploader = _this.createUploader();
    _this.beforeFileQueued(uploader);
    uploader.on('fileQueued', function(file) {
        _this.params.fileQueued(file);
    });
    _this.uploadProcess(uploader);
}