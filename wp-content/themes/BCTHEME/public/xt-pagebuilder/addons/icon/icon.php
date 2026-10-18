<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Icon{

	public function get_name(){
		return 'xtpb_icon';
	}
	public function get_title(){
		return __( '图标', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-heart-thin';
	}

	// Icon Settings Fields
	public function get_settings() {

		$settings = array(
			'icon_list' => array(
				'type' => 'icon',
				'title' => __('图标','xintheme-pagebuilder'),
				'std' => 'ri-home-4-line'
			),
			'icon_link' => array(
				'type' => 'link',
				'std' => array( 'link'=>'','window'=>false,'nofolow'=>false ),
				'title' => __('链接','xintheme-pagebuilder'),
			),
			'icon_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'std' => array( 'md' => 'center', 'sm' => 'center', 'xs' => 'center' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}}.xtpb-icon-content, {{SELECTOR}} .xtpb-icon-content { text-align: {{data.icon_align}}; }'
			),

			// Style Area
			'icon_size' => array(
				'type' => 'slider',
				'title' => __('尺寸','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 200,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 400,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '36px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner i, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner i { font-size: {{data.icon_size}}; }'
			),
			'icon_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 50,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 500,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '100px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner { width: {{data.icon_width}}; }'
			),

			'icon_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 50,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 500,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
				'std' => array(
						'md' => '100px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'selector' => array(
					'{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner { height: {{data.icon_height}}; }',
					'{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner i, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner i { line-height: {{data.icon_height}}; }',
				)
			),
			'icon_line_height' => array(
				'type' => 'slider',
				'title' => __('行高','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 50,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 500,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
				'std' => array(
						'md' => '100px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner { line-height: {{data.icon_line_height}}; }',
			),
			'icon_color' => array(
				'type' => 'color2',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => true,
				'std' => array(
					'clip' => true,
					'colorType' => 'color',
					'colorColor' => '#ffffff',
				),
				'section' => __( '颜色', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner i, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner i, {{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner a i, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner a i'
			),
			'icon_hcolor' => array(
				'type' => 'color2',
				'title' => __('悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => true,
				'std' => array(
					'clip' => true,
					'colorType' => 'color',
					'colorColor' => '#ffffff',
				),
				'section' => __( '颜色', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:hover i, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:hover i, {{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:hover a i, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:hover a i'
			),
			'icon_bg' => array(
				'type' => 'color2',
				'title' => __('背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '颜色', 'xintheme-pagebuilder' ),
				'clip' => false,
				'std' => array(
					'clip' => false,
					'colorType' => 'color',
					'colorColor' => '#0080FE',
				),
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner'
			),
			'icon_hover_bg' => array(
				'type' => 'color2',
				'title' => __('悬停背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => false,
				'std' => array(
					'clip' => false,
					'colorType' => 'color',
					'colorColor' => '#0170dc',
				),
				'section' => __( '颜色', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:before, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:before, {{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:hover, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:hover'
			),
			'icon_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner'
			),
			'border_hcolor' => array(
				'type' => 'border',
				'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:hover, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:hover, {{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:before, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:before'
			),
			'icon_radius' => array(
				'type' => 'dimension',
				'title' => __( '边框圆角', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'std' => array(
					'md' => array( 'top' => '100px', 'right' => '100px', 'bottom' => '100px', 'left' => '100px' ), 
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner, {{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:before, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:before { border-radius: {{data.icon_radius}}; }',
			),
			'icon_hover_radius' => array(
				'type' => 'dimension',
				'title' => __( '悬停边框圆角', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'std' => array(
					'md' => array( 'top' => '100px', 'right' => '100px', 'bottom' => '100px', 'left' => '100px' ), 
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
				),
				'tab' => 'style',
				'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:hover, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:hover, {{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:hover:before, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:hover:before { border-radius: {{data.icon_hover_radius}}; }',
			),
			'icon_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '图标阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner, {{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:before, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:before',
				'tab' => 'style',
				'section' => __( '盒子阴影', 'xintheme-pagebuilder' ),
			),
			'icon_hboxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '图标悬停阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'selector' => '{{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:hover, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:hover, {{SELECTOR}}.xtpb-icon-content .xtpb-icon-inner:hover:before, {{SELECTOR}} .xtpb-icon-content .xtpb-icon-inner:hover:before',
				'tab' => 'style',
				'section' => __( '盒子阴影', 'xintheme-pagebuilder' ),
			),
		);

		return $settings;
	}

	// Icon Render HTML
	public function render($data = null){
		$settings 			= $data['settings']; 
		$icon_list 			= isset($settings["icon_list"]) ? sanitize_text_field( $settings["icon_list"] ) : '';
		$icon_link 	    	= isset($settings["icon_link"]) ? $settings["icon_link"] : array();

		$output = '' ;

		$target = $icon_link['window'] ? 'target=_blank' : "";
		$nofolow = $icon_link['nofolow'] ? 'rel=nofolow' : "";

		$output  .= '<div class="xtpb-icon-content">';
		if($icon_link['link']) {
			$output  .= '<span class="xtpb-icon-inner"><a '.esc_attr($nofolow).' href="'.esc_url($icon_link['link']).'" '.esc_attr($target).'>';
			$output  .= '<i class="' . esc_attr($icon_list) . '"></i>';
			$output  .= '</a></span>';
		} else {
			$output  .= '<span class="xtpb-icon-inner"><i class="' . esc_attr($icon_list) . '"></i></span>';
		}
		$output  .= '</div>';

		return $output;
	}

	// Icon Template
	public function getTemplate(){
		$output = '
			<div class="xtpb-icon-content">
				<# if( !__.isEmpty(data.icon_link.link) ) { #>
					<span class="xtpb-icon-inner"><a {{ data.icon_link.link ? "href="+data.icon_link.link : "" }} {{ data.icon_link.window ? "target=_blank" : "" }} {{ data.icon_link.nofolow ? "rel=nofolow" : "" }}>
					<i class=" {{data.icon_list}}"></i>
					</a></span>
				<# } else { #>	
					<span class="xtpb-icon-inner"><i class=" {{data.icon_list}}"></i></span>
				<# } #>
			</div>
		';
		return $output;
	}

}
