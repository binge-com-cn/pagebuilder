<?php

/**
 * Template: 产品文章页面
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-11-12 14:32:30
 */

//头部样式
$header_style = wpjam_theme_get_setting('header_style') ?: 'style_1';

//产品摘要
$product_excerpt = get_post_meta(get_the_ID(), 'product_excerpt', true);

//产品图集
$product_gallery = get_post_meta(get_the_ID(), 'product_gallery', true);

//产品分类目录
$product_cat = wpjam_theme_get_setting('product_cat');

//产品特色标签
$product_feature_style = wpjam_theme_get_setting('product_feature_style') ?? 'one';
$product_feature = wpjam_theme_get_setting('product_feature');

//产品自定义按钮
$product_btn = wpjam_theme_get_setting('product_btn');

//产品联系电话
$product_teltitle = wpjam_theme_get_setting('product_teltitle');
$product_telnum = wpjam_theme_get_setting('product_telnum');

//产品页TAB内容 产品独立设置
// 获取产品独立设置
$product_tab_post = get_post_meta(get_the_ID(), 'product_tabs', true);
// 获取全局设置
$product_tab_global = wpjam_theme_get_setting('product_tabs');
if( !empty($product_tab_post) && !empty($product_tab_global) ){
    // 合并两个数组
    $product_tab_post_combined = array_merge($product_tab_post, $product_tab_global);
}
// 如果有合并的数组，则使用它，否则使用单独的数组
$product_tabs = !empty($product_tab_post_combined) ? $product_tab_post_combined : (!empty($product_tab_post) ? $product_tab_post : $product_tab_global);

//Banner
$product_banner = wpjam_theme_get_setting('product_banner');
if ( !empty($product_banner) ){
    get_template_part( 'template/archive', 'banner' );
}

//文章缩进
$single_indent = wpjam_theme_get_setting('single_indent');

//面包屑导航+搜索框
get_template_part( 'template/archive', 'search' );

?>
<div class="product-header">
    <div class="container">
        <div class="product-hd-box">
            <div class="product-hd-img">
            <?php
            //产品图集
            if( !empty($product_gallery) ){
                //产品图集 大图
                echo '<div class="swiper product-slider">';
                echo '<div class="swiper-wrapper">';
                foreach ($product_gallery as $item) {
                    echo '<div class="swiper-slide">';
                    //图片
                    if( $item['gallery_type'] == 'img' ){
                        echo '<a data-fancybox="product-gallery" href="'.$item['product_img'].'">';
                        echo '<img loading="lazy" src="'.$item['product_img'].'" alt="" />';
                        echo '</a>';
                    }
                    //视频
                    if( $item['gallery_type'] == 'video' ){
                        echo '<div class="product-video">';
                        echo '<a data-fancybox="product-gallery" href="'.$item['product_video'].'">';
                        echo '<img loading="lazy" src="'.$item['product_img'].'" alt="" />';
                        echo '<span class="video-play"><i class="ri-play-fill"></i></span>';
                        echo '</a>';
                        //echo '<video loading="lazy" controls="" controlslist="nodownload" id="a21794995790d1db91264e6f8b7c01159" style="display: none;" class="fancybox-video">';
                        //echo '<source src="'.$item['product_video'].'" type="video/mp4">';
                        //echo '</video>';
                        echo '</div>';
                    }
                    echo '</div>';
                };
                echo '</div>';
                echo '<div class="swiper-button-next"></div>';
                echo '<div class="swiper-button-prev"></div>';
                echo '</div>';

                //产品图集 缩略图
                if (count($product_gallery) > 1) {
                    echo '<div thumbsslider="" class="swiper product-thumbs-slider">';
                    echo '<div class="swiper-wrapper">';
                    foreach ($product_gallery as $item) {
                        echo '<div class="swiper-slide">';
                            //图片
                            if( $item['gallery_type'] == 'img' ){
                                echo '<img loading="lazy" src="'.$item['product_img'].'" alt="" />';
                            }
                            //视频
                            if( $item['gallery_type'] == 'video' ){
                                echo '<div class="thumbs-video">';
                                echo '<img loading="lazy" src="'.$item['product_img'].'" alt="" />';
                                echo '<i class="ri-play-circle-line"></i>';
                                echo '</div>';
                            }
                        echo '</div>';
                    };
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
			</div>
			<div class="product-hd-text">
                <?php
                //产品分类目录
                if( !empty($product_cat) ){

                    // 获取当前文章的分类目录
                    $categories = get_the_category();

                    // 检查是否有分类目录
                    if ($categories) {
                        echo '<div class="product-cat">';
                        foreach ($categories as $category) {
                            // 输出 HTML
                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                        }
                        echo '</div>';
                    } 
                }?>
				<h1><?php the_title();?></h1>
                <div class="split-line"></div>
				<?php
				if( !empty($product_excerpt) ){
					echo '<div class="product-excerpt">'.wpautop($product_excerpt).'</div>';
				}

                //产品特色标签
                if( !empty($product_feature) ){
                    echo '<div class="product-feature product-feature-'.count($product_feature).' feature-style-'.$product_feature_style.'">';
                    $product_feature = array_filter($product_feature, function($product_feature){
                    echo '<div class="feature-item">';
                    echo '<img loading="lazy" src="'.$product_feature['feature_img'].'">';
                    echo '<span>'.$product_feature['feature_text'].'</span>';
                    echo '</div>';
                    });
                    echo '</div>';
                }

                //产品自定义按钮 和 联系电话
                if( !empty($product_btn) || !empty($product_telnum) ){
                echo '<div class="product-contact">';
                    //产品自定义按钮
                    if( !empty($product_btn) ){
                        echo '<div class="product-btn">';
                        $product_btn = array_filter($product_btn, function($product_btn){
                            // 判断是否存在颜色值
                            $background_style = !empty($product_btn['product_btn_color']) ? 'style="--product-btn-color:' . $product_btn['product_btn_color'] . '"' : '';
                            // 判断是否存在图标
                            $product_btn_icon = !empty($product_btn['product_btn_icon']) ? $product_btn['product_btn_icon'] : '';
                            //跳转链接
                            if( $product_btn['product_btn_type'] == 'link' ){
                                echo '<a href="'.$product_btn['product_btn_url'].'" target="_blank" '.$background_style.'>'.$product_btn_icon.$product_btn['product_btn_text'].'</a>';
                            }
                            //弹出图片
                            if( $product_btn['product_btn_type'] == 'img' ){
                                $md5_title = md5( $product_btn['product_btn_text'] );
                                echo '<a data-fancybox href="#a'.$md5_title.'" '.$background_style.'>'.$product_btn_icon.$product_btn['product_btn_text'].'</a>';
                                echo '<div id="a'.$md5_title.'" style="display:none;">';
                                echo '<img loading="lazy" src="'.$product_btn['product_btn_img'].'" alt="" style="display:block">';
                                echo '</div>';
                            }
                            //弹出表单
                            if( $product_btn['product_btn_type'] == 'form' ){
                                $md5_title = md5( $product_btn['product_btn_text'] );
                                echo '<a data-fancybox href="#a'.$md5_title.'" '.$background_style.'>'.$product_btn_icon.$product_btn['product_btn_text'].'</a>';
                                echo '<div id="a'.$md5_title.'" class="fancybox-form" style="display:none;">';
                                $form_title = $product_btn['product_btn_form_title'];//表单标题
                                $form_id    = $product_btn['product_btn_form'];//表单ID
                                
                                wpjam_render_form($form_id, $form_title);
                                echo '</div>';
                            }
                            //一键复制
                            if( $product_btn['product_btn_type'] == 'copy' ){
                                $md5_copy = md5( $product_btn['product_btn_text'] );
                                $data_settings    = wp_json_encode([
                                    'id'            => 'c'.$md5_copy,
                                    'copy'          => $product_btn['product_btn_copy'],
                                    'copy_tip'      => str_replace(' ', '&nbsp;', $product_btn['product_btn_copy_tip']), // 将空格替换为 &nbsp;
                                    'open_wechat'   => $product_btn['product_btn_open_wechat'] ?? '',
                                ]);
                                // 将 JSON 数据转义为 HTML 属性
                                $data_settings = esc_attr($data_settings);
                                echo '<a href="javaScript:;" id="c'.$md5_copy.'" data-clipboard-text="'.$product_btn['product_btn_copy'].'" class="copy-btn" '.$background_style.' data-settings="'.$data_settings.'">'.$product_btn_icon.$product_btn['product_btn_text'].'</a>';
                            }
                        });
        				echo '</div>';
                    }

                    //产品联系电话
                    if( !empty($product_teltitle) || !empty($product_telnum) ){?>
                    <div class="product-tel">
                        <div class="icon-tel">
                            <i class="ri-phone-fill"></i>
                        </div>
                        <div class="tel-text">
                            <span><?php echo $product_teltitle;?></span>
                            <a href="tel:<?php echo $product_telnum;?>"><?php echo $product_telnum;?></a>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <?php } //产品自定义按钮 和 联系电话?>
			</div>
		</div>
	</div>
</div>

<div class="page-section<?php if(!empty($header_style) && $header_style == 'style_2'){ echo ' header_style_2'; }else if(!empty($header_style) && $header_style == 'style_3'){ echo ' header_style_3'; }?>">
	<div class="container">
		<div class="clearfix">
			<?php
			//侧边栏
			if (is_active_sidebar('widget_product')){?>
				<div class="sidebar-widget <?php echo get_theme_sidebar_class();?>">
				<?php dynamic_sidebar('widget_product');?>
				</div>
			<?php }?>

			<div class="page-main">
                <div class="product-tab-menu clearfix">
                    <ul>
                        <li class="active" data-tab="productTab-default">
                            <?php
                            $text_product_details = wpjam_theme_get_setting('text_product_details');
                            $product_details = !empty($text_product_details) ? $text_product_details : '产品详情';
                            echo '<span>' . __($product_details, 'modular_theme') . '</span>';
                            ?>
                        </li>
                        <?php
                        //TAB标题
                        if ( !empty($product_tabs) ){
                            foreach($product_tabs as $key => $tab){
                                //echo print_r($tab);
                                echo '<li data-tab="productTab-'.$key.'"><span>'.$tab['tab_title'].'</span></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
				<div class="post-content productTab-content active" id="productTab-default">
					<?php
					// 判断是否显示摘要
					$single_excerpt = wpjam_theme_get_setting('single_excerpt');
					if (has_excerpt() && $single_excerpt){
						echo '<div class="post-desc">';
							echo '<p style="font-weight:600">'.__( '摘要：', 'modular_theme' ).'</p>';
							the_excerpt();
						echo '</div>';
					}?>
					<div class="post-body<?php if ( !empty($single_indent) ){ echo ' text-indent';}?>">
					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();
						the_content();
					endwhile; // End of the loop.?>
					</div>
					<div class="post-tags">
						<?php the_tags('<strong>'.__( '标签', 'modular_theme' ).'</strong>：', ' · ', ''); ?>
					</div>
                    <?php echo dahuzi_post_share(); //文章分享?>
				</div>
                <?php

                //TAB详情
                if ( !empty($product_tabs) ){
                    foreach($product_tabs as $key => $tab){
                        //echo print_r($tab);
                        echo '<div class="post-content productTab-content" id="productTab-'.$key.'">';
                        echo '<div class="post-body' . (!empty($single_indent) ? ' text-indent' : '') . '">';
                        echo apply_filters( 'the_content', $tab['tab_content'] );
                        //echo do_shortcode($tab['tab_content']);
                        echo '</div>';
                        echo '</div>';
                    }
                }

                //自定义相关推荐
                $single_related = wpjam_theme_get_setting('single_related');
                $related_post = get_post_meta(get_the_ID(), 'related_post', true);

                if ( !empty($single_related) && !empty($related_post) ){

                    // 获取指定 ID 的文章
                    $related = array(
                        'post__in' => $related_post,
                        'orderby' => 'post__in', // 按照指定 ID 的顺序显示
                    );

                    $related_query = wpjam_query($related);
                }else{
                    //默认相关推荐
                    $related_query = wpjam_get_related_posts_query(6);
                }

                if ($related_query->have_posts()) { 
                ?>
                <div class="post-related">
                    <div class="related-title">
                        <?php
                        $text_product_related = wpjam_theme_get_setting('text_product_related');
                        $related_title = !empty($text_product_related) ? $text_product_related : '相关产品';
                        echo '<h3>' . __($related_title, 'modular_theme') . '</h3>';
                        ?>
                    </div>
                    <div class="list-one list-two">
                    <?php
                    while ($related_query->have_posts()) {
                        $related_query->the_post(); 
                        get_template_part( 'template/loop/list', 'one' );
                        ?>

                    <?php 
                    } 
                    ?>
                    </div>
                </div>
                <?php } ?>

			</div>
		</div>
	</div>
</div>
<?php
get_footer();

?>
<script>
//(function($) {

    var swiper_thumbs = new Swiper(".product-thumbs-slider", {
      loop: true,
	  autoplay: true,
      spaceBetween: 10,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
    });
    var swiper2 = new Swiper(".product-slider", {
      loop: true,
	  autoplay: true,
      spaceBetween: 10,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      thumbs: {
        swiper: swiper_thumbs,
      },
    });

//})(jQuery);

</script>