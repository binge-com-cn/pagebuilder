<?php
//禁用评论的邮件通知
add_filter( 'notify_post_author', '__return_false' );
add_filter( 'notify_moderator', '__return_false' );

//移除后台菜单
function remove_comments_menu() {
	//移除「评论」主菜单
	remove_menu_page('edit-comments.php');
	//移除评论子菜单项
	remove_submenu_page('edit.php', 'post-comments');
}
add_action('admin_menu', 'remove_comments_menu');

//禁用评论功能
function disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('init', 'disable_comments_post_types_support');

//移除文章编辑页面中的评论选项卡
function remove_comments_meta_box() {
	remove_meta_box('commentsdiv', 'post', 'normal'); //对于文章
	remove_meta_box('commentsdiv', 'page', 'normal'); //对于页面
}
add_action('admin_menu', 'remove_comments_meta_box');

//移除 WordPress 后台管理栏（Admin Bar）中的「评论」菜单项
function remove_admin_bar_comments() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments'); //移除评论菜单项
}
add_action('wp_before_admin_bar_render', 'remove_admin_bar_comments');

//禁止提交评论（防止被刷评论）
function disable_comment_post() {
    if (is_singular() && !is_admin()) {
        wp_die(__('评论功能已被禁用。')); // 显示提示信息并阻止评论提交
    }
}
add_action('pre_comment_on_post', 'disable_comment_post');
