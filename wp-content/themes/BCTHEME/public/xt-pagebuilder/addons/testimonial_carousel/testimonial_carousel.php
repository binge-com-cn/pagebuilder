<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Testimonial_Carousel{
	public function get_name(){
		return 'xtpb_testimonial_carousel';
	}
	public function get_title(){
		return __( '评价轮播', 'xintheme-pagebuilder' );
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
	// Testimonial Settings Fields
	public function get_settings() {

		$settings = array(

			'testimonial_layout' => array(
				'type' => 'radioimage',
				'title' => __( '布局', 'xintheme-pagebuilder' ),
				'values'=> array(
					'layoutone' =>  XTPB_DIR_URL.'addons/testimonial_carousel/img/testimonial-img1.png',
					'layouttwo' =>  XTPB_DIR_URL.'addons/testimonial_carousel/img/testimonial-img2.png',
					'layoutthree' =>  XTPB_DIR_URL.'addons/testimonial_carousel/img/testimonial-img3.png',
					'layoutfour' =>  XTPB_DIR_URL.'addons/testimonial_carousel/img/testimonial-img4.png',
				),
				'std' => 'layoutone',
			),

			'testimonial_list' => array(
				'title' => __('评价项','xintheme-pagebuilder'),
				'type' => 'repeatable',
				'label' => 'name',
					'std' => array(
						array(
							'image' => array( 'url' =>  XTPB_DIR_URL.'assets/img/placeholder/xtpb-small.jpg' ),
							'name' => __( '张明远', 'xintheme-pagebuilder' ),
							'name_link' => array( 'link'=>'#' ),
							'designation' => __( '企业负责人', 'xintheme-pagebuilder' ),
							'introtext' => __( '整体体验非常不错，页面搭建效率高，功能设置也比较清晰，基本不需要额外学习就能快速上手。', 'xintheme-pagebuilder' ),
						),
						array(
							'image' => array( 'url' =>  XTPB_DIR_URL.'assets/img/placeholder/xtpb-small.jpg' ),
							'name' => __( '李思涵', 'xintheme-pagebuilder' ),
							'name_link' => array( 'link'=>'#' ),
							'designation' => __( '网站运营', 'xintheme-pagebuilder' ),
							'introtext' => __( '模块样式比较丰富，编辑过程很直观，后期维护内容也方便，适合日常网站更新和页面调整。', 'xintheme-pagebuilder' ),
						),
						array(
							'image' => array( 'url' =>  XTPB_DIR_URL.'assets/img/placeholder/xtpb-small.jpg' ),
							'name' => __( '陈亦舟', 'xintheme-pagebuilder' ),
							'name_link' => array( 'link'=>'#' ),
							'designation' => __( '项目经理', 'xintheme-pagebuilder' ),
							'introtext' => __( '设计效果简洁专业，功能满足项目需求，整体交付效率比预期更高，是一次比较省心的使用体验。', 'xintheme-pagebuilder' ),
						),

					),
				'attr' => array(
					'image' => array(
						'type' => 'media',
						'title' => __('图片','xintheme-pagebuilder'),
						'std' => array( 'url' =>  XTPB_DIR_URL.'assets/img/placeholder/xtpb-small.jpg' ),
					),
					'name' => array(
						'type' => 'text',
						'title' => __('名称','xintheme-pagebuilder'),
						'std' => __( '老王', 'xintheme-pagebuilder' ),
					),
					'name_link' => array(
						'type' => 'link',
						'title' => __('链接','xintheme-pagebuilder'),
						'std' =>   array( 'link'=>'#' ),
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
				),
      ),

			'autoplay_option' => array(
				'type' => 'switch',
				'title' => __('自动播放','xintheme-pagebuilder'),
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
				'std' => '1',
				'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
			),
			'control_nav' => array(
				'type' => 'switch',
				'title' => __('控制导航','xintheme-pagebuilder'),
				'std' => '0',
				'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
			),
			'control_nav_style' => array(
				'type' => 'select',
				'title' => __('导航样式','xintheme-pagebuilder'),
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
			'testimonial_align' => array(
				'type' => 'alignment',
				'title' => __('评价对齐','xintheme-pagebuilder'),
				'responsive' => true,
				'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-carousel-addon-content{ text-align: {{data.testimonial_align}}; }'
			),

			//style
			'img_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'std' => array(
					'md' => '50px',
					'sm' => '',
					'xs' => '',
				),
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
				'title' => __('文字颜色','xintheme-pagebuilder'),
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-introtext',
				'section' => __( '消息文本', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'message_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-testimonial-introtext { margin: {{data.message_margin}}; }',
				'section' => __( '消息文本', 'xintheme-pagebuilder' ),
			),

			//intro
			'quote_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '引用图标', 'xintheme-pagebuilder' ),
				'depends' => array(array('testimonial_layout', '=', array('layoutfour'))),
				'selector' => '{{SELECTOR}} .testimonial-layout-layoutfour .xtpb-testimonial-quote { color: {{data.quote_color}}; }'
			),
			'quote_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .testimonial-layout-layoutfour .xtpb-testimonial-quote',
				'section' => __( '引用图标', 'xintheme-pagebuilder' ),
				'depends' => array(array('testimonial_layout', '=', array('layoutfour'))),
				'tab' => 'style',
			),

			// dots
			'dot_position' => array(
				'type' => 'slider',
				'title' => __('位置','xintheme-pagebuilder'),
				'responsive' => true,
				'max' => 550,
				'min' => -200,
				'range' => array(
					'em' => array(
						'min' => -20,
						'max' => 50,
						'step' => .1,
					),
					'px' => array(
						'min' => -220,
						'max' => 550,
						'step' => 1,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
						'step' => 1,
					),
				),
				'tab' => 'style',
				'std' => array(
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'unit' => array( '%','px','em' ),
				'section' => __( '圆点样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_dots', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .slick-dots { bottom: {{data.dot_position}}; }',
			),
			'dot_bg_color' => array(
				'type' => 'color',
				'title' => __('背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '圆点样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .slick-dots li button { background: {{data.dot_bg_color}}; }'
			),
			'dot_bg_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停/激活背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '圆点样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .slick-dots li.slick-active button,{{SELECTOR}} .xtpb-testimonial-content-carousel .slick-dots li button:hover { background: {{data.dot_bg_hcolor}}; }'
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
						'max' => 5,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 50,
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .slick-dots li button, {{SELECTOR}} .xtpb-testimonial-content-carousel .slick-dots li { width: {{data.dot_width}}; }'
			),
			'dot_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => '16px',
					'sm' => '',
					'xs' => '',
				),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 50,
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .slick-dots li button, {{SELECTOR}} .xtpb-testimonial-content-carousel .slick-dots li { height: {{data.dot_height}}; }'
			),
			'dot_margin' => array(
				'type' => 'dimension',
				'title' => __('圆点外边距','xintheme-pagebuilder'),
				'tab'=> 'style',
				'section' => __('圆点样式','xintheme-pagebuilder'),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .slick-dots li { margin: {{data.dot_margin}}; }'
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .slick-dots li button { border-radius: {{data.dot_radius}}; }'
			),

			// nav
			'nav_position' => array(
				'type' => 'slider',
				'title' => __('位置','xintheme-pagebuilder'),
				'responsive' => true,
				'unit' => array( '%','px','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 20,
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
						'md' => '0px',
						'sm' => '',
						'xs' => '',
					),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-carousel-addon-content .xtpb-testimonial-content-carousel .xtpb-carousel-prev,{{SELECTOR}} .xtpb-testimonial-carousel-addon-content .xtpb-testimonial-content-carousel .xtpb-carousel-next { top: {{data.nav_position}}; }',
			),
			'nav_left_position' => array(
				'type' => 'slider',
				'title' => __('上一项位置','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'range' => array(
						'em' => array(
							'min' => -10,
							'max' => 20,
							'step' => .1,
						),
						'px' => array(
							'min' => -200,
							'max' => 1000,
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
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav_style', '=', array('nav_style1','nav_style2'))),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-carousel-addon-content .xtpb-testimonial-content-carousel .xtpb-carousel-next { left: {{data.nav_left_position}}; }',
			),
			'nav_right_position' => array(
				'type' => 'slider',
				'title' => __('下一项位置','xintheme-pagebuilder'),
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
						'min' => -10,
						'max' => 20,
						'step' => .1,
					),
					'px' => array(
						'min' => -200,
						'max' => 1000,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav_style', '=', array('nav_style1','nav_style3'))),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-carousel-addon-content .xtpb-testimonial-content-carousel .xtpb-carousel-prev { right: {{data.nav_right_position}}; }',
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next, {{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev { font-size: {{data.nav_font_size}}; }',
			),

			'nav_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next { color: {{data.nav_color}}; }'
			),
			'nav_bg_color' => array(
				'type' => 'color',
				'title' => __('背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next { background: {{data.nav_bg_color}}; }'
			),
			'nav_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev:hover, {{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next:hover { color: {{data.nav_hcolor}}; }'
			),
			'nav_bg_hcolor' => array(
				'type' => 'color',
				'title' => __('背景悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev:hover, {{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next:hover { background: {{data.nav_bg_hcolor}}; }'
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next { width: {{data.nav_width}}; }'
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
				'selector' => array(
					'{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next { height: {{data.nav_height}}; }',
					'{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next { line-height: {{data.nav_height}}; }',
				)
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev, {{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next { border-radius: {{data.nav_radius}}; }'
			),
			'nav_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#cccccc'
				),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev,{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next'
			),
			'border_hcolor' => array(
				'type' => 'border',
				'title' => __( '悬停边框颜色', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#cccccc'
				),
				'tab' => 'style',
				'section' => __( '导航样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('control_nav', '!=', '')),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-prev:hover,{{SELECTOR}} .xtpb-testimonial-content-carousel .xtpb-carousel-next:hover'
			),

			//wrapper
			'wrap_background' => array(
				'type' => 'background',
				'title' => '',
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content .xtpb-testimonial-content-in',
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
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content .xtpb-testimonial-content-in'
			),
			'wrap_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '评价容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content .xtpb-testimonial-content-in { border-radius: {{data.wrap_radius}}; }'
			),
			'wrap_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'tab' => 'style',
				'section' => __( '评价容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content.slick-active .xtpb-testimonial-content-in'
			),
			'wrap_padding'  => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-testimonial-content .xtpb-testimonial-content-in { padding: {{data.wrap_padding}}; }',
				'section' => __( '评价容器', 'xintheme-pagebuilder' ),
			),

			'wrap_margin' => array(
				'type' => 'slider',
				'title' => __('右外边距','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 150,
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
				'std' => array(
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '评价容器', 'xintheme-pagebuilder' ),
				'selector' => array('{{SELECTOR}} .xtpb-testimonial-content-carousel .slick-slide { margin-right: {{data.wrap_margin}}; }',
					'{{SELECTOR}} .xtpb-testimonial-content-carousel .slick-list { margin-right: -{{data.wrap_margin}}; }')
			),

		);
		return $settings;
	}

	// Testimonial Render HTML
	public function render($data = null){
		$settings 			 = $data['settings'];

		$testimonial_list    = isset($settings["testimonial_list"]) ? $settings["testimonial_list"] : array();
		$testimonial_layout  = isset( $settings['testimonial_layout'] ) ? sanitize_text_field( $settings['testimonial_layout'] ) : '';
		$autoplay_option  	 = isset( $settings['autoplay_option'] ) ? sanitize_text_field( $settings['autoplay_option'] ) : '';
		$control_nav  	 	 = isset( $settings['control_nav'] ) ? sanitize_text_field( $settings['control_nav'] ) : '';
		$control_dots  	 	 = isset( $settings['control_dots'] ) ? sanitize_text_field( $settings['control_dots'] ) : '';
		$control_nav_style   = isset( $settings['control_nav_style'] ) ? sanitize_text_field( $settings['control_nav_style'] ) : '';
		$speed_option        = isset( $settings['speed_option'] ) ? sanitize_text_field( $settings['speed_option'] ) : '500';
		$column  	 	 	 = isset( $settings['column'] ) ? sanitize_text_field( $settings['column'] ) : '';

		$rand = rand(1000,999999);
		$output = '';

		if( $autoplay_option == '1'){
			$autoplay_option = "true";
		} else {
			$autoplay_option = "false";
		}
		if( $control_dots == '1'){
			$control_dots = "true";
		} else {
			$control_dots = "false";
		}
		if( $control_nav == '1'){
			$control_nav = "true";
		} else {
			$control_nav = "false";
		}
		$column = (isset($column) && $column) ? $column : 1;
		$control_nav_style = (isset($control_nav_style) && $control_nav_style) ? $control_nav_style : 'nav_style1';

		$output  .= '<div class="xtpb-testimonial-carousel-addon-content testimonial-layout-' . esc_attr( $testimonial_layout ) . '">';
		$output  .= '<div class="xtpb-testimonial-content-carousel xtpb-testimonial-' . esc_attr( $column ) . ' ' . esc_attr( $control_nav_style ) . '" data-col="' . esc_attr( $column ) . '" data-shownav="' . esc_attr( $control_nav ) . '" data-showdots="' . esc_attr( $control_dots ) . '" data-autoplay="' . esc_attr( $autoplay_option ) . '" data-speed="' . esc_attr( $speed_option ) . '">';

		if ( is_array( $testimonial_list ) && count( $testimonial_list ) ) {
			if ( $testimonial_layout == "layouttwo" ) {
				foreach ( $testimonial_list as $key => $value ) {
					if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
						$target  = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['window'] ) ? 'target=_blank' : 'target=_self';
						$nofolow = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['nofolow'] ) ? 'rel=nofolow' : "";
					}

					$output .= '<div class="xtpb-testimonial-content">';
					$output .= '<div class="xtpb-testimonial-content-in">';
					if ( ! empty( $value['image']['url'] ) ) {
						$img_url = $value['image']['url'];
						$output  .= '<div class="xtpb-testimonial-image">';
						$output  .= '<img class="xtpb-testimonial-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( get_xtpb_array_value_by_key( $value, 'name' ) ) . '">';
						$output  .= '</div>';
					}
					if ( get_xtpb_array_value_by_key( $value, 'introtext' ) ) {
						$output .= '<div class="xtpb-testimonial-introtext">' . wp_kses_post( $value['introtext'] ) . '</div>';
					}
					$output .= '<div class="xtpb-testimonial-title">';
					if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
						if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
							$output .= '<h4 class="xtpb-testimonial-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $value['name_link']['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $value['name'] ) . '</a></h4>';
						}
					} else {
						if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
							$output .= '<h4 class="xtpb-testimonial-name">' . esc_html( $value['name'] ) . '</h4>';
						}
					}
					if ( get_xtpb_array_value_by_key( $value, 'designation' ) ) {
						$output .= '<span class="xtpb-testimonial-designation">' . esc_html( $value['designation'] ) . '</span>';
					}
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
				}

			} elseif ( $testimonial_layout == "layoutthree" ) {
				foreach ( $testimonial_list as $key => $value ) {

					if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
						$target  = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['window'] ) ? 'target=_blank' : 'target=_self';
						$nofolow = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['nofolow'] ) ? 'rel=nofolow' : "";
					}

					$output .= '<div class="xtpb-testimonial-content">';
					$output .= '<div class="xtpb-testimonial-content-in">';
					if ( get_xtpb_array_value_by_key( $value, 'introtext' ) ) {
						$output .= '<div class="xtpb-testimonial-introtext">' . wp_kses_post( $value['introtext'] ) . '</div>';
					}
					if ( ! empty( $value['image']['url'] ) ) {
						$img_url = $value['image']['url'];
						$output  .= '<div class="xtpb-testimonial-image">';
						$output  .= '<img class="xtpb-testimonial-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( get_xtpb_array_value_by_key( $value, 'name' ) ) . '">';
						$output  .= '</div>';
					}
					$output .= '<div class="xtpb-testimonial-title">';
					if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
						if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
							$output .= '<h4 class="xtpb-testimonial-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $value['name_link']['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $value['name'] ) . '</a></h4>';
						}
					} else {
						if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
							$output .= '<h4 class="xtpb-testimonial-name">' . esc_html( $value['name'] ) . '</h4>';
						}
					}
					if ( get_xtpb_array_value_by_key( $value, 'designation' ) ) {
						$output .= '<span class="xtpb-testimonial-designation">' . esc_html( $value['designation'] ) . '</span>';
					}
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';

				}

			} elseif ( $testimonial_layout == "layoutfour" ) {
				foreach ( $testimonial_list as $key => $value ) {

					if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
						$target  = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['window'] ) ? 'target=_blank' : 'target=_self';
						$nofolow = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['nofolow'] ) ? 'rel=nofolow' : "";
					}

					$output .= '<div class="xtpb-testimonial-content">';
					$output .= '<div class="xtpb-testimonial-content-in">';
					$output .= '<span class="xtpb-testimonial-quote xtpb-font-quote"></span>';
					if ( get_xtpb_array_value_by_key( $value, 'introtext' ) ) {
						$output .= '<div class="xtpb-testimonial-introtext">' . wp_kses_post( $value['introtext'] ) . '</div>';
					}
					if ( ! empty( $value['image']['url'] ) ) {
						$img_url = $value['image']['url'];
						$output  .= '<div class="xtpb-testimonial-image">';
						$output  .= '<img class="xtpb-testimonial-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( get_xtpb_array_value_by_key( $value, 'name' ) ) . '">';
						$output  .= '</div>';
					}
					$output .= '<div class="xtpb-testimonial-title">';
					if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
						if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
							$output .= '<h4 class="xtpb-testimonial-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $value['name_link']['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $value['name'] ) . '</a></h4>';
						}
					} else {
						if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
							$output .= '<h4 class="xtpb-testimonial-name">' . esc_html( $value['name'] ) . '</h4>';
						}
					}
					if ( get_xtpb_array_value_by_key( $value, 'designation' ) ) {
						$output .= '<span class="xtpb-testimonial-designation">' . esc_html( $value['designation'] ) . '</span>';
					}
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';

				}

			} else {
				foreach ( $testimonial_list as $key => $value ) {
					if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
						$target  = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['window'] ) ? 'target=_blank' : 'target=_self';
						$nofolow = ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['nofolow'] ) ? 'rel=nofolow' : "";
					}
					$output .= '<div class="xtpb-testimonial-content">';
					$output .= '<div class="xtpb-testimonial-content-in">';
					if ( get_xtpb_array_value_by_key( $value, 'introtext' ) ) {
						$output .= '<div class="xtpb-testimonial-introtext">' . wp_kses_post( $value['introtext'] ) . '</div>';
					}
					$output .= '<div class="xtpb-testimonial-information">';
					if ( ! empty( $value['image']['url'] ) ) {
						$img_url = $value['image']['url'];
						$output  .= '<div class="xtpb-testimonial-image">';
						$output  .= '<img class="xtpb-testimonial-addon-img" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( get_xtpb_array_value_by_key( $value, 'name' ) ) . '">';
						$output  .= '</div>';
					}
					$output .= '<div class="xtpb-testimonial-title">';
					if ( ! empty( get_xtpb_array_value_by_key( $value, 'name_link' )['link'] ) ) {
						if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
							$output .= '<h4 class="xtpb-testimonial-name"><a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $value['name_link']['link'] ) . '" ' . esc_attr( $target ) . '>' . esc_html( $value['name'] ) . '</a></h4>';
						}
					} else {
						if ( get_xtpb_array_value_by_key( $value, 'name' ) ) {
							$output .= '<h4 class="xtpb-testimonial-name">' . esc_html( $value['name'] ) . '</h4>';
						}
					}
					if ( get_xtpb_array_value_by_key( $value, 'designation' ) ) {
						$output .= '<span class="xtpb-testimonial-designation">' . esc_html( $value['designation'] ) . '</span>';
					}
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
				}
			}
		}
		$output  .= '</div>';
		$output  .= '</div>';

		return $output;
	}

	// Testimonial Template
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
			<div class="xtpb-testimonial-carousel-addon-content testimonial-layout-{{data.testimonial_layout}}">
					<div class="xtpb-testimonial-content-carousel xtpb-testimonial-{{column}} {{control_nav_style}} " data-col="{{column}}" data-shownav="{{control_nav}}" data-showdots="{{control_dots}}" data-autoplay="{{autoplay_option}}" data-speed="{{data.speed_option}}">
						<# if( data.testimonial_layout == "layouttwo" ){ #>
							<#  _.forEach(data.testimonial_list, function(value, key) { #>
								<div class="xtpb-testimonial-content">
									<div class="xtpb-testimonial-content-in">
										<# if ( value.image ) { #>
											<div class="xtpb-testimonial-image">
												<img class="xtpb-testimonial-addon-img" src="{{value.image.url}}">
											</div>
										<# } #>
										<div class="xtpb-testimonial-introtext">{{{value.introtext}}}</div>
										<div class="xtpb-testimonial-title">
											<# if( value.name_link ){ #>
												<h4 class="xtpb-testimonial-name"><a {{ value.name_link.link ? "href="+value.name_link.link : "" }} {{ value.name_link.window ? "target=_blank" : "" }} {{ value.name_link.nofolow ? "rel=nofolow" : "" }}>{{{value.name}}}</a></h4>
											<# } else { #>
												<h4 class="xtpb-testimonial-name">{{{value.name}}}</h4>
											<# } #>
											<span class="xtpb-testimonial-designation">{{{value.designation}}}</span>
										</div>
									</div>
								</div>
							<# }); #>
						<# } else if( data.testimonial_layout == "layoutthree" ){ #>

							<#  _.forEach(data.testimonial_list, function(value, key) { #>
								<div class="xtpb-testimonial-content">
									<div class="xtpb-testimonial-content-in">
										<div class="xtpb-testimonial-introtext">{{{value.introtext}}}</div>
										<# if ( value.image ) { #>
											<div class="xtpb-testimonial-image">
												<img class="xtpb-testimonial-addon-img" src="{{value.image.url}}">
											</div>
										<# } #>
										<div class="xtpb-testimonial-title">
											<# if( value.name_link ){ #>
												<h4 class="xtpb-testimonial-name"><a {{ value.name_link.link ? "href="+value.name_link.link : "" }} {{ value.name_link.window ? "target=_blank" : "" }} {{ value.name_link.nofolow ? "rel=nofolow" : "" }}>{{{value.name}}}</a></h4>
											<# } else { #>
												<h4 class="xtpb-testimonial-name">{{{value.name}}}</h4>
											<# } #>
											<span class="xtpb-testimonial-designation">{{{value.designation}}}</span>
										</div>
									</div>
								</div>
							<# }); #>

						<# } else if( data.testimonial_layout == "layoutfour" ){ #>

								<#  _.forEach(data.testimonial_list, function(value, key) { #>
									<div class="xtpb-testimonial-content">
										<div class="xtpb-testimonial-content-in">
											<span class="xtpb-testimonial-quote xtpb-font-quote"></span>
											<div class="xtpb-testimonial-introtext">{{{value.introtext}}}</div>
											<# if ( value.image ) { #>
												<div class="xtpb-testimonial-image">
													<img class="xtpb-testimonial-addon-img" src="{{value.image.url}}">
												</div>
											<# } #>
											<div class="xtpb-testimonial-title">
												<# if( value.name_link ){ #>
													<h4 class="xtpb-testimonial-name"><a {{ value.name_link.link ? "href="+value.name_link.link : "" }} {{ value.name_link.window ? "target=_blank" : "" }} {{ value.name_link.nofolow ? "rel=nofolow" : "" }}>{{{value.name}}}</a></h4>
												<# } else { #>
													<h4 class="xtpb-testimonial-name">{{{value.name}}}</h4>
												<# } #>
												<span class="xtpb-testimonial-designation">{{{value.designation}}}</span>
											</div>
										</div>
									</div>
								<# }); #>

						<# } else {  #>

							<#  _.forEach(data.testimonial_list, function(value, key) { #>
								<div class="xtpb-testimonial-content">
									<div class="xtpb-testimonial-content-in">
										<div class="xtpb-testimonial-introtext">{{{value.introtext}}}</div>
										<div class="xtpb-testimonial-information">
											<# if ( value.image ) { #>
												<div class="xtpb-testimonial-image">
													<img class="xtpb-testimonial-addon-img" src="{{value.image.url}}">
												</div>
											<# } #>
											<div class="xtpb-testimonial-title">
												<# if( value.name_link ){ #>
													<h4 class="xtpb-testimonial-name"><a {{ value.name_link.link ? "href="+value.name_link.link : "" }} {{ value.name_link.window ? "target=_blank" : "" }} {{ value.name_link.nofolow ? "rel=nofolow" : "" }}>{{{value.name}}}</a></h4>
												<# } else { #>
													<h4 class="xtpb-testimonial-name">{{{value.name}}}</h4>
												<# } #>
												<span class="xtpb-testimonial-designation">{{{value.designation}}}</span>
											</div>
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
