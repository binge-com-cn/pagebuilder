<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_About {

	public function get_name() {
		return 'theme_about';
	}

	public function get_title() {
		return '关于我们';
	}

	public function get_class() {
		return 'xtpb-theme-about';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-about/assets/css/frontend.css',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-about/assets/img/modules-about.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'module_title' => array(
				'type'    => 'text',
				'title'   => '模块标题',
				'std'     => '关于我们',
				'section' => '模块设置',
			),
			'module_desc' => array(
				'type'    => 'textarea',
				'title'   => '模块简介',
				'std'     => '',
				'section' => '模块设置',
			),
			'about_style' => array(
				'type'    => 'select',
				'title'   => '模块样式',
				'values'  => array(
					'style_1' => '图片靠左显示',
					'style_2' => '图片靠右显示',
				),
				'std'     => 'style_1',
				'section' => '模块设置',
			),
			'about_subtitle' => array(
				'type'    => 'text',
				'title'   => '标题',
				'std'     => '专注企业数字化服务',
				'section' => '内容设置',
			),
			'about_desc' => array(
				'type'    => 'textarea',
				'title'   => '描述',
				'std'     => '在这里填写关于我们的介绍内容。',
				'section' => '内容设置',
			),
			'about_type' => array(
				'type'    => 'select',
				'title'   => '媒体类型',
				'values'  => array(
					'img'   => '图片',
					'video' => '视频',
				),
				'std'     => 'img',
				'section' => '内容设置',
			),
			'about_img' => array(
				'type'    => 'media',
				'title'   => '特色图片',
				'std'     => array( 'url' => '' ),
				'section' => '内容设置',
			),
			'about_video' => array(
				'type'    => 'text',
				'title'   => '视频地址',
				'std'     => '',
				'depends' => array( array( 'about_type', '=', 'video' ) ),
				'section' => '内容设置',
			),
			'about_btn1_text' => array(
				'type'    => 'text',
				'title'   => '按钮-1 文本',
				'std'     => '',
				'section' => '内容设置',
			),
			'about_btn1_url' => array(
				'type'    => 'text',
				'title'   => '按钮-1 链接',
				'std'     => '',
				'depends' => array( array( 'about_btn1_text', '!=', '' ) ),
				'section' => '内容设置',
			),
			'about_btn2_text' => array(
				'type'    => 'text',
				'title'   => '按钮-2 文本',
				'std'     => '',
				'section' => '内容设置',
			),
			'about_btn2_url' => array(
				'type'    => 'text',
				'title'   => '按钮-2 链接',
				'std'     => '',
				'depends' => array( array( 'about_btn2_text', '!=', '' ) ),
				'section' => '内容设置',
			),
			'about_tel_title' => array(
				'type'    => 'text',
				'title'   => '号码标题',
				'std'     => '',
				'section' => '内容设置',
			),
			'about_tel_number' => array(
				'type'    => 'text',
				'title'   => '电话号码',
				'std'     => '',
				'section' => '内容设置',
			),
		);
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$title    = ! empty( $settings['module_title'] ) ? sanitize_text_field( $settings['module_title'] ) : '';
		$desc     = ! empty( $settings['module_desc'] ) ? sanitize_textarea_field( $settings['module_desc'] ) : '';
		$subtitle = ! empty( $settings['about_subtitle'] ) ? sanitize_text_field( $settings['about_subtitle'] ) : '';
		$content  = ! empty( $settings['about_desc'] ) ? wp_kses_post( $settings['about_desc'] ) : '';
		$style    = ! empty( $settings['about_style'] ) ? sanitize_html_class( $settings['about_style'] ) : 'style_1';
		$type     = ! empty( $settings['about_type'] ) ? sanitize_key( $settings['about_type'] ) : 'img';
		$image    = $this->get_media_url( isset( $settings['about_img'] ) ? $settings['about_img'] : '' );
		$video    = ! empty( $settings['about_video'] ) ? esc_url( $settings['about_video'] ) : '';

		ob_start();
		?>
		<div class="module-theme-about">
			<div class="section">
				<div class="container">
					<?php if ( $title || $desc ) : ?>
						<div class="section-title">
							<?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?>
							<?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="md-about <?php echo esc_attr( $style ); ?>">
						<?php if ( $image ) : ?>
							<div class="about-img">
								<img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $this->get_image_alt( $image ) ); ?>">
								<?php if ( 'video' === $type && $video ) : ?>
									<a data-fancybox data-type="youtube" href="<?php echo esc_url( $video ); ?>" class="play-view" rel="nofollow"><span class="video-play-icon"><i class="ri-video-fill"></i></span></a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="about-text">
							<?php if ( $subtitle ) : ?><h3><?php echo esc_html( $subtitle ); ?></h3><?php endif; ?>
							<?php echo $content ? wpautop( $content ) : ''; ?>
							<?php echo $this->render_actions( $settings ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private function render_actions( $settings ) {
		$button_1 = ! empty( $settings['about_btn1_text'] ) ? sanitize_text_field( $settings['about_btn1_text'] ) : '';
		$button_2 = ! empty( $settings['about_btn2_text'] ) ? sanitize_text_field( $settings['about_btn2_text'] ) : '';
		$tel_title = ! empty( $settings['about_tel_title'] ) ? sanitize_text_field( $settings['about_tel_title'] ) : '';
		$tel_number = ! empty( $settings['about_tel_number'] ) ? sanitize_text_field( $settings['about_tel_number'] ) : '';

		if ( ! $button_1 && ! $button_2 ) {
			return '';
		}

		$output = '<div class="btn">';
		if ( $button_1 ) {
			$output .= '<a href="' . esc_url( isset( $settings['about_btn1_url'] ) ? $settings['about_btn1_url'] : '' ) . '">' . esc_html( $button_1 ) . '</a>';
		}
		if ( $button_2 ) {
			$output .= '<a href="' . esc_url( isset( $settings['about_btn2_url'] ) ? $settings['about_btn2_url'] : '' ) . '">' . esc_html( $button_2 ) . '</a>';
		}
		if ( $tel_title || $tel_number ) {
			$output .= '<div class="about-tel">';
			$output .= $tel_title ? '<span class="tel-title">' . esc_html( $tel_title ) . '</span>' : '';
			$output .= $tel_number ? '<span class="tel-number">' . esc_html( $tel_number ) . '</span>' : '';
			$output .= '</div>';
		}
		return $output . '</div>';
	}

	private function get_media_url( $value ) {
		return is_array( $value ) ? ( isset( $value['url'] ) ? $value['url'] : '' ) : ( is_string( $value ) ? $value : '' );
	}

	private function get_image_alt( $url ) {
		return function_exists( 'dahuzi_get_image_alt' ) ? dahuzi_get_image_alt( $url ) : '';
	}

	public function getTemplate() {
		return '
		<#
		var image = data.about_img && typeof data.about_img === "object" ? (data.about_img.url || "") : (data.about_img || "");
		var style = data.about_style || "style_1";
		#>
		<div class="module-theme-about">
			<div class="section"><div class="container">
				<# if (data.module_title || data.module_desc) { #>
					<div class="section-title">
						<# if (data.module_title) { #><strong>{{ data.module_title }}</strong><# } #>
						<# if (data.module_desc) { #><p>{{ data.module_desc }}</p><# } #>
					</div>
				<# } #>
				<div class="md-about {{ style }}">
					<# if (image) { #>
						<div class="about-img">
							<img loading="lazy" src="{{ image }}" alt="">
							<# if (data.about_type === "video" && data.about_video) { #><a data-fancybox data-type="youtube" href="{{ data.about_video }}" class="play-view" rel="nofollow"><span class="video-play-icon"><i class="ri-video-fill"></i></span></a><# } #>
						</div>
					<# } #>
					<div class="about-text">
						<# if (data.about_subtitle) { #><h3>{{ data.about_subtitle }}</h3><# } #>
						<# if (data.about_desc) { #><p>{{ data.about_desc }}</p><# } #>
						<# if (data.about_btn1_text || data.about_btn2_text) { #>
							<div class="btn">
								<# if (data.about_btn1_text) { #><a href="{{ data.about_btn1_url || "" }}">{{ data.about_btn1_text }}</a><# } #>
								<# if (data.about_btn2_text) { #><a href="{{ data.about_btn2_url || "" }}">{{ data.about_btn2_text }}</a><# } #>
								<# if (data.about_tel_title || data.about_tel_number) { #><div class="about-tel"><# if (data.about_tel_title) { #><span class="tel-title">{{ data.about_tel_title }}</span><# } #><# if (data.about_tel_number) { #><span class="tel-number">{{ data.about_tel_number }}</span><# } #></div><# } #>
							</div>
						<# } #>
					</div>
				</div>
			</div></div>
		</div>';
	}
}
