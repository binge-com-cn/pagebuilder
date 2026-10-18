<?php
/*
Plugin Name: WPJAM 评论增强
Plugin URI: http://blog.wpjam.com/project/wpjam-comment/
Description: 1. 评论点赞，2. 评论置顶，3. 评论点赞排序。
Requires Plugins: wpjam-basic
Version: 6.5
Requires at least: 6.3
Tested up to: 6.5
Requires PHP: 7.4
Author: Denis
Author URI: http://blog.wpjam.com/
Update URI: http://blog.wpjam.com/project/wpjam-comment/
*/
add_action('plugins_loaded', function(){
	if(wp_installing() || !did_action('wpjam_loaded') || class_exists('WPJAM_Comment')){
		return;
	}

	include __DIR__.'/includes/class-comment.php';
	include __DIR__.'/includes/class-comment-type.php';

	include __DIR__.'/public/comment-setting.php';

	do_action('wpjam_comment_loaded');
});