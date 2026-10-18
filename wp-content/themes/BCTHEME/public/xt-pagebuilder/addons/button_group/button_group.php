<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Button_Group{

	public function get_name(){
		return 'xtpb_button_group';
	}
	public function get_title(){
		return __( '按钮组', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-button-group';
	}

	// Group Button Settings Fields
	public function get_settings() {

		$settings = array(

			// Repeatable Items
			'button_item' => array(
				'title' => __('按钮','xintheme-pagebuilder'),
				'type' => 'repeatable',
				'label' => 'button_text',
				'std' => array(
					array( 
						'button_text' => __( '按钮 1', 'xintheme-pagebuilder' ),
						'button_link' => array( 'link'=>'#','window'=>false,'nofolow'=>false ),
						'style' => 'primary',
						'btn_size' => 'standard',
						'shape' => 'rounded',
					),
					array(
						'button_text' => __( '按钮 2', 'xintheme-pagebuilder' ),
						'button_link' => array( 'link'=>'#', 'window'=>false, 'nofolow'=>false ),
						'style' => 'info',
						'btn_size' => 'standard',
						'shape' => 'square',
						'icon_list' => 'ri-settings-3-line',
						'icon_position' => 'left',
					),
					array(
						'button_text' => __( '按钮 3', 'xintheme-pagebuilder' ),
						'button_link' => array( 'link'=>'#','window'=>false,'nofolow'=>false ),
						'style' => 'success',
						'btn_size' => 'standard',
						'shape' => 'rounded',
						'icon_list' => 'ri-apple-fill',
						'icon_position' => 'right',
					)
				),
				'attr' => array(
					'button_text' => array(
						'type' => 'text',
						'title' => __('文本','xintheme-pagebuilder'),
						'std' => __( '按钮', 'xintheme-pagebuilder' ),
					),
					'button_link' => array(
						'type' => 'link',
						'title' => __('链接','xintheme-pagebuilder'),
						'std' => array( 'link'=>'#','window'=>false,'nofolow'=>false ),
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
						'title' => __('颜色','xintheme-pagebuilder'),
						'depends' => array(array('style', '=', array('custom'))),
						'selector' => '{{SELECTOR}} .xtpb-btn-addons { color: {{data.button_color}}; }'
					),
					'button_hcolor' => array(
						'type' => 'color',
						'title' => __('悬停颜色','xintheme-pagebuilder'),
						'depends' => array(array('style', '=', array('custom'))),
						'selector' => '{{SELECTOR}} .xtpb-btn-addons:hover { color: {{data.button_hcolor}}; }'
					),
					'button_bg' => array(
						'type' => 'color2',
						'title' => __('背景','xintheme-pagebuilder'),
						'depends' => array(array('style', '=', array('custom'))),
						'selector' => '{{SELECTOR}} .xtpb-btn-addons'
					),
					'button_hover_bg' => array(
						'type' => 'color2',
						'title' => __('悬停背景','xintheme-pagebuilder'),
						'depends' => array(array('style', '=', array('custom'))),
						'selector' => '{{SELECTOR}} .xtpb-btn-custom.xtpb-btn-addons:before'
					),
					'btn_size' => array(
						'type' => 'select',
						'title' => __('尺寸','xintheme-pagebuilder'),
						'values' => array(
							'standard' => __('标准','xintheme-pagebuilder'),
							'large' => __('大','xintheme-pagebuilder'),
							'xlarge' => __('超大','xintheme-pagebuilder'),
							'small' => __('小','xintheme-pagebuilder'),
							'xsmall' => __('超小','xintheme-pagebuilder'),
							'custom' => __('自定义','xintheme-pagebuilder'),
						),
						'std' => 'standard',
					),
					'button_padding' => array(
						'type' => 'dimension',
						'title' => __( '内边距', 'xintheme-pagebuilder' ),
						'unit' => array( 'px','em','%' ),
						'depends' => array(array('btn_size', '=', array('custom'))),
						'responsive' => true,
						'selector' => '{{SELECTOR}} .xtpb-btn-addons { padding: {{data.button_padding}}; }',
					),
					'shape' => array(
						'type' => 'select',
						'title' => __('形状','xintheme-pagebuilder'),
						'values' => array(
							'rounded' => __('圆角','xintheme-pagebuilder'),
							'square' => __('方形','xintheme-pagebuilder'),
							'round' => __('圆形','xintheme-pagebuilder'),
							'custom' => __('自定义','xintheme-pagebuilder'),
						),
						'std' => 'rounded',
					),
					'buttom_radius' => array(
						'type' => 'dimension',
						'title' => __('边框圆角','xintheme-pagebuilder'),
						'unit' => array( '%','px','em' ),
						'responsive' => true,
						'depends' => array(array('shape', '=', array('custom'))),
						'selector' => '{{SELECTOR}} .xtpb-btn-addons { border-radius: {{data.buttom_radius}}; }'
					),				
					'icon_list' => array(
						'type' => 'icon',
						'title' => __('图标','xintheme-pagebuilder'),
						'std' => ''
					),
					'icon_position' => array(
						'type' => 'select',
						'title' => __('图标位置','xintheme-pagebuilder'),
						'values' => array(
							'left' => __('左侧','xintheme-pagebuilder'),
							'right' => __('右侧','xintheme-pagebuilder'),
						),
						'std' => 'left',
					),		

					'button_border' => array(
						'type' => 'border',
						'title' => __( '边框', 'xintheme-pagebuilder' ),
						'std' => array(
							'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
							'borderStyle' => 'solid', 
							'borderColor' => '#cccccc' 
						),
						'selector' => '{{SELECTOR}} .xtpb-btn-addons'
					),
					'border_hcolor' => array(
						'type' => 'border',
						'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
						'std' => array(
							'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
							'borderStyle' => 'solid', 
							'borderColor' => '#cccccc' 
						),
						'selector' => '{{SELECTOR}} .xtpb-btn-addons:hover'
					),
				),
			),

			'button_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'std' => array( 'md'=>'center' ),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content{ text-align: {{data.button_align}}; }'
			),

			// Style Area
			'button_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'14px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-addons',
				'tab' => 'style',
			),

			'button_margin' => array(
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
					'{{SELECTOR}} .xtpb-button-addon-content { margin-left: -{{data.button_margin}}; }',
					'{{SELECTOR}} .xtpb-button-addon-content { margin-right: -{{data.button_margin}}; }',
					'{{SELECTOR}} .xtpb-button-group { padding-right: {{data.button_margin}}; }',
					'{{SELECTOR}} .xtpb-button-group { padding-left: {{data.button_margin}}; }',
				)
			),

		);

		return $settings;
	}
	
	// Group Button Render HTML
	public function render($data = null){
		$settings 			= $data['settings']; 
		$button_item 		= isset($settings["button_item"]) ? $settings["button_item"] : array();

		$output = $button = '' ;
		$output  .= '<div class="xtpb-button-addon-content">';
			if (is_array($button_item) && count($button_item)){
				foreach ( $button_item as $key => $value ) {
					$classlist = '';
					if( !empty($value['button_link']['link'])) {
						$output  .= '<div class="xtpb-button-group repeater-'.$key.'">';
							$classlist .= ($value['style'] && $value['style']) ? ' xtpb-btn-' . $value['style'] : '';
							$classlist .= ($value['shape'] && $value['shape']) ? ' xtpb-btn-' . $value['shape'] : '';
							$classlist .= ($value['btn_size'] && $value['btn_size']) ? ' xtpb-btn-' . $value['btn_size'] : '';
							$target = $value['button_link']['window'] ? 'target=_blank' : 'target=_self';
							$nofolow = $value['button_link']['nofolow'] ? 'rel=nofolow' : "";

							if( isset($value['icon_position']) == 'left') {
								$button = ( isset($value['icon_list']) && $value['icon_list'] ) ? '<i class="' . esc_attr( $value['icon_list']) . '"></i> ' . esc_attr($value['button_text']) : esc_attr($value['button_text']);
							} elseif ( isset($value['icon_position']) == 'right') {
								$button = ( isset($value['icon_list']) && $value['icon_list'] ) ? esc_attr($value['button_text']) . ' <i class="' . esc_attr( $value['icon_list']) . '"></i>' : esc_attr($value['button_text']);
							} else {
								$button =  esc_attr($value['button_text']);
							}
							$output  .= '<a '.esc_attr($nofolow).' href="'.esc_url($value['button_link']['link']).'" '.esc_attr($target).' class="xtpb-btn-addons '.esc_attr($classlist).'">' . $button  . '</a>';
						$output  .= '</div>';
					}
				}
			}
		$output  .= '</div>';

		return $output;
	}

	// Group Button Template
	public function getTemplate(){
		$output = '
		<div class="xtpb-button-addon-content">
				<#  _.forEach(data.button_item, function(value, key) { 
					var classList = "";
					classList += " xtpb-btn-"+value.style;
					classList += " xtpb-btn-"+value.shape;
					classList += " xtpb-btn-"+value.btn_size;  
					#>
					<div class="xtpb-button-group repeater-{{key}}">	
						<# if( value.button_link && value.button_link.link) { #>
							<a {{ value.button_link.link ? "href="+value.button_link.link : "" }} {{ value.button_link.window ? "target=_blank" : "" }} {{ value.button_link.nofolow ? "rel=nofolow" : "" }}  class="xtpb-btn-addons {{classList}}">
								<# if(value.icon_list){ #>
									<# if(value.icon_position == "right" ) { #>
										<i class=" {{value.icon_list}}"></i> {{ value.button_text }}
									<# }else{ #>
										{{ value.button_text }} <i class=" {{value.icon_list}}"></i>
									<# } #>
								<# }else{ #>
									{{ value.button_text }} 
								<# } #>
							</a>
						<# } #>
					</div>
				<# }); #>
			</div>';
		return $output;
	}

}
