<?php

/**
 * Template: 侧边栏
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-06-12 09:07:08
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-07-05 23:10:31
 */

if (function_exists('dynamic_sidebar')) {
	echo '<div class="sidebar-widget '.get_theme_sidebar_class().'">';
	if (is_single()) {
		dynamic_sidebar('widget_post');
	} elseif (is_page()) {
		dynamic_sidebar('widget_page');
	} elseif (is_category()) {
		dynamic_sidebar('widget_category');
	} else {
		dynamic_sidebar('widget_other');
	}
	echo '</div>';
}
?>