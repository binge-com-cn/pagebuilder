<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Text_Block{

	public function get_name(){
		return 'xtpb_text_block';
	}
	public function get_title(){
		return __( '文本块', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-text';
	}

	// text block Settings Fields
	public function get_settings() {

		$settings = array(

			'text' => array(
				'type' => 'editor',
				'title' => __('内容','xintheme-pagebuilder'),
				'std' => __( '无需编码技能，数小时内即可上线可供客户使用的网站，而非数天。', 'xintheme-pagebuilder' )
			),
			'drop_cap' => array(
				'type' => 'switch',
				'title' => __('首字下沉','xintheme-pagebuilder'),
				'std' => 0
			),
			'align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'selector' => '{{SELECTOR}}.xtpb-text-block, {{SELECTOR}} .xtpb-text-block { text-align: {{data.align}}; }'
			),

			//style
			'color' => array(
				'type' => 'color',
				'title' => __('内容颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'selector' => '{{SELECTOR}}.xtpb-text-block, {{SELECTOR}} .xtpb-text-block { color: {{data.color}}; }'
			),
			'text_fontstyle' => array(
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
				'selector' => '{{SELECTOR}}.xtpb-text-block, {{SELECTOR}} .xtpb-text-block',
				'tab' => 'style',
			),
			'drop_color' => array(
				'type' => 'color',
				'title' => __('下沉字颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'selector' => '{{SELECTOR}}.xtpb-text-block-drop:first-letter, {{SELECTOR}} .xtpb-text-block-drop:first-letter { color: {{data.drop_color}}; }',
				'depends' => array( array( 'drop_cap', '=', '1' ) ),
			),
			'drop_fontstyle' => array(
				'type' => 'typography',
				'title' => __('首字下沉排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'50px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}}.xtpb-text-block-drop:first-letter, {{SELECTOR}} .xtpb-text-block-drop:first-letter',
				'tab' => 'style',
				'depends' => array( array( 'drop_cap', '=', '1' ) ),
			),
			'drop_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}}.xtpb-text-block-drop:first-letter, {{SELECTOR}} .xtpb-text-block-drop:first-letter { padding: {{data.drop_padding}}; }',
				'depends' => array( array( 'drop_cap', '=', '1' ) ),
			),
		);
		return $settings;
	}

	// text block Render HTML
	public function render($data = null){
		$settings 		= $data['settings'];
		$text 			= isset($settings['text']) ? wp_kses_post( $settings['text'] ) : '';
		$drop_cap 		= isset($settings['drop_cap']) ? sanitize_text_field( $settings['drop_cap'] ) : '';

		$output = '';
		$drop_cap = ( isset( $drop_cap ) && $drop_cap ) ? $drop_cap : 0;
		if ( $drop_cap ) {
			$drop_cap = 'xtpb-text-block-drop';
		}
		$output  .= '<div class="xtpb-text-block ' . esc_attr( $drop_cap ) . '">';
		if ( $text ) {
			$output .= wp_kses_post( $text );
		}
		$output .= '</div>';

		return $output;
	}

	// text block Template
	public function getTemplate(){
		$output = '
		<#
			var drop_cap = "";
			if(data.drop_cap){
				drop_cap = "xtpb-text-block-drop";
			}
		#>
		<# if(data.text){ #>
			<div class="xtpb-text-block {{drop_cap}}">
				<# if(data.text) { #>
				<# print(data.text) #>
				<# } #>
			</div>
		<# } #> ';
		return $output;
	}

}
