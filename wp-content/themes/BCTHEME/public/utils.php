<?php

/**
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-09-25 17:02:34
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-11-12 14:31:51
 */

/** --------------------------------------------------------------------------------- *
 *  通过图片链接获取alt标签
 *  --------------------------------------------------------------------------------- */
function dahuzi_get_image_alt($image_url) {
    // 移除查询参数
    $parsed_url = parse_url($image_url);

    if (empty($parsed_url)) {
        return '';
    }

    // 检查并组装没有查询参数的URL
    $url_without_query = '';
    if (isset($parsed_url['scheme'])) {
        $url_without_query .= $parsed_url['scheme'] . '://';
    }
    if (isset($parsed_url['host'])) {
        $url_without_query .= $parsed_url['host'];
    }
    if (isset($parsed_url['path'])) {
        $url_without_query .= $parsed_url['path'];
    }

    // 如果组装后的URL为空，返回空字符串
    if (empty($url_without_query)) {
        return '';
    }

    // 获取图片ID
    $image_id = attachment_url_to_postid($url_without_query);

    // 获取图片的alt属性
    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

    return $image_alt;
}


/** --------------------------------------------------------------------------------- *
 *  为指定来源的 iframe 标签添加包裹 div
 *  --------------------------------------------------------------------------------- */
function dahuzi_specific_iframe_with_div($content) {
    // 使用正则表达式在内容中查找特定来源的 iframe 标签
    $pattern = '/<iframe.*?<\/iframe>/';
    preg_match_all($pattern, $content, $matches);

    // 遍历所有匹配到的 iframe 标签
    foreach ($matches[0] as $iframe) {
        // 判断 iframe 的来源是否是腾讯视频、Bilibili 或 YouTube
        if (strpos($iframe, 'v.qq.com') !== false || strpos($iframe, 'bilibili.com') !== false || strpos($iframe, 'youtube.com') !== false) {
            // 在 iframe 标签外包裹一个 div
            $wrapped_iframe = '<div class="video-iframe-wrapper">' . $iframe . '</div>';
            // 用包裹后的 iframe 替换原始的 iframe 标签
            $content = str_replace($iframe, $wrapped_iframe, $content);
        }
    }

    return $content;
}
add_filter('the_content', 'dahuzi_specific_iframe_with_div');

/** --------------------------------------------------------------------------------- *
 *  文章发布时间
 *  --------------------------------------------------------------------------------- */
function dahuzi_post_time() {
  $date_format = get_option( 'date_format' );
  $time_format = get_option( 'time_format' );
  return get_the_date( "{$date_format} {$time_format}" );
}

/** --------------------------------------------------------------------------------- *
 *  面包屑导航
 *  --------------------------------------------------------------------------------- */
function get_breadcrumbs()  {
    global $wp_query;
    echo '<div class="breadcrumb">';
    echo '<i class="ri-map-pin-fill"></i> ';
    echo '<strong>'.__( '当前位置：', 'modular_theme' ).'</strong>';
    echo '<a href="'. get_option('home') .'">'.__( '首页', 'modular_theme' ).'</a>';

    if ( is_category() )  {
        $catTitle = single_cat_title( "", false );
        $cat = get_cat_ID( $catTitle );
        echo '<i class="ri-arrow-right-s-line"></i>'.get_category_parents( $cat, TRUE, '<i class="ri-arrow-right-s-line"></i>' );
    }
    elseif ( is_tag() )  {
        echo '<i class="ri-arrow-right-s-line"></i>'.single_tag_title('', false).'<i class="ri-arrow-right-s-line"></i>';
    }

    elseif ( is_post_type_archive('faq') )  {
        echo '<i class="ri-arrow-right-s-line" style="display:inline-block"></i>'.__( '常见问题', 'modular_theme' );
    }
    elseif ( is_post_type_archive('download') )  {
        echo '<i class="ri-arrow-right-s-line" style="display:inline-block"></i>'.__( '下载', 'modular_theme' );
    }
    elseif ( is_post_type_archive('team') )  {
        echo '<i class="ri-arrow-right-s-line" style="display:inline-block"></i>'.__( '团队', 'modular_theme' );
    }
    elseif ( is_tax('faq_type') )  {
        echo '<i class="ri-arrow-right-s-line" style="display:inline-block"></i>'.__( '常见问题', 'modular_theme' );
    }
    elseif ( is_tax('download_type') )  {
        echo '<i class="ri-arrow-right-s-line" style="display:inline-block"></i>'.__( '下载', 'modular_theme' );
    }
    elseif ( is_singular('faq') )  {
        echo '<i class="ri-arrow-right-s-line" style="display:inline-block"></i>'.__( '常见问题', 'modular_theme' );
    }
    elseif ( is_singular('download') )  {
        echo '<i class="ri-arrow-right-s-line" style="display:inline-block"></i>'.__( '下载', 'modular_theme' );
    }
    elseif ( is_singular('team') )  {
        echo '<i class="ri-arrow-right-s-line" style="display:inline-block"></i>'.__( '团队', 'modular_theme' );
    }
    elseif ( is_search() ) {
        echo ' <span>&raquo;</span> '.sprintf( __('【%s】的搜索结果', 'modular_theme'), get_search_query() );
        //echo '<i class="ri-arrow-right-s-line"></i>'.sprintf( __('Search for: %s', 'dahuzi_theme'), get_search_query() ).'';
    }

    elseif ( is_archive() && !is_category() )  {
        echo '<i class="ri-arrow-right-s-line" style="display:inline-block"></i>'.single_term_title('', false).'<i class="ri-arrow-right-s-line"></i>';
    }

    elseif ( is_single() )  {
        $category = get_the_category();
        if($category){
            $category_id = get_cat_ID( $category[0]->cat_name );
            echo '<i class="ri-arrow-right-s-line"></i>'. get_category_parents( $category_id, TRUE, '<i class="ri-arrow-right-s-line"></i>' );
            echo '<span>'.get_the_title().'</span>'; 
        }
    }
    elseif ( is_page() )  {
        $post = $wp_query->get_queried_object();
        if ( $post->post_parent == 0 ){
            echo '<i class="ri-arrow-right-s-line"></i><span>'.the_title('','', FALSE).'</span>';
        } else {
            $ancestors = array_reverse( get_post_ancestors( $post->ID ) );
            array_push($ancestors, $post->ID);
    
            foreach ( $ancestors as $ancestor ){
                if( $ancestor != end($ancestors) ){
                    echo '<i class="ri-arrow-right-s-line"></i><a href="'. get_permalink($ancestor) .'">'. strip_tags( apply_filters( 'single_post_title', get_the_title( $ancestor ) ) ) .'</a>'; 
                } else {
                    echo '<i class="ri-arrow-right-s-line"></i><span>'. strip_tags( apply_filters( 'single_post_title', get_the_title( $ancestor ) ) ) .'</span>';
                }
            }
        }
    }
    echo "</div>";
}

/** --------------------------------------------------------------------------------- *
 *  分页
 *  --------------------------------------------------------------------------------- */
function dahuzi_theme_pagination() {
    $p = 2;
    if ( is_singular() ) return;
    global $wp_query, $paged;
    $max_page = $wp_query->max_num_pages;
    if ( $max_page == 0 || $max_page == 1 ) return; 
    echo '<div class="theme-pagination"><ul>';
    if ( empty( $paged ) ) $paged = 1;
    echo '<li class="prev-page">';
    previous_posts_link(__('上一页', 'modular_theme'));
    echo '</li>';
    if ( $paged > $p + 1 ) dahuzi_pagination_link( 1, '<li>'.__('第一页', 'modular_theme').'</li>' );
    if ( $paged > $p + 2 ) echo "<li><span>···</span></li>";
    for( $i = $paged - $p; $i <= $paged + $p; $i++ ) { 
        if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<li class=\"active\"><span>{$i}</span></li>" : dahuzi_pagination_link( $i );
    }
    if ( $paged < $max_page - $p - 1 ) echo "<li><span>···</span></li>";
    echo '<li class="next-page">';
    next_posts_link(__('下一页', 'modular_theme'));
    echo '</li>';
    echo '<li><span>' . sprintf(__('共 %d 页', 'modular_theme'), $max_page) . '</span></li>';
    echo '</ul></div>';
}

function dahuzi_pagination_link( $i, $title = '' ) {
    if ( $title == '' ) $title = "第 {$i} 页";
    echo "<li><a href='", esc_html( get_pagenum_link( $i ) ), "'>{$i}</a></li>";
}

/** --------------------------------------------------------------------------------- *
 *  自定义摘要字数以及省略号
 *  --------------------------------------------------------------------------------- */
//dahuzi_excerpt( 120, '...' );
function dahuzi_excerpt( $length, $more = '&hellip;', $echo = true ){
    static $excerpt_length, $excerpt_more;
 
    $current_filter = current_filter();
    if( $current_filter == 'excerpt_length' ) return $excerpt_length;
    if( $current_filter == 'excerpt_more'   ) return $excerpt_more;
 
    $excerpt_length = $length;
    $excerpt_more   = $more;
 
    $callable = __FUNCTION__;
    add_filter( 'excerpt_length', $callable, 18 );
    add_filter( 'excerpt_more',   $callable, 18 );
 
        $excerpt = $echo ? the_excerpt() : get_the_excerpt();
 
    remove_filter( 'excerpt_length', $callable, 18 );
    remove_filter( 'excerpt_more',   $callable, 18 );
 
    unset( $excerpt_length, $excerpt_more );
    return $excerpt;
}

/** --------------------------------------------------------------------------------- *
 *  侧栏显示位置
 *  --------------------------------------------------------------------------------- */
function get_theme_sidebar_class() {
    $theme_sidebar = wpjam_theme_get_setting('theme_sidebar') ?: 'left';
    return 'sidebar-' . $theme_sidebar;
}

/** --------------------------------------------------------------------------------- *
 *  登录界面美化
 *  --------------------------------------------------------------------------------- */
if( wpjam_theme_get_setting('login_style') ){
    include_once TEMPLATEPATH.'/public/login-style.php';
}

/** --------------------------------------------------------------------------------- *
 *  文章分享
 *  --------------------------------------------------------------------------------- */
function dahuzi_post_share() {

    //分享开关
    $post_share = wpjam_theme_get_setting('post_share');

    if (empty($post_share)) {
        return;
    }

    //分享平台
    $platforms = wpjam_theme_get_setting('share_platforms');
    //按钮样式
    $style_key = wpjam_theme_get_setting('share_style');
    //按钮风格
    $variant_key = wpjam_theme_get_setting('share_variant');

    $title = wp_strip_all_tags( get_the_title() );
    $url = rawurlencode( esc_url( esc_attr( get_the_permalink() ) ) );
    $pin_img = has_post_thumbnail() ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ) : '';
    $pin_img = isset( $pin_img[0] ) ? $pin_img[0] : '';

    $share = array();

    //国内平台
    $share['wechat'] = '<a data-fancybox href="#sharer-wechat" class="post-share-item socicon-wechat prevent-share-popup"><span>'.__( '微信', 'modular_theme' ).'</span></a>';
    $share['wechat'] .= '<div id="sharer-wechat" style="display:none;" data-url="'.get_the_permalink().'"><p><i class="ri-qr-scan-line"></i>'.__( '微信扫一扫', 'modular_theme' ).'</p></div>';

    $share['weibo'] = '<a href="#" class="post-share-item socicon-weibo" data-url="https://service.weibo.com/share/share.php?url=' . $url . '&amp;title=' . $title . '&amp;pic=' . urlencode( esc_attr( $pin_img ) ) . '"><span>'.__( '微博', 'modular_theme' ).'</span></a>';

    //复制链接
    $copy_settings    = wp_json_encode([
        'id'            => 'sharer-link',
        'copy'          => get_the_permalink(),
        'copy_tip'      => '【'.get_the_permalink().'】'.__('复制成功', 'modular_theme'),
        'open_wechat'   => '',
    ]);
    // 将 JSON 数据转义为 HTML 属性
    $copy_settings = esc_attr($copy_settings);

    $share['copy'] = '<a href="javaScript:;" id="sharer-link" class="post-share-item ri-file-copy-2-fill prevent-share-popup copy-btn" data-clipboard-text="'.get_the_permalink().'" data-settings="'.$copy_settings.'"><span>'.__( '复制链接', 'modular_theme' ).'</span></a>';


    //国外平台
    $share['facebook'] = '<a href="#" class="post-share-item socicon-facebook" data-url="http://www.facebook.com/sharer/sharer.php?u=' . $url . '&amp;t=' . $title . '"><span>'.__( 'Facebook', 'modular_theme' ).'</span></a>';
    $share['twitter'] = '<a href="#" class="post-share-item socicon-twitter" data-url="http://twitter.com/intent/tweet?url=' . $url . '&amp;text=' . $title . '"><span>'.__( 'X', 'modular_theme' ).'</span></a>';
    $share['googleplus'] = '<a href="#"  class="post-share-item socicon-googleplus" data-url="https://plus.google.com/share?url=' . $url . '"><span>'.__( 'Google+', 'modular_theme' ).'</span></a>';
    $share['pinterest'] = '<a href="#"  class="post-share-item socicon-pinterest" data-url="http://pinterest.com/pin/create/button/?url=' . $url . '&amp;media=' . urlencode( esc_attr( $pin_img ) ) . '&amp;description=' . $title . '"><span>'.__( 'Pinterest', 'modular_theme' ).'</span></a>';
    $share['linkedin'] = '<a href="#"  class="post-share-item socicon-linkedin" data-url="https://www.linkedin.com/cws/share?url=' . $url . '"><span>'.__( 'LinkedIn', 'modular_theme' ).'</span></a>';
    $share['reddit'] = '<a href="#"  class="post-share-item socicon-reddit" data-url="http://www.reddit.com/submit?url=' . $url . '&amp;title=' . $title . '"><span>'.__( 'Reddit', 'modular_theme' ).'</span></a>';
    $share['email'] = '<a rel="nofollow" target="_blank" href="mailto:?subject=' . $title . '&amp;body=' . $url . '" class="post-share-item  socicon-mail prevent-share-popup"><span>'.__( 'Email', 'modular_theme' ).'</span></a>';
    $share['stumbleupon'] = '<a href="#"  class="post-share-item socicon-stumbleupon" data-url="http://www.stumbleupon.com/badge?url=' . $url . '&amp;title=' . $title . '"><span>'.__( 'StumbleUpon', 'modular_theme' ).'</span></a>';
    $share['vk'] = '<a href="#"  class="post-share-item socicon-vkontakte" data-url="http://vk.com/share.php?url='.$url.'&amp;title='.$title.'"><span>'.__( 'vKontakte', 'modular_theme' ).'</span></a>';
    $share['whatsapp'] = '<a rel="nofollow" target="_blank" href="https://api.whatsapp.com/send?text='.$title.' '.$url.'" class="post-share-item socicon-whatsapp prevent-share-popup"><span>'.__( 'WhatsApp', 'modular_theme' ).'</span></a>';

    $share_platforms = '';

    foreach ( $platforms as $social ) {
        $platform_title = $social['platforms_title'];

        // 检查 $platform_title 是否存在于 $share 数组中
        if ( array_key_exists( $platform_title, $share ) ) {
            $share_platforms .= $share[$platform_title];
        }
    }

    // 定义样式和风格的映射关系
    $styles = array(
        'style' => array(
            '1' => 'rectangle no-labels',
            '2' => 'rounded no-labels',
            '3' => 'circle no-labels',
            '4' => 'square no-labels',
            '5' => 'transparent no-labels',
            '6' => 'rectangle',
            '7' => 'rounded',
            '8' => 'transparent'
        ),
        'variant' => array(
            '1' => 'solid',
            '2' => 'outline'
        )
    );

    // 根据 key 获取对应的样式和风格
    $style = $styles['style'][$style_key] ?? '';
    $variant = $styles['variant'][$variant_key] ?? '';

    //模块标题
    $share_text = wpjam_theme_get_setting('share_title');
    $share_title = '<div class="share-title"><h3>' . __($share_text, 'modular_theme') . '</h3></div>';
    $title_html = !empty($share_text) ? $share_title : '';
                    

    // 构建输出的 HTML
    $before = '<div class="post-share layout-'.esc_attr($style_key).'-'.esc_attr($variant_key).' '.esc_attr($style).' '.esc_attr($variant).'">';
    $after = '</div>';

    return $before . $title_html . $share_platforms . $after;

}
