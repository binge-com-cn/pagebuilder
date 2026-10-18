<?php

/**
 * Template: 手机端底部菜单
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-11-13 18:37:22
 */

$mobile_foot = wpjam_theme_get_setting('mobile_foot');
if( !empty($mobile_foot) ){
?>
<style type="text/css">
    @media screen and (max-width: 767px) {
        body{padding-bottom:calc(63px + env(safe-area-inset-bottom))}
    }
</style>
<div class="footer-fix">
    <ul class="clearfix">
        <?php
        $mobile_foot = array_filter($mobile_foot, function($mobile_foot){
            //跳转链接
            if( $mobile_foot['foot_menu_type'] == 'link' ){?>
            <li>
                <a href="<?php echo $mobile_foot['foot_menu_url'];?>" target="_blank">
                    <img loading="lazy" src="<?php echo $mobile_foot['foot_menu_ico'];?>" alt="<?php echo $mobile_foot['foot_menu_text'];?>" />
                    <span><?php echo $mobile_foot['foot_menu_text'];?></span>
                </a>
            </li>
            <?php }
            //拨打电话
            if( $mobile_foot['foot_menu_type'] == 'phone' ){?>
            <li>
                <a href="tel:<?php echo $mobile_foot['foot_menu_phone'];?>">
                    <img loading="lazy" src="<?php echo $mobile_foot['foot_menu_ico'];?>" alt="<?php echo $mobile_foot['foot_menu_text'];?>" />
                    <span><?php echo $mobile_foot['foot_menu_text'];?></span>
                </a>
            </li>
            <?php }
            //弹出图片
            if( $mobile_foot['foot_menu_type'] == 'img' ){
            $md5_title = md5( $mobile_foot['foot_menu_text'] );
            ?>
            <li>
                <a data-fancybox href="#a<?php echo $md5_title;?>">
                    <img loading="lazy" src="<?php echo $mobile_foot['foot_menu_ico'];?>" alt="<?php echo $mobile_foot['foot_menu_text'];?>" />
                    <span><?php echo $mobile_foot['foot_menu_text'];?></span>
                </a>
            </li>
            <div id="a<?php echo $md5_title;?>" style="display:none;">
                <img loading="lazy" src="<?php echo $mobile_foot['foot_menu_img'];?>" alt="" style="display:block">
            </div>
            <?php }
            //弹出表单
            if( $mobile_foot['foot_menu_type'] == 'form' ){
            $md5_form = md5( $mobile_foot['foot_menu_text'] );
            ?>
            <li>
                <a data-fancybox href="#a<?php echo $md5_form;?>">
                    <img loading="lazy" src="<?php echo $mobile_foot['foot_menu_ico'];?>" alt="<?php echo $mobile_foot['foot_menu_text'];?>" />
                    <span><?php echo $mobile_foot['foot_menu_text'];?></span>
                </a>
            </li>
            <div id="a<?php echo $md5_form;?>" class="fancybox-form" style="display:none;">
                <?php
                $form_title = $mobile_foot['foot_menu_form_title'];
                $form_id    = $mobile_foot['foot_menu_form'];

                wpjam_render_form($form_id, $form_title);
                ?>
            </div>
            <?php }
            //一键复制
            if( $mobile_foot['foot_menu_type'] == 'copy' ){
                $md5_copy = md5( $mobile_foot['foot_menu_text'] );
                $data_settings    = wp_json_encode([
                    'id'            => 'c'.$md5_copy,
                    'copy'          => $mobile_foot['foot_menu_copy'],
                    'copy_tip'      => str_replace(' ', '&nbsp;', $mobile_foot['foot_menu_copy_tip']), // 将空格替换为 &nbsp;
                    'open_wechat'   => $mobile_foot['foot_menu_open_wechat'] ?? '',
                ]);
                // 将 JSON 数据转义为 HTML 属性
                $data_settings = esc_attr($data_settings);
                ?>
            <li class="mobile-foot-copy copy-btn" data-settings="<?php echo $data_settings;?>">
                <a href="javaScript:;" id="c<?php echo $md5_copy;?>" data-clipboard-text="<?php echo $mobile_foot['foot_menu_copy'];?>">
                    <img loading="lazy" src="<?php echo $mobile_foot['foot_menu_ico'];?>" alt="<?php echo $mobile_foot['foot_menu_text'];?>" />
                    <span><?php echo $mobile_foot['foot_menu_text'];?></span>
                </a>
            </li>
            <?php }
            //多语言
            if( $mobile_foot['foot_menu_type'] == 'language' && function_exists('trp_custom_language_switcher') ){?>
            <li>
                <a data-fancybox="" href="#shortcode-language">
                    <img loading="lazy" src="<?php echo $mobile_foot['foot_menu_ico'];?>" alt="<?php echo $mobile_foot['foot_menu_text'];?>" />
                    <span><?php echo $mobile_foot['foot_menu_text'];?></span>
                </a>
            </li>
            <div id="shortcode-language" style="display: none;">
                <ul data-no-translation>
                    <?php
                    $array = trp_custom_language_switcher();
                    if ( apply_filters( 'trp_allow_tp_to_run', true ) ){
                        foreach ($array as $name => $item){
                            echo '<li> ';
                            echo '<a href="'.$item['current_page_url'].'">';
                            echo '<img loading="lazy" src="'.$item['flag_link'].'" alt="'.$item['language_name'].'">';
                            echo '<span>'.$item['language_name'].'</span>';
                            echo '</a>';
                            echo '</li>';
                        }
                    }?>
                </ul>
            </div>
            <?php }

        });?>

    </ul>
</div>
<?php }?>