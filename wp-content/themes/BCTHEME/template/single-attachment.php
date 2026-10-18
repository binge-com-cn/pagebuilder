<?php

/**
 * Template: 附件页面
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-07-05 23:21:44
 */
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
					</div>
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