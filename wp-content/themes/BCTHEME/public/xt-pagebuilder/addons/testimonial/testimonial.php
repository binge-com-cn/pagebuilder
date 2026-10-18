<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Testimonial{

	public function get_name(){
		return 'xtpb_testimonial';
	}
	public function get_title(){
		return __( '评价', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-quote';
	}

	// Testimonial Settings Fields
	public function get_settings() {

		$settings = array(

			'testimonial_layout' => array(
				'type' => 'radioimage',
				'title' => __( '布局', 'xintheme-pagebuilder' ),
				'values'=> array(
					'one' =>  XTPB_DIR_URL.'addons/testimonial/img/testimonial-img1.png',
					'two' =>  XTPB_DIR_URL.'addons/testimonial/img/testimonial-img2.png',
					'three' =>  XTPB_DIR_URL.'addons/testimonial/img/testimonial-img3.png',
					'four' =>  XTPB_DIR_URL.'addons/testimonial/img/testimonial-img4.png',
				),
				'std' => 'one',
			),

			'image' => array(
				'type' => 'media',
				'title' => __('图片','xintheme-pagebuilder'),
				'std'=> array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-small.jpg' ),
			),

			'name' => array(
				'type' => 'text',
				'title' => __('名称','xintheme-pagebuilder'),
				'std' => __( '老王', 'xintheme-pagebuilder' ),
			),
			'name_link' => array(
				'type' => 'link',
				'title' => __('链接','xintheme-pagebuilder'),
				'std' =>   array('link'=>'','window'=>true,'nofolow'=>false),
			),
			'designation' => array(
				'type' => 'text',
				'title' => __('职位','xintheme-pagebuilder'),
				'std' => __( '总经理', 'xintheme-pagebuilder' ),
			),
			'introtext' => array(
				'type' => 'textarea',
				'title' => __('消息文本','xintheme-pagebuilder'),
				'std' => __( '整体使用体验很不错，功能清晰，操作简单，页面展示效果也比较专业，能够满足日常网站搭建和内容展示需求。', 'xintheme-pagebuilder' ),
			),

			'testimonial_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-content{ text-align: {{data.testimonial_align}}; }'
			),

			//style
			'img_width' => array(
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
						'md' => '50px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-img { width: {{data.img_width}}; }',
			),
			'img_height' => array(
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
						'md' => '50px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-img { height: {{data.img_height}}; }',
			),
			'img_radius' => array(
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
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-img { border-radius: {{data.img_radius}}; }',
			),
			'image_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#cccccc'
				),
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-img'
			),
			'image_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-img { margin: {{data.image_margin}}; }'
			),
			//name
			'name_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '名称', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-name, {{SELECTOR}} .xtpb-testimonial-name a { color: {{data.name_color}}; }'
			),
			'name_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-name',
				'section' => __( '名称', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'name_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-testimonial-name { margin: {{data.name_margin}}; }',
				'section' => __( '名称', 'xintheme-pagebuilder' ),
			),

			//职位
			'desgn_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '职位', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-designation { color: {{data.desgn_color}}; }'
			),
			'desgn_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-designation',
				'section' => __( '职位', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),

			//intro
			'intro_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '消息文本', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-introtext { color: {{data.intro_color}}; }'
			),
			'intro_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-introtext, {{SELECTOR}} .testimonial-layout-four .xtpb-testimonial-introtext',
				'section' => __( '消息文本', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'message_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-testimonial-introtext, {{SELECTOR}} .testimonial-layout-four .xtpb-testimonial-introtext { margin: {{data.message_margin}}; }',
				'section' => __( '消息文本', 'xintheme-pagebuilder' ),
			),

			//intro
			'quote_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '引用图标', 'xintheme-pagebuilder' ),
				'depends' => array(array('testimonial_layout', '=', array('four'))),
				'selector' => '{{SELECTOR}} .testimonial-layout-four .xtpb-testimonial-quote { color: {{data.quote_color}}; }'
			),
			'quote_fontsize' => array(
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
							'max' => 300,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
				'std' => array(
					'md' => '48px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '引用图标', 'xintheme-pagebuilder' ),
				'depends' => array(array('testimonial_layout', '=', array('four'))),
				'selector' => '{{SELECTOR}} .testimonial-layout-four .xtpb-testimonial-quote { font-size: {{data.quote_fontsize}}; }'
			),
			'quote_line_height' => array(
				'type' => 'slider',
				'title' => __('行高','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 20,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 300,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
				),
				'std' => array(
					'md' => '48px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '引用图标', 'xintheme-pagebuilder' ),
				'depends' => array(array('testimonial_layout', '=', array('four'))),
				'selector' => '{{SELECTOR}} .testimonial-layout-four .xtpb-testimonial-quote { line-height: {{data.quote_line_height}}; }'
			),

			//wrapper
			'wrap_background' => array(
				'type' => 'background',
				'title' => '',
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-content',
				'tab' => 'style',
				'section' => __( '评价容器', 'xintheme-pagebuilder' ),
			),
			'wrap_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#cccccc'
				),
				'tab' => 'style',
				'section' => __( '评价容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-content'
			),
			'wrap_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '评价容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-content { border-radius: {{data.wrap_radius}}; }'
			),

			'wrap_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '阴影', 'xintheme-pagebuilder' ),
				'std' => array(
					'shadowValue' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ),
					'shadowColor' => '#ffffff'
				),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-content',
				'tab' => 'style',
				'section' => __( '评价容器', 'xintheme-pagebuilder' ),
			),
			'wrap_hover_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '悬停阴影', 'xintheme-pagebuilder' ),
				'std' => array(
					'shadowValue' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ),
					'shadowColor' => '#ffffff'
				),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-content:hover',
				'tab' => 'style',
				'section' => __( '评价容器', 'xintheme-pagebuilder' ),
			),

			'wrap_padding'  => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-testimonial-addon-content { padding: {{data.wrap_padding}}; }',
				'section' => __( '评价容器', 'xintheme-pagebuilder' ),
			),

		);

		return $settings;
	}


	// Testimonial Render HTML
	public function render($data = null){
		$settings 					= $data['settings'];

		$image 						= isset($settings['image']) ? $settings['image'] : array();
		$name 						= isset($settings['name']) ? sanitize_text_field( $settings['name'] ) : '';
		$name_link 					= isset($settings['name_link']) ? $settings['name_link'] : array();
		$designation 				= isset($settings['designation']) ? sanitize_text_field( $settings['designation'] ) : '';
		$introtext 					= isset($settings['introtext']) ? sanitize_text_field( $settings['introtext'] ) : '';
		$testimonial_layout 		= isset($settings['testimonial_layout']) ? sanitize_text_field( $settings['testimonial_layout'] ) : '';

		$output = $img_url = '' ;

		if(! empty($image['url'])){
			$img_url = $image['url'];
		}

		$name_link_url = is_array($name_link) ? $name_link['link'] : $name_link;

		$target = ( isset($name_link['window']) && $name_link['window'] ) ? 'target=_blank' : "";
		$nofolow = ( isset($name_link['nofolow']) && $name_link['nofolow'] ) ? 'rel=nofolow' : "";

		$output  .= '<div class="xtpb-testimonial-addon-content testimonial-layout-' . esc_attr( $testimonial_layout ) . '">';

		if ( $testimonial_layout == "two" ) {
			$output  .= '<div class="xtpb-testimonial-content">';
			if ( $img_url ) {
				$output  .= '<div class="xtpb-testimonial-image">';
				$output  .= '<img class="xtpb-testimonial-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $name ) . '">';
				$output  .= '</div>';
			}
			if ( $introtext ) {
				$output  .= '<div class="xtpb-testimonial-introtext">' . wp_kses_post( $introtext ) . '</div>';
			}
			if ($name || $designation ) {
				$output  .= '<div class="xtpb-testimonial-title">';
				if ( $name_link_url ) {
					if ( $name ) { $output .= '<h4 class="xtpb-testimonial-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $name_link_url ) . '" ' . esc_attr( $target ) . '>' . esc_html( $name ) . '</a></h4>';}
				} else {
					if ( $name ) { $output .= '<h4 class="xtpb-testimonial-name">' . esc_html( $name ) . '</h4>';}
				}
				if ( $designation ) {
					$output  .= '<span class="xtpb-testimonial-designation">' . esc_html( $designation ) . '</span>';
				}
				$output  .= '</div>';//xtpb-testimonial-title
			}
			$output  .= '</div>';

		} elseif ( $testimonial_layout == "three" ) {
			$output  .= '<div class="xtpb-testimonial-content">';
			if ( $introtext ) {
				$output  .= '<div class="xtpb-testimonial-introtext">' . wp_kses_post( $introtext ) . '</div>';
			}
			if ( $img_url ) {
				$output  .= '<div class="xtpb-testimonial-image">';
				$output  .= '<img class="xtpb-testimonial-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $name ) . '">';
				$output  .= '</div>';
			}
			if ( $name || $designation ) {
				$output  .= '<div class="xtpb-testimonial-title">';
				if ( $name_link_url ) {
					if ( $name ) { $output .= '<h4 class="xtpb-testimonial-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $name_link_url ) . '" ' . esc_attr( $target ) . '>' . esc_html( $name ) . '</a></h4>';}
				} else {
					if ( $name ) { $output .= '<h4 class="xtpb-testimonial-name">' . esc_html( $name ) . '</h4>';}
				}
				if ( $designation ) {
					$output  .= '<span class="xtpb-testimonial-designation">' . esc_html( $designation ) . '</span>';
				}
				$output  .= '</div>';//xtpb-testimonial-title
			}
			$output  .= '</div>';


		} elseif ( $testimonial_layout == "four" ) {
			$output  .= '<div class="xtpb-testimonial-content">';
			$output  .= '<span class="xtpb-testimonial-quote xtpb-font-quote"></span>';
			if ( $introtext ) {
				$output  .= '<div class="xtpb-testimonial-introtext">' . wp_kses_post( $introtext ) . '</div>';
			}
			if ( $img_url ) {
				$output  .= '<div class="xtpb-testimonial-image">';
				$output  .= '<img class="xtpb-testimonial-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $name ) . '">';
				$output  .= '</div>';
			}
			if ( $name || $designation ) {
				$output  .= '<div class="xtpb-testimonial-title">';
				if ( $name_link_url ) {
					if ( $name ) { $output .= '<h4 class="xtpb-testimonial-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $name_link_url ) . '" ' . esc_attr( $target ) . '>' . esc_html( $name ) . '</a></h4>';}
				} else {
					if ( $name ) { $output .= '<h4 class="xtpb-testimonial-name">' . esc_html( $name ) . '</h4>';}
				}
				if ( $designation ) {
					$output  .= '<span class="xtpb-testimonial-designation">' . esc_html( $designation ) . '</span>';
				}
				$output  .= '</div>';//xtpb-testimonial-title
			}
			$output  .= '</div>';

		} else {
			$output  .= '<div class="xtpb-testimonial-content">';
			if ( $introtext ) {
				$output  .= '<div class="xtpb-testimonial-introtext">' . wp_kses_post( $introtext ) . '</div>';
			}

			if ( $name || $designation ) {
				$output  .= '<div class="xtpb-testimonial-information">';
				if ( $img_url ) {
					$output  .= '<div class="xtpb-testimonial-image">';
					$output  .= '<img class="xtpb-testimonial-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $name ) . '">';
					$output  .= '</div>';
				}
				$output  .= '<div class="xtpb-testimonial-title">';
				if ( $name_link_url ) {
					if ( $name ) { $output .= '<h4 class="xtpb-testimonial-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $name_link_url ) . '" ' . esc_attr( $target ) . '>' . esc_html( $name ) . '</a></h4>';}
				} else {
					if ( $name ) { $output .= '<h4 class="xtpb-testimonial-name">' . esc_html( $name ) . '</h4>';}
				}
				if ( $designation ) {
					$output  .= '<span class="xtpb-testimonial-designation">' . esc_html( $designation ) . '</span>';
				}
				$output  .= '</div>';//xtpb-testimonial-title
				$output  .= '</div>';//xtpb-testimonial-information
			}
			$output  .= '</div>';

		}

		$output  .= '</div>';//xtpb-testimonial-addon-content

		return $output;
	}

	// Testimonial Template
	public function getTemplate(){
		$output = '

		<div class="xtpb-testimonial-addon-content testimonial-layout-{{data.testimonial_layout}}">

				<# if( data.testimonial_layout == "two" ){ #>
					<div class="xtpb-testimonial-content">
						<# if( data.image.url ) { #>
							<div class="xtpb-testimonial-image">
								<img class="xtpb-testimonial-addon-img" src="{{data.image.url}}">
							</div>
						<# } #>

						<# if( data.introtext ) { #>
							<div class="xtpb-testimonial-introtext">{{{data.introtext}}}</div>
						<# } #>

						<# if( data.name || data.designation ) { #>
							<div class="xtpb-testimonial-title">
								<# if( !__.isEmpty(data.name_link.link) ) { #>
									<h4 class="xtpb-testimonial-name"><a {{ data.name_link.link ? "href="+data.name_link.link : "" }} {{ data.name_link.window ? "target=_blank" : "" }} {{ data.name_link.nofolow ? "rel=nofolow" : "" }} >{{{ data.name }}}</a></h4>
								<# } else { #>
									<h4 class="xtpb-testimonial-name">{{{ data.name }}}</h4>
								<# } #>
								<# if( data.designation ) { #>
									<span class="xtpb-testimonial-designation">{{{data.designation}}}</span>
								<# } #>
							</div>
						<# } #>
					</div>

				<# } else if( data.testimonial_layout == "three" ){ #>
					<div class="xtpb-testimonial-content">
						<# if( data.introtext ) { #>
							<div class="xtpb-testimonial-introtext">{{{data.introtext}}}</div>
						<# } #>

						<# if( data.image.url ) { #>
							<div class="xtpb-testimonial-image">
								<img class="xtpb-testimonial-addon-img" src="{{data.image.url}}">
							</div>
						<# } #>

						<# if( data.name || data.designation ) { #>
							<div class="xtpb-testimonial-title">
								<# if( !__.isEmpty(data.name_link.link) ) { #>
									<h4 class="xtpb-testimonial-name"><a {{ data.name_link.link ? "href="+data.name_link.link : "" }} {{ data.name_link.window ? "target=_blank" : "" }} {{ data.name_link.nofolow ? "rel=nofolow" : "" }} >{{{ data.name }}}</a></h4>
								<# } else { #>
									<h4 class="xtpb-testimonial-name">{{{ data.name }}}</h4>
								<# } #>
								<# if( data.designation ) { #>
									<span class="xtpb-testimonial-designation">{{{data.designation}}}</span>
								<# } #>
							</div>
						<# } #>	
					</div>	

					<# } else if( data.testimonial_layout == "four" ){ #>
						<div class="xtpb-testimonial-content">
							<span class="xtpb-testimonial-quote xtpb-font-quote"></span>
							<# if( data.introtext ) { #>
								<div class="xtpb-testimonial-introtext">{{{data.introtext}}}</div>
							<# } #>
	
							<# if( data.image.url ) { #>
								<div class="xtpb-testimonial-image">
									<img class="xtpb-testimonial-addon-img" src="{{data.image.url}}">
								</div>
							<# } #>
	
							<# if( data.name || data.designation ) { #>
								<div class="xtpb-testimonial-title">
									<# if( !__.isEmpty(data.name_link.link) ) { #>
										<h4 class="xtpb-testimonial-name"><a {{ data.name_link.link ? "href="+data.name_link.link : "" }} {{ data.name_link.window ? "target=_blank" : "" }} {{ data.name_link.nofolow ? "rel=nofolow" : "" }} >{{{ data.name }}}</a></h4>
									<# } else { #>
										<h4 class="xtpb-testimonial-name">{{{ data.name }}}</h4>
									<# } #>
									<# if( data.designation ) { #>
										<span class="xtpb-testimonial-designation">{{{data.designation}}}</span>
									<# } #>
								</div>
							<# } #>	
						</div>	
					
				<# } else {  #>	
					<div class="xtpb-testimonial-content">
						<# if( data.introtext ) { #>
							<div class="xtpb-testimonial-introtext">{{{data.introtext}}}</div>
						<# } #>
						<# if( data.name || data.designation ) { #>
							<div class="xtpb-testimonial-information">
								<# if( data.image.url ) { #>
									<div class="xtpb-testimonial-image">
										<img class="xtpb-testimonial-addon-img" src="{{data.image.url}}">
									</div>
								<# } #>
								<div class="xtpb-testimonial-title">
									<# if( !__.isEmpty(data.name_link.link) ) { #>
										<h4 class="xtpb-testimonial-name"><a {{ data.name_link.link ? "href="+data.name_link.link : "" }} {{ data.name_link.window ? "target=_blank" : "" }} {{ data.name_link.nofolow ? "rel=nofolow" : "" }} >{{{ data.name }}}</a></h4>
									<# } else { #>
										<h4 class="xtpb-testimonial-name">{{{ data.name }}}</h4>
									<# } #>
									<# if( data.designation ) { #>
										<span class="xtpb-testimonial-designation">{{{data.designation}}}</span>
									<# } #>
								</div>
							</div>
						<# } #>
					</div>

				<# } #>

			</div>
	';
		return $output;
	}

}
