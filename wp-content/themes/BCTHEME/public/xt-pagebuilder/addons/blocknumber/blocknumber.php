<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Blocknumber{

	public function get_name(){
		return 'xtpb_block_number';
	}
	public function get_title(){
		return __( '区块数字', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-block-number';
	}

	// Settings Fields
	public function get_settings() {

		$settings = array(
			'number' => array(
				'type' => 'text',
				'title' => __('区块数字','xintheme-pagebuilder'),
				'std' => '01'
			),
			'number_title' => array(
				'type' => 'text',
				'title' => __('标题','xintheme-pagebuilder'),
				'std' => __( '数字标题', 'xintheme-pagebuilder' )
			),
			'number_description' => array(
				'type' => 'textarea',
				'title' => __('添加描述','xintheme-pagebuilder'),
				'std' => __( '在这里添加一段简短描述，用来说明这个数字代表的含义。', 'xintheme-pagebuilder' )
			),
			// tab style	
			'number_color' => array(
				'type' => 'color',
				'title' => __('数字颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'std'       => '#ffffff',
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-number span{ color: {{data.number_color}}; }'
			),
			'number_bg' => array(
				'type' => 'color',
				'title' => __('数字背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'std' => '#03E16D',
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-number span{ background: {{data.number_bg}}; }'
			),
			'number_width' => array(
				'type' => 'slider',
				'title' => __('数字宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 15,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 200,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
				'std' => array(
						'md' => '48px',
						'sm' => '',
						'xs' => ''
					),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-number span { width: {{data.number_width}}; }'
			),
			'number_height' => array(
				'type' => 'slider',
				'title' => __('数字高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 25,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 200,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
				'std' => array(
						'md' => '48px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'selector' => array(
					'{{SELECTOR}} .xtpb-blocknumber-number span { height: {{data.number_height}}; }',
					'{{SELECTOR}} .xtpb-blocknumber-number span { line-height: {{data.number_height}}; }',
				)
			),
			'number_border' => array(
				'type' => 'border',
				'title' => __( '数字边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-number span'
      ),
      'number_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-number span',
				'tab' => 'style',
      ),
			'number_radius' => array(
				'type' => 'dimension',
				'title' => __('数字边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-number span { border-radius: {{data.number_radius}}; }'
			),
      'number_margin' => array(
				'type' => 'dimension',
				'title' => __('数字外边距','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-number span { margin: {{data.number_margin}}; }'
			),
			'title_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'std'       => '#000000',
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-title { color: {{data.title_color}}; }'
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
				'tab' => 'style',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-title',
			),
			'descr_color' => array(
				'type' => 'color',
				'title' => __('描述颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '描述', 'xintheme-pagebuilder' ),
				'std'       => '#666666',
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-desc { color: {{data.descr_color}}; }'
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
				'tab' => 'style',
				'section' => __( '描述', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-blocknumber-desc',
			),
		);
		return $settings;
	}

	// 区块数字 Render HTML
	public function render( $data = null ) {
		$settings 		    = $data['settings'];

		$number_title 	    = isset( $settings['number_title'] ) ? sanitize_text_field( $settings['number_title'] ) : '';
		$number_description = isset( $settings['number_description'] ) ? sanitize_textarea_field( $settings['number_description'] ) : '';
		$number 		    = isset( $settings['number'] ) ? (int) $settings['number'] : '';

		$output                 = '';
		$output  .= '<div class="xtpb-blocknumber-addon-content">';
				$output  .= '<div class="xtpb-blocknumber-media">';
					if ( $number ) {
						$output .= '<div class="xtpb-blocknumber-number"><span>' . esc_html( $number ) . '</span></div>';
					}
					$output .= '<div class="xtpb-blocknumber-body">';
					if ( $number_title ) {
						$output .= '<h5 class="xtpb-blocknumber-title">' . esc_html( $number_title ) . '</h5>';
					}
					if ( $number_description ) {
						$output .= '<div class="xtpb-blocknumber-desc">' . esc_html( $number_description ) . '</div>';
					}
					$output .= '</div>';//xtpb-blocknumber-body
				$output  .= '</div>';//xtpb-blocknumber-media
		$output .= '</div>';//xtpb-blocknumber-addon-content

		return $output;
	}

	// 区块数字 Template
	public function getTemplate(){
		$output = '
			<div class="xtpb-blocknumber-addon-content">
					<div class="xtpb-blocknumber-media">
						<# if(data.number) { #>
							<div class="xtpb-blocknumber-number"><span>{{data.number}}</span></div>
						<# } #>
						<# if( data.number_title || data.number_description ) { #>
							<div class="xtpb-blocknumber-body">	
								<# if(data.number_title) { #>
								<h5 class="xtpb-blocknumber-title">{{ data.number_title }}</h5>
								<# } #>
								<# if(data.number_description) { #>
									<div class="xtpb-blocknumber-desc">{{data.number_description}}</div>
								<# } #>
							</div>
						<# } #>
					</div>
				</div>

		';
		return $output;
	}

}
