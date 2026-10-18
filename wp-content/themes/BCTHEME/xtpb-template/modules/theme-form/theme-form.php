<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Form {

	public function get_name() {
		return 'theme_form';
	}

	public function get_title() {
		return '表单';
	}

	public function get_class() {
		return 'xtpb-theme-form';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-form/assets/css/frontend.css',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-form/assets/img/modules-form.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'module_title' => array(
				'type'    => 'text',
				'title'   => '模块标题',
				'std'     => '联系我们',
				'section' => '模块设置',
			),
			'module_desc' => array(
				'type'    => 'textarea',
				'title'   => '模块简介',
				'std'     => '',
				'section' => '模块设置',
			),
			'bg_img' => array(
				'type'    => 'media',
				'title'   => '背景图片',
				'std'     => array( 'url' => '' ),
				'section' => '模块设置',
			),
			'radius' => array(
				'type'    => 'number',
				'title'   => '圆角',
				'desc'    => 'px（建议 8px，范围 0–100）',
				'std'     => 8,
				'min'     => 0,
				'max'     => 100,
				'section' => '模块设置',
			),
			'item_style' => array(
				'type'    => 'select',
				'title'   => '左侧内容',
				'values'  => array(
					'text' => '文本',
					'img'  => '图片',
				),
				'std'     => 'text',
				'section' => '内容设置',
			),
			'item_desc' => array(
				'type'    => 'editor',
				'title'   => '文本内容',
				'std'     => '',
				'depends' => array( array( 'item_style', '=', 'text' ) ),
				'section' => '内容设置',
			),
			'item_img' => array(
				'type'    => 'media',
				'title'   => '特色图片',
				'std'     => array( 'url' => '' ),
				'depends' => array( array( 'item_style', '=', 'img' ) ),
				'section' => '内容设置',
			),
			'form_title' => array(
				'type'    => 'text',
				'title'   => '表单标题',
				'std'     => '',
				'section' => '表单设置',
			),
			'form_id' => array(
				'type'    => 'select',
				'title'   => 'WPJAM 表单',
				'values'  => $this->get_form_options(),
				'std'     => '',
				'section' => '表单设置',
			),
		);
	}

	private function get_form_options() {
		$options = array( '' => '请选择表单' );
		$forms   = get_posts(
			array(
				'post_type'      => 'form',
				'post_status'    => array( 'publish', 'draft', 'private' ),
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		foreach ( $forms as $form ) {
			$options[ (string) $form->ID ] = $form->post_title ?: sprintf( '表单 #%d', $form->ID );
		}

		return $options;
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$style    = ! empty( $settings['item_style'] ) ? sanitize_key( $settings['item_style'] ) : 'text';
		$title    = ! empty( $settings['module_title'] ) ? sanitize_text_field( $settings['module_title'] ) : '';
		$desc     = ! empty( $settings['module_desc'] ) ? sanitize_textarea_field( $settings['module_desc'] ) : '';
		$image    = $this->get_media_url( isset( $settings['item_img'] ) ? $settings['item_img'] : '' );
		$bg_image = $this->get_media_url( isset( $settings['bg_img'] ) ? $settings['bg_img'] : '' );
		$radius   = isset( $settings['radius'] ) ? min( 100, absint( $settings['radius'] ) ) : 0;
		$form_id  = ! empty( $settings['form_id'] ) ? absint( $settings['form_id'] ) : 0;

		$section_style = $bg_image ? 'background:url(' . esc_url_raw( $bg_image ) . ') no-repeat center 0 / cover;' : '';
		$box_style     = $radius ? '--btn-radius:4px;--border-radius:' . $radius . 'px;' : '';

		ob_start();
		?>
		<div class="module-theme-form">
			<div class="section"<?php echo $section_style ? ' style="' . esc_attr( $section_style ) . '"' : ''; ?>>
				<div class="container">
					<?php if ( 'img' === $style && ( $title || $desc ) ) : ?>
						<div class="section-title">
							<?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?>
							<?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="form-box<?php echo 'img' === $style ? ' form-img-style' : ''; ?>"<?php echo $box_style ? ' style="' . esc_attr( $box_style ) . '"' : ''; ?>>
						<div class="form-left">
							<?php if ( 'img' === $style ) : ?>
								<?php if ( $image ) : ?><div class="form-img"><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>"></div><?php endif; ?>
							<?php else : ?>
								<div class="form-text">
									<?php if ( $title ) : ?><h3><?php echo esc_html( $title ); ?></h3><?php endif; ?>
									<?php echo ! empty( $settings['item_desc'] ) ? do_shortcode( wp_kses_post( $settings['item_desc'] ) ) : ''; ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="form-right">
							<?php if ( function_exists( 'wpjam_render_form' ) ) : ?>
								<?php wpjam_render_form( $form_id, ! empty( $settings['form_title'] ) ? sanitize_text_field( $settings['form_title'] ) : '' ); ?>
							<?php else : ?>
								<p>WPJAM Forms 当前不可用。</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private function get_media_url( $value ) {
		return is_array( $value ) ? ( isset( $value['url'] ) ? $value['url'] : '' ) : ( is_string( $value ) ? $value : '' );
	}

}
