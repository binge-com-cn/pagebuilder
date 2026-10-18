<?php

/**
 * Template: 分类列表样式-1
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-08-05 17:29:40
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-08-28 16:21:05
 */

//获取文章缩略图
$get_post_thumbnail_url = wpjam_get_post_thumbnail_url();

//图片Alt标签
$img_alt = get_the_title();
?>
<div class="post-item">
    <a href="<?php the_permalink(); ?>">
        <div class="img-cover">
        	<img loading="lazy" src="<?php echo $get_post_thumbnail_url;?>" alt="<?php echo $img_alt;?>" />
        </div>
        <div class="text">
            <h2><?php the_title();?></h2>
        </div>
    </a>
</div>