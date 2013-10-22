(function ($, _) {
	jQuery(document).ready(function () {
        $.fn.extend({
        	bubble: function () {
                var that = $(this);
                $.subscribe('bubble', function (e, o) {
                    if (o !== undefined || o.el !== undefined || !o.el.hasClass('active')) {
                        that.find('.active').removeClass('active');
                        o.el.addClass('active');
                    }
                });
                $(that).find('li').bind('hover', function () {
                    $.publish('bubble', {
                        el: $(this)
                    });
                });

            }
        });
	});
}(jQuery, Grape));
            