jQuery(function($){ 'use strict';
    function bindTabs(doc) {
        $(doc).off('click.xtpbTab', 'ul.xtpb-tab-nav .xtpb-tab-nav-list').on('click.xtpbTab', 'ul.xtpb-tab-nav .xtpb-tab-nav-list', function(){
            var $tab = $(this);
            var targetId = $tab.attr('data-tab');
            var $addon = $tab.closest('.xtpb-tab-addon-content');

            if (!targetId || !$addon.length) {
                return;
            }

            $tab.closest('ul').find('li').removeClass('active');
            $addon.find('.xtpb-tab-content-wrap .xtpb-tab-content').removeClass('active');
            $tab.addClass('active');
            $addon.find('#' + targetId).addClass('active');
        });
    }

    bindTabs(document);

    $(document).on('rendered_addon', function(e, addon){
        var iframe;
        if (!addon || (addon.type !== 'addon' && addon.type !== 'inner_addon') || addon.name !== 'xtpb_tab') {
            return;
        }
        iframe = window.frames['xtpb-builder-view'];
        if (iframe && iframe.window && iframe.window.document) {
            bindTabs(iframe.window.document);
        }
    });
});
