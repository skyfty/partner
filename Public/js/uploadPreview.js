/*
*兼容浏览器(IE 谷歌 火狐) 不支持safari
*参数说明: Img:图片ID;Width:预览宽度;Height:预览高度;ImgType:支持文件类型;Callback:选择文件后回调方法;
*/
jQuery.fn.extend({
    uploadPreview: function (opts) {
        var _self = this, _this = $(this);
        opts = jQuery.extend({
            Img: "ImgPr",
            Width: 100,
            Height: 100,
            Callback: function () { }
        }, opts || {});
        _self.getObjectURL = function (file) {
            var url = null;
            if (window.createObjectURL != undefined) {
                url = window.createObjectURL(file);
            } else if (window.URL != undefined) {
                url = window.URL.createObjectURL(file);
            } else if (window.webkitURL != undefined) {
                url = window.webkitURL.createObjectURL(file);
            }
            return url;
        }
        _this.change(function () {
            if (this.value) {
                if (navigator.userAgent.indexOf("MSIE") > -1) {
                    try {
                        $("#" + opts.Img).attr('src', _self.getObjectURL(this.files[0]));
                    } catch (e) {
                        var src = "";
                        var obj = $("#" + opts.Img);
                        var div = obj.parent("div")[0];
                        _self.select();
                        if (top != self) {
                            window.parent.document.body.focus();
                        } else {
                            _self.blur();
                        }
                        src = document.selection.createRange().text;
                        document.selection.empty();
                        obj.hide();
                        obj.parent("div").css({
                            'filter': 'progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)',
                            'width': opts.Width + 'px',
                            'height': opts.Height + 'px'
                        });
                        div.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = src;				
						
						files_name = this.files[0].name;
						$("#" + opts.Img+"_name").val(files_name);
                    }
                } else {
                    $("#" + opts.Img).attr('src', _self.getObjectURL(this.files[0]));
					files_name = this.files[0].name;
					$("#" + opts.Img+"_name").val(files_name);
                }
                opts.Callback();
            }
        });
    }
});

//去掉一个文件
$('body').on('click','.btn_reduce_files', function(){
	var selector_id = $(this).parents('table').attr('id');
	var item = selector_id.substr(13);
	$('#tables_files_'+item).remove();
});