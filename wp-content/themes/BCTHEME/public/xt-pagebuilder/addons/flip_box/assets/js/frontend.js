jQuery(function($){ 'use strict';
    function bindFlipBox(doc) {
        $(doc)
            .off('click.xtpbFlipBox', '.xtpb-flip-box-content.flipon-click .xtpb-flipbox-panel, .threeD_style.flipon-click .threeD-content-wrap')
            .on('click.xtpbFlipBox', '.xtpb-flip-box-content.flipon-click .xtpb-flipbox-panel, .threeD_style.flipon-click .threeD-content-wrap', function () {
                $(this).toggleClass('flip');
            });

        $(doc)
            .off('mouseenter.xtpbFlipBox', '.xtpb-flip-box-content.flipon-hover .xtpb-flipbox-panel, .threeD_style.flipon-hover .threeD-content-wrap')
            .on('mouseenter.xtpbFlipBox', '.xtpb-flip-box-content.flipon-hover .xtpb-flipbox-panel, .threeD_style.flipon-hover .threeD-content-wrap', function () {
                $(this).addClass('flip');
            });

        $(doc)
            .off('mouseleave.xtpbFlipBox', '.xtpb-flip-box-content.flipon-hover .xtpb-flipbox-panel, .threeD_style.flipon-hover .threeD-content-wrap')
            .on('mouseleave.xtpbFlipBox', '.xtpb-flip-box-content.flipon-hover .xtpb-flipbox-panel, .threeD_style.flipon-hover .threeD-content-wrap', function () {
                $(this).removeClass('flip');
            });
    }

    bindFlipBox(document);

    $(document).on('rendered_addon', function(e, addon){
        var iframe;
        if (!addon || (addon.type !== 'addon' && addon.type !== 'inner_addon') || addon.name !== 'xtpb_flip_box') {
            return;
        }
        iframe = window.frames['xtpb-builder-view'];
        if (iframe && iframe.window && iframe.window.document) {
            bindFlipBox(iframe.window.document);
        }
    });
});
