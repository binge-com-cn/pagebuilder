jQuery(document).ready(function($){
    'use strict';

    function getPageId() {
        var bodyClass = $('body').attr('class') || '';
        var match = bodyClass.match(/\bxtpb-body-single-(\d+)\b/);
        return match ? match[1] : '';
    }

    function getAddonPageId($addon) {
        return ($addon && $addon.attr('data-page-id')) || getPageId();
    }

    function appendSpinner($addon) {
        $addon.append('<div class="xtpb-post-grid-spinner"><div class="xtpb-post-grid-spin"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>');
    }

    function removeSpinner($addon) {
        $addon.find('.xtpb-post-grid-spinner').remove();
    }

    function getResponseContent(response) {
        if (!response || !response.success || !response.data || !response.data.html) {
            return $();
        }

        var $response = $(response.data.html);
        return $response.hasClass('xtpb-posts-addon-content') ? $response : $response.filter('.xtpb-posts-addon-content');
    }

    $(document).on('click', '.xtpb-posts-addon-loadmore-btn', function(e){
        e.preventDefault();
        var selector = $(this);
        var paged = selector.attr('data-paged');
        var $addon = selector.closest('.xtpb-posts-addon-content');
        var page_id = getAddonPageId($addon);
        var addon_id = selector.closest('.xtpb-addon-node').attr('data-addon-id');

        $.ajax({
            url: xtpb_posts_addon.ajax_url,
            type: 'POST',
            data: {
                action: 'xtpb_posts_addon_load_more',
                'paged': paged,
                'page_id': page_id,
                'addon_id': addon_id,
                'nonce': xtpb_posts_addon.nonce,
            },
            beforeSend: function(){
                selector.prop('disabled', true).addClass('xtpb-posts-addon-loadmore-btn-loading');
                appendSpinner($addon);
            },
            success: function(response){
                var $responseContent = getResponseContent(response);
                var $currentGrid = $addon.find('.xtpb-addons-col').first();
                var $responseGrid = $responseContent.find('.xtpb-addons-col').first();
                var $responsePagination = $responseContent.find('.xtpb-posts-addon-pagination').first();

                if (!$responseContent.length) {
                    return;
                }

                $addon.find('.xtpb-posts-addon-pagination').remove();

                if ($currentGrid.length && $responseGrid.length) {
                    $currentGrid.append($responseGrid.children());
                }

                if ($responsePagination.length) {
                    $addon.append($responsePagination);
                }
            },
            error: function(jqXHR, String ){
                //
            },
            complete: function(){
                selector.prop('disabled', false).removeClass('xtpb-posts-addon-loadmore-btn-loading');
                removeSpinner($addon);
            }
        });

    });


    $(document).on('click', '.xtpb-posts-paginate-link', function(e){

        e.preventDefault();
        var selector = $(this);
        var paged = selector.attr('data-paged');
        var $addon = selector.closest('.xtpb-posts-addon-content');
        var page_id = getAddonPageId($addon);
        var addon_id = selector.closest('.xtpb-addon-node').attr('data-addon-id');

        if (paged < 1){
            paged = 1;
        }

        $.ajax({
            url: xtpb_posts_addon.ajax_url,
            type: 'POST',
            data: {
                action: 'xtpb_posts_addon_load_more',
                'paged': paged,
                'page_id': page_id,
                'addon_id': addon_id,
                'nonce': xtpb_posts_addon.nonce,
            },
            beforeSend: function(){
                appendSpinner($addon);
            },
            success: function(response){
                var $responseContent = getResponseContent(response);

                if ($responseContent.length) {
                    $addon.replaceWith($responseContent);
                }
            },
            error: function(jqXHR, String ){
                //
            },
            complete: function(){
                removeSpinner($addon);
            }
        });

    })



});
