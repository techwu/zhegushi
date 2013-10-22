(function ($, _) {
	jQuery(document).ready(function () {
        $.fn.extend({
        	 //placeholder处理，获取placeholder属性的值作为input的value，监听focus、blur事件
            placeHolder: function () {
                if ($(this).attr('type') == 'password') {
                    return false;
                } else {
                    $(this).focus(function () {
                        var input = $(this);
                        if (input.val() == input.attr('placeholder')) {
                            input.val('');
                            input.removeClass('placeholder');
                        }
                    }).blur(function () {
                        var input = $(this);
                        if (input.val() == '' || input.val() == input.attr('placeholder')) {
                            input.addClass('placeholder');
                            input.val(input.attr('placeholder'));
                        } else if (input.val() != '' && input.val() != input.attr('placeholder')) {
                            input.removeClass('placeholder');
                        }
                    }).blur();
                    $(this).parents('form').submit(function () {
                        $(this).find('[placeholder]').each(function () {
                            var input = $(this);
                            if (input.val() == input.attr('placeholder')) {
                                input.val('');
                            }
                        });
                    });
                    return true;
                }
            }
        });
	});
}(jQuery, Grape));
            