(function ($, _) {
	jQuery(document).ready(function () {
        $.fn.extend({
        	fileUpload: function (options) {
                var that = this , tarImg = options.tarimg || '' ,
                    action = options.action || 'uploader/ajax_uploadPicture' ,
                    name = options.name || 'logo[]' ,
                    genre = options.genre || 'img' ,
                    size = options.size || 2048 * 1024 ,
                    user_id = $('body').attr('id') ,
                    type = options.type || 'logo' ,
                    arrType = {
                        'picture': $("#pictureUpload").find("img"),
                        'logo': $("[rel='img-target-logo']"),
                        'card': $("[rel='img-target']"),
                        'avatar': $("[rel='img-target-avatar']"),
                        'album': $("[rel='img-taget-album']")
                    },
                    text = $(that).text();
	                new AjaxUpload($(that), {
	                    action: action,
	                    name: name,
	                    data: {
	                        uid: user_id,
	                        type: type
	                    },
	                    validation: {
	                        sizeLimit: size
	                    },
	                    onSubmit: function (file, extension) {
	                        $(that).text('loading...');
	                        $(that).attr('disabled', 'disabled');
	                        tarImg = arrType[type];
	                        if (genre === 'img') {
	                            if (extension && /^(jpg|png|jpeg|gif|JPG|PNG|JPEG|GIF)$/.test(extension)) {
	                                $('#od_avatar_show').attr('src', 'http://img.36kr.net/css/image/icon/loading.gif');
	                                if (type === 'picture') {
	                                    var activeCount = 0;
	                                    tarImg.each(function () {
	                                        if ($(this).hasClass('active')) {
	                                            activeCount++;
	                                        }
	                                    });
	                                    if (activeCount === 3) {
	                                        alert('您已上传三张截图');
	                                        $(that).text(text);
	                                        $(that).removeAttr('disabled');
	                                        return false;
	                                    }
	                                }
	                            } else {
	                                alert('上传的图片仅限gif,jpg,png,jpeg!');
	                                $(that).text(text);
	                                $(that).removeAttr('disabled');
	                                return false;
	                            }
	                        }
	                        return true;
	                    },
	                    onComplete: function (file, res) {
	                        var response = $.parseJSON(res.toString().jsonParse());
	                        if(response.code == 200){
		                        tarImg = arrType[type];
		                        if (type === 'picture') {
		                            var i;
		                            for (i = 0; i < 3; i++) {
		                                if ($(tarImg[i]).attr('data-value').length === 0) {
		                                    tarImg = $(tarImg[i]);
		                                    break;
		                                }
		                            }
		                            tarImg.after('<a rel="delete-picture" data-value="' + response.picture_id + '" data-check="' + response.picturec + '">删除</a>');
		                        }
		                        var tmpimg = new Image();
		                        tmpimg.src=response.picture;
		                        	tmpimg.onload = function(){
		                            	tarImg
		                                .attr('src', response.picture)
		                                .attr('data-value', response.picture_id)
		                                .attr('data-check', response.picturec)
		                                .addClass('active');
		                            	$(that).text(text);
		                                $(that).removeAttr('disabled');
		                            };
		                        	tmpimg.onreadystatechange = function() { //处理IE等浏览器的缓存问题：图片缓存后不会再触发onload事件
		                                if (image.readyState == "complete") {
		                                	tarImg
		                                    .attr('src', response.picture)
		                                    .attr('data-value', response.picture_id)
		                                    .attr('data-check', response.picturec)
		                                    .addClass('active');
		                                	$(that).text(text);
		                                    $(that).removeAttr('disabled');
		                                    $(that).patent("div.controls").parent().removeClass("error");
		                                }
		                            };
		                    }else{
		                    	alert(response.error);
		                    	 $(that).text(text);
	                             $(that).removeAttr('disabled');
		                    }
	                    }
	                });
            }
        });
	});
}(jQuery, Grape));