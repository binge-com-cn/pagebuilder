<?php

/**
 * Template: 分类列表
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-09-04 22:47:12
 */

//根分类
$root_cat_id = get_category_root_id($cat);

//分类列表样式
$list_type = get_term_meta($cat, 'cat_list_type', true) ?? 'one';

if( !empty( $list_type ) ){
  $list_type = $list_type;
}elseif( !empty( $root_cat_id ) ){
  $list_type = get_term_meta($root_cat_id, 'cat_list_type', true) ?: 'one';
}

//手机端分类列表样式
$list_mobile = get_term_meta($cat, 'list_'.$list_type.'_mobile', true) ?? 'one';

if( !empty( $list_mobile ) ){
  $list_mobile = $list_mobile;
}elseif( !empty( $root_cat_id ) ){
  $list_mobile = get_term_meta($root_cat_id, 'list_'.$list_type.'_mobile', true) ?? 'one';
}

$list_thumb_ratio = get_term_meta($cat, 'list_thumb_ratio', true);
if( empty( $list_thumb_ratio ) && !empty( $root_cat_id ) ){
  $list_thumb_ratio = get_term_meta($root_cat_id, 'list_thumb_ratio', true);
}

$list_thumb_ratios = ['ratio-1-1', 'ratio-4-3', 'ratio-3-2', 'ratio-16-9', 'ratio-21-9'];
$list_thumb_ratio = in_array($list_thumb_ratio, $list_thumb_ratios, true) ? $list_thumb_ratio : '';
$list_thumb_ratio_class = $list_thumb_ratio ? ' customize-list-thumb-ratio '.$list_thumb_ratio : '';

//blog列表 样式
$list_style = '';
$list_style = '';
$list_blog_mobile = '';
if($list_type === 'blog'){
  $list_style = get_term_meta($cat, 'list_style', true) ?: 'style_3';
  $list_blog_mobile = get_term_meta($cat, 'list_blog_mobile', true) ?: 'one';
  $list_blog_mobile = ' mb-list_style_'.$list_blog_mobile;
}

//头部
get_header();

//Banner
get_template_part( 'template/archive', 'banner' );

//面包屑导航+搜索框
get_template_part( 'template/archive', 'search' );

?>
<div class="page-section">
  <div class="container">
    <div class="page-wrapper clearfix">
      <?php
      //侧边栏
      if($list_type != 'blog'){ //blog列表不显示侧栏

        $cat_widgets = get_term_meta( $cat, 'cat_widgets', true );

        if(empty($cat_widgets)){
          $cat = get_category_root_id($cat);
          $cat_widgets = get_term_meta($cat, 'cat_widgets', true);
        }

        if( $cat_widgets && is_active_sidebar('widget-area-'.$cat) ){
          echo '<div class="sidebar-widget '.get_theme_sidebar_class().'">';
          dynamic_sidebar('widget-area-'.$cat);
          echo '</div>';
        }elseif( !$cat_widgets && is_active_sidebar('widget_category') ){
          echo '<div class="sidebar-widget '.get_theme_sidebar_class().'">';
          dynamic_sidebar('widget_category');
          echo '</div>';
        }

      }?>

      <div class="page-main<?php echo esc_attr($list_thumb_ratio_class);?>">
        <?php
        // 获取筛选条件
        $post_filter = get_term_meta($cat, 'cat_filter', true);
        $filter_menu = get_term_meta($cat, 'filter_menu', true);

        if (empty($post_filter) && !empty($root_cat_id)) {
            $post_filter = get_term_meta($root_cat_id, 'cat_filter', true);
        }

        if (empty($filter_menu) && !empty($root_cat_id)) {
            $filter_menu = get_term_meta($root_cat_id, 'filter_menu', true);
        }

        // 如果存在筛选条件，加载筛选菜单
        if (!empty($post_filter) && !empty($filter_menu)) {
            get_template_part('template/filter-menu');
        }

        //文章列表
        if( $list_type == 'two' ){
          echo '<div class="post-list-box list-one list-'.$list_type.' mobile-list-'.$list_mobile.'">';
        }else{
          echo '<div class="post-list-box list-'.$list_type.' mobile-list-'.$list_mobile.' '.$list_style.$list_blog_mobile.'">';
        }

        //文章显示顺序 暂时移除
        /*
        $post_orderby = get_term_meta($cat, 'post_orderby', true) ?? 'new';
        if( !empty($post_orderby) && ( $post_orderby == 'rand' || $post_orderby == 'views' ) ){
          //获取当前分类的 ID
          $category_id = get_queried_object_id();

          //随机排序
          if( $post_orderby == 'rand' ){
            $args = array(
              'cat'            => $category_id,
              'orderby'        => 'rand',
              //'posts_per_page' => get_query_var('paged')
            );
          }

          //按文章浏览量排序
          if( $post_orderby == 'views' ){
            $args = array(
              'cat'       => $category_id,
              'orderby'     => 'meta_value_num',
              'meta_key'      => 'views',
              'order'       => 'DESC',
              //'posts_per_page'  => get_query_var('paged')
            );
          }

          //文章循环
          $posts_query = new WP_Query( $args );
          if ( $posts_query->have_posts() ) :
            while ( $posts_query->have_posts() ) : $posts_query->the_post();
              get_template_part( 'template/loop/list', $list_type );
            endwhile;
          endif;
        }
        */


        //if( empty($post_orderby) || $post_orderby == 'new' ){
          //文章循环
          while(have_posts()){
            the_post();
            get_template_part( 'template/loop/list', $list_type );
          }
        //}



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
        //  echo '<h3 style="padding-bottom:94px">抱歉，当前分类下暂无文章！</h3>';
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
