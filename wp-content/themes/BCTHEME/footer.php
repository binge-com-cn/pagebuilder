<?php
// 页脚配色
$footer_color     = wpjam_theme_get_setting('footer_color');
$footer_img       = wpjam_theme_get_setting('footer_img');
$footer_style     = '';

if ($footer_color || $footer_img) {
    $background_color   = $footer_color ? '--footer-bg-color:' . $footer_color . ';' : '';
    $background_img     = $footer_img ? 'background: url(' . $footer_img . ') no-repeat center 0 / cover;' : '';
    $footer_style       = $background_color || $background_img ? ' style="' . $background_color . $background_img . '"' : '';
}
?>

        <div class="footer bg_cover"<?php echo $footer_style;?>>
            <?php
            //页脚横幅
            $show_footer_banner = wpjam_theme_get_setting('show_footer_banner');
            $footer_b_title = wpjam_theme_get_setting('footer_b_title');
            $footer_b_desc = wpjam_theme_get_setting('footer_b_desc');
            $footer_b_btn_type = wpjam_theme_get_setting('footer_b_btn_type');

            if( !empty($show_footer_banner) ){
            ?>
            <div class="footer-banner">
                <div class="container">
                    <div class="footer-b-wrapper">
                        <?php
                        // 文本区域，如果不显示按钮，文本区域宽度100%
                        $footer_b_btn_style = ($footer_b_btn_type === 'none') ? ' style="width:100%"' : '';
                        echo '<div class="footer-b-l"'.$footer_b_btn_style.'>';
                            if( !empty($footer_b_title) ){
                                echo '<h2>'.$footer_b_title.'</h2>';
                            }
                            if( !empty($footer_b_desc) ){
                                echo '<p>'.$footer_b_desc.'</p>';
                            }
                        echo '</div>';

                        //按钮
                        if( $footer_b_btn_type != 'none' ){
                            echo '<div class="footer-b-r">';
                            if ( $footer_b_btn_type === 'btn' ) {
                                $footer_b_btn_text = wpjam_theme_get_setting('footer_b_btn_text') ?: '联系我们';
                                $footer_b_btn_url  = wpjam_theme_get_setting('footer_b_btn_url') ?: '#';
                                echo sprintf('<a href="%s" target="_blank">%s</a>', esc_url($footer_b_btn_url), esc_html($footer_b_btn_text));
                            }

                            if( $footer_b_btn_type === 'search' ){
                                echo '<form action="'.esc_url( home_url('/') ).'">';
                                echo '<input name="s" type="text" placeholder="'.__( '输入关键词进行搜索...', 'modular_theme' ).'">';
                                echo '<button type="submit"><i class="ri-search-line"></i></button>';
                                echo '</form>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php }


            //页脚小工具
            if( is_active_sidebar('widget_footer') ){
                echo '<div class="footer-widget">';
                echo '<div class="container">';
                echo '<div class="widget-box">';
                dynamic_sidebar('widget_footer');
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }?>
            <div class="footer-copy">
                <div class="container">
                    <?php
                    $footer_link = wpjam_theme_get_setting('footer_link');
                    $link_title = wpjam_theme_get_setting('link_title');
                    $mb_remove_link = wpjam_theme_get_setting('mb_remove_footer_link');
                    if ((is_front_page() || is_home()) && (!empty($footer_link) || !empty($link_title)) && (!wp_is_mobile() || empty($mb_remove_link))) {
                            echo '<div class="footer-link">';
                            echo '<ul>';
                            if( !empty($link_title) ){
                                echo '<li class="link-title">'.$link_title.'</li>';
                            }
                            if( !empty($footer_link) ){
                                $footer_link = array_filter($footer_link, function($footer_link){
                                    echo '<li>';
                                    echo '<a href="'.$footer_link['link_url'].'" target="_blank">'.$footer_link['link_text'].'</a>';
                                    echo '</li>';
                                });
                            }
                            echo '</ul>';
                            echo '</div>';

                    }?>
                    <p>
                        <?php
                        $footer_icp = wpjam_theme_get_setting('footer_icp');
                        $footer_gaba = wpjam_theme_get_setting('footer_gaba');
                        $gabah = preg_replace('/\D/', '', $footer_gaba);
                        $footer_copyright = wpjam_theme_get_setting('footer_copyright') ?: 'Copyright © ' . date('Y') . ', ' . get_bloginfo('name') . '&nbsp;All Rights Reserved.';
                        $theme_link = wpjam_theme_get_setting('xintheme_link') ? '' : '&nbsp;&nbsp;Theme By&nbsp;<a href="http://www.xintheme.com" target="_blank">XinTheme</a>';

                        //版权信息
                        echo $footer_copyright;

                        //ICP备案
                        if ( !empty($footer_icp) ){
                            echo '&nbsp;&nbsp;<a rel="nofollow" target="_blank" href="http://beian.miit.gov.cn/">'.$footer_icp.'</a>';
                        }

                        //公安备案
                        if ( !empty($footer_gaba) ){
                            echo '&nbsp;&nbsp;<a rel="nofollow" target="_blank" href="https://www.beian.gov.cn/portal/registerSystemInfo?recordcode='.$gabah.'">';
                            echo '<img loading="lazy" class="gaba" alt="公安备案" src="'.get_template_directory_uri().'/static/images/gaba.png">';
                            echo $footer_gaba;
                            echo '</a>';

                        }

                        //主题链接
                        echo $theme_link;
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <?php
        //手机端底部菜单
        get_template_part( 'template/mobile-foot' );
        //侧边客服工具栏
        get_template_part( 'template/slide-bar' );

        wp_footer();
        ?>
    </body>
</html>