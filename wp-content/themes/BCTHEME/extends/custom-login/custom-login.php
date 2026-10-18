<?php
/*
Plugin Name: 修改登录页面链接
Plugin URI: https://www.xintheme.com
Description: 自定义登录页面链接链接，并限制默认登录链接访问(/wp-admin，/admin，/login，/wp-login.php)
Version: 1.0
Author: 大胡子｜XinTheme
Author URI: https://www.xintheme.com/
*/

$custom_login_url = wpjam_get_setting('wpjam-theme', 'custom_login_url') ?: null;

if (empty($custom_login_url)) {
	return;
}

// 获取自定义登录路径
function get_dahuzi_login_path() {
	return sanitize_text_field(wpjam_get_setting('wpjam-theme', 'redirect_login_url') ?: 'dahuzi');
}

// 替换登录表单的 action 属性，指向自定义路径
function dahuzi_custom_login_form_action_url($url, $path, $scheme, $blog_id) {
	$custom_login_path = get_dahuzi_login_path();
	// 判断是否是登录页面
	if (strpos($path, 'wp-login.php') !== false) {
		return home_url('/'.$custom_login_path); // 自定义登录路径
	}
	return $url; // 其他路径保持不变
}
add_filter('site_url', 'dahuzi_custom_login_form_action_url', 10, 4);

// 修改找回密码链接，指向自定义路径
function dahuzi_custom_lostpassword_url($lostpassword_url) {
	$custom_login_path = get_dahuzi_login_path();
	return home_url('/'.$custom_login_path .'?action=lostpassword'); // 自定义找回密码路径
}
add_filter('lostpassword_url', 'dahuzi_custom_lostpassword_url');

// 修改注销链接，指向自定义路径
function dahuzi_custom_logout_url($logout_url) {
	$custom_login_path = get_dahuzi_login_path();
	return home_url('/'.$custom_login_path.'?action=logout'); // 自定义注销路径
}
add_filter('logout_url', 'dahuzi_custom_logout_url');

// 修改注册链接，指向自定义路径
function dahuzi_custom_register_url($register_url) {
	$custom_login_path = get_dahuzi_login_path(); // 获取自定义登录路径
	return home_url('/' . $custom_login_path . '?action=register'); // 自定义注册路径
}
add_filter('register_url', 'dahuzi_custom_register_url');


// 处理自定义登录路径和未登录用户访问限制
function dahuzi_custom_login_and_restrict_access() {
	$custom_login_path = get_dahuzi_login_path();
	$requested_url = $_SERVER['REQUEST_URI'];
	$custom_login_path = '/'.$custom_login_path; // 自定义登录路径
	$restricted_paths = ['/wp-admin', '/wp-login.php', '/admin', '/login']; // 需要限制的路径

	// 初始化
	$user_login = '';  // 确保变量初始化
	$error = '';       // 确保变量初始化

	// 直接允许 admin-ajax.php 请求
	if (strpos($requested_url, '/wp-admin/admin-ajax.php') !== false) {
		return; // 不干扰 admin-ajax.php 的请求
	}

	// 如果是访问自定义登录路径
	if ($requested_url === $custom_login_path) {
		require_once ABSPATH . 'wp-login.php'; // 加载默认登录页面
		exit;
	}

	// 处理注销操作
	if (isset($_GET['action']) && $_GET['action'] === 'logout') {
		wp_logout(); // 执行注销操作
		wp_redirect(home_url($custom_login_path)); // 注销后重定向到自定义登录页面
		exit;
	}

	// 处理注册和找回密码操作
	if (isset($_GET['action']) && in_array($_GET['action'], ['register', 'lostpassword'])) {
		// 加载 WordPress 注册或找回密码页面
		require_once ABSPATH . 'wp-login.php';
		exit;
	}

	// 如果访问受限路径且未登录，返回 404
	foreach ($restricted_paths as $path) {
		if (strpos($requested_url, $path) !== false && !is_user_logged_in()) {
			status_header(404); // 设置 HTTP 状态为 404
			nocache_headers();   // 清除缓存头

			// 告诉 WordPress 当前是 404 页面
			global $wp_query;
			$wp_query->set_404();
			$wp_query->is_404 = true;

			// 加载 404 模板
			include(get_query_template('404'));
			exit; // 停止进一步执行
		}
	}

}
add_action('init', 'dahuzi_custom_login_and_restrict_access');

