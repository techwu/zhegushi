(function ($) {
    var Grape = (function () {
        //私有属性
        var url = window.location.href , 
            BASE_URL = url.substring(0, url.indexOf('/', 7) + 1) , 
            UPLOAD_ACTION = 'http://up.qbox.me/upload'  , 
            TIMELINE_MARGINHEIGHT = 0 
        ;
        return {
            extend       : function (o) {
                var extended = o.extended || function(){};
                $.extend(this, o);
                if (extended) extended(this);
            },
            BASE_URL     : BASE_URL,
            UPLOAD_ACTION: UPLOAD_ACTION ,
            TIMELINE_MARGINHEIGHT: TIMELINE_MARGINHEIGHT
        };
    }());
    Grape.ui = {};
    Grape.extend({
        setCookie     : function (c_name, value, expiredays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expiredays);
            document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
        },
        getCookie     : function (c_name) {
            if (document.cookie.length > 0) {
                var c_start = document.cookie.indexOf(c_name + "=");
                if (c_start != -1) {
                    c_start += c_name.length + 1;
                    var c_end = document.cookie.indexOf(";", c_start);
                    if (c_end == -1)
                        c_end = document.cookie.length;
                    return unescape(document.cookie.substring(c_start, c_end));
                }
            }
            return false;
        },
        delCookie     : function (c_name){
            var exp = new Date();
            exp.setTime (exp.getTime() - 1);
            document.cookie =c_name + "=" + c_name + "; expires="+ exp.toGMTString();
        },
        isMobileDevice: function (parameter) {
            return _.getCookie(parameter);
        }
    });

    window.Grape = window._ = Grape;

    (function () {
        var o = $({});
        //PubSub
        $.subscribe = function () {
            o.bind.apply(o, arguments);
        };
        $.subOnce = function () {
            o.one.apply(o, arguments);
        };
        $.unsubscribe = function () {
            o.unbind.apply(o, arguments);
        };
        $.publish = function () {
            o.trigger.apply(o, arguments);
        };
    }());

    //添加原型方法
    Function.prototype.method = function (name, func) {
        if (!this.prototype[name]) {
            this.prototype[name] = func;
        }
        return this;
    };

    //移除string首尾空白
    if (typeof String.prototype.trim !== 'function') {
        String.method('trim', function () {
            return this.replace(/^\s+|\s+$/g, '');
        });
    }

    if (typeof String.prototype.jsonParse !== 'function') {
	    String.method('jsonParse', function () {
	    	if (typeof (this) == String){ 
	    		return this.match(/.*[^:\\]"}/).toString(); 
	    	}else {
	    		return this.toString().match(/.*[^:\\]"}/).toString();
	    	}
	    });
    }
//    window.alert = function (str) {
//    	if(!$(".alert-bg").length){
//            $('<div class="modal modal-small" id="alert-modal">  <div class="modal-header mouse-move">    <span class="icon-logo"></span>  </div>  <div class="modal-body">    <p id="alert-text" class="center">    </p>    <p class="center">      <button type="button" class="btn btn-primary btn-small">确定</button>    </p>  </div></div>').appendTo('body');
//       	    $('#alert-text').text(str);
//
//            $('<div class="alert-bg" style="width:100%;height:100%;background:#ffffff;opacity:0.7;z-index:1000;position:fixed;left:0;top:0;"></div>').appendTo('body');
//            $('#alert-modal').css('display', 'block').animate({'top': '50px'});//.modalDragHandler();
//            
//	    $("#alert-modal").find("button").one('click', function () {
//	        $('#alert-modal').animate({
//	            'top': '-100%'
//	        }, function () {
//	            $(this).remove();
//	            $('.alert-bg').remove();
//	        });
//	    });
//	}
//	return true;
//    };

//    window.confirm = function (str, submit, cancel) {
//    	if ($(".alert-bg").length === 0) {
//	    var that = this;
//	    this.submit = submit === undefined ? function () {
//	        return true;
//	    } : submit;
//	    this.cancel = cancel === undefined ? function () {
//	        return false;
//	    } : cancel;
//	    $('<div class="modal modal-small" id="alert-modal"><div class="modal-header mouse-move"><span class="icon-logo"></span></div><div class="modal-body"><p id="alert-text" class="center"></p><p class="center"><button type="button" class="btn btn-primary confirm btn-small">确定</button> <button type="button" class="btn btn-small">取消</button>   </p>  </div></div>').appendTo('body');
//	    $('#alert-text').text(str);
//	    $('<div class="alert-bg" style="width:100%;height:100%;background:#ffffff;opacity:0.7;z-index:1000;position:fixed;left:0;top:0;"></div>').appendTo('body');
//	    $('#alert-modal').css('display', 'block').animate({'top': '50px'});//.modalDragHandler();
//	    $("#alert-modal").find('button').each(function () {
//	        $(this).one('click', function () {
//	            $('#alert-modal').animate({
//	                'top': '-100%'
//	            }, function () {
//	                $(this).remove();
//	                $('.alert-bg').remove();
//	            });
//	            if ($(this).hasClass('confirm')) {
//	                that.submit();
//	            } else {
//	                that.cancel();
//	            }
//	        });
//	    });
//	}
//    };
}(jQuery));
