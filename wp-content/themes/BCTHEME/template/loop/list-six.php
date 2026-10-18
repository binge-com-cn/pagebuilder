<?php

/**
 * Template: Download 列表样式
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-08-05 17:29:40
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-08-28 16:21:39
 */

//获取文件下载地址
$download_url = get_post_meta(get_the_ID(), 'download_url', true);

//获取文章缩略图
$get_post_thumbnail_url = wpjam_get_post_thumbnail_url();

//图片Alt标签
if(!empty($get_post_thumbnail_url)){
	$img_alt = get_the_title();
}
?>

<div class="post-download">
	<?php
	if(!empty($get_post_thumbnail_url)){
		echo '<div class="download-img">';
		echo '<img loading="lazy" src="'.$get_post_thumbnail_url.'" alt="'.$img_alt.'">';
		echo '</div>';
	}?>
	<div class="download-title">
		<h2><?php the_title();?></h2>
		<?php the_excerpt();?>
	</div>
	<?php
	if(!empty($download_url)){
		echo '<div class="download-button">';
		echo '<a href="'.$download_url.'" target="_blank" rel="nofollow">';
		echo '<i class="ri-download-cloud-line"></i> '.__( '点击下载', 'modular_theme' );
		echo '</a>';
		echo '</div>';
		echo '<a class="download-link" href="'.$download_url.'" target="_blank" rel="nofollow"></a>';
	}?>
</div>