<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Pie_Progress{

	public function get_name(){
		return 'xtpb_pie_progress';
	}
	public function get_title(){
		return __( '饼图进度', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-pie-progress';
	}
	public function get_enqueue_script(){
		return array( 'jquery.easypiechart','jquery.inview' );
	}
	
	// Pie Progress Settings Fields
	public function get_settings() {

		$settings = array(
			'show_icon_title' => array(
				'type' => 'radioimage',
				'title' => __( '布局', 'xintheme-pagebuilder' ),
				'values'=> array(
					'0' =>  XTPB_DIR_URL.'addons/pie_progress/img/pie-img1.png',
					'1' =>  XTPB_DIR_URL.'addons/pie_progress/img/pie-img2.png',
					'2' =>  XTPB_DIR_URL.'addons/pie_progress/img/pie-img3.png',
				),
				'std' => '0',
			),

			'progress_percentage' => array(
				'type' => 'slider',
				'title' => __('百分比','xintheme-pagebuilder'),
				'std' => 75,
				'responsive' => false,
				'range' => array(
					'min' => 0,
					'max' => 100,
					'step' => 1,
				),
			),
			'progress_size' => array(
				'type' => 'slider',
				'title' => __('条形尺寸','xintheme-pagebuilder'),
				'std' => 150,
				'responsive' => false,
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 40,
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
				'selector' => '{{SELECTOR}} .xtpb-pie-chart { height: {{data.progress_size}}px; width: {{data.progress_size}}px; }',
			),
			'progress_width' => array(
				'type' => 'slider',
				'title' => __('边框宽度','xintheme-pagebuilder'),
				'responsive' => false,
				'range' => array(
					'min' => 0,
					'max' => 20,
					'step' => 1,
				),
			),
			'progress_title' => array(
				'type' => 'text',
				'title' => __('标题','xintheme-pagebuilder'),
				'std' => __( 'WordPress', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon_title', '=', '1')),
			),
			'icon_list' => array(
				'type' => 'icon',
				'title' => __('图标列表','xintheme-pagebuilder'),
				'std' => 'ri-star-line',
				'depends' => array(array('show_icon_title', '=', '2')),
			),

			'align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'std' => array( 'md' => 'center', 'sm' => 'center', 'xs' => 'center' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-pie-progress-addon-content { text-align: {{data.align}}; }'
			),

			//style
			'progress_bar' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'std'=> '#F1F8FF',
			),
			'progress_bar_active' => array(
				'type' => 'color',
				'title' => __('激活颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'std'=> '#22A1FB',
			),
			'title_color' => array(
				'type' => 'color2',
				'title' => '',
				'tab' => 'style',
				'clip' => true,
				'std' => array(
					'clip' => true,
					'colorType' => 'color',
					'colorColor' => '#333',
				),
				'section' => __( '标题和百分比', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-chart-title, {{SELECTOR}} .xtpb-pie-chart .xtpb-chart-percent'
			),
			'title_style' => array(
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
				'section' => __( '标题和百分比', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-chart-title, {{SELECTOR}} .xtpb-pie-chart .xtpb-chart-percent',
				'tab' => 'style',
			),

			// icon
			'icon_size' => array(
				'type' => 'slider',
				'title' => __('尺寸','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 20,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 250,
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
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-chart-icon i { font-size: {{data.icon_size}}; }'
			),
			'icon_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-chart-icon span i{ color: {{data.icon_color}}; }'
			),
			'icon_bg' => array(
				'type' => 'color',
				'title' => __('背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-chart-icon span i{ background: {{data.icon_bg}}; }'
			),
			'icon_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
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
						'md' => '50px',
						'sm' => '',
						'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-chart-icon span i{ width: {{data.icon_width}}; }'
			),
			'icon_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
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
						'md' => '50px',
						'sm' => '',
						'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => array(
					'{{SELECTOR}} .xtpb-chart-icon span i{ height: {{data.icon_height}}; }',
					'{{SELECTOR}} .xtpb-chart-icon i { line-height: {{data.icon_height}}; }',
				)
			),
			'icon_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '100px', 'right' => '100px', 'bottom' => '100px', 'left' => '100px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}}  .xtpb-chart-icon span i{ border-radius: {{data.icon_radius}}; }'
			),
		);

		return $settings;
	}


	// Pie Progress Render HTML
	public function render($data = null){
		$settings 				= $data['settings'];

		$progress_percentage 	= isset($settings['progress_percentage']) ? sanitize_text_field( $settings['progress_percentage'] ) : '';
		$progress_size 			= isset($settings['progress_size']) ? sanitize_text_field( $settings['progress_size'] ) : '';
		$progress_width  		= isset($settings['progress_width']) ? sanitize_text_field( $settings['progress_width'] ) : '';
		$show_icon_title 		= isset($settings['show_icon_title']) ? sanitize_text_field( $settings['show_icon_title'] ) : 1;
		$icon_list 				= isset($settings['icon_list']) ? sanitize_text_field( $settings['icon_list'] ) : '';
		$progress_title 		= isset($settings['progress_title']) ? sanitize_text_field( $settings['progress_title'] ) : '';
		$progress_bar 			= isset($settings['progress_bar']) ? sanitize_text_field( $settings['progress_bar'] ) : '';
		$progress_bar_active 	= isset($settings['progress_bar_active']) ? sanitize_text_field( $settings['progress_bar_active'] ) : '';

		$output = '';

		if ( is_array ($progress_size ) ) {
			$progress_size =  ( isset($progress_size['md'] ) && $progress_size['md'] ) ? $progress_size['md']  : 150;
		} else {
			$progress_size = (isset($progress_size) && $progress_size) ? $progress_size : 150;;
		}

		if ( is_array ( $progress_percentage ) ) {
			$progress_percentage = (isset($progress_percentage['md']) && $progress_percentage['md']) ? $progress_percentage['md'] : 75;
		} else {
			$progress_percentage = (isset($progress_percentage) && $progress_percentage) ? $progress_percentage : 75;
		}

		if ( is_array ( $progress_width ) ) {
			$progress_width = (isset($progress_width['md']) && $progress_width['md']) ? $progress_width['md'] : 5;
		} else {
			$progress_width = (isset($progress_width) && $progress_width) ? $progress_width : 5;
		}

		$output  .= '<div class="xtpb-pie-progress-addon-content xtpb-clearfix">';

				$output .= '<div class="xtpb-pie-chart" data-size="'. (int) $progress_size .'" data-percent="'.$progress_percentage.'" data-width="'.$progress_width.'" data-barcolor="'.$progress_bar_active.'" data-trackcolor="'.$progress_bar.'">';

					if ( $show_icon_title == 1 ) {
						if ( $progress_title ) {
							$output .= '<div class="xtpb-chart-title"><span>' . esc_html( $progress_title ) . '</span></div>';
						}
					} elseif ( $show_icon_title == 2 ) {
						if ( $icon_list ) {
							$output .= '<div class="xtpb-chart-icon"><span><i class="' . esc_attr( $icon_list ) . '"></i></span></div>';
						}
					} else {
						$output .= '<div class="xtpb-chart-percent"><span></span></div>';
					}

				$output .= '</div>';

		$output .= '</div>';//xtpb-pie-progress-addon-content

		return $output;
	}

	// Pie Progress Template
	public function getTemplate(){
		$output = '
		<#
		var progress_size;
		if(_.isObject(data.progress_size) ){
			progress_size = parseInt(data.progress_size.md) ;
		}else{
			progress_size =  parseInt(data.progress_size) ? parseInt(data.progress_size) : "150";;
		}

		var progress_percentage;
		if(_.isObject(data.progress_percentage) ){
			progress_percentage = parseInt(data.progress_percentage.md) ;
		}else{
			progress_percentage =  parseInt(data.progress_percentage) ? parseInt(data.progress_percentage) : "75";
		}

		var progress_width;
		if(_.isObject(data.progress_width) ){
			progress_width = parseInt(data.progress_width.md) ;
		}else{
			progress_width = parseInt(data.progress_width) ? parseInt(data.progress_width) : "5";;
		}

		#>
			<div class="xtpb-pie-progress-addon-content xtpb-clearfix">

				<div class="xtpb-pie-chart" data-size="{{progress_size}}" data-percent="{{progress_percentage}}" data-width="{{progress_width}}" data-barcolor="{{data.progress_bar_active}}" data-trackcolor="{{data.progress_bar}}">
				
					<#
					if( data.show_icon_title == 1 ) {
						if( data.progress_title ) { #>
							<div class="xtpb-chart-title"><span>{{data.progress_title}}</span></div>
						<# }
					} else if ( data.show_icon_title == 2 ) {
						if(data.icon_list) { #>
							<div class="xtpb-chart-icon"><span><i class="{{data.icon_list}}"></i></span></div>
						<# }
					} else { #>
						<div class="xtpb-chart-percent"><span></span></div>
					<# } #>
					
				</div>

				</div>
		';
		return $output;
	}
}
