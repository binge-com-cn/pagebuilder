<?php

/**
 * Template: 团队 文章页面
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-11-10 21:35:46
 */

//头部
get_header();

//Banner
get_template_part( 'template/banner', 'team' );

//面包屑导航+搜索框
get_template_part( 'template/archive', 'search' );

//成员信息
$team_position = get_post_meta(get_the_ID(), 'team_position', true);
$team_contact = get_post_meta(get_the_ID(), 'team_contact', true);

//获取文章缩略图
$get_post_thumbnail_url = wpjam_get_post_thumbnail_url();

//更多成员
$more_team = wpjam_theme_get_setting('more_team');
?>
<div class="page-section">
    <div class="container">
        <div class="clearfix">
            <div class="team-single">
                <div class="team-img">
                    <?php
                    //缩略图
                    if(!empty($get_post_thumbnail_url)){
                        echo '<img loading="lazy" src="'.$get_post_thumbnail_url.'" alt="'.get_the_title().'">';
                    }
                    ?>
                </div>
                <div class="team-details post-content">
                    <div class="team-title">
                        <h1><?php the_title();?></h1>
                        <?php
                        //职位
                        if(!empty($team_position)){
                            echo '<span>'.$team_position.'</span>';
                        }
                        ?>
                    </div>
                    <div class="team-about post-body">
                        <?php
                        /* Start the Loop */
                        while ( have_posts() ) : the_post();
                            the_content();
                        endwhile; // End of the loop.


                        //联系方式
                        if( !empty($team_contact) ){
                            echo '<div class="social-icons">';
                            $team_contact = array_filter($team_contact, function($team_contact){

                                if( $team_contact['team_contact_type'] == 'link' ){
                                    //跳转链接
                                    echo '<a href="' . esc_url($team_contact['team_contact_url'] ?? '#') . '" target="_blank" rel="nofollow">';
                                    echo $team_contact['team_contact_icon'] ?? '';
                                    echo '</a>';
                                }elseif( $team_contact['team_contact_type'] == 'img' ){
                                    //弹出图片
                                    $img_id = substr(uniqid(mt_rand(), true), 0, 6);
                                    echo '<a data-fancybox href="#a'.$img_id.'">';
                                    echo $team_contact['team_contact_icon'] ?? '';
                                    echo '</a>';

                                    echo '<div id="a'.$img_id.'" style="display:none;">';
                                    echo '<img loading="lazy" src="'.$team_contact['team_contact_img'].'" alt="">';
                                    echo '</div>';
                                }elseif( $team_contact['team_contact_type'] == 'form' ){                            
                                    //弹出表单
                                    $fancybox_id = substr(uniqid(mt_rand(), true), 0, 6);
                                    echo '<a data-fancybox href="#a'.$fancybox_id.'">';
                                    echo $team_contact['team_contact_icon'] ?? '';
                                    echo '</a>';

                                    echo '<div id="a'.$fancybox_id.'" class="fancybox-form" style="display:none;">';
                                    $form_title = $team_contact['team_contact_form_title'];//表单标题
                                    $form_id = $team_contact['team_contact_form'];//表单ID
                                    wpjam_render_form($form_id, $form_title);
                                    echo '</div>';
                                }elseif( $team_contact['team_contact_type'] == 'copy' ){
                                    //一键复制
                                    $copy_id = substr(uniqid(mt_rand(), true), 0, 6);
                                    $data_settings    = wp_json_encode([
                                        'id'            => 's'.$copy_id,
                                        'copy'          => $team_contact['team_contact_copy'],
                                        'copy_tip'      => str_replace(' ', '&nbsp;', $team_contact['team_contact_copy_tip']), // 将空格替换为 &nbsp;
                                        'open_wechat'   => $team_contact['team_contact_open_wechat'] ?? '',
                                    ]);
                                    // 将 JSON 数据转义为 HTML 属性
                                    $data_settings = esc_attr($data_settings);

                                    echo '<a href="javaScript:;" id="s'.$copy_id.'" class="copy-btn" data-clipboard-text="'.$team_contact['team_contact_copy'].'" data-settings='.$data_settings.'>';
                                    echo $team_contact['team_contact_icon'] ?? '';
                                    echo '</a>';
                                }elseif( $team_contact['team_contact_type'] == 'phone' ){
                                    //拨打电话
                                    echo '<a href="tel:'.$team_contact['team_contact_phone'].'">';
                                    echo $team_contact['team_contact_icon'] ?? '';
                                    echo '</a>';
                                }

                            });
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <?php
            //更多成员
            if(!empty($more_team)){?>
            <div class="related-team-list">
                <?php
                //更多成员
                $more_team_title = wpjam_theme_get_setting('more_team_title');
                if(!empty($more_team_title)){
                    echo '<div class="related-team-title">';
                    echo '<h3>'.$more_team_title.'</h3>';
                    echo '</div>';
                }?>
                <div class="related-team-wrapper team-post-list swiper" data-uc-swiper="items: 1; sets: 1; loop: true; autoplay: 5000; dots: .swiper-pagination;" data-uc-swiper-l="items:4; sets: 4;">
                    <div class="swiper-wrapper">
                        <?php
                        // 获取当前文章的 ID
                        $current_post_id = get_the_ID();

                        // 设置查询参数
                        $args = array(
                            'post_type'      => 'team',         // 自定义文章类型
                            'posts_per_page' => -1,              // 获取的文章数量
                            'post__not_in'   => array($current_post_id),  // 排除当前文章
                            'orderby'        => 'date',         // 按发布日期排序
                            'order'          => 'DESC'          // 最新文章优先
                        );

                        // 执行查询
                        $team_query = new WP_Query($args);

                        if ($team_query->have_posts()) :
                            while ($team_query->have_posts()) : $team_query->the_post();
                                echo '<div class="swiper-slide">';
                                get_template_part( 'template/loop/list', 'team' );
                                echo '</div>';
                            endwhile;
                        endif;

                        // 重置查询
                        wp_reset_postdata();
                        ?>
                    </div>
                    <div class="swiper-pagination text-primary m-0 justify-center"></div>
                </div>
            </div>
            <?php }?>

        </div>
    </div>
</div>
<?php
//页脚
get_footer();