<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Flip_Box{

	public function get_name(){
		return 'xtpb_flip_box';
	}
	public function get_title(){
		return __( '翻转框', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-flip';
	}

	// Flip Box Settings Fields
	public function get_settings() {

		$settings = array(
			// flip options
			'flip_bhave' => array(
				'type' => 'select',
				'title' => __('行为','xintheme-pagebuilder'),
				'values' => array(
					'hover' => __('悬停','xintheme-pagebuilder'),
					'click' => __('点击','xintheme-pagebuilder'),
				),
				'section' => __( '翻转选项', 'xintheme-pagebuilder' ),
				'std' => 'hover',
			),	
			'flip_style' => array(
				'type' => 'select',
				'title' => __('样式','xintheme-pagebuilder'),
				'values' => array(
					'rotate_style' => __('旋转','xintheme-pagebuilder'),
					'slide_style' => __('滑动','xintheme-pagebuilder'),
					'fade_style' => __('淡入淡出','xintheme-pagebuilder'),
					'threeD_style' => __('3D','xintheme-pagebuilder'),
				),
				'section' => __( '翻转选项', 'xintheme-pagebuilder' ),
				'std' => 'rotate_style',
			),			
			'flip_direction' => array(
				'type' => 'select',
				'title' => __('方向','xintheme-pagebuilder'),
				'values' => array(
					'flip_top' => __('从上方','xintheme-pagebuilder'),
					'flip_bottom' => __('从下方','xintheme-pagebuilder'),
					'flip_left' => __('从左侧','xintheme-pagebuilder'),
					'flip_right' => __('从右侧','xintheme-pagebuilder'),
				),
				'section' => __( '翻转选项', 'xintheme-pagebuilder' ),
				'std' => 'flip_right',
			),	
			'flip_height' => array(
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
						'max' => 600,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'responsive' => true,
				'section' => __( '翻转选项', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flipbox-panel, {{SELECTOR}} .threeD-item { height: {{data.flip_height}}; }'
			),	
			'flip_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'responsive' => true,
				'section' => __( '翻转选项', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flipbox-front.flip-box:before, {{SELECTOR}} .threeD-flip-front:before,{{SELECTOR}} .xtpb-flipbox-back.flip-box:before, {{SELECTOR}} .threeD-flip-back:before { border-radius: {{data.flip_radius}}; }'
			),		

			//front view	
			'front_title' => array(
				'type' => 'text',
				'title' => __('标题','xintheme-pagebuilder'),
				'section' => __( '正面内容', 'xintheme-pagebuilder' ),
				'std' => __( '产品设计', 'xintheme-pagebuilder' ),
			),
			'front_heading_selector' => array(
				'type' => 'select',
				'title' => __('标题标签','xintheme-pagebuilder'),
				'values' => array(
					'h1' => 'h1',
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
					'span' => 'span',
					'p' => 'p',
					'div' => 'div',
				),
				'section' => __( '正面内容', 'xintheme-pagebuilder' ),
				'std' => 'h4',
			),
			'front_introtext' => array(
				'type' => 'textarea',
				'title' => __('内容','xintheme-pagebuilder'),
				'std' => __( '在这里添加翻转框正面的简短说明内容。', 'xintheme-pagebuilder' ),
				'section' => __( '正面内容', 'xintheme-pagebuilder' ),
			),
			'front_show_icon' => array(
				'type' => 'switch',
				'title' => __('显示图标','xintheme-pagebuilder'),
				'section' => __( '正面内容', 'xintheme-pagebuilder' ),
				'std' => '1'
			),
			'front_icon_list' => array(
				'type' => 'icon',
				'title' => __('图标列表','xintheme-pagebuilder'),
				'std' => 'xtpb-font-puzzle',
				'depends' => array(array('front_show_icon', '!=', '0')),
				'section' => __( '正面内容', 'xintheme-pagebuilder' ),
			),
			'front_image_upload' => array(
				'type' => 'media',
				'title' => __('上传特色图片','xintheme-pagebuilder'),
				'std'=> array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-small.jpg' ) ,
				'depends' => array(array('front_show_icon', '!=', '1')),
				'section' => __( '正面内容', 'xintheme-pagebuilder' ),
			),
			'front_image_alt' => array(
				'type' => 'text',
				'title' => __('图片替代文本','xintheme-pagebuilder'),
				'std' => __( '图片', 'xintheme-pagebuilder' ),
				'depends' => array(array('front_show_icon', '!=', '1')),
				'section' => __( '正面内容', 'xintheme-pagebuilder' ),
			),
			'front_bg' => array(
				'type' => 'background',
				'disableHover' => true,
				'title' => __('背景','xintheme-pagebuilder'),
				'std' => array( 'bgType' => '颜色', 'bgColor' => '#3375dc' ),
				'section' => __( '正面内容', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flipbox-front.flip-box:before, {{SELECTOR}} .threeD-flip-front:before'
			),
			'front_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'section' => __( '正面内容', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flipbox-front, {{SELECTOR}} .threeD-flip-front{ text-align: {{data.front_align}}; }'
			),

			//Back view	
			'back_title' => array(
				'type' => 'text',
				'title' => __('标题','xintheme-pagebuilder'),
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
				'std' => __( '创意想法', 'xintheme-pagebuilder' ),
			),
			'back_heading_selector' => array(
				'type' => 'select',
				'title' => __('标题标签','xintheme-pagebuilder'),
				'values' => array(
					'h1' => 'h1',
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
					'span' => 'span',
					'p' => 'p',
					'div' => 'div',
				),
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
				'std' => 'h4',
			),
			'back_introtext' => array(
				'type' => 'textarea',
				'title' => __('内容','xintheme-pagebuilder'),
				'std' => __( '在这里添加翻转框背面的详细说明内容。', 'xintheme-pagebuilder' ),
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
			),
			'back_button_text' => array(
				'type' => 'text',
				'title' => __('按钮文字','xintheme-pagebuilder'),
				'std' => __( '按钮', 'xintheme-pagebuilder' ),
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
			),
			'back_btn_link' => array(
				'type' => 'link',
				'title' => __('按钮链接','xintheme-pagebuilder'),
				'std' => array( 'link'=>'#','window'=>false,'nofolow'=>false ),
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
				
			),
			'back_show_icon' => array(
				'type' => 'switch',
				'title' => __('显示图标','xintheme-pagebuilder'),
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
				'std' => '1'
			),
			'back_icon_list' => array(
				'type' => 'icon',
				'title' => __('图标列表','xintheme-pagebuilder'),
				'std' => 'xtpb-font-suitcase',
				'depends' => array(array('back_show_icon', '!=', '0')),
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
			),
			'back_image_upload' => array(
				'type' => 'media',
				'title' => __('上传特色图片','xintheme-pagebuilder'),
				'std' => array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-small.jpg'),
				'depends' => array(array('back_show_icon', '!=', '1')),
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
			),
			'back_image_alt' => array(
				'type' => 'text',
				'title' => __('图片替代文本','xintheme-pagebuilder'),
				'std' => __( '图片', 'xintheme-pagebuilder' ),
				'depends' => array(array('back_show_icon', '!=', '1')),
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
			),
			//back background		
			'back_bg' => array(
				'type' => 'background',
				'title' => __('背景','xintheme-pagebuilder'),
				'std' => array( 'bgType' => '颜色', 'bgColor' => '#22cc96' ),
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flipbox-back.flip-box:before, {{SELECTOR}} .threeD-flip-back:before'
			),
			'back_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'section' => __( '背面内容', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flipbox-back, .threeD-flip-back { text-align: {{data.back_align}}; }'
			),
			//front style options
			'front_title_color' => array(
				'type' => 'color2',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => true,
				'std' => array( 'colorType' => 'color', 'clip' => true, 'colorColor' => '' ),
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-title'
			),
			'front_title_style' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'22px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-title',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'front_title_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-title { margin: {{data.front_title_margin}}; }'
			),

			//Intro text
			'intro_separator' => array(
				'type' => 'separator',
				'title' => __( '介绍文本样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
			),
			'front_intro_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-intro { color: {{data.front_intro_color}}; }'
			),
			'front_intro_style' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'14px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-intro',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'front_intro_margin' => array(
				'type' => 'dimension',
				'title' => __('介绍文本外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-flip-front-intro { margin: {{data.front_intro_margin}}; }'
			),
			
			//image
			'image_separator' => array(
				'type' => 'separator',
				'title' => __( '图片样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
			),
			'front_img_width' => array(
				'type' => 'slider',
				'title' => __('图片宽度','xintheme-pagebuilder'),
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
				'responsive' => true,
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'depends' => array(array('show_icon', '!=', '1')),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-img img { width: {{data.front_img_width}}; }'
			),
			'front_img_height' => array(
				'type' => 'slider',
				'title' => __('图片高度','xintheme-pagebuilder'),
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
				'responsive' => true,
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'depends' => array(array('show_icon', '!=', '1')),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-img img { height: {{data.front_img_height}}; }'
			),
			'front_border_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'responsive' => true,
				'unit' => array( 'px','%','em' ),
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'depends' => array(array('show_icon', '!=', '1')),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-img img { border-radius: {{data.front_border_radius}}; }'
			),
			'front_img_margin' => array(
				'type' => 'dimension',
				'title' => __('图片外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-flip-front-img img { margin: {{data.front_img_margin}}; }'
			),

			// icon
			'icon_separator' => array(
				'type' => 'separator',
				'title' => __( '图标样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'depends' => array(array('show_icon', '!=', '0')),
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
			),
			'front_icon_size' => array(
				'type' => 'slider',
				'title' => __('图标尺寸','xintheme-pagebuilder'),
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
						'md' => '36px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-icon i { font-size: {{data.front_icon_size}}; }'
			),
			'front_icon_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 5,
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
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-icon { width: {{data.front_icon_width}}; }'
			),
			'front_icon_height' => array(
				'type' => 'slider',
				'title' => __('图标高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 5,
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
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => array(
					'{{SELECTOR}} .xtpb-flip-front-icon { height: {{data.front_icon_height}}; }',
					'{{SELECTOR}} .xtpb-flip-front-icon i { line-height: {{data.front_icon_height}}; }',
				)
			),
			'front_icon_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}}  .xtpb-flip-front-icon { border-radius: {{data.front_icon_radius}}; }'
			),
			'front_icon_color' => array(
				'type' => 'color',
				'title' => __('图标颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-icon { color: {{data.front_icon_color}}; }'
			),
			'front_icon_hcolor' => array(
				'type' => 'color',
				'title' => __('图标悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-icon:hover { color: {{data.front_icon_hcolor}}; }'
			),
			'front_icon_bg' => array(
				'type' => 'color2',
				'title' => __('图标背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-icon { background: {{data.front_icon_bg}}; }'
			),
			'front_icon_hover_bg' => array(
				'type' => 'color2',
				'title' => __('图标悬停背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}} .xtpb-flip-front-icon:hover{ background: {{data.front_icon_hover_bg}}; }'
			),

			'front_icon_margin' => array(
				'type' => 'dimension',
				'title' => __('图标外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '正面样式', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-flip-front-icon { margin: {{data.front_icon_margin}}; }'
			),


			//back style options
			'back_title_color' => array(
				'type' => 'color',
				'title' => __('背面颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-title { color: {{data.back_title_color}}; }'
			),
			'back_title_style' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'22px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-title',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'back_title_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-title { margin: {{data.back_title_margin}}; }'
			),

			//Intro text
			'back_intro_separator' => array(
				'type' => 'separator',
				'title' => __( '介绍文本样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
			),
			'back_intro_color' => array(
				'type' => 'color',
				'title' => __('背面介绍文字颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-intro { color: {{data.back_intro_color}}; }'
			),
			'back_intro_style' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'14px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-intro',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'back_intro_margin' => array(
				'type' => 'dimension',
				'title' => __('介绍文本外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-flip-back-intro { margin: {{data.back_intro_margin}}; }'
			),

			//image	
			'back_img_separator' => array(
				'type' => 'separator',
				'title' => __( '图片样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
			),	
			'back_img_width' => array(
				'type' => 'slider',
				'title' => __('图片宽度','xintheme-pagebuilder'),
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
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '1')),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-img img { width: {{data.back_img_width}}; }'
			),
			'back_img_height' => array(
				'type' => 'slider',
				'title' => __('图片高度','xintheme-pagebuilder'),
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
				'responsive' => true,
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'depends' => array(array('show_icon', '!=', '1')),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-img img { height: {{data.back_img_height}}; }'
			),
			'back_border_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'responsive' => true,
				'unit' => array( 'px','%','em' ),
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'depends' => array(array('show_icon', '!=', '1')),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-img img { border-radius: {{data.back_border_radius}}; }'
			),
			'back_img_margin' => array(
				'type' => 'dimension',
				'title' => __('图片外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-flip-back-img img { margin: {{data.back_img_margin}}; }'
			),			
			

			// icon
			'back_icon_separator' => array(
				'type' => 'separator',
				'title' => __( '图标样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'depends' => array(array('show_icon', '!=', '0')),
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
			),
			'back_icon_size' => array(
				'type' => 'slider',
				'title' => __('图标尺寸','xintheme-pagebuilder'),
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
						'md' => '36px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-icon i { font-size: {{data.back_icon_size}}; }'
			),
			'back_icon_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
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
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-icon { width: {{data.back_icon_width}}; }'
			),
			'back_icon_height' => array(
				'type' => 'slider',
				'title' => __('图标高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 5,
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
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => array(
					'{{SELECTOR}} .xtpb-flip-back-icon { height: {{data.back_icon_height}}; }',
					'{{SELECTOR}} .xtpb-flip-back-icon i { line-height: {{data.back_icon_height}}; }',
				)
			),
			'back_icon_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}}  .xtpb-flip-back-icon { border-radius: {{data.back_icon_radius}}; }'
			),
			'back_icon_color' => array(
				'type' => 'color',
				'title' => __('图标颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-icon { color: {{data.back_icon_color}}; }'
			),
			'back_icon_hcolor' => array(
				'type' => 'color',
				'title' => __('图标悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-icon:hover { color: {{data.back_icon_hcolor}}; }'
			),
			'back_icon_bg' => array(
				'type' => 'color2',
				'title' => __('图标背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-icon { background: {{data.back_icon_bg}}; }'
			),
			'back_icon_hover_bg' => array(
				'type' => 'color2',
				'title' => __('图标悬停背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('show_icon', '!=', '0')),
				'selector' => '{{SELECTOR}} .xtpb-flip-back-icon:hover{ background: {{data.back_icon_hover_bg}}; }'
			),

			'back_icon_margin' => array(
				'type' => 'dimension',
				'title' => __('图标外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-flip-back-icon { margin: {{data.back_icon_margin}}; }'
			),


			//button
			'back_btn_separator' => array(
				'type' => 'separator',
				'title' => __( '按钮样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
			),
			'back_button_typo' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'14px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'back_button_color' => array(
				'type' => 'color',
				'title' => __('按钮颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn{ color: {{data.back_button_color}}; }'
			),
			'back_button_hcolor' => array(
				'type' => 'color',
				'title' => __('按钮悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn:hover { color: {{data.back_button_hcolor}}; }'
			),
			'back_button_bg' => array(
				'type' => 'color2',
				'title' => __('按钮背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn{ background: {{data.back_button_bg}}; }'
			),
			'back_button_hover_bg' => array(
				'type' => 'color2',
				'title' => __('按钮悬停背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn:hover{ background: {{data.back_button_hover_bg}}; }'
			),
			'back_btn_border' => array(
				'type' => 'border',
				'title' => __( '按钮边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn'
			),
			'button_border_hcolor' => array(
				'type' => 'border',
				'title' => __( '按钮悬停边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn:hover'
			),
			'button_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '48px', 'right' => '48px', 'bottom' => '48px', 'left' => '48px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn { border-radius: {{data.button_radius}}; }'
			),
			'button_hradius' => array(
				'type' => 'dimension',
				'title' => __('悬停边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '48px', 'right' => '48px', 'bottom' => '48px', 'left' => '48px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn:hover { border-radius: {{data.button_hradius}}; }'
			),
			'button_padding' => array(
				'type' => 'dimension',
				'title' => __('按钮内边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn{ padding: {{data.button_padding}}; }'
			),
			'button_margin' => array(
				'type' => 'dimension',
				'title' => __('按钮外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '背面样式', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-back-view-btn{ margin: {{data.button_margin}}; }'
			),
		);

		return $settings;
	}


	// Flip Box Render HTML
	public function render($data = null){
		$settings 			= $data['settings'];

		$front_title 					= isset($settings['front_title']) ? sanitize_text_field( $settings['front_title'] ) : '';
		$front_heading_selector 		= isset($settings["front_heading_selector"]) ? sanitize_text_field( $settings["front_heading_selector"] ) : '';
		$front_introtext 				= isset($settings['front_introtext']) ? sanitize_text_field( $settings['front_introtext'] ) : '';
		$front_show_icon 	    		= isset($settings['front_show_icon']) ? sanitize_text_field( $settings['front_show_icon'] ) : '';
		$front_icon_list 				= isset($settings['front_icon_list']) ? sanitize_text_field( $settings['front_icon_list'] ) : '';
		$front_image_upload 			= isset($settings['front_image_upload']) ? $settings['front_image_upload'] : array();
		$front_image_alt 				= isset($settings['front_image_alt']) ? sanitize_text_field( $settings['front_image_alt'] ) : '';

		$back_title 					= isset($settings['back_title']) ? sanitize_text_field( $settings['back_title'] ) : '';
		$back_heading_selector 			= isset($settings["back_heading_selector"]) ? sanitize_text_field( $settings["back_heading_selector"] ) : '';
		$back_introtext 				= isset($settings['back_introtext']) ? sanitize_text_field( $settings['back_introtext'] ) : '';
		$back_button_text 	    		= isset($settings['back_button_text']) ? sanitize_text_field( $settings['back_button_text'] ) : '';
		$back_btn_link 					= isset($settings['back_btn_link']) ? $settings['back_btn_link'] : array();
		$back_show_icon 				= isset($settings['back_show_icon']) ? sanitize_text_field( $settings['back_show_icon'] ) : '';
		$back_icon_list 				= isset($settings['back_icon_list']) ? sanitize_text_field( $settings['back_icon_list'] ) : '';
		$back_image_upload 	    		= isset($settings['back_image_upload']) ? $settings['back_image_upload'] : array();
		$back_image_alt 	    		= isset($settings['back_image_alt']) ? sanitize_text_field( $settings['back_image_alt'] ) : '';

		$flip_bhave 	    			= isset($settings['flip_bhave']) ? sanitize_text_field( $settings['flip_bhave'] ) : '';
		$flip_style 	    			= isset($settings['flip_style']) ? sanitize_text_field( $settings['flip_style'] ) : 'rotate_style';
		$flip_direction 	    		= isset($settings['flip_direction']) ? sanitize_text_field( $settings['flip_direction'] ) : '';


		$output = $img_front_url = $img_back_url = $data_front_title = $data_front_intro = $data_back_title = $data_back_intro = $data_back_button = $data_front_media = $data_back_media = '' ;

		if ( ! empty( $front_title ) ) {
			$data_front_title .= '<' .esc_attr($front_heading_selector). ' class="xtpb-flip-front-title">' . wp_kses_post( $front_title ) .'</' . esc_attr($front_heading_selector) . '>';
		}

		if ( ! empty( $front_introtext ) ) {
			$data_front_intro .= '<div class="xtpb-flip-front-intro">' . wp_kses_post( $front_introtext ) .'</div>';
		}

		if ( $front_show_icon == 1 ) {	
			if ( ! empty( $front_icon_list ) ) {
				$data_front_media .= '<div class="xtpb-flip-front-icon">';
					$data_front_media .= '<i class="' . esc_attr( $front_icon_list ) . '"></i>';
				$data_front_media .= '</div>';
			}
		} else {
			if ( ! empty( $front_image_upload['url'] ) ) {
				$img_front_url = $front_image_upload['url'];	
				$data_front_media .= '<div class="xtpb-flip-front-img">';
				$data_front_media .= '<img src="' . esc_url( $img_front_url ) . '" alt="'. $front_image_alt .'">';
				$data_front_media .= '</div>';
			}
		}

		if ( ! empty( $back_title ) ) {
			$data_back_title .= '<' . esc_attr( $back_heading_selector ) . ' class="xtpb-flip-back-title">' . wp_kses_post($back_title) .'</' . esc_attr($back_heading_selector) . '>';
		}
		if ( ! empty( $back_introtext ) ) {
			$data_back_intro .= '<div class="xtpb-flip-back-intro">' . wp_kses_post($back_introtext) .'</div>';
		}
		if ( ! empty( $back_btn_link['link'] ) ) {
			$btntarget = $back_btn_link['window'] ? 'target=_blank' : 'target=_self';
			$btnnofolow = $back_btn_link['nofolow'] ? 'rel=nofolow' : "";
			$data_back_button  = '<a ' . esc_attr( $btntarget ) . ' href="'.esc_url($back_btn_link['link']).'" '.esc_attr($btnnofolow).' class="xtpb-back-view-btn">' . $back_button_text  . '</a>';
		}

		if ( $back_show_icon == 1 ) {	
			if ( ! empty( $back_icon_list ) ) {
				$data_back_media .= '<div class="xtpb-flip-back-icon">';
					$data_back_media .= '<i class="' . esc_attr( $back_icon_list ) . '"></i>';
				$data_back_media .= '</div>';
			}
		} else {
			if ( ! empty( $back_image_upload['url'] ) ) {
				$img_back_url = $back_image_upload['url'];	
				$data_back_media .= '<div class="xtpb-flip-back-img">';
				$data_back_media .= '<img src="' . esc_url( $img_back_url ) . '" alt="'. $back_image_alt .'">';
				$data_back_media .= '</div>';
			}
		}		

		if ($flip_style != '') {
        if ($flip_style == 'slide_style') {
            $flip_style = 'slide_style';
        } elseif ($flip_style == 'fade_style') {
            $flip_style = 'fade_style';
        } elseif ($flip_style == 'threeD_style') {
            $flip_style = 'threeD_style';
        } else {
          $flip_style = 'rotate_style';
        }
		}
		

		$output .= '<div class="xtpb-flip-box-content ' . esc_attr($flip_style) . ' ' . esc_attr($flip_direction) . ' flipon-' . esc_attr($flip_bhave) . '">';
				if ($flip_style == 'threeD_style') {
					$output .= '<div class="threeD-content-wrap flip">';
					$output .= '<div class="threeD-item">';
					$output .= '<div class = "threeD-flip-front">';
					$output .= '<div class = "threeD-content-inner">';
					$output .= $data_front_media;
					$output .= $data_front_title;
					$output .= $data_front_intro;
					$output .= '</div>';
					$output .= '</div>';
					$output .= '<div class = "threeD-flip-back">';
					$output .= '<div class = "threeD-content-inner">';
					$output .= $data_back_media;
					$output .= $data_back_title;
					$output .= $data_back_intro;
					$output .= $data_back_button;
					$output .= '</div>';
					$output .= '</div >';
					$output .= '</div>'; //.threeD-item
					$output .= '</div>'; //.threeD-content-wrap
				} else {
					$output .= '<div class="xtpb-flipbox-panel">';
					$output .= '<div class="xtpb-flipbox-front flip-box">';
					$output .= '<div class="flip-box-inner">';
					$output .= $data_front_media;
					$output .= $data_front_title;
					$output .= $data_front_intro;
					$output .= '</div>'; //.flip-box-inner
					$output .= '</div>'; //.front
					$output .= '<div class="xtpb-flipbox-back flip-box">';
					$output .= '<div class="flip-box-inner">';
					$output .= $data_back_media;
					$output .= $data_back_title;
					$output .= $data_back_intro;
					$output .= $data_back_button;
					$output .= '</div>'; //.flip-box-inner
					$output .= '</div>'; //.back
					$output .= '</div>'; //.xtpb-flipbox-panel
				}
		$output  .= '</div>';//xtpb-flip-box-content

		return $output;
	}

	// Flip Box Template
	public function getTemplate(){
		$output = '
		<#
		var front_heading = data.front_heading_selector ? data.front_heading_selector : "h4";
		var back_heading = data.back_heading_selector ? data.back_heading_selector : "h4";
		var data_front_intro = data_back_intro = data_front_title = data_back_title = data_back_button = data_front_media = data_back_media = flip_style = "";
		
        if(data.front_title){
			data_front_title += "<"+front_heading+" class=\'xtpb-flip-front-title\'>"+data.front_title+"</"+front_heading+">";
		}
		if(data.front_introtext){
			data_front_intro += "<div class=\'xtpb-flip-front-intro\'>"+data.front_introtext+"</div>";
		}

		if( data.front_show_icon == 1 ) {	
			if( data.front_icon_list ){
				data_front_media  += "<div class=\'xtpb-flip-front-icon\'>";
				data_front_media  += "<i class=\'"+data.front_icon_list+"\'></i>";
				data_front_media  += "</div>";
			}

		} else {
			if ( data.front_image_upload.url ) {
				data_front_media  += "<div class=\'xtpb-flip-front-img\'>";
				data_front_media  += "<img src=\'"+data.front_image_upload.url+"\'>";
				data_front_media  += "</div>";
			} 
		}

		if(data.back_title){
			data_back_title += "<"+back_heading+" class=\'xtpb-flip-back-title\'>"+data.back_title+"</"+back_heading+">";
		}

		if(data.back_introtext){
			data_back_intro += "<div class=\'xtpb-flip-back-intro\'>"+data.back_introtext+"</div>";
		}

		if( data.back_show_icon == 1 ) {	
			if( data.back_icon_list ){
				data_back_media  += "<div class=\'xtpb-flip-back-icon\'>";
				data_back_media  += "<i class=\'"+data.back_icon_list+"\'></i>";
				data_back_media  += "</div>";
			}

		} else {
			if ( data.back_image_upload.url ) {
				data_back_media  += "<div class=\'xtpb-flip-back-img\'>";
				data_back_media  += "<img src=\'"+data.back_image_upload.url+"\'>";
				data_back_media  += "</div>";
			} 
		}

		if( data.back_btn_link && data.back_btn_link.link ) {
			data_back_button += "<a class=\'xtpb-back-view-btn\' href=\'"+ data.back_btn_link+"\'>"+data.back_button_text+"</a>";
		}

		var flip_style = (data.flip_style) ? data.flip_style : "rotate_style";
		
		if (flip_style != "") {
			if (flip_style == "slide_style") {
				flip_style = "slide-flipbox";
			} else if (flip_style == "fade_style") {
				flip_style = "fade-flipbox";
			} else if (flip_style == "threeD_style") {
				flip_style = "threeD_style";
			}
		}
		#>

		<div class="xtpb-flip-box-content {{{data.flip_style}}} {{{data.flip_direction}}} flipon-{{{data.flip_bhave}}}">
				<#
				if ( flip_style == "threeD_style" ) { #>
					<div class="threeD-content-wrap flip">
						<div class="threeD-item">
							<div class="threeD-flip-front">
								<div class="threeD-content-inner">
									{{{data_front_media}}}
									{{{data_front_title}}}
									{{{data_front_intro}}}
								</div>
							</div>
							<div class="threeD-flip-back">
								<div class="threeD-content-inner">
									{{{data_back_media}}}
									{{{data_back_title}}}
									{{{data_back_intro}}}
									<# if( data.back_btn_link && data.back_btn_link.link ) { #>
									{{{data_back_button}}}
									<# } #>
								</div>
							</div>
						</div> 
					</div>
				<# } else { #>
					<div class="xtpb-flipbox-panel">
						<div class="xtpb-flipbox-front flip-box">
							<div class="flip-box-inner">
								{{{data_front_media}}}
								{{{data_front_title}}}
								{{{data_front_intro}}}
							</div> 
						</div>
						<div class="xtpb-flipbox-back flip-box">
							<div class="flip-box-inner">
								{{{data_back_media}}}
								{{{data_back_title}}}
								{{{data_back_intro}}}
								<# if( data.back_btn_link && data.back_btn_link.link ) { #>
								{{{data_back_button}}}
								<# } #>
							</div> 
						</div>
					</div> 
				<# } #>
			</div>
		';
		return $output;
	}

}
