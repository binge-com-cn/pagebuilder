<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Image_Hover{

	public function get_name(){
		return 'xtpb_image_hover';
	}
	public function get_title(){
		return __( '图片悬停', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-image3';
	}

	// Feature Box Settings Fields
	public function get_settings() {

		$settings = array(
			'layout' => array(
				'type' => 'radioimage',
				'title' => __('选择布局','xintheme-pagebuilder'),
				'values' => array(
					'one' =>  XTPB_DIR_URL.'addons/image_hover/img/image_hover1.png',
					'three' =>  XTPB_DIR_URL.'addons/image_hover/img/image_hover2.png',
					'four' =>  XTPB_DIR_URL.'addons/image_hover/img/image_hover3.png',
					'five' =>  XTPB_DIR_URL.'addons/image_hover/img/image_hover4.png',
					'six' =>  XTPB_DIR_URL.'addons/image_hover/img/image_hover5.png',
					'seven' =>  XTPB_DIR_URL.'addons/image_hover/img/image_hover6.png',
					'eight' =>  XTPB_DIR_URL.'addons/image_hover/img/image_hover7.png',
				),
				'std' => 'one'
			),
			'content_position' => array(
				'type' => 'select',
				'title' => __('内容位置','xintheme-pagebuilder'),
				'values' => array(
					'topleft' => __('左上','xintheme-pagebuilder'),
					'topcenter' => __('上中','xintheme-pagebuilder'),
					'topright' => __('右上','xintheme-pagebuilder'),
					'middletop' => __('左中','xintheme-pagebuilder'),
					'middlecenter' => __('正中','xintheme-pagebuilder'),
					'middleright' => __('右中','xintheme-pagebuilder'),
					'bottomleft' => __('左下','xintheme-pagebuilder'),
					'bottomcenter' => __('下中','xintheme-pagebuilder'),
					'bottomright' => __('右下','xintheme-pagebuilder'),
				),
				'depends' => array(array('layout', '=', array('one'))),
				'std' => 'bottomleft',
			),

			'title_show' => array(
				'type' => 'switch',
				'title' => __('显示标题','xintheme-pagebuilder'),
				'std' => '1',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
			),
			'title' => array(
				'type' => 'text',
				'title' => __('标题','xintheme-pagebuilder'),
				'std' =>   __( '图片悬停标题', 'xintheme-pagebuilder' ),
				'depends' => array(array('title_show', '=', '1')),
				'section' => __( '标题', 'xintheme-pagebuilder' ),
			),
			'heading_selector' => array(
				'type' => 'select',
				'title' => __('标题 HTML 元素','xintheme-pagebuilder'),
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
				'depends' => array(array('title_show', '=', '1')),
				'section' => __( '标题', 'xintheme-pagebuilder' ),
			),
			'title_link' => array(
				'type' => 'link',
				'std' => array( 'link'=>'','window'=>false,'nofolow'=>false ),
				'title' => __('添加标题链接','xintheme-pagebuilder'),
				'depends' => array(array('title_show', '=', '1')),
				'section' => __( '标题', 'xintheme-pagebuilder' ),
			),

			//subtitle
			'subtitle_show' => array(
				'type' => 'switch',
				'title' => __('显示副标题','xintheme-pagebuilder'),
				'std' => '1',
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
			),
			'subtitle' => array(
				'type' => 'textarea',
				'title' => __('副标题','xintheme-pagebuilder'),
				'std' =>   __( '这里填写图片悬停的说明文字', 'xintheme-pagebuilder' ),
				'depends' => array(array('subtitle_show', '=', '1')),
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
			),
			'subheading_selector' => array(
				'type' => 'select',
				'title' => __('副标题 HTML 元素','xintheme-pagebuilder'),
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
				'depends' => array(array('subtitle_show', '=', '1')),
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
			),

			'button_show' => array(
				'type' => 'switch',
				'title' => __('隐藏按钮','xintheme-pagebuilder'),
				'std' => '0',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_text' => array(
				'type' => 'text',
				'title' => __('按钮文字','xintheme-pagebuilder'),
				'std' =>   __( '了解更多', 'xintheme-pagebuilder' ),
				'depends' => array(array('button_show', '=', '1')),
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_link' => array(
				'type' => 'link',
				'title' => __('添加按钮链接','xintheme-pagebuilder'),
				'std' => array( 'link'=>'#','window'=>false,'nofolow'=>false ),
				'depends' => array(array('button_show', '=', '1')),
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'btn_icon_list' => array(
				'type' => 'icon',
				'title' => __('按钮图标','xintheme-pagebuilder'),
				'depends' => array(array('button_show', '=', '1')),
				'std' => '',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
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
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'front_bg' => array(
				'type' => 'background',
				'title' => __('背景','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-bg',
				'std' => array(
					'bgType' => 'image',
					'bgImage' => array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-large.jpg'),
				),
				'section' => __( '背景', 'xintheme-pagebuilder' ),
                'disableHover' => true
			),
            'overlay_bg' => array(
                'type' => 'color',
                'title' => __('遮罩层背景','xintheme-pagebuilder'),
                'std' => '#0180fe',
                'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-image-hover-overlay { background: {{data.overlay_bg}}; }',
                'section' => __( '背景', 'xintheme-pagebuilder' ),
            ),
            'back_bg' => array(
                'type' => 'color',
                'title' => __('遮罩层悬停背景','xintheme-pagebuilder'),
                'std' => '#377133',
                'selector' => '{{SELECTOR}} .xtpb-image-hover-content:hover .xtpb-image-hover-overlay{ background-color: {{data.back_bg}}; }',
                'section' => __( '背景', 'xintheme-pagebuilder' ),
            ),

			//box title
			'image_hover_effect' => array(
				'type' => 'select',
				'title' => __('图片动画效果','xintheme-pagebuilder'),
				'values' => array(
					'slideright' => __('从右侧滑入','xintheme-pagebuilder'),
					'slideleft' => __('从左侧滑入','xintheme-pagebuilder'),
					'slidetop' => __('从上方滑入','xintheme-pagebuilder'),
					'slidebottom' => __('从下方滑入','xintheme-pagebuilder'),
					'zoomin' => __('缩小','xintheme-pagebuilder'),
					'zoomout' => __('放大','xintheme-pagebuilder'),
				),
				'tab'=> 'style',
				'std' => 'slideleft',
			),
			'image_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 70,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 800,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '300px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab'=> 'style',
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content { height: {{data.image_height}}; }'
			),
			'wrap_padding' => array(
				'type' => 'dimension',
				'title' => __('容器内边距','xintheme-pagebuilder'),
				'tab'=> 'style',
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content { padding: {{data.wrap_padding}}; }'
			),

			//title
			'box_title_color' => array(
				'type' => 'color',
				'title' => __( '标题颜色', 'xintheme-pagebuilder' ),
				'std'  => '#fff',
				'selector' => '{{SELECTOR}} .xtpb-image-hover-title, {{SELECTOR}} .xtpb-image-hover-title a { color: {{data.box_title_color}}; }',
				'tab'=> 'style',
				'section' => __('标题','xintheme-pagebuilder'),
				'depends' => array(array('title_show', '=', '1')),
			),
			'box_title_hcolor' => array(
				'type' => 'color',
				'title' => __( '标题悬停颜色', 'xintheme-pagebuilder' ),
				'std'  => '#fff',
				'selector' => '{{SELECTOR}} .xtpb-image-hover-title a:hover { color: {{data.box_title_hcolor}};}',
				'tab'=> 'style',
				'section' => __('标题','xintheme-pagebuilder'),
				'depends' => array(array('title_show', '=', '1')),
			),
			'box_title_bg' => array(
				'type' => 'color',
				'title' => __( '标题背景颜色', 'xintheme-pagebuilder' ),
				'std'  => '#0180fe',
				'selector' => '{{SELECTOR}} .xtpb-image-hover-four .xtpb-image-hover-intro-four .xtpb-image-hover-title { background: {{data.box_title_bg}}; }',
				'tab'=> 'style',
				'section' => __('标题','xintheme-pagebuilder'),
				'depends' => array(
				    array('layout', '=', array('four')),
                    array('title_show', '=', '1')
                ),
			),
			'box_title_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std'		=> array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'20px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '500',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-title',
				'section' => __('标题','xintheme-pagebuilder'),
				'tab'=> 'style',
				'depends' => array(array('title_show', '=', '1')),
			),
			'title_margin' => array(
				'type' => 'dimension',
				'title' => __('标题外边距','xintheme-pagebuilder'),
				'tab'=> 'style',
				'section' => __('标题','xintheme-pagebuilder'),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'std' => array(
					'md' => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'depends' => array(array('title_show', '=', '1')),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-title, {{SELECTOR}} .feature-icontitle-six .xtpb-image-hover-title { margin: {{data.title_margin}}; }'
			),
			'title_padding' => array(
				'type' => 'dimension',
				'title' => __('标题内边距','xintheme-pagebuilder'),
				'tab'=> 'style',
				'section' => __('标题','xintheme-pagebuilder'),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'depends' => array(array('layout', '!=', array('four'))),
				'depends' => array(array('title_show', '=', '1')),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-title, {{SELECTOR}} .feature-icontitle-six .xtpb-image-hover-title, {{SELECTOR}} .xtpb-image-hover-four .xtpb-image-hover-intro-four .xtpb-image-hover-title { padding: {{data.title_padding}}; }'
			),

			//sub title
			'box_subtitle_color' => array(
				'type' => 'color',
				'title' => __( '副标题颜色', 'xintheme-pagebuilder' ),
				'clip'		=> true,
				'std'  => '#fff',
				'selector' => '{{SELECTOR}} .xtpb-image-hover-subtitle { color: {{data.box_subtitle_color}}; }',
				'tab'=> 'style',
				'section' => __('副标题','xintheme-pagebuilder'),
				'depends' => array(array('subtitle_show', '=', '1')),
			),
			'box_subtitle_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std'		=> array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'20px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '500',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-subtitle',
				'section' => __('副标题','xintheme-pagebuilder'),
				'tab'=> 'style',
				'depends' => array(array('subtitle_show', '=', '1')),
			),
			'subtitle_margin' => array(
				'type' => 'dimension',
				'title' => __('副标题外边距','xintheme-pagebuilder'),
				'tab'=> 'style',
				'section' => __('副标题','xintheme-pagebuilder'),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'std' => array(
					'md' => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-subtitle { margin: {{data.subtitle_margin}}; }',
				'depends' => array(array('subtitle_show', '=', '1')),
			),
			'subtitle_padding' => array(
				'type' => 'dimension',
				'title' => __('副标题内边距','xintheme-pagebuilder'),
				'tab'=> 'style',
				'section' => __('副标题','xintheme-pagebuilder'),
				'responsive' => true,
				'unit' => array( 'px','em','%' ),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-subtitle { padding: {{data.subtitle_padding}}; }',
				'depends' => array(array('subtitle_show', '=', '1')),
			),

			//button
			'button_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std'		=> array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'14px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-addons',
				'section' => __('按钮','xintheme-pagebuilder'),
				'tab'=> 'style',
				'depends' => array(array('button_show', '=', '1')),
			),
			'button_color' => array(
				'type' => 'color',
				'title' => __( '按钮颜色', 'xintheme-pagebuilder' ),
				'std'  => '#fff',
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-addons { color: {{data.button_color}}; }',
				'tab'=> 'style',
				'section' => __('按钮','xintheme-pagebuilder'),
				'depends' => array(array('button_show', '=', '1')),
			),
			'button_hcolor' => array(
				'type' => 'color',
				'title' => __( '按钮悬停颜色', 'xintheme-pagebuilder' ),
				'std'  => '#fff',
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-addons:hover { color: {{data.button_hcolor}}; }',
				'tab'=> 'style',
				'section' => __('按钮','xintheme-pagebuilder'),
				'depends' => array(array('button_show', '=', '1')),
			),
			'button_bg' => array(
				'type' => 'color2',
				'title' => __( '按钮背景', 'xintheme-pagebuilder' ),
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#2e56ff',
					'clip' => false
				),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-addons',
				'tab'=> 'style',
				'section' => __('按钮','xintheme-pagebuilder'),
				'depends' => array(array('button_show', '=', '1')),
			),
			'button_hover_bg' => array(
				'type' => 'color2',
				'title' => __( '按钮悬停背景', 'xintheme-pagebuilder' ),
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#2347e2',
					'clip' => false
				),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-image-hover:before',
				'tab'=> 'style',
				'section' => __('按钮','xintheme-pagebuilder'),
				'depends' => array(array('button_show', '=', '1')),
			),
			'button_border' => array(
				'type' => 'border',
				'title' => __( '按钮边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle'	=>'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab'=> 'style',
				'section' => __('按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-addons',
				'depends' => array(array('button_show', '=', '1')),
			),
			'border_hcolor' => array(
				'type' => 'border',
				'title' => __( '按钮悬停边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ), 
					'borderStyle'	=>'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab'=> 'style',
				'section' => __('按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-addons:hover',
				'depends' => array(array('button_show', '=', '1')),
			),
			'buttom_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'std' => array(
					'md' => array( 'top' => '3px', 'right' => '3px', 'bottom' => '3px', 'left' => '3px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab'=> 'style',
				'section' => __('按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-addons { border-radius: {{data.buttom_radius}}; }',
				'depends' => array(array('button_show', '=', '1')),
			),
			'buttom_hradius' => array(
				'type' => 'dimension',
				'title' => __('悬停边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'std' => array(
					'md' => array( 'top' => '3px', 'right' => '3px', 'bottom' => '3px', 'left' => '3px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab'=> 'style',
				'section' => __('按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-addons:hover { border-radius: {{data.buttom_hradius}}; }',
				'depends' => array(array('button_show', '=', '1')),
			),
			'button_padding'   => array(
				'type' => 'dimension',
				'title' => __( '按钮内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'std' => array(
					'md' => array( 'top' => '5px', 'right' => '15px', 'bottom' => '5px', 'left' => '15px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive'=> true,
				'tab'=> 'style',
				'section' => __('按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-addons { padding: {{data.button_padding}}; }',
				'depends' => array(array('button_show', '=', '1')),
			),
			'btn_margin' => array(
				'type' => 'dimension',
				'title' => __('按钮外边距','xintheme-pagebuilder'),
				'tab'=> 'style',
				'section' => __('按钮','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-image-hover-content .xtpb-btn-addons { margin: {{data.btn_margin}}; }',
				'depends' => array(array('button_show', '=', '1')),
			),

			//layout three
			'three_border_top' => array(
				'type' => 'border',
				'title' => __( '上下边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '1px', 'right' => '0px', 'bottom' => '1px', 'left' => '0px' ), 
					'borderStyle'	=>'solid', 
					'borderColor' => '#fff' 
				),
				'tab'=> 'style',
				'depends' => array(array('layout', '=', array('three','seven'))),
				'section' => __('边框','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-three .xtpb-image-hover-intro:before, {{SELECTOR}} .xtpb-image-hover-seven .xtpb-image-hover-content .xtpb-image-hover-intro::before'
			),
			'three_border_left' => array(
				'type' => 'border',
				'title' => __( '左右边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '1px', 'bottom' => '0px', 'left' => '1px' ), 
					'borderStyle'	=>'solid', 
					'borderColor' => '#fff' 
				),
				'tab'=> 'style',
				'depends' => array(array('layout', '=', array('three','seven'))),
				'section' => __('边框','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-three .xtpb-image-hover-intro:after, {{SELECTOR}} .xtpb-image-hover-seven .xtpb-image-hover-content .xtpb-image-hover-intro::after'
			),
			'five_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ), 
					'borderStyle'	=>'solid', 
					'borderColor' => '#fff' 
				),
				'tab'=> 'style',
				'depends' => array(array('layout', '=', array('five'))),
				'section' => __('边框','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-five .xtpb-image-hover-content .xtpb-image-hover-intro::before'
			),
			'six_border' => array(
				'type' => 'color2',
				'title' => __( '背景', 'xintheme-pagebuilder' ),
				'tab'=> 'style',
				'depends' => array(array('layout', '=', array('six'))),
				'section' => __('边框','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-six .xtpb-image-hover-title:after'
			),
			'six_border_width' => array(
				'type' => 'slider',
				'title' => __('条形高度','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%', ),
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
						'md' => '3px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab'=> 'style',
				'section' => __('边框','xintheme-pagebuilder'),
				'depends' => array(array('layout', '=', array('six'))),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-six .xtpb-image-hover-title:after { height: {{data.six_border_width}}; }'
			),
			'eight_border' => array(
				'type' => 'color2',
				'title' => __( '背景', 'xintheme-pagebuilder' ),
				'tab'=> 'style',
				'depends' => array(array('layout', '=', array('eight'))),
				'section' => __('边框','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-eight .xtpb-image-hover-title::after'
			),
			'eight_border_width' => array(
				'type' => 'slider',
				'title' => __('条形高度','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%', ),
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
						'md' => '3px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab'=> 'style',
				'section' => __('边框','xintheme-pagebuilder'),
				'depends' => array(array('layout', '=', array('eight'))),
				'selector' => '{{SELECTOR}} .xtpb-image-hover-eight .xtpb-image-hover-title::after { height: {{data.eight_border_width}}; }'
			),

		);

		return $settings;
	}


	// Feature box Render HTML
	public function render($data = null){
		$settings 				= $data['settings'];

		$layout 				= isset($settings['layout']) ? sanitize_text_field( $settings['layout'] ) : '';
		$title 					= isset($settings['title']) ? sanitize_text_field( $settings['title'] ) : '';
		$selector 				= isset($settings["heading_selector"]) ? sanitize_text_field( $settings["heading_selector"] ) : '';
		$subtitle 				= isset($settings['subtitle']) ? sanitize_text_field( $settings['subtitle'] ) : '';
		$subselector 			= isset($settings["subheading_selector"]) ? sanitize_text_field( $settings["subheading_selector"] ) : '';
		$title_link 			= isset($settings['title_link']) ? $settings['title_link'] : array();
		$button_text 			= isset($settings["button_text"]) ? sanitize_text_field( $settings["button_text"] ) : '';
		$button_link 			= isset($settings['button_link']) ? $settings['button_link'] : array();
		$btn_icon_list 			= isset($settings["btn_icon_list"]) ? sanitize_text_field( $settings["btn_icon_list"] ) : '';
		$icon_position 			= isset($settings["icon_position"]) ? sanitize_text_field( $settings["icon_position"] ) : '';
		$content_position 		= isset($settings['content_position']) ? sanitize_text_field( $settings['content_position'] ) : '';
		$title_show 			= isset($settings['title_show']) ? sanitize_text_field( $settings['title_show'] ) : '';
		$subtitle_show 			= isset($settings['subtitle_show']) ? sanitize_text_field( $settings['subtitle_show'] ) : '';
		$button_show 			= isset($settings['button_show']) ? sanitize_text_field( $settings['button_show'] ) : '';
		$image_hover_effect 	= isset($settings['image_hover_effect']) ? sanitize_text_field( $settings['image_hover_effect'] ) : '';

		$output = $img_url = $data_media = $data_title = $data_number = $data_subtitle = $data_intro = $btndata = $button = '' ;
		$title_show = (isset($title_show) && $title_show) ? '1' : '0';
		$subtitle_show = (isset($subtitle_show) && $subtitle_show) ? '1' : '0';
		$button_show = (isset($button_show) && $button_show) ? '1' : '0';
		$image_hover_effect = (isset($image_hover_effect) && $image_hover_effect) ? $image_hover_effect : "slideleft";

		$data_media .= '<div class="xtpb-image-hover-background image-hover-'.$image_hover_effect.'"><div class="xtpb-image-hover-bg"></div><div class="xtpb-image-hover-overlay"></div></div>';

		$selector = $selector ? $selector : "h3";
		$subselector = $subselector ? $subselector : "h4";

		if( $title_show == '1'){
			if( ( $title ) ){
				if( !empty($title_link['link']) ){
					$target = $title_link['window'] ? 'target=_blank' : "";
					$nofolow = $title_link['nofolow'] ? 'rel=nofolow' : "";
					$data_title .= '<' .esc_attr($selector). ' class="xtpb-image-hover-title xtpb-image-hover-title-'.esc_attr($title_show).'"><a '.esc_attr($nofolow).' href="'.esc_url($title_link['link']).'" '.esc_attr($target).'>' . wp_kses_post($title) .'</a></' . esc_attr($selector) . '>';
				} else {
					$data_title .= '<' .esc_attr($selector). ' class="xtpb-image-hover-title xtpb-image-hover-title-'.esc_attr($title_show).'">' . wp_kses_post($title) .'</' . esc_attr($selector) . '>';
				}
			}
		}

		if( $subtitle_show == '1'){
			if( !empty( $subtitle ) ){
				$data_subtitle .= '<' .esc_attr($subselector). ' class="xtpb-image-hover-subtitle xtpb-image-hover-subtitle-'.esc_attr($subtitle_show).'">' . wp_kses_post($subtitle) .'</' . esc_attr($subselector) . '>';
			}
		}

		if($icon_position == 'left') {
			$button = (esc_attr($btn_icon_list)) ? '<i class="' . esc_attr($btn_icon_list) . '"></i> <span>' . esc_attr($button_text).'</span>' : '<span>' . esc_attr($button_text). '</span>';
		} else {
			$button = (esc_attr($btn_icon_list)) ? '<span>' . esc_attr($button_text) . '</span> <i class="' . esc_attr($btn_icon_list) . '"></i><span>' : esc_attr($button_text).'</span>';
		}

		if($button_show == '1') {
			if( !empty($button_link['link']) ){
				$btntarget = ( isset($button_link['window']) && $button_link['window'] ) ? 'target=_blank' : 'target=_self';
				$btnnofolow = ( isset($button_link['nofolow']) && $button_link['nofolow'] ) ? 'rel=nofolow' : "";
				$btndata  = '<a '.esc_attr($btntarget).' href="'.esc_url($button_link['link']).'" '.esc_attr($btnnofolow).' class="xtpb-btn-addons xtpb-btn-image-hover">' . $button  . '</a>';
			}
		}

		$output  .= '<div class="xtpb-image-hover-content xtpb-image-hover-'.esc_attr($layout).' xtpb-image-hover-'.esc_attr($content_position).'">';
			if ( $layout == "three" ) {
				$output  .= $data_media;
					$output  .= '<div class="xtpb-image-hover-intro">';
						$output  .= '<div class="xtpb-image-hover-intro-three">';
						$output  .= $data_title;
						$output  .= $data_subtitle;
						$output  .= $btndata;
					$output  .= '</div>';
				$output  .= '</div>';
			}
			elseif ( $layout == "four" ) {
				$output  .= $data_media;
					$output  .= '<div class="xtpb-image-hover-intro">';
						$output  .= $data_subtitle;
						$output  .= $btndata;
						$output  .= '<div class="xtpb-image-hover-intro-four">';
						$output  .= $data_title;
					$output  .= '</div>';
				$output  .= '</div>';
			} elseif ( $layout == "five" ) {
				$output  .= $data_media;
					$output  .= '<div class="xtpb-image-hover-intro">';
						$output  .= '<div class="xtpb-image-hover-intro-five">';
						$output  .= $data_title;
						$output  .= $data_subtitle;
						$output  .= $btndata;
					$output  .= '</div>';
				$output  .= '</div>';
			} elseif ( $layout == "six" ) {
				$output  .= $data_media;
					$output  .= '<div class="xtpb-image-hover-intro">';
						$output  .= $data_title;
						$output  .= '<div class="xtpb-image-hover-intro-six">';
						$output  .= $data_subtitle;
						$output  .= $btndata;
					$output  .= '</div>';
				$output  .= '</div>';
			} elseif ( $layout == "seven" ) {
				$output  .= $data_media;
					$output  .= '<div class="xtpb-image-hover-intro">';
						$output  .= '<div class="xtpb-image-hover-intro-seven">';
						$output  .= $data_title;
						$output  .= $data_subtitle;
						$output  .= $btndata;
					$output  .= '</div>';
				$output  .= '</div>';
			} elseif ( $layout == "eight" ) {
				$output  .= $data_media;
					$output  .= '<div class="xtpb-image-hover-intro">';
						$output  .= '<div class="xtpb-image-hover-intro-eight">';
						$output  .= $data_title;
						$output  .= $data_subtitle;
						$output  .= $btndata;
					$output  .= '</div>';
				$output  .= '</div>';
			} else {
				$output  .= $data_media;
				$output  .= '<div class="xtpb-image-hover-intro">';
					$output  .= $data_title;
					$output  .= $data_subtitle;
					$output  .= $btndata;
				$output  .= '</div>';
			}
		$output  .= '</div>';//xtpb-image-hover-content

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

		var heading_selector = data.heading_selector ? data.heading_selector : "h3";
		var subheading_selector = data.subheading_selector ? data.subheading_selector : "h4";
		var image_hover_effect = data.image_hover_effect ? data.image_hover_effect : "slideleft";

		var title_show = data.title_show ? "1" : "0";
		var subtitle_show = data.subtitle_show ? "1" : "0";
		var button_show = data.button_show ? "1" : "0";

		if(data.title_show == "1"){
			if(data.title){
				if( !_.isEmpty( data.link ) ){
					data_title += "<"+data.heading_selector+" class=\'xtpb-image-hover-title xtpb-image-hover-title-"+ data.title_show+"\'><a href=\'"+ data.link+"\'>"+data.title+"</a></"+data.heading_selector+">";
				} else {
					data_title += "<"+data.heading_selector+" class=\'xtpb-image-hover-title xtpb-image-hover-title-"+ data.title_show+"\'>"+data.title+"</"+data.heading_selector+">";
				}
			}
		}

		if(data.subtitle_show == "1"){
			if(data.subtitle){
				data_subtitle += "<"+data.subheading_selector+" class=\'xtpb-image-hover-subtitle xtpb-image-hover-subtitle-"+ data.subtitle_show+"\'>"+data.subtitle+"</"+data.subheading_selector+">";
			}
		}
		
		data_media  += "<div class=\'xtpb-image-hover-background image-hover-"+data.image_hover_effect+"\'><div class=\'xtpb-image-hover-bg\'></div><div class=\'xtpb-image-hover-overlay\'></div></div>";
		 
		#>

		<div class="xtpb-image-hover-content xtpb-image-hover-{{data.layout}} xtpb-image-hover-{{data.content_position}}">
				<# if(data.layout == "three"){ #>	
					{{{data_media}}}
					<div class="xtpb-image-hover-intro">
						<div class="xtpb-image-hover-intro-three">
							{{{data_title}}}
							{{{data_subtitle}}}
							<# if( data.button_show == "1" ) { #>
								<# if( !__.isEmpty(data.button_link.link) ) { #>
									<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons xtpb-btn-image-hover">
										<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
									</a>
								<# } #>
							<# } #>
						</div>
					</div>
				<# } else if(data.layout == "four"){ #>		
					{{{data_media}}}
					<div class="xtpb-image-hover-intro">
						{{{data_subtitle}}}
						<# if( data.button_show == "1" ) { #>
							<# if( !__.isEmpty(data.button_link.link) ) { #>
								<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons xtpb-btn-image-hover">
									<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
								</a>
							<# } #>
						<# } #>
						<div class="xtpb-image-hover-intro-four">
							{{{data_title}}}
						</div>
					</div>
					<# } else if(data.layout == "five"){ #>		
						{{{data_media}}}
						<div class="xtpb-image-hover-intro">
							<div class="xtpb-image-hover-intro-five">
								{{{data_title}}}
								{{{data_subtitle}}}
								<# if( data.button_show == "1" ) { #>
									<# if( !__.isEmpty(data.button_link.link) ) { #>
										<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons xtpb-btn-image-hover">
											<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
										</a>
									<# } #>
								<# } #>
							</div>
						</div>
					<# } else if(data.layout == "six"){ #>		
						{{{data_media}}}
						<div class="xtpb-image-hover-intro">
							{{{data_title}}}
							<div class="xtpb-image-hover-intro-six">
								{{{data_subtitle}}}
								<# if( data.button_show == "1" ) { #>
									<# if( !__.isEmpty(data.button_link.link) ) { #>
										<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons xtpb-btn-image-hover">
											<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
										</a>
									<# } #>
								<# } #>
							</div>
						</div>
				<# } else if(data.layout == "seven"){ #>		
					{{{data_media}}}
					<div class="xtpb-image-hover-intro">
						<div class="xtpb-image-hover-intro-seven">
							{{{data_title}}}
							{{{data_subtitle}}}
							<# if( data.button_show == "1" ) { #>
								<# if( !__.isEmpty(data.button_link.link) ) { #>
									<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons xtpb-btn-image-hover">
										<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
									</a>
								<# } #>
							<# } #>
						</div>
					</div>
				<# } else if(data.layout == "eight"){ #>		
					{{{data_media}}}
					<div class="xtpb-image-hover-intro">
						<div class="xtpb-image-hover-intro-eight">
							{{{data_title}}}
							{{{data_subtitle}}}
							<# if( data.button_show == "1" ) { #>
								<# if( !__.isEmpty(data.button_link.link) ) { #>
									<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons xtpb-btn-image-hover">
										<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
									</a>
								<# } #>
							<# } #>
						</div>
					</div>
				<# } else { #>	
					{{{data_media}}}
					<div class="xtpb-image-hover-intro">
						{{{data_title}}}
						{{{data_subtitle}}}
						<# if( data.button_show == "1" ) { #>
							<# if( !__.isEmpty(data.button_link.link) ) { #>
								<a {{ data.button_link.link ? "href="+data.button_link.link : "" }} {{ data.button_link.window ? "target=_blank" : "" }} {{ data.button_link.nofolow ? "rel=nofolow" : "" }} class="xtpb-btn-addons xtpb-btn-image-hover">
									<# if(data.icon_position == "left" && !_.isEmpty(data.btn_icon_list)) { #><i class="{{ data.btn_icon_list }}"></i> <# } #><span>{{ data.button_text }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.btn_icon_list)) { #> <i class="{{ data.btn_icon_list }}"></i><# } #>
								</a>
							<# } #>
						<# } #>
					</div>
				<# } #>
			</div>
		';
		return $output;
	}

}
