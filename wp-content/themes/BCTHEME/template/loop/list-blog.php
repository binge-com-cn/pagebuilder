<?php

/**
 * Template: 分类列表样式-blog
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-08-05 17:29:40
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2023-09-02 10:20:05
 */

// 获取文章缩略图和 ALT 标签
$post_thumbnail_url = wpjam_get_post_thumbnail_url();
$img_alt = esc_attr(get_the_title($post));

if( is_category() ){
	$module_content['post_category'] = get_term_meta($cat, 'post_category', true);
	$module_content['post_date'] = get_term_meta($cat, 'post_date', true);
}

?>
<article class="post-item">
	<figure class="post-image">
		<a href="<?php the_permalink($post); ?>">
			<img loading="lazy" src="<?php echo $post_thumbnail_url; ?>" alt="<?php echo $img_alt; ?>">
		</a>
	</figure>
	<div class="post-text">
		<?php if (!empty($module_content['post_category']) || !empty($module_content['post_date'])): ?>
		<div class="post-meta">
			<?php 
			// 显示文章分类
			if (!empty($module_content['post_category'])) {
				$categories = get_the_category($post->ID);
				if (!empty($categories)) {
					$first_category = $categories[0];
					printf(
						'<a class="post-category" href="%s">%s</a>',
						esc_url(get_category_link($first_category->term_id)),
						esc_html($first_category->name)
					);
				}
			}

			// 显示发布日期
			if (!empty($module_content['post_date'])): ?>
			<div class="post-date">
				<span><?php echo esc_html(get_the_date('', $post)); ?></span>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<a class="post-title" href="<?php the_permalink($post); ?>">
			<h3><?php echo get_the_title($post); ?></h3>
		</a>
		<div class="post-excerpt">
			<?php dahuzi_excerpt(200, '...'); ?>
		</div>
	</div>
</article>