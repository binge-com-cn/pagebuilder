jQuery(function($){ 'use strict';
    $.fn.xtpbAnimateNumbers = function(stop, commas, duration, ease) {
        return this.each(function() {
            var $this = $(this);
            var start = parseInt($this.text().replace(/,/g, ''), 10);
            commas = (commas === undefined) ? true : commas;
            $({value: start}).animate({value: stop}, {
                duration: duration === undefined ? 1000 : duration,
                easing: ease === undefined ? 'swing' : ease,
                step: function() {
                    $this.text(Math.floor(this.value));
                    if (commas) {
                        $this.text($this.text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));
                    }
                },
                complete: function() {
                    if (parseInt($this.text(), 10) !== stop) {
                        $this.text(stop);
                        if (commas) {
                            $this.text($this.text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));
                        }
                    }
                }
            });
        });
    };

    function initCounters($scope) {
        $scope.find('.xtpb-counter-number').each(function() {
            var $this = $(this);
            $this.xtpbAnimateNumbers($this.data('digit'), false, $this.data('duration'), 'swing');
            $this.off('inview');
        });
    }

    $(document).on('rendered_addon', function(e, addon){
        var iframe;
        if (!addon || (addon.type !== 'addon' && addon.type !== 'inner_addon') || addon.name !== 'xtpb_animated_number') {
            return;
        }
        iframe = window.frames['xtpb-builder-view'];
        if (iframe && iframe.window && iframe.window.document) {
            initCounters($(iframe.window.document));
        }
    });

    $(document).on('inview', '.xtpb-counter-number', function(event, visible) {
        var $this = $(this);
        if (!visible) {
            return;
        }
        $this.xtpbAnimateNumbers($this.data('digit'), false, $this.data('duration'), 'swing');
        $this.off('inview');
    });
});
