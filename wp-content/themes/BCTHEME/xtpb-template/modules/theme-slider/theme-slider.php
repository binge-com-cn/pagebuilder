<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Slider {

	public function get_name() {
		return 'theme_slider';
	}

	public function get_title() {
		return '幻灯片/滑块';
	}

	public function get_class() {
		return 'xtpb-theme-slider';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-slider/assets/css/frontend.css',
		);
	}

	public function get_js_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-slider/assets/js/frontend.js',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-slider/assets/img/modules-slider.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'md_width' => array(
				'type'    => 'select',
				'title'   => '模块尺寸',
				'values'  => array(
					'full' => '全宽，自适应高度',
					'main' => '等宽，自适应高度',
				),
				'std'     => 'full',
				'section' => '模块设置',
			),
			'banner_h_pc' => array(
				'type'    => 'number',
				'title'   => '模块高度',
				'desc'    => 'px（PC端，留空则不限制）',
				'std'     => '',
				'section' => '模块设置',
			),
			'banner_h_mb' => array(
				'type'    => 'number',
				'title'   => '模块高度',
				'desc'    => 'px（手机端，留空则不限制）',
				'std'     => '',
				'section' => '模块设置',
			),
			'previous_next' => array(
				'type'    => 'select',
				'title'   => '切换按钮',
				'values'  => array(
					'none' => '不显示',
					'show' => '显示',
				),
				'std'     => 'show',
				'section' => '模块设置',
			),
			'nav_style' => array(
				'type'    => 'select',
				'title'   => '状态样式',
				'values'  => array(
					'none' => '不显示',
					'long' => '长条',
					'disc' => '圆点',
				),
				'std'     => 'disc',
				'section' => '模块设置',
			),
			'banner_items' => array(
				'type'    => 'repeatable',
				'title'   => '幻灯片内容',
				'label'   => 'banner_title',
				'section' => '内容设置',
				'std'     => array(
					array(
						'banner_type'        => 'img',
						'image'              => array( 'url' => '' ),
						'mb_image'           => array( 'url' => '' ),
						'banner_title'       => '幻灯片标题',
						'banner_desc'        => '这里是幻灯片描述内容。',
						'banner_text_color'  => '#ffffff',
						'banner_mask_color'  => 'rgba(0,0,0,.28)',
						'banner_align'       => 'default',
						'banner_url'         => array( 'link' => '', 'window' => false, 'nofolow' => false ),
						'banner_button'      => '',
						'banner_button_bg'   => '#000000',
						'banner_button_co'   => '#ffffff',
					),
				),
				'attr'    => array(
					'banner_type' => array(
						'type'   => 'select',
						'title'  => '内容类型',
						'values' => array(
							'img'   => '图片',
							'video' => '视频',
						),
						'std'    => 'img',
					),
					'video_url' => array(
						'type'    => 'text',
						'title'   => '视频链接',
						'depends' => array( array( 'banner_type', '=', 'video' ) ),
					),
					'image' => array(
						'type'    => 'media',
						'title'   => '图片(PC端)',
						'desc'    => '建议尺寸：1920 × 550 px',
						'depends' => array( array( 'banner_type', '=', 'img' ) ),
					),
					'mb_image' => array(
						'type'    => 'media',
						'title'   => '图片(手机端)',
						'desc'    => '建议尺寸：790 × 560 px',
						'depends' => array( array( 'banner_type', '=', 'img' ) ),
					),
					'banner_title' => array(
						'type'  => 'text',
						'title' => '标题',
					),
					'banner_desc' => array(
						'type'    => 'textarea',
						'title'   => '描述',
						'depends' => array( array( 'banner_title', '!=', '' ) ),
					),
					'banner_text_color' => array(
						'type'    => 'color',
						'title'   => '文本颜色',
						'desc'    => '标题和描述的文本颜色',
						'depends' => array( array( 'banner_title', '!=', '' ) ),
					),
					'banner_mask_color' => array(
						'type'  => 'color',
						'title' => '图片滤镜',
						'desc'  => '给图片增加一个自定义颜色的遮罩层',
					),
					'banner_align' => array(
						'type'    => 'select',
						'title'   => '文本对齐方式',
						'values'  => array(
							'default' => '左对齐',
							'center'  => '居中显示',
						),
						'std'     => 'default',
						'depends' => array( array( 'banner_title', '!=', '' ) ),
					),
					'banner_url' => array(
						'type'  => 'link',
						'title' => '跳转链接',
						'std'   => array( 'link' => '', 'window' => false, 'nofolow' => false ),
					),
					'banner_button' => array(
						'type'  => 'text',
						'title' => '按钮文本',
						'desc'  => '留空则点击图片进行跳转',
					),
					'banner_button_bg' => array(
						'type'    => 'color',
						'title'   => '按钮颜色',
						'desc'    => '整个按钮颜色',
						'depends' => array( array( 'banner_button', '!=', '' ) ),
					),
					'banner_button_co' => array(
						'type'    => 'color',
						'title'   => '文本颜色',
						'desc'    => '按钮文本颜色',
						'depends' => array( array( 'banner_button', '!=', '' ) ),
					),
				),
			),
		);
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items    = isset( $settings['banner_items'] ) && is_array( $settings['banner_items'] ) ? $settings['banner_items'] : array();

		if ( empty( $items ) ) {
			return '';
		}

		$md_width      = ! empty( $settings['md_width'] ) ? sanitize_key( $settings['md_width'] ) : 'full';
		$banner_h_pc   = ! empty( $settings['banner_h_pc'] ) ? absint( $settings['banner_h_pc'] ) : 0;
		$banner_h_mb   = ! empty( $settings['banner_h_mb'] ) ? absint( $settings['banner_h_mb'] ) : 0;
		$previous_next = ! empty( $settings['previous_next'] ) ? sanitize_key( $settings['previous_next'] ) : 'none';
		$nav_style     = ! empty( $settings['nav_style'] ) ? sanitize_key( $settings['nav_style'] ) : 'none';
		$style         = $this->get_banner_height_style( $banner_h_pc, $banner_h_mb );

		ob_start();

		if ( 'full' !== $md_width ) {
			echo '<div class="section"><div class="container">';
		}
		?>
		<div class="swiper swiper-container module-banner xtpb-theme-slider-banner"<?php echo $style ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
			<div class="swiper-wrapper">
				<?php foreach ( $items as $item ) : ?>
					<?php echo $this->render_slide( is_array( $item ) ? $item : array() ); ?>
				<?php endforeach; ?>
			</div>
			<?php if ( 'none' !== $nav_style ) : ?>
				<div class="swiper-pagination pagination-<?php echo esc_attr( $nav_style ); ?>"></div>
			<?php endif; ?>
			<?php if ( 'none' !== $previous_next ) : ?>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			<?php endif; ?>
		</div>
		<?php
		if ( 'full' !== $md_width ) {
			echo '</div></div>';
		}

		return ob_get_clean();
	}

	private function render_slide( $item ) {
		$banner_type = ! empty( $item['banner_type'] ) ? sanitize_key( $item['banner_type'] ) : 'img';
		$video_url   = ! empty( $item['video_url'] ) ? esc_url( $item['video_url'] ) : '';
		$image_url   = $this->get_media_url( $item, 'image' );
		$mb_url      = $this->get_media_url( $item, 'mb_image' );
		$link        = $this->get_link_data( isset( $item['banner_url'] ) ? $item['banner_url'] : '' );
		$title       = ! empty( $item['banner_title'] ) ? wp_kses_post( $item['banner_title'] ) : '';
		$desc        = ! empty( $item['banner_desc'] ) ? wp_kses_post( $item['banner_desc'] ) : '';
		$align       = ! empty( $item['banner_align'] ) ? sanitize_html_class( $item['banner_align'] ) : 'default';
		$button      = ! empty( $item['banner_button'] ) ? sanitize_text_field( $item['banner_button'] ) : '';
		$style       = $this->get_slide_style( $item, $button );
		$slide_class = ! empty( $item['banner_mask_color'] ) ? ' banner-mask' : '';
		$slide_class .= $mb_url ? ' has-mobile-img' : '';

		ob_start();
		?>
		<div class="swiper-slide<?php echo esc_attr( $slide_class ); ?>"<?php echo $style ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
			<?php if ( 'video' === $banner_type && $video_url ) : ?>
				<video loop muted preload="auto" playsinline><source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4"></video>
			<?php elseif ( 'img' === $banner_type ) : ?>
				<?php if ( $image_url ) : ?>
					<img loading="lazy" class="pc-img" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $this->get_image_alt( $image_url ) ); ?>">
				<?php endif; ?>
				<?php if ( $mb_url ) : ?>
					<img loading="lazy" class="mobile-img" src="<?php echo esc_url( $mb_url ); ?>" alt="<?php echo esc_attr( $this->get_image_alt( $mb_url ) ); ?>">
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $title || $desc ) : ?>
				<div class="swiper-text align-<?php echo esc_attr( $align ); ?>">
					<div class="container">
						<?php if ( $title ) : ?>
							<h2><?php echo $title; ?></h2>
						<?php endif; ?>
						<?php echo $desc ? wpautop( $desc ) : ''; ?>
						<?php if ( $link['url'] && $button ) : ?>
							<a class="banner-url" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>><?php echo esc_html( $button ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $link['url'] && ! $button ) : ?>
				<a class="swiper-url" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>></a>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	private function get_media_url( $item, $key ) {
		if ( empty( $item[ $key ] ) ) {
			return '';
		}

		if ( is_array( $item[ $key ] ) && ! empty( $item[ $key ]['url'] ) ) {
			return $item[ $key ]['url'];
		}

		return is_string( $item[ $key ] ) ? $item[ $key ] : '';
	}

	private function get_link_data( $link ) {
		if ( is_array( $link ) ) {
			$url      = ! empty( $link['link'] ) ? $link['link'] : '';
			$target   = ! empty( $link['window'] );
			$nofollow = ! empty( $link['nofolow'] ) || ! empty( $link['nofollow'] );
		} else {
			$url      = is_string( $link ) ? $link : '';
			$target   = false;
			$nofollow = false;
		}

		$rel = array();
		if ( $target ) {
			$rel[] = 'noopener';
		}
		if ( $nofollow ) {
			$rel[] = 'nofollow';
		}

		return array(
			'url'    => $url,
			'target' => $target,
			'rel'    => implode( ' ', $rel ),
		);
	}

	private function get_banner_height_style( $pc, $mb ) {
		$style = '';
		if ( $pc ) {
			$style .= '--banner_h_pc:' . $pc . 'px;';
		}
		if ( $mb ) {
			$style .= '--banner_h_mb:' . $mb . 'px;';
		}

		return $style;
	}

	private function get_slide_style( $item, $button ) {
		$style = '';

		if ( ! empty( $item['banner_mask_color'] ) ) {
			$style .= '--banner_mask_color:' . sanitize_text_field( $item['banner_mask_color'] ) . ';';
		}
		if ( ! empty( $item['banner_text_color'] ) ) {
			$style .= '--banner_text_color:' . sanitize_text_field( $item['banner_text_color'] ) . ';';
		}
		if ( $button && ! empty( $item['banner_button_bg'] ) ) {
			$style .= '--banner_button_bg:' . sanitize_text_field( $item['banner_button_bg'] ) . ';';
		}
		if ( $button && ! empty( $item['banner_button_co'] ) ) {
			$style .= '--banner_button_co:' . sanitize_text_field( $item['banner_button_co'] ) . ';';
		}

		return $style;
	}

	private function get_image_alt( $image_url ) {
		if ( function_exists( 'dahuzi_get_image_alt' ) ) {
			return dahuzi_get_image_alt( $image_url );
		}

		return '';
	}

	public function getTemplate() {
		return '
		<#
		var items = _.isArray(data.banner_items) ? data.banner_items : [];
		var mdWidth = data.md_width || "full";
		var bannerHPc = data.banner_h_pc || "";
		var bannerHMb = data.banner_h_mb || "";
		var previousNext = data.previous_next || "none";
		var navStyle = data.nav_style || "none";
		var bannerStyle = "";

		if (bannerHPc) {
			bannerStyle += "--banner_h_pc:" + bannerHPc + "px;";
		}
		if (bannerHMb) {
			bannerStyle += "--banner_h_mb:" + bannerHMb + "px;";
		}

		function mediaUrl(value) {
			if (!value) {
				return "";
			}
			if (typeof value === "object") {
				return value.url || "";
			}
			return value;
		}

		function linkData(value) {
			if (!value) {
				return { url: "", target: false, rel: "" };
			}
			if (typeof value === "object") {
				var rel = [];
				if (value.window) {
					rel.push("noopener");
				}
				if (value.nofolow || value.nofollow) {
					rel.push("nofollow");
				}
				return { url: value.link || "", target: !!value.window, rel: rel.join(" ") };
			}
			return { url: value, target: false, rel: "" };
		}
		#>

		<# if (mdWidth !== "full") { #>
			<div class="section"><div class="container">
		<# } #>

		<div class="swiper swiper-container module-banner xtpb-theme-slider-banner"<# if (bannerStyle) { #> style="{{ bannerStyle }}"<# } #>>
			<div class="swiper-wrapper">
				<# _.forEach(items, function(item) {
					item = item || {};
					var bannerType = item.banner_type || "img";
					var imageUrl = mediaUrl(item.image);
					var mbImageUrl = mediaUrl(item.mb_image);
					var videoUrl = item.video_url || "";
					var link = linkData(item.banner_url);
					var title = item.banner_title || "";
					var desc = item.banner_desc || "";
					var align = item.banner_align || "default";
					var button = item.banner_button || "";
					var slideStyle = "";
					var slideClass = item.banner_mask_color ? " banner-mask" : "";
					slideClass += mbImageUrl ? " has-mobile-img" : "";

					if (item.banner_mask_color) {
						slideStyle += "--banner_mask_color:" + item.banner_mask_color + ";";
					}
					if (item.banner_text_color) {
						slideStyle += "--banner_text_color:" + item.banner_text_color + ";";
					}
					if (button && item.banner_button_bg) {
						slideStyle += "--banner_button_bg:" + item.banner_button_bg + ";";
					}
					if (button && item.banner_button_co) {
						slideStyle += "--banner_button_co:" + item.banner_button_co + ";";
					}
				#>
					<div class="swiper-slide{{ slideClass }}"<# if (slideStyle) { #> style="{{ slideStyle }}"<# } #>>
						<# if (bannerType === "video" && videoUrl) { #>
							<video loop muted preload="auto" playsinline><source src="{{ videoUrl }}" type="video/mp4"></video>
						<# } else if (bannerType === "img") { #>
							<# if (imageUrl) { #>
								<img loading="lazy" class="pc-img" src="{{ imageUrl }}" alt="">
							<# } #>
							<# if (mbImageUrl) { #>
								<img loading="lazy" class="mobile-img" src="{{ mbImageUrl }}" alt="">
							<# } #>
						<# } #>

						<# if (title || desc) { #>
							<div class="swiper-text align-{{ align }}">
								<div class="container">
									<# if (title) { #>
										<h2>{{{ title }}}</h2>
									<# } #>
									<# if (desc) { #>
										<p>{{{ desc.replace(/\\n/g, "<br>") }}}</p>
									<# } #>
									<# if (link.url && button) { #>
										<a class="banner-url" href="{{ link.url }}"<# if (link.target) { #> target="_blank"<# } #><# if (link.rel) { #> rel="{{ link.rel }}"<# } #>>{{ button }}</a>
									<# } #>
								</div>
							</div>
						<# } #>

						<# if (link.url && !button) { #>
							<a class="swiper-url" href="{{ link.url }}"<# if (link.target) { #> target="_blank"<# } #><# if (link.rel) { #> rel="{{ link.rel }}"<# } #>></a>
						<# } #>
					</div>
				<# }); #>
			</div>

			<# if (navStyle !== "none") { #>
				<div class="swiper-pagination pagination-{{ navStyle }}"></div>
			<# } #>
			<# if (previousNext !== "none") { #>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			<# } #>
		</div>

		<# if (mdWidth !== "full") { #>
			</div></div>
		<# } #>
		';
	}
}
