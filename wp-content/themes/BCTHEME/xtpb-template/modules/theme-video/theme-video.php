<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Video {

	public function get_name() {
		return 'theme_video';
	}

	public function get_title() {
		return '视频';
	}

	public function get_class() {
		return 'xtpb-theme-video';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-video/assets/css/frontend.css',
		);
	}

	public function get_js_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-video/assets/js/frontend.js',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-video/assets/img/modules-video.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'module_title' => array(
				'type'    => 'text',
				'title'   => '模块标题',
				'std'     => '视频',
				'section' => '模块设置',
			),
			'module_desc' => array(
				'type'    => 'textarea',
				'title'   => '模块简介',
				'std'     => '',
				'section' => '模块设置',
			),
			'video_autoplay' => array(
				'type'    => 'switch',
				'title'   => '自动播放',
				'std'     => 0,
				'section' => '视频设置',
			),
			'video_transition' => array(
				'type'    => 'switch',
				'title'   => '滚动过渡动画',
				'std'     => 0,
				'section' => '视频设置',
			),
			'video_bg' => array(
				'type'    => 'media',
				'title'   => '视频封面',
				'std'     => array( 'url' => '' ),
				'section' => '视频设置',
			),
			'video_url' => array(
				'type'    => 'text',
				'title'   => '视频直达链接',
				'desc'    => '请输入视频文件直达链接，不支持第三方平台播放页面',
				'std'     => '',
				'section' => '视频设置',
			),
			'video_pc_h' => array(
				'type'    => 'number',
				'title'   => '视频高度（PC）',
				'desc'    => 'px，留空显示视频原始比例',
				'std'     => '',
				'section' => '样式设置',
			),
			'video_mb_h' => array(
				'type'    => 'number',
				'title'   => '视频高度（手机）',
				'desc'    => 'px，留空显示视频原始比例',
				'std'     => '',
				'section' => '样式设置',
			),
		);
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$title    = ! empty( $settings['module_title'] ) ? sanitize_text_field( $settings['module_title'] ) : '';
		$desc     = ! empty( $settings['module_desc'] ) ? sanitize_textarea_field( $settings['module_desc'] ) : '';
		$video    = ! empty( $settings['video_url'] ) ? esc_url( $settings['video_url'] ) : '';
		$poster   = $this->media_url( isset( $settings['video_bg'] ) ? $settings['video_bg'] : '' );
		$pc_h     = ! empty( $settings['video_pc_h'] ) ? max( 0, absint( $settings['video_pc_h'] ) ) . 'px' : 'auto';
		$mb_h     = ! empty( $settings['video_mb_h'] ) ? max( 0, absint( $settings['video_mb_h'] ) ) . 'px' : 'auto';
		$classes  = 'section video-wrapper';

		if ( ! empty( $settings['video_transition'] ) ) {
			$classes .= ' video-transition';
		}

		ob_start();
		?>
		<div class="module-theme-video">
			<div class="<?php echo esc_attr( $classes ); ?>">
				<div class="container">
					<?php if ( $title || $desc ) : ?>
						<div class="section-title">
							<?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?>
							<?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="video-box" style="--video-h-pc:<?php echo esc_attr( $pc_h ); ?>;--video-h-mb:<?php echo esc_attr( $mb_h ); ?>;">
						<video<?php echo $video ? ' src="' . esc_url( $video ) . '"' : ''; ?><?php echo $poster ? ' poster="' . esc_url( $poster ) . '"' : ''; ?> preload="none" loop muted playsinline<?php echo ! empty( $settings['video_autoplay'] ) ? ' autoplay' : ''; ?>></video>
						<?php if ( $video ) : ?>
							<div class="video-fancybox"><a data-fancybox href="<?php echo esc_url( $video ); ?>" aria-label="<?php esc_attr_e( '播放视频', 'modular' ); ?>"><i class="ri-play-large-line"></i></a></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private function media_url( $media ) {
		if ( is_array( $media ) ) {
			return isset( $media['url'] ) ? $media['url'] : '';
		}

		return is_string( $media ) ? $media : '';
	}

	public function getTemplate() {
		return '
		<#
		var poster = data.video_bg && typeof data.video_bg === "object" ? (data.video_bg.url || "") : (data.video_bg || "");
		var pcHeight = data.video_pc_h ? parseInt(data.video_pc_h, 10) + "px" : "auto";
		var mbHeight = data.video_mb_h ? parseInt(data.video_mb_h, 10) + "px" : "auto";
		var sectionClass = "section video-wrapper" + (data.video_transition ? " video-transition" : "");
		#>
		<div class="module-theme-video"><div class="{{ sectionClass }}"><div class="container">
			<# if (data.module_title || data.module_desc) { #><div class="section-title"><# if (data.module_title) { #><strong>{{ data.module_title }}</strong><# } #><# if (data.module_desc) { #><p>{{ data.module_desc }}</p><# } #></div><# } #>
			<div class="video-box" style="--video-h-pc:{{ pcHeight }};--video-h-mb:{{ mbHeight }};"><video<# if (data.video_url) { #> src="{{ data.video_url }}"<# } #><# if (poster) { #> poster="{{ poster }}"<# } #> preload="none" loop muted playsinline<# if (data.video_autoplay) { #> autoplay<# } #>></video><# if (data.video_url) { #><div class="video-fancybox"><span><i class="ri-play-large-line"></i></span></div><# } #></div>
		</div></div></div>';
	}
}
