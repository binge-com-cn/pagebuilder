
jQuery(document).ready(function($) {
    'use strict';

    function buildSlickOptions(settings, isEditorPreview) {
        let isDots = ( parseInt(settings.control_option) === 1);
        let isArrow = ( parseInt(settings.arrow_option) === 1);
        let animated_speed = settings.animated_speed;
        let isAutoPlay = ( parseInt(settings.autoplay_option) === 1);

        let slickOptions = {
            autoplay: false,
            speed: 600,
            lazyLoad: 'progressive',
            nextArrow: isEditorPreview
                ? '<span class="xtpb-carousel-prev"><i class="ri-arrow-left-s-line"></i></span>'
                : '<span class="xtpb-carousel-next"><i class="ri-arrow-right-s-line"></i></span>',
            prevArrow: isEditorPreview
                ? '<span class="xtpb-carousel-next"><i class="ri-arrow-right-s-line"></i></span>'
                : '<span class="xtpb-carousel-prev"><i class="ri-arrow-left-s-line"></i></span>'
        };

        slickOptions.dots = isDots;
        slickOptions.arrows = isArrow;
        slickOptions.speed = animated_speed;
        slickOptions.autoplay = isAutoPlay;

        return slickOptions;
    }

    function initSlider($slider, settings, isEditorPreview) {
        let slickOptions = buildSlickOptions(settings, isEditorPreview);

        if ($slider.hasClass('slick-initialized')) {
            $slider.slick('unslick');
        }
        $slider.not('.slick-initialized').slick(slickOptions).slickAnimation();
    }

    $('.xtpb-carousel').each(function(index, $value){
        let $slider = $(this);
        let settingsAttr = $slider.attr('data-settings');
        let settings;
        if (!settingsAttr) {
            return;
        }
        try {
            settings = JSON.parse(settingsAttr);
        } catch (e) {
            return;
        }
        initSlider($slider, settings, false);
    });

    $(document).on('rendered_addon', function (e, addon) {
        let iframe = window.frames['xtpb-builder-view'];

        if (typeof addon.type !== 'undefined' && addon.type === 'addon' && ( addon.name === 'xtpb_carousel')) {
            if (!iframe || !iframe.window || !iframe.window.document) {
                return;
            }

            let $sliderWrap = $(iframe.window.document).find('.xtpb-carousel');

            $sliderWrap.each(function(index, $value){
                let $slider = $(this);
                let addonID = parseInt($slider.attr('data-addon-id'));

                if (addonID === parseInt(addon.id) ) {
                    initSlider($slider, addon.settings, true);
                }
            });
        }
    });

});
