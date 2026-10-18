<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Image{

	public function get_name(){
		return 'xtpb_image';
	}
	public function get_title(){
		return __( '图片', 'xintheme-pagebuilder' );
	}
	public function get_icon(){
		return 'xtpb-font-image';
	}

	public function get_enqueue_script(){
		return array('jquery.magnific-popup');
	}

	public function get_enqueue_style(){
		return array('magnific-popup');
	}

	// image Settings Fields
	public function get_settings() {

		$settings = array(

			'upload_image' => array(
				'type' => 'media',
				'title' => __('上传图片','xintheme-pagebuilder'),
				'std'=> array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-medium.jpg' ),
			),
			'替代文本_text' => array(
				'type' => 'text',
				'title' => __('替代文本','xintheme-pagebuilder'),
				'std' => __( '图片', 'xintheme-pagebuilder' ),
			),
			'caption_text' => array(
				'type' => 'text',
				'title' => __( '图片说明', 'xintheme-pagebuilder' ),
				'std' => __( '图片说明文字', 'xintheme-pagebuilder' ),
			),
            'lightbox' => array(
                'type' => 'switch',
                'title' => __('启用弹窗','xintheme-pagebuilder'),
                'std' => '0'
            ),
            'lightbox_icon' => array(
                'type' => 'switch',
                'title' => __('启用图标','xintheme-pagebuilder'),
                'std' => '0'
            ),
			'image_link' => array(
				'type' => 'link',
				'title' => __('链接','xintheme-pagebuilder'),
				'std' =>   array('link'=>'','window'=>true,'nofolow'=>false),
				'depends' => array(array('lightbox', '!=', '1')),
			),
			'image_display' => array(
				'type' => 'select',
				'title' => __('图片显示方式','xintheme-pagebuilder'),
				'values' => array(
					'imgblock' => __('块级','xintheme-pagebuilder'),
					'imginlineblock' => __('行内块','xintheme-pagebuilder'),
					'imginline' => __('行内','xintheme-pagebuilder'),
				),
				'std' => 'imginlineblock',
			),
			'image_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-image-addon-content-wrap { text-align: {{data.image_align}}; }'
			),

			//style
			'image_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 40,
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
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'tab' => 'style',
				'responsive' => true,
				'selector' => array(
					'{{SELECTOR}} .xtpb-image-addon-img { width: {{data.image_width}}; }',
					'{{SELECTOR}} .xtpb-addon-image-overlay { width: {{data.image_width}}; }'
				),
			),
			'image_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 40,
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
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-image-addon-img, {{SELECTOR}} .xtpb-addon-image-overlay { height: {{data.image_height}}; }',
			),

			'border_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-image-addon-content, {{SELECTOR}} .xtpb-image-addon-img,{{SELECTOR}} .xtpb-addon-image-overlay  { border-radius: {{data.border_radius}}; }',
			),
			'border_hover_radius' => array(
				'type' => 'dimension',
				'title' => __('悬停边框圆角','xintheme-pagebuilder'),
				'responsive' => true,
				'unit' => array( '%','px','em' ),
				'tab' => 'style',
				'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-image-addon-content:hover, {{SELECTOR}} .xtpb-image-addon-content:hover img, {{SELECTOR}} .xtpb-image-addon-content:hover .xtpb-addon-image-overlay { border-radius: {{data.border_hover_radius}}; }',
			),
			'image_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#cccccc'
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-image-addon-img, {{SELECTOR}} .xtpb-addon-image-overlay'
			),
			'image_border_hover' => array(
				'type' => 'border',
				'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#cccccc'
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-image-addon-img:hover, {{SELECTOR}} .xtpb-addon-image-overlay:hover'
			),
			'image_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
                ),
				'selector' => '{{SELECTOR}} .xtpb-image-addon-img, {{SELECTOR}} .xtpb-addon-image-overlay',
				'tab' => 'style',
				'section' => __( '阴影', 'xintheme-pagebuilder' ),
			),
			'image_hboxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '悬停阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
                ),
				'selector' => '{{SELECTOR}} .xtpb-image-addon-img:hover, {{SELECTOR}} .xtpb-addon-image-overlay:hover',
				'tab' => 'style',
				'section' => __( '阴影', 'xintheme-pagebuilder' ),
			),

			//light box
			'overlay_icon' => array(
				'type' => 'color',
				'title' => __('图标颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '遮罩层', 'xintheme-pagebuilder' ),
				'depends' => array(array('lightbox', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-addon-image-overlay-icon { color: {{data.overlay_icon}}; }'
			),

			'overlay_bg' => array(
				'type' => 'color',
				'title' => __('背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '遮罩层', 'xintheme-pagebuilder' ),
				'depends' => array(array('lightbox', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-addon-image-overlay{ background: {{data.overlay_bg}}; }'
			),

			//caption
			'caption_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图片说明', 'xintheme-pagebuilder' ),
				'depends' => array(array('caption_text', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-addon-image-caption{ color: {{data.caption_color}}; }'
			),
			'caption_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'12px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-addon-image-caption',
				'section' => __( '图片说明', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'caption_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图片说明', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-addon-image-caption { margin: {{data.caption_margin}}; }',
			),

			'opacity' => array(
				'type' => 'slider',
				'title' => __( '透明度', 'xintheme-pagebuilder' ),
				'range' => array(
					'min' => 0,
					'max' => 1,
					'step' => .01,
				),
				'selector' => '{{SELECTOR}} img.xtpb-image-addon-img{ opacity:{{data.opacity}}; }',
				'tab' => 'style',
				'section' => __( '透明度', 'xintheme-pagebuilder' ),
			),

		);

		return $settings;
	}


	// Image Render HTML
	public function render($data = null){
		$settings 		= $data['settings'];
		$upload_image 	= isset($settings['upload_image']) ? $settings['upload_image'] : array();
		$lightbox 		= isset($settings['lightbox']) ? sanitize_text_field( $settings['lightbox'] ) : '';
        $lightbox_icon 	= isset($settings['lightbox_icon']) ? sanitize_text_field( $settings['lightbox_icon'] ) : '';
		$替代文本_text 		= isset($settings['替代文本_text']) ? sanitize_text_field( $settings['替代文本_text'] ) : '';
		$caption_text 	= isset($settings['caption_text']) ? sanitize_text_field( $settings['caption_text'] ) : '';
		$image_link 	= isset($settings['image_link']) ? $settings['image_link'] : array();
		$image_display  = isset($settings["image_display"]) ? sanitize_text_field( $settings["image_display"] ) : 'imginlineblock';

		$img_url = '';
		$output = '';

		if(! empty($upload_image['url'])){
			$img_url = $upload_image['url'];
		}

		$target =  get_xtpb_array_value_by_key($image_link, 'window') ? 'target=_blank' : "";
		$nofolow = get_xtpb_array_value_by_key($image_link, 'window') ? 'rel=nofolow' : "";

		$output  .= '<figure class="xtpb-image-addon-content-wrap">';
		$output  .= '<div class="xtpb-image-addon-content xtpb-'.esc_attr($image_display).'">';
		if($lightbox) {
		    $icon = $lightbox_icon == 1 ? '<i class="ri-add-line xtpb-addon-image-overlay-icon" aria-hidden="true"></i>' : '';
            $output .= '<a class="xtpb-image-popup" data-popup_type="image" data-mainclass="mfp-no-margins mfp-with-zoom" href="'. esc_url($img_url) .'">';
			$output  .= '<div class="xtpb-addon-image-overlay">'.$icon.'</div>';
		}
		if( !$lightbox && get_xtpb_array_value_by_key($image_link, 'link') ) {
			$output  .= '<a '.esc_attr($nofolow).' href="'.esc_url(get_xtpb_array_value_by_key($image_link, 'link')).'" '.esc_attr($target).'>';
		}
		$output  .= '<img class="xtpb-image-addon-img" src="'.esc_url($img_url).'" 替代文本="'. esc_attr($替代文本_text) .'">';
		if(  !$lightbox && get_xtpb_array_value_by_key($image_link, 'link') ) {
			$output .= '</a>';
		}
        if($lightbox) {
            $output .= '</a>';
        }
		$output .= '</div>';
		if($caption_text){
			$output .= '<figcaption class="xtpb-addon-image-caption">'. esc_html($caption_text).'</figcaption>';
		}
		$output .= '</figure>';//xtpb-image-addon-content-wrap

		return $output;
	}

	// Image Template
	public function getTemplate(){
		$output = '
		<figure class="xtpb-image-addon-content-wrap">
				<div class="xtpb-image-addon-content xtpb-{{ data.image_display }}">
					<# if( data.lightbox == 1 ) { #>
					    <a class="xtpb-image-popup" data-popup_type="image" data-mainclass="mfp-no-margins mfp-with-zoom" href="{{data.upload_image.url }}">
						<div class="xtpb-addon-image-overlay">
						    <# if(data.lightbox_icon) { #><i class="ri-add-line xtpb-addon-image-overlay-icon" aria-hidden="true"></i> <# } #>
                        </div>
					<# } #>
					<# if( data.lightbox == 0 && !__.isEmpty(data.image_link.link) ) { #>
						<a {{ data.image_link.link ? "href="+data.image_link.link : "" }} {{ data.image_link.window ? "target=_blank" : "" }} {{ data.image_link.nofolow ? "rel=nofolow" : "" }}>
					<# } #>
					<# if( data.upload_image ) { #>
						<# if( data.upload_image.url ) { #>
							<img class="xtpb-image-addon-img" src="{{data.upload_image.url }}">
						<# } #>
					<# } #>
					<# if( data.lightbox == 0 && !__.isEmpty(data.image_link.link) ) { #>
						</a> 
					<# } #>
					<# if( data.lightbox == 1 ) { #>
					    </a>
					<# } #>
				</div>
				<# if(data.caption_text) { #>
				<figcaption class="xtpb-addon-image-caption">{{ data.caption_text }}</figcaption>
				<# } #>
			</figure>
		';
		return $output;
	}
}
