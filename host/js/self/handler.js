(function ($, _) {
	jQuery(document).ready(function () {
        $.fn.extend({
        	//登陆框滑动函数
            itemSlideHandler: function () {
                var that = $(this) , 
                	active = that.find(".slide.active") , 
                	next = that.find(".slide.next") , 
                	handler = that.attr("rel") , 
                	horizontal = that.hasClass('horizontal') ? true : false;
                $(handler).bind('click', function () {
                    if (that.find('.error').length) {
                        that.find('.error').each(function () {
                            $(this).removeClass('has-error');
                        });
                        that.find('span.help-block').text('');
                    }
                    if (horizontal) {
                        if ($(next).hasClass('left')) {
                            $(active).css('position', 'absolute');
                            $(next).css('display', 'block');
                            $(active).animate({
                                'left': '-100%'
                            }, function () {
                                $(this).removeClass('active').addClass('next right');
                                next = $(this);
                                $(this).attr('style', '');
                            });
                            $(next).animate({
                                'left': '10px'
                            }, function () {
                                $(this).removeClass('next left').addClass('active');
                                active = $(this);
                                $(this).attr('style', '');
                            });
                        } else {
                            $(active).css('position', 'absolute');
                            $(next).css('display', 'block');
                            $(active).animate({
                                'left': '100%'
                            }, function () {
                                $(this).removeClass('active').addClass('next left');
                                next = $(this);
                                $(this).attr('style', '');
                            });
                            $(next).animate({
                                'left': '10px'
                            }, function () {
                                $(this).removeClass('next right').addClass('active');
                                active = $(this);
                                $(this).attr('style', '');
                            });
                        }
                    } else {
                        if ($(next).hasClass('up')) {
                            $(next).css('display', 'block');
                            $(active).css('position', 'absolute');
                            $(active).animate({
                                'top': '-100%'
                            }, function () {
                                $(this).removeClass('active').addClass('next down');
                                next = $(this);
                                $(this).attr('style', '');
                            });
                            $(next).animate({
                                'top': '0'
                            }, function () {
                                $(this).removeClass('next up').addClass('active');
                                active = $(this);
                                $(this).attr('style', '');
                            });
                        } else {
                            $(next).css('display', 'block');
                            $(active).css('position', 'absolute');
                            $(active).animate({
                                'top': '100%'
                            }, function () {
                                $(this).removeClass('active').addClass('next up');
                                next = $(this);
                                $(this).attr('style', '');
                            });
                            $(next).animate({
                                'top': '0'
                            }, function () {
                                $(this).removeClass('next down').addClass('active');
                                active = $(this);
                                $(this).attr('style', '');
                            });
                        }
                    }
                    var text = $(this).attr('text-toggle'), temp = $(this).text();
                    $(this).text(text).attr('text-toggle', temp);
                });
            },
          //打分函数
            ratingHandler: function () {
                if ($('body').attr('id') != '0') {
                    $(this).each(function () {
                    	var that = $(this) , 
                    		identify = $(this).attr('rel-target');

                    	that.delegate('.star', 'hover', function () {
                            var item = $(this).attr('rel') , 
                            	att = 'stars' + item + '0' , 
                            	status = that.attr('class');
                            $(this).parent().attr('class', 'stars ' + att);
                            that.unbind('mouseout');
                            that.one('mouseout', function () {
                            	$(this).attr('class', status);
                            });
                   		});

                    	that.delegate('.star', 'click', function () {
                            var item = $(this).attr('rel') , 
                            	att = 'stars' + item + '0' , 
                            	uid = $('body').attr('id') , 
                            	el = $(this) , 
                            	rating = $(this).attr('rel');
                            $.get('product/ajax_startupRating', {
								uid: uid,
								identify: identify,
								rating: rating
                            }, function (d, s) {
								var d = $.parseJSON(d);
								if (d['code'] == 200 && s == 'success') {
                                    $(el).parent().attr('class', 'stars rating ' + att);
                                    var status = that.attr('class');
                                    that.unbind('mouseout');
                                    that.one('mouseout', function () {
                                    	$(this).attr('class', status);
                                    });
								}
                            });
						});
                    });
                }
            },
            
          //input输入自动匹配
            typeaheadHandler: function (url, multi, callback) {
                var that = $(this) , 
                	action = url , 
                	reset = false , 
                	data = {};
                $(this).typeahead({
                    source: function (query, callback) {
                        var arr = [];
                        if (multi && multi === true) {
                            query = query.split(',');
                            if (query.length !== 0) {
                                if (query.length === 1) {
                                    reset = true;
                                } else {
                                    query = query[query.length - 1];
                                    this.query = query;
                                }
                            } else {
                                return false;
                            }
                        }
                        url = action + query;
                        $.get(url, function (d, s) {
                            data = $.parseJSON(d);
                            if (s == 'success' && data['code'] == '200') {
                                data = data.name;
                                for (var i in data) {
                                    arr[arr.length] = data[i].name;
                                }
                                callback.call(this, arr);
                            }
                        });
                        return true;
                    },
                    updater: function (item) {
                        var text = '';
                        if (multi && multi === true) {
                            if (reset === true) {
                                text = item;
                                that.val(text);
                                reset = false;
                            } else {
                                var pos = '-' + this.query.length;
                                text = that.val() === "" ? item : that.val().slice('0', pos).trim() + item;
                                that.val(text);
                            }
                        } else {
                            text = item;
                            that.val(text);
                        }
                        if (typeof callback === 'function') {
                            callback.call(that, data, item);
                        }
                        if (multi && multi === true) {
                            return text + ',';
                        } else {
                            return text;
                        }
                    }
                });
            },
            
        	//tabs导航切换函数
            navTabsHandler: function () {
                var active = $(this).find('.active a') , 
                	items = $(this).find('li a') , 
                	id;

                items.each(function () {
                    $(this).bind('click', function (e) {
                        e.preventDefault();
                        if (active) {
                            id = active.attr('href');
                            active.parent().removeClass('active');
                            $(id).hide();
                            $(this).parent().addClass('active');
                            id = $(this).attr('href');
                            $(id).show();
                            active = $(this);
                        } else {
                            $(this).parent().addClass('active');
                            id = $(this).attr('href');
                            $(id).show();
                            active = $(this);
                        }
                    });
                });
            } ,
          //auto complete tag
            textTagHandler:function(ul , lcls ){
                var id = $(this)  , 
                	closecls = 'close-tag' , 
                	closeel =  $( '.'+closecls ) ,
                	adcls = '.tagsbox > .supertags > a' ;
                function tagInsert(){
	                if (id){
	                	$(id).keydown(function(){
	                        document.onkeydown=function(event){
		                        var e = event || window.event || arguments.callee.caller.arguments[0];
		                        if(e.keyCode==13||e.keyCode==32 || e.keyCode == 188 ||e.which == 13  ||e.which==32 || e.which == 188 ){
		                            insertLi();
		                                return false;
		                        }
		                    };
	                	});
	                	id.bind('blur',function(){
	                		insertLi();
	                	});
	                	if ( $(adcls).length > 0 ){
	                		$(adcls).bind('click' , function (){
	                			var tag = $(this).attr('data-tag') ;
	                			id.val(tag);
	                			insertLi();
	                		});
	                	}
	                }
	                           
	                //focus的时候消除文字
	                id.bind('focus',function(){
	                    setTimeout(function(){id.val('');},10);
	                });
	                //鼠标滑过的时候把val写到textbox中
	                $('.ac_over').live('hover',function(){
	                    id.val($('.ac_over').text());
	                });
	                //autocomplete中keydown
	                $('.ac_over').keydown(function(event){
	                    id.val($('.ac_over').text());
	                    var e = event || window.event || arguments.callee.caller.arguments[0];
	                        if(e.keyCode==13||e.keyCode==32 || e.keyCode == 188 ||e.which == 13  ||e.which==32 || e.which == 188 ){
	                        	$('body').click();
	                        	id.focus();
	                        }
	                    });
                }
                function insertLi(){
                    var val=$.trim(id.val()).split(' ') || v ,
                    	li = '.' + lcls ,
                    	allval=$(li+' a') ,
                    	reg=/^[^\d]*/ig ,
                    	len=allval.length ,
                    	vallen=val.length;
                    function _operator(value , length){
                        if(length==0){
                            return 1;
                        }else{
                            for(var i=0;i<length;i++){
                                if(value==allval.eq(i).html()){
                                    return 0;
                                }
                            }
                        }
                        return 1;
                    }
                    for ( var i=0 ; i< vallen ; i ++ ){
                    	var oprl = len ;
                    	if( _operator(val[i] , len)==1 && val[i] != '' && val[i] !=null){
                    		$(ul).show().append('<li class="'+lcls+'"></li>');
     		            	$(li).last().append('<a></a><img src="http://img.36tr.com/img/tags/close-tag.png" class="'+closecls+'"/>');
     			         	$(li+' a').last().append(val[i]);
     			         	oprl += 1 ;
                    	}
                    } 
                    setTimeout(function(){id.val('');},10);
                }
                tagInsert();
//                closeel.live('click',function(){
//                    var index=$('.close-tag').index(this),
//                    dataval=$(this).siblings('a').html();
//                    closeel.eq(index).parent().fadeOut(function(){
//                    	closeel.eq(index).parent().empty();
//	                });
//	            });
                //end return
            },
        });
	});
}(jQuery, Grape));