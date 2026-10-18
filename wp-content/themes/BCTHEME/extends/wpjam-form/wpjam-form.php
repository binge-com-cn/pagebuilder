<?php
/*
Plugin Name: WPJAM 表单
Plugin URI: http://blog.wpjam.com/project/wpjam-form/
Description: WordPress 表单系统。
Requires Plugins: wpjam-basic
Version: 6.5
Requires at least: 6.3
Tested up to: 6.5
Requires PHP: 7.4
Author: Denis
Author URI: http://blog.wpjam.com/
*/

add_action('plugins_loaded', function(){
	if(wp_installing() || !did_action('wpjam_loaded') || class_exists('WPJAM_Form')){
		return;
	}

	wpjam_include('wpjam_comment_loaded', [__DIR__.'/public/form-post_type.php', __DIR__.'/public/form-submit.php']);
	wpjam_include(['wpjam_comment_loaded', 'wpjam_module_loaded'], __DIR__.'/public/form-module.php');
});
