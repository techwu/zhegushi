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

        // form check
        $("[rel='form-check']").formInputHandler();
//        signin page
        $("form[rel='form-login']").formInputHandler(function () {
            var that = $(this) , 
            	email = that.find("[name='email']").val().trim() ,
            	pwd = that.find("[name='password']").val();
            if( pwd.length < 6 ){
            	$($(that).find("[name='password']").parents('.form-group')[0]).removeClass('has-success').addClass('has-error');
            	if($(that).find('span.help-block').html() == ''){
            		$(that).find('span.help-block').html('密码必须大于6位！').addClass('alert');	
            	}
            }
            return {
                method: 'post',
                data: {
                    email: email ,
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
                    window.location = _.BASE_URL + back_url ;
                }
            };
        });
        
        $("[rel='operateStory']").on('click' , function(){
        	var that = $(this) ,
	    		operate = that.attr('data-operate') ,
	    		story_id = that.attr('data-value') ;
        	console.log(operate)
        	if ( confirm('确定执行该条指令吗？')){
		    	$.ajax({
		            url: _.BASE_URL +'story/ajax_operateStory', 
		            type: 'post',
		            data: {
		            	operate  : operate  ,
		            	story_id  : story_id
		            },
		            success: function(d) {
		                window.location.reload();
		            }
		        });
        	}
        });

        $("#user-menu").dropdown();
        $("#tl-menu").dropdown('down');
        
        $(".datepicker").each(function () {
            $(this).datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });
        App.init();
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
        
        $("form[rel='form-story-edit']").formInputHandler(function () {
            var that = $(this) ,
            	content = that.find("[name='content']").val() , 
            	title = that.find("[name='title']").val() ,
	        	story_tags =  that.find('ul.tags-area li.story-tag a') ,
	        	id = that.attr('data-value') ,
	        	tags = '';
            if ( story_tags.length > 0 ){
	            for ( index = 0 ; index < story_tags.length ; index ++ ){
	            	tags += " " + $(story_tags[index]).html() ;
	            }
            }
	        return {
	            data: {
	            	id:id ,
	            	t:title ,
	                c: content,
	                tags: tags
	            },
	            help: that.find("span.help-block"),
	            internaltxt: '修改失败',
	            callback: function (data) {
	            	setTimeout(function () {
                        window.location.href= _.BASE_URL + 'story/page/'+id ;
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
        $('li.item > ul.row .toggle').toggle( function(){
        	$(this).parent().next('div').animate({height:'toggle',opactity:'toggle'},'slow');
		},function(){
			$(this).parent().next("div").animate({height:'toggle',opactity:'toggle'},'slow');
		});
        
        $("li.item > ul.row.list-title input.group-checkable[type='checkbox']").on('change' , function(){
        	var that = $(this) , 
        		checked = that.is(":checked");
        		items = that.attr('data-set');
        	$(items).each(function(){
        		if (checked){
        			$(this).change().attr("checked", true);
        		}else{
        			$(this).change().attr("checked", false);
        		}
        	});
        });
        
        $(".checker span input[type='checkbox']").on('change' , function(){
        	var that = $(this) ,
        		span= that.parent();
        	console.log(1);
        	if ( span.hasClass('checked') ){
        		span.removeClass('checked');
        	}else{
        		span.addClass('checked');
        	}
        });
        
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