<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Feature_Box{

	public function get_name(){
		return 'xtpb_feature_box';
	}
	public function get_title(){
		return __( '特色框', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-rocket';
	}

	// Feature Box Settings Fields
	public function get_settings() {

		$settings = array(
			'layout' => array(
				'type' => 'radioimage',
				'title' => __( '布局', 'xintheme-pagebuilder' ),
				'values' => array(
					'one' =>  XTPB_DIR_URL.'addons/feature_box/img/feature-img1.png',
					'two' =>  XTPB_DIR_URL.'addons/feature_box/img/feature-img2.png',
					'three' =>  XTPB_DIR_URL.'addons/feature_box/img/feature-img3.png',
					'four' =>  XTPB_DIR_URL.'addons/feature_box/img/feature-img4.png',
					'five' =>  XTPB_DIR_URL.'addons/feature_box/img/feature-img5.png',
					'six' =>  XTPB_DIR_URL.'addons/feature_box/img/feature-img6.png',
					'seven' =>  XTPB_DIR_URL.'addons/feature_box/img/feature-img7.png',
				),
				'std' => 'one'
			),
			'title' => array(
				'type' => 'text',
				'title' => __('标题','xintheme-pagebuilder'),
				'std' => __( '特色框', 'xintheme-pagebuilder' ),
			),
			'heading_selector' => array(
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
				'std' => 'h3',
			),
			'title_link' => array(
				'type' => 'link',
				'std' => array( 'link'=>'','window'=>false,'nofolow'=>false ),
				'title' => __('链接','xintheme-pagebuilder'),
				'depends' => array(array('title', '!=', '')),
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
			//subtitle
			'subtitle' => array(
				'type' => 'text',
				'title' => __('副标题','xintheme-pagebuilder'),
				'std' => '',
			),
			'subheading_selector' => array(
				'type' => 'select',
				'title' => __('副标题标签','xintheme-pagebuilder'),
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
				'std' => 'h4',
			),
			'subtitle_animation' => array(
				'type' => 'animation',
				'title' => __( '副标题动画', 'xintheme-pagebuilder' ),
				'std' => array(
					'name' => 'fadeIn',  
					'delay' => '300',    
					'duration' => '400'
				),
			),
			//Number 
			'number' => array(
				'type' => 'text',
				'title' => __('数字','xintheme-pagebuilder'),
				'depends' => array(array('layout', '=', array('seven'))),
				'std' => '01',
			),
			'number_animation' => array(
				'type' => 'animation',
				'title' => __( '数字动画', 'xintheme-pagebuilder' ),
				'depends' => array(array('layout', '=', array('seven'))),
				'std' => array(
					'name' => 'fadeIn',   
					'delay' => '300',     
					'duration' => '400' 
				),
			),
			//intro
			'introtext' => array(
				'type' => 'textarea',
				'title' => __('介绍文本','xintheme-pagebuilder'),
				'std' => __( '在这里添加特色框的简短介绍内容。', 'xintheme-pagebuilder' ),
			),
			'intro_animation' => array(
				'type' => 'animation',
				'title' => __( '介绍文本动画', 'xintheme-pagebuilder' ),
				'std' => array(
					'name'      => 'fadeIn',
					'delay'     => '300',
					'duration'  => '400'
				),
			),
			'show_image' => array(
				'type'      => 'switch',
				'title'     => __('显示图片','xintheme-pagebuilder'),
				'std'       => '0',
                'depends' => array(array('layout', '!=', array('seven')))
			),
			'icon_list' => array(
				'type'      => 'icon',
				'title'     => __('图标列表','xintheme-pagebuilder'),
				'std'       => 'xtpb-font-rocket',
				'depends'   => array(array('show_image', '!=', '1'), array('layout', '!=', array('seven'))),
			),
			'icon_animation' => array(
				'type' => 'animation',
				'title' => __( '图标动画', 'xintheme-pagebuilder' ),
				'std' => array(
					'name' => 'fadeIn',   
					'delay' => '300',     
					'duration' => '400' 
				),
				'depends' => array(array('show_image', '!=', '1')),
			),
			'image_upload' => array(
				'type' => 'media',
				'title' => __('上传特色图片','xintheme-pagebuilder'),
				'std' => array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-small.jpg' ),
				'depends' => array(array('show_image', '!=', '0')),
			),
			'image_animation' => array(
				'type' => 'animation',
				'title' => __( '图片动画', 'xintheme-pagebuilder' ),
				'std' => array(
					'name' => 'fadeIn',   
					'delay' => '300',    
					'duration' => '400' 
				),
				'depends' => array(array('show_image', '!=', '0')),
			),

			'button_text' => array(
				'type' => 'text',
				'title' => __('按钮文字','xintheme-pagebuilder'),
				'std' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_link' => array(
				'type' => 'link',
				'title' => __('按钮链接','xintheme-pagebuilder'),
				'std' => array( 'link'=>'#','window'=>false,'nofolow'=>false )
			),
			'btn_icon_list' => array(
				'type' => 'icon',
				'title' => __('按钮图标','xintheme-pagebuilder'),
				'std' => ''
			),
			'icon_position' => array(
				'type' => 'select',
				'title' => __('按钮图标位置','xintheme-pagebuilder'),
				'depends' => array(array('btn_icon_list', '!=', '')),
				'values' => array(
					'left' => __('左侧','xintheme-pagebuilder'),
					'right' => __('右侧','xintheme-pagebuilder'),
				),
				'std' => 'left',
			),
			'feature_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content{ text-align: {{data.feature_align}}; }',
				'depends' => array(array('layout', '=', array('one','six'))),
			),

			//box title
			'box_title_color' => array(
				'type' => 'color2',
				'title' => __( '颜色', 'xintheme-pagebuilder' ),
				'clip' => true,
				'std' => array( 'colorType' => 'color', 'clip' => true, 'colorColor' => '' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-title, {{SELECTOR}} .xtpb-feature-box-title a',
				'tab' => 'style',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
			),
			'box_title_hcolor' => array(
				'type' => 'color2',
				'title' => __( '悬停颜色', 'xintheme-pagebuilder' ),
				'clip' => true,
				'std' => array( 'colorType' => 'color', 'clip' => true, 'colorColor' => '' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-title:hover, {{SELECTOR}} .xtpb-feature-box-title a:hover',
				'tab' => 'style',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
			),
			'box_title_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'20px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '500',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-title',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'title_margin' => array(
				'type'      => 'dimension',
				'title'     => __('外边距','xintheme-pagebuilder'),
				'tab'       => 'style',
				'section'   => __( '标题', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit'      => array( 'px','em','%' ),
				'selector'  => '{{SELECTOR}} .xtpb-feature-box-title, {{SELECTOR}} .feature-icontitle-six .xtpb-feature-box-title { margin: {{data.title_margin}}; }'
			),
			'title_padding' => array(
				'type'      => 'dimension',
				'title'     => __('内边距','xintheme-pagebuilder'),
				'tab'       => 'style',
				'section'   => __( '标题', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit'      => array( 'px','em','%' ),
				'selector'  => '{{SELECTOR}} .xtpb-feature-box-title, {{SELECTOR}} .feature-icontitle-six .xtpb-feature-box-title { padding: {{data.title_padding}}; }'
			),

			//box sub title
			'box_subtitle_color' => array(
				'type' => 'color2',
				'title' => __( '颜色', 'xintheme-pagebuilder' ),
				'clip' => true,
				'std' => array( 'colorType' => 'color', 'clip' => true, 'colorColor' => '' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-subtitle',
				'tab' => 'style',
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
			),
			'box_subtitle_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'20px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '500',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-subtitle',
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'subtitle_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-subtitle { margin: {{data.subtitle_margin}}; }'
			),
			'subtitle_padding' => array(
				'type' => 'dimension',
				'title' => __('内边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-subtitle { padding: {{data.subtitle_padding}}; }'
			),

			//介绍文本
			'intro_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '介绍文本', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-intro { color: {{data.intro_color}}; }'
			),
			'intro_size' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-feature-box-intro',
				'section' => __( '介绍文本', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'intro_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '介绍文本', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-intro { margin: {{data.intro_margin}}; }'
			),
			'intro_padding' => array(
				'type' => 'dimension',
				'title' => __('内边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '介绍文本', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-intro { padding: {{data.intro_padding}}; }'
			),

			//Number text
			'number_color' => array(
				'type' => 'color2',
				'title' => __( '颜色', 'xintheme-pagebuilder' ),
				'clip' => true,
				'std' => array( 'colorType' => 'color', 'clip' => true, 'colorColor' => '' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-number',
				'tab' => 'style',
				'section' => __( '数字', 'xintheme-pagebuilder' ),
				'depends' => array(array('layout', '=', array('seven'))),
			),
			'number_horizontal' => array(
				'type' => 'slider',
				'title' => __('水平','xintheme-pagebuilder'),
				'std' => array(
						'md' => '-25px',
						'sm' => '',
						'xs' => '',
					),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => -10,
							'max' => 10,
							'step' => .1,
						),
						'px' => array(
							'min' => -150,
							'max' => 150,
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
				'section' => __( '数字', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-number { top: {{data.number_horizontal}}; }',
				'depends' => array(array('layout', '=', array('seven'))),
			),
			'number_vertical' => array(
				'type' => 'slider',
				'title' => __('垂直','xintheme-pagebuilder'),
				'std' => array(
						'md' => '15px',
						'sm' => '',
						'xs' => '',
					),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => -10,
							'max' => 10,
							'step' => .1,
						),
						'px' => array(
							'min' => -100,
							'max' => 150,
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
				'section' => __( '数字', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-number { left: {{data.number_vertical}}; }',
				'depends' => array(array('layout', '=', array('seven'))),
			),
			'number_size' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-feature-box-number',
				'section' => __( '数字', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'depends' => array(array('layout', '=', array('seven'))),
			),
			'number_padding' => array(
				'type' => 'dimension',
				'title' => __('内边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '数字', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-number { padding: {{data.number_padding}}; }',
				'depends' => array(array('layout', '=', array('seven'))),
			),

			// icon
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
						'md' => '48px',
						'sm' => '',
						'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-icon i { font-size: {{data.icon_size}}; }'
			),
			'icon_line_height' => array(
				'type' => 'slider',
				'title' => __('行高','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'max' => 300,
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'selector' =>'{{SELECTOR}} .xtpb-feature-box-icon i { line-height: {{data.icon_line_height}}; }'
			),
			'icon_width' => array(
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
						'max' => 300,
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
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-icon { width: {{data.icon_width}}; }'
			),
			'icon_height' => array(
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
						'max' => 300,
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
				'selector' => '{{SELECTOR}} .xtpb-feature-box-icon { height: {{data.icon_height}}; }',
			),
			'icon_color' => array(
				'type' => 'color2',
				'title' => __( '颜色', 'xintheme-pagebuilder' ),
				'clip' => true,
				'std' => array( 'colorType' => 'color', 'clip' => true, 'colorColor' => '' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-icon i',
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
			),
			'icon_hcolor' => array(
				'type' => 'color2',
				'title' => __( '悬停颜色', 'xintheme-pagebuilder' ),
				'clip' => true,
				'std' => array( 'colorType' => 'color', 'clip' => true, 'colorColor' => '' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content:hover .xtpb-feature-box-icon i',
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
			),

			'icon_bg' => array(
				'type' => 'color2',
				'title' => __( '背景', 'xintheme-pagebuilder' ),
				'clip' => false,
				'selector' => '{{SELECTOR}} .xtpb-feature-box-icon',
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
			),

			'icon_hover_bg' => array(
				'type' => 'color2',
				'title' => __( '悬停背景', 'xintheme-pagebuilder' ),
				'clip' => false,
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content:hover .xtpb-feature-box-icon',
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
			),

			'icon_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-icon'
			),
			'icon_border_hcolor' => array(
				'type' => 'border',
				'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content:hover .xtpb-feature-box-icon'
			),
			'icon_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-icon',
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
			),
			'icon_hboxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '悬停阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content:hover .xtpb-feature-box-icon',
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
			),
			'icon_radius' => array(
				'type' => 'slider',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-icon { border-radius: {{data.icon_radius}}; }'
			),
			'icon_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-icon { margin: {{data.icon_margin}}; }'
			),

			//image
			'img_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 30,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 400,
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
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-img img { width: {{data.img_width}}; max-width:{{data.img_width}} }'
			),
			'img_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 30,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 400,
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
				'selector' => '{{SELECTOR}} .xtpb-feature-box-img img { height: {{data.img_height}}; }'
			),
			'border_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-img img { border-radius: {{data.border_radius}}; }'
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
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-img img'
			),
			'img_border_hcolor' => array(
				'type' => 'border',
				'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-img img:hover'
			),
			'img_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-img img',
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
			),
			'img_hboxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '悬停阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
				),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-img img:hover',
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
			),
			'img_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图片', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-feature-box-img img { margin: {{data.img_margin}}; }'
			),

			//button
			'button_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'button_color' => array(
				'type' => 'color',
				'title' => __( '颜色', 'xintheme-pagebuilder' ),
				'std'       => '#ffffff',
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons { color: {{data.button_color}}; }',
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_hcolor' => array(
				'type' => 'color',
				'title' => __( '悬停颜色', 'xintheme-pagebuilder' ),
				'std'       => '#ffffff',
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons:hover { color: {{data.button_hcolor}}; }',
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_bg' => array(
				'type' => 'color2',
				'title' => __( '背景', 'xintheme-pagebuilder' ),
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#0080FE',
					'clip' => false
				),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons',
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_hover_bg' => array(
				'type' => 'color2',
				'title' => __( '悬停背景', 'xintheme-pagebuilder' ),
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#0074e6',
					'clip' => false
				),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons:before',
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons'
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
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons:hover'
			),
			'buttom_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'std' => array(
					'md' => array( 'top' => '4px', 'right' => '4px', 'bottom' => '4px', 'left' => '4px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons { border-radius: {{data.buttom_radius}}; }'
			),
			'buttom_hradius' => array(
				'type' => 'dimension',
				'title' => __('悬停边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'std' => array(
					'md' => array( 'top' => '4px', 'right' => '4px', 'bottom' => '4px', 'left' => '4px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons:hover { border-radius: {{data.buttom_hradius}}; }'
			),
			'button_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons { padding: {{data.button_padding}}; }',
			),
			'btn_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-feature-box-content .xtpb-btn-addons { margin: {{data.btn_margin}}; }'
			),
			//six
			'bar_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'std' => array(
					'md' => '50%',
					'sm' => '',
					'xs' => '',
				),
				'unit' => array( '%','px','em' ),
				'responsive' => true,
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
				'tab' => 'style',
				'section' => __( '条形样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .feature-icontitle-six .xtpb-feature-box-title:after { width: {{data.bar_width}}; }',
				'depends' => array(array('layout', '=', array('six'))),
			),
			'bar_height' => array(
				'type' => 'slider',
				'title' => __('条形高度','xintheme-pagebuilder'),
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
					'md' => '2px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '条形样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .feature-icontitle-six .xtpb-feature-box-title:after { height: {{data.bar_height}}; }',
				'depends' => array(array('layout', '=', array('six'))),
			),
			'bar_horizontal' => array(
				'type' => 'slider',
				'title' => __('条形水平位移','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'range' => array(
						'em' => array(
							'min' => -25,
							'max' => 25,
							'step' => .1,
						),
						'px' => array(
							'min' => -200,
							'max' => 200,
							'step' => 1,
						),
						'%' => array(
							'min' => -100,
							'max' => 100,
							'step' => 1,
						),
					),
				'std' => array(
						'md' => '100%',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '条形样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .feature-icontitle-six .xtpb-feature-box-title:after { top: {{data.bar_horizontal}}; }',
				'depends' => array(array('layout', '=', array('six'))),
			),
			'bar_vertical' => array(
				'type' => 'slider',
				'title' => __('条形垂直位移','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
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
						'md' => '0%',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '条形样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .feature-icontitle-six .xtpb-feature-box-title:after { left: {{data.bar_vertical}}; }',
				'depends' => array(array('layout', '=', array('six'))),
			),
			'bar_background' => array(
				'type' => 'color2',
				'title' => __( '条形背景', 'xintheme-pagebuilder' ),
				'clip' => false,
				'selector' => '{{SELECTOR}} .feature-icontitle-six .xtpb-feature-box-title:after',
				'tab' => 'style',
				'section' => __( '条形样式', 'xintheme-pagebuilder' ),
				'depends' => array(array('layout', '=', array('six'))),
			),
		);

		return $settings;
	}


	// Feature box Render HTML
	public function render($data = null){
		$settings 				= $data['settings'];
		$layout 				= isset( $settings['layout'] ) ? sanitize_text_field( $settings['layout'] ) : '';
		$title 					= isset( $settings['title'] ) ? sanitize_text_field( $settings['title'] ) : '';
		$selector 				= isset( $settings["heading_selector"] ) ? sanitize_text_field( $settings["heading_selector"] ) : '';
		$subtitle 				= isset( $settings['subtitle'] ) ? sanitize_text_field( $settings['subtitle'] ) : '';
		$subselector 			= isset( $settings["subheading_selector"] ) ? sanitize_text_field( $settings["subheading_selector"] ) : '';
		$subtitle_animation 	= isset( $settings["subtitle_animation"] ) ? $settings["subtitle_animation"] : array();
		$number 				= isset( $settings["number"] ) ? sanitize_text_field( $settings["number"] ) : '';
		$number_animation 		= isset( $settings["number_animation"] ) ? $settings["number_animation"] : array();
		$title_link 			= isset( $settings['title_link'] ) ? $settings['title_link'] : array();
		$introtext 				= isset( $settings['introtext'] ) ? sanitize_text_field( $settings['introtext'] ) : '';
		$intro_animation 		= isset( $settings['intro_animation'] ) ? $settings['intro_animation'] : array();
		$show_image 			= isset( $settings['show_image'] ) ? sanitize_text_field( $settings['show_image'] ) : '';
		$icon_list 				= isset( $settings['icon_list'] ) ? sanitize_text_field( $settings['icon_list'] ) : '';
		$icon_animation 		= isset( $settings['icon_animation'] ) ? $settings['icon_animation'] : array();
		$image_upload 			= isset( $settings['image_upload'] ) ? $settings['image_upload'] : array();
		$image_animation 		= isset( $settings['image_animation'] ) ? $settings['image_animation'] : array();
		$title_animation 	    = isset( $settings['title_animation'] ) ? $settings['title_animation'] : array();
		$button_text 			= isset( $settings["button_text"] ) ? sanitize_text_field( $settings["button_text"] ) : '';
		$button_link 			= isset( $settings['button_link'] ) ? $settings['button_link'] : array();
		$btn_icon_list 			= isset( $settings["btn_icon_list"] ) ? sanitize_text_field( $settings["btn_icon_list"] ) : '';
		$icon_position 			= isset( $settings["icon_position"] ) ? sanitize_text_field( $settings["icon_position"] ) : '';
		$feature_align 			= isset( $settings["feature_align"] ) ? sanitize_text_field( $settings["feature_align"] ) : '';

		$output = $img_url = $data_media = $data_title = $data_number = $data_subtitle = $data_intro = $btndata = $classlist = $button = '' ;

		$classlist .= (isset($style) && $style) ? ' xtpb-btn-' . $style : '';

		if ( $show_image == 1 ) {	
			if ( ! empty($image_upload['url']) ) {
				$img_url = $image_upload['url'];
				if ( isset( $image_animation['itemOpen'] ) && $image_animation['itemOpen'] ) {
					$data_media .= '<div class="xtpb-feature-box-img xtpb-wow xtpb-animated '.esc_attr($image_animation['name']).'" data-wow-duration="'.esc_attr($image_animation['duration']).'ms" data-wow-delay="'.esc_attr($image_animation['delay']).'ms">';
					$data_media .= '<img src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $title ) . '">';
					$data_media .= '</div>';
				} else {
					$data_media .= '<div class="xtpb-feature-box-img">';
					$data_media .= '<img src="' . esc_url( $img_url ) . '" alt="'. esc_attr( $title ) .'">';
					$data_media .= '</div>';
				}
			}
		} else {
			if ( ! empty( $icon_list ) ) {
				if ( isset( $icon_animation['itemOpen'] ) && $icon_animation['itemOpen'] ) {
					$data_media .= '<div class="xtpb-feature-box-icon xtpb-wow xtpb-animated ' . esc_attr( $icon_animation['name'] ) . '" data-wow-duration="'.esc_attr($icon_animation['duration']).'ms" data-wow-delay="'.esc_attr($icon_animation['delay']).'ms">';
						$data_media .= '<i class="' . esc_attr( $icon_list ) . '"></i>';
					$data_media .= '</div>';
				} else {
					$data_media .= '<div class="xtpb-feature-box-icon">';
						$data_media .= '<i class="' . esc_attr( $icon_list ) . '"></i>';
					$data_media .= '</div>';
				}
			}
		}

		$selector = $selector ? $selector : "h3";
		$subselector = $subselector ? $subselector : "h4";

		if ( ( $title ) ) {
			if ( ! empty( $title_link['link'] ) ) {
				$target = $title_link['window'] ? 'target=_blank' : "";
				$nofolow = $title_link['nofolow'] ? 'rel=nofolow' : "";
				if ( isset( $title_animation['itemOpen'] )  && $title_animation['itemOpen'] ) {
					$data_title .= '<' .esc_attr($selector). ' class="xtpb-feature-box-title xtpb-wow xtpb-animated '.esc_attr($title_animation['name']).'" data-wow-duration="'.esc_attr($title_animation['duration']).'ms" data-wow-delay="'.esc_attr($title_animation['delay']).'ms"><a '.esc_attr($nofolow).' href="'.esc_url($title_link['link']).'" '.esc_attr($target).'>' . wp_kses_post($title) .'</a></' . esc_attr($selector) . '>';
				} else {
					$data_title .= '<' .esc_attr($selector). ' class="xtpb-feature-box-title"><a '.esc_attr($nofolow).' href="'.esc_url($title_link['link']).'" '.esc_attr($target).'>' . wp_kses_post($title) .'</a></' . esc_attr($selector) . '>';
				}
			} else {
				if ( isset( $title_animation['itemOpen'] ) && $title_animation['itemOpen'] ) {
					$data_title .= '<' .esc_attr($selector). ' class="xtpb-feature-box-title xtpb-wow xtpb-animated '.esc_attr($title_animation['name']).'" data-wow-duration="'.esc_attr($title_animation['duration']).'ms" data-wow-delay="'.esc_attr($title_animation['delay']).'ms">' . wp_kses_post($title) .'</' . esc_attr($selector) . '>';
				} else {
					$data_title .= '<' .esc_attr($selector). ' class="xtpb-feature-box-title">' . wp_kses_post($title) .'</' . esc_attr($selector) . '>';
				}
			}
		}

		if ( ! empty( $subtitle ) ) {
			if ( isset( $subtitle_animation['itemOpen'] ) && $subtitle_animation['itemOpen'] ) {
				$data_subtitle .= '<' . esc_attr($subselector) . ' class="xtpb-feature-box-subtitle xtpb-wow xtpb-animated ' . esc_attr( $subtitle_animation['name'] ) . '" data-wow-duration="' . esc_attr( $subtitle_animation['duration'] ) . 'ms" data-wow-delay="'.esc_attr($subtitle_animation['delay']).'ms">' . wp_kses_post($subtitle) .'</' . esc_attr($subselector) . '>';
			} else {
				$data_subtitle .= '<' .esc_attr($subselector). ' class="xtpb-feature-box-subtitle">' . wp_kses_post($subtitle) .'</' . esc_attr($subselector) . '>';
			}
		}

		if ( ! empty( $number ) ) {
			if ( isset( $number_animation['itemOpen'] ) && $number_animation['itemOpen'] ) {
				$data_number .= '<span class="xtpb-feature-box-number xtpb-wow xtpb-animated ' . esc_attr( $number_animation['name'] ).'" data-wow-duration="'.esc_attr($number_animation['duration']).'ms" data-wow-delay="'.esc_attr($number_animation['delay']).'ms">';
					$data_number .= $number;
				$data_number .= '</span>';
			} else {
				$data_number .= '<span class="xtpb-feature-box-number">';
					$data_number .= $number;
				$data_number .= '</span>';
			}
		}

		if ( ! empty( $introtext ) ) {
			if( isset( $intro_animation['itemOpen'] )  && $intro_animation['itemOpen'] ) {
				$data_intro .= '<div class="xtpb-feature-box-intro xtpb-wow xtpb-animated '.esc_attr($intro_animation['name']).'" data-wow-duration="'.esc_attr($intro_animation['duration']).'ms" data-wow-delay="'.esc_attr($intro_animation['delay']).'ms">';
					$data_intro .= $introtext;
				$data_intro .= '</div>';
			} else {
				$data_intro .= '<div class="xtpb-feature-box-intro">';
					$data_intro .= $introtext;
				$data_intro .= '</div>';
			}
		}

		if ( $icon_position == 'left' ) {
			$button = ( esc_attr($btn_icon_list ) ) ? '<i class="' . esc_attr($btn_icon_list) . '"></i> <span>' . esc_attr($button_text).'</span>' : '<span>' . esc_attr($button_text). '</span>';
		} else {
			$button = ( esc_attr( $btn_icon_list ) ) ? '<span>' . esc_attr($button_text) . '</span> <i class="' . esc_attr($btn_icon_list) . '"></i><span>' : esc_attr($button_text).'</span>';
		}

		if ( ! empty( $button_link['link'] ) ) {
			$btntarget = !empty($button_link['window']) ? 'target=_blank' : 'target=_self';
			$btnnofolow = !empty($button_link['nofolow']) ? 'rel=nofolow' : "";
			$btndata  = '<a '.esc_attr($btntarget).' href="'.esc_url($button_link['link']).'" '.esc_attr($btnnofolow).' class="xtpb-btn-addons '.$classlist.'">' . $button  . '</a>';
		}

		$output  .= '<div class="xtpb-feature-box-content">';
			if ( $layout == "two" ) {
				$output  .= '<div class="feature-box-left">';
				$output  .= '<div class="feature-box-left-media">';
				$output  .= $data_media;
				$output  .= '</div>';
				$output  .= '<div class="feature-box-left-body">';
				$output  .= $data_title;
				$output  .= $data_subtitle;
				$output  .= $data_intro;
				$output  .= $btndata;
				$output  .= '</div>';
				$output  .= '</div>';
			} elseif ( $layout == "three" ) {
				$output  .= '<div class="feature-box-right">';	
				$output  .= '<div class="feature-box-right-body">';
				$output  .= $data_title;
				$output  .= $data_subtitle;
				$output  .= $data_intro;
				$output  .= $btndata;
				$output  .= '</div>';
				$output  .= '<div class="feature-box-right-media">';
				$output  .= $data_media;
				$output  .= '</div>';
				$output  .= '</div>';
			}
			elseif ( $layout == "four" ) {
				$output  .= '<div class="feature-icontitle-wrap">';	
				$output  .= '<div class="feature-icontitle-left xtpb-clearfix">';
				$output  .= $data_media;
				$output  .= $data_title;
				$output  .= '</div>';
				$output  .= '<div class="feature-icontitle-body">';	
				$output  .= $data_subtitle;
				$output  .= $data_intro;
				$output  .= $btndata;
				$output  .= '</div>';
				$output  .= '</div>';
			} elseif ( $layout == "five" ) {
				$output  .= '<div class="feature-icontitle-wrap">';	
				$output  .= '<div class="feature-icontitle-right xtpb-clearfix">';
				$output  .= $data_media;
				$output  .= $data_title;
				$output  .= '</div>';
				$output  .= '<div class="feature-icontitle-body">';	
				$output  .= $data_subtitle;
				$output  .= $data_intro;
				$output  .= $btndata;
				$output  .= '</div>';
				$output  .= '</div>';
			} elseif ( $layout == "six" ) {
				$output  .= '<div class="feature-icontitle-six">';	
				$output  .= $data_media;
				$output  .= '<div class="feature-title-six">';
				$output  .= $data_title;
				$output  .= '</div>';
				$output  .= $data_subtitle;
				$output  .= $data_intro;
				$output  .= $btndata;
				$output  .= '</div>';
			} elseif ( $layout == "seven" ) {
				$output  .= '<div class="feature-icontitle-seven">';	
				$output  .= '<div class="feature-title-seven">';
				$output  .= $data_number;
				$output  .= $data_title;
				$output  .= '</div>';
				$output  .= $data_subtitle;
				$output  .= $data_intro;
				$output  .= $btndata;
				$output  .= '</div>';
			} else {
				$output  .= $data_media;
				$output  .= $data_title;
				$output  .= $data_subtitle;
				$output  .= $data_intro;
				$output  .= $btndata;
			}
		$output  .= '</div>';//xtpb-feature-box-content

		return $output;
	}

	// Feature Bix Template
	public function getTemplate(){
		$output = '
		<#
		var data_media = "";
		var data_title = "";
		var data_subtitle = "";
		var data_number = "";
		var data_intro = "";
		var classList = "";
		classList += " xtpb-btn-"+data.style;

		var heading_selector = data.heading_selector ? data.heading_selector : "h3";
		var subheading_selector = data.subheading_selector ? data.subheading_selector : "h4";
		if(data.title){
			if( !_.isEmpty( data.link ) ){
				if(data.title_animation.itemOpen) {
					data_title += "<"+heading_selector+" class=\'xtpb-feature-box-title xtpb-wow xtpb-animated " +data.title_animation.name + "\' data-wow-duration="+data.title_animation.duration+"ms data-wow-delay="+data.title_animation.delay+"ms><a href=\'"+ data.link+"\'>"+data.title+"</a></"+heading_selector+">";
				} else {
					data_title += "<"+heading_selector+" class=\'xtpb-feature-box-title\'><a href=\'"+ data.link+"\'>"+data.title+"</a></"+heading_selector+">";
				}
			} else {
				if(data.title_animation.itemOpen) {
					data_title += "<"+heading_selector+" class=\'xtpb-feature-box-title xtpb-wow xtpb-animated " +data.title_animation.name + "\' data-wow-duration="+data.title_animation.duration+"ms data-wow-delay="+data.title_animation.delay+"ms>"+data.title+"</"+heading_selector+">";
				} else {
					data_title += "<"+heading_selector+" class=\'xtpb-feature-box-title\'>"+data.title+"</"+heading_selector+">";
				}
			}
		}

		if(data.subtitle){
			if(data.subtitle_animation.itemOpen) {
				data_subtitle += "<"+data.subheading_selector+" class=\'xtpb-feature-box-subtitle xtpb-wow xtpb-animated" +data.subtitle_animation.name + "\' data-wow-duration="+data.subtitle_animation.duration+"ms data-wow-delay="+data.subtitle_animation.delay+"ms>"+data.subtitle+"</"+data.subheading_selector+">";
			} else {
				data_subtitle += "<"+data.subheading_selector+" class=\'xtpb-feature-box-subtitle\'>"+data.subtitle+"</"+data.subheading_selector+">";
			}
		}
		
		if(data.number){
			if(data.number_animation.itemOpen) {
				data_number += "<span class=\'xtpb-feature-box-number xtpb-wow xtpb-animated " +data.number_animation.name + "\' data-wow-duration="+data.number_animation.duration+"ms data-wow-delay="+data.number_animation.delay+"ms>";
				data_number += data.number;
				data_number += "</span>";
			} else {
				data_number += "<span class=\'xtpb-feature-box-number\'>";
				data_number += data.number;
				data_number += "</span>";
			}
		}

		if(data.introtext){
			if(data.intro_animation.itemOpen) {
				data_intro += "<div class=\'xtpb-feature-box-intro xtpb-wow xtpb-animated " +data.intro_animation.name + "\' data-wow-duration="+data.intro_animation.duration+"ms data-wow-delay="+data.intro_animation.delay+"ms>";
				data_intro += data.introtext;
				data_intro += "</div>";
			} else {
				data_intro += "<div class=\'xtpb-feature-box-intro\'>";
				data_intro += data.introtext;
				data_intro += "</div>";
			}
		}

		if( data.show_image == 1 ) {	
			if ( data.image_upload.url ) {
				if(data.image_animation.itemOpen) {
					data_media  += "<div class=\'xtpb-feature-box-img xtpb-wow xtpb-animated " +data.image_animation.name + "\' data-wow-duration="+data.image_animation.duration+"ms data-wow-delay="+data.image_animation.delay+"ms>";
					data_media  += "<img src=\'"+data.image_upload.url+"\'>";
					data_media  += "</div>";
				} else {
					data_media  += "<div class=\'xtpb-feature-box-img\'>";
					data_media  += "<img src=\'"+data.image_upload.url+"\'>";
					data_media  += "</div>";
				}
			} 
		} else {
			if( data.icon_list ){
				if(data.icon_animation.itemOpen) {
					data_media  += "<div class=\'xtpb-feature-box-icon xtpb-wow xtpb-animated " +data.icon_animation.name + "\' data-wow-duration="+data.icon_animation.duration+"ms data-wow-delay="+data.icon_animation.delay+"ms>";
					data_media  += "<i class=\'"+data.icon_list+"\'></i>";
					data_media  += "</div>";
				} else {
					data_media  += "<div class=\'xtpb-feature-box-icon\'>";
					data_media  += "<i class=\'"+data.icon_list+"\'></i>";
					data_media  += "</div>";
				}
			}
		}	
		#>

		<div class="xtpb-feature-box-content">
				<# if ( data.layout == "two" ) { #>
					<div class="feature-box-left">
						<div class="feature-box-left-media">
							{{{data_media}}}
						</div>
						<div class="feature-box-left-body">
							{{{data_title}}}
							{{{data_subtitle}}}
							{{{data_intro}}}
							<# if( !__.isEmpty(data.button_link.link) ) { #>
								<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons {{classList}}">
									<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
								</a>
							<# } #>
						</div>
					</div>
				<# } else if(data.layout == "three"){ #>		
					<div class="feature-box-right">
					<div class="feature-box-right-body">
						{{{data_title}}}
						{{{data_subtitle}}}
						{{{data_intro}}}
						<# if( !__.isEmpty(data.button_link.link) ) { #>
							<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons {{classList}}">
								<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
							</a>
						<# } #>
					</div>
					<div class="feature-box-right-media">
						{{{data_media}}}
					</div>
				</div>
				<# } else if(data.layout == "four"){ #>		
					<div class="feature-icontitle-wrap">
						<div class="feature-icontitle-left xtpb-clearfix">
							{{{data_media}}}
							{{{data_title}}}
						</div>
						<div class="feature-icontitle-body">
							{{{data_subtitle}}}
							{{{data_intro}}}
							<# if( !__.isEmpty(data.button_link.link) ) { #>
								<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons {{classList}}">
									<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
								</a>
							<# } #>
						</div>
					</div>
					<# } else if(data.layout == "five"){ #>		
						<div class="feature-icontitle-wrap">
							<div class="feature-icontitle-right xtpb-clearfix">
								{{{data_title}}}
								{{{data_media}}}
							</div>
							<div class="feature-icontitle-body">
								{{{data_subtitle}}}
								{{{data_intro}}}
								<# if( !__.isEmpty(data.button_link.link) ) { #>
									<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons {{classList}}">
										<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
									</a>
								<# } #>
							</div>
						</div>
					<# } else if(data.layout == "six"){ #>		
						<div class="feature-icontitle-six">
							{{{data_media}}}
							<div class="feature-title-six">
							{{{data_title}}}
							</div>
							{{{data_subtitle}}}
							{{{data_intro}}}
							<# if( !__.isEmpty(data.button_link.link) ) { #>
								<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons {{classList}}">
									<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
								</a>
							<# } #>
						</div>
				<# } else if(data.layout == "seven"){ #>		
					<div class="feature-icontitle-seven">
						<div class="feature-title-seven">
						{{{data_number}}}
						{{{data_title}}}
						</div>
						{{{data_subtitle}}}
						{{{data_intro}}}
						<# if( !__.isEmpty(data.button_link.link) ) { #>
							<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons {{classList}}">
								<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
							</a>
						<# } #>
					</div>
				<# } else { #>			
					{{{data_media}}}
					{{{data_title}}}
					{{{data_subtitle}}}
					{{{data_intro}}}
					<# if( !__.isEmpty(data.button_link.link) ) { #>
						<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons {{classList}}">
							<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
						</a>
					<# } #>
				<# } #>
			</div>
		';
		return $output;
	}

}
