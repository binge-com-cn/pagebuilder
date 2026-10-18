<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Alert{

	public function get_name(){
		return 'xtpb_alert';
	}
	public function get_title(){
		return '提示框';
	}
	public function get_icon() {
		return 'xtpb-font-alert';
	}

	// Settings Fields
	public function get_settings() {

		$settings = array(
			'alert_title' => array(
				'type' => 'text',
				'title' => __('标题','xintheme-pagebuilder'),
				'std' => '提示框标题',
			),
			'alert_description' => array(
				'type' => 'textarea',
				'title' => __('描述','xintheme-pagebuilder'),
				'std' => '这是一个提示框描述内容，可用于展示通知、提醒、说明或重要信息。',
			),
			'alert_close' => array(
				'type' => 'switch',
				'title' => __('显示关闭按钮','xintheme-pagebuilder'),
				'std' => '1'
			),
			//style tab
			'alert_style' => array(
				'type' => 'select',
				'title' => __('提示样式','xintheme-pagebuilder'),
				'values' => array(
					'primary' => __('主要','xintheme-pagebuilder'),
					'success' => __('成功','xintheme-pagebuilder'),
					'info' => __('信息','xintheme-pagebuilder'),
					'warning' => __('警告','xintheme-pagebuilder'),
					'danger' => __('危险','xintheme-pagebuilder'),
					'light' => __('浅色','xintheme-pagebuilder'),
					'dark' => __('深色','xintheme-pagebuilder'),
					'custom' => __('自定义','xintheme-pagebuilder'),
				),
				'std' => 'info',
				'tab' => 'style',
			),
			'content_color' => array(
				'type' => 'color',
				'title' => __('内容背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'depends' => array( array( 'alert_style', '=', 'custom' ) ),
				'selector' => '{{SELECTOR}} .xtpb-alert-addon-content{ background: {{data.content_color}}; }'
			),
			'close_color' => array(
				'type' => 'color',
				'title' => __('关闭按钮颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'std' => '#e02e2e',
				'selector' => '{{SELECTOR}} .xtpb-close-alert { color: {{data.close_color}}; }'
			),
			'close_hcolor' => array(
				'type' => 'color',
				'title' => __('关闭按钮悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-close-alert:hover { color: {{data.close_hcolor}}; }'
			),

			'title_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => '标题',
				'depends' => array( array( 'alert_style', '=', 'custom' ) ),
				'selector' => '{{SELECTOR}} .xtpb-alert-title { color: {{data.title_color}}; }'
			),
			'title_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'16px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '600',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-alert-title',
				'tab' => 'style',
				'section' => '标题',
			),

			'desc_color' => array(
				'type' => 'color',
				'title' => __('描述颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => '描述',
				'depends' => array( array( 'alert_style', '=', 'custom' ) ),
				'selector' => '{{SELECTOR}} .xtpb-alert-desc { color: {{data.desc_color}}; }'
			),
			'desc_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'16px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '600',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-alert-desc',
				'tab' => 'style',
				'section' => '描述',
			),

			'alert_padding' => array(
				'type' => 'dimension',
				'title' => '内边距',
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => '间距',
				'selector' => '{{SELECTOR}} .xtpb-alert-addon-content { padding: {{data.alert_padding}}; }'
			),
			'alert_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => '间距',
				'selector' => '{{SELECTOR}} .xtpb-alert-addon-content { border-radius: {{data.alert_radius}}; }'
			),
		);

		return $settings;
	}


	// Alert Render HTML
	public function render($data = null){
		$settings 				= $data['settings'];
		$alert_title 			= isset($settings['alert_title']) ? sanitize_text_field( $settings['alert_title'] ) : '';
		$alert_description 		= isset($settings['alert_description']) ? sanitize_text_field( $settings['alert_description'] ) : '';
		$alert_close 			= (bool) isset($settings['alert_close']) ? $settings['alert_close'] : false;
		$alert_style 			= isset($settings['alert_style']) ? sanitize_text_field( $settings['alert_style'] ) : 'info';
		$output = '';

		$output  .= '<div class="xtpb-alert-addon-content xtpb-alert-'.esc_attr($alert_style).'">';
			if ($alert_title) {
				$output .= '<span class="xtpb-alert-title">' . esc_html( $alert_title ) .'</span>';
			}
			if ($alert_description) {
				$output .= '<span class="xtpb-alert-desc">' . wp_kses_post( $alert_description ) . '</span>';
			}
			if ($alert_close == 1) {
				$output .= '<button type="button" class="xtpb-close-alert" data-dismiss="xtpb-alert"><span aria-hidden="true">&times;</span></button>';
			}
		$output .= '</div>';
		return $output;
	}

	// alert Template
	public function getTemplate(){
		$output = '
			<div class="xtpb-alert-addon-content xtpb-alert-{{ data.alert_style }}">
					<# if(data.alert_title) { #>
					<span class="xtpb-alert-title">{{ data.alert_title }}</span>
					<# } #>
					<# if(data.alert_description) { #>
					<span class="xtpb-alert-desc">{{ data.alert_description }}</span>
					<# } #>
					<# if(data.alert_close == 1) { #>
						<button type="button" class="xtpb-close-alert" data-dismiss="xtpb-alert"><span aria-hidden="true">&times;</span></button>
					<# } #>
				</div>';
		return $output;
	}

}
