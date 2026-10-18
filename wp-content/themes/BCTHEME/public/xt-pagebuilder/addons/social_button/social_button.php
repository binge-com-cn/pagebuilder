<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Social_Button{

	public function get_name(){
		return 'xtpb_social_button';
	}
	public function get_title(){
		return __( '社交按钮', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-social-button';
	}

	// social button Settings Fields
	public function get_settings() {

		$settings = array(

			'social_item' => array(
				'title' => __( '按钮列表', 'xintheme-pagebuilder' ),
				'type' => 'repeatable',
				'label' => 'icon_list',

				'std' => array(
					array( 
						'icon_list'  => 'ri-facebook-fill',
						'socialurl'  => array( 'link'=>'www.facebook.com','window'=>false,'nofolow'=>false ),
						'socialtext' => __( 'Facebook', 'xintheme-pagebuilder' ),
						'style'      => 'primary',
					),
					array(
						'icon_list' => 'ri-twitter-fill',
						'socialurl'  => array( 'link'=>'www.twitter.com','window'=>false,'nofolow'=>false ),
						'socialtext' => __( 'Twitter', 'xintheme-pagebuilder' ),
						'style' => 'info',
					),
					array(
						'icon_list' => 'ri-youtube-fill',
						'socialurl'  => array( 'link'=>'www.youtube.com/@xintheme','window'=>false,'nofolow'=>false ),
						'socialtext' => __( 'YouTube', 'xintheme-pagebuilder' ),
						'style' => 'danger',
					)
				),
				
				'attr' => array(
					'icon_list' => array(
						'type' => 'iconsocial',
						'title' => __( '图标', 'xintheme-pagebuilder' ),
						'std' => 'ri-facebook-fill',
					),
					'socialurl' => array(
						'type' => 'link',
						'title' => __('社交链接','xintheme-pagebuilder'),
						'std' => array( 'link'=>'#','window'=>false,'nofolow'=>false),
					),
					'socialtext' => array(
						'type' => 'text',
						'title' => __('社交文本','xintheme-pagebuilder'),
					),
					'style' => array(
						'type' => 'select',
						'title' => __('样式','xintheme-pagebuilder'),
						'values' => array(
							'primary' => __('主要','xintheme-pagebuilder'),
							'success' => __('成功','xintheme-pagebuilder'),
							'info' => __('信息','xintheme-pagebuilder'),
							'warning' => __('警告','xintheme-pagebuilder'),
							'danger' => __('危险','xintheme-pagebuilder'),
							'light' => __('浅色','xintheme-pagebuilder'),
							'dark' => __('深色','xintheme-pagebuilder'),
							'link' => __('链接','xintheme-pagebuilder'),
							'custom' => __('自定义','xintheme-pagebuilder'),
						),
						'std' => 'primary',
					),
					'button_color' => array(
						'type' => 'color',
						'title' => __('按钮颜色','xintheme-pagebuilder'),
						'depends' => array(array('style', '=', array('custom','gradient'))),
						'selector' => '{{SELECTOR}} .xtpb-social-btn { color: {{data.button_color}}; }'
					),
					'button_hcolor' => array(
						'type' => 'color',
						'title' => __('按钮悬停颜色','xintheme-pagebuilder'),
						'depends' => array(array('style', '=', array('custom','gradient'))),
						'selector' => '{{SELECTOR}} .xtpb-social-btn:hover { color: {{data.button_hcolor}}; }'
					),

					'button_bg' => array(
						'type' => 'color2',
						'std' => array(
							'colorType' => 'color',
							'clip' => false,
						),
						'title' => __('按钮背景','xintheme-pagebuilder'),
						'depends' => array(array('style', '=', array('custom'))),
						'selector' => '{{SELECTOR}} .xtpb-social-btn'
					),
					'button_hover_bg' => array(
						'type' => 'color2',
						'std' => array(
							'colorType' => 'color',
							'clip' => false,
						),
						'title' => __('按钮悬停背景','xintheme-pagebuilder'),
						'depends' => array(array('style', '=', array('custom'))),
						'selector' => '{{SELECTOR}} .xtpb-social-btn:hover'
					),
					'social_border' => array(
						'type' => 'border',
						'title' => __( '边框', 'xintheme-pagebuilder' ),
						'std' => array(
							'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
							'borderStyle' => 'solid', 
							'borderColor' => '#cccccc' 
						),
						'selector' => '{{SELECTOR}} .xtpb-social-btn'
					),
					'social_hcolor' => array(
						'type' => 'border',
						'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
						'std' => array(
							'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
							'borderStyle' => 'solid', 
							'borderColor' => '#cccccc' 
						),
						'selector' => '{{SELECTOR}} .xtpb-social-btn:hover'
					),
				),
			),

			'shape' => array(
				'type' => 'select',
				'title' => __('社交按钮形状','xintheme-pagebuilder'),
				'values' => array(
					'rounded' => __('圆角','xintheme-pagebuilder'),
					'square' => __('方形','xintheme-pagebuilder'),
					'round' => __('圆形','xintheme-pagebuilder'),
					'custom' => __('自定义','xintheme-pagebuilder'),
				),
				'std' => 'rounded',
			),

			'social_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'depends' => array(array('shape', '=', array('custom'))),
				'selector' => '{{SELECTOR}} .xtpb-social-button li a { border-radius: {{data.social_radius}}; }'
			),
			'hover_social_radius' => array(
				'type' => 'dimension',
				'title' => __('悬停边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'depends' => array(array('shape', '=', array('custom'))),
				'selector' => '{{SELECTOR}} .xtpb-social-button li a:hover { border-radius: {{data.hover_social_radius}}; }'
			),

			'btn_size' => array(
				'type' => 'select',
				'title' => __('按钮尺寸','xintheme-pagebuilder'),
				'values' => array(
					'standard' => __('标准','xintheme-pagebuilder'),
					'large' => __('大','xintheme-pagebuilder'),
					'xlarge' => __('超大','xintheme-pagebuilder'),
					'small' => __('小','xintheme-pagebuilder'),
					'xsmall' => __('超小','xintheme-pagebuilder'),
					'custom' => __('自定义','xintheme-pagebuilder'),
				),
				'std' => 'large',
			),
			'social_padding' => array(
				'type' => 'dimension',
				'title' => __( '社交按钮内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'depends' => array(array('btn_size', '=', array('custom'))),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-social-button li a { padding: {{data.social_padding}}; }',
			),

			'social_align' => array(
				'type' => 'alignment',
				'title' => __('社交按钮对齐','xintheme-pagebuilder'),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-social-addon-content { text-align: {{data.social_align}}; }'
			),

			//style
			'social_icon_font' => array(
				'type' => 'slider',
				'title' => __('图标尺寸','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 10,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 150,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '14px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-social-button li a i { font-size: {{data.social_icon_font}}; }'
			),
			'social_icon_line' => array(
				'type' => 'slider',
				'title' => __('行高','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 10,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 150,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '20px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-social-button li a i { line-height: {{data.social_icon_line}}; }'
			),

			'social_fontstyle' => array(
				'type' => 'typography',
				'title' => __('标题排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'14px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-social-button li a span',
				'tab' => 'style',
			),

			'social_margin' => array(
				'type' => 'slider',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 15,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 150,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '5px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'selector' => array(
					'{{SELECTOR}} .xtpb-social-button { margin-left: -{{data.social_margin}}; }',
					'{{SELECTOR}} .xtpb-social-button { margin-right: -{{data.social_margin}}; }',
					'{{SELECTOR}} .xtpb-social-button .xtpb-social-button-list { padding-right: {{data.social_margin}}; }',
					'{{SELECTOR}} .xtpb-social-button .xtpb-social-button-list { padding-left: {{data.social_margin}}; }',
				)
			),
		);

		return $settings;
	}

	//Share Render HTML
	public function render($data = null){
		$settings 		= $data['settings'];

		$social_item    = isset($settings['social_item']) ? $settings['social_item'] : array();
		$shape 			= isset($settings['shape']) ? sanitize_text_field( $settings['shape'] ) : '';
		$btn_size       = isset($settings['btn_size']) ? sanitize_text_field( $settings['btn_size'] ) : '';

		$output  = $socialdesign = '' ;
		$socialdesign .= ( isset( $shape ) && $shape ) ? ' xtpb-social-' . $shape : '';
		$socialdesign .= ( isset( $btn_size ) && $btn_size ) ? ' xtpb-social-' . $btn_size : '';

		$output  .= '<div class="xtpb-social-addon-content">';
				$output  .= '<ul class="xtpb-social-button ' . esc_attr( $socialdesign ) . '">';
				if ( is_array( $social_item ) && count( $social_item ) ) {
					foreach ( $social_item as $key => $value ) {
						$classlist = '';
						if ( isset( $value['socialurl']['link'] ) ) {
							$classlist .= ( $value['style'] && $value['style'] ) ? ' xtpb-social-' . $value['style'] : '';
							$output  .= '<li class="xtpb-social-button-list repeater-' . esc_attr( $key ) . '">';
							$target = $value['socialurl']['window'] ? 'target=_blank' : 'target=_self';
							$nofolow = $value['socialurl']['nofolow'] ? 'rel=nofolow' : "";
							$output  .= '<a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $value['socialurl']['link'] ) . '" ' . esc_attr( $target ) . ' class="xtpb-social-btn ' . esc_attr( $classlist ) . '"><i class="' . esc_attr( $value['icon_list'] ) . '"></i><span>' . esc_html( $value['socialtext'] ) . '</span>';
							$output  .= '</a></li>';
						}
					}
				}

				$output  .= '</ul>';
		$output  .= '</div>';//xtpb-social-addon-content

		return $output;
	}

	// Share Template
	public function getTemplate(){
		$output = '
		<#
		var socailDesign = "";
		socailDesign += " xtpb-social-"+data.shape;
		socailDesign += " xtpb-social-"+data.btn_size;
		#>
		<div class="xtpb-social-addon-content">
				<ul class="xtpb-social-button xtpb-social-{{socailDesign}}">
					<#  _.forEach(data.social_item, function(value, key) { 
						var classList = "";
						classList += " xtpb-social-"+value.style;
						#>
						<li class="xtpb-social-button-list repeater-{{key}}">
						<a {{ value.socialurl.link ? "href="+value.socialurl.link : "" }} {{ value.socialurl.window ? "target=_blank" : "" }} {{ value.socialurl.nofolow ? "rel=nofolow" : "" }} class="xtpb-social-btn {{classList}}">
							<i class="{{ value.icon_list }}"></i><span>{{ value.socialtext }}</span>
						</a></li>
					<# }); #>
				</ul>
		</div>
		';
		return $output;
	}

}
