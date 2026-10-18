<?php

/**
 * Template: 分类列表样式-3
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-08-05 17:29:40
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-08-31 15:50:43
 */

//兼容筛选
if (isset($_POST['category_id'])) {
	$cat = intval($_POST['category_id']);
}

//根分类
$cat_id = get_category_root_id($cat);

//分类列表 文章缩略图
$list_three_thumb = get_term_meta($cat, 'list_three_thumb', true) ?? '';

if( !empty( $list_three_thumb ) ){
	$list_three_thumb = $list_three_thumb;
}elseif( !empty( $cat_id ) ){
	$list_three_thumb = get_term_meta($cat_id, 'list_three_thumb', true) ?? '';
}

if( is_tag() || is_search() ) {
	$list_three_thumb = true;
}

//获取文章缩略图
$get_post_thumbnail_url = wpjam_get_post_thumbnail_url();

//图片Alt标签
if(!empty($get_post_thumbnail_url)){
	$img_alt = get_the_title();
}
?>
<div class="post-item">
	<a href="<?php the_permalink();?>">
		<?php
		if(!empty($list_three_thumb) && !empty($get_post_thumbnail_url) ){
			echo '<div class="img-center">';
			echo '<img loading="lazy" src="'.$get_post_thumbnail_url.'" alt="'.$img_alt.'">';
			echo '</div>';
		}?>
		<div class="text">
			<h2><?php the_title();?></h2>
			<div class="time">
				<span><?php echo get_the_date();?></span>
			</div>
			<?php dahuzi_excerpt( 180, '...' );?>
		</div>
	</a>
</div>