<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Aptitude {

	public function get_name() {
		return 'theme_aptitude';
	}

	public function get_title() {
		return '荣誉资质';
	}

	public function get_class() {
		return 'xtpb-theme-aptitude';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-aptitude/assets/css/frontend.css',
		);
	}

	public function get_js_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-aptitude/assets/js/frontend.js',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-aptitude/assets/img/modules-aptitude.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'module_title' => array(
				'type'    => 'text',
				'title'   => '模块标题',
				'std'     => '荣誉资质',
				'section' => '模块设置',
			),
			'module_desc' => array(
				'type'    => 'textarea',
				'title'   => '模块简介',
				'std'     => '',
				'section' => '模块设置',
			),
			'aptitude_items' => array(
				'type'    => 'repeatable',
				'title'   => '资质图片',
				'label'   => 'item_title',
				'section' => '内容设置',
				'std'     => array(
					array(
						'item_img'   => array( 'url' => '' ),
						'item_title' => '资质标题',
						'item_url'   => '',
					),
				),
				'attr'    => array(
					'item_img' => array(
						'type'  => 'media',
						'title' => '图片',
						'desc'  => '建议尺寸：415 × 530 px',
					),
					'item_title' => array(
						'type'  => 'text',
						'title' => '图片标题',
					),
					'item_url' => array(
						'type'  => 'text',
						'title' => '跳转链接',
						'desc'  => '留空时点击图片打开大图',
					),
				),
			),
		);
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items    = isset( $settings['aptitude_items'] ) && is_array( $settings['aptitude_items'] ) ? $settings['aptitude_items'] : array();

		if ( empty( $items ) ) {
			return '';
		}

		$title = ! empty( $settings['module_title'] ) ? sanitize_text_field( $settings['module_title'] ) : '';
		$desc  = ! empty( $settings['module_desc'] ) ? sanitize_textarea_field( $settings['module_desc'] ) : '';

		ob_start();
		?>
		<div class="module-theme-aptitude">
			<div class="section">
				<div class="container">
					<?php if ( $title || $desc ) : ?>
						<div class="section-title">
							<?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?>
							<?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="md-aptitude">
						<div class="swiper swiper-container aptitude-list xtpb-theme-aptitude-slider">
							<ul class="swiper-wrapper">
								<?php foreach ( $items as $item ) : ?>
									<?php echo $this->render_item( is_array( $item ) ? $item : array() ); ?>
								<?php endforeach; ?>
							</ul>
							<div class="swiper-pagination"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private function render_item( $item ) {
		$image = $this->get_media_url( isset( $item['item_img'] ) ? $item['item_img'] : '' );
		$title = ! empty( $item['item_title'] ) ? sanitize_text_field( $item['item_title'] ) : '';
		$url   = ! empty( $item['item_url'] ) ? esc_url( $item['item_url'] ) : '';

		if ( ! $image ) {
			return '';
		}

		ob_start();
		?>
		<li class="swiper-slide">
			<div class="aptitude-item">
				<a href="<?php echo esc_url( $url ?: $image ); ?>"<?php echo $url ? ' target="_blank" rel="noopener"' : ' data-fancybox="aptitude-img"'; ?>>
					<div class="img-center"><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $this->get_image_alt( $image ) ); ?>"></div>
					<?php if ( $title ) : ?><p><?php echo esc_html( $title ); ?></p><?php endif; ?>
				</a>
			</div>
		</li>
		<?php
		return ob_get_clean();
	}

	private function get_media_url( $value ) {
		return is_array( $value ) ? ( isset( $value['url'] ) ? $value['url'] : '' ) : ( is_string( $value ) ? $value : '' );
	}

	private function get_image_alt( $url ) {
		return function_exists( 'dahuzi_get_image_alt' ) ? dahuzi_get_image_alt( $url ) : '';
	}

	public function getTemplate() {
		return '
		<# var items = _.isArray(data.aptitude_items) ? data.aptitude_items : []; #>
		<# if (items.length) { #>
			<div class="module-theme-aptitude"><div class="section"><div class="container">
				<# if (data.module_title || data.module_desc) { #>
					<div class="section-title"><# if (data.module_title) { #><strong>{{ data.module_title }}</strong><# } #><# if (data.module_desc) { #><p>{{ data.module_desc }}</p><# } #></div>
				<# } #>
				<div class="md-aptitude"><div class="swiper swiper-container aptitude-list xtpb-theme-aptitude-slider"><ul class="swiper-wrapper">
					<# _.forEach(items, function(item) { item = item || {}; var image = item.item_img && typeof item.item_img === "object" ? (item.item_img.url || "") : (item.item_img || ""); #>
						<# if (image) { #><li class="swiper-slide"><div class="aptitude-item"><a href="{{ item.item_url || image }}"<# if (!item.item_url) { #> data-fancybox="aptitude-img"<# } #>><div class="img-center"><img loading="lazy" src="{{ image }}" alt=""></div><# if (item.item_title) { #><p>{{ item.item_title }}</p><# } #></a></div></li><# } #>
					<# }); #>
				</ul><div class="swiper-pagination"></div></div></div>
			</div></div></div>
		<# } #>';
	}
}
