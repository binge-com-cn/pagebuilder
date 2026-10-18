jQuery(function($){ 'use strict';
    function initImagePopup($scope) {
        if (typeof $.fn.magnificPopup === 'undefined') {
            return;
        }

        $scope.find('.xtpb-image-popup').each(function() {
            var $this = $(this);
            if ($this.data('xtpbImagePopupInit')) {
                return;
            }
            $this.magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                mainClass: 'mfp-img-mobile',
                image: { verticalFit: true },
                gallery: { enabled: true }
            });
            $this.data('xtpbImagePopupInit', true);
        });
    }

    initImagePopup($(document));

    $(document).on('rendered_addon', function(e, addon){
        var iframe;
        if (!addon || (addon.type !== 'addon' && addon.type !== 'inner_addon') || addon.name !== 'xtpb_image') {
            return;
        }
        iframe = window.frames['xtpb-builder-view'];
        if (iframe && iframe.window && iframe.window.document) {
            initImagePopup($(iframe.window.document));
        }
    });
});
