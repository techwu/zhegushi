(function ($, _) {
    Grape.ui = (function () {
        var CAROUSEL_FLAG = 0 ;
        return {
            //-------------------------
            //form check function start
            //-------------------------

            //改变input提示文本和状态
            //@el：input对象
            //@iserror：bool，是否出错
            //@textt：文本提示（可选）
            inputCheckNull: function (el) {
                if ($(el).attr('type') != 'file') {
                    if ($(el).val() == "") {
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }
            },
            formInputText: function (el, iserror, text) {
                if (iserror) {
                    $(el).next('span').html(text);
                    $(el).parents('.control-group').attr('class', 'control-group error');
                } else {
                    if (text) {
                        $(el).next('span').html('<i class="icon-ok"></i>' + text);
                        $(el).parents('.control-group').attr('class', 'control-group success');
                    } else {
                        $(el).next('span').html('<i class="icon-ok"></i>');
                        $(el).parents('.control-group').attr('class', 'control-group success');
                    }
                }
            },
            formInputReset: function (el, text) {
                $(el).bind('focus', function () {
                    $(this).next('span').html('');
                    if (text != "") {
                        $(this).next('span').text(text);
                    } else {
                        $(this).next('span').text('');
                    }
                    $(this).parents('.control-group').attr('class', 'control-group');
                });

            },
            formDataUsed: function (url, data, callback) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    async: false,
                    success: function (d, s) {
                        var d = $.parseJSON(d);
                        if (s == 'success' && d.code != '200') {
                            callback(false, d);
                        } else if (s == 'success' && d.code == '200') {
                            callback(true, d);
                        }
                    }
                });
            },
            formPasswdBlurCheck: function (el) {
                var BASE = this;
                BASE.formInputReset($('#inputPassword'));
                BASE.formInputReset($('#inputOldPassword'));
                BASE.formInputReset($('#inputCheck'));
                $(el).bind('blur', function () {
                    var _id = $(this).attr('id');
                    if ($(this).val().length < 6) {
                        BASE.formInputText($(this), true, '密码长度至少为6位');
                    } else if (_id == 'inputOldPassword') {
                        var that = $(this);
                        var url = 'user/ajax_checkIfEmailAndPasswdCorrect';
                        var email = $('#inputEmail').val();
                        $.post(url, {
                            e: email,
                            p: $('#inputOldPassword').val()
                        }, function (d, s) {
                            var status = $.parseJSON(d).code;
                            if (status == '200' && s == 'success') {
                                BASE.formInputText(that, false);
                            } else {
                                BASE.formInputText(that, true, '密码不正确');
                            }
                        });
                    } else if (_id == 'inputPassword') {
                        if ($('#inputPassword').val() == $('#inputOldPassword').val()) {
                            BASE.formInputText($(this), true, '新旧密码相同');
                        } else {
                            BASE.formInputText($(this), false);
                        }
                    } else if (_id == 'inputCheck') {
                        if ($(this).val() != $('#inputPassword').val()) {
                            BASE.formInputText($(this), true, '两次输入密码不相同');
                        } else {
                            BASE.formInputText($(this), false);
                        }
                    } else {
                        BASE.formInputText($(this), false);
                    }
                });
            },
            formTextBlurCheck: function (el) {
                var BASE = this;
                BASE.formInputReset(el);
                $(el).bind('blur', function () {
                    var l = "[for='" + $(this).attr('id') + "']";
                    var t = $(l).text() + '不能为空';
                    if ($(this).val() == "") {
                        BASE.formInputText($(this), true, t);
                    } else {
                        BASE.formInputText($(this), false);
                    }
                });
            },
            //-------------------------
            //正则判断
            //-------------------------
            isUrl: function (url) {
                var regExp = /(?:(?:http[s]?|ftp):\/\/)?[^\/\.]+?(\.)?[^\.\\\/]+?\.\w{2,}.*$/i;
                if (url.match(regExp)) {
                    return true;
                } else {
                    return false;
                }
            },
            isItunesUrl: function (url) {
                var regExp =  /^(https|http):\/\/itunes\.apple\.com\/?[^\/\.]+?(\.)?[^\.\\\/]+.*\/id+?\d{5,11}?[^\.\\\/]+$/i;
                if (url.match(regExp)) {
                    return true;
                } else {
                    return false;
                }
            },
            isGooglePlayUrl: function (url) {
                var regExp = /^(https|http):\/\/play\.google\.com\/?[^\/\.]+?(\.)?[^\.\\\/]+.*$/i;
                if (url.match(regExp)) {
                    return true;
                } else {
                    return false;
                }
            },
            isWeiboUrl: function (url) {
                var regExp = /^((https|http):\/\/)?([\w-]+\.)?weibo\.com\/?[u\/]+?[^\/\.]+?(\.)?[^\.\\\/]+.*$/i;
                if (url.match(regExp)) {
                    return true;
                } else {
                    return false;
                }
            },
            isEmail: function (email) {
                var regExp = /^([a-zA-Z0-9_\-\.\+])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
                //wu+123@36kr.com
                if (!regExp.exec(email)) {
                    return false;
                }
                var Table = new Array("\"", "\'", "<", ">", "~", "\\", ";", ",", "?", "/")
                , I, J;
                for (I = 0; I < email.length; I++) {
                    for (J = 0; J < Table.length; J++) {
                        if (email.charAt(I) == Table[J]) {
                            return false;
                        }
                    }
                }
                return true;
            },
            isNumber: function (words) {
                var patrn = /^\d*$/;
                return patrn.test(words);
            },
            isPhoneNumber: function (words) {
                var patrn = /^\+{0,1}(\d+\-{0,}\d+)+$/g;
                if (!patrn.test(words)) {
                    return false;
                } else {
                    return true;
                }
            },
            isQQ:function(words){
                var patrn=/^\d{5,11}$/g;
                if (!patrn.test(words)||words.length>11||words.length<5){
                    return false;
                } else {
                    return true;
                }
            },
            isEnglish: function(words){
                var patrn=/(\?|\>|\<|\(|\&|\!|\#|[\u4E00-\u9FBF])+/g;
                if (patrn.test(words)||words.length<5){
                    return false;
                } else {
                    return true;
                }
            },
            getFileExtension: function (file) {
                var reg = /(\\+)/g
                    , _file = file.replace(reg, "#")
                    , _arr = _file.split("#")
                    , fn = _arr[_arr.length - 1]
                    , _arrfn = fn.split(".")
                    , ext = _arrfn[_arrfn.length - 1];
                return ext;
            },
            isValidateFile: function (file, isImage) {
                var BASE = this
                    , patrn
                    , ext = BASE.getFileExtension(file);
                if (isImage == true) {
                    patrn = /^(jpg|jpeg|png|gif)$/i;
                } else {
                    patrn = /^(pdf|ppt|pptx)$/i;
                }
                return patrn.test(ext);
            },
            //-------------------------
            //字数统计
            //@el：input对象
            //@tar：提示对象
            //@max：字数限制，可选，默认为30
            //-------------------------
            numCount: function (el, tar, max) {
                var BASE = this
                , num = $(el).val().length;
                BASE.formInputReset(el);
                num = parseInt(max - num);
                if (num < 0) {
                    $(tar).attr('class', 'label label-important');
                    $(tar).text(num);
                } else {
                    $(tar).attr('class', 'label label-info');
                    $(tar).text(num);
                }
                $(el).bind('keyup', function () {
                    var num = $(this).val().length;
                    num = parseInt(max - num);
                    if (num < 0) {
                        $(tar).attr('class', 'label label-important');
                        $(tar).text(num);
                    } else {
                        $(tar).attr('class', 'label label-info');
                        $(tar).text(num);
                    }
                });
                $(el).bind('blur', function () {
                    var l = "[for='" + $(this).attr('id') + "']"
                        , t = $(l).text() + '不能为空'
                        , num = $(this).val().length;
                    num = parseInt(max - num);
                    if ($(this).val() == "") {
                        BASE.formInputText($(this), true, t)
                    } else if (num < 0) {
                        BASE.formInputText($(this), true, '字数为' + max + '字');
                    } else {
                        BASE.formInputText($(this), false);
                    }
                });
            } , 
            array_combine: function(field, value){
                if(field.length != value.length){
            		return false; 
            	}else{
            		var new_array = new Array();
            		for (i = 0; i < field.length; i++){
                       new_array[field[i]] = value[i];
            	    }
            		return new_array;
                }
            }
            //-------------------------
            //form check function end
            //-------------------------
        };
    }());
}(jQuery, Grape));
