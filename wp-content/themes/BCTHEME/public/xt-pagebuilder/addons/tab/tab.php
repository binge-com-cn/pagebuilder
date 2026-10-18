<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Tab{

	public function get_name(){
		return 'xtpb_tab';
	}
	public function get_title(){
		return __( '标签页', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-tabs';
	}

	// Tab Settings Fields
	public function get_settings() {
		$settings = array(
			'tabtype' => array(
				'type' => 'radioimage',
				'title' => __( '类型', 'xintheme-pagebuilder' ),
				'values'=> array(
					'horizontal' =>  XTPB_DIR_URL.'addons/tab/img/tab-img1.png',
					'vertical' =>  XTPB_DIR_URL.'addons/tab/img/tab-img2.png',
				),
				'std' => 'horizontal',
			),
			'horizontal_align' => array(
				'type' => 'select',
				'title' => __('水平对齐','xintheme-pagebuilder'),
				'depends' => array(array('tabtype', '=', 'horizontal')),
				'values' => array(
					'horizontalleft' => __('左侧','xintheme-pagebuilder'),
					'horizontalcenter' => __('居中','xintheme-pagebuilder'),
					'horizontalright' => __('右侧','xintheme-pagebuilder'),
				),
				'std' => 'horizontalleft',
			),
			'vertical_position' => array(
				'type' => 'select',
				'title' => __('导航栏位置','xintheme-pagebuilder'),
				'depends' => array(array('tabtype', '=', 'vertical')),
				'values' => array(
					'verticalleft' => __('左侧','xintheme-pagebuilder'),
					'verticalright' => __('右侧','xintheme-pagebuilder'),
				),
				'std' => 'verticalleft',
			),
			'navbar_width' => array(
				'type' => 'slider',
				'title' => __('导航栏宽度','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
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
				'responsive' => true,
				'selector' => '{{SELECTOR}} .vertical-tab ul.xtpb-tab-nav, {{SELECTOR}} .horizontal-tab .xtpb-tab-nav-list { width: {{data.navbar_width}}; }'
			),

			'tab_list' => array(
				'title' => __('添加标签项','xintheme-pagebuilder'),
				'type' => 'repeatable',
				'label' => 'title',
					'std' => array(
						array(
							'title' => __( '页面构建器', 'xintheme-pagebuilder' ),
							'icon_list' => 'ri-star-line',
							'icon_position' => 'left',
							'content' => __( '内置灵活的页面构建功能，无需编写代码，即可快速创建企业官网、产品展示、博客资讯等多种页面布局。', 'xintheme-pagebuilder' ),
						),
						array(
							'title' => __( '拖拽编辑', 'xintheme-pagebuilder' ),
							'icon_list' => 'ri-drag-move-line',
							'icon_position' => 'right',
							'content' => __( '支持直观的拖拽式操作，模块可以自由添加、移动和调整，让页面编辑更加简单高效。', 'xintheme-pagebuilder' ),
						),
						array(
							'title' => __( 'WordPress 主题', 'xintheme-pagebuilder' ),
							'icon_position' => 'right',
							'content' => __( '专为 WordPress 网站打造，兼顾设计美观、功能扩展和使用体验，帮助你快速搭建专业网站。', 'xintheme-pagebuilder' ),
						),
					),
				'attr' => array(
					'title' => array(
						'type' => 'text',
						'title' => __('标题','xintheme-pagebuilder'),
						'std' => __( 'BCTheme PageBuilder', 'xintheme-pagebuilder' )
					),
					'subtitle' => array(
						'type' => 'text',
						'title' => __('副标题','xintheme-pagebuilder'),
						'std' => __( '丰富的元素', 'xintheme-pagebuilder' )
					),
					'icon_list' => array(
						'type' => 'icon',
						'title' => __('图标','xintheme-pagebuilder'),
						'std' => 'ri-home-4-line'
					),
					'icon_position' => array(
						'type' => 'select',
						'title' => __('图标位置','xintheme-pagebuilder'),
						'depends' => array(array('icon_list', '!=', '')),
						'values' => array(
							'left' => __('左侧','xintheme-pagebuilder'),
							'right' => __('右侧','xintheme-pagebuilder'),
							'top' => __('顶部','xintheme-pagebuilder'),
							'bottom' => __('底部','xintheme-pagebuilder'),
						),
						'std' => 'left',
					),
					'content' => array(
						'type' => 'editor',
						'title' => __('内容','xintheme-pagebuilder'),
						'std' => __( '这是一段默认内容示例，可用于展示模块说明、产品介绍、服务描述或文章摘要。你可以在编辑器中自由修改文字、添加链接、调整格式，让内容更符合当前页面需求。', 'xintheme-pagebuilder' )
					),
				),
			),

			//nav bar wrap
			'nav_bar_wrap_bg' => array(
				'type' => 'color',
				'title' => __('背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航栏容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav { background: {{data.nav_bar_wrap_bg}}; }'
			),

			'nav_wrap_radius' => array(
				'type' => 'dimension',
				'title' => __( '边框圆角', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航栏容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav { border-radius: {{data.nav_wrap_radius}}; }'
			),
			'nav_wrap_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航栏容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav { padding: {{data.nav_wrap_padding}}; }'
			),
			'nav_wrap_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航栏容器', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav { margin: {{data.nav_wrap_margin}}; }'
			),

			//nav bar item
			'nav_bar_bg' => array(
				'type' => 'color',
				'title' => __('背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航栏项目', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list .xtpb-tab-nav-list-wrap { background: {{data.nav_bar_bg}}; }'
			),
			'nav_hover_bg' => array(
				'type' => 'color',
				'title' => __('悬停背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航栏项目', 'xintheme-pagebuilder' ),
				'std'   => '#fafafa',
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list:hover .xtpb-tab-nav-list-wrap { background: {{data.nav_hover_bg}}; }'
			),
			'nav_active_bg' => array(
				'type' => 'color',
				'title' => __('激活背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '导航栏项目', 'xintheme-pagebuilder' ),
				'std'   => '#fff',
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list.active .xtpb-tab-nav-list-wrap { background: {{data.nav_active_bg}}; }'
			),
			'nav_active_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => __( '激活阴影', 'xintheme-pagebuilder' ),
                'std' => array(
                    'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                    'shadowColor' => 'rgba(0,0,0,.3)'
                ),
				'tab' => 'style',
				'section' => __( '导航栏项目', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list.active .xtpb-tab-nav-list-wrap,{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list:hover .xtpb-tab-nav-list-wrap'
			),
			'nav_radius' => array(
				'type' => 'dimension',
				'title' => __( '边框圆角', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航栏项目', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list .xtpb-tab-nav-list-wrap { border-radius: {{data.nav_radius}}; }'
			),
			'nav_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航栏项目', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list .xtpb-tab-nav-list-wrap { margin: {{data.nav_margin}}; }'
			),
			'nav_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'std' => array(
					'md' => array( 'top' => '15px', 'right' => '20px', 'bottom' => '15px', 'left' => '20px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航栏项目', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list .xtpb-tab-nav-list-wrap { padding: {{data.nav_padding}}; }'
			),
			'navbar_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '导航栏项目', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list .xtpb-tab-nav-list-wrap { text-align: {{data.navbar_align}}; }'
			),

			//border
			'navbar_border' => array(
				'type' => 'border',
				'title' => __( '导航栏容器边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '0', 'right' => '0', 'bottom' => '1px', 'left' => '0' ),
					'borderStyle' => 'solid',
					'borderColor' => '#e5e5e5'
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'depends' => array(array('tabtype', '=', 'horizontal')),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav', 
			),
			'navbar_list_border' => array(
				'type' => 'border',
				'title' => __( '导航项边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '1px', 'right' => '0px', 'bottom' => '1px', 'left' => '1px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#e5e5e5'
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'depends' => array(array('tabtype', '=', 'horizontal')),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list .xtpb-tab-nav-list-wrap', 
			),
			'navbar_list_border_last' => array(
				'type' => 'border',
				'title' => __( '最后一个导航项边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#e5e5e5'
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'depends' => array(array('tabtype', '=', 'horizontal')),
				'selector' => '{{SELECTOR}} ul.xtpb-tab-nav .xtpb-tab-nav-list:last-child .xtpb-tab-nav-list-wrap', 
			),
			'navbar_vertical_wrap_border' => array(
				'type' => 'border',
				'title' => __( '垂直导航容器边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#e5e5e5'
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'depends' => array(array('tabtype', '=', 'vertical')),
				'selector' => '{{SELECTOR}} .vertical-tab ul.xtpb-tab-nav', 
			),
			'navbar_vertical_list_border' => array(
				'type' => 'border',
				'title' => __( '垂直导航项边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '1px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#e5e5e5'
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'depends' => array(array('tabtype', '=', 'vertical')),
				'selector' => '{{SELECTOR}} .vertical-tab ul.xtpb-tab-nav .xtpb-tab-nav-list .xtpb-tab-nav-list-wrap', 
			),
			'content_border' => array(
				'type' => 'border',
				'title' => __( '内容边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#e5e5e5'
				),
				'tab' => 'style',
				'section' => __( '边框', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-content-wrap'
			),

			//title
			'title_style' => array(
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
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-tab-title-content',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
			),
			'title_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-title-content { color: {{data.title_color}}; }'
			),
			'title_active_color' => array(
				'type' => 'color',
				'title' => __('激活颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list.active .xtpb-tab-title-content,{{SELECTOR}} .xtpb-tab-nav-list:hover .xtpb-tab-title-content{ color: {{data.title_active_color}}; }'
			),
			'title_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list .xtpb-tab-title-content { margin: {{data.title_margin}}; }'
			),

			//subtitle
			'subtitle_style' => array(
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
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-tab-subtitle-content',
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
			),
			'subtitle_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-subtitle-content { color: {{data.subtitle_color}}; }'
			),
			'subtitle_active_color' => array(
				'type' => 'color',
				'title' => __('激活颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list.active .xtpb-tab-subtitle-content,{{SELECTOR}} .xtpb-tab-nav-list:hover .xtpb-tab-subtitle-content{ color: {{data.subtitle_active_color}}; }'
			),
			'subtitle_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '副标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list .xtpb-tab-subtitle-content { margin: {{data.subtitle_margin}}; }'
			),

			//icon
			'icon_size' => array(
				'type' => 'slider',
				'title' => __('尺寸','xintheme-pagebuilder'),
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
					'md' => '14px',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list i { font-size: {{data.icon_size}}; }'
			),
			'icon_line_height' => array(
				'type' => 'slider',
				'title' => __('行高','xintheme-pagebuilder'),
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
						'md' => '14px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list i { line-height: {{data.icon_line_height}}; }'
			),
			'icon_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list i { color: {{data.icon_color}}; }'
			),
			'icon_active_color' => array(
				'type' => 'color',
				'title' => __('激活/悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list.active i,{{SELECTOR}} .xtpb-tab-nav-list:hover i { color: {{data.icon_active_color}}; }'
			),

			'icon_bg_color' => array(
				'type' => 'color',
				'title' => __('背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list i{ background: {{data.icon_bg_color}}; }'
			),

			'icon_bg_active_color' => array(
				'type' => 'color',
				'title' => __('激活/悬停背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list.active i,{{SELECTOR}} .xtpb-tab-nav-list:hover i { background: {{data.icon_bg_active_color}}; }'
			),

			'icon_width' => array(
				'type' => 'slider',
				'title' => __('宽度','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
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
						'md' => '',
						'sm' => '',
						'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list i { width: {{data.icon_width}}; }'
			),
			'icon_height' => array(
				'type' => 'slider',
				'title' => __('高度','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
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
						'md' => '',
						'sm' => '',
						'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => array(
					'{{SELECTOR}} .xtpb-tab-nav-list i { height: {{data.icon_height}}; }',
					'{{SELECTOR}} .xtpb-tab-nav-list i { line-height: {{data.icon_height}}; }',
				)
			),

			'icon_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'responsive' => false,
				'tab' => 'style',
				'unit' => array( '%','px','em' ),
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list i { border-radius: {{data.icon_radius}}; }'
			),

			'icon_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'std' => array( 'md' => 'center', 'sm' => 'center', 'xs' => 'center' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list i { text-align: {{data.icon_align}}; }'
			),
			'icon_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'std' => array(
					'md' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'sm' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'xs' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
				),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-nav-list i{ margin: {{data.icon_margin}}; }'
			),

			//content
			'content_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '标签内容', 'xintheme-pagebuilder' ),
				'std' => '#000',
				'selector' => '{{SELECTOR}} .xtpb-tab-content { color: {{data.content_color}}; }'
			),
			'content_bg' => array(
				'type' => 'color',
				'title' => __('背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '标签内容', 'xintheme-pagebuilder' ),
				'std' => '#fff',
				'selector' => '{{SELECTOR}} .xtpb-tab-content-wrap { background: {{data.content_bg}}; }'
			),
			'content_style' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'tab' => 'style',
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'14px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-tab-content-wrap',
				'section' => __( '标签内容', 'xintheme-pagebuilder' ),
			),

			'content_radius' => array(
				'type' => 'dimension',
				'title' => __( '边框圆角', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '标签内容', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-content-wrap{ border-radius: {{data.content_radius}}; }'
			),
//			'margin_bottom' => array(
//				'type' => 'slider',
//				'title' => __('外边距','xintheme-pagebuilder'),
//				'unit' => array( 'px','%','em' ),
//				'range' => array(
//						'em' => array(
//							'min' => 0,
//							'max' => 5,
//							'step' => .1,
//						),
//						'px' => array(
//							'min' => 0,
//							'max' => 20,
//							'step' => 1,
//						),
//						'%' => array(
//							'min' => 0,
//							'max' => 100,
//							'step' => 1,
//						),
//					),
//				'responsive' => true,
//				'tab' => 'style',
//				'section' => 'Tab Content',
//				'selector' => '{{SELECTOR}} .xtpb-tab-nav li { margin-bottom: -{{data.margin_bottom}}; }'
//			),
			'content_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'std' => array(
					'md' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'sm' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'xs' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
				),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '标签内容', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-content-wrap{ margin: {{data.content_margin}}; }'
			),
			'content_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'std' => array(
					'md' => array( 'top' => '15px', 'right' => '15px', 'bottom' => '15px', 'left' => '15px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
				),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '标签内容', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-content-wrap{ padding: {{data.content_padding}}; }'
			),
			'content_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '标签内容', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-tab-content-wrap { text-align: {{data.content_align}}; }'
			),
		);

		return $settings;
	}

	// Tab Render HTML
	public function render($data = null){
		$settings 			 = $data['settings'];

		$tab_list 			 = isset($settings["tab_list"]) ? $settings["tab_list"] : array();
		$tabtype 			 = isset($settings["tabtype"]) ? sanitize_text_field( $settings["tabtype"] ) : '';
		$vertical_position 	 = isset($settings["vertical_position"]) ? sanitize_text_field( $settings["vertical_position"] ) : '';
		$horizontal_align 	 = isset($settings["horizontal_align"]) ? sanitize_text_field( $settings["horizontal_align"] ) : '';
		$rand = rand(1000,999999);
		$output = $classlist = '';

		$classlist .= (isset($tabtype) && $tabtype) ? $tabtype.'-tab' : 'horizontal-tab';
		if( $horizontal_align ) {
			$classlist .= (isset($horizontal_align) && $horizontal_align) ? ' ' . $horizontal_align : ' horizontalcenter ';
		}
		if( $vertical_position ) {
			$classlist .= (isset($vertical_position) && $vertical_position) ? ' ' . $vertical_position : ' verticalleft ';
		}

		$output  .= '<div class="xtpb-tab-addon-content '. $classlist.' ">';
		$output  .= '<ul class="xtpb-tab-nav">';
		if (is_array($tab_list) && count($tab_list)) {
			foreach ( $tab_list as $key => $value ) {
				$activeClass = ( $key == 0 ) ? "active" : "";
				$output      .= '<li class="xtpb-tab-nav-list xtpb-nav-' . esc_attr( get_xtpb_array_value_by_key( $value, 'icon_position' ) ) . ' ' . $activeClass . '" data-tab="tab-' . $key . $rand . '">';
				$output      .= '<div class="xtpb-tab-nav-list-wrap">';
				$output      .= '<div>';
				if ( get_xtpb_array_value_by_key( $value, 'icon_position' ) == "left" || get_xtpb_array_value_by_key( $value, 'icon_position' ) == "top" ) {
					if ( ! empty( $value['icon_list'] ) ) {
						$output .= '<i class="' . esc_attr( $value['icon_list'] ) . '"></i>';
					}
				}
				if ( get_xtpb_array_value_by_key( $value, 'title' ) ) {
					$output .= '<span class="xtpb-tab-title-content">' . esc_html( $value['title'] ) . '</span>';
				}
				if ( get_xtpb_array_value_by_key( $value, 'icon_position' ) == "right" ) {
					if ( get_xtpb_array_value_by_key( $value, 'icon_list' ) ) {
						$output .= '<i class="' . esc_attr( $value['icon_list'] ) . '"></i>';
					}
				}
				if ( get_xtpb_array_value_by_key( $value, 'subtitle' ) ) {
					$output .= '<span class="xtpb-tab-subtitle-content">' . esc_html( $value['subtitle'] ) . '</span>';
				}
				if ( get_xtpb_array_value_by_key( $value, 'icon_position' ) == "bottom" ) {
					if ( get_xtpb_array_value_by_key( $value, 'icon_list' ) ) {
						$output .= '<i class="' . esc_attr( $value['icon_list'] ) . '"></i>';
					}
				}
				$output .= '</div>';
				$output .= '</div>';
				$output .= '</li>';
			}
		}
		$output  .= '</ul>';

		$output  .= '<div class="xtpb-tab-content-wrap">';
		if (is_array($tab_list) && count($tab_list)) {
			foreach ( $tab_list as $key => $value ) {
				$activeContent = ( $key == 0 ) ? "active" : "";
				$output        .= '<div id="tab-' . $key . $rand . '" class="xtpb-tab-content ' . $activeContent . '">';
				$output        .= $value['content'];
				$output        .= '</div>';
			}
		}
		$output  .= '</div>';
		$output  .= '</div>';

		return $output;
	}

	// Tab Template
	public function getTemplate(){
		$output = '
			<#
			var rand = Math.floor(Math.random() * 1000);
			var classList = "";
			classList += data.tabtype ? data.tabtype+"-tab" : " horizontal-tab"; 
			if(data.vertical_position){  
				classList += data.vertical_position ? " "+ data.vertical_position : " verticalleft";   
			}
			if(data.horizontal_align){  
				classList += " "+data.horizontal_align ? " "+ data.horizontal_align : " horizontalcenter";
			}

			#>
			<div class="xtpb-tab-addon-content {{classList}}">
					<ul class="xtpb-tab-nav">
						<#  _.forEach(data.tab_list, function(value, key) { #>
							<# var activeClass = (key == 0) ? "active" : ""; #>
							<li class="xtpb-tab-nav-list xtpb-nav-{{value.icon_position}} {{ activeClass }}" data-tab="tab-{{key+rand}}">
								<div class="xtpb-tab-nav-list-wrap">
									<div>
										<# if(value.icon_position == "left" && !_.isEmpty(value.icon_list)) { #> <i class="{{ value.icon_list }}"></i><# } #><# if(value.icon_position == "top" && !_.isEmpty(value.icon_list)) { #> <i class="{{ value.icon_list }}"></i><# } #><span class="xtpb-tab-title-content">{{value.title}}</span><# if(value.icon_position == "right" && !_.isEmpty(value.icon_list)) { #> <i class="{{ value.icon_list }}"></i><# } #><span class="xtpb-tab-subtitle-content">{{value.subtitle}}</span><# if(value.icon_position == "bottom" && !_.isEmpty(value.icon_list)) { #> <i class="{{ value.icon_list }}"></i><# } #>
									</div>
								</div>
							</li>
						<# }); #>
					</ul>
					<div class="xtpb-tab-content-wrap">
					<#  _.forEach(data.tab_list, function(value, key) { #>
							<# var activeContent = (key == 0) ? "active" : ""; #>
							<div id="tab-{{key+rand}}" class="xtpb-tab-content {{activeContent}}">
								{{{value.content}}}
							</div>
					<# }); #>
					</div>
				</div>
		';
		return $output;
	}

}
