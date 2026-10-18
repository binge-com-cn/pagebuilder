<?php

/**
 * Template: 分类列表
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-08-27 22:47:08
 */

//头部
get_header();

//面包屑导航+搜索框
get_template_part( 'template/archive', 'search' );
?>
<div class="page-section">
	<div class="container">
		<div class="page-wrapper clearfix">
			<?php
			//侧边栏
			if( is_active_sidebar('widget_category') ){
				echo '<div class="sidebar-widget '.get_theme_sidebar_class().'">';
				dynamic_sidebar('widget_category');
				echo '</div>';
			}?>

			<div class="page-main">
				<?php

				//文章列表
				echo '<div class="list-three mobile-list-three">';

				//文章循环
				while(have_posts()){
					the_post();
					get_template_part( 'template/loop/list', 'three' );
				}
				
				?>

				</div>
				<?php
				//分页
				dahuzi_theme_pagination();?>
			</div>
		</div>
	</div>
</div>
<?php
//页脚
get_footer();
