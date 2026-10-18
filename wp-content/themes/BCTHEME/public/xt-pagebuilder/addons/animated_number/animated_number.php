<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Animated_Number{

	public function get_name(){
		return 'xtpb_animated_number';
	}
	public function get_title(){
		return __( '动态数字', 'xintheme-pagebuilder' );
	}
	public function get_icon(){
		return 'xtpb-font-magic-wand';
	}
	public function get_enqueue_script(){
		return array( 'jquery.inview' );
	}

	// Animated Settings Fields
	public function get_settings() {

		$settings = array(
			'animated_layout' => array(
				'type' => 'radioimage',
				'title' => __( '布局', 'xintheme-pagebuilder' ),
				'values'=> array(
					'one' =>  XTPB_DIR_URL.'addons/animated_number/img/number-img1.jpg',
					'two' =>  XTPB_DIR_URL.'addons/animated_number/img/number-img2.jpg',
				),
				'std' => 'one',
			),
			'digit' => array(
				'type' => 'text',
				'title' => __('计数数字','xintheme-pagebuilder'),
				'std' => '500'
			),
			'digit_animation' => array(
				'type' => 'animation',
				'title' => __( '数字动画', 'xintheme-pagebuilder' ),
				'std' => array(
					'name' => 'fadeIn',   
					'delay' => '300',    
					'duration' => '400'
				),
			),
			'duration' => array(
				'type' => 'text',
				'title' => __('动画时长','xintheme-pagebuilder'),
				'std' => '1000',
			),
			'counter_title' => array(
				'type' => 'text',
				'title' => __('计数标题','xintheme-pagebuilder'),
				'std' => 'WordPress'
			),
			'title_animation' => array(
				'type' => 'animation',
				'title' => __( '标题动画', 'xintheme-pagebuilder' ),
				'std' => array(
					'name' => 'fadeIn',   
					'delay' => '300',  
					'duration' => '400' 
				),
			),
			'prefix_text' => array(
				'type' => 'text',
				'title' => __('前缀文本','xintheme-pagebuilder'),
				'std' => ''
			),
			'additional' => array(
				'type' => 'text',
				'title' => __('后缀文本','xintheme-pagebuilder'),
				'std' => '+'
			),
			'counter_align' => array(
				'type' => 'alignment',
				'title' => __('计数对齐','xintheme-pagebuilder'),
				'std' => array( 'md' => 'center', 'sm' => 'center', 'xs' => 'center' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-animated-counter-content { text-align: {{data.counter_align}}; }'
			),

			//number style	
			'number_color' => array(
				'type' => 'color2',
				'title' => __('数字颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '数字', 'xintheme-pagebuilder' ),
				'clip' => true,
				'std' => array(
					'colorType' => 'color',
					'clip' => true,
					'colorColor' => '#101010',
				),
				'selector' => '{{SELECTOR}} .xtpb-counter-number'
			),
			'number_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'60px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-counter-number',
				'section' => __( '数字', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'number_margin' => array(
				'type' => 'dimension',
				'title' => __( '数字外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-counter-number { margin: {{data.number_margin}}; }',
				'section' => __( '数字', 'xintheme-pagebuilder' ),
			),

			//title style	
			'title_color' => array(
				'type' => 'color2',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'clip' => true,
				'std' => array(
					'colorType' => 'color',
					'clip' => true,
					'colorColor' => '#101010',
				),
				'selector' => '{{SELECTOR}} .xtpb-count-number-title'
			),
			'title_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'18px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-count-number-title',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'title_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-count-number-title { margin: {{data.title_margin}}; }',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
			),

			//Suffix style	
			'additional_color' => array(
				'type' => 'color2',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '后缀', 'xintheme-pagebuilder' ),
				'clip' => true,
				'std' => array(
					'colorType' => 'color',
					'clip' => true,
					'colorColor' => '#101010',
				),
				'depends' => array(array('additional', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-count-number-addition'
			),
			'additional_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'60px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'depends' => array(array('additional', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-count-number-addition',
				'section' => __( '后缀', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'additional_space_horizontal' => array(
				'type' => 'slider',
				'title' => __('水平间距','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'range' => array(
					'em' => array(
						'min' => -5,
						'max' => 23,
						'step' => .1,
					),
					'px' => array(
						'min' => -120,
						'max' => 120,
						'step' => 1,
					),
					'%' => array(
						'min' => -10,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '10px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '后缀', 'xintheme-pagebuilder' ),
				'depends' => array(array('additional', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-count-number-addition { left: {{data.additional_space_horizontal}}; }',
			),
			'additional_space_vertical' => array(
				'type' => 'slider',
				'title' => __('垂直间距','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'range' => array(
					'em' => array(
						'min' => -5,
						'max' => 23,
						'step' => .1,
					),
					'px' => array(
						'min' => -50,
						'max' => 150,
						'step' => 1,
					),
					'%' => array(
						'min' => -10,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '0px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '后缀', 'xintheme-pagebuilder' ),
				'depends' => array(array('additional', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-count-number-addition { top: {{data.additional_space_vertical}}; }',
			),


			// Prefix style	
			'prefix_color' => array(
				'type' => 'color2',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '前缀', 'xintheme-pagebuilder' ),
				'clip' => true,
				'std' => array(
					'colorType' => 'color',
					'clip' => true,
					'colorColor' => '#101010',
				),
				'depends' => array(array('prefix_text', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-count-number-prefix'
			),
			'prefix_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'60px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'depends' => array(array('prefix_text', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-count-number-prefix',
				'section' => __( '前缀', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'prefix_space_horizontal' => array(
				'type' => 'slider',
				'title' => __('水平间距','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'range' => array(
					'em' => array(
						'min' => -5,
						'max' => 23,
						'step' => .1,
					),
					'px' => array(
						'min' => -120,
						'max' => 120,
						'step' => 1,
					),
					'%' => array(
						'min' => -10,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '10px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '前缀', 'xintheme-pagebuilder' ),
				'depends' => array(array('prefix_text', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-count-number-prefix { left: {{data.prefix_space_horizontal}}; }',
			),
			'prefix_space_vertical' => array(
				'type' => 'slider',
				'title' => __('垂直间距','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'range' => array(
					'em' => array(
						'min' => -5,
						'max' => 23,
						'step' => .1,
					),
					'px' => array(
						'min' => -50,
						'max' => 150,
						'step' => 1,
					),
					'%' => array(
						'min' => -10,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '0px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '前缀', 'xintheme-pagebuilder' ),
				'depends' => array(array('prefix_text', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-count-number-prefix { top: {{data.prefix_space_vertical}}; }',
			),

		);
		return $settings;
	}

	// Animated Render HTML
	public function render($data = null){
		$settings 				= $data['settings'];
		$title 					= isset($settings['counter_title']) ? sanitize_text_field( $settings['counter_title'] ) : '';
		$duration  				= isset( $settings['duration'] ) ? (int) $settings['duration'] : 1000;
		$digit 					= (int) isset($settings['digit']) ? $settings['digit'] : 500;
		$animated_layout 		= isset($settings['animated_layout']) ? sanitize_text_field( $settings['animated_layout'] ) : 'one';
		$digit_animation 		= (bool) isset($settings['digit_animation']) ? $settings['digit_animation'] : false;
		$title_animation 		= isset($settings['title_animation']) ? sanitize_text_field( $settings['title_animation'] ) : '';
		$additional 			= isset($settings['additional']) ? sanitize_text_field( $settings['additional'] ) : '';
		$prefix_text     		= isset($settings['prefix_text']) ? sanitize_text_field( $settings['prefix_text'] ) : '';

		$output = '';

		$output  .= '<div class="xtpb-animated-counter-content animated-layout-'.esc_attr($animated_layout).'">';
			if( $animated_layout == "two" ){
				if($title){
					if( isset( $digit_animation['itemOpen'] ) &&  $digit_animation['itemOpen'] ) {
						$output  .= '<div class="xtpb-count-number-title xtpb-wow xtpb-animated '.esc_attr($digit_animation['name']).'" data-wow-duration="'.esc_attr($digit_animation['duration']).'ms" data-wow-delay="'.esc_attr($digit_animation['delay']).'ms">'.esc_html($title).'</div>';
					} else {
						$output  .= '<div class="xtpb-count-number-title">'.esc_html($title).'</div>';
					}
				}
				if($digit){ 
					if($prefix_text) {
						$output  .= '<div class="xtpb-count-number-prefix">'.esc_html($prefix_text).'</div>';
					}
					if( isset( $title_animation['itemOpen'] ) && $title_animation['itemOpen'] ) {
						$output  .= '<div class="xtpb-counter-number xtpb-wow xtpb-animated '.esc_attr($title_animation['name']).'" data-wow-duration="'.esc_attr($title_animation['duration']).'ms" data-wow-delay="'.esc_attr($title_animation['delay']).'ms" data-digit="'.esc_attr($digit).'" data-duration="'.esc_attr($duration).'">0</div>';
					} else {
						$output  .= '<div class="xtpb-counter-number" data-digit="'.esc_attr($digit).'" data-duration="'.esc_attr($duration).'">0</div>';
					}
					if($additional) {
						$output  .= '<div class="xtpb-count-number-addition">'.esc_html($additional).'</div>';
					}
				}	
			} else {
				if($digit){ 
					if($prefix_text) {
						$output  .= '<div class="xtpb-count-number-prefix">'.esc_html($prefix_text).'</div>';
					}
					if(isset( $digit_animation['itemOpen'] ) && $digit_animation['itemOpen'] ) {
						$output  .= '<div class="xtpb-counter-number xtpb-wow xtpb-animated '.esc_attr($digit_animation['name']).'" data-wow-duration="'.esc_attr($digit_animation['duration']).'ms" data-wow-delay="'.esc_attr($digit_animation['delay']).'ms" data-digit="'.esc_attr($digit).'" data-duration="'.esc_attr($duration).'">0</div>';
					} else {
						$output  .= '<div class="xtpb-counter-number" data-digit="'.esc_attr($digit).'" data-duration="'.esc_attr($duration).'">0</div>';
					}
					if($additional) {
						$output  .= '<div class="xtpb-count-number-addition">'.esc_html($additional).'</div>';
					}
				}

				if($title){
					if(isset( $title_animation['itemOpen'] ) && $title_animation['itemOpen'] ) {
						$output  .= '<div class="xtpb-count-number-title xtpb-wow xtpb-animated '.esc_attr($title_animation['name']).'" data-wow-duration="'.esc_attr($title_animation['duration']).'ms" data-wow-delay="'.esc_attr($title_animation['delay']).'ms">'.esc_html($title).'</div>';
					} else {
						$output  .= '<div class="xtpb-count-number-title">'.esc_html($title).'</div>';
					}
				}
			}	
		$output .= '</div>';//xtpb-animated-counter-content
		return $output;
	}

	// Animated Template
	public function getTemplate(){
		$output = '
		<div class="xtpb-animated-counter-content animated-layout-{{data.animated_layout}}">
				<# if( data.animated_layout == "two" ){ #>
					<# if(data.counter_title){ #>
						<# if(data.title_animation.itemOpen) { #>
							<div class="xtpb-count-number-title xtpb-wow xtpb-animated {{ data.title_animation.name }}" data-wow-duration="{{ data.title_animation.duration }}ms" data-wow-delay="{{ data.title_animation.delay }}ms">{{ data.counter_title }}</div>
						<# } else { #>
							<div class="xtpb-count-number-title">{{ data.counter_title }}</div>
						<# } #>
					<# } #>
					<# if(data.digit){ #>
						<# if(data.prefix_text){ #>
							<div class="wxtpb-count-number-prefix">{{ data.prefix_text }}</div>
						<# } #>
						<# if(data.digit_animation.itemOpen) { #>
							<div class="xtpb-counter-number xtpb-wow xtpb-animated {{ data.digit_animation.name }}" data-wow-duration="{{ data.digit_animation.duration }}ms" data-wow-delay="{{ data.digit_animation.delay }}ms" data-digit="{{ data.digit }}" data-duration="{{ data.duration }}">0</div>
						<# } else { #>
							<div class="xtpb-counter-number" data-digit="{{ data.digit }}" data-duration="{{ data.duration }}">0</div>
						<# } #>
						<# if(data.additional){ #>
							<div class="xtpb-count-number-addition">{{ data.additional }}</div>
						<# } #>
					<# } #>	
				<# } else {  #>	
					<# if(data.digit){ #>
						<# if(data.prefix_text){ #>
							<div class="xtpb-count-number-prefix">{{ data.prefix_text }}</div>
						<# } #>
						<# if(data.digit_animation.itemOpen) { #>
							<div class="xtpb-counter-number xtpb-wow xtpb-animated {{ data.digit_animation.name }}" data-wow-duration="{{ data.digit_animation.duration }}ms" data-wow-delay="{{ data.digit_animation.delay }}ms" data-digit="{{ data.digit }}" data-duration="{{ data.duration }}">0</div>
						<# } else { #>
							<div class="xtpb-counter-number" data-digit="{{ data.digit }}" data-duration="{{ data.duration }}">0</div>
						<# } #>
						<# if(data.additional){ #>
							<div class="xtpb-count-number-addition">{{ data.additional }}</div>
						<# } #>
					<# } #>	
					<# if(data.counter_title){ #>
						<# if(data.title_animation.itemOpen) { #>
							<div class="xtpb-count-number-title xtpb-wow xtpb-animated {{ data.title_animation.name }}" data-wow-duration="{{ data.title_animation.duration }}ms" data-wow-delay="{{ data.title_animation.delay }}ms">{{ data.counter_title }}</div>
						<# } else { #>
							<div class="xtpb-count-number-title">{{ data.counter_title }}</div>
						<# } #>
					<# } #>
				<# } #>
			</div>
		';
		return $output;
	}
}
