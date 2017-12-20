(function ($) {
    $.fn.materialTabs = function () {
        this.selector = '#'+$(this)[0].id;

        this.init = function () {
            var activeItem = $(this.selector + ' nav .viewport ul').find("li.active");

            if (!activeItem.length) {
                activeItem = $(this.selector + ' nav .viewport ul li').first();
            }

            this.setActiveItem(activeItem);
        };

        this.getActiveItem = function () {
            return $(this.selector + ' nav .viewport ul li.active');
        };

        this.getActiveTab = function () {
            return $(this.selector + ' .tabs .tab-content.active');
        };

        this.setActiveItem = function (activeItem) {
            this.setActiveTab(parseInt(activeItem.data('tab')));

            $(this.selector + ' li.active').removeClass('active');

            if (!activeItem.hasClass('active')) {
                activeItem.addClass('active');
            }

            this.setBorder(activeItem);
        };

        this.setBorder = function (activeItem) {
            var border = $(this.selector + ' nav .viewport ul .material-tabs-border');

            if(border.length != 0) {
                var width = activeItem.outerWidth();
                var left = $(activeItem).offset().left - $(this.selector + ' nav .viewport ul').offset().left;
                var right = $(this.selector + ' nav .viewport ul').outerWidth() - (left + width);

                if (left < border.offset().left) {
                    border.removeClass('to-right');
                    border.addClass('to-left');
                } else {
                    border.removeClass('to-left');
                    border.addClass('to-right');
                }

                $(this.selector + ' nav .viewport ul .material-tabs-border').css({
                    'left': left + 'px',
                    'right': right + 'px'
                });
            }
        };

        this.setActiveTab = function (index) {
            var oldItem = $(this.selector + ' .tabs .tab-content.active');
            var newItem = $(this.selector + ' .tabs .tab-content[data-tab="' + index + '"]');

            var oldIndex = oldItem.data('tab');

            var height = newItem.outerHeight();

            $(this.selector + ' .tabs').css({
                'height': height + 'px'
            });

            $(this.selector + ' .tabs .tab-content.active').removeClass('active');
            newItem.addClass('active');

            if (oldIndex !== index) {

                var transform = 100;

                if (index > oldIndex) {
                    transform *= -1;
                }

                newItem.css({
                    'display': 'block',
                    '-webkit-transform': 'translateX(0%)',
                    'transform': 'translateX(0%)'
                });

                oldItem.css({
                    'display': 'block',
                    '-webkit-transform': 'translateX(' + transform + '%)',
                    'transform': 'translateX(' + transform + '%)'
                });
            }
        };

        this.setTabsHeight = function () {
            var activeTab = this.getActiveTab();
            var height = activeTab.outerHeight();

            $(this.selector + ' .tabs').css({
                'height': height + 'px'
            });
        };

        this.checkViewport = function () {
            if ($(this.selector + ' nav .viewport ul').offset().left < 0) {
                $(this.selector + ' nav .left-fade').fadeIn(150);
            } else {
                $(this.selector + ' nav .left-fade').fadeOut(150);
            }

            if ($(this.selector + ' nav .viewport ul').offset().left > $(this.selector + ' nav .viewport').width() - $(this.selector + ' nav .viewport ul').width() + 1) {
                $(this.selector + ' nav .right-fade').fadeIn(150);
            } else {
                $(this.selector + ' nav .right-fade').fadeOut(150);
            }
        };

        return this
    };
})(jQuery);

$.fn.materialRipple = function (options) {
    var defaults = {
        rippleClass: 'ripple-wrapper'
    };

    $.extend(defaults, options);

    $('body').on('animationend webkitAnimationEnd oAnimationEnd', '.' + defaults.rippleClass, function () {
        removeRippleElement(this);
    });

    var addRippleElement = function (element, e) {
        $(element).append('<span class="added ' + defaults.rippleClass + '"></span>');
        newRippleElement = $(element).find('.added');
        newRippleElement.removeClass('added');

        // get Mouse Position
        var mouseX = e.pageX;
        var mouseY = e.pageY;

        // for each ripple element, set sizes
        var elementWidth = $(element).outerWidth();
        var elementHeight = $(element).outerHeight();
        var d = Math.max(elementWidth, elementHeight);
        newRippleElement.css({'width': d, 'height': d});

        var rippleX = e.clientX - $(element).offset().left - d / 2 + $(window).scrollLeft();
        var rippleY = e.clientY - $(element).offset().top - d / 2 + $(window).scrollTop();

        // Position the Ripple Element
        newRippleElement.css('top', rippleY + 'px').css('left', rippleX + 'px').addClass('animated');
    };

    var removeRippleElement = function ($element) {
        $($element).remove();
    };

    // add Ripple-Wrapper to all Elements
    $(this).addClass('ripple');

    // Let it ripple on click
    $(this).bind('click', function (e) {
        addRippleElement(this, e);
    });
};