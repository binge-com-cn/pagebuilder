<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Contact {

	public function get_name() {
		return 'theme_contact';
	}

	public function get_title() {
		return '联系我们';
	}

	public function get_class() {
		return 'xtpb-theme-contact';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-contact/assets/css/frontend.css',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-contact/assets/img/modules-contact.jpg';
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
			'contact_style' => array(
				'type'    => 'select',
				'title'   => '列数',
				'values'  => array(
					'style_2' => '两列',
					'style_3' => '三列',
					'style_4' => '四列',
				),
				'std'     => 'style_3',
				'section' => '模块设置',
			),
			'contact_items' => array(
				'type'    => 'repeatable',
				'title'   => '联系信息',
				'label'   => 'item_title',
				'section' => '内容设置',
				'std'     => array(
					array(
						'item_icon'  => 'ri-phone-line',
						'item_title' => '联系电话',
						'item_desc'  => '400-000-0000',
					),
				),
				'attr'    => array(
					'item_icon' => array(
						'type'  => 'icon',
						'title' => '图标',
						'std'   => 'ri-phone-line',
					),
					'item_title' => array(
						'type'  => 'text',
						'title' => '标题',
					),
					'item_desc' => array(
						'type'  => 'text',
						'title' => '描述',
					),
				),
			),
		);
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items    = isset( $settings['contact_items'] ) && is_array( $settings['contact_items'] ) ? $settings['contact_items'] : array();

		if ( empty( $items ) ) {
			return '';
		}

		$title    = ! empty( $settings['module_title'] ) ? sanitize_text_field( $settings['module_title'] ) : '';
		$desc     = ! empty( $settings['module_desc'] ) ? sanitize_textarea_field( $settings['module_desc'] ) : '';
		$style    = ! empty( $settings['contact_style'] ) ? sanitize_html_class( $settings['contact_style'] ) : 'style_3';
		$bg_image = $this->get_media_url( isset( $settings['bg_img'] ) ? $settings['bg_img'] : '' );

		ob_start();
		?>
		<div class="module-theme-contact">
			<div class="section<?php echo $bg_image ? ' mbbg-img' : ''; ?>"<?php echo $bg_image ? ' style="background-image:url(' . esc_url( $bg_image ) . ')"' : ''; ?>>
				<div class="container">
					<?php if ( $title || $desc ) : ?>
						<div class="section-title">
							<?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?>
							<?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="md-contact <?php echo esc_attr( $style ); ?>">
						<ul>
							<?php foreach ( $items as $item ) : ?>
								<?php
								$item = is_array( $item ) ? $item : array();
								$icon = ! empty( $item['item_icon'] ) ? sanitize_text_field( $item['item_icon'] ) : '';
								?>
								<li>
									<?php if ( $icon ) : ?><i class="<?php echo esc_attr( $icon ); ?>"></i><?php endif; ?>
									<?php if ( ! empty( $item['item_title'] ) ) : ?><h3><?php echo esc_html( $item['item_title'] ); ?></h3><?php endif; ?>
									<?php if ( ! empty( $item['item_desc'] ) ) : ?><p><?php echo esc_html( $item['item_desc'] ); ?></p><?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
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

	public function getTemplate() {
		return '
		<#
		var items = _.isArray(data.contact_items) ? data.contact_items : [];
		var bgImage = data.bg_img && typeof data.bg_img === "object" ? (data.bg_img.url || "") : (data.bg_img || "");
		var style = data.contact_style || "style_3";
		#>
		<# if (items.length) { #>
			<div class="module-theme-contact"><div class="section<# if (bgImage) { #> mbbg-img<# } #>"<# if (bgImage) { #> style="background-image:url({{ bgImage }})"<# } #>><div class="container">
				<# if (data.module_title || data.module_desc) { #><div class="section-title"><# if (data.module_title) { #><strong>{{ data.module_title }}</strong><# } #><# if (data.module_desc) { #><p>{{ data.module_desc }}</p><# } #></div><# } #>
				<div class="md-contact {{ style }}"><ul>
					<# _.forEach(items, function(item) { item = item || {}; #><li><# if (item.item_icon) { #><i class="{{ item.item_icon }}"></i><# } #><# if (item.item_title) { #><h3>{{ item.item_title }}</h3><# } #><# if (item.item_desc) { #><p>{{ item.item_desc }}</p><# } #></li><# }); #>
				</ul></div>
			</div></div></div>
		<# } #>';
	}
}
