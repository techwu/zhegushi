(function ($, _) {
    jQuery(document).ready(function () {
//        $("[move]").each(function () {
//            $(this).modalDragHandler();
//        });
//
//        $("[zoom]").each(function () {
//            $(this).modalZoomHandler();
//        });
        $("[rel='tooltip']").tooltip();
        $("[popover='tooltip']").tooltip();
//      !-[1,] 识别IE
        if (!-[1, ] || (typeof window.ScriptEngineMinorVersion == 'function' && window.ScriptEngineMinorVersion() == 0)) {
            $("[placeholder]").each(function () {
                $(this).placeHolder();
            });
        }
        $('a.artZoom').artZoom();
        $('img.lazy').lazyload({
            placeholder:'http://img.36tr.com/img/circle/changebg.png',
            effect : "fadeIn",
            effectspeed : 1000
        });
//        slider test
//        $(".item-slide").itemSlideHandler();
        $("[rel='input-linked']").delegate('input, textarea', 'blur', function () {
            var parent = $(this).parents("[rel='input-linked']");
            if (!parent.hasClass('check')) {
                if ($(this).val() != '') {
                    parent.addClass('check');
                }
            }
        });

        $("[confirm]").each(function () {
            $(this).bind("click", function (e) {
                e.preventDefault();
                var text = $(this).attr('confirm') || "你确定要提交吗？", href = $(this).attr('href');
                confirm(text, function () {
                    window.location.href = href;
                });
            });
        });

        $("[rel='nav-tabs']").each(function () {
            $(this).navTabsHandler();
        });

        $('.carousel').carousel();
        $('.bubble').bubble();
        $("[rel='modal']").modal();
        $("[rel='reviews-modal']").modal();
        $(".icon-chevron-down").packToggle();
        
        $("[rel='feedback-model']").modal({
            onclose: function () {
                var tar = $('#fb').attr('modal-target');
                $(tar).find('.active').each(function () {
                    $(this).removeClass('active');
                });
                return $(this);
            }
        });

        $('[modal-target="#img-modal"]').each(function () {
            var type = $(this).attr('rel').slice(12);
            $(this).fileUpload({
                name: type + '[]',
                type: type
            });
        });

        $("#user-menu").on('click' ,function(){
        	if ($(this).find('i').hasClass('glyphicon-collapse-down')){
        		$(this).find('i').attr('class' , 'glyphicon glyphicon-collapse-up');
        	}else{
        		$(this).find('i').attr('class' , 'glyphicon glyphicon-collapse-down');
        	}
        });
        $('#user-message-envelope').on('click' , function(){
        	var el = $('#user-message-box') ,
        		uid = $('body').attr('id') ;
	    	$.ajax({
	            url: 'message/ajax_getUserUnreadMessage', 
	            type: 'post',
	            data: {uid:uid}, 
	            success: function(d) {
	                var status = $.parseJSON(d).code , 
	                	error =  $.parseJSON(d).error ,
	                	result =  $.parseJSON(d).result ;
	                if (status == '200') {
	                	el.html(result.content);
	                	$('#user-message-badge').html(result.message_num)
	                } else{
	                	el.html('<li><p>'+error+'</p></li>');
	                }
	            }
	        });
        });
//        $('.bar-action-share').dropdown();
//        $("#tl-menu").dropdown('down');
        
        // form check
        $("[rel='form-check']").formInputHandler();
//        signin page
        $("form[rel='form-login']").formInputHandler(function () {
            var that = $(this) , 
            	email = that.find("[name='email']").val().trim() ,
            	pwd = that.find("[name='pwd']").val();
            if( email.length == 0 || !_.ui.isEmail(email)){
            	$($(that).find("[name='email']").parents('.form-group')[0]).removeClass('has-success').addClass('has-error');
            	$(that).find('span.help-block').html('请填写正确的邮箱！').addClass('alert');
            }
            
            if( pwd.length < 6 ){
            	$($(that).find("[name='pwd']").parents('.form-group')[0]).removeClass('has-success').addClass('has-error');
            	if($(that).find('span.help-block').html() == ''){
            		$(that).find('span.help-block').html('密码必须大于6位！').addClass('alert');	
            	}
            }
            return {
                method: 'post',
                data: {
                    email: that.find("[name='email']").val().trim(),
                    pwd: pwd ,
                    set_cookie: that.find("[name='set-cookie']").attr("checked") !== undefined
                },
                help: that.find("span.help-block"),
                errortxt: '用户名或密码错误',
                badrequesttxt: '用户名或密码错误',
                internaltxt: '用户名或密码错误',
                disable: true,
                text: '欢迎回来！',
                callback: function () {
                    setTimeout(function () {
                        $('.modal-bg').click();
                    }, 1000);
                    var back_url = _.getCookie('COOKIE_CALLBACK_URL');
                    if (!back_url){
                    	back_url = '';
                    }
                    console.log(back_url);
                    window.location = _.BASE_URL + back_url ;
                }
            };
        });
//        signup page
        $("form[rel='form-signup']").formInputHandler(function () {
            var email = $(this).find("[name='email']").val() , 
            	that = $(this) , 
            	pwdEls = $(this).find('[type="pwd"]') , 
            	isPwdNull = false;
		    pwdEls.each(function () {
				if (!$(this).val() && !isPwdNull) {
				    isPwdNull = true;
				}
		    });
	    
		    if(email.length == 0 ){
		    	$(that).find("span.help-block").text('请输入正确的邮箱！').addClass('alert');
				return false;
		    }
		    if (isPwdNull) {
				$(that).find("span.help-block").text('密码不能为空').addClass('alert');
				return false;
		    }
            return {
                data: {
                	username : that.find("[name='username']").val().trim(),
                    email: that.find("[name='email']").val().trim(),
                    pwd: that.find("[name='pwd']").val(),
                    repwd: that.find("[name='repwd']").val()
                },
                help: that.find("span.help-block"),
                disable: true,
                text: '注册成功！',
                internaltxt: "该邮箱已经被注册",
                errortxt: '该邮箱已经被注册',
                badrequesttxt: '请填写正确的密码',
                callback: function () {
                    setTimeout(function () {
                        $('.modal-bg').click();
                        window.location.reload();
                    }, 3000);
                    var back_url = _.getCookie('COOKIE_CALLBACK_URL');
                    if (!back_url){
                    	back_url = '';
                    }
                    window.location = _.BASE_URL + back_url ;
                }
            };
        });
//        story page
        $("form[rel='form-story-add']").formInputHandler(function () {
            var content = $(this).find("[name='content']").val() ,
            	title = $(this).find("[name='title']").val() ,
	        	that = $(this) , 
	        	user_id = $('body').attr('id') ,
	        	story_tags =  that.find('ul.tags-area li.story-tag a') , 
	        	tags = '';
            if ( story_tags.length > 0 ){
	            for ( var index = 0 ; index < story_tags.length ; index ++ ){
	            	tags += " " + $(story_tags[index]).html() ;
	            }
            }
	        return {
	            data: {
	            	t:title ,
	                c: content,
	                tags: tags,
	                pic:$('#logoUpload').find('img').attr('data-check').trim() , 
	                uid: user_id
	            },
	            help: that.find("span.help-block"),
	            internaltxt: '分享故事失败',
	            callback: function (data) {
	            	setTimeout(function () {
                        $('.modal-bg').click();
                        window.location.href= _.BASE_URL + 'me'
                    }, 1000);
	            }
	        };
		});
        var arr = $('.tags-autocomplete').textTagHandler('.tags-area','story-tag' );
        
        $('.close-tag').live('click',function(){
            var index=$('.close-tag').index(this),
                dataval=$(this).siblings('a').html();
            $('.close-tag').eq(index).parent().fadeOut(function(){
                $('.close-tag').eq(index).parent().empty();
            });
        });
        //bar-action
        $("[rel='bar-action']").bind('click' , function(){
        	var that = $(this) ,
        		uid = $('body').attr('id') ,
        		action = that.attr('data-action') ,
        		sid = that.parents('.item').attr('data-value') ;
        	$.ajax({
                url: 'story/ajax_addUserActionStory', 
                type: 'post',
                data: {uid:uid , sid:sid , action:action}, 
                success: function(d) {
                    var status = $.parseJSON(d).code , 
                    	error =  $.parseJSON(d).error ;
                    if (status == '200') {
                    	var result = $.parseJSON(d).result ,
                    		up = result['up']  , 
                    		down = result['down'] , 
                    		comments = result['comments'];
                    	that.parent().parent().find("[rel='bar-action'][data-action='10']").html('<i class="glyphicon glyphicon-thumbs-up inline"></i>' + up);
	                	that.parent().parent().find("[rel='bar-action'][data-action='11']").html('<i class="glyphicon glyphicon-thumbs-down inline"></i>' + down);
	                	that.parent().parent().find("[rel='bar-action'][data-action='12']").html('<i class="glyphicon glyphicon-comment inline"></i>' + comments);
                    } else{
                    	alert(error);
                    }
                }
            });
        });
        
        $("[rel='bar-action-comment']").bind('click' , function (){
        	var that = $(this) ,
        		sid = that.parents('.item').attr('data-value') ;
        	$.ajax({
                url: 'story/ajax_getComments', 
                type: 'post',
                data: {sid:sid}, 
                success: function(d) {
                    var status = $.parseJSON(d).code , 
                    	error =  $.parseJSON(d).error ,
                    	result = $.parseJSON(d).result;
                    if (status == '200') {
                    	var comments = result['comments'] ;
                    	that.parents('.item').find("[rel='comment-items']").html( comments );
                    	var comment_el = that.parents('.item').find('.comments') ;
                    	if ( comment_el.hasClass('hide') ){
                    		comment_el.removeClass('hide');
                    	}else{
                    		comment_el.addClass('hide');
                    	}
                    } else{
                    	alert(error);
                    }
                }
            });
        });
        
        $("[rel='form-story-add-comment']").each(function(){
	        $(this).formInputHandler(function () {
	                var that = $(this) , 
	                	comment = that.find("[name='comment']").val() ,
	                	sid = that.parents('.item').attr('data-value') ;
	    	        return {
	    	            data: {
	    	            	comment: comment,
	    	            	sid: sid,
	    	            },
	    	            help: that.find("span.help-block"),
	    	            internaltxt: '评论失败',
	    	            callback: function (data) {
	    	            	var status = data.code , 
	                    		error =  data.error ,
	    	            		result = data.result ;
	    	            	if ( status == '200' ){
	    	            		var comments = result['comments'] ,
	    	            			comment_num = result['comment_num'];
	    	            		that.parents('.item').find("[rel='bar-action-comment'][data-action='12']").html('<i class="glyphicon glyphicon-comment inline"></i>' + comment_num);
		                    	that.parents('.item').find("[rel='comment-items']").html( comments );
		                    	that.find("[name='comment']").val('');
	    	            	}else{
	    	            		alert(error);
	    	            	}
	    	            	
	    	            }
	    	        };
	    	});
        });
        
        $("[rel='common-reply']").live('click' , function(){
        	var uid = $('body').attr('id') , that=$(this) ,
        		form = that.parents('.comments').find("form[rel='form-story-add-comment']");
        	if ( uid > 0 && form.length > 0){
        		var input = form.find("[name='comment']") ;
        		input.val(input.val()+'@'+that.attr('data-value')+' ').position(input.val().length );
        	}else{
        		alert('请先登陆');
        	}
        });
        
        $("[rel='close-comments']").live('click' , function(){
        	var that = $(this) ;
        	that.parents('.item').find('.comments').hide();
        });
        
        $(".datepicker").each(function () {
            $(this).datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });
        
        // timeline datepicke compare date
        $("input.datepicker[name*='date']").on("change", function () {
            var dateBegin = $("[name='date-begin']").val() , 
            	dateEnd = $("[name='date-end']").val() , 
            	secondBegin = Date.parse(dateBegin.replace("-", "/")) , 
            	secondEnd = Date.parse(dateEnd.replace("-", "/"));
            $(this).parent().find(".help-inline").removeClass("show");
            if (secondBegin <= secondEnd) {
                window.location.href = _.BASE_URL + "timeline/list?begin=" + dateBegin + "&end=" + dateEnd;
            } else {
                $(this).parent().find(".help-inline").addClass("show");
            }
        });
        
        $("input.datepicker[name='date-end']").on("click", function () {
            $("input.datepicker[name='date-begin']").datepicker("hide");
        });

        $("input.datepicker[name='date-begin']").on("click", function () {
            $("input.datepicker[name='date-end']").datepicker("hide");
        });

        $(".ajax, [disabled]").live('click', function (e) {
            e.preventDefault();
            return false;
        });
        $(".location-select").live('change', function () {
            var url = 'location/ajax_loadCities/' + $("#provinceid").val();
            $("#city").load(url);
        });
        
        $('ul.nav > li.nav-bubble-more').on('click',function(e){
        	e.preventDefault();
        	var slide=$('.nav-slide') ;
        	slide.toggle();
        });
        
        $("#kr-plus-timeline ul.kr-timeline-list li").last().addClass("last-child");
        
        $(".pagination-centered ul.pagination form").bind('submit', function (e) {
            e.preventDefault();
        	var that = $(this) ,
        		page = that.find("input[type='text']").val() -1 , 
        		base_url = that.find("input[type='text']").attr("data-value"),
        		exten = that.find("input[type='text']").attr("data-extend");
        	window.location.href = _.BASE_URL + base_url + page + exten;
        });
    });
}(jQuery, Grape));