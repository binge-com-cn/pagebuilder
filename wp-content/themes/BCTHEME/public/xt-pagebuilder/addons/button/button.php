<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Button{

	public function get_name(){
		return 'xtpb_button';
	}
	public function get_title(){
		return __( '按钮', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-button';
	}

	// Button Settings Fields
	public function get_settings() {

		$settings = array(
			'button_text' => array(
				'type' => 'text',
				'title' => __('文本','xintheme-pagebuilder'),
				'std' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_link' => array(
				'type' => 'link',
				'title' => __('链接','xintheme-pagebuilder'),
				'std' => array( 'link'=>'#','window'=>false,'nofolow'=>false )
			),
			'icon_list' => array(
				'type' => 'icon',
				'title' => __('图标','xintheme-pagebuilder'),
				'std' => ''
			),
			'icon_position' => array(
				'type' => 'select',
				'title' => __('图标位置','xintheme-pagebuilder'),
				'depends' => array(array('icon_list', '!=', '')),
				'values' => array(
					'left' => __('左侧','xintheme-pagebuilder'),
					'right' => __('右侧','xintheme-pagebuilder'),
				),
				'std' => 'left',
			),

			// Style Area
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
				'std' => 'large',
			),
			'button_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'depends' => array(array('btn_size', '=', array('custom'))),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-addons { padding: {{data.button_padding}}; }',
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
				'title' => __( '边框圆角', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'depends' => array(array('shape', '=', array('custom'))),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-addons{ border-radius: {{data.buttom_radius}} ; }',
			),
			'button_border_radius_hover' => array(
				'type' => 'dimension',
				'title' => __( '悬停圆角', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'depends' => array(array('shape', '=', array('custom'))),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-addons:hover{ border-radius: {{data.button_border_radius_hover}} ; }',
			),

			'fullwidth_button' => array(
				'type' => 'select',
				'title' => __('全宽','xintheme-pagebuilder'),
				'values' => array(
					'full-yes' => __('是','xintheme-pagebuilder'),
					'full-no' => __('否','xintheme-pagebuilder'),
				),
				'std' => 'no',
			),

			'button_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'std' => array( 'md' => 'center', 'sm' => 'center', 'xs' => 'center' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content{ text-align: {{data.button_align}}; }'
			),
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
				'section' => __( '字体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-addons',
				'tab' => 'style',
			),

			'style' => array(
				'type' => 'select',
				'title' => __( '选择样式', 'xintheme-pagebuilder' ),
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
				'std' => 'success',
				'tab' => 'style',
				'section' => __( '样式', 'xintheme-pagebuilder' ),
			),
			'button_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('style', '=', array('custom'))),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-addons { color: {{data.button_color}}; }'
			),
			'button_hcolor' => array(
				'type' => 'color',
				'title' => __('按钮悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('style', '=', array('custom'))),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-addons:hover { color: {{data.button_hcolor}}; }'
			),
			'button_bg' => array(
				'type' => 'color2',
				'title' => __('按钮背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '样式', 'xintheme-pagebuilder' ),
				'clip' => false,
				'depends' => array(array('style', '=', array('custom'))),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-custom.xtpb-btn-addons'
			),
			'button_hover_bg' => array(
				'type' => 'color2',
				'title' => __('悬停背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '样式', 'xintheme-pagebuilder' ),
				'clip' => false,
				'depends' => array(array('style', '=', array('custom'))),
				'selector' => '{{SELECTOR}} .xtpb-btn-custom.xtpb-btn-addons:before'
			),
			'button_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder'=> 0,
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc'
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-addons'
			),
			'button_border_hover' => array(
				'type' => 'border',
				'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder'=> 0,
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc'
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-btn-custom.xtpb-btn-addons:hover'
			),
			'button_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-addons, {{SELECTOR}} .xtpb-btn-custom.xtpb-btn-addons:before',
				'section' => __( '阴影', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'button_boxshadow_hover' => array(
				'type' => 'boxshadow',
				'title' => __( '悬停阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'selector' => '{{SELECTOR}} .xtpb-button-addon-content .xtpb-btn-addons:hover, {{SELECTOR}} .xtpb-btn-custom.xtpb-btn-addons:before',
				'section' => __( '阴影', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
		);
		return $settings;
	}

	// Button Render HTML
	public function render($data = null){
		$settings 			= $data['settings']; 
		$button_text 		= isset( $settings["button_text"] ) ? sanitize_text_field( $settings["button_text"] ) : '';
		$button_link 		= isset( $settings['button_link'] ) ? $settings['button_link'] : array();
		$fullwidth_button 	= isset( $settings["fullwidth_button"] ) ? sanitize_text_field( $settings["fullwidth_button"] ) : '';
		$icon_list 			= isset( $settings["icon_list"] ) ? sanitize_text_field( $settings["icon_list"] ) : '';
		$icon_position 		= isset( $settings["icon_position"] ) ? sanitize_text_field( $settings["icon_position"] ) : '';
		$style 				= isset( $settings["style"] ) ? sanitize_text_field( $settings["style"] ) : '';
		$btn_size 			= isset( $settings["btn_size"] ) ? sanitize_text_field( $settings["btn_size"] ) : '';
		$shape 				= isset( $settings["shape"] ) ? sanitize_text_field( $settings["shape"] ) : '';
		//$appearance 		= isset($settings["appearance"]) ? $settings["appearance"] : '';

		$output = $button = $classlist = '' ;

		$target = ( isset( $button_link['window'] ) && $button_link['window'] ) ? 'target=_blank' : 'target=_self';
		$nofolow = ( isset( $button_link['nofolow'] ) && $button_link['nofolow'] ) ? 'rel=nofolow' : "";

		//$classlist .= (isset($appearance) && $appearance) ? ' xtpb-btn-' . $appearance : '';
		$classlist .= ( isset( $style ) && $style ) ? ' xtpb-btn-' . $style : '';
		$classlist .= ( isset( $shape ) && $shape ) ? ' xtpb-btn-' . $shape : '';
		$classlist .= ( isset( $btn_size ) && $btn_size ) ? ' xtpb-btn-' . $btn_size : '';
		$classlist .= ( isset( $fullwidth_button ) && $fullwidth_button ) ? ' xtpb-btn-' . $fullwidth_button : '';

		if ( $icon_position == 'left' ) {
			$button = ( esc_attr( $icon_list ) ) ? '<i class="' . esc_attr( $icon_list ) . '"></i> ' . esc_attr( $button_text ) : esc_attr( $button_text );
		} else {
			$button = ( esc_attr( $icon_list ) ) ? esc_attr( $button_text ) . ' <i class="' . esc_attr( $icon_list ) . '"></i>' : esc_attr( $button_text );
		}
		if ( $button_link['link'] ) {
			$output  .= '<div class="xtpb-button-addon-content">';
				$output  .= '<a ' . esc_attr( $nofolow ).' href="' . esc_url( $button_link['link'] ) . '" '. esc_attr( $target ) . ' class="xtpb-btn-addons ' . esc_attr( $classlist ) . '">' . $button  . '</a>';
			$output  .= '</div>';
		}
		return $output;
	}

	// Button Template
	public function getTemplate(){
		$output = '
		<#
		var classList = "";
		classList += " xtpb-btn-"+data.style;
		classList += " xtpb-btn-"+data.shape;
		// classList += " xtpb-btn-"+data.appearance;
		classList += " xtpb-btn-"+data.btn_size;
		classList += " xtpb-btn-"+data.fullwidth_button;
		#>
		<div class="xtpb-button-addon-content">
			<# if( data.button_link && data.button_link.link) { #>
				<a {{ data.button_link && data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link && data.button_link.window ? "target=_blank" : "" }} {{ data.button_link && data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons {{classList}}">
					<# if(data.icon_position == "left" && !_.isEmpty(data.icon_list)) { #><i class="{{ data.icon_list }}"></i> <# } #>{{ data.button_text }}<# if(data.icon_position == "right" && !_.isEmpty(data.icon_list)) { #> <i class="{{ data.icon_list }}"></i><# } #>
				</a>
			<# } #>
		</div>
		';
		return $output;
	}

}
