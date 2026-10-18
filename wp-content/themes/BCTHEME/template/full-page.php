<?php

/**
 * Template Name: 全宽页面（无侧边栏）
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-01-16 23:01:49
 */

//头部
get_header();

//如果有设置特色图片 显示Banner图片
if( has_post_thumbnail() ){
	echo '<div class="page-banner">';
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

?>
<div class="page-section">
	<div class="container">
		<div class="clearfix">
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