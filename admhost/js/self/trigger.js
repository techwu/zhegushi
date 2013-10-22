(function ($, _) {
	jQuery(document).ready(function () {
        $.fn.extend({
        	//ratio 选择展开相应元素
            ratioTrigger: function () {
                var tar = $(this).attr('ratio-target');
                $(this).bind('change', function () {
                    if ($(this).val() == 1) {
                        $(tar).show();
                    } else {
                        $(tar).hide().removeClass('has-error');
                        if( $(tar).find("span.help-inline") ){
                        	$(tar).find("span.help-inline").html('');
                        }
                    }
                });
            },

            //点击checkbox展开相应元素
            checkboxSlideTrigger: function () {
                var tar = $(this).attr('slide-target') , 
                	active = $(tar).find('.item') , 
                	action = $(tar).find('.timeline-actions') , 
                	btnCancel = $(tar).find("[rel='timeline-cancel']") , 
                	that = $(this);
                $(this).bind('change', function () {
                    if (that.attr('checked')) {
                        active.removeClass('active');
                        action.removeClass('active');
                    } else {
                        active.addClass('active');
                        action.addClass('active');
                    }
                });

                btnCancel.bind('click', function () {
                    if (that.attr('checked')) {
                        active.removeClass('active');
                        action.removeClass('active');
                    } else {
                        active.addClass('active');
                        action.addClass('active');
                        that.attr('checked', true);
                    }
                });

            }
        });
	});
}(jQuery, Grape));
            