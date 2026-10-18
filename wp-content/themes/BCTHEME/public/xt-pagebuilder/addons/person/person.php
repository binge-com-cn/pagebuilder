<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Person{

	public function get_name(){
		return 'xtpb_person';
	}
	public function get_title(){
		return __( '人物', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-person';
	}

	// Person Settings Fields
	public function get_settings() {

		$settings = array(
			'person_layout' => array(
				'type' => 'radioimage',
				'title' => __( '布局', 'xintheme-pagebuilder' ),
				'values'=> array(
					'one' =>  XTPB_DIR_URL.'addons/person/img/person-img1.png',
					'two' =>  XTPB_DIR_URL.'addons/person/img/person-img2.png',
					'three' =>  XTPB_DIR_URL.'addons/person/img/person-img3.png',
					'four' =>  XTPB_DIR_URL.'addons/person/img/person-img4.png',
					'five' =>  XTPB_DIR_URL.'addons/person/img/person-img5.png',
				),
				'std' => 'one',
			),
			'person_image' => array(
				'type' => 'media',
				'title' => __('图片','xintheme-pagebuilder'),
				'std'=> array( 'url' =>  XTPB_DIR_URL.'assets/img/placeholder/xtpb-large.jpg' ),
			),
			'name' => array(
				'type' => 'text',
				'title' => __('名称','xintheme-pagebuilder'),
				'std' => __( '老王', 'xintheme-pagebuilder' ),
			),
			'name_link' => array(
				'type' => 'link',
				'std' =>   array('link'=>'','window'=>true,'nofolow'=>false),
				'title' => __('个人资料链接','xintheme-pagebuilder'),
			),
			'designation' => array(
				'type' => 'text',
				'title' => __('职位','xintheme-pagebuilder'),
				'std' => __( '总经理', 'xintheme-pagebuilder' ),
			),
			'email' => array(
				'type' => 'text',
				'title' => __('邮箱','xintheme-pagebuilder'),
				'placeholder' => 'example@example.com',
			),
			'introtext' => array(
				'type' => 'textarea',
				'title' => __('介绍文本','xintheme-pagebuilder'),
				'std' => __( '专注于互联网产品与网站建设，擅长将设计、技术与运营结合，帮助项目高效落地。', 'xintheme-pagebuilder' ),
			),
			'show_social' => array(
				'type' => 'switch',
				'title' => __('显示社交媒体','xintheme-pagebuilder'),
				'std' => '1'
			),

			// Repeatable Items
			'social_item' => array(
				'title' => __('社交媒体链接','xintheme-pagebuilder'),
				'depends' => array(array('show_social', '!=', '')),
				'type' => 'repeatable',
				'label' => 'icon_list',
				'std' => array(
					array( 
						'icon_list' => 'ri-facebook-fill',
						'socialurl' => '#'
					),
					array(
						'icon_list' => 'ri-linkedin-fill',
						'socialurl' => '#'
					),
					array(
						'icon_list' => 'ri-flickr-fill',
						'socialurl' => '#'
					)
				),
				'attr' => array(
					'icon_list' => array(
						'type' => 'iconsocial',
						'title' => __('图标列表','xintheme-pagebuilder'),
						'std' => 'ri-facebook-fill'
					),
					'socialurl' => array(
						'type' => 'text',
						'title' => __('社交链接','xintheme-pagebuilder'),
						'std' => '#'
					),
					'icon_color' => array(
						'type' => 'color',
						'title' => __('颜色','xintheme-pagebuilder'),
						'selector' =>'{{SELECTOR}} .xtpb-person-social-list a { color: {{data.icon_color}}; }',
					),
					'icon_hcolor' => array(
						'type' => 'color',
						'title' => __('悬停颜色','xintheme-pagebuilder'),
						'selector' => '{{SELECTOR}}  .xtpb-person-social-list a:hover { color: {{data.icon_hcolor}}; }',
					),
					'icon_bg' => array(
						'type' => 'color2',
						'title' => __('背景','xintheme-pagebuilder'),
						'clip' => false,
						'std' => array(
							'colorType' => 'color',
							'colorColor' => '#3D88E4',
						),
						'selector' => '{{SELECTOR}} .xtpb-person-social-list a'
					),
					'icon_hover_bg' => array(
						'type' => 'color2',
						'title' => __('悬停背景','xintheme-pagebuilder'),
						'clip' => false,
						'std' => array(
							'colorType' => 'color',
							'colorColor' => '#3D88E4',
						),
						'selector' => '{{SELECTOR}} .xtpb-person-social-list a:hover'
					),
				),
			),
			'persion_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-person-addon-content{ text-align: {{data.persion_align}}; }'
			),

			//style
			'img_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
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
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-addon-img, {{SELECTOR}} .person-layout-one .xtpb-person-addon-img, {{SELECTOR}} .person-layout-four .xtpb-person-addon-img, {{SELECTOR}} .person-layout-three .xtpb-person-addon-img { width: {{data.img_width}}; }',
			),
			'img_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
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
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-addon-img, {{SELECTOR}} .person-layout-one .xtpb-person-addon-img, {{SELECTOR}} .person-layout-four .xtpb-person-addon-img, {{SELECTOR}} .person-layout-three .xtpb-person-addon-img { height: {{data.img_height}}; }',
			),
			'img_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-addon-img, {{SELECTOR}} .person-layout-one .xtpb-person-addon-img, {{SELECTOR}} .person-layout-four .xtpb-person-addon-img, {{SELECTOR}} .person-layout-three .xtpb-person-addon-img { border-radius: {{data.img_radius}}; }',
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
				'selector' => '{{SELECTOR}} .xtpb-person-addon-img'
			),
			'image_margin' => array(
				'type' => 'dimension',
				'title' => __( '图片外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-addon-img { margin: {{data.image_margin}}; }',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
			),

			//name
			'name_color' => array(
				'type' => 'color2',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => true,
				'std' => array( 'colorType' => 'color', 'clip' => true, 'colorColor' => '' ),
				'section' => __( '名称', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-information .xtpb-person-name, {{SELECTOR}} .xtpb-person-name a, {{SELECTOR}} .xtpb-person-five-overlay .xtpb-person-name'
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
				'selector' => '{{SELECTOR}} .xtpb-person-name',
				'section' => __( '名称', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'name_margin' => array(
				'type' => 'dimension',
				'title' => __( '名称外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-name { margin: {{data.name_margin}}; }',
				'section' => __( '名称', 'xintheme-pagebuilder' ),
			),

			//desg
			'desgn_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '职位', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-information .xtpb-person-designation, {{SELECTOR}} .xtpb-person-five-overlay .xtpb-person-designation { color: {{data.desgn_color}}; }'
			),
			'desgn_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-person-information .xtpb-person-designation',
				'section' => __( '职位', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'desgn_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-information .xtpb-person-designation { margin: {{data.desgn_margin}}; }',
				'section' => __( '职位', 'xintheme-pagebuilder' ),
			),

			//email
			'email_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '邮箱', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-email, {{SELECTOR}} .xtpb-person-five-overlay .xtpb-person-email { color: {{data.email_color}}; }'
			),
			'email_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-person-information .xtpb-person-email',
				'section' => __( '邮箱', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'email_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-information .xtpb-person-email { margin: {{data.email_margin}}; }',
				'section' => __( '邮箱', 'xintheme-pagebuilder' ),
			),


			//Intro
			'intro_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '介绍文本', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-introtext, {{SELECTOR}} .xtpb-person-five-overlay .xtpb-person-introtext { color: {{data.intro_color}}; }'
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
				'selector' => '{{SELECTOR}} .xtpb-person-introtext',
				'section' => __( '介绍文本', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),

			//social
			'icon_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' =>'{{SELECTOR}} .xtpb-person-social li a { color: {{data.icon_color}}; }',
			),
			'icon_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' =>'{{SELECTOR}}  .xtpb-person-social li a i:hover { color: {{data.icon_hcolor}}; }',
			),
			'icon_bg' => array(
				'type' => 'color',
				'title' => __('背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a { background: {{data.icon_bg}}; }'
			),
			'icon_hover_bg' => array(
				'type' => 'color',
				'title' => __('悬停背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a:hover{ background: {{data.icon_hover_bg}}; }'
			),
			'icon_size' => array(
				'type' => 'slider',
				'title' => __('尺寸','xintheme-pagebuilder'),
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
						'md' => '18px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a { font-size: {{data.icon_size}}; }'
			),
			'icon_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
						'md' => '',
						'sm' => '',
						'xs' => '',
					),
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
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a { width: {{data.icon_width}}; }'
			),
			'icon_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'std' => array(
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => array(
					'{{SELECTOR}} .xtpb-person-social li a { height: {{data.icon_height}}; }',
					'{{SELECTOR}} .xtpb-person-social li a { line-height: {{data.icon_height}}; }',
				)
			),
			'icon_line_height' => array(
				'type' => 'slider',
				'title' => __('行高','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'std' => array(
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a { line-height: {{data.icon_line_height}}; }'
			),
			'icon_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a { border-radius: {{data.icon_radius}}; }'
			),
			'icon_border' => array(
				'type' => 'border',
				'title' => __( '图标边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a'
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
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a:hover'
			),
			'social_margin' => array(
				'type' => 'dimension',
				'title' => __( '社交媒体外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-social-list { margin: {{data.social_margin}}; }',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
			),
			'content_background' => array(
				'type' => 'color2',
				'title' => __('背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '内容容器', 'xintheme-pagebuilder' ),
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => 'rgba(4, 190, 254, 1)',
					'clip' => false
				),
				'depends' => array(array('person_layout', '=', array('two','five'))),
				'selector' => '{{SELECTOR}} .person-layout-two .xtpb-person-information-wrap,{{SELECTOR}} .xtpb-person-five-overlay:after'
			),
			'content_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'tab' => 'style',
				'section' => __( '内容容器', 'xintheme-pagebuilder' ),
				'depends' => array(array('person_layout', '=', array('five'))),
				'selector' => '{{SELECTOR}} .xtpb-person-five-overlay, {{SELECTOR}} .xtpb-person-five-overlay:after'
			),
			'content_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '内容容器', 'xintheme-pagebuilder' ),
				'depends' => array(array('person_layout', '=', array('five'))),
				'selector' => '{{SELECTOR}} .xtpb-person-five-wrap .xtpb-person-image img, {{SELECTOR}} .xtpb-person-five-overlay,{{SELECTOR}} .xtpb-person-five-overlay:after { border-radius: {{data.content_radius}}; }'
			),
			'content_wrap' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .person-layout-one, {{SELECTOR}} .person-layout-two .xtpb-person-information-wrap,{{SELECTOR}} .xtpb-person-five-overlay { padding: {{data.content_wrap}}; }',
				'section' => __( '内容容器', 'xintheme-pagebuilder' ),
			),
		);
		return $settings;
	}


	// Persion Render HTML
	public function render($data = null){
		$settings 		= $data['settings'];
		$person_image 	= isset($settings['person_image']) ? $settings['person_image'] : array();
		$person_layout 	= isset($settings['person_layout']) ? sanitize_text_field( $settings['person_layout'] ) : '';
		$name 			= isset($settings['name']) ? sanitize_text_field( $settings['name'] ) : '';
		$name_link 		= isset($settings['name_link']) ? $settings['name_link'] : array();
		$designation 	= isset($settings['designation']) ? sanitize_text_field( $settings['designation'] ) : '';
		$email 			= isset($settings['email']) ? sanitize_email( $settings['email'] ) : '';
		$introtext 		= isset($settings['introtext']) ? sanitize_text_field( $settings['introtext'] ) : '';
		$show_social 	= isset($settings['show_social']) ? sanitize_text_field( $settings['show_social'] ) : '';
		$social_item 	= isset($settings['social_item']) ? $settings['social_item'] : array();

		$output = $img_url = '' ;

		if (! empty( $person_image['url'] ) ) {
			$img_url = $person_image['url'];
		}
		$target = $name_link['window'] ? 'target=_blank' : "";
		$nofolow = $name_link['nofolow'] ? 'rel=nofolow' : "";

		$output  .= '<div class="xtpb-person-addon-content person-layout-' . esc_attr( $person_layout ) . '">';

				if ( $person_layout == "two" ) {
					if ( $img_url ) {
						$output  .= '<div class="xtpb-person-image">';
						$output  .= '<img class="xtpb-person-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $name ) . '">';
						$output  .= '</div>';//xtpb-person-image
					}
					$output  .= '<div class="xtpb-person-information-wrap">';
						$output  .= '<div class="xtpb-person-information">';
							if ( $name_link['link'] ) {
								$output .= '<span class="xtpb-person-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $name_link['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $name ) . '</a></span>';
							} else {
								$output .= '<span class="xtpb-person-name">' . esc_html( $name ) . '</span>';
							}
							if ( $designation ) { $output  .= '<span class="xtpb-person-designation">' . esc_html( $designation ) . '</span>'; }
							if ( $email ) { $output  .= '<span class="xtpb-person-email">' . esc_html( $email ) . '</span>'; }
							if ( $introtext ) { $output  .= '<div class="xtpb-person-introtext">' . wp_kses_post( $introtext ) . '</div>'; }
						$output  .= '</div>';//xtpb-person-information
						if ( $show_social == 1 ) {
							$output  .= '<ul class="xtpb-person-social">';
							foreach ( $social_item as $key => $value ) {
								$output  .= '<li class="repeater-' . esc_attr( $key ) . '"><span class="xtpb-person-social-list"><a href="' . esc_url( $value['socialurl'] ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list'] ) . '"></i></a></span></li>';
							}
							$output  .= '</ul>';
						}
					$output  .= '</div>';

				} elseif ( $person_layout == "three" ) {
					$output  .= '<div class="xtpb-person-media">';
						if ( $img_url ) {
							$output  .= '<div class="xtpb-person-image">';
							$output  .= '<img class="xtpb-person-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $name ) . '">';
							$output  .= '</div>';//xtpb-person-image
						}
						$output  .= '<div class="xtpb-person-media-body">';
							$output  .= '<div class="xtpb-person-information">';
								if ( $name_link['link'] ) {
									$output .= '<span class="xtpb-person-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $name_link['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $name ) . '</a></span>';
								} else {
									$output .= '<span class="xtpb-person-name">' . esc_html( $name ) . '</span>';
								}
								if ( $designation ) { $output  .= '<span class="xtpb-person-designation">' . esc_html( $designation ) . '</span>'; }
								if ( $email ) { $output  .= '<span class="xtpb-person-email">' . esc_html( $email ) . '</span>'; }
								if ( $introtext ) { $output  .= '<div class="xtpb-person-introtext">' . wp_kses_post( $introtext ) . '</div>'; }
							$output  .= '</div>';//xtpb-person-information
							if ( $show_social == 1 ) {
								$output  .= '<ul class="xtpb-person-social">';
								foreach ( $social_item as $key => $value ) {
									$output  .= '<li class="repeater-' . esc_attr( $key ) . '"><span class="xtpb-person-social-list"><a href="' . esc_url( $value['socialurl'] ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list'] ) . '"></i></a></span></li>';
								}
								$output  .= '</ul>';
							}
						$output  .= '</div>';
					$output  .= '</div>';
					
				} elseif ( $person_layout == "four" ) {
					$output  .= '<div class="xtpb-person-media">';
						if ( $img_url ) {
							$output  .= '<div class="xtpb-person-image">';
							$output  .= '<img class="xtpb-person-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $name ) . '">';
							$output  .= '</div>';//xtpb-person-image
						}
						$output  .= '<div class="xtpb-person-media-body">';
							$output  .= '<div class="xtpb-person-information">';
								if ( $name_link['link'] ) {
									$output .= '<span class="xtpb-person-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $name_link['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $name ) . '</a></span>';
								} else {
									$output .= '<span class="xtpb-person-name">' . esc_html( $name ) . '</span>';
								}
								if ( $designation ) { $output  .= '<span class="xtpb-person-designation">' . esc_html( $designation ) . '</span>'; }
								if ( $email ) { $output  .= '<span class="xtpb-person-email">' . esc_html( $email ) . '</span>'; }
								if ( $introtext ) { $output  .= '<div class="xtpb-person-introtext">' . wp_kses_post( $introtext ) . '</div>'; }
							$output  .= '</div>';//xtpb-person-information
							if ( $show_social == 1 ) {
								$output  .= '<ul class="xtpb-person-social">';
								foreach ( $social_item as $key => $value ) {
									$output  .= '<li class="repeater-' . esc_attr( $key ) . '"><span class="xtpb-person-social-list"><a href="' . esc_url( $value['socialurl'] ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list'] ) . '"></i></a></span></li>';
								}
								$output  .= '</ul>';
							}
						$output  .= '</div>';
					$output  .= '</div>';
				} elseif ( $person_layout == "five" ) {
					$output  .= '<div class="xtpb-person-five-wrap">';
						if ( $img_url ) {
							$output  .= '<div class="xtpb-person-image">';
							$output  .= '<img class="xtpb-person-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $name ) . '">';
							$output  .= '</div>';//xtpb-person-image
						}
						$output  .= '<div class="xtpb-person-information-wrap xtpb-person-five-overlay">';
							if ( $show_social == 1 ) {
								$output  .= '<ul class="xtpb-person-social">';
								foreach ( $social_item as $key => $value ) {
									$output  .= '<li class="repeater-' . esc_attr( $key ) . '"><span class="xtpb-person-social-list"><a href="' . esc_url( $value['socialurl'] ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list'] ) . '"></i></a></span></li>';
								}
								$output  .= '</ul>';
							}
							$output  .= '<div class="xtpb-person-information">';
								if ( $name_link['link'] ) {
									$output .= '<span class="xtpb-person-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $name_link['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $name ) . '</a></span>';
								} else {
									$output .= '<span class="xtpb-person-name">' . esc_html( $name ) . '</span>';
								}
								if ( $designation ) { $output  .= '<span class="xtpb-person-designation">' . esc_html( $designation ) . '</span>'; }
								if ( $email ) { $output  .= '<span class="xtpb-person-email">' . esc_html( $email ) . '</span>'; }
								if ( $introtext ) { $output  .= '<div class="xtpb-person-introtext">' . wp_kses_post( $introtext ) . '</div>'; }
							$output  .= '</div>';//xtpb-person-information
						$output  .= '</div>';
					$output  .= '</div>';
					
				} else {
					if ( $img_url ) {
						$output  .= '<div class="xtpb-person-image">';
						$output  .= '<img class="xtpb-person-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $name ) . '">';
						$output  .= '</div>';//xtpb-person-image
					}
					$output  .= '<div class="xtpb-person-information">';
						if ( $name_link['link'] ) {
							$output .= '<span class="xtpb-person-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $name_link['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $name ) . '</a></span>';
						} else {
							$output .= '<span class="xtpb-person-name">' . esc_html( $name ) . '</span>';
						}
						if ( $designation ) { $output  .= '<span class="xtpb-person-designation">' . esc_html( $designation ) . '</span>'; }
						if ( $email ) { $output  .= '<span class="xtpb-person-email">' . esc_html( $email ) . '</span>'; }
						if ( $introtext ) { $output  .= '<div class="xtpb-person-introtext">' . wp_kses_post( $introtext ) . '</div>'; }
					$output  .= '</div>';//xtpb-person-information
					if ( $show_social == 1 ) {
						$output  .= '<ul class="xtpb-person-social">';
						foreach ( $social_item as $key => $value ) {
							$output  .= '<li class="repeater-' . esc_attr( $key ) . '"><span class="xtpb-person-social-list"><a href="' . esc_url( $value['socialurl'] ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list'] ) . '"></i></a></span></li>';
						}
						$output  .= '</ul>';
					}
				}
		$output  .= '</div>';//xtpb-person-addon-content

		return $output;
	}

	// Person Template
	public function getTemplate(){
		$output = '
		<div class="xtpb-person-addon-content person-layout-{{data.person_layout}}">

			<# if( data.person_layout == "two" ){ #>

				<# if( data.person_image.url ) { #>
					<div class="xtpb-person-image">
						<img class="xtpb-person-addon-img" src="{{data.person_image.url}}">
					</div>
				<# } #>

				<div class="xtpb-person-information-wrap">

					<div class="xtpb-person-information">
						<# if( !__.isEmpty(data.name_link.link) ) { #>
							<span class="xtpb-person-name"><a {{ data.name_link.link ? "href="+data.name_link.link : "" }} {{ data.name_link.window ? "target=_blank" : "" }} {{ data.name_link.nofolow ? "rel=nofolow" : "" }} >{{{ data.name }}}</a></span>
						<# } else { #>
							<span class="xtpb-person-name">{{{ data.name }}}</span>
						<# } #>
						<# if( data.designation ) { #>
							<span class="xtpb-person-designation">{{data.designation}}</span>
						<# } #>
						<# if( data.email ) { #>
							<span class="xtpb-person-email">{{data.email}}</span>
						<# } #>
						<# if( data.introtext ) { #>
							<span class="xtpb-person-introtext">{{data.introtext}}</span>
						<# } #>
					</div>

					<# if( data.show_social == 1 ) { #>
						<ul class="xtpb-person-social">
							<#  _.forEach(data.social_item, function(value, key) { #>
								<li class="repeater-{{key}}">
									<span class="xtpb-person-social-list"><a href="{{value.socialurl}}" target="_blank"><i class="{{value.icon_list}}"></i></a></span>
								</li>
							<# }); #>
						</ul>
					<# } #>
				</div>

			<# } else if( data.person_layout == "three" ){ #>

				<div class="xtpb-person-media">
					<# if( data.person_image.url ) { #>
						<div class="xtpb-person-image">
							<img class="xtpb-person-addon-img" src="{{data.person_image.url}}">
						</div>
					<# } #>
					<div class="xtpb-person-media-body">
						<div class="xtpb-person-information">
							<# if( !__.isEmpty(data.name_link.link) ) { #>
								<span class="xtpb-person-name"><a {{ data.name_link.link ? "href="+data.name_link.link : "" }} {{ data.name_link.window ? "target=_blank" : "" }} {{ data.name_link.nofolow ? "rel=nofolow" : "" }} >{{{ data.name }}}</a></span>
							<# } else { #>
								<span class="xtpb-person-name">{{{ data.name }}}</span>
							<# } #>
							<# if( data.designation ) { #>
								<span class="xtpb-person-designation">{{data.designation}}</span>
							<# } #>
							<# if( data.email ) { #>
								<span class="xtpb-person-email">{{data.email}}</span>
							<# } #>
							<# if( data.introtext ) { #>
								<span class="xtpb-person-introtext">{{data.introtext}}</span>
							<# } #>
						</div>

						<# if( data.show_social == 1 ) { #>
							<ul class="xtpb-person-social">
								<#  _.forEach(data.social_item, function(value, key) { #>
									<li class="repeater-{{key}}">
										<span class="xtpb-person-social-list"><a href="{{value.socialurl}}" target="_blank"><i class="{{value.icon_list}}"></i></a></span>
									</li>
								<# }); #>
							</ul>
						<# } #>
					</div>
				</div>

			<# } else if( data.person_layout == "four" ){ #>

				<div class="xtpb-person-media">
					<# if( data.person_image.url ) { #>
						<div class="xtpb-person-image">
							<img class="xtpb-person-addon-img" src="{{data.person_image.url}}">
						</div>
					<# } #>
					<div class="xtpb-person-media-body">

						<div class="xtpb-person-information">
							<# if( !__.isEmpty(data.name_link.link) ) { #>
								<span class="xtpb-person-name"><a {{ data.name_link.link ? "href="+data.name_link.link : "" }} {{ data.name_link.window ? "target=_blank" : "" }} {{ data.name_link.nofolow ? "rel=nofolow" : "" }} >{{{ data.name }}}</a></span>
							<# } else { #>
								<span class="xtpb-person-name">{{{ data.name }}}</span>
							<# } #>
							<# if( data.designation ) { #>
								<span class="xtpb-person-designation">{{data.designation}}</span>
							<# } #>
							<# if( data.email ) { #>
								<span class="xtpb-person-email">{{data.email}}</span>
							<# } #>
							<# if( data.introtext ) { #>
								<span class="xtpb-person-introtext">{{data.introtext}}</span>
							<# } #>
						</div>

						<# if( data.show_social == 1 ) { #>
							<ul class="xtpb-person-social">
								<#  _.forEach(data.social_item, function(value, key) { #>
									<li class="repeater-{{key}}">
										<span class="xtpb-person-social-list"><a href="{{value.socialurl}}" target="_blank"><i class="{{value.icon_list}}"></i></a></span>
									</li>
								<# }); #>
							</ul>
						<# } #>
					</div>
				</div>

				<# } else if( data.person_layout == "five" ){ #>
					<div class="xtpb-person-five-wrap">
						<# if( data.person_image.url ) { #>
							<div class="xtpb-person-image">
								<img class="xtpb-person-addon-img" src="{{data.person_image.url}}">
							</div>
						<# } #>
						<div class="xtpb-person-information-wrap xtpb-person-five-overlay">
							<# if( data.show_social == 1 ) { #>
								<ul class="xtpb-person-social">
									<#  _.forEach(data.social_item, function(value, key) { #>
										<li class="repeater-{{key}}">
											<span class="xtpb-person-social-list"><a href="{{value.socialurl}}" target="_blank"><i class="{{value.icon_list}}"></i></a></span>
										</li>
									<# }); #>
								</ul>
							<# } #>
							<div class="xtpb-person-information">
								<# if( !__.isEmpty(data.name_link.link) ) { #>
									<span class="xtpb-person-name"><a {{ data.name_link.link ? "href="+data.name_link.link : "" }} {{ data.name_link.window ? "target=_blank" : "" }} {{ data.name_link.nofolow ? "rel=nofolow" : "" }} >{{{ data.name }}}</a></span>
								<# } else { #>
									<span class="xtpb-person-name">{{{ data.name }}}</span>
								<# } #>
								<# if( data.designation ) { #>
									<span class="xtpb-person-designation">{{data.designation}}</span>
								<# } #>
								<# if( data.email ) { #>
									<span class="xtpb-person-email">{{data.email}}</span>
								<# } #>
								<# if( data.introtext ) { #>
									<span class="xtpb-person-introtext">{{data.introtext}}</span>
								<# } #>
							</div>
						</div>
					</div>

			<# } else {  #>	

				<# if( data.person_image.url ) { #>
					<div class="xtpb-person-image">
						<img class="xtpb-person-addon-img" src="{{data.person_image.url}}">
					</div>
				<# } #>
				<div class="xtpb-person-information">
					<# if( !__.isEmpty(data.name_link.link) ) { #>
						<span class="xtpb-person-name"><a {{ data.name_link.link ? "href="+data.name_link.link : "" }} {{ data.name_link.window ? "target=_blank" : "" }} {{ data.name_link.nofolow ? "rel=nofolow" : "" }} >{{{ data.name }}}</a></span>
					<# } else { #>
						<span class="xtpb-person-name">{{{ data.name }}}</span>
					<# } #>
					<# if( data.designation ) { #>
						<span class="xtpb-person-designation">{{data.designation}}</span>
					<# } #>
					<# if( data.email ) { #>
						<span class="xtpb-person-email">{{data.email}}</span>
					<# } #>
					<# if( data.introtext ) { #>
						<span class="xtpb-person-introtext">{{data.introtext}}</span>
					<# } #>
				</div>

				<# if( data.show_social == 1 ) { #>
					<ul class="xtpb-person-social">
						<#  _.forEach(data.social_item, function(value, key) { #>
							<li class="repeater-{{key}}">
								<span class="xtpb-person-social-list"><a href="{{value.socialurl}}" target="_blank"><i class="{{value.icon_list}}"></i></a></span>
							</li>
						<# }); #>
					</ul>
				<# } #>
			<# } #>

			</div>
		';
		return $output;
	}

}
