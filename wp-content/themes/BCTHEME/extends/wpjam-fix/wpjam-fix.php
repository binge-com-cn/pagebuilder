<?php
add_filter('option_wpjam-extends', function($value){
	$value	= is_array($value) ? $value : [];

	return array_merge($value, [
		'related-posts'		=> 0,
		'mobile-theme'		=> 0,
		'wpjam-postviews'	=> 1
	]);
});

add_filter('option_wpjam-basic', function ($value){
	$value	= is_array($value) ? $value : [];

	$value['excerpt_optimization']	= $value['excerpt_optimization'] ?? 1;
	$value['excerpt_length']		= $value['excerpt_length'] ?? 115;

	return $value;
});

add_filter('option_wpjam-custom', function ($value){
	$value	= is_array($value) ? $value :[];
	$options = get_option('theme_info_options', []);
	$value['admin_footer']	= 'Powered by <a href="'.esc_url($options['author_uri'] ?? 'https://www.xintheme.com/theme/188027.html').'" target="_blank">'.esc_html($options['author'] ?? 'XinTheme 新主题 + WordPress果酱').'</a>';

	return $value;
});

add_filter('option_wpjam-thumbnail', function($value){
	$value	= is_array($value) ? $value : [];

	return array_merge($value, [
		'auto'					=> true,
		'post_thumbnail_orders'	=> [['type'=>'post_meta','post_meta'=>'header_img'],['type'=>'first'],]
	]);
});

if(is_admin()){
	add_filter('pre_transient_wpjam_basic_verify', '__return_true');
	add_filter('wpjam_option_setting_sections', function($sections, $name){
		if($name == 'wpjam-extends'){
			foreach(['related-posts', 'mobile-theme', 'wpjam-postviews'] as $extend){
				$sections['wpjam-extends']['fields'][$extend]['class']	= 'disabled';
			}
		}elseif($name == 'wpjam-custom'){
			$sections['admin']['fields']['admin_footer']['class']	= 'disabled';
		}elseif($name == 'wpjam-thumbnail'){
			$fields	= &$sections['wpjam-thumbnail']['fields'];

			$fields['auto']['class']	= 'disabled';

			unset($fields['term_set']);
			unset($fields['post_set']);
		}

		return $sections;
	}, 99, 2);
}

add_action('init', function(){
	foreach(['form_mail'=>'form_mail', 'thank_url'=>'form_redirect'] as $theme_key => $form_key){
		if(wpjam_get_setting('wpjam-comment', $form_key) === null){
			wpjam_update_setting('wpjam-comment', $form_key, (wpjam_theme_get_setting($theme_key) ?: ''));
		}
	}
});

