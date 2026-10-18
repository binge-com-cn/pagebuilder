<?php

/**
 * Template: 团队页面Banner
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-08-05 17:29:40
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-11-10 21:57:37
 */

//获取分类Banner设置
$team_banner = wpjam_theme_get_setting('team_banner');
$team_banner_pc_h = wpjam_theme_get_setting('team_banner_pc_h');
$team_banner_mb_h = wpjam_theme_get_setting('team_banner_mb_h');

if (is_array( $team_banner )) {
?>
<div class="swiper-container module-banner<?php if(count( $team_banner ) > 1){ echo ' swiper-banner'; }?>">
	<?php

	//限制图片高度
	$banner_h_pc = $team_banner_pc_h ? '--banner_h_pc:'.$team_banner_pc_h.'px;' : '';
	$banner_h_mb = $team_banner_mb_h ? '--banner_h_mb:'.$team_banner_mb_h.'px;' : '';

	$banner_h = ($banner_h_pc || $banner_h_mb) ? 'style="'.$banner_h_pc.$banner_h_mb.'"' : '';

	?>
	<div class="swiper-wrapper" <?php echo $banner_h;?>>
	<?php
	// 输出 img 标签的函数
	function output_image($img_url, $class) {
		if ($img_url) {
			echo '<img loading="lazy" class="'.esc_attr($class).'" src="'.esc_url($img_url).'" alt="" />';
		}
	}
	// 生成图片标签的函数
	function generate_images($banner_img, $banner_img_mb) {
		// 如果存在移动端图片，使用它，否则使用桌面端图片
		$mobile_img = $banner_img_mb ?: $banner_img;

		output_image($banner_img, 'pc-img');
		output_image($mobile_img, 'mobile-img');
	}

	foreach ($team_banner as $value) {
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