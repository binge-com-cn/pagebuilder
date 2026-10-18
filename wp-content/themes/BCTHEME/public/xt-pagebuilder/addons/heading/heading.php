<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Heading{

	public function get_name(){
		return 'xtpb_heading';
	}
	public function get_title(){
		return __( '标题', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-heading';
	}

	// headline Settings Fields
	public function get_settings() {

		$settings = array(

			'title' => array(
				'type' => 'textarea',
				'title' => __('标题','xintheme-pagebuilder'),
				'std' => __( '这是标题', 'xintheme-pagebuilder' ),
			),

			'title_selector' => array(
				'type' => 'select',
				'title' => __('标题标签','xintheme-pagebuilder'),
				'values' => array(
					'h1' => 'h1',
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
					'span' => 'span',
					'p' => 'p',
					'div' => 'div',
				),
				'std' => 'h2',
			),
			'title_link' => array(
				'type' => 'link',
				'title' => __('链接','xintheme-pagebuilder'),
				'std' =>   array('link'=>'','window'=>false,'nofolow'=>false),
			),
			'align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'selector' => '{{SELECTOR}}.xtpb-headline-addon .xtpb-addon-title, {{SELECTOR}} .xtpb-headline-addon .xtpb-addon-title { text-align: {{data.align}}; }'
			),
			'title_color' => array(
				'type' => 'color2',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => true,
				'std' => array( 'colorType' => 'color', 'clip' => true, 'colorColor' => '' ),
				'selector' => '{{SELECTOR}}.xtpb-headline-addon .xtpb-addon-title, {{SELECTOR}}.xtpb-headline-addon .xtpb-addon-title a, {{SELECTOR}} .xtpb-headline-addon .xtpb-addon-title, {{SELECTOR}} .xtpb-headline-addon .xtpb-addon-title a'
			),
			'title_link_hcolor' => array(
				'type' => 'color',
				'title' => __('链接悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'selector' => '{{SELECTOR}}.xtpb-headline-addon .xtpb-addon-title a:hover, {{SELECTOR}} .xtpb-headline-addon .xtpb-addon-title a:hover{ color: {{data.title_link_hcolor}}; }'
			),
			'title_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'28px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}}.xtpb-headline-addon .xtpb-addon-title, {{SELECTOR}} .xtpb-headline-addon .xtpb-addon-title',
				'tab' => 'style',
			)

		);

		return $settings;
	}

	// Headline Render HTML
	public function render($data = null){
		$settings 		= $data['settings']; 
		$title_selector = isset($settings["title_selector"]) ? sanitize_text_field( $settings["title_selector"] ) : 'h2';
		$title 			= isset($settings["title"]) ? sanitize_text_field( $settings["title"] ) : '';
		$title_link 	= isset($settings["title_link"]) ? $settings["title_link"] : array();

		$output = '';

		$target = !empty($title_link['window']) ? 'target=_blank' : "";
		$nofolow = !empty($title_link['nofolow']) ? 'rel=nofolow' : "";

			$output  .= '<div class="xtpb-headline-addon">';
			if( !empty($title_link['link']) ){
				$output .= '<' .esc_attr($title_selector). ' class="xtpb-addon-title"><a '.esc_attr($nofolow).' href="'.esc_url($title_link['link']).'" '.esc_attr($target).'>' . wp_kses_post($title) .'</a></' . esc_attr($title_selector) . '>';
			} else {
				$output .= '<' .esc_attr($title_selector ). ' class="xtpb-addon-title">' . wp_kses_post($title) .'</' . esc_attr($title_selector) . '>';
			}
			$output .= '</div>';
		return $output;
	}

	// headline Template
	public function getTemplate(){
		$output = '
		<# var title_selector = data.title_selector ? data.title_selector : "h2" #>
			<div class="xtpb-headline-addon">
				<# if( !__.isEmpty(data.title_link) && data.title_link.link ) { #>
					<{{ title_selector }} class="xtpb-addon-title"><a {{ data.title_link.link ? "href="+data.title_link.link : "" }} {{ data.title_link.window ? "target=_blank" : "" }} {{ data.title_link.nofolow ? "rel=nofolow" : "" }} >{{{ data.title }}}</a></{{ title_selector }}>
				<# } else { #>
					<{{ title_selector }} class="xtpb-addon-title">{{{ data.title }}}</{{ title_selector }}>
				<# } #>
			</div>
		';
		return $output;
	}

}
