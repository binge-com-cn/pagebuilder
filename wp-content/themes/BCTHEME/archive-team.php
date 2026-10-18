<?php

/**
 * Template: 团队 分类列表
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-11-10 23:33:02
 */

//头部
get_header();

//Banner
get_template_part( 'template/banner', 'team' );

//面包屑导航+搜索框
get_template_part( 'template/archive', 'search' );
?>
<div class="page-section">
    <div class="container">
        <div class="page-wrapper clearfix">
            <div class="team-post-list">
                <?php
                //文章循环
                while(have_posts()){
                    the_post();
                    get_template_part( 'template/loop/list', 'team' );
                }                
                ?>


            </div>

            <?php
            //分页
            dahuzi_theme_pagination();
            ?>

        </div>
    </div>
</div>
<?php
//页脚
get_footer();
