jQuery(document).ready(function($){
    function getCurrentEditorPostId($trigger) {
        var postId = '';
        var $context = $trigger && $trigger.length ? $trigger.closest('[data-postid]') : $();

        if ($context.length) {
            postId = $context.attr('data-postid');
        }

        if (!postId) {
            postId = jQuery('.wppagebuilder-edit-button').attr('data-postid');
        }

        if (!postId) {
            postId = $('#post_ID').val();
        }

        return postId || '';
    }

    function refreshClassicEditorUI() {
        var editorId = 'content';

        if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
            tinymce.execCommand('mceRemoveEditor', false, editorId);
            tinymce.execCommand('mceAddEditor', false, editorId);
        }

        $(window).trigger('resize');
    }

    if (xtpb_admin_ajax.current_editor === 'xtpb_builder_activated'){
        jQuery("body").addClass("active-xtpb-editor");
    }else{
        jQuery("body").addClass("inactive-xtpb-editor");
    }

    $(document).on('click', '.edit-with-xtpb-builder', function (e) {
        e.preventDefault();
        var $that =  $(this);
        var warningText = $that.attr('data-warning') || '这将打开页面构建器编辑器；只有在保存了页面构建器内容后，前端页面才会切换为页面构建器输出。是否继续？';

        if (!window.confirm(warningText)) {
            return;
        }

        location.href = $that.attr('href');
    });

    $(document).on('click', '.use-default-editor', function (e) {
        e.preventDefault();
        var $that = $(this);
        var post_id = getCurrentEditorPostId($(this));
        var warningText = $that.attr('data-warning') || '切换后，你可以继续编辑默认编辑器中的内容；前端页面会在你重新保存文章后，才切换为显示默认编辑器中的内容。当前页面构建器内容不会自动转换。是否继续？';

        if (!window.confirm(warningText)) {
            return;
        }

        if (!post_id) {
            return;
        }

        jQuery.ajax({
            url : xtpb_admin_ajax.ajax_url,
            type : 'post',
            data : {
                action: 'xtpb_stage_editor_switch',
                post_id : post_id,
                xtpb_nonce : xtpb_admin_ajax.xtpb_nonce,
                pending_editor : 'xtpb_default_editor'
            },
            success : function( response ) {
                $('body').removeClass('active-xtpb-editor').addClass('inactive-xtpb-editor');
                $('.wppagebuilder-edit-button')
                    .removeClass('wppagebuilder-edit-hide-editor')
                    .addClass('wppagebuilder-edit-show-editor');
                $('#postdivrich').show();
                setTimeout(refreshClassicEditorUI, 0);
            }
        });
    });

    $(document).on('click', '#xtpb_clear_cache_btn', function(e){
        e.preventDefault();

        var $that = $(this);

        jQuery.ajax({
            url : xtpb_admin_ajax.ajax_url,
            type : 'post',
            data : {
                action: 'xtpb_clear_cache',
                xtpb_nonce : xtpb_admin_ajax.xtpb_nonce
            },
            beforeSend: function(){
                $that.addClass('updating-message');
            },
            success : function( response ) {
                $that.closest('label').find('.response-text').html('<span style="color: #228b22;">页面构建器缓存已清理。</span>');
            },
            complete: function(){
                $that.removeClass('updating-message');
            }
        });
    });

    function showRebuildPageAssetsMessage($trigger, message, isError) {
        var html = '<span style="color: ' + (isError ? '#b32d2e' : '#228b22') + ';">' + message + '</span>';
        var $response = $trigger.closest('label').find('.response-text');

        if ($response.length) {
            $response.html(html);
            return;
        }

        var $notice = $('#xtpb-rebuild-page-assets-notice');
        if (!$notice.length) {
            $notice = $('<div id="xtpb-rebuild-page-assets-notice" class="notice is-dismissible"><p></p></div>');
            $('#wpbody-content .wrap').first().prepend($notice);
        }

        $notice
            .removeClass('notice-success notice-error')
            .addClass(isError ? 'notice-error' : 'notice-success')
            .find('p')
            .html(message);
    }

    function rebuildPageAssetsBatch($trigger, offset, total, successCount, failedCount) {
        var limit = 20;

        $.ajax({
            url: xtpb_admin_ajax.ajax_url,
            type: 'post',
            data: {
                action: 'xtpb_rebuild_page_assets',
                xtpb_nonce: xtpb_admin_ajax.xtpb_nonce,
                offset: offset,
                limit: limit
            },
            success: function(response) {
                if (!response || !response.success) {
                    var message = response && response.data && response.data.message ? response.data.message : '页面资源重新生成失败。';
                    showRebuildPageAssetsMessage($trigger, message, true);
                    $trigger.removeClass('updating-message').prop('disabled', false);
                    return;
                }

                var data = response.data || {};
                var currentTotal = typeof data.total !== 'undefined' ? parseInt(data.total, 10) : total;
                var processed = typeof data.processed !== 'undefined' ? parseInt(data.processed, 10) : offset;
                var batchDone = typeof data.done !== 'undefined' ? parseInt(data.done, 10) : 0;
                var batchFailed = data.failed && data.failed.length ? data.failed.length : 0;
                var nextSuccessCount = successCount + batchDone;
                var nextFailedCount = failedCount + batchFailed;

                if (!currentTotal) {
                    showRebuildPageAssetsMessage($trigger, '没有找到使用页面构建器编辑的页面。', false);
                    return;
                }

                showRebuildPageAssetsMessage(
                    $trigger,
                    '正在重新生成页面资源：已处理 ' + Math.min(processed, currentTotal) + ' / ' + currentTotal + '。',
                    false
                );

                if (!data.finished) {
                    rebuildPageAssetsBatch($trigger, data.next_offset || processed, currentTotal, nextSuccessCount, nextFailedCount);
                    return;
                }

                showRebuildPageAssetsMessage(
                    $trigger,
                    '页面资源重新生成完成。成功 ' + nextSuccessCount + ' 个，失败 ' + nextFailedCount + ' 个。',
                    nextFailedCount > 0
                );
                $trigger.removeClass('updating-message').prop('disabled', false);
            },
            error: function() {
                showRebuildPageAssetsMessage($trigger, '页面资源重新生成失败，请稍后重试。', true);
                $trigger.removeClass('updating-message').prop('disabled', false);
            }
        });
    }

    $(document).on('click', '#xtpb_rebuild_page_assets_btn, #wp-admin-bar-xtpb-rebuild-page-assets a', function(e){
        e.preventDefault();

        var $that = $(this);
        if (!window.confirm('这将重新生成所有页面构建器页面的 CSS 和 JS 文件。是否继续？')) {
            return;
        }

        $that.addClass('updating-message').prop('disabled', true);
        showRebuildPageAssetsMessage($that, '正在准备重新生成页面资源...', false);
        rebuildPageAssetsBatch($that, 0, 0, 0, 0);
    });

    /**
     * Gutenberg compatibility
     */

    let xtpbGutenbergCompatibility = {
        observer: null,
        scheduleTimer: null,
        isBuilderActivated: function(){
            return xtpb_admin_ajax.current_editor === 'xtpb_builder_activated' || $('body').hasClass('currently-activated-editor-xtpb_builder_activated');
        },
        init : function(){
            let self = this;
            self.mountToolbarButtons();
            self.watchToolbarChanges();
        },
        mountToolbarButtons: function(attempt){
            let self = this;
            let tryCount = attempt || 0;

            self.addXTPBBtn();
            self.addSwitchTemplate();

            if (
                !self.isXTPBBtnMounted() ||
                !self.isSwitchTemplateMounted()
            ) {
                if (tryCount < 40) {
                    setTimeout(function(){
                        self.mountToolbarButtons(tryCount + 1);
                    }, 250);
                }
            }
        },
        getToolbarTarget: function(){
            return $('.editor-document-tools__left, .edit-post-header-toolbar').first();
        },
        isXTPBBtnMounted: function(){
            if (this.isBuilderActivated()) {
                return true;
            }
            return this.getToolbarTarget().find('.edit-with-xtpb-builder, .xtpb-back-to-gutenberg').length > 0;
        },
        isSwitchTemplateMounted: function(){
            return $('#xintheme-pagebuilder-switch-to-gutenberg').length > 0;
        },
        addXTPBBtn: function(){
            let btnHtmlWrap = $('#xtpb-edit-with-btn-in-gutenberg-toolbar');
            let $target = this.getToolbarTarget();

            if (this.isBuilderActivated() || !$target.length || !btnHtmlWrap.length || this.isXTPBBtnMounted()) {
                return;
            }

            $target.append(btnHtmlWrap.html());
        },
        addSwitchTemplate: function(){
            let $target = $('.editor-visual-editor').first();
            let $template = $('#xintheme-pagebuilder-to-gutenberg-switch-mode');

            if (!$target.length || !$template.length || this.isSwitchTemplateMounted()) {
                return;
            }

            $target.after($template.html());
        },
        scheduleMount: function(){
            let self = this;

            if (self.scheduleTimer) {
                clearTimeout(self.scheduleTimer);
            }

            self.scheduleTimer = setTimeout(function(){
                self.mountToolbarButtons();
            }, 50);
        },
        watchToolbarChanges: function(){
            let self = this;

            if (self.observer || typeof MutationObserver === 'undefined') {
                return;
            }

            self.observer = new MutationObserver(function(){
                self.scheduleMount();
            });

            self.observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    };
    xtpbGutenbergCompatibility.init();
    $(document).on('click', '.xtpb-back-to-gutenberg', function(e){
        e.preventDefault();

        var $that = $(this);
        var warningText = $that.attr('data-warning') || '切换后，你可以继续编辑 Gutenberg 内容；前端页面会在你重新保存文章后，才切换为显示 Gutenberg 编辑器中的内容。当前页面构建器内容不会自动转换。是否继续？';

        if (!window.confirm(warningText)) {
            return;
        }

        $that.hide();
        $('.edit-with-xtpb-builder').show();
        $('body').removeClass('currently-activated-editor-xtpb_builder_activated');

        var post_id = $('#post_ID').val();
        $.ajax({
            url : xtpb_admin_ajax.ajax_url,
            type : 'post',
            data : {
                action: 'xtpb_stage_editor_switch',
                post_id : post_id,
                xtpb_nonce : xtpb_admin_ajax.xtpb_nonce,
                pending_editor : 'gutenberg',
            },
        });
    });
});
