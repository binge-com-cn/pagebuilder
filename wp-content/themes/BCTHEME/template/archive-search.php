<?php

/**
 * Template: 面包屑导航+搜索框
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-08-05 17:29:40
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-08-22 17:00:34
 */
?>
<div class="breadcrumb-search">
	<div class="container">
		<?php echo get_breadcrumbs();?>
		<?php
		$remove_search = wpjam_theme_get_setting('remove_search');
		if( empty($remove_search) ){?>
		<div class="search-form">
			<form role="search" action="<?php echo esc_url(home_url('/')); ?>">
				<input name="s" type="text" placeholder="<?php echo esc_html_e( '输入关键词进行搜索...', 'modular_theme' );?>">
				<button type="submit"><i class="ri-search-line"></i></button>
			</form>
		</div>
		<?php }?>
	</div>
</div>