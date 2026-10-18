<?php

/**
 * Template: 搜索结果列表
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2023-12-25 11:31:55
 */

//头部
get_header();

//面包屑导航+搜索框
get_template_part( 'template/archive', 'search' );

$list_type = 'three';
?>
<div class="page-section">
	<div class="container">
		<div class="page-wrapper clearfix">
			<div class="page-main">
				<?php

				//文章列表
				echo '<div class="list-three mobile-list-one">';

				//文章循环
				while(have_posts()){
					the_post();
					get_template_part( 'template/loop/list', 'three' );
				}

				/*
				$the_query = new WP_Query( array(
					'post__not_in' => get_option( 'sticky_posts' ),
					'paged' => get_query_var('paged'),
					'cat' => $cat
				) );
				if ( $the_query->have_posts() ) :
					while ( $the_query->have_posts() ) : $the_query->the_post();
						get_template_part( 'template/list-item', $list_type );
					endwhile; //结束while
				//else:
				//	echo '<h3 style="padding-bottom:94px">抱歉，当前分类下暂无文章！</h3>';
				endif; //结束if
				*/
				
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
