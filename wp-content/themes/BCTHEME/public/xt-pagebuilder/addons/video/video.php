<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Video{

	public function get_name(){
		return 'xtpb_video';
	}
	public function get_title(){
		return __( '视频', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-video';
	}

	// Video Settings Fields
	public function get_settings() {
		$settings = array(
			'video_type' => array(
				'type' => 'select',
				'title' => __('视频来源','xintheme-pagebuilder'),
				'values' => array(
					'youtube' => __('YouTube','xintheme-pagebuilder'),
					'vimeo' => __('Vimeo','xintheme-pagebuilder'),
					'self-hosted' => __('自托管','xintheme-pagebuilder'),
				),
				'std' => 'youtube',
			),
			'video_url' => array(
				'type' => 'text',
				'title' => __('视频 URL','xintheme-pagebuilder'),
				'std' => 'https://www.youtube.com/watch?v=_cTAlxWtmS4'
			),
			'video_hide_logo' => array(
				'type' => 'switch',
				'title' => __('标志','xintheme-pagebuilder'),
				'std' => '1',
				'depends' => array(array('video_type', '=', 'youtube')),
			),
			'video_controls' => array(
				'type' => 'switch',
				'title' => __('控件','xintheme-pagebuilder'),
				'std' => '1',
				'depends' => array(array('video_type', '=', 'youtube')),
			),
			//style
			'video_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'std' => array(
					'md' => '100%',
					'sm' => '',
					'xs' => '',
				),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 90,
						'step' =>.1,
					),
					'px' => array(
						'min' => 0,
						'max' => 500,
						'step' =>1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'selector' => '{{SELECTOR}} .xtpb-video-block.xtpb-embed-responsive iframe { width: {{data.video_width}}; }'
			),
			'video_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'std' => array(
					'md' => '100%',
					'sm' => '',
					'xs' => '',
				),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 90,
							'step' =>.1,
						),
						'px' => array(
							'min' => 0,
							'max' => 500,
							'step' =>1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
				),
				'selector' => '{{SELECTOR}} .xtpb-video-block.xtpb-embed-responsive iframe { height: {{data.video_height}}; }'
			),
			'video_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'unit' => array( 'px','%','em' ),
				'selector' => '{{SELECTOR}} .xtpb-video-block.xtpb-embed-responsive{ border-radius: {{data.video_radius}}; }'
			),
		);

		return $settings;
	}


	// Video Render HTML
	public function render($data = null){
		$settings 		= $data['settings'];
		$video_url 		= isset( $settings['video_url'] ) ? sanitize_text_field( $settings['video_url'] ) : '';
		$video_controls = isset( $settings['video_controls'] ) ? sanitize_text_field( $settings['video_controls'] ) : '';
		$video_hide_logo = isset( $settings['video_hide_logo'] ) ? sanitize_text_field( $settings['video_hide_logo'] ) : '';
		$output = '';
		if ( $video_url ) {
			$video = parse_url( $video_url );

			switch( $video['host'] ) {
				case 'youtu.be':
				$id = trim( $video['path'],'/' );
				$src = '//www.youtube.com/embed/' . $id.'?controls=' . esc_attr( $video_controls ) . '&modestbranding=' . esc_attr( $video_hide_logo );
				break;

				case 'www.youtube.com':
				case 'youtube.com':
				parse_str( $video['query'], $query );
				$id = $query['v'];
				$src = '//www.youtube.com/embed/' . $id . '?controls=' . esc_attr( $video_controls ) . '&modestbranding=' . esc_attr( $video_hide_logo );
				break;

				case 'vimeo.com':
				case 'www.vimeo.com':
				$id = trim( $video['path'],'/' );
				$src = "//player.vimeo.com/video/{$id}";
			}
			$output  .= '<div class="xtpb-video-content">';
			$output .= '<div class="xtpb-video-block xtpb-embed-responsive xtpb-embed-responsive-16by9">';
			$output .= '<iframe class="xtpb-embed-responsive-item" src="' . esc_url( $src ) . '" allowFullScreen></iframe>';
			$output .= '</div>';
			$output .= '</div>';
			return $output;
		}
		return $output;
	}

	
	// headline Template
	public function getTemplate(){
		$output = '
		<#
			let videoUrl = data.video_url || ""
			let embedSrc = ""

			if ( videoUrl ) {
				let tempAchor = document.createElement("a")
						tempAchor.href = videoUrl

				let videoObj = {
						host    :   tempAchor.hostname,
						path    :   tempAchor.pathname,
						query   :   tempAchor.search.substr(tempAchor.search.indexOf("?") + 1)
					}

				switch( videoObj.host ){
					case "youtu.be":
						var videoId = videoObj.path.trim();
								embedSrc = "//www.youtube.com/embed"+ videoId + "?controls="+data.video_controls+"&modestbranding="+data.video_hide_logo
						break;

					case "www.youtube.com":
					case "youtube.com":
						var queryStr = videoObj.query.split("=");
								embedSrc = "//www.youtube.com/embed/"+ queryStr[1] + "?controls="+data.video_controls+"&modestbranding="+data.video_hide_logo
						break;

					case "www.vimeo.com":
					case "vimeo.com":
						var videoId = videoObj.path.trim();
								embedSrc = "//player.vimeo.com/video"+ videoId
						break;
				}
			}
		#>
		<div class="xtpb-video-content">
			<div class="xtpb-iframe-drag-overlay"></div>
			<div class="xtpb-video-block xtpb-embed-responsive xtpb-embed-responsive-16by9">
			<# if(embedSrc){ #>
				<iframe class="xtpb-embed-responsive-item" src=\'{{ embedSrc }}\' allowFullScreen></iframe>
			<# } #>
			</div>
		</div>
		';
		return $output;
	}

}
