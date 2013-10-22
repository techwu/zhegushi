(function ($, _) {
	jQuery(document).ready(function () {
        $.fn.extend({
        	fileUpload: function (options) {
                var that = this , tarImg = options.tarimg || '' ,
                    action = options.action || _.BASE_URL+'uploader/ajax_uploadPicture' ,
                    name = options.name || 'logo[]' ,
                    genre = options.genre || 'img' ,
                    size = options.size || 20*2048 * 1024 ,
                    user_id = $('body').attr('id') ,
                    type = options.type || 'logo' ,
                    id = options.id || '',
                    arrType = {
                        'picture': $("#pictureUpload"),
                        'logo': $("[rel='img-target-logo']"),
                        'card': $("[rel='img-target']"),
                        'avatar': $("[rel='img-target-avatar']"),
                        'album': $("[rel='img-taget-album']") ,
                        'file' :$("#bussinessFiles") 
                    },
                    text = $(that).html();
	                new AjaxUpload($(that), {
	                    action: action,
	                    name: name,
	                    data: {
	                        uid: user_id,
	                        type: type , 
	                        id : id
	                    },
	                    validation: {
	                        sizeLimit: size
	                    },
	                    onSubmit: function (file, extension) {
	                        tarImg = arrType[type];
	                        if (genre === 'img') {
	                            if (extension && /^(jpg|png|jpeg|gif|JPG|PNG|JPEG|GIF)$/.test(extension)) {
	                                if (type === 'picture' || type === 'logo') {
	                                	$(that).html("<img src=\"http://img.36tr.com/img/loading.gif\">")
	                                	$(that).attr('disabled', 'disabled');
	                                	tarImg = arrType[type];
                                        return true;
	                                }
	                            }else {
	                                alert('上传的图片仅限gif,jpg,png,jpeg!');
	                                $(that).html(text);
	                                $(that).removeAttr('disabled');
	                                return false;
	                            }
	                        }else{
	                        	if (extension && /^(doc|DOC|pptx|PPTX|xls|XLS|ppt|PPT|docx|DOCX|pdf|PDF)$/.test(extension)) {
	                        		
	                        	}else{
	                        		 alert('上传的图片仅限doc、ppt、pdf、xls等文件!');
	                        		 $(that).html("<img src=\"http://img.36tr.com/img/loading.gif\">")
		                             $(that).removeAttr('disabled');
		                             return false;
	                        	}
	                        }
	                        $(that).html("<img src=\"http://img.36tr.com/img/loading.gif\">")
 	                        $(that).attr('disabled', 'disabled');
 	                        tarImg = arrType[type];
	                        return true;
	                    },
	                    onComplete: function (file, res) {
	                    	//TODO 兼容非ajax的情况
                            if(navigator.appName =="Microsoft Internet Explorer" && navigator.appVersion.split(";")[1].replace(/[ ]/g,"")=="MSIE8.0"){ 
                                alert("图片已经成功上传！"); 
                            } 
	                        var response = $.parseJSON(res.toString().jsonParse());
	                        if(response.code == 200){
	                        	tarImg = arrType[type];
	                        	if (genre === 'img') {
			                        var tmpimg = new Image();
			                        tmpimg.src=response.picture;
			                        	tmpimg.onload = function(){
			                        		if (type === 'picture') {
						                        	if ($(tarImg).length >=0){
						                        		$(tarImg).find(".picture-box").last().after("<div class=\"picture-box\"><img src=\"\" rel=\"img-target-picture\" data-value=\"\" data-check=\"\" class=\"img-target img-on\"></div>");
						                        		tarImg = $(tarImg).find(".picture-box img").last();
						                        	}
						                    }
			                            	tarImg
			                                .attr('src', response.picture)
			                                .attr('data-value', response.picture_id)
			                                .attr('data-check', response.picturec)
			                                .addClass('active');
			                            	if (type === 'picture') {
			                            		tarImg.after('<a rel="delete-picture" data-value="' + response.picture_id + '" data-check="' + response.picturec + '"><img src="http://img.36tr.com/img/tags/close-tag.png"/></a>');;
			                            	}
			                            	$(that).html(text);
			                                $(that).removeAttr('disabled');
			                            };
			                        	tmpimg.onreadystatechange = function() { //处理IE等浏览器的缓存问题：图片缓存后不会再触发onload事件
			                                if (image.readyState == "complete") {
			                                	 if (type === 'picture') {
			 			                        	if ($(tarImg).length >=0){
			 			                        		$(tarImg).find(".picture-box").last().after("<div class=\"picture-box\"><img src=\"\" rel=\"img-target-picture\" data-value=\"\" data-check=\"\" class=\"img-target img-on\"></div>");
			 			                        		tarImg = $(tarImg).find(".picture-box img").last();
			 			                        	}
			 			                        }
			                                	tarImg
			                                    .attr('src', response.picture)
			                                    .attr('data-value', response.picture_id)
			                                    .attr('data-check', response.picturec)
			                                    .addClass('active');
			                                	if (type === 'picture') {
				                            		tarImg.after('<a rel="delete-picture" data-value="' + response.picture_id + '" data-check="' + response.picturec + '"><img src="http://img.36tr.com/img/tags/close-tag.png"/></a>');;
				                            	}
			                                	$(that).html(text);
			                                    $(that).removeAttr('disabled');
			                                    $(that).patent("div.controls").parent().removeClass("error");
			                                }
			                            };
			                            if ( type === 'album' || type === 'card' || type === 'file' ){
			                            	$("input[name='"+type+"']").val(response.picture);
			                            }
	                        	}else{
	                        		 tarImg = arrType[type];
	                        		 $(tarImg).html('<a href=\''+response.file+'\' target="_blank">&nbsp;&nbsp;'+response.filename+'</a>');
	                        		 $(that).html(text);
		                             $(that).removeAttr('disabled');
	                        	}
		                    }else{
		                    	alert(response.error_text);
		                    	 $(that).html(text);
	                             $(that).removeAttr('disabled');
		                    }
	                    }
	                });
            },
        
        });
	});
}(jQuery, Grape));