<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Progress_Bar{

	public function get_name(){
		return 'xtpb_progress_bar';
	}
	public function get_title(){
		return __( '进度条', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-progress-bar';
	}

	// Progress bar Settings Fields
	public function get_settings() {

		$settings = array(
			'progress_layout' => array(
				'type' => 'radioimage',
				'title' => __( '布局', 'xintheme-pagebuilder' ),
				'values'=> array(
					'one' =>  XTPB_DIR_URL.'addons/progress_bar/img/progress-img1.png',
					'two' =>  XTPB_DIR_URL.'addons/progress_bar/img/progress-img2.png',
					'three' =>  XTPB_DIR_URL.'addons/progress_bar/img/progress-img3.png',
				),
				'std' => 'one',
			),
			'title' => array(
				'type' => 'text',
				'title' => __('标题','xintheme-pagebuilder'),
				'std' => __( 'WordPress', 'xintheme-pagebuilder' )
			),
			'progress_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'range' => array(
					'min' => 0,
					'max' => 100,
					'step' => 1,
				),
				'std' => '50',
				'responsive' => false,
			),
			'progress_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'range' => array(
					'min' => 5,
					'max' => 100,
					'step' => 1,
				),
				'std' => '20',
				'responsive' => false,
				'depends' => array(array('progress_layout', '!=', array('three'))),
				'selector' => '{{SELECTOR}} .xtpb-progress{ height: {{data.progress_height}}px; }'
			),
			'stripped' => array(
				'type' => 'switch',
				'title' => __('显示条纹','xintheme-pagebuilder'),
				'std' => '0'
			),
			'show_title' => array(
				'type' => 'switch',
				'title' => __('显示标题','xintheme-pagebuilder'),
				'std' => '1'
			),
			'show_percent' => array(
				'type' => 'switch',
				'title' => __('显示百分比','xintheme-pagebuilder'),
				'std' => '1'
			),			

			//style
			'border_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-progress { border-radius: {{data.border_radius}}; }'
			),
			'overlay_radius' => array(
				'type' => 'dimension',
				'title' => __('覆盖层圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'std' => array(
					'md' => array( 'top' => '4px', 'right' => '4px', 'bottom' => '4px', 'left' => '4px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-progress-bar { border-radius: {{data.overlay_radius}}; }'
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
					'custom' => __('自定义','xintheme-pagebuilder'),
				),
				'std' => 'primary',
				'tab' => 'style',
				'section' => __( '样式', 'xintheme-pagebuilder' ),
			),
			'progress_bg' => array(
				'type' => 'color2',
				'title' => __('自定义背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#f5f5f5',
					'clip' => false,
				),
				'selector' => '{{SELECTOR}} .xtpb-progress',
				'depends' => array(array('style', '=', array('custom'))),
				'section' => __( '样式', 'xintheme-pagebuilder' ),
			),
			'progress_bg_overlay' => array(
				'type' => 'color2',
				'title' => __('自定义覆盖层背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#3D88E4',
					'clip' => false,
				),
				'selector' => '{{SELECTOR}} .xtpb-progress-bar',
				'depends' => array(array('style', '=', array('custom'))),
				'section' => __( '样式', 'xintheme-pagebuilder' ),
			),

			//title
			'title_color' => array(
				'type' => 'color2',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => true,
				'std' => array(
					'clip' => true,
					'colorType' => 'color',
					'colorColor' => '#333',
				),
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-progress-label'
			),

			'font_fontstyle' => array(
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
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-progress-label',
				'tab' => 'style',
			),
			'title_margin_bottom' => array(
				'type' => 'slider',
				'title' => __('底部外边距','xintheme-pagebuilder'),
				'range' => array(
					'min' => 0,
					'max' => 150,
					'step' => 1,
				),
				'std' => 4,
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-progress-label { margin-bottom: {{data.title_margin_bottom}}px; }'
			),

			//Percentage
			'percent_text_color' => array(
				'type' => 'color2',
				'title' => __('颜色','xintheme-pagebuilder'),
				'clip' => true,
				'tab' => 'style',
				'std' => array(
					'clip' => true,
					'colorType' => 'color',
					'colorColor' => '#333',
				),
				'section' => __( '百分比', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-progress-percent, {{SELECTOR}} .progressbar-layout-two .xtpb-progress-bar span, {{SELECTOR}} .progressbar-layout-three .xtpb-progress-bar span'
			),
			'percent_text_bg' => array(
				'type' => 'color',
				'title' => __('盒子背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '百分比', 'xintheme-pagebuilder' ),
				'depends' => array(array('progress_layout', '=', array('three'))),
				'selector' => array(
					'{{SELECTOR}} .progressbar-layout-three .xtpb-progress-bar span { background: {{data.percent_text_bg}}; }',
					'{{SELECTOR}} .progressbar-layout-three .xtpb-progress-bar span:after { border-color: {{data.percent_text_bg}} rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) rgba(0, 0, 0, 0); }',
				),
			),
			'percent_fontstyle' => array(
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
				'section' => __( '百分比', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-progress-percent',
				'tab' => 'style',
			),
			'percent_margin_bottom' => array(
				'type' => 'slider',
				'title' => __('底部外边距','xintheme-pagebuilder'),
				'range' => array(
					'min' => -80,
					'max' => 100,
					'step' => 1,
				),
				'std' => 4,
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '百分比', 'xintheme-pagebuilder' ),
				'selector' => array(
					'{{SELECTOR}} .xtpb-progress-percent { margin-bottom: {{data.percent_margin_bottom}}px; }',
					'{{SELECTOR}} .progressbar-layout-one .xtpb-progress-bar span { top: {{data.percent_margin_bottom}}px; }',
				)
			),
		);

		return $settings;
	}


	// Progress bar Render HTML
	public function render($data = null){
		$settings 			= $data['settings'];

		$title 				= isset($settings['title']) ? sanitize_text_field( $settings['title'] ) : '';
		$progress_width  	= isset($settings['progress_width']) ? sanitize_text_field( $settings['progress_width'] ) : '';
		$style 				= isset($settings['style']) ? sanitize_text_field( $settings['style'] ) : '';
		$stripped 			= isset($settings['stripped']) ? sanitize_text_field( $settings['stripped'] ) : '';
		$show_title 		= isset($settings['show_title']) ? sanitize_text_field( $settings['show_title'] ) : '';
		$show_percent 		= isset($settings['show_percent']) ? sanitize_text_field( $settings['show_percent'] ) : '';
		$progress_layout 	= isset($settings['progress_layout']) ? sanitize_text_field( $settings['progress_layout'] ) : '';

		$output = $show_percent_content = $show_title_content = '';

		$stripped = ( isset( $stripped ) && $stripped ) ? $stripped : 0;
		$progress_width = ( isset( $progress_width ) && $progress_width ) ? $progress_width : 50;
		if ( $stripped ) {
			$stripped = 'xtpb-progress-bar-striped';
		}
		$output  .= '<div class="xtpb-progressbar-addon-content progressbar-layout-' . esc_attr( $progress_layout ) . '">';

			if ( $progress_layout == "two" ) {
				if ( $show_title == 1 ) {
					$show_title_content  = esc_attr( $title );
				}
				if ( ( $show_title == 1 ) ) {
					$output  .= '<div class="xtpb-progress-label xtpb-clearfix">' .  esc_html( $show_title_content ) . '</div>';
				}
				$output .= '<div class="xtpb-progress">';
					$output .= '<div class="xtpb-progress-bar xtpb-progress-bar-' . esc_attr( $style ) . ' ' . esc_attr( $stripped ) . '" style="width:' . (int) esc_attr( $progress_width ) . '%">';
					if ( $show_percent == 1 ) {
						$output .= '<span class="xtpb-progress-percent">' . (int) esc_html( $progress_width ) . '%</span>';
					}
					$output .= '</div>';
				$output .= '</div>';//xtpb-progress

			} elseif ( $progress_layout == "three" ) {

				if ( $show_title == 1 ) {
					$show_title_content  = esc_attr( $title );
				}
				if ( ( $show_title == 1 ) ) {
					$output  .= '<div class="xtpb-progress-label xtpb-clearfix">' .  esc_html( $show_title_content ) . '</div>';
				}
				$output .= '<div class="xtpb-progress">';
					$output .= '<div class="xtpb-progress-bar xtpb-progress-bar-'. esc_attr( $style)  . ' ' . esc_attr( $stripped ) . '" style="width:'. (int) esc_attr( $progress_width ) . '%">';
					if ( $show_percent == 1 ) {
						$output .= '<span class="xtpb-progress-percent">' . (int) esc_html( $progress_width ) . '%</span>';
					}
					$output .= '</div>';
				$output .= '</div>';//xtpb-progress

			} else {

				if ( $show_title == 1 ) {
					$show_title_content  = esc_attr( $title );
				}
				if ( ( $show_title == 1 ) ) {
					$output  .= '<div class="xtpb-progress-label xtpb-clearfix">' . esc_html( $show_title_content ) . '</div>';
				}
				$output .= '<div class="xtpb-progress">';
					$output .= '<div class="xtpb-progress-bar xtpb-progress-bar-' . esc_attr( $style ) . ' ' . esc_attr( $stripped ) . '" style="width:'. (int) esc_attr( $progress_width ) . '%">';
					if ( $show_percent == 1 ) {
						$output .= '<span class="xtpb-progress-percent">' . (int) esc_attr( $progress_width ) . '%</span>';
					}
					$output .= '</div>';
				$output .= '</div>';//xtpb-progress

			}

		$output .= '</div>';//xtpb-progressbar-addon-content

		return $output;
	}

	// Progress Bar Template
	public function getTemplate(){
		$output = '
			<#
				var stripped = "";
				if(data.stripped == 1){
					stripped = "xtpb-progress-bar-striped";
				}
			#>
			<# 
			var show_title_content = "";
			if(data.show_title == 1){ 
				show_title_content = data.title;
			 } #>	

			<# 
			var show_percent_content = "";
			if(data.show_percent == 1){   
				show_percent_content =  data.progress_width ? data.progress_width : "50";
			} #>		

			<div class="xtpb-progressbar-addon-content progressbar-layout-{{data.progress_layout}}">

				<# if( data.progress_layout == "two" ){ #>

					<# if( (data.show_title==1) ) { #>
						<div class="xtpb-progress-label xtpb-clearfix">{{show_title_content}} 
						</div>
					<# } #>
					<div class="xtpb-progress">
						<div class="xtpb-progress-bar xtpb-progress-bar-{{data.style}} {{stripped}}" style="width: {{data.progress_width}}%">
							<# if(data.show_percent == 1) { #>	
								<span class="xtpb-progress-percent"> {{show_percent_content}}% </span>
							<# } #>
						</div>
					</div>

				<# } else if( data.progress_layout == "three" ){ #>	

					<# if( (data.show_title==1) ) { #>
						<div class="xtpb-progress-label xtpb-clearfix">{{show_title_content}} 
						</div>
					<# } #>
					<div class="xtpb-progress">
						<div class="xtpb-progress-bar xtpb-progress-bar-{{data.style}} {{stripped}}" style="width: {{data.progress_width}}%">
						<# if(data.show_percent == 1) { #>	
							<span class="xtpb-progress-percent"> {{show_percent_content}}% </span>
						<# } #>
						</div>
					</div>

				<# } else {  #>	

					<# if( (data.show_title==1) ) { #>
						<div class="xtpb-progress-label xtpb-clearfix">{{show_title_content}} 
						</div>
					<# } #>
					<div class="xtpb-progress">
						<div class="xtpb-progress-bar xtpb-progress-bar-{{data.style}} {{stripped}}" style="width: {{data.progress_width}}%">							
						<# if(data.show_percent == 1) { #>	
							<span class="xtpb-progress-percent"> {{show_percent_content}}% </span>
						<# } #>
						</div>
					</div>

				<# } #>

				</div>
		';
		return $output;
	}
}
