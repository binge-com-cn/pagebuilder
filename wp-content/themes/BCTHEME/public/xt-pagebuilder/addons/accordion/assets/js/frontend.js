jQuery(function($){ 'use strict';
    function bindAccordion(doc) {
        $(doc).off('click.xtpbAccordion', '.xtpb-accordion-title').on('click.xtpbAccordion', '.xtpb-accordion-title', function(){
            var $items = $(this).closest('.xtpb-accordion-items').find('>div');
            var $handlers = $items.find('.xtpb-accordion-title');
            var $panels = $items.find('.xtpb-panel-collapse');

            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $panels.slideUp();
            } else {
                $handlers.removeClass('active');
                $panels.slideUp();
                $(this).addClass('active').next().slideDown();
            }
        });
    }

    bindAccordion(document);

    $(document).on('rendered_addon', function(e, addon){
        var iframe;
        if (!addon || (addon.type !== 'addon' && addon.type !== 'inner_addon') || addon.name !== 'xtpb_accordion') {
            return;
        }
        iframe = window.frames['xtpb-builder-view'];
        if (iframe && iframe.window && iframe.window.document) {
            bindAccordion(iframe.window.document);
        }
    });
});
