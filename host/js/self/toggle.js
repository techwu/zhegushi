(function ($, _) {
	jQuery(document).ready(function () {
        $.fn.extend({
        	hideToggle: function () {
                var tar = $($(this).attr('rel-target')) , 
                	that = $(this);

                $(this).live('click', function () {
                    if (tar.hasClass('active')) {
                        tar.hide();
                        tar.removeClass('active');
                    } else {
                        tar.show();
                        tar.addClass('active');
                    }
                });
                tar.find("[rel='cancel']").live('click', function () {
                    that.click();
                });
            },
        	//下拉效果切换
            packToggle: function () {
                var toggleClass = $(this).attr('rel') , 
                	that = this;
                this.animating = false;
                $.subscribe('packUp', function (e, o) {
                    if (!that.animating) {
                        that.animating = true;
                        var pack = o.el.parents('.pack-down') ,
                        	baseClass = o.el.attr('class');
                        pack.animate({
                            'height': o.height
                        }, function () {
                            pack.attr('class', 'pack-up');
                            o.el.attr('class', o.el.attr('rel'));
                            o.el.attr('rel', baseClass);
                            that.animating = false;
                        });
                    }
                });
                $.subscribe('packDown', function (e, o) {
                    if (!that.animating) {
                        that.animating = true;
                        var pack = o.el.parents('.pack-up')
			, baseClass = o.el.attr('class');
                        pack.animate({
                            'height': o.height
                        }, function () {
                            pack.attr('class', 'pack-down');
                            o.el.attr('class', o.el.attr('rel'));
                            o.el.attr('rel', baseClass);
                            that.animating = false;
                        });
                    }
                });
                $(this).live('click', function () {
                    var height = $(this).parents('.pack-up').find('ul').height();
                    $.publish('packDown', {
                        el: $(this),
                        height: height
                    });
                });
                $('.' + toggleClass).live('click', function () {
                    $.publish('packUp', {
                        el: $(this),
                        height: '32px'
                    });
                });

            }
        });
	});
}(jQuery, Grape));