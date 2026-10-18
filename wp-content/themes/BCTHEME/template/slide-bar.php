<?php

/**
 * Template: 侧边客服工具栏
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-11-12 14:38:56
 */

?>
<div class="slide-bar">
    <?php
    $slide_bar = wpjam_theme_get_setting('slide_bar');
    if( !empty($slide_bar) ){
        $slide_bar = array_filter($slide_bar, function($slide_bar){
        if( $slide_bar['slide_bar_type'] == 'link' ){
            //跳转链接
            echo '<a href="'.$slide_bar['slide_bar_url'].'" target="_blank" rel="nofollow" class="slide-bar__item'.(!$slide_bar['slide_bar_text'] ? ' slide-bar-title_none' : '').'">';
            echo '<img loading="lazy" class="slide-bar__item__icon" src="'.$slide_bar['slide_bar_ico'].'" alt="'.$slide_bar['slide_bar_text'].'">';
            if($slide_bar['slide_bar_text']){
                echo '<span class="slide-bar__item__text">'.$slide_bar['slide_bar_text'].'</span>';
            }
            if($slide_bar['slide_bar_desc']){
                echo '<div class="slide-bar__item__tips"><p>'.$slide_bar['slide_bar_desc'].'</p></div>';
            }
            echo '</a>';
        }elseif( $slide_bar['slide_bar_type'] == 'img' ){
            //弹出图片
            echo '<div class="slide-bar__item'.(!$slide_bar['slide_bar_text'] ? ' slide-bar-title_none' : '').'">';
            echo '<img loading="lazy" class="slide-bar__item__icon" src="'.$slide_bar['slide_bar_ico'].'" alt="'.$slide_bar['slide_bar_text'].'">';
            if($slide_bar['slide_bar_text']){
                echo '<span class="slide-bar__item__text">'.$slide_bar['slide_bar_text'].'</span>';
            }
            echo '<div class="slide-bar__item__img">';
            echo '<img loading="lazy" src="'.$slide_bar['slide_bar_img'].'" alt="'.$slide_bar['slide_bar_text'].'">';
            echo '</div>';
            echo '</div>';
        }elseif( $slide_bar['slide_bar_type'] == 'form' ){                            
            //弹出表单
            $md5_slide_bar_ico = md5( $slide_bar['slide_bar_ico'] );
            echo '<a data-fancybox href="#a'.$md5_slide_bar_ico.'" class="slide-bar__item'.(!$slide_bar['slide_bar_text'] ? ' slide-bar-title_none' : '').'">';
            echo '<img loading="lazy" class="slide-bar__item__icon" src="'.$slide_bar['slide_bar_ico'].'" alt="'.$slide_bar['slide_bar_text'].'">';
            if($slide_bar['slide_bar_text']){
                echo '<span class="slide-bar__item__text">'.$slide_bar['slide_bar_text'].'</span>';
            }
            if($slide_bar['slide_bar_desc']){
                echo '<div class="slide-bar__item__tips"><p>'.$slide_bar['slide_bar_desc'].'</p></div>';
            }
            echo '</a>';

            echo '<div id="a'.$md5_slide_bar_ico.'" class="fancybox-form" style="display:none;">';
            $form_title = $slide_bar['slide_bar_form_title'];//表单标题
            $form_id = $slide_bar['slide_bar_form'];//表单ID
            wpjam_render_form($form_id, $form_title);
            echo '</div>';
        }elseif( $slide_bar['slide_bar_type'] == 'copy' ){
            //一键复制
            $md5_copy = md5( $slide_bar['slide_bar_text'] );
            $data_settings    = wp_json_encode([
                'id'            => 's'.$md5_copy,
                'copy'          => $slide_bar['slide_bar_copy'],
                'copy_tip'      => str_replace(' ', '&nbsp;', $slide_bar['slide_bar_copy_tip']), // 将空格替换为 &nbsp;
                'open_wechat'   => $slide_bar['slide_bar_open_wechat'] ?? '',
            ]);
            // 将 JSON 数据转义为 HTML 属性
            $data_settings = esc_attr($data_settings);

            echo '<a href="javaScript:;" id="s'.$md5_copy.'" class="copy-btn slide-bar__item'.(!$slide_bar['slide_bar_text'] ? ' slide-bar-title_none' : '').'" data-clipboard-text="'.$slide_bar['slide_bar_copy'].'" data-settings="'.$data_settings.'">';
            echo '<img loading="lazy" class="slide-bar__item__icon" src="'.$slide_bar['slide_bar_ico'].'" alt="'.$slide_bar['slide_bar_text'].'">';
            if($slide_bar['slide_bar_text']){
                echo '<span class="slide-bar__item__text">'.$slide_bar['slide_bar_text'].'</span>';
            }
            if($slide_bar['slide_bar_desc']){
                echo '<div class="slide-bar__item__tips"><p>'.$slide_bar['slide_bar_desc'].'</p></div>';
            }
            echo '</a>';
        }elseif( $slide_bar['slide_bar_type'] == 'phone' ){
            //拨打电话
            echo '<a href="tel:'.$slide_bar['slide_bar_phone'].'" class="slide-bar__item'.(!$slide_bar['slide_bar_text'] ? ' slide-bar-title_none' : '').'">';
            echo '<img loading="lazy" class="slide-bar__item__icon" src="'.$slide_bar['slide_bar_ico'].'" alt="'.$slide_bar['slide_bar_text'].'">';
            if($slide_bar['slide_bar_text']){
                echo '<span class="slide-bar__item__text">'.$slide_bar['slide_bar_text'].'</span>';
            }
            if($slide_bar['slide_bar_desc']){
                echo '<div class="slide-bar__item__tips"><p>'.$slide_bar['slide_bar_desc'].'</p></div>';
            }
            echo '</a>';
        }

        });

    }?>
    <div class="slide-bar__item scrollup">
        <i class="slide-bar__item__top ri-skip-up-line"></i>
        <div class="slide-bar__item__tips"><p><?php esc_html_e( '返回顶部', 'modular_theme' );?></p></div>
    </div>
</div>