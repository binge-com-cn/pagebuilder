<?php

/**
 * Template: 分类页面Banner
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-08-05 17:29:40
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-11-10 22:09:57
 */

if( is_single() ){

	// 文章页面获取分类Banner设置
	// 获取当前文章的所属分类
	$categories = get_the_category();
	if ($categories) {
		// 获取第一个分类的 ID
		$category_id = $categories[0]->term_id;
		// 通过分类ID获取该分类的Banner设置
		$cat_banner = get_term_meta($category_id, 'cat_banner', true);
		$cat_banner_pc_h = get_term_meta($category_id, 'cat_banner_pc_h', true);
		$cat_banner_mb_h = get_term_meta($category_id, 'cat_banner_mb_h', true);
		// 如果该分类没有设置Banner 则获取该分类的上级分类的Banner设置
		if( !is_array( $cat_banner ) ){
			$cat_id = get_category_root_id($category_id);
			$cat_banner = get_term_meta($cat_id, 'cat_banner', true);
			$cat_banner_pc_h = get_term_meta($cat_id, 'cat_banner_pc_h', true);
			$cat_banner_mb_h = get_term_meta($cat_id, 'cat_banner_mb_h', true);
		}
	}

}else{

	// 分类页面获取分类Banner设置
	$cat_banner = get_term_meta($cat, 'cat_banner', true);
	$cat_banner_pc_h = get_term_meta($cat, 'cat_banner_pc_h', true);
	$cat_banner_mb_h = get_term_meta($cat, 'cat_banner_mb_h', true);
	if( !is_array( $cat_banner ) ){
		$cat_id = get_category_root_id($cat);
		$cat_banner = get_term_meta($cat_id, 'cat_banner', true);
		$cat_banner_pc_h = get_term_meta($cat_id, 'cat_banner_pc_h', true);
		$cat_banner_mb_h = get_term_meta($cat_id, 'cat_banner_mb_h', true);
	}
}

if (is_array( $cat_banner )) {
?>
<div class="swiper-container module-banner<?php if(count( $cat_banner ) > 1){ echo ' swiper-banner'; }?>">
	<?php

	//限制图片高度
	$banner_h_pc = $cat_banner_pc_h ? '--banner_h_pc:'.$cat_banner_pc_h.'px;' : '';
	$banner_h_mb = $cat_banner_mb_h ? '--banner_h_mb:'.$cat_banner_mb_h.'px;' : '';

	$banner_h = ($banner_h_pc || $banner_h_mb) ? 'style="'.$banner_h_pc.$banner_h_mb.'"' : '';

	?>
	<div class="swiper-wrapper" <?php echo $banner_h;?>>
	<?php
	// 输出 img 标签的函数
	function output_image($img_url, $class) {
		if ($img_url) {
			echo '<img loading="lazy" class="'.esc_attr($class).'" src="'.esc_url($img_url).'" alt="'.esc_attr(dahuzi_get_image_alt($img_url)).'" />';
		}
	}
	// 生成图片标签的函数
	function generate_images($banner_img, $banner_img_mb) {
		// 如果存在移动端图片，使用它，否则使用桌面端图片
		$mobile_img = $banner_img_mb ?: $banner_img;

		output_image($banner_img, 'pc-img');
		output_image($mobile_img, 'mobile-img');
	}

	foreach ($cat_banner as $value) {
		$banner_img = $value['banner_img'] ?? '';
		$banner_img_mb = $value['banner_img_mb'] ?? '';
		$banner_url = $value['banner_url'] ?? '';
		$banner_title = $value['banner_title'] ?? '';
		$banner_desc = $value['banner_desc'] ?? '';
		$banner_target = $value['banner_target'] ?? '';
		$banner_target = ($banner_target == '_blank') ? 'target="_blank"' : '';
		$banner_button = $value['banner_button'] ?? '';

		//图片遮罩层和文本颜色
		$banner_text_color = $value['banner_text_color'] ?? '';
		$banner_mask_color = $value['banner_mask_color'] ?? '';
		$banner_button_bg = $value['banner_button_bg'] ?? '';
		$banner_button_co = $value['banner_button_co'] ?? '';

		$text_color = $banner_text_color ? '--banner_text_color:'.$banner_text_color.';' : '';
		$mask_color = $banner_mask_color ? '--banner_mask_color:'.$banner_mask_color.';' : '';
		$button_bg = $banner_button_bg && $banner_button ? '--banner_button_bg:'.$banner_button_bg.';' : '';
		$button_color = $banner_button_co && $banner_button ? '--banner_button_co:'.$banner_button_co.';' : '';

		$banner_mask_style = ($mask_color || $text_color || $button_bg || $button_color) ? 'style="'.$mask_color.$text_color.$button_bg.$button_color.'"' : '';
		$banner_mask_class = $mask_color ? ' banner-mask' : '';

		?>

		<div class="swiper-slide<?php echo $banner_mask_class;?>" <?php echo $banner_mask_style;?>>
			<?php

			//img标签
			generate_images($banner_img, $banner_img_mb);

			//幻灯片标题、描述
			if( !empty($banner_title) ){?>
			<div class="swiper-text">
				<div class="container">
					<h2><?php echo $banner_title;?></h2>
					<?php
					if( !empty($banner_desc) ){
						echo wpautop($banner_desc);
					}?>
					<?php
					if( !empty($banner_url) && !empty($banner_button) ){
						echo '<a class="banner-url" href="'.esc_url($banner_url).'" '.$banner_target.'>'.$banner_button.'</a>';
					}?>
				</div>
			</div>
			<?php
			}

			//a标签，未设置链接按钮时候显示
			if( !empty($banner_url) && empty($banner_button)  ){
				echo '<a class="banner-permalink" href="'.esc_url($banner_url).'" '.$banner_target.'></a>';
			}

			?>
		</div>

		<?php
	}
	?>
	</div>
	<div class="swiper-pagination"></div>
</div>
<?php
}
?>