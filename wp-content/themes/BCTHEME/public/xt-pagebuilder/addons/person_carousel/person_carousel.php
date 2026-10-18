<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Person_Carousel{

	public function get_name(){
		return 'xtpb_person_carousel';
	}
	public function get_title(){
		return __( '人物轮播', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-full-slider';
	}

	public function get_enqueue_script(){
		return array('xtpb-slick-slider');
	}

	public function get_enqueue_style(){
		return array('xtpb-slick-slider-css', 'xtpb-slick-slider-css-theme');
	}

	// Person Settings Fields
	public function get_settings() {

		$settings = array(
			'person_layout' => array(
				'type' => 'radioimage',
				'title' => __( '布局', 'xintheme-pagebuilder' ),
				'values'=> array(
					'layoutone' =>  XTPB_DIR_URL.'addons/person_carousel/img/person-img1.png',
					'layouttwo' =>  XTPB_DIR_URL.'addons/person_carousel/img/person-img2.png',
					'layoutthree' =>  XTPB_DIR_URL.'addons/person_carousel/img/person-img3.png',
					'layoutfour' =>  XTPB_DIR_URL.'addons/person_carousel/img/person-img4.png',
				),
				'std' => 'layoutone',
			),

			'person_list' => array(
				'title' => __('人物列表','xintheme-pagebuilder'),
				'type' => 'repeatable',
				'label' => 'name',
				'std' => array(
					array(
						'image' => array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-large.jpg' ),
						'name' => __( '张明远', 'xintheme-pagebuilder' ),
						'name_link' => array('link'=>'','window'=>true,'nofolow'=>false),
						'职位' => __( '开发工程师', 'xintheme-pagebuilder' ),
						'introtext' => __( '专注于网站开发与系统优化，熟悉前端交互、后端功能扩展及项目落地。', 'xintheme-pagebuilder' ),
						'show_social' => '1',
						'icon_list1' => 'ri-facebook-fill',
						'socialurl1' => '#',
						'icon_list2' => 'ri-twitter-fill',
						'socialurl2' => '#',
						'icon_list3' => 'ri-flickr-fill',
						'socialurl3' => '#'
					),
					array(
						'image' => array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-medium.jpg' ),
						'name' => __( '李思涵', 'xintheme-pagebuilder' ),
						'name_link' => array('link'=>'','window'=>true,'nofolow'=>false),
						'职位' => __( '视觉设计师', 'xintheme-pagebuilder' ),
						'introtext' => __( '擅长品牌视觉、界面设计与页面排版，注重用户体验与细节表达。', 'xintheme-pagebuilder' ),
						'show_social' => '1',
						'icon_list1' => 'ri-facebook-fill',
						'socialurl1' => '#',
						'icon_list2' => 'ri-twitter-fill',
						'socialurl2' => '#',
						'icon_list3' => 'ri-flickr-fill',
						'socialurl3' => '#'
					),
					array( 
						'image' => array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-medium.jpg' ),
						'name' => __( '陈亦舟', 'xintheme-pagebuilder' ),
						'name_link' => array('link'=>'','window'=>true,'nofolow'=>false),
						'职位' => __( '创始人', 'xintheme-pagebuilder' ),
						'introtext' => __( '拥有多年互联网项目经验，专注于产品规划、团队管理和数字化服务。', 'xintheme-pagebuilder' ),
						'show_social' => '1',
						'icon_list1' => 'ri-facebook-fill',
						'socialurl1' => '#',
						'icon_list2' => 'ri-twitter-fill',
						'socialurl2' => '#',
						'icon_list3' => 'ri-flickr-fill',
						'socialurl3' => '#'
					),

				),
				'attr' => array(
					'image' => array(
						'type' => 'media',
						'title' => __('图片','xintheme-pagebuilder'),
						'std' => array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-medium.jpg' ), 
					),
					'name' => array(
						'type' => 'text',
						'title' => __('名称','xintheme-pagebuilder'),
						'std' => __( '老王', 'xintheme-pagebuilder' ),
					),
					'name_link' => array(
						'type' => 'link',
						'title' => __('个人资料链接','xintheme-pagebuilder'),
						'std' =>   array('link'=>'','window'=>false,'nofolow'=>false),
					),
					'邮箱' => array(
						'type' => 'text',
						'title' => __('邮箱','xintheme-pagebuilder'),
						'placeholder' => 'example@example.com',
					),
					'职位' => array(
						'type' => 'text',
						'title' => __('职位','xintheme-pagebuilder'),
						'std' => __( '总经理', 'xintheme-pagebuilder' ),
					),
					'introtext' => array(
						'type' => 'textarea',
						'title' => __('消息文本','xintheme-pagebuilder'),
						'std' => __( '专注于互联网产品与网站建设，擅长将设计、技术与运营结合，帮助项目高效落地。', 'xintheme-pagebuilder' ),
					),
					'show_social' => array(
						'type' => 'switch',
						'title' => __('显示社交媒体链接','xintheme-pagebuilder'),
						'std' => '1'
					),
					'icon_list1' => array(
						'type' => 'iconsocial',
						'title' => __('图标 1','xintheme-pagebuilder'),
						'std' => 'ri-facebook-fill',
						'depends' => array(array('show_social', '!=', '')),
					),
					'socialurl1' => array(
						'type' => 'text',
						'title' => __('社交链接 1','xintheme-pagebuilder'),
						'std' => '#',
						'depends' => array(array('show_social', '!=', '')),
					),
					'icon_list2' => array(
						'type' => 'iconsocial',
						'title' => __('图标 2','xintheme-pagebuilder'),
						'std' => 'ri-twitter-fill',
						'depends' => array(array('show_social', '!=', '')),
					),
					'socialurl2' => array(
						'type' => 'text',
						'title' => __('社交链接 2','xintheme-pagebuilder'),
						'std' => '#',
						'depends' => array(array('show_social', '!=', '')),
					),
					'icon_list3' => array(
						'type' => 'iconsocial',
						'title' => __('图标 3','xintheme-pagebuilder'),
						'std' => 'ri-flickr-fill',
						'depends' => array(array('show_social', '!=', '')),
					),
					'socialurl3' => array(
						'type' => 'text',
						'title' => __('社交链接 3','xintheme-pagebuilder'),
						'std' => '#',
						'depends' => array(array('show_social', '!=', '')),
					),
					'icon_list4' => array(
						'type' => 'iconsocial',
						'title' => __('图标 4','xintheme-pagebuilder'),
						'std' => 'ri-facebook-fill',
						'depends' => array(array('show_social', '!=', '')),
					),
					'socialurl4' => array(
						'type' => 'text',
						'title' => __('社交链接 4','xintheme-pagebuilder'),
						'std' => '#',
						'depends' => array(array('show_social', '!=', '')),
					),
					'icon_list5' => array(
						'type' => 'iconsocial',
						'title' => __('图标 4','xintheme-pagebuilder'),
						'std' => 'ri-facebook-fill',
						'depends' => array(array('show_social', '!=', '')),
					),
					'socialurl5' => array(
						'type' => 'text',
						'title' => __('社交链接 5','xintheme-pagebuilder'),
						'std' => '#',
						'depends' => array(array('show_social', '!=', '')),
					),
					'icon_list6' => array(
						'type' => 'iconsocial',
						'title' => __('图标 6','xintheme-pagebuilder'),
						'std' => 'ri-facebook-fill',
						'depends' => array(array('show_social', '!=', '')),
					),
					'socialurl6' => array(
						'type' => 'text',
						'title' => __('社交链接 6','xintheme-pagebuilder'),
						'std' => '#',
						'depends' => array(array('show_social', '!=', '')),
					),
				),
			),
			'autoplay_option' => array(
				'type' => 'switch',
				'title' => __('自动播放选项','xintheme-pagebuilder'),
				'std' => '1',
				'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
			),
			'speed_option' => array(
				'type' => 'number',
				'title' => __('动画速度','xintheme-pagebuilder'),
				'std' => '600',
				'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
				'depends' => array(array('autoplay_option', '=', '1')),
			),
			'control_dots' => array(
				'type' => 'switch',
				'title' => __('控制圆点','xintheme-pagebuilder'),
				'std' => '0',
				'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
			),
			'control_nav' => array(
				'type' => 'switch',
				'title' => __('控制导航','xintheme-pagebuilder'),
				'std' => '1',
				'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
			),
			'control_nav_style' => array(
				'type' => 'select',
				'title' => __('样式','xintheme-pagebuilder'),
				'values' => array(
					'nav_style1' => __('导航样式 1','xintheme-pagebuilder'),
					'nav_style2' => __('导航样式 2','xintheme-pagebuilder'),
					'nav_style3' => __('导航样式 3','xintheme-pagebuilder'),
					'nav_style4' => __('导航样式 4','xintheme-pagebuilder'),
				),
				'std' => 'nav_style1',
				'depends' => array(array('control_nav', '!=', '')),
				'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
			),
			'column' => array(
				'type' => 'select',
				'title' => __('列','xintheme-pagebuilder'),
				'values' => array(
					'1' => __('第 1 列','xintheme-pagebuilder'),
					'2' => __('第 2 列','xintheme-pagebuilder'),
					'3' => __('第 3 列','xintheme-pagebuilder'),
					'4' => __('第 4 列','xintheme-pagebuilder'),
					'5' => __('第 5 列','xintheme-pagebuilder'),
					'6' => __('第 6 列','xintheme-pagebuilder'),
				),
				'std' => '1',
				'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
			),
			'person_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-carousel-addon-content{ text-align: {{data.person_align}}; }'
			),

			//style
			'img_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 55,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 550,
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
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-addon-img { width: {{data.img_width}}; }',
			),
			'img_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
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
						'md' => '',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-addon-img, {{SELECTOR}} .person-layout-layoutone .xtpb-person-addon-img { height: {{data.img_height}}; }',
			),
			'img_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-addon-img { border-radius: {{data.img_radius}}; }',
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

			//name
			'name_color' => array(
				'type' => 'color2',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => true,
				'std' => array(
					'colorType' => 'color',
					'clip' => true,
					'colorColor' => '#666666',
				),
				'section' => __( '名称', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-name, {{SELECTOR}} .xtpb-person-name a'
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
			'name_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-name { padding: {{data.name_padding}}; }',
				'section' => __( '名称', 'xintheme-pagebuilder' ),
			),
			'name_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-name { margin: {{data.name_margin}}; }',
				'section' => __( '名称', 'xintheme-pagebuilder' ),
			),
			//Designation	
			'desgn_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '职位', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-designation { color: {{data.desgn_color}}; }'
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
				'selector' => '{{SELECTOR}} .xtpb-person-designation',
				'section' => __( '职位', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			//邮箱
			'email_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '邮箱', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-email { color: {{data.email_color}}; }'
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
				'selector' => '{{SELECTOR}} .xtpb-person-email',
				'section' => __( '邮箱', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'email_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-email { margin: {{data.email_margin}}; }',
				'section' => __( '邮箱', 'xintheme-pagebuilder' ),
			),

			//intro
			'intro_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '消息文本', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-introtext { color: {{data.intro_color}}; }'
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
				'section' => __( '消息文本', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'message_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-introtext { margin: {{data.message_margin}}; }',
				'section' => __( '消息文本', 'xintheme-pagebuilder' ),
			),

			//social
			'icon_size' => array(
				'type' => 'slider',
				'title' => __('尺寸','xintheme-pagebuilder'),
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
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a { width: {{data.icon_width}}; }'
			),

			'icon_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 20,
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
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
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
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 20,
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
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' =>  '{{SELECTOR}} .xtpb-person-social li a { line-height: {{data.icon_line_height}}; }',
			),

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
				'type' => 'color2',
				'title' => __('背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a { background: {{data.icon_bg}}; }'
			),
			'icon_hover_bg' => array(
				'type' => 'color2',
				'title' => __('悬停背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-social li a:hover{ background: {{data.icon_hover_bg}}; }'
			),
			'icon_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '50px', 'right' => '50px', 'bottom' => '50px', 'left' => '50px' ),
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

			'share_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-social { margin: {{data.share_margin}}; }',
				'section' => __( '社交媒体', 'xintheme-pagebuilder' ),
			),

			// dots
			'dot_position' => array(
				'type' => 'slider',
				'title' => __('位置','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'range' => array(
					'em' => array(
						'min' => -20,
						'max' => 40,
						'step' => .1,
					),
					'px' => array(
						'min' => -200,
						'max' => 550,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
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
				'section' => __( '圆点样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_dots', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .slick-dots { bottom: {{data.dot_position}}; }',
			),
			'dot_bg_color' => array(
				'type' => 'color',
				'title' => __('背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '圆点样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .slick-dots li button { background: {{data.dot_bg_color}}; }'
			),
			'dot_bg_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停/激活背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '圆点样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .slick-dots li.slick-active button, {{SELECTOR}} .xtpb-person-content-carousel .slick-dots li button:hover { background: {{data.dot_bg_hcolor}}; }'
			),
			'dot_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => '16px',
					'sm' => '',
					'xs' => '',
				),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 10,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 100,
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
				'section' => __( '圆点样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .slick-dots li button, {{SELECTOR}} .xtpb-person-content-carousel .slick-dots li { width: {{data.dot_width}}; }'
			),
			'dot_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
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
						'md' => '16px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '圆点样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .slick-dots li button, {{SELECTOR}} .xtpb-person-content-carousel .slick-dots li { height: {{data.dot_height}}; }'
			),
			'dot_radius' => array(
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
				'section' => __( '圆点样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .slick-dots li button { border-radius: {{data.dot_radius}}; }'
			),

			// nav
			'nav_position' => array(
				'type' => 'slider',
				'title' => __('位置','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 55,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 550,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
				'std' => array(
						'md' => '35%',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev,{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next { top: {{data.nav_position}}; }',
			),
			'nav_left_position' => array(
				'type' => 'slider',
				'title' => __('上一项位置','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'std' => array(
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav_style', '=', 'nav_style2')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel.nav_style2 .xtpb-carousel-next { right: {{data.nav_left_position}}; }',
			),
			'nav_right_position' => array(
				'type' => 'slider',
				'title' => __('下一项位置','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'range' => array(
					'em' => array(
						'min' => -50,
						'max' => 80,
						'step' => .1,
					),
					'px' => array(
						'min' => -100,
						'max' => 1000,
						'step' => 1,
					),
					'%' => array(
						'min' => -100,
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
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav_style', '=', 'nav_style3')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel.nav_style3 .xtpb-carousel-prev { left: {{data.nav_right_position}}; }',
			),
			'nav_font_size' => array(
				'type' => 'slider',
				'title' => __('导航字体大小','xintheme-pagebuilder'),
				'responsive' => true,
				'std' => array(
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'tab' => 'style',
				'unit' => array( 'px','em','%' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 150,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next, {{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev { font-size: {{data.nav_font_size}}; }',
			),
			'nav_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next { color: {{data.nav_color}}; }'
			),
			'nav_bg_color' => array(
				'type' => 'color',
				'title' => __('背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next { background: {{data.nav_bg_color}}; }'
			),
			'nav_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev:hover, {{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next:hover { color: {{data.nav_hcolor}}; }'
			),
			'nav_bg_hcolor' => array(
				'type' => 'color',
				'title' => __('背景悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev:hover, {{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next:hover { background: {{data.nav_bg_hcolor}}; }'
			),
			'nav_width' => array(
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
						'md' => '30px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next { width: {{data.nav_width}}; }'
			),
			'nav_height' => array(
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
						'md' => '30px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next { height: {{data.nav_height}}; }',
			),
			'nav_line_height' => array(
				'type' => 'slider',
				'title' => __('行高','xintheme-pagebuilder'),
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
						'md' => '30px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next { line-height: {{data.nav_line_height}}; }',
			),

			'nav_radius' => array(
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
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next { border-radius: {{data.nav_radius}}; }'
			),
			'nav_border' => array(
				'type' => 'border',
				'title' => __( '导航边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev,{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next'
			),
			'nav_border_hcolor' => array(
				'type' => 'border',
				'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-prev:hover, {{SELECTOR}} .xtpb-person-content-carousel .xtpb-carousel-next:hover'
			),

			//wrapper
			'wrap_background' => array(
				'type' => 'background',
				'title' => '',
				'selector' => '{{SELECTOR}} .xtpb-person-addon-content',
				'tab' => 'style',
				'section' => __( '人物容器', 'xintheme-pagebuilder' ),
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
				'section' => __( '人物容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-addon-content'
			),
			'wrap_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '人物容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-addon-content { border-radius: {{data.wrap_radius}}; }'
			),
			'wrap_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
                ),
				'tab' => 'style',
				'section' => __( '人物容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-addon-content.slick-active'
			),
			'wrap_padding'  => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-addon-content { padding: {{data.wrap_padding}}; }',
				'section' => __( '人物容器', 'xintheme-pagebuilder' ),
			),
			'wrap_margin' => array(
				'type' => 'slider',
				'title' => __('右外边距','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 55,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 550,
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
				'section' => __( '人物容器', 'xintheme-pagebuilder' ),
				'selector' => array('{{SELECTOR}} .xtpb-person-content-carousel .slick-slide { margin-right: {{data.wrap_margin}}; }',
				'{{SELECTOR}} .xtpb-person-content-carousel .slick-list { margin-right: -{{data.wrap_margin}}; }')
			),

			'inner_background' => array(
				'type' => 'color2',
				'title' => __( '内容背景', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-person-information-wrap { background: {{data.inner_background}}; }',
				'tab' => 'style',
				'section' => __( '内容容器', 'xintheme-pagebuilder' ),
				'depends' => array(array('person_layout', '=', 'layouttwo')),
			),
			'inner_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '内容阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
                ),
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-person-information-wrap',
				'section' => __( '内容容器', 'xintheme-pagebuilder' ),
				'depends' => array(array('person_layout', '=', 'layouttwo')),
			),
			'inner_padding'  => array(
				'type' => 'dimension',
				'title' => __( '内容内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .person-layout-layoutone, {{SELECTOR}} .xtpb-person-information-wrap { padding: {{data.inner_padding}}; }',
				'section' => __( '内容容器', 'xintheme-pagebuilder' ),
			),
			

		);
		return $settings;
	}

	// Person Render HTML
	public function render($data = null){
		$settings 		    = $data['settings'];

		$person_list        = isset($settings["person_list"]) ? $settings["person_list"] : array();
		$person_layout      = isset($settings['person_layout']) ? sanitize_text_field( $settings['person_layout'] ) : 'layouttwo';
		$autoplay_option  	= isset($settings['autoplay_option']) ? sanitize_text_field( $settings['autoplay_option'] ) : '1';
		$control_nav  	 	  = isset($settings['control_nav']) ? sanitize_text_field( $settings['control_nav'] ) : '1';
		$control_dots  	 	  = isset($settings['control_dots']) ? sanitize_text_field( $settings['control_dots'] ) : '1';
		$control_nav_style  = isset($settings['control_nav_style']) ? sanitize_text_field( $settings['control_nav_style'] ) : '';
		$column  	 	 	      = isset($settings['column']) ? sanitize_text_field( $settings['column'] ) : '';
		$speed_option  	 	 	= isset($settings['speed_option']) ? sanitize_text_field( $settings['speed_option'] ) : '500';
		$rand               = rand(1000,999999);
		$output = '';

		if ( $autoplay_option == '1' ) {
			$autoplay_option = "true";
		} else {
			$autoplay_option = "false";
		}
		if ( $control_dots == '1') {
			$control_dots = "true";
		} else {
			$control_dots = "false";
		}
		if ( $control_nav == '1') {
			$control_nav = "true";
		} else {
			$control_nav = "false";
		}
		$column = ( isset( $column ) && $column ) ? $column : 1;
		$control_nav_style = ( isset( $control_nav_style ) && $control_nav_style ) ? $control_nav_style : 'nav_style1';

		$output  .= '<div class="xtpb-person-carousel-addon-content person-layout-' . esc_attr( $person_layout ) . '">';
				$output  .= '<div class="xtpb-person-content-carousel xtpb-person-' . esc_attr( $column ) . ' ' . esc_attr( $control_nav_style ) . '" data-col="' . esc_attr( $column ) . '" data-shownav="' . esc_attr( $control_nav ) . '" data-showdots="' . esc_attr( $control_dots ) . '" data-autoplay="' . esc_attr( $autoplay_option ) . '" data-speed="' . esc_attr( $speed_option ) . '">';
					
				if ( $person_layout == "layouttwo" ) {
						foreach ( $person_list as $key => $value ) {
							
							if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
								$target = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['window'] ) ? 'target=_blank' : 'target=_self';
								$nofolow = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['nofolow'] ) ? 'rel=nofolow' : "";
							}

							$output  .= '<div class="xtpb-person-addon-content">';

								if ( ! empty( $value['image']['url'] ) ) {
									$img_url = $value['image']['url'];
									$output .= '<div class="xtpb-person-image">';
										$output .= '<img class="xtpb-person-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( get_xtpb_array_value_by_key( $value, 'name' ) ) . '">';
									$output .= '</div>';
								}
								$output  .= '<div class="xtpb-person-information-wrap">';
									$output  .= '<div class="xtpb-person-information">';
										if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
											if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
												$output  .= '<span class="xtpb-person-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $value['name_link']['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $value['name'] ) . '</a></span>';
											}
										} else {
											if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
												$output  .= '<span class="xtpb-person-name">' . esc_html( $value['name'] ) . '</span>';
											}
										}
										if ( get_xtpb_array_value_by_key( $value, '职位' ) ) {
											$output  .= '<span class="xtpb-person-designation">' . esc_html( $value['职位'] ) . '</span>';
										}
										if ( get_xtpb_array_value_by_key( $value, '邮箱' ) ) {
											$output  .= '<span class="xtpb-person-email">' . esc_html( $value['邮箱'] ) . '</span>';
										}
										if ( get_xtpb_array_value_by_key( $value, 'introtext' ) ) {
											$output  .= '<div class="xtpb-person-introtext">' . wp_kses_post( $value['introtext'] ) . '</div>';
										}
									$output  .= '</div>';//xtpb-person-information
									if ( get_xtpb_array_value_by_key( $value, 'show_social' ) == 1 ) {
										$output  .= '<ul class="xtpb-person-social">';
											if ( get_xtpb_array_value_by_key( $value, 'socialurl1' ) ) {
												$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl1' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list1'] ) . '"></i></a></li>';
											}
											if ( get_xtpb_array_value_by_key( $value, 'socialurl2' ) ) {
												$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl2' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list2'] ) . '"></i></a></li>';
											}
											if ( get_xtpb_array_value_by_key( $value, 'socialurl3' ) ) {
												$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl3' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list3'] ) . '"></i></a></li>';
											}
											if ( get_xtpb_array_value_by_key( $value, 'socialurl4' ) ) {
												$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl4' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list4'] ) . '"></i></a></li>';
											}
											if ( get_xtpb_array_value_by_key( $value, 'socialurl5' ) ) {
												$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl5' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list5'] ) . '"></i></a></li>';
											}
											if ( get_xtpb_array_value_by_key( $value, 'socialurl6' ) ) {
												$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl6' ) ) .'" target="_blank"><i class="' . esc_attr( $value['icon_list6'] ) . '"></i></a></li>';
											}
										$output  .= '</ul>';
									}
								$output  .= '</div>';

							$output .= '</div>';
						}

					} elseif ( $person_layout == "layoutthree" ) {
						foreach ( $person_list as $key => $value ) {
							if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
								$target = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['window'] ) ? 'target=_blank' : 'target=_self';
								$nofolow = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['nofolow'] ) ? 'rel=nofolow' : "";
							}

							$output  .= '<div class="xtpb-person-addon-content">';

								$output  .= '<div class="xtpb-person-media">';
									if ( ! empty( $value['image']['url'] ) ) {
										$img_url = $value['image']['url'];
										$output .= '<div class="xtpb-person-image">';
											$output .= '<img class="xtpb-person-addon-img" src="' . esc_url( $img_url ) . '" alt="'. esc_attr( get_xtpb_array_value_by_key( $value, 'name' ) ) . '">';
										$output .= '</div>';
									}
									$output  .= '<div class="xtpb-person-media-body">';
										$output  .= '<div class="xtpb-person-information">';
											if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
												if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
													$output  .= '<span class="xtpb-person-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $value['name_link']['link'] ) . '" ' . esc_attr( $target ) . '>' . $value['name'] . '</a></span>';
												}
											} else {
												if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
													$output  .= '<span class="xtpb-person-name">' . esc_html( $value['name'] ) . '</span>';
												}
											}
											if ( get_xtpb_array_value_by_key( $value, '职位' ) ) {
												$output  .= '<span class="xtpb-person-designation">' . esc_html( $value['职位'] ) . '</span>';
											}
											if ( get_xtpb_array_value_by_key( $value, '邮箱' ) ) {
												$output  .= '<span class="xtpb-person-email">' . esc_html( $value['邮箱'] ) . '</span>';
											}
											if ( get_xtpb_array_value_by_key( $value, 'introtext' ) ) {
												$output  .= '<div class="xtpb-person-introtext">' . wp_kses_post( $value['introtext'] ) . '</div>';
											}
										$output  .= '</div>';//xtpb-person-information
										if ( get_xtpb_array_value_by_key( $value, 'show_social' ) == 1 ) {
											$output  .= '<ul class="xtpb-person-social">';
												if ( get_xtpb_array_value_by_key( $value, 'socialurl1' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl1' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list1'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl2' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl2' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list2'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl3' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl3' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list3'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl4' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl4' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list4'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl5' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl5' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list5'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl6' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl6' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list6'] ) . '"></i></a></li>';
												}
											$output  .= '</ul>';
										}
		$output  .= '</div>';


							$output .= '</div>';
						}

					} elseif ( $person_layout == "layoutfour" ) {
						foreach ( $person_list as $key => $value ) {
							if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
								$target = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['window'] ) ? 'target=_blank' : 'target=_self';
								$nofolow = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['nofolow'] ) ? 'rel=nofolow' : "";
							}
							$output  .= '<div class="xtpb-person-addon-content">';
								$output  .= '<div class="xtpb-person-media">';
									if ( ! empty( $value['image']['url'] ) ) {
										$img_url = $value['image']['url'];
										$output .= '<div class="xtpb-person-image">';
											$output .= '<img class="xtpb-person-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( get_xtpb_array_value_by_key( $value, 'name' ) ) . '">';
										$output .= '</div>';
									}
									$output  .= '<div class="xtpb-person-media-body">';
										$output  .= '<div class="xtpb-person-information">';
											if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
												if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
													$output  .= '<span class="xtpb-person-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $value['name_link']['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $value['name'] ) . '</a></span>';
												}
											} else {
												if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
													$output  .= '<span class="xtpb-person-name">' . esc_html( $value['name'] ) . '</span>';
												}
											}
											if ( get_xtpb_array_value_by_key( $value, '职位' ) ) {
												$output  .= '<span class="xtpb-person-designation">' . esc_html( $value['职位'] ) . '</span>';
											}
											if ( get_xtpb_array_value_by_key( $value, '邮箱' ) ) {
												$output  .= '<span class="xtpb-person-email">' . esc_html( $value['邮箱'] ) . '</span>';
											}
											if ( get_xtpb_array_value_by_key( $value, 'introtext' ) ) {
												$output  .= '<div class="xtpb-person-introtext">' . wp_kses_post( $value['introtext'] ) . '</div>';
											}
										$output  .= '</div>';//xtpb-person-information
										if ( get_xtpb_array_value_by_key( $value, 'show_social' ) == 1 ) {
											$output  .= '<ul class="xtpb-person-social">';
												if ( get_xtpb_array_value_by_key( $value, 'socialurl1' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl1' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list1'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl2' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl2' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list2'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key($value, 'socialurl3' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl3' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list3'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl4' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl4' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list4'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl5' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl5' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list5'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl6' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl6' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list6'] ) . '"></i></a></li>';
												}
											$output  .= '</ul>';
										}
									$output  .= '</div>';
								$output  .= '</div>';
							$output  .= '</div>';
						}

					} else {
						foreach ( $person_list as $key => $value ) {
							if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
								$target = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['window'] ) ? 'target=_blank' : 'target=_self';
								$nofolow = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['nofolow'] ) ? 'rel=nofolow' : "";
							}
							$output  .= '<div class="xtpb-person-addon-content xtpb-key-' . esc_attr( $key ) . '">';
								if ( ! empty( $value['image']['url'] ) ) {
									$img_url = $value['image']['url'];
									$output .= '<div class="xtpb-person-image">';
										$output .= '<img class="xtpb-person-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( get_xtpb_array_value_by_key( $value, 'name' ) ) . '">';
									$output .= '</div>';
								}
								$output  .= '<div class="xtpb-person-information-wrap">';
									$output  .= '<div class="xtpb-person-information">';
										if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
											if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
												$output  .= '<span class="xtpb-person-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $value['name_link']['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $value['name'] ) . '</a></span>';
											}
										} else {
											if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
												$output  .= '<span class="xtpb-person-name">' . esc_html( $value['name'] ) . '</span>';
											}
										}
										if ( get_xtpb_array_value_by_key( $value, '职位' ) ) {
											$output  .= '<span class="xtpb-person-designation">' . esc_html( $value['职位'] ) . '</span>';
										}
										if ( get_xtpb_array_value_by_key( $value, '邮箱' ) ) {
											$output  .= '<span class="xtpb-person-email">' . esc_html( $value['邮箱'] ) . '</span>';
										}
										if ( get_xtpb_array_value_by_key( $value, 'introtext' ) ) {
											$output  .= '<div class="xtpb-person-introtext">' . wp_kses_post( $value['introtext'] ) . '</div>';
										}
										if ( get_xtpb_array_value_by_key( $value, 'show_social' ) == 1 ) {
											$output  .= '<ul class="xtpb-person-social">';
												if ( get_xtpb_array_value_by_key( $value, 'socialurl1' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl1' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list1'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl2' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl2' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list2'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl3' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl3' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list3'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl4' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl4' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list4'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl5' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl5' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list5'] ) . '"></i></a></li>';
												}
												if ( get_xtpb_array_value_by_key( $value, 'socialurl6' ) ) {
													$output  .= '<li><a href="' . esc_url( get_xtpb_array_value_by_key( $value, 'socialurl6' ) ) . '" target="_blank"><i class="' . esc_attr( $value['icon_list6'] ) . '"></i></a></li>';
												}
											$output  .= '</ul>';
										}
									$output  .= '</div>';//xtpb-person-information
								$output  .= '</div>';
							$output  .= '</div>';
						}
					}	
					$output  .= '</div>';
				$output  .= '</div>';

		return $output;
	}

	// Person Template
	public function getTemplate(){
		$output = '
			<#
			var rand = Math.floor(Math.random() * 1000);
			
			var autoplay_option = "";
			if(data.autoplay_option == 1){
				autoplay_option = "true";
			} else {
				autoplay_option = "false";
			}
			var control_dots = "";
			if(data.control_dots == 1){
				control_dots = "true";
			} else {
				control_dots = "false";
			}
			var control_nav = "";
			if(data.control_nav == 1){
				control_nav = "true";
			} else {
				control_nav = "false";
			}
			column =  data.column ? data.column : "1";
			control_nav_style =  data.control_nav_style ? data.control_nav_style : "nav_style1";
			#>
			<div class="xtpb-person-carousel-addon-content person-layout-{{data.person_layout}}">
					<div class="xtpb-person-content-carousel xtpb-person-{{column}} {{control_nav_style}} " data-col="{{column}}" data-shownav="{{control_nav}}" data-showdots="{{control_dots}}" data-autoplay="{{autoplay_option}}" data-speed="{{data.speed_option}}">
						<# if( data.person_layout == "layouttwo" ){ #>
							<#  _.forEach(data.person_list, function(value, key) { #>
								<div class="xtpb-person-addon-content">
									<# if ( value.image ) { #>
										<div class="xtpb-person-image">
											<img class="xtpb-person-addon-img" src="{{value.image.url}}">
										</div>
									<# } #>
									<div class="xtpb-person-information-wrap">
										<div class="xtpb-person-information">
											<# if( value.name_link ){ #>
												<span class="xtpb-person-name"><a {{ value.name_link.link ? "href="+value.name_link.link : "" }} {{ value.name_link.window ? "target=_blank" : "" }} {{ value.name_link.nofolow ? "rel=nofolow" : "" }}>{{value.name}}</a></span>
											<# } else { #>
												<span class="xtpb-person-name">{{value.name}}</span>
											<# } #>
											<# if( value.职位 ) { #>
												<span class="xtpb-person-designation">{{value.职位}}</span>
											<# } #>
											<# if( value.邮箱 ) { #>
												<span class="xtpb-person-email">{{value.邮箱}}</span>
											<# } #>
											<# if( value.introtext ) { #>
												<div class="xtpb-person-introtext">{{value.introtext}}</div>
											<# } #>

											<# if( value.show_social == 1 ) { #>
												<ul class="xtpb-person-social">
													<# if( value.socialurl1 ) { #>
														<li><a href="{{value.socialurl1}}" target="_blank"><i class="{{value.icon_list1}}"></i></a></li>
													<# } #>
													<# if( value.socialurl2 ) { #>
														<li><a href="{{value.socialurl2}}" target="_blank"><i class="{{value.icon_list2}}"></i></a></li>
													<# } #>
													<# if( value.socialurl3 ) { #>
														<li><a href="{{value.socialurl3}}" target="_blank"><i class="{{value.icon_list3}}"></i></a></li>
													<# } #>
													<# if( value.socialurl4 ) { #>
														<li><a href="{{value.socialurl4}}" target="_blank"><i class="{{value.icon_list4}}"></i></a></li>
													<# } #>
													<# if( value.socialurl5 ) { #>
														<li><a href="{{value.socialurl5}}" target="_blank"><i class="{{value.icon_list5}}"></i></a></li>
													<# } #>
													<# if( value.socialurl6 ) { #>
														<li><a href="{{value.socialurl6}}" target="_blank"><i class="{{value.icon_list6}}"></i></a></li>
													<# } #>
												</ul>
											<# } #>
										</div>
									</div>
								</div>
							<# }); #>
						<# } else if( data.person_layout == "layoutthree" ){ #>

							<#  _.forEach(data.person_list, function(value, key) { #>
								<div class="xtpb-person-addon-content">
									<div class="xtpb-person-media">
										<# if ( value.image ) { #>
											<div class="xtpb-person-image">
												<img class="xtpb-person-addon-img" src="{{value.image.url}}">
											</div>
										<# } #>
										<div class="xtpb-person-media-body">
											<div class="xtpb-person-information">
												<# if( value.name_link ){ #>
													<span class="xtpb-person-name"><a {{ value.name_link.link ? "href="+value.name_link.link : "" }} {{ value.name_link.window ? "target=_blank" : "" }} {{ value.name_link.nofolow ? "rel=nofolow" : "" }}>{{value.name}}</a></span>
												<# } else { #>
													<span class="xtpb-person-name">{{value.name}}</span>
												<# } #>
												<# if( value.职位 ) { #>
													<span class="xtpb-person-designation">{{value.职位}}</span>
												<# } #>
												<# if( value.邮箱 ) { #>
													<span class="xtpb-person-email">{{value.邮箱}}</span>
												<# } #>
												<# if( value.introtext ) { #>
													<div class="xtpb-person-introtext">{{value.introtext}}</div>
												<# } #>
											</div>
					
											<# if( value.show_social == 1 ) { #>
												<ul class="xtpb-person-social">
													<# if( value.socialurl1 ) { #>
														<li><a href="{{value.socialurl1}}" target="_blank"><i class="{{value.icon_list1}}"></i></a></li>
													<# } #>
													<# if( value.socialurl2 ) { #>
														<li><a href="{{value.socialurl2}}" target="_blank"><i class="{{value.icon_list2}}"></i></a></li>
													<# } #>
													<# if( value.socialurl3 ) { #>
														<li><a href="{{value.socialurl3}}" target="_blank"><i class="{{value.icon_list3}}"></i></a></li>
													<# } #>
													<# if( value.socialurl4 ) { #>
														<li><a href="{{value.socialurl4}}" target="_blank"><i class="{{value.icon_list4}}"></i></a></li>
													<# } #>
													<# if( value.socialurl5 ) { #>
														<li><a href="{{value.socialurl5}}" target="_blank"><i class="{{value.icon_list5}}"></i></a></li>
													<# } #>
													<# if( value.socialurl6 ) { #>
														<li><a href="{{value.socialurl6}}" target="_blank"><i class="{{value.icon_list6}}"></i></a></li>
													<# } #>
												</ul>
											<# } #>
										</div>
									</div>
								</div>

							<# }); #>

						<# } else if( data.person_layout == "layoutfour" ){ #>

								<#  _.forEach(data.person_list, function(value, key) { #>
									<div class="xtpb-person-addon-content">
										<div class="xtpb-person-media">
											<# if ( value.image ) { #>
												<div class="xtpb-person-image">
													<img class="xtpb-person-addon-img" src="{{value.image.url}}">
												</div>
											<# } #>
											<div class="xtpb-person-media-body">
						
												<div class="xtpb-person-information">
													<# if( value.name_link ){ #>
														<span class="xtpb-person-name"><a {{ value.name_link.link ? "href="+value.name_link.link : "" }} {{ value.name_link.window ? "target=_blank" : "" }} {{ value.name_link.nofolow ? "rel=nofolow" : "" }}>{{value.name}}</a></span>
													<# } else { #>
														<span class="xtpb-person-name">{{value.name}}</span>
													<# } #>
													<# if( value.职位 ) { #>
														<span class="xtpb-person-designation">{{value.职位}}</span>
													<# } #>
													<# if( value.邮箱 ) { #>
														<span class="xtpb-person-email">{{value.邮箱}}</span>
													<# } #>
													<# if( value.introtext ) { #>
														<div class="xtpb-person-introtext">{{value.introtext}}</div>
													<# } #>
												</div>
						
												<# if( value.show_social == 1 ) { #>
													<ul class="xtpb-person-social">
														<# if( value.socialurl1 ) { #>
															<li><a href="{{value.socialurl1}}" target="_blank"><i class="{{value.icon_list1}}"></i></a></li>
														<# } #>
														<# if( value.socialurl2 ) { #>
															<li><a href="{{value.socialurl2}}" target="_blank"><i class="{{value.icon_list2}}"></i></a></li>
														<# } #>
														<# if( value.socialurl3 ) { #>
															<li><a href="{{value.socialurl3}}" target="_blank"><i class="{{value.icon_list3}}"></i></a></li>
														<# } #>
														<# if( value.socialurl4 ) { #>
															<li><a href="{{value.socialurl4}}" target="_blank"><i class="{{value.icon_list4}}"></i></a></li>
														<# } #>
														<# if( value.socialurl5 ) { #>
															<li><a href="{{value.socialurl5}}" target="_blank"><i class="{{value.icon_list5}}"></i></a></li>
														<# } #>
														<# if( value.socialurl6 ) { #>
															<li><a href="{{value.socialurl6}}" target="_blank"><i class="{{value.icon_list6}}"></i></a></li>
														<# } #>
													</ul>
												<# } #>
											</div>
										</div>
									</div>
								<# }); #>

						<# } else {  #>	
							
							<#  _.forEach(data.person_list, function(value, key) { #>
								<div class="xtpb-person-addon-content">
									<# if ( value.image ) { #>
										<div class="xtpb-person-image">
											<img class="xtpb-person-addon-img" src="{{value.image.url}}">
										</div>
									<# } #>
									<div class="xtpb-person-information-wrap">
										<div class="xtpb-person-information">
											<# if( value.name_link ){ #>
												<span class="xtpb-person-name"><a {{ value.name_link.link ? "href="+value.name_link.link : "" }} {{ value.name_link.window ? "target=_blank" : "" }} {{ value.name_link.nofolow ? "rel=nofolow" : "" }}>{{value.name}}</a></span>
											<# } else { #>
												<span class="xtpb-person-name">{{value.name}}</span>
											<# } #>
											<# if( value.职位 ) { #>
												<span class="xtpb-person-designation">{{value.职位}}</span>
											<# } #>
											<# if( value.邮箱 ) { #>
												<span class="xtpb-person-email">{{value.邮箱}}</span>
											<# } #>
											<# if( value.introtext ) { #>
												<div class="xtpb-person-introtext">{{value.introtext}}</div>
											<# } #>

											<# if( value.show_social == 1 ) { #>
												<ul class="xtpb-person-social">
													<# if( value.socialurl1 ) { #>
														<li><a href="{{value.socialurl1}}" target="_blank"><i class="{{value.icon_list1}}"></i></a></li>
													<# } #>
													<# if( value.socialurl2 ) { #>
														<li><a href="{{value.socialurl2}}" target="_blank"><i class="{{value.icon_list2}}"></i></a></li>
													<# } #>
													<# if( value.socialurl3 ) { #>
														<li><a href="{{value.socialurl3}}" target="_blank"><i class="{{value.icon_list3}}"></i></a></li>
													<# } #>
													<# if( value.socialurl4 ) { #>
														<li><a href="{{value.socialurl4}}" target="_blank"><i class="{{value.icon_list4}}"></i></a></li>
													<# } #>
													<# if( value.socialurl5 ) { #>
														<li><a href="{{value.socialurl5}}" target="_blank"><i class="{{value.icon_list5}}"></i></a></li>
													<# } #>
													<# if( value.socialurl6 ) { #>
														<li><a href="{{value.socialurl6}}" target="_blank"><i class="{{value.icon_list6}}"></i></a></li>
													<# } #>
												</ul>
											<# } #>
										</div>
									</div>
								</div>
							<# }); #>

						<# } #>	

					</div>
				</div>
		';
		return $output;
	}

}
