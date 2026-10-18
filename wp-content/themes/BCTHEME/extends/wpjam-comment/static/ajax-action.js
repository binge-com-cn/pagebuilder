jQuery(function($){
	$('body').on('click', '.post-action', function(e){
		e.preventDefault();

		$(this).wpjam_action(function(data){
			if(data.errcode != 0){
				alert(data.errmsg);
			}else{
				$(this).removeClass().addClass(data.class).data('data', data.data);
				$(this).find('i').removeClass().addClass(data.icon);
				$(this).find('span.post-action-count').html(data.count);

				$('body').trigger('wpjam_post_action_success', data);
			}
		});
	});

	$('body').on('click', '.comment-digg', function(e){
		e.preventDefault();

		$(this).wpjam_action(function(data){
			if(data.errcode != 0){
				alert(data.errmsg);
			}else{
				$(this).removeClass().addClass(data.class).data('data', data.data);
				$(this).find('.comment-digg-count').html(data.count);
				$(this).find('i').removeClass().addClass(data.icon);
			}
		});
	});
});