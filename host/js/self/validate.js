(function ($, _) {
	jQuery(document).ready(function () {
        $.fn.extend({
			formInputHandler : function (process) {
		        var that = $(this) ,
		          user_id = $('body').attr('data-value') , 
		          inputElements = that.find("[rel='input-text']") ? that.find("[rel='input-text']") : null , 
		          inputPasswords = that.find("[type='password']") ? that.find("[type='password']") : null , 
		          emailElements = that.find("[rel='input-email']") ? that.find("[rel='input-email']") : null , 
		          urlElements = that.find("[rel='input-url']") ? that.find("[rel='input-url']") : null , 
		          itunesUrlElements = that.find("[rel='input-itunesurl']") ? that.find("[rel='input-itunesurl']") : null ,
		          googlePlayUrlElements = that.find("[rel='input-googleplayurl']") ? that.find("[rel='input-googleplayurl']") : null ,
		          weiboUrlElements = that.find("[rel='input-weibo']") ? that.find("[rel='input-weibo']") : null ,
		          textElements = that.find("[rel='text-area']") ? that.find("[rel='text-area']") : null , 
		          pillsElements = that.find("[rel='select-pills']") ? that.find("[rel='select-pills']") : null , 
		          phoneElements = that.find("[rel='input-phone']") ? that.find("[rel='input-phone']") : null , 
		          helpBlock = that.hasClass("form-col") ? that.find("span.help-block") : null , 
		          btnSubmit = that.find("button[type='submit']").last() , 
		          selectElements = that.find("[rel='select-check']") ? that.find("[rel='select-check']") : null ,
		          linkedElements = that.find("[rel='input-linked'] .linked-on") ? that.find("[rel='input-linked'] .linked-on") : null , 
		          numberElements = that.find("[rel='input-number']") ? that.find("[rel='input-number']") : null , 
		          checkListElements = that.find(".check-exist") ? that.find(".check-exist") : null ,
		          englishElements = that.find("[rel='input-english']") ? that.find("[rel='input-english']") : null,
		          qqElements = that.find("[rel='qq']") ? that.find("[rel='qq']") : null;
		          that.hasErrorEl = false;
		
		        //-------------------------
		        //form check function start
		        //-------------------------
		        //将错误信息进行提示
		        function getHelpAlert(el , text ){
		//          var that = $(el);
		          	getHelpInline(el).html('').removeClass('alert').removeClass('alert-error');
		          	if (text) {
		                getHelpInline(el).html(text).addClass('alert').addClass('alert-error');
		            } else {
		                getHelpInline(el).html('').removeClass('alert').removeClass('alert-error');
		            }
		        }
		      //获取提示el
		        function getHelpInline(el) {
		            if (helpBlock) {
		                return $(helpBlock);
		            } else if ($(el).next('span.help-inline').length && !$(el).next('span.add-on').length) {
		                return $($(el).next('span.help-inline')[0]);
		            } else if ($(el).next('span.help-inline').length && $(el).next('span.add-on')) {
		                return $($(el).parents('.controls').find('span.help-inline')[0]);
		            } else if ($(el).parent().find('span.help-inline')) {
		                return $($(el).parent().find('span.help-inline')[0]);
		            } else {
		                return false;
		            }
		        }
		//标记error元素
		        function hasErrorElHandler(el) {
		//            if (el) {
		//                that.hasErrorEl = $(el);
		//            } else {
		                that.hasErrorEl = false;
		//            }
		        }
		
		//pills判断，默认最多选取3个 
		        function pillsActiveLimit(el) {
		            $(el).on('count', function () {
		                var count = $(this).attr('data-value'), 
		                	max = typeof(count) == 'undefined' || count == 0 || count == '' ? 3 : $(this).attr('data-value');
		                if ($(this).find('li.active').length == max) {
		                    $(this).find("li:not(.active)").addClass('disabled');
		                } else {
		                    $(this).find("li:not(.active)").removeClass('disabled');
		                }
		            });
		            $(el).trigger('count');
		            $(el).delegate('li > a', 'click', function () {
		                $($(this).parents('.form-group')[0]).removeClass('has-error').find('span.help-block').text('');
		                if ($(this).parents('li').hasClass('disabled')) {
		                    return false;
		                } else {
		                    if ($(this).parents('li').hasClass('active')) {
		                        $(this).parents('li').removeClass('active');
		                        $(el).trigger('count');
		                        return $(this);
		                    } else {
		                        $(this).parents('li').addClass('active');
		                        $(el).trigger('count');
		                        return $(this);
		                    }
		                }
		            });
		        }
		//监听keyup事件，初始化input
		        function formKeyPressHandler(el, text) {
		            $(el).on('keyup', function () {
		                that.hasErrorEl = false;
		                getHelpAlert($(this) , text);
		                $($(this).parents('.form-group')[0]).removeClass("has-error").removeClass('has-success');
		            });
		        }
		//检查input是否为空
		        function inputCheckNull(el) {
		            if ($(el).attr('type') !== 'file') {
		                return $(el).val().trim() == "";
		            } else {
		                return false;
		            }
		        }
		//更改提示文本
		//@param el jQueryElement input元素
		//@param iserror bool 是否填写有误
		//@param text string 提示文本
		        function formInputText(el, iserror, text) {
		        	getHelpAlert(el , text  );
		            if (iserror) {
		                $($(el).parents('.form-group')[0]).removeClass('has-success').addClass('has-error');
		            } else {
		                $($(el).parents('.form-group')[0]).removeClass('has-error').addClass('has-success');
		            }
		        }
		//初始化表单
		//@param el jQueryElement input元素
		//@param text string 提示文本 可选
		        function formInputReset(el, text) {
		        	$($(el).parents('.form-group')[0]).removeClass('has-error').removeClass('has-success');
		        	return true;
		        }
		//监听input的change事件，初始化表单
		//@param el jQueryElement input元素
		//@param text string 提示文本 可选
		        function formChangeReset(el, text) {
		            $(el).on('change', function () {
		              getHelpAlert($(this) , text);
		                $($(this).parents('.form-group')[0]).removeClass('has-error').removeClass('has-success');
		                formInputText($(this), false);
		            });
		        }
		//url检查, 检查input输入的url格式是否合法
		//@param el jQueryElement 
		        function formUrlCheck(el){
		            if (_.ui.isUrl($(el).val())) {
		                formInputText($(el), false);
		                hasErrorElHandler();
		            } else if (inputCheckNull($(el)) || ($(el).val() == $(el).attr('placeholder')) ) {
		            	if ( el.hasClass('on') && !el.hasClass('is-null') ){
		            		formInputText($(el), true, '网址信息不能为空');
		                    hasErrorElHandler($(el));
		            	}else{
		            		formInputText($(el), false);
		            	}
		            } else {
		                formInputText($(el), true, '网址格式不正确');
		                hasErrorElHandler($(el));
		            }
		        }
		        function formUrlBlurCheck(el) {
		        	formInputReset(el);
		            $(el).on('blur', function () {
		                formUrlCheck($(this));
		            });
		        }
		
		        function formItunesUrlBlurCheck(el){
		            formInputReset(el);
		            $(el).bind('blur', function () {
		                formItunesUrlCheck($(this));
		            });
		        }
		        function formItunesUrlCheck(el){
		            if (_.ui.isItunesUrl($(el).val())) {
		                formInputText($(el), false);
		                hasErrorElHandler();
		            } else if (inputCheckNull($(el)) || ($(el).val() == $(el).attr('placeholder'))) {
		            	if ( el.hasClass('on') && !el.hasClass('is-null') ){
		            		formInputText($(el), true, '网址信息不能为空');
		                    hasErrorElHandler($(el));
		            	}else{
		            		formInputText($(el), false);
		            	}
		            } else {
		                formInputText($(el), true, '网址格式不正确');
		                hasErrorElHandler($(el));
		            }
		        }
		        function formGooglePlayUrlCheck(el){
		            if (_.ui.isGooglePlayUrl($(el).val())) {
		                formInputText($(el), false);
		                hasErrorElHandler();
		            } else if (inputCheckNull($(el)) || ($(el).val() == $(el).attr('placeholder'))) {
		            	if ( el.hasClass('on') && !el.hasClass('is-null') ){
		            		formInputText($(el), true, '网址信息不能为空');
		                    hasErrorElHandler($(el));
		            	}else{
		            		formInputText($(el), false);
		            	}
		            } else {
		                formInputText($(el), true, '网址格式不正确');
		                hasErrorElHandler($(el));
		            }
		        }
		        function formGooglePlayUrlBlurCheck(el){
		        	formInputReset(el);
		            $(el).bind('blur', function () {
		                formGooglePlayUrlCheck($(this));
		            });
		        }
		        function formWeiboUrlCheck(el){
		            if (_.ui.isWeiboUrl($(el).val())) {
		                formInputText($(el), false);
		                hasErrorElHandler();
		            } else if (inputCheckNull($(el)) || ($(el).val() == $(el).attr('placeholder'))) {
		            	if ( el.hasClass('on') && !el.hasClass('is-null') ){
		            		formInputText($(el), true, '网址信息不能为空');
		                    hasErrorElHandler($(el));
		            	}else{
		            		formInputText($(el), false);
		            	}
		            } else {
		            	formInputText($(el), true, '微博网址格式不正确');
		            	hasErrorElHandler($(el));
		            }
		        }
		        function formWeiboUrlBlurCheck(el){
		            formInputReset(el);
		            $(el).bind('blur', function () {
		                formWeiboUrlCheck($(this));
		            });
		        }
		//电话号码检查，检查input输入的电话号码是否合法
		//@param el jQueryElement 
		        function formPhoneBlurCheck(el) {
		            formInputReset(el);
		            $(el).on('blur', function () {
		                formPhoneCheck($(this));
		            });
		        }
		        function formPhoneCheck(el) {
		            if (inputCheckNull($(el)) || ($(el).val() == $(el).attr('placeholder'))) {
		            	if ( el.hasClass('on') && !el.hasClass('is-null') ){
		            		formInputText($(el), true, '不能为空');
		            	}else{
		            		formInputText($(el), false);
		            	}
		            } //else {
		                //formInputText($(this), false);
		            //}
		            else if (!_.ui.isPhoneNumber($(el).val()) && $(el).val().trim()) {
		                formInputText($(el), true, '电话号码不正确！');
		            }else{
		               formInputText($(el), false); 
		            }
		        }
		//英文检查 
		        function formEnglishCheck(el){
		            if (_.ui.isEnglish($(el).val())) {
		                formInputText($(el), false);
		            }else if (inputCheckNull($(el)) || ($(el).val() == $(el).attr('placeholder'))){
		            	if ( el.hasClass('on') && !el.hasClass('is-null') ){
		            		formInputText($(el), true, '只能使用长度大于五位的英文字符！');
		            	}else{
		            		formInputText($(el), false);
		            	}
		            }else {
		                formInputText($(el), true, '只能使用长度大于五位的英文字符！');
		            }
		        }
		        function formEnglishBlurCheck(el){
		            formInputReset(el);
		            $(el).on('blur', function () {
		                formEnglishCheck($(this));
		            });
		        }
		        function formQQCheck(el){
		            if (_.ui.isQQ($(el).val())) {
		                formInputText($(el), false);
		            }else if (inputCheckNull($(el)) || ($(el).val() == $(el).attr('placeholder'))){
		            	if ( el.hasClass('on') && !el.hasClass('is-null') ){
		            		formInputText($(el), true, '格式不正确,应为5~11位数字！');
		            	}else{
		            		formInputText($(el), false);
		            	}
		            }else {
		                formInputText($(el), true, '格式不正确,应为5~11位数字！');
		            }
		        }
		
		        function formQQBlurCheck(el){
		            formInputReset(el);
		            $(el).on('blur', function () {
		                formQQCheck($(this));
		            });
		        }
		        function formExistCheck(el){
		        			if ($(el).hasClass('user-email')){
		        				_.ui.formDataUsed('user/ajax_checkIfEmailExistExceptMe', {
		                           e: $(el).val(),
		                           uid: user_id
		                       }, function (used) {
		                           if (used == false) {
		                               $($(el).parents('.form-group')[0]).removeClass('has-success').addClass('has-error');
		                               $(el).next('span.help-inline').removeClass('alert').removeClass('alert-error').html('');
		                           }
		                       });
		                    }else if($(el).attr('id')=='inputEmail'){
		                        _.ui.formDataUsed('user/ajax_checkIfEmailExist', {
		                            e: $(el).val()
		                        }, function (used , data) {
		                            if (!used) {
		                                $($(el).parents('.form-group')[0]).removeClass('has-success').addClass('has-error');
		                                formInputText($(el), true , data.error );
		                                return false;
		                            }
		                        });
		                    }else if($(el).attr('id')=='inputName'){
		                        _.ui.formDataUsed('user/ajax_checkIfNickNameExist', {
		                            e: $(el).val()
		                        }, function (used , data) {
		                            if (!used) {
		                                $($(el).parents('.form-group')[0]).removeClass('has-success').addClass('has-error');
		                                formInputText($(el), true , data.error );
		                                return false;
		                            }
		                        });
		                    }
		        }
		
		        function formExistBlurCheck(el){
		            formInputReset(el);
		            el.on('blur', function () {
		                formExistCheck($(this));
		            });
		        }
		//email检查，检查输入的email是否合法
		//@param el jQueryElement
		        function formEmailCheck(el){
		            var email = $(el).val();
		            if (inputCheckNull($(el))  || ($(el).val() == $(el).attr('placeholder'))) {
		            	if ( el.hasClass('on') && !el.hasClass('is-null') ){
		            		formInputText($(el), true, 'Email不能为空');
		            	}else{
		            		formInputText($(el), false);
		            	}
		            } else if (!_.ui.isEmail(email) && $(el).val().trim()) {
		                formInputText($(el), true, '请输入正确的Email');
		                hasErrorElHandler($(el));
		            } else {
		                formInputText($(el), false);
		                hasErrorElHandler();
		            }
		        }
		
		        function formEmailBlurCheck(el) {
		        	formInputReset(el);
		            $(el).on('blur', function () {
		                formEmailCheck($(this));
		            });
		        }
		//password检查，两次输入password或新旧password两种情况
		//@paran el jQueryElement
		        function formPasswdCheck(el){
		            var _id = $(el).attr('id');
		            if ($(el).val().length < 6 && !$(el).hasClass('is-null')) {
		                formInputText($(el), true, '密码长度至少为6位');
		            } else if (_id == 'inputOldPassword') {
		                var inputEl = $(el), url = 'user/ajax_checkIfEmailAndPasswdCorrect', email = $('#inputSettingEmail').val();
		                $.post(url, {
		                    e: email,
		                    p: $('#inputOldPassword').val()
		                }, function (d, s) {
		                    var status = $.parseJSON(d)['code'];
		                    if (status == '200' && s == 'success') {
		                        formInputText(inputEl, false);
		                    } else {
		                        formInputText(inputEl, true, '密码不正确');
		                    }
		                });
		            } else if (_id == 'inputPassword') {
		                if (that.find('#inputPassword').val() == that.find('#inputOldPassword').val()) {
		                    formInputText($(el), true, '新旧密码相同');
		                } else {
		                    formInputText($(el), false);
		                }
		            } else if (_id == 'inputCheck') {
		                if ($(el).val() != that.find('#inputPassword').val()) {
		                    formInputText($(el), true, '两次输入密码不相同');
		                } else {
		                    formInputText($(el), false);
		                }
		            } else {
		                formInputText($(el), false);
		            }
		        }
		
		        function formPasswdBlurCheck(el) {
		        	formInputReset($('#inputPassword'));
		            formInputReset($('#inputOldPassword'));
		            formInputReset($('#inputCheck'));
		            $(el).on('blur', function () {
		                formPasswdCheck($(this));
		            });
		        }
		        //数字元素检查，绑定blur事件，判断是否为空，是否为数字
		//@param el jQueryElement
		        function formNumCheck(el){
		            var num = $(el).val().trim();
		            if (num && !num.match(/^\d+$/g)) {
		                formInputText($(el), true, '请输入数字！');
		                hasErrorElHandler($(el));
		            } else if (!num) {
		                var  t = '不能为空';
		                if ( el.hasClass('on') && !el.hasClass('is-null') ){
		                	formInputText($(el), true, t);
		                    hasErrorElHandler($(el));
		                }else{
		            		formInputText($(el), false);
		            	}
		            }  else {
		                formInputText($(el), false);
		                hasErrorElHandler();
		            }
		        }
		        function formNumBlurCheck(el) {
		            formInputReset(el);
		            $(el).on('blur', function () {
		                formNumCheck($(this));
		            });
		        }
		        //input失去焦点时进行验证，绑定blur事件，判断是否为空
		//@param el jQueryElement
		        function formTextCheck(el) {
		            if (that.hasErrorEl && $(that.hasErrorEl).attr('id') !== $(el).attr('id')) {
		                that.hasErrorEl.focus();
		            } else {
		                var t = '不能为空' ;
		                if (inputCheckNull($(el)) || ($(el).val() == $(el).attr('placeholder'))) {
		                	if ( el.hasClass('on') && !el.hasClass('is-null') ){
		                		hasErrorElHandler($(el));
		                        formInputText($(el), true, t);
		                	}else{
		                		formInputText($(el), false);
		                	}
		                } else {
		                    formInputText($(el), false);
		                    that.hasErrorEl = false;
		                }
		            }
		        }
		        function formTextBlurCheck(el) {
		            $(el).on('blur', function () {
		                formTextCheck($(this)) ;
		            });
		        }
		      //input聚焦时进行验证，绑定focus时间，并给与提示
		        function formTextFocusCheck(el){
		        	formInputReset(el);
		        	el.on('focus', function () {
		        		if (that.hasErrorEl && $(that.hasErrorEl).attr('id') !== $(el).attr('id')) {
		                    that.hasErrorEl.focus();
		                } else {
		                    var placeNotice = $(this).attr('placeNotice') ,
		                    	id = $(el).attr('id') ; 
		                    	$("[rel='"+id+"']").show()  ;
		                    getHelpAlert($(this));
		                    formInputText(el, false , placeNotice );
		                    $($(this).parents('.form-group')[0]).removeClass('has-success').removeClass('has-error');
		                    that.hasErrorEl = false;
		                }
		            });
		        }
		        //-------------------------
		        //字数统计
		        //@el jQueryElement input对象
		        //@tar jQueryElement 提示对象
		        //@max number 字数限制，可选，默认为30
		        //-------------------------
		        function numCount(el, tar, max) {
		        	formInputReset(el);
		        	
		            var num = $(el).val().length ,
		            	p = $(el).parents('.form-group')[0] ,
		            	l = $(p).find('label').length != 0 ?$(p).find('label').width()+43:14,
		                width = $(el).width() + l ,
		            	tp = {} ;
		            if ( $(p).width()!= 0 ){
		            	tp = { right : $(p).width()-width}
		            }else{
		            	tp = {right:'5%' /*$(p).width()-width - 43*/ }
		            }
		            $(tar).css(tp);
		            if (num > max) {
		                $(tar).text(num + '/' + max);
		            } else {
		                $(tar).text(num + '/' + max);
		            }
		            
		            $(el).on('keyup', function () {
		                var num = $(this).val().length;
		                if (num > max) {
		                    $(tar).text(num + '/' + max);
		                } else {
		                    $(tar).text(num + '/' + max);
		                }
		            });
		            $(el).on('blur', function () {
		                var t = '不能为空'
		                , num = $(this).val().length;
		                $(tar).hide();
		                if (inputCheckNull($(this)) || ($(this).val() == $(this).attr('placeholder'))) {
		                	if ( el.hasClass('on') && !el.hasClass('is-null') ){
		                		formInputText($(this), true, t);
		                        hasErrorElHandler($(this));
		                	}else{
		                		formInputText($(el), false);
		                	}
		                    
		                } else if (num > max) {
		                    formInputText($(this), true, '字数为' + max + '字');
		                } else {
		                    formInputText($(this), false);
		                }
		            });
		        }
		
		        //-------------------------
		        // 提交时进行全局验证
		        //-------------------------
		        function errorEleScroll(el) {
		            var offset = parseInt($(el).position().top) - 200;
		//判断是否为ie8
		            if (offset > -170){
		            	$('html,body').animate({ 'scrollTop': offset }, 200);
		            }
		        }
		//全局验证，遍历含有select-on的select元素，检查选中状态
		//@return bool
		        function selectGlobalValuation() {
		            var selectEls = that.find('select.select-on') , 
		              i = 0 , 
		              il = selectEls.length;
		            if (il == 0) {
		                return true;
		            } else {
		                for (; i < il; i++) {
		                    var ratio = "[name='" + $(selectEls[i]).attr('check') + "']", sel = $(selectEls[i]), text;
		                    if ($(ratio).length && $(ratio).attr('checked') == 'checked' && sel.val() == '0') {
		                        text = '请选择' + $($(sel).parents('.form-group')[0]).find('label').text();
		                        getHelpAlert(sel , text);
		                        formInputText(sel, true);
		                    } else if (!$(ratio).length && sel.val() == '0') {
		                        text = '请选择' + $($(sel).parents('.form-group')[0]).find('label').text();
		                        getHelpAlert(sel , text);
		                        formInputText(sel, true);
		                    }
		                }
		                return true;
		            }
		        }
		//全局验证，遍历相关联的input元素是否已填写
		//@return bool
		        function inputLinkedGlobalValuation() {
		            var inputEls = that.find("[rel='input-linked'] .linked-on") , 
		              i = 0 , 
		              il = inputEls.length , 
		              check = that.find("[rel='input-linked']").hasClass('check');
		            if (!check) {
		                return true;
		            } else {
		                for (; i < il; i++) {
		                    var inputEl = $(inputEls[i]);
		                    if ((inputEl.val() == '' && !inputEl.hasClass('is-null')) || (inputEl.val() == inputEl.attr('placeholder'))) {
		                        var help = getHelpInline(inputEl)
		, text = '不能为空';
		                        formInputText(inputEl, true);
		                        if (help.length) {
		                          getHelpAlert(inputEl , text);
		                        } else {
		                            inputEl.tooltip('show');
		                        }
		                        return false;
		                    }
		                }
		                return true;
		            }
		        }
		//全局验证input元素是否有必填项为空，遍历含有on这个类的元素，比对placeholder
		//@return bool
		        function inputGlobalValuation() {
		            var inputEls = that.find('.on') , 
		              i = 0 ,
		              il = inputEls.length;
		            for (; i < il; i++) {
		                var inputEl = $(inputEls[i]), help = getHelpInline(inputEl);
		                if (inputEl.val() == '' || (inputEl.val() == inputEl.attr('placeholder'))) {
		                    var text = '不能为空';
		                    formInputText(inputEl, true );
		                    if (help.length) {
		                    	getHelpAlert(inputEl , text);
		                    } else {
		                        inputEl.tooltip('show');
		                    }
		                } else {
		                    //formInputText(inputEl, false);
		                }
		            }
		            return true;
		        }
		//全局验证必填的img是否未上传图像，遍历含有img-on这个类的img元素
		//@return bool
		        function imgGlobalValuation() {
		            var imgEls = that.find("img.img-on") , 
		              i = 0 , 
		              il = imgEls.length;
		           for (; i < il; i++) {
		              var imgEl = $(imgEls[i]);
		              if (imgEl.length == 0 || !imgEl.hasClass('active')) {
		            	  var text =  '请先上传图片';
		                  if (imgEl.parent().find('.active').length == '0') {
		                	  formInputText(imgEl, true);
		                      imgEl.parents('.form-group').find('span.help-inline').addClass('alert').addClass('alert-error').text(text);
		                  } 
		              } else {
		                 formInputText(imgEl, false);
		              }
		           }
		           return true;
		        }
		        
		        //-------------------------
		        //form check function end
		        //-------------------------
		        globalValuation();
		        function globalValuation(oper){
		            oper = oper || 'blur';
		            if(oper == 'blur'){
		            	linkedElements.each(function () {
		                    formKeyPressHandler($(this));
		                });
		
		                selectElements.each(function () {
		                    formChangeReset($(this));
		                });
		                
		                pillsActiveLimit($(pillsElements));
		            }
		            
		            inputElements.each(function () {
		            	if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		                    formTextBlurCheck($(this));
		                    formKeyPressHandler($(this));
		                    formChangeReset($(this));
		                }else{
		                    formTextCheck($(this));
		                }
		            });
		
		            emailElements.each(function () {
		                if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		                    formEmailBlurCheck($(this));
		                    formKeyPressHandler($(this));
		                }else{
		                    formEmailCheck($(this));
		                }
		            });
		
		            inputPasswords.each(function () {
		                if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		                    formPasswdBlurCheck($(this));
		                    formKeyPressHandler($(this));
		                }else{
		                    formPasswdCheck($(this));
		                }
		            });
		
		            urlElements.each(function () {
		                if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		                    formUrlBlurCheck($(this));
		                    formKeyPressHandler($(this));
		                }else{
		                    formUrlCheck($(this));
		                }
		            });
		            itunesUrlElements.each(function () {
		                if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		            	    formItunesUrlBlurCheck($(this));
		            	    formKeyPressHandler($(this));
		                }else{
		                    formItunesUrlCheck($(this));
		                }
		            });
		            googlePlayUrlElements.each(function () {
		                if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		            	   formGooglePlayUrlBlurCheck($(this));
		            	   formKeyPressHandler($(this));
		                }else{
		                    formGooglePlayUrlCheck($(this));
		                }
		            });
		            weiboUrlElements.each(function () {
		                if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		            	   formWeiboUrlBlurCheck($(this));
		            	   formKeyPressHandler($(this));
		                }else{
		                    formWeiboUrlCheck($(this));
		                }
		            });
		
		            phoneElements.each(function () {
		                if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		                    formPhoneBlurCheck($(this));
		                    formKeyPressHandler($(this));
		                }else{
		                    formPhoneCheck($(this));
		                }
		            });
		
		            numberElements.each(function () {
		                if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		                    formNumBlurCheck($(this));
		                    formKeyPressHandler($(this));
		                }else{
		                    formNumCheck($(this));
		                }
		            });
		
		            textElements.each(function () {
		                if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		                    formKeyPressHandler($(this));
		                    formTextBlurCheck($(this));
		                    var num = $(this).attr('maxlength') ? parseInt($(this).attr('maxlength')) : null
		                            , tar = "[rel='" + $(this).attr('id') + "']";
		                    if (num) {
		                    	numCount($(this), tar, num);
		                    }
		                }else{
		                    formTextCheck($(this));
		                }
		            });
		            
		            
		            checkListElements.each(function (){
		          	  	var el = $(this) ;
		                if(oper == 'blur'){
		          	  	    formExistBlurCheck(el);
		              	  	formKeyPressHandler(el);
		              	  	formChangeReset(el);
		                }else{
		                    formExistCheck(el);
		                }
		            });
		            
		            englishElements.each(function(){
		                if(oper == 'blur'){
		                	formTextFocusCheck($(this));
		                    formEnglishBlurCheck($(this));
		                }else{
		                    formEnglishCheck($(this));
		                }
		            });
		            qqElements.each(function(){
		               if(oper == 'blur'){
		            	   formTextFocusCheck($(this));
		                    formQQBlurCheck($(this));
		                }else{
		                    formQQCheck($(this));
		                }
		            });
		        }
		//监听提交表单事件
		        $(that).bind('submit', function (e) {
		//        	console.log(e);
		            var options = typeof process === 'function' ? process.call(that) : {} ;
		            var   action = that.attr('action') , 
		              method = that.attr('method') , 
		              data = options.data , 
		              help = options.help , 
		              text = options.text || '发送成功' , 
		              badrequesttxt = options.badrequesttxt || '数据有误，请重试！' , 
		              notfoundtxt = options.notfoundtxt || '非法请求，请重试！' , 
		              internaltxt = options.internaltxt || '非法请求，请重试！' , 
		              errortxt = options.errortxt || '数据填写错误，请查证！' , 
		              disable = options.disable || false , 
		              nobind =  options.nobind || false ,
		              unpreventSubmit = options.unpreventSubmit || false , 
		              callback = options.callback || function () {} , //提交完成后调用，可用于页面跳转
		              success = options.success || function () {}  , //ajax请求成功后调用，status == 'success' && code == 200
		              error = options.error || function () {} , //ajax请求失败后调用，status == 'success' && code != 200
		              btnSubmitText = btnSubmit.text();
		              
		            if ( nobind ){
		            	$(that).unbind();
		            }
		            inputLinkedGlobalValuation();
		            selectGlobalValuation();
		            inputGlobalValuation();
		            imgGlobalValuation() ;
		            globalValuation('submit');
		            if (that.find('.form-group.has-error').length) {
		            	e.preventDefault();
		                errorEleScroll($('.form-group.has-error')[0]);
		                if ( nobind ){
		                	that.bind('submit' , arguments.callee);
		                }
		                return false;
		            } else {
		            	if ( unpreventSubmit ){
		                	$(that).submit();
		                	return true;
		                }else{
		                	data['submit_method'] = "js" ;
		                	e.preventDefault();
		                }
		                if (btnSubmit.hasClass("disabled")) {
		                    return false;
		                } else {                                        
		                    btnSubmit.addClass("disabled");
		                    btnSubmit.text('loading...');
		                }
		                if (options === false) {
		                    btnSubmit.removeClass("disabled");
		                    btnSubmit.text(btnSubmitText);
		                    return false;
		                } else {
		                    if (typeof options.success !== 'function') {
		                        success = function (d, s) {
		                            var status = $.parseJSON(d)['code'];
		                            if (s == 'success') {
		                                switch (status) {
		                                case 200:
		                                case '200':
		                                    $(help).html(text);
		                                    if (!disable) {
		                                        btnSubmit.removeClass("disabled");
		                                        btnSubmit.text(btnSubmitText);
		                                    }
		                                    callback.call(that, $.parseJSON(d));
		                                    break;
		                                case 400:
		                                case '400':
		                                    var badrequesttxt = $.parseJSON(d)['error'] || badrequesttxt;
		                                    $(help).html(badrequesttxt);
		                                    btnSubmit.removeClass("disabled");
		                                    btnSubmit.text(btnSubmitText);
		                                    break;
		                                case 404:
		                                case '404':
		                                    $(help).html(notfoundtxt);
		                                    btnSubmit.removeClass("disabled");
		                                    btnSubmit.text(btnSubmitText);
		                                    break;
		                                case 500 :
		                                case '500':
		                                	var internaltxt = $.parseJSON(d)['error'] || internaltxt;
		                                    $(help).html(internaltxt);
		                                    btnSubmit.removeClass("disabled");
		                                    btnSubmit.text(btnSubmitText);
		                                    break;
		                                default:
		                                    $(help).html('请求超时，请稍后重试！');
		                                    btnSubmit.removeClass("disabled");
		                                    btnSubmit.text(btnSubmitText);
		                                    break;
		                                }
		                            } else {
		                                $(help).html('请求超时，请稍后重试！');
		                                btnSubmit.removeClass("disabled");
		                                btnSubmit.text(btnSubmitText);
		                            }
		                        };
		                    } else {
		                        success = options.success;
		                    }
		                    if (typeof options.error !== 'function') {
		                        error = function () {
		                            $(help).html('请求超时，请稍后重试！');
		                            btnSubmit.removeClass("disabled");
		                            btnSubmit.text(btnSubmitText);
		                        };
		                    } else {
		                        error = options.error;
		                    }
		                    if (that.find('.has-error').length == 0) {
		                        $.ajax({
		                            type: method,
		                            url: action,
		                            data: data,
		                            success: success,
		                            error  : error,
		                            callback : callback
		                        });
		                    } else {
		                    	if ( nobind ){
		                        	that.bind('submit' , arguments.callee);
		                        }
		                        $(help).html(errortxt);
		                        btnSubmit.removeClass("disabled");
		                        btnSubmit.text(btnSubmitText);
		                    }
		                    return false;
		                }
		            }
		        });
		    }
        });
	});
}(jQuery, Grape));