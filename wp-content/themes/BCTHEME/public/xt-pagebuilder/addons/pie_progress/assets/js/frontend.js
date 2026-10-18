jQuery(function($){ 'use strict';
    function initPieCharts($scope) {
        $scope.find('.xtpb-pie-chart').each(function() {
            var $this = $(this);
            if (!$this.easyPieChart) {
                return;
            }
            $this.easyPieChart({
                barColor: $this.data('barcolor'),
                trackColor: $this.data('trackcolor'),
                scaleColor: false,
                lineWidth: $this.data('width'),
                size: $this.data('size'),
                onStep: function (from, to, percent) {
                    $this.find('.xtpb-chart-percent > span').text(Math.round(percent) + '%');
                }
            });
            $this.off('inview');
        });
    }

    $(document).on('rendered_addon', function(e, addon){
        var iframe;
        if (!addon || (addon.type !== 'addon' && addon.type !== 'inner_addon') || addon.name !== 'xtpb_pie_progress') {
            return;
        }
        iframe = window.frames['xtpb-builder-view'];
        if (iframe && iframe.window && iframe.window.document) {
            initPieCharts($(iframe.window.document));
        }
    });

    $(document).on('inview', '.xtpb-pie-chart', function(event, visible) {
        var $this = $(this);
        if (!$this.easyPieChart || !visible) {
            return;
        }
        $this.easyPieChart({
            barColor: $this.data('barcolor'),
            trackColor: $this.data('trackcolor'),
            scaleColor: false,
            lineWidth: $this.data('width'),
            size: $this.data('size'),
            onStep: function (from, to, percent) {
                $this.find('.xtpb-chart-percent > span').text(Math.round(percent) + '%');
            }
        });
        $this.off('inview');
    });
});
