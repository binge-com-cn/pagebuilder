<?php

/**
 * Template: 普通文章页面
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-08-25 16:12:38
 */

//Banner
$single_banner = wpjam_theme_get_setting('single_banner');
if ( !empty($single_banner) ){
	get_template_part( 'template/archive', 'banner' );
}

//文章扩展设置
$single_date = wpjam_theme_get_setting('single_date');
$single_category = wpjam_theme_get_setting('single_category');
$single_views = wpjam_theme_get_setting('single_views');
$single_indent = wpjam_theme_get_setting('single_indent');

//面包屑导航+搜索框
get_template_part( 'template/archive', 'search' );

?>
<div class="page-section">
	<div class="container">
		<div class="clearfix">
			<?php
			//侧边栏
			if (is_active_sidebar('widget_post')){?>
				<div class="sidebar-widget <?php echo get_theme_sidebar_class();?>">
				<?php dynamic_sidebar('widget_post');?>
				</div>
			<?php }?>

			<div class="page-main">
				<div class="post-content">
					<div class="post-title">
						<h1><?php the_title();?></h1>
						<p>
							<?php
							//发布时间
							if ( !empty($single_date) ){
								echo '<span>'.__( '发布时间：', 'modular_theme' ).get_the_date().'</span>';
							}
							//分类
							if ( !empty($single_category) ){?>
								<span><?php esc_html_e( '分类：', 'modular_theme' );?><?php the_category('、');?></span>
							<?php }
							//浏览量
							if ( !empty($single_views) ){
								echo '<span>'.__( '浏览量：', 'modular_theme' ).wpjam_get_post_views(get_the_ID()).'</span>';
							}
							//编辑文章
							edit_post_link('[编辑文章]');
							?>
						</p>
					</div>
					<?php
					// 判断是否显示摘要
					$single_excerpt = wpjam_theme_get_setting('single_excerpt');
					if (has_excerpt() && !empty($single_excerpt) ){
						echo '<div class="post-desc">';
							echo '<p style="font-weight:600">'.__( '摘要：', 'modular_theme' ).'</p>';
							the_excerpt();
						echo '</div>';
					}?>
					<div class="post-body<?php if ( !empty($single_indent) ){ echo ' text-indent';}?>">
					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();
						the_content();
					endwhile; // End of the loop.?>
					</div>
					<div class="post-tags">
						<?php the_tags('<strong>'.__( '标签', 'modular_theme' ).'</strong>：', ' , ', ''); ?>
					</div>
					<?php echo dahuzi_post_share(); //文章分享?>
				</div>

				<div class="previous-next">
					<ul>
					<?php
					//获取同分类下的上一篇 下一篇
					$categories = get_the_category();
					$categoryIDS = array();
					foreach ($categories as $category) {
					array_push($categoryIDS, $category->term_id);
					}
					$categoryIDS = implode(",", $categoryIDS);

					$prev_post = get_previous_post( $categoryIDS );
					$next_post = get_next_post( $categoryIDS );

					//上一篇
					if(!empty($prev_post)):?>
						<li>
							<span><?php esc_html_e( '上一篇：', 'modular_theme' );?></span>
							<a href="<?php echo get_permalink($prev_post->ID);?>">
								<em><?php esc_html_e( '查看详情', 'modular_theme' );?> +</em><?php echo $prev_post->post_title;?>
							</a>
						</li>
					<?php
					endif;

					//下一篇
					if(!empty($next_post)):?>
						<li>
							<span><?php esc_html_e( '下一篇：', 'modular_theme' );?></span>
							<a href="<?php echo get_permalink($next_post->ID);?>">
								<em><?php esc_html_e( '查看详情', 'modular_theme' );?> +</em><?php echo $next_post->post_title;?>
							</a>
						</li>
					<?php endif;?>
					</ul>
				</div>
				<?php
				//自定义相关推荐
				$single_related = wpjam_theme_get_setting('single_related');
				$related_post = get_post_meta(get_the_ID(), 'related_post', true);

				if ( !empty($single_related) && !empty($related_post) ){

					// 获取指定 ID 的文章
					$related = array(
						'post__in' => $related_post,
						'orderby' => 'post__in', // 按照指定 ID 的顺序显示
					);

					$related_query = wpjam_query($related);
				}else{
					//默认相关推荐
					$related_query = wpjam_get_related_posts_query(10);
				}

				if ($related_query->have_posts()) { 
					$post_count = 0; // 初始化文章计数器
				?>
				<div class="post-related">
					<div class="related-title">
						<?php
						$text_single_related = wpjam_theme_get_setting('text_single_related');
						$related_title = !empty($text_single_related) ? $text_single_related : '相关推荐';
						echo '<h3>' . __($related_title, 'modular_theme') . '</h3>';
						?>
					</div>
					<div class="related-list">
					<?php
					while ($related_query->have_posts()) {
						$related_query->the_post(); 
						if ($post_count % 5 === 0) {
							// 在每5篇文章后开始一个新的<ul>
							if ($post_count !== 0) {
								echo '</ul>';
							}
							echo '<ul>';
						}
						?>
						<li>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							<span><?php echo get_the_date(); ?></span>
						</li>
					<?php 
						$post_count++;
					} 
						echo '</ul>'; // 确保最后一个<ul>闭合
					?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>