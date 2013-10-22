(function ($, _) {
	jQuery(document).ready(function () {
        $.fn.extend({
        	modal: function (option) {
                this.sliding = false;
                var that = this , 
                	modal = $({});
                that.onshow = option !== undefined ? (option.onshow || function () {
                    return true;
                }) : function () {
                    return true;
                };
                that.onclose = option !== undefined ? (option.onclose || function () {
                    return true;
                }) : function () {
                    return true;
                };
                function modalFadeIn(o) {
                    if ((!$('.modal-active').length || !that.sliding || !$(o.el).attr('style')) && that.onshow.call(that)) {
                        that.sliding = true;
                        var modal_bg = $('<div class="modal-bg"></div>'),
                            active = modal.find('.active').length ? modal.find('.active') : '' , 
                            reminder = '' ;
                        modal = $($(o.el).attr('modal-target'));
                        if( $(o.el).attr('modal-target') == '#login-register-modal'){
                        	reminder = reminder || $(o.el).attr('modal-reminder');
                        }
                        modal.find('.error').each(function () {
                            $(this).removeClass('has-error');
                        });
                        modal.find('.success').each(function () {
                            $(this).removeClass('has-success');
                        });
                        modal.one('modal_close', function (e, callback) {
                            modalFadeOut(callback);
                        });
                        modal.find("input[type='text']").each(function () {
                            if (!$(this).attr('value')) {
                                $(this).val('');
                            }
                        });
                        modal.find("input[type='password']").val('');
                        modal.find("textarea").val('');
                        modal.find('span.help-block').text('');
                        modal.find('span.help-inline').text('');
                        modal_bg.appendTo('body');
                        modal.css({
                            'display': 'block',
                        });
                        modal.animate({
                            'top': '50px'
                        }, '1000', function () {
                            if (active) {
                                var pos = active.offset().top - 300;
                                modal.find('.modal-body').animate({'scrollTop': pos}, 0);
                            }
                            if( reminder ){
                            	modal.find("p[rel='help-reminder']").html(reminder);
                            }
                            $('.modal-close').one('click', function () {
                                modal.trigger('modal_close', that.onclose);
                            });
                            $('.modal-bg').one('click', function () {
                                modal.trigger('modal_close', that.onclose);
                            });
                            $(this).addClass('modal-active');
                        });
                    }
                };
                function modalFadeOut(callback) {
                    if (callback.call(that)) {
                        if (modal.attr('id') == 'img-modal') {
                            //$(".jcrop-holder").remove();
                            $("#source-img > img").unbind().remove();
                            $("#source-img > input").before('<img src="http://img.36kr.net/css/image/defaults/160160.png">');
                            $('#img-modal').attr('type','');
                            modal.find("[rel='img-select-aciton']").unbind();
                        }
                        $('.modal-active').animate({
                            'top': '-1000px'
                        }, '1000', function () {
                            $(this).removeClass('modal-active');
                            $(this).attr('style', '');
                            $('.modal-body').attr('style', '');
                            $('.drag-icon').attr('style', '');
                            $(".modal-bg").remove();
                            that.sliding = false;
                            $('body > div').each(function () {
                                if ($(this).css('z-index') == '2147483583') {
                                    $(this).remove();
                                }
                            });
                        });
                    }
                };
                $(this).bind('click', function () {
                    modalFadeIn({
                        el: $(this)
                    });
                });
            } , 
            
          //处理modal缩放
            modalZoomHandler: function () {
                var that = $(this) , 
                	zoomer = that.find('.icon-zoom') , 
                	modalBody = that.find('.modal-body') , 
                	first = true;
                zoomer.bind('mousedown', function (e) {
                    e.preventDefault();
                    if (first) {
                        that.minW = parseInt(that.css('width'));
                        that.minH = parseInt(that.css('height'));
                        that.bodyMinH = parseInt(modalBody.css('height'));
                        first = false;
                    }
                    var _x = parseInt(e.clientX) , 
                    	_y = parseInt(e.clientY) , 
                    	modalW = parseInt(that.css('width')) , 
                    	modalH = parseInt(that.css('height')) , 
                    	modalBodyH = parseInt(modalBody.css('height'));
                    that.find('.modal-body').css('max-height', 'none');
                    $(document).bind('mousemove', function (e) {
                        e.preventDefault();
                        var dx = e.clientX - _x , 
                        	dy = e.clientY - _y , 
                        	w = modalW + dx , 
                        	h = modalH + dy , 
                        	bh = modalBodyH + dy;
                        w = w >= that.minW ? w + 'px' : that.minW + 'px';
                        h = h >= that.minH ? h + 'px' : that.minH + 'px';
                        bh = bh >= that.bodyMinH ? bh + 'px' : that.bodyMinH + 'px';
                        that.css({
                            'width': w,
                            'height': h
                        });
                        modalBody.css('height', bh);
                    });
                    $(document).bind('mouseup', function () {
                        $(this).unbind('mousemove');
                        $(this).unbind('mouseup');
                    });
                    $(window).bind('blur', function () {
                        $(document).unbind('mousemove');
                        $(document).unbind('mouseup');
                    });
                });
            },
            //处理modal拖动
            modalDragHandler: function () {
                var that = $(this) , 
                	mover = that.find('.mouse-move');
                mover.bind('mousedown', function (e) {
                    e.preventDefault();
                    var _x = e.clientX - parseInt(that.css('left'))
                    , _y = e.clientY - parseInt(that.css('top'));
                    $(document).bind('mousemove', function (e) {
                        var dx = e.clientX - _x
                        , dy = e.clientY - _y;
                        that.css({
                            'left': dx,
                            'top': dy
                        });
                    });
                    $(document).bind('mouseup', function () {
                        $(this).unbind('mousemove');
                        $(this).unbind('mouseup');
                    });
                    $(window).bind('blur', function () {
                        $(document).unbind('mousemove');
                        $(this).unbind('blur');
                    });
                });
            }
        });
	});
}(jQuery, Grape));
            