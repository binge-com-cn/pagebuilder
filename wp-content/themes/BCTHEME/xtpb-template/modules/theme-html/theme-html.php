<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Html {
	public function get_name() { return 'theme_html'; }
	public function get_title() { return '自定义 HTML'; }
	public function get_class() { return 'xtpb-theme-html'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-html/assets/css/frontend.css' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-html/assets/img/modules-html.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'item_content'=>array(
				'type'=>'textarea',
				'title'=>'自定义代码',
				'desc'=>'支持 HTML、CSS 和 JavaScript。仅向可信任的管理员开放此模块。',
				'std'=>'<p>在这里输入自定义 HTML 内容。</p>',
				'section'=>'内容设置',
			),
		);
	}

	public function render( $data=null ) {
		$settings=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$content=isset( $settings['item_content'] ) && is_string( $settings['item_content'] ) ? $settings['item_content'] : '';
		if ( '' === trim( $content ) ) { return ''; }
		return '<div class="module-theme-html">'.$content.'</div>';
	}

	public function getTemplate() {
		return '<div class="module-theme-html">{{{ data.item_content || "" }}}</div>';
	}
}
