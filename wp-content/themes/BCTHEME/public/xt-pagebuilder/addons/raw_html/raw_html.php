<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Raw_Html{

	public function get_name(){
		return 'xtpb_raw_html';
	}
	public function get_title(){
		return __( '原始 HTML', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-html5';
	}

	// image Settings Fields
	public function get_settings() {

		$settings = array(
			'rawhtml' => array(
				'type' => 'textarea',
				'title' => __('内容','xintheme-pagebuilder'),
				'std' => __( '<p>在这里输入自定义 HTML 内容。<br/>这是一个超链接示例：<a href="https://www.xintheme.com">XinTheme</a></p>', 'xintheme-pagebuilder' )
			),

			'align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-raw-html-content { text-align: {{data.align}}; }'
			),

			//style
			'color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-raw-html-content { color: {{data.color}}; }'
			),
			'link_color' => array(
				'type' => 'color',
				'title' => __('链接颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-raw-html-content a { color: {{data.link_color}}; }'
			),
			'link_hcolor' => array(
				'type' => 'color',
				'title' => __('链接悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-raw-html-content a:hover { color: {{data.link_hcolor}}; }'
			),

			'typo_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'14px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-raw-html-content',
				'tab' => 'style',
			)
		);

		return $settings;
	}


	// Raw HTML Render HTML
	public function render($data = null){
		$settings 		= $data['settings'];
		$rawhtml 		= isset($settings['rawhtml']) ? wp_kses_post( $settings['rawhtml'] ) : '';
		$output = '';
		if ( $rawhtml ) {
			$output .= '<div class="xtpb-raw-html-content">' . $rawhtml . '</div>';
		}

		return $output;
	}

	// raw Template
	public function getTemplate(){
		$output = '
		<div class="xtpb-raw-html-content">
			{{{ data.rawhtml }}}
		</div>
		';
		return $output;
	}

}
