<?php
while(have_posts()){ the_post(); 

	$post_id	= get_the_ID();

	if(!is_user_logged_in()){
		if(get_option('comment_registration')){
			wp_redirect(wp_login_url(get_permalink($post_id)));
			exit;
		}

		if(is_weixin() && did_action('weixin_loaded')){
			$openid	= weixin_get_current_openid();

			if(is_wp_error($openid)){
				weixin_oauth_request();
			}
		}
	}

	$form	= wpjam_get_post_object($post_id);
	$status	= $form->get_status('array');
	$label	= $status[0] == 1 ? '' : $status[1];

	if(!$form->get_items()){
		wp_die('表单未定字段');
	}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	
	<title><?php the_title(); ?></title>
	<link rel="stylesheet" href="<?php echo wpjam_url(__DIR__);?>/style.css">
	<script type="text/javascript" src="<?php echo wpjam_get_static_cdn(); ?>/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript">var ajaxurl = "<?php echo admin_url('admin-ajax.php');?>";</script>
	<script type="text/javascript" src="<?php echo plugin_dir_url(''); ?>wpjam-basic/static/ajax.js"></script>
	<script type="text/javascript" src="<?php echo wpjam_url(__DIR__);?>/script.js"></script>

	<?php if($form->background){?><style type="text/css">
	html body{
		background-image: url(<?php echo $form->background; ?>);
		background-position: top center;
		background-attachment: fixed;
		background-repeat: repeat;
		height:100%;
	}
	</style><?php } ?>
</head>
<body>
<div class="entry-container-inner">
	<form class="wpjam-form center with-shadow" enctype="multipart/form-data" method="post" <?php echo wpjam_get_ajax_data_attr('form-submit', ['form_id'=>$post_id]).' data-label="'.$label.'"'; ?>>
		<div class="form-header">
			<?php if(has_post_thumbnail()){ ?><div class="banner header-type-image">
				<div class="banner-img">
					<img class="header-image" src="<?php echo wpjam_get_post_thumbnail_url($post_id, [1400]);?>">
				</div>
			</div><?php } ?>
			
			<div class="container-fluid">
				<div class="row">
					<h1 class="form-title"><?php echo $form->title; ?></h1>
					<div class="form-description collapse-details"><?php echo wpautop($form->excerpt); ?></div>
				</div>
			</div>
		</div>
		
		<div class="form-content container-fluid">
			<div class="row">
				<div class="fields clearfix">
				<?php $form->render_fields(); ?>
				</div>
				<div class="field submit-field col-md-12 text-center clearfix">
					<input type="submit" name="commit" value="提交" class="submit gd-btn gd-btn-primary-solid with-shadow">
				</div>
			</div>
		</div>
	</form>
	
	<footer class="published text-center">
		<div class="powered-by light"><p><span>WPJAM</span></p></div>
	</footer>
</div>
</body>
</html><?php } ?>