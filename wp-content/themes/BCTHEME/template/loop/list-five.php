<?php

/**
 * Template: FAQ 列表样式
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-08-05 17:29:40
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2023-12-18 17:27:50
 */

?>
<div class="post-accordion">
	<div class="accordion-title">
		<h2><?php the_title();?></h2>
		<i class="ri-arrow-down-s-line"></i>
	</div>
	<div class="accordion-desc">
		<?php the_content();?>
	</div>
</div>