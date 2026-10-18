<?php

/**
 * Template: FAQ 分类列表
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-07-05 23:20:35
 */

//分类列表样式
$list_type = 'five';

//头部
get_header();

//Banner
get_template_part( 'template/banner', 'faq' );

//面包屑导航+搜索框
get_template_part( 'template/archive', 'search' );
?>
<div class="page-section">
    <div class="container">
        <div class="page-wrapper clearfix">
            <?php
            //faq侧边栏
            if( is_active_sidebar('widget_faq') ){
                echo '<div class="sidebar-widget '.get_theme_sidebar_class().'">';
                dynamic_sidebar('widget_faq');
                echo '</div>';
            }?>
            <div class="page-main">
                <?php
                //文章列表
                echo '<div class="list-'.$list_type.'">';

                //文章循环
                while(have_posts()){
                    the_post();
                    get_template_part( 'template/loop/list', $list_type );
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
