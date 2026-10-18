<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, viewport-fit=cover">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class();?>>
        <?php
        // 获取产品图集
        $product_gallery = is_single() ? get_post_meta(get_the_ID(), 'product_gallery', true) : '';

        // 获取头部样式设置
        $header_style = wpjam_theme_get_setting('header_style') ?: 'style_1';

        // 获取菜单分割线设置
        $header_line = wpjam_theme_get_setting('header_line');

        // 初始化类名字符串
        $header_class = '';

        // 判断是否为 'style_3' 并且 $product_gallery 为空
        //if ($header_style === 'style_3' && empty($product_gallery)) {
        if ( ($header_style === 'style_3' && empty($product_gallery) ) && ($header_style === 'style_3' && !is_search()) ) {
            $header_class .= ' header-style-3';
        }

        // 判断是否存在 $header_line
        if (!empty($header_line)) {
            $header_class .= ' header-line';
        }
        ?>
        <div class="header<?php echo esc_attr($header_class); ?>">
            <?php
            $header_notice = wpjam_theme_get_setting('header_notice');
            $menu=get_nav_menu_locations();
            if( !empty($header_notice) || !empty($menu["top"]) ){?>
            <div class="header-top">
                <div class="container clearfix">
                    <?php
                    //公告文本
                    if(!empty($header_notice)){
                        echo '<div class="top-notice">'.wpautop($header_notice).'</div>';
                    }
                    //顶部菜单
                    if(!empty($menu["top"])):
                        echo '<div class="top-menu">';
                        if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'top'));
                        echo '</div>';
                    endif;
                    ?>
                </div>
            </div>
            <?php }

            //头部样式
            if(!empty($header_style) && $header_style == 'style_1'){?>
            <div class="container clearfix">
                <a href="javascript:void(0)" class="hidden-lg hamburger">
                    <span class="h-top"></span>
                    <span class="h-middle"></span>
                    <span class="h-bottom"></span>
                </a>
                <div class="logo img-center">
                <?php
                //Logo
                $header_logo = wpjam_theme_get_setting('logo');
                $image_src = esc_url($header_logo);
                $image_alt = esc_html(dahuzi_get_image_alt($image_src));
                if (!empty($header_logo)):
                    echo '<a href="'.esc_url(home_url('/')).'">';
                        echo '<img loading="lazy" src="'.$image_src.'" alt="'.$image_alt.'" />';
                    echo '</a>';
                endif;
                echo '</div>';

                //Logo右侧文本
                $logo_title = wpjam_theme_get_setting('logo_title');
                $logo_desc = wpjam_theme_get_setting('logo_desc');
                if(!empty($logo_title)){
                echo '<div class="text">';
                    echo '<strong>'.$logo_title.'</strong>'.$logo_desc.'';
                echo '</div>';
                }

                //联系电话
                $tel_title = wpjam_theme_get_setting('tel_title');
                $tel_number = wpjam_theme_get_setting('tel_number');
                if(!empty($tel_title)||!empty($tel_number)){
                    echo '<div class="tel wow bounce">'.$tel_title.'<strong>'.$tel_number.'</strong></div>';
                }?>
            </div>
            <div class="header-menu header-style-1">
                <div class="container">
                    <ul>
                        <?php if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'primary' )); ?>
                    </ul>
                </div>
            </div>
            <?php
            }

            //头部样式-2
            if(!empty($header_style) && ( $header_style == 'style_2' || $header_style == 'style_3' )){?>
            <div class="header-style-2">
                <div class="container">
                    <?php
                    //Logo
                    echo '<div class="logo">';
                    $header_logo = wpjam_theme_get_setting('logo');
                    $image_src = esc_url($header_logo ?? '');
                    $image_alt = esc_html(dahuzi_get_image_alt($image_src));

                    $header_logo2 = wpjam_theme_get_setting('logo_2');
                    $image_src2 = esc_url($header_logo2 ?? '');
                    $image_alt2 = esc_html(dahuzi_get_image_alt($image_src2));

                    if (!empty($header_logo)):
                        echo '<a href="'.esc_url(home_url('/')).'">';
                            // 确定第一个图片的源和替代文本
                            if ( ($header_style === 'style_3' && !empty($product_gallery) ) || ($header_style === 'style_3' && is_search()) ) {
                                $image_src1 = $image_src2;
                                $image_alt1 = $image_alt2;
                            } else {
                                $image_src1 = $image_src;
                                $image_alt1 = $image_alt;
                            }

                            // 输出第一个图片
                            echo '<img loading="lazy" src="'.$image_src1.'" alt="'.$image_alt1.'" class="header-logo-1" />';

                            // 如果存在第二个图片，输出第二个图片
                            if (!empty($header_logo2) && $header_style == 'style_3' && empty($product_gallery) && !is_search()) {
                                echo '<img loading="lazy" src="'.$image_src2.'" alt="'.$image_alt2.'" class="header-logo-2" />';
                            }
                        echo '</a>';
                    endif;
                    echo '</div>';?>
                    <a href="javascript:void(0)" class="hidden-lg hamburger">
                        <span class="h-top"></span>
                        <span class="h-middle"></span>
                        <span class="h-bottom"></span>
                    </a>
                    <div class="header-menu">
                        <ul>
                            <?php if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'primary' )); ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php }?>

        </div>