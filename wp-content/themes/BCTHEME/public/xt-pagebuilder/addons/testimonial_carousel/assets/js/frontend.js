jQuery(function($){ 'use strict';
    function buildSliderOptions($container) {
        var nav = $container.data('shownav');
        var col = $container.data('col');
        var options = {
            autoplay: $container.data('autoplay'),
            dots: $container.data('showdots'),
            arrows: nav,
            speed: $container.data('speed'),
            slidesToShow: col,
            slidesToScroll: col,
            responsive: [
                { breakpoint: 1024, settings: { slidesToShow: col, slidesToScroll: col } },
                { breakpoint: 850, settings: { slidesToShow: 1, slidesToScroll: 1 } },
                { breakpoint: 600, settings: { slidesToShow: 1, slidesToScroll: 1 } }
            ]
        };

        if (nav === true || nav === 'true' || nav === 1 || nav === '1') {
            options.nextArrow = '<span class="xtpb-carousel-prev"><i class="ri-arrow-right-s-line"></i></span>';
            options.prevArrow = '<span class="xtpb-carousel-next"><i class="ri-arrow-left-s-line"></i></span>';
        }

        return options;
    }

    function initTestimonialCarousel($scope) {
        $scope.find('.xtpb-testimonial-content-carousel').each(function() {
            var $this = $(this);
            if ($this.hasClass('slick-initialized')) {
                return;
            }
            $this.slick(buildSliderOptions($this));
        });
    }

    initTestimonialCarousel($(document));

    $(document).on('rendered_addon', function(e, addon){
        var iframe;
        if (!addon || (addon.type !== 'addon' && addon.type !== 'inner_addon') || addon.name !== 'xtpb_testimonial_carousel') {
            return;
        }
        iframe = window.frames['xtpb-builder-view'];
        if (iframe && iframe.window && iframe.window.document) {
            initTestimonialCarousel($(iframe.window.document));
        }
    });
});
