<?php

/**
 * Template: 普通文章页面
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-11-10 22:06:34
 */

//头部
get_header();

//当前页面ID
$current_page_id = get_the_ID();

//如果有设置特色图片 显示Banner图片
if( has_post_thumbnail() ){

	//图片遮罩层和文本颜色
	$banner_text_color = get_post_meta($current_page_id, 'banner_text_color', true);
	$banner_mask_color = get_post_meta($current_page_id, 'banner_mask_color', true);


	$text_color = $banner_text_color ? '--banner_text_color:'.$banner_text_color.';' : '';
	$mask_color = $banner_mask_color ? '--banner_mask_color:'.$banner_mask_color.';' : '';

	$banner_mask_style = ($mask_color || $text_color) ? 'style="'.$mask_color.$text_color.'"' : '';
	$banner_mask_class = $mask_color ? ' banner-mask' : '';

	echo '<div class="page-banner'.$banner_mask_class.'" '.$banner_mask_style.'>';
	echo '<img loading="lazy" src="'.get_the_post_thumbnail_url().'" alt="'.dahuzi_get_image_alt(get_the_post_thumbnail_url()).'" />';
	echo '<div class="banner-text">';
	echo '<div class="container">';
	echo '<h1>'.get_the_title().'</h1>';
	if(has_excerpt()){
		echo the_excerpt();
	}
	echo '</div>';
	echo '</div>';
	echo '</div>';
}

//面包屑导航+搜索框
get_template_part( 'template/archive', 'search' );

//页面菜单
$page_menu = get_post_meta($current_page_id, 'page_menu', true);
if (!empty($page_menu)) {
	echo '<div class="page-menu">';
	echo '<div class="container">';
	wp_nav_menu([
		'menu' => $page_menu,
		'container'     => false,
		'menu_class'    => 'nav-page-menu',
		'menu_id'       => 'nav-page-menu',
	]);
	echo '</div>';
	echo '</div>';
}
?>
<div class="page-section">
	<div class="container">
		<div class="clearfix">
			<?php
			//侧边栏
			$page_widgets = get_post_meta($current_page_id, 'page_widgets', true);

			if( $page_widgets && is_active_sidebar('widget-area-page-'.$current_page_id) ){
				echo '<div class="sidebar-widget '.get_theme_sidebar_class().'">';
				dynamic_sidebar('widget-area-page-'.$current_page_id);
				echo '</div>';
			}elseif( is_active_sidebar('widget_page') ){
				echo '<div class="sidebar-widget '.get_theme_sidebar_class().'">';
				dynamic_sidebar('widget_page');
				echo '</div>';
			}?>
			<div class="page-main">
				<div class="post-content">
					<?php
					//没有设置特色图片的时候 显示标题
					if( !has_post_thumbnail() ){?>
						<div class="post-title">
							<h1><?php the_title();?></h1>
						</div>
					<?php }?>
					<div class="post-body">
					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();
						the_content();
					endwhile; // End of the loop.?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();