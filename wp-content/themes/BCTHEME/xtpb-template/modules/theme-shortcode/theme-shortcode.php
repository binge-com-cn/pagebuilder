<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Shortcode {
	public function get_name() {
		return 'theme_shortcode';
	}

	public function get_title() {
		return '短代码';
	}

	public function get_class() {
		return 'xtpb-theme-shortcode';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-shortcode/assets/css/frontend.css',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-shortcode/assets/img/modules-shortcode.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'item_content' => array(
				'type'    => 'textarea',
				'title'   => '短代码',
				'desc'    => '输入已注册的 WordPress 短代码，可包含普通文本或 HTML。',
				'std'     => '',
				'section' => '内容设置',
			),
		);
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$content  = isset( $settings['item_content'] ) && is_string( $settings['item_content'] ) ? $settings['item_content'] : '';

		if ( '' === trim( $content ) ) {
			return '';
		}

		return '<div class="module-theme-shortcode">' . apply_filters( 'the_content', $content ) . '</div>';
	}

	public function getTemplate() {
		return '
		<# if(data.item_content){ #>
			<div class="module-theme-shortcode"><div class="shortcode-preview"><i class="ri-code-s-slash-line"></i><span>{{ data.item_content }}</span><small>短代码将在前台执行</small></div></div>
		<# } #>';
	}
}
