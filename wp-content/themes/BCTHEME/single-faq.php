<?php

/**
 * Template: FAQ 文章页面
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-07-05 23:21:22
 */

//头部
get_header();

//Banner
get_template_part( 'template/banner', 'faq' );

//面包屑导航+搜索框
get_template_part( 'template/archive', 'search' );

//文章扩展设置
$single_date = wpjam_theme_get_setting('single_date');
$single_views = wpjam_theme_get_setting('single_views');
$single_indent = wpjam_theme_get_setting('single_indent');
?>
<div class="page-section">
    <div class="container">
        <div class="clearfix">
            <?php
            //faq侧边栏
            if (is_active_sidebar('widget_faq')){?>
                <div class="sidebar-widget <?php echo get_theme_sidebar_class();?>">
                <?php dynamic_sidebar('widget_faq');?>
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
                            //浏览量
                            if ( !empty($single_views) ){
                                echo '<span>'.__( '浏览量：', 'modular_theme' ).wpjam_get_post_views(get_the_ID()).'</span>';
                            }
                            //编辑文章
                            edit_post_link('[编辑文章]');
                            ?>
                        </p>
                    </div>
                    <div class="post-body<?php if ( !empty($single_indent) ){ echo ' text-indent';}?>">
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
//页脚
get_footer();