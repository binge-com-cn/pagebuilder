jQuery(function($){
	$('.wpjam-form').each(function() {
		$(this).on('submit', function(e){
			e.preventDefault();

			if($(this).data('label')){
				alert($(this).data('label'));

				return false;
			}

			$(this).wpjam_submit(function(data){
				if(data.errcode){
					alert(data.errmsg);
				}else{
					if(data.redirect){
						window.location.href = data.redirect;
					}else{
						alert(data.msg);
					}
				}
			});
		});
	});
});