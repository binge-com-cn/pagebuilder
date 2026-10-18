<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Video_Popup{

	public function get_name(){
		return 'xtpb_video_popup';
	}
	public function get_title(){
		return __( '视频弹窗', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-video-camera';
	}
	public function get_enqueue_script(){
		return array( 'jquery.magnific-popup' );
	}
	public function get_enqueue_style(){
		return array('magnific-popup');
	}

	// Video Settings Fields
	public function get_settings() {
		$settings = array(
			'video_url' => array(
				'type' => 'text',
				'title' => __('视频 URL','xintheme-pagebuilder'),
				'std' => 'https://www.youtube.com/watch?v=_cTAlxWtmS4'
			),
			'before_text' => array(
				'type' => 'text',
				'title' => __('视频前文字','xintheme-pagebuilder'),
			),
			'after_text' => array(
				'type' => 'text',
				'title' => __('视频后文字','xintheme-pagebuilder'),
			),
			'video_align' => array(
				'type' => 'select',
				'title' => __('视频图标对齐','xintheme-pagebuilder'),
				'responsive' => true,
				'values' => array(
					'video_left' => __('左侧','xintheme-pagebuilder'),
					'video_center' => __('居中','xintheme-pagebuilder'),
					'video_right' => __('右侧','xintheme-pagebuilder'),
				),
				'std' => 'video_center',
			),
			//style
			'icon_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon i { color: {{data.icon_color}}; }'
			),
			'icon_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon:hover i { color: {{data.icon_hcolor}}; }'
			),
			'icon_bg_color' => array(
				'type' => 'color',
				'title' => __('背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'std' => '#f5f5f5',
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon { background: {{data.icon_bg_color}}; }'
			),
			'icon_bg_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'std' => '#e5e5e5',
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon:hover { background: {{data.icon_bg_hcolor}}; }'
			),
			'icon_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon'
			),
			'icon_hover_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '悬停阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon:hover'
			),
			'icon_size' => array(
				'type' => 'slider',
				'title' => __('尺寸','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 23,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 350,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
				'std' => array(
						'md' => '32px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon i { font-size: {{data.icon_size}}; }',
			),
			'icon_line_height' => array(
				'type' => 'slider',
				'title' => __('行高','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 23,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 350,
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
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon i { line-height: {{data.icon_line_height}}; }',
			),
			'icon_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 23,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 350,
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
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon { width: {{data.icon_width}}; }',
			),
			'icon_height' => array(
				'type' => 'slider',
				'title' => __('图标高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 23,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 350,
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
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon { height: {{data.icon_height}}; }',
			),
			'icon_radius' => array(
				'type' => 'dimension',
				'title' => __('图标圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '100px', 'right' => '100px', 'bottom' => '100px', 'left' => '100px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-icon { border-radius: {{data.icon_radius}}; }',
			),

			//before text
			'before_color' => array(
				'type' => 'color',
				'title' => __('前置文字颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '前置文字', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-before-text a, {{SELECTOR}} .xtpb-video-popup-before-text a:hover { color: {{data.before_color}}; }'
			),
			'before_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-video-popup-before-text',
				'section' => __( '前置文字', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'before_margin' => array(
				'type' => 'dimension',
				'title' => __( '前置文字外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-video-popup-before-text { margin: {{data.before_margin}}; }',
				'section' => __( '前置文字', 'xintheme-pagebuilder' ),
			),

			//after text
			'after_color' => array(
				'type' => 'color',
				'title' => __('后置文字颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '后置文字', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-video-popup-after-text a,{{SELECTOR}} .xtpb-video-popup-after-text a:hover { color: {{data.after_color}}; }'
			),
			'after_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-video-popup-after-text',
				'section' => __( '后置文字', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'after_margin' => array(
				'type' => 'dimension',
				'title' => __( '后置文字外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-video-popup-after-text { margin: {{data.after_margin}}; }',
				'section' => __( '后置文字', 'xintheme-pagebuilder' ),
			),
		);
		return $settings;
	}

	// Video popup Render HTML
	public function render($data = null){
		$settings 		= $data['settings'];
		$video_url 		= isset($settings['video_url']) ? sanitize_text_field( $settings['video_url'] ) : '';
		$before_text 	= isset($settings['before_text']) ? sanitize_text_field( $settings['before_text'] ) : '';
		$after_text 	= isset($settings['after_text']) ? sanitize_text_field( $settings['after_text'] ) : '';
		$video_align 	= isset($settings['video_align']) ? sanitize_text_field( $settings['video_align'] ) : '';
		$output = $video_alignment = '';


			if( isset($video_align['md']) && $video_align['md'] ){
				$video_alignment .=  ( isset($video_align['md'] ) && $video_align['md'] ) ? 'md'.$video_align['md'].' '  : 'mdvideo_center';
			}
			if( isset($video_align['sm']) && $video_align['sm'] ){
				$video_alignment .=  ( isset($video_align['sm'] ) && $video_align['sm'] ) ? 'sm'.$video_align['sm'].' '  : ' smvideo_center';
			}
			if( isset($video_align['xs']) && $video_align['xs'] ){
				$video_alignment .=  ( isset($video_align['xs'] ) && $video_align['xs'] ) ? 'xs'.$video_align['xs'].' '  : ' xsvideo_center';
			}
		
			$output  .= '<div class="xtpb-video-popup-content">';
			$output  .= '<div class="xtpb-video-popup-wrap ' . esc_attr( $video_alignment ) . '">';
				if ( $before_text ) {
					$output  .= '<div class="xtpb-video-popup-before-text"><a href="' . esc_url( $video_url ) . '" class="xtpb-video-popup">' . esc_html( $before_text ) . '</a></div>';
				}
				$output  .= '<div class="xtpb-video-popup-content">';
					$output .= '<a href="' . esc_url( $video_url ) . '" class="xtpb-video-popup xtpb-video-popup-icon"><i class="xtpb-font-play-button-alt"></i></a>';
				$output .= '</div>';
				if ( $after_text ) {
					$output  .= '<div class="xtpb-video-popup-after-text"><a href="' . esc_url( $video_url ) . '" class="xtpb-video-popup">' . esc_html( $after_text ) . '</a></div>';
				}
			$output .= '</div>';
			$output .= '</div>';
		return $output;
	}

	// Video popup Template
	public function getTemplate(){
		$output = '
		<#
		var video_alignment = "";
			if(data.video_align["md"]){
				video_alignment +=  data.video_align["md"] ? "md"+data.video_align["md"]+" " : "mdvideo_center";
			}
			if(data.video_align["sm"]){
				video_alignment +=  data.video_align["sm"] ? "sm"+data.video_align["sm"]+" " : " smvideo_center";
			}
			if(data.video_align["xs"]){
				video_alignment +=  data.video_align["xs"] ? "xs"+data.video_align["xs"]+" " : " xsvideo_center";
			}
		#>
		<div class="xtpb-video-popup-content">
			<div class="xtpb-video-popup-wrap {{video_alignment}}">
					<# if( data.before_text ) { #>
						<div class="xtpb-video-popup-before-text"><a href="{{data.video_url}}" class="xtpb-video-popup">{{data.before_text}}</a></div>
					<# } #>
					<div class="xtpb-video-popup-content">
						<a href="{{data.video_url}}" class="xtpb-video-popup xtpb-video-popup-icon"><i class="xtpb-font-play-button-alt"></i></a>
					</div>
					<# if( data.after_text ) { #>
						<div class="xtpb-video-popup-after-text"><a href="{{data.video_url}}" class="xtpb-video-popup">{{data.after_text}}</a></div>
					<# } #>
			</div>
		</div>
		';
		return $output;
	}

}
