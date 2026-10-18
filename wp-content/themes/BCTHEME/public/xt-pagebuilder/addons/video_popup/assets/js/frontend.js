jQuery(function($){ 'use strict';
    function initVideoPopup($scope, jq) {
        jq = jq || $;

        if (typeof jq.fn.magnificPopup === 'undefined') {
            return;
        }

        $scope.find('.xtpb-video-popup').each(function() {
            var $this = jq(this);
            if (this.getAttribute('data-xtpb-video-popup-init') === '1') {
                return;
            }
            $this.magnificPopup({
                type: 'iframe',
                rtl: true,
                mainClass: 'mfp-fade',
                removalDelay: 300,
                preloader: false,
                fixedContentPos: false
            });
            this.setAttribute('data-xtpb-video-popup-init', '1');
        });
    }

    initVideoPopup($(document), $);

    $(document).on('rendered_addon', function(e, addon){
        var iframe, iframeJQuery;
        if (!addon || (addon.type !== 'addon' && addon.type !== 'inner_addon') || addon.name !== 'xtpb_video_popup') {
            return;
        }
        iframe = window.frames['xtpb-builder-view'];
        if (iframe && iframe.window && iframe.window.document) {
            iframeJQuery = iframe.window.jQuery || $;
            initVideoPopup(iframeJQuery(iframe.window.document), iframeJQuery);
        }
    });
});
