jQuery(function($){ 'use strict';
    function bindAlerts(doc) {
        $(doc).off('click.xtpbAlert', '.xtpb-close-alert').on('click.xtpbAlert', '.xtpb-close-alert', function(){
            $(this).closest('.xtpb-alert-addon-content').fadeOut(200);
        });
    }

    bindAlerts(document);

    $(document).on('rendered_addon', function(e, addon){
        var iframe;
        if (!addon || (addon.type !== 'addon' && addon.type !== 'inner_addon') || addon.name !== 'xtpb_alert') {
            return;
        }
        iframe = window.frames['xtpb-builder-view'];
        if (iframe && iframe.window && iframe.window.document) {
            bindAlerts(iframe.window.document);
        }
    });
});
