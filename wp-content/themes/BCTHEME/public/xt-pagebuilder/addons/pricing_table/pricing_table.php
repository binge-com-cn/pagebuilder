<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Pricing_Table{

	public function get_name(){
		return 'xtpb_pricing_table';
	}
	public function get_title(){
		return __( '价格表', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-pricing-table';
	}

	// Pricing Settings Fields
	public function get_settings() {

		$settings = array(
			'pricing_layout' => array(
				'type' => 'radioimage',
				'title' => __( '选择布局', 'xintheme-pagebuilder' ),
				'values'=> array(
					'one' =>  XTPB_DIR_URL.'addons/pricing_table/img/pricing-img1.png',
					'two' =>  XTPB_DIR_URL.'addons/pricing_table/img/pricing-img2.png',
					'three' =>  XTPB_DIR_URL.'addons/pricing_table/img/pricing-img3.png',
				),
				'std' => 'one',
			),
			'title' => array(
				'type' => 'text',
				'title' => __('标题','xintheme-pagebuilder'),
				'std' => __( '标准版', 'xintheme-pagebuilder' ),
			),
			'subtitle' => array(
				'type' => 'text',
				'title' => __('副标题','xintheme-pagebuilder'),
				'std' => __( '适合个人网站和小型项目', 'xintheme-pagebuilder' ),
				'depends' => array(array('pricing_layout', '!=', array('three'))),
			),
			'currency' => array(
				'type' => 'text',
				'title' => __('货币符号','xintheme-pagebuilder'),
				'std' => '￥',
			),
			'pricing' => array(
				'type' => 'text',
				'title' => __('促销价','xintheme-pagebuilder'),
				'std' => '688',
			),
			'sales' => array(
				'type' => 'text',
				'title' => __('原价','xintheme-pagebuilder'),
				'std' => '888',
			),
			'period' => array(
				'type' => 'text',
				'title' => __('周期','xintheme-pagebuilder'),
				'std' => __( '/ 月', 'xintheme-pagebuilder' ),
			),
			'pt_icon_list' => array(
				'type' => 'icon',
				'title' => __('图标列表','xintheme-pagebuilder'),
				'std' => 'ri-star-line',
				'depends' => array(array('pricing_layout', '!=', array('three'))),
			),
			'pricing_feature' => array(
				'title' => __('价格特性','xintheme-pagebuilder'),
				'type' => 'repeatable',
				'label' => 'feature_text',
				'std' => array(
					array( 
						'feature_text' => __( '一年技术支持', 'xintheme-pagebuilder' ),
						'icon_list' => 'ri-check-line',
						'icon_list_color' => '#23c131'
					),
					array( 
						'feature_text' => __( '不限量主题使用', 'xintheme-pagebuilder' ),
						'icon_list' => 'ri-close-line',
						'icon_list_color' => '#ff0000'
					),
					array( 
						'feature_text' => __( '免费扩展组件', 'xintheme-pagebuilder' ),
						'icon_list' => 'ri-check-line',
						'icon_list_color' => '#23c131'
					),
					array( 
						'feature_text' => __( '支持 5 个站点', 'xintheme-pagebuilder' ),
						'icon_list' => 'ri-check-line',
						'icon_list_color' => '#23c131'
					),
				),
				'attr' => array(
					'feature_text' => array(
						'type' => 'text',
						'title' => __('标题','xintheme-pagebuilder'),
						'std' => __( '一年技术支持', 'xintheme-pagebuilder' )
					),
					'icon_list' => array(
						'type' => 'icon',
						'title' => __('图标','xintheme-pagebuilder'),
						'std' => 'ri-check-line'
					),
					'icon_list_color' => array(
						'type' => 'color',
						'title' => __('图标颜色','xintheme-pagebuilder'),
						'selector' => '{{SELECTOR}} .xtpb-pricing-list i { color: {{data.icon_list_color}}; }'
					),
				),
			),			

			'button_text' => array(
				'type' => 'text',
				'title' => __('按钮文字','xintheme-pagebuilder'),
				'std' => __( '立即购买', 'xintheme-pagebuilder' ),
				'placeholder'=> __( '按钮', 'xintheme-pagebuilder' ),
			),
			'btn_link' => array(
				'type' => 'link',
				'title' => __('按钮链接','xintheme-pagebuilder'),
				'std' => array('link'=>'#','window'=>true,'nofolow'=>false),
				'depends' => array(array('button_text', '!=', '')),
			),
			'icon_list' => array(
				'type' => 'icon',
				'title' => __('按钮图标','xintheme-pagebuilder'),
				'std' => '',
				'depends' => array(array('button_text', '!=', '')),
			),
			'icon_position' => array(
				'type' => 'select',
				'title' => __('按钮图标位置','xintheme-pagebuilder'),
				'depends' => array(array('icon_list', '!=', '')),
				'values' => array(
					'left' => __('左侧','xintheme-pagebuilder'),
					'right' => __('右侧','xintheme-pagebuilder'),
				),
				'std' => 'left',
			),

			'additional_info' => array(
				'type' => 'text',
				'title' => __('附加信息','xintheme-pagebuilder'),
				'std' => __( '提供 24 小时技术支持', 'xintheme-pagebuilder' ),
			),

			'pricing_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'std' => array( 'md' => 'center', 'sm' => 'center', 'xs' => 'center' ),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content{ text-align: {{data.pricing_align}}; }'
			),

			// Style Area
			'pricing_content_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '价格特性', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-feature { color: {{data.pricing_content_color}}; }'
			),
			'pricing_content_odd' => array(
				'type' => 'color',
				'title' => __('奇数项背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '价格特性', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-feature div:nth-child(odd) .xtpb-pricing-list { background: {{data.pricing_content_odd}}; }'
			),
			'pricing_content_even' => array(
				'type' => 'color',
				'title' => __('偶数项背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '价格特性', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-feature div:nth-child(even) .xtpb-pricing-list { background: {{data.pricing_content_even}}; }'
			),
			'content_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-pricing-feature',
				'section' => __( '价格特性', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'content_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-pricing-feature { margin: {{data.content_margin}}; }',
				'section' => __( '价格特性', 'xintheme-pagebuilder' ),
			),
			'content_border' => array(
				'type' => 'border',
				'title' => __( '底部边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'bottom' => '1px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#e5e5e5' 
				),
				'tab' => 'style',
				'section' => __( '价格特性', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-list'
			),

			//title
			'title_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '价格标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-title, {{SELECTOR}} .pricing-layout-two .xtpb-pricing-title, {{SELECTOR}} .pricing-layout-one .xtpb-pricing-title { color: {{data.title_color}}; }'
			),
			'title_bg' => array(
				'type' => 'color2',
				'title' => __('背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#36ADFF',
					'clip' => false,
				),
				'section' => __( '价格标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .pricing-layout-two .xtpb-pricing-title-wrap,{{SELECTOR}} .pricing-layout-one .xtpb-pricing-title-wrap',
				'depends' => array(array('pricing_layout', '!=', array('three'))),
			),
			'title_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'28px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-pricing-title',
				'section' => __( '价格标题', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'title_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'unit' => array( 'px','%','em' ),
				'section' => __( '价格标题', 'xintheme-pagebuilder' ),
				'depends' => array(array('pricing_layout', '!=', array('three'))),
				'selector' => '{{SELECTOR}} .xtpb-pricing-title-wrap { border-radius: {{data.title_radius}}; }'
			),
			'title_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-pricing-title-wrap { padding: {{data.title_padding}}; }',
				'section' => __( '价格标题', 'xintheme-pagebuilder' ),
				'depends' => array(array('pricing_layout', '!=', array('three'))),
			),
			'title_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-pricing-title { margin: {{data.title_margin}}; }',
				'section' => __( '价格标题', 'xintheme-pagebuilder' ),
			),
			
			//sub title
			'pricing_subtitle' => array(
				'type' => 'color',
				'title' => __('价格副标题颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '价格副标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-subtitle,{{SELECTOR}} .pricing-layout-two .xtpb-pricing-subtitle, {{SELECTOR}} .pricing-layout-one .xtpb-pricing-subtitle { color: {{data.pricing_subtitle}}; }',
				'depends' => array(array('pricing_layout', '!=', array('three'))),
			),
			'subtitle_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'28px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-pricing-subtitle',
				'section' => __( '价格副标题', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'depends' => array(array('pricing_layout', '!=', array('three'))),
			),
			'subtitle_margin' => array(
				'type' => 'slider',
				'title' => __('底部外边距','xintheme-pagebuilder'),
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
						'md' => '10px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '价格副标题', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-subtitle { margin-bottom: {{data.subtitle_margin}}; display: block; }',
				'depends' => array(array('pricing_layout', '!=', array('three'))),
			),	

			//Icon
			'icon_color' => array(
				'type' => 'color',
				'title' => __('图标','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-table-icon i { color: {{data.icon_color}}; }'
			),
			'icon_bg' => array(
				'type' => 'color',
				'title' => __('图标背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-table-icon i { background: {{data.icon_bg}}; }'
			),
			'icon_section_bg' => array(
				'type' => 'color2',
				'title' => __('图标区域背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#2399ea',
					'clip' => false,
				),
				'selector' => '{{SELECTOR}} .xtpb-pricing-table-icon,{{SELECTOR}} .pricing-layout-two .xtpb-pricing-table-icon,{{SELECTOR}} .pricing-layout-one .xtpb-pricing-table-icon',
				'depends' => array(array('pricing_layout', '!=', array('three'))),
			),
			'icon_size' => array(
				'type' => 'slider',
				'title' => __('图标尺寸','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 100,
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
						'md' => '20px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-table-icon i { font-size: {{data.icon_size}}; }'
			),
			'icon_line_height' => array(
				'type' => 'slider',
				'title' => __('图标行高','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 100,
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
						'md' => '60px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-table-icon i { line-height: {{data.icon_line_height}}; }'
			),
			'icon_width' => array(
				'type' => 'slider',
				'title' => __('图标宽度','xintheme-pagebuilder'),
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
						'md' => '60px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-table-icon i { width: {{data.icon_width}}; }'
			),
			'icon_height' => array(
				'type' => 'slider',
				'title' => __('图标高度','xintheme-pagebuilder'),
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
						'md' => '60px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-table-icon i{ height: {{data.icon_height}}; }'
			),
			'icon_border' => array(
				'type' => 'border',
				'title' => __( '图标边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-table-icon i'
			),
			'icon_radius' => array(
				'type' => 'dimension',
				'title' => __('图标圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '100px', 'right' => '100px', 'bottom' => '100px', 'left' => '100px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-table-icon i { border-radius: {{data.icon_radius}}; }'
			),
			'icon_section_padding' => array(
				'type' => 'dimension',
				'title' => __( '图标内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-pricing-table-icon { padding: {{data.icon_section_padding}}; }',
				'section' => __( '图标', 'xintheme-pagebuilder' ),
			),

			//currency symbol
			'pricing_symbol' => array(
				'type' => 'color',
				'title' => __('货币符号颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '货币符号', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-title-currency { color: {{data.pricing_symbol}}; }'
			),
			'currency_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'28px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '700',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-title-currency',
				'section' => __( '货币符号', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),

			//pricing sales	
			'pricing_sales' => array(
				'type' => 'color',
				'title' => __('价格促销颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '原价', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-title-sales, {{SELECTOR}} .xtpb-pricing-addon-content .xtpb-title-sales-wrapper .xtpb-title-currency{ color: {{data.pricing_sales}}; }'
			),
			'sales_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'16px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-title-sales',
				'section' => __( '原价', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			//pricing text	
			'pricing_color' => array(
				'type' => 'color',
				'title' => __('价格文本颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '价格', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-pricing-text { color: {{data.pricing_color}}; }'
			),
			'pricing_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'32px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-pricing-text',
				'section' => __( '价格', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),	
			'pricing_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-pricing-text { margin: {{data.pricing_margin}}; }',
				'section' => __( '价格', 'xintheme-pagebuilder' ),
			),
	
			//pricing priods	
			'period_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '周期', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-title-period { color: {{data.period_color}}; }',
			),
			'period_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-title-period',
				'section' => __( '周期', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),

			//button	
			'button_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-btn-pricing',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),

			'button_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'std'     => '#ffffff',
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-btn-pricing { color: {{data.button_color}}; }'
			),

			'button_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'std'     => '#ffffff',
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-btn-pricing:hover { color: {{data.button_hcolor}}; }'
			),

			'button_bg' => array(
				'type' => 'color',
				'title' => __('背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'std'     => '#36ADFF',
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-btn-pricing{ background: {{data.button_bg}}; }'
			),

			'button_hover_bg' => array(
				'type' => 'color',
				'title' => __('悬停背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'std'     => '#0074e6',
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-btn-pricing:hover{ background: {{data.button_hover_bg}}; }'
			),
			'button_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-btn-pricing'
			),
			'button_border_hcolor' => array(
				'type' => 'border',
				'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-btn-pricing:hover'
			),
			'button_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-btn-pricing { border-radius: {{data.button_radius}}; }'
			),
			'button_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-btn-pricing { padding: {{data.button_padding}}; }',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content .xtpb-btn-pricing { margin: {{data.button_margin}}; }',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_display' => array(
				'type' => 'select',
				'title' => __('按钮显示方式','xintheme-pagebuilder'),
				'values' => array(
					'btnblock' => __('块级','xintheme-pagebuilder'),
					'btninlineblock' => __('行内块','xintheme-pagebuilder'),
					'btninline' => __('行内','xintheme-pagebuilder'),
				),
				'std' => 'btninlineblock',
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			//Additional	
			'addition_text' => array(
				'type' => 'color',
				'title' => __('文字颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '附加信息', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-title-aditional-info { color: {{data.addition_text}}; }',
			),
			'addition_fontstyle' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-title-aditional-info',
				'section' => __( '附加信息', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),

			//wrap
			'wrap_background' => array(
				'type' => 'background',
				'title' => '',
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content',
				'tab' => 'style',
				'section' => __( '价格卡片', 'xintheme-pagebuilder' ),
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
				'section' => __( '价格卡片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content'
			),
			'wrap_border_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '价格卡片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content:hover{ border-color: {{data.button_border_hcolor}}; }'
			),
			'wrap_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'unit' => array( 'px','%','em' ),
				'section' => __( '价格卡片', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content { border-radius: {{data.wrap_radius}}; }'
			),
			'wrap_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content { padding: {{data.wrap_padding}}; }',
				'section' => __( '价格卡片', 'xintheme-pagebuilder' ),
			),
			'wrap_margin' => array(
				'type' => 'dimension',
				'title' => __( '外边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-pricing-addon-content { margin: {{data.wrap_margin}}; }',
				'section' => __( '价格卡片', 'xintheme-pagebuilder' ),
			),

			//two title wrap
			'two_title_background' => array(
				'type' => 'background',
				'title' => '',
				'selector' => '{{SELECTOR}} .xtpb-pricing-content-one,{{SELECTOR}} .xtpb-pricing-content-two',
				'tab' => 'style',
				'section' => __( '内容区域', 'xintheme-pagebuilder' ),
				'depends' => array(array('pricing_layout', '=', array('one','two'))),
			),
			'two_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __( '内容区域', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-content-one, {{SELECTOR}} .xtpb-pricing-content-two',
				'depends' => array(array('pricing_layout', '=', array('one','two'))),
			),
			'two_border_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '内容区域', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-pricing-content-one:hover, {{SELECTOR}} .xtpb-pricing-content-two:hover{ border-color: {{data.button_border_hcolor}}; }',
				'depends' => array(array('pricing_layout', '=', array('one','two'))),
			),
			'two_title_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'unit' => array( 'px','%','em' ),
				'section' => __( '内容区域', 'xintheme-pagebuilder' ),
				'depends' => array(array('pricing_layout', '=', array('one','two'))),
				'selector' => '{{SELECTOR}} .xtpb-pricing-content-one,{{SELECTOR}} .xtpb-pricing-content-two { border-radius: {{data.two_title_radius}}; }'
			),
			'two_title_padding' => array(
				'type' => 'dimension',
				'title' => __( '内边距', 'xintheme-pagebuilder' ),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-pricing-content-one, {{SELECTOR}} .xtpb-pricing-content-two { padding: {{data.two_title_padding}}; }',
				'section' => __( '内容区域', 'xintheme-pagebuilder' ),
				'depends' => array(array('pricing_layout', '=', array('one','two'))),
			),
		);

		return $settings;
	}

	// Pricing Render HTML
	public function render($data = null){
		$settings 			= $data['settings'];

		$title 				= isset($settings["title"]) ? sanitize_text_field( $settings["title"] ) : '';
		$subtitle 			= isset($settings["subtitle"]) ? sanitize_text_field( $settings["subtitle"] ) : '';
		$pt_icon_list 		= isset($settings["pt_icon_list"]) ? sanitize_text_field( $settings["pt_icon_list"] ) : '';
		$pricing 			= isset($settings["pricing"]) ? sanitize_text_field( $settings["pricing"] ) : '';
		$sales 				= isset($settings["sales"]) ? sanitize_text_field( $settings["sales"] ) : '';
		$currency 			= isset($settings["currency"]) ? sanitize_text_field( $settings["currency"] ) : '';
		$period 			= isset($settings["period"]) ? sanitize_text_field( $settings["period"] ) : '';
		$additional_info 	= isset($settings["additional_info"]) ? sanitize_textarea_field( $settings["additional_info"] ) : '';
		$btn_link 			= isset($settings["btn_link"]) ? $settings["btn_link"] : array();
		$button_text 		= isset($settings["button_text"]) ? sanitize_text_field( $settings["button_text"] ) : '';
		$icon_list 			= isset($settings["icon_list"]) ? sanitize_text_field( $settings["icon_list"] ) : '';
		$icon_position 		= isset($settings["icon_position"]) ? sanitize_text_field( $settings["icon_position"] ) : '';
		$pricing_feature 	= isset($settings["pricing_feature"]) ? $settings["pricing_feature"] : array();
		$pricing_layout 	= isset($settings["pricing_layout"]) ? sanitize_text_field( $settings["pricing_layout"] ) : '';
		$button_display 	= isset($settings["button_display"]) ? sanitize_text_field( $settings["button_display"] ) : 'btnblock';

		$output = $button = $data_pt_media = '';

		if( $pt_icon_list ){
			$data_pt_media  .= '<div class="xtpb-pricing-table-icon">';
			$data_pt_media  .= '<i class="' . esc_attr($pt_icon_list) . '"></i>';
			$data_pt_media  .= '</div>';
		}
		if( $icon_position == 'left' ) {
			$button = (esc_attr($icon_list)) ? '<i class="' . esc_attr($icon_list) . '"></i> ' . esc_attr($button_text) : esc_attr($button_text);
		} elseif( $icon_position == 'right' ){
			$button = (esc_attr($icon_list)) ? esc_attr($button_text) . ' <i class="' . esc_attr($icon_list) . '"></i>' : esc_attr($button_text);
		} else {
			$button = (esc_attr($icon_list)) ? esc_attr($button_text) : esc_attr($button_text);
		}
		$target = !empty($btn_link['window']) ? "_blank" : "_self";
		$nofolow = ! empty($btn_link['nofolow']) ? 'rel="nofollow"' : " ";

		$output  .= '<div class="xtpb-pricing-addon-content pricing-layout-'.esc_attr($pricing_layout).'">';
				
				if( $pricing_layout == "two" ){
					$output  .= '<div class="xtpb-pricing-content-two">';
						if( !empty($title) ){
							$output  .= '<div class="xtpb-pricing-title-wrap">';
								if( !empty($title) ){
									$output  .= '<h3 class="xtpb-pricing-title">' . esc_html( $title ) . '</h3>';
								}
								if ( ! empty( $subtitle ) ) {	
									$output  .= '<span class="xtpb-pricing-subtitle">' . esc_html( $subtitle ) . '</span>';
								}
								if ( ! empty( $pricing ) || ! empty( $sales ) || ! empty( $currency ) || ! empty( $period ) ) {
									$output  .= '<div class="xtpb-pricing-content">';	
										if ( ! empty( $pricing ) ) {
											$output  .= '<div class="xtpb-pricing-text-wrapper">';
												if ( ! empty( $sales ) ) {
													$output  .= '<span class="xtpb-title-sales-wrapper">';
														if ( ! empty( $currency ) ) {
															$output  .= '<span class="xtpb-title-currency">' . esc_html( $currency ) . '</span>';
														}
														$output  .= '<span class="xtpb-title-sales">' . esc_html( $sales ) . '</span>';
														$output  .= '</span>';
												}
												if ( ! empty( $currency ) ) {
													$output  .= '<sup><span class="xtpb-title-currency">' . esc_html( $currency ) . '</span></sup>';
												}
												if ( ! empty( $pricing ) ) {
													$output  .= '<span class="xtpb-pricing-text">' . esc_html( $pricing ) . '</span>';
												}
												if ( ! empty( $period ) ) {
													$output  .= '<sub class="xtpb-title-period">' . esc_html( $period ) . '</sub>';
												}
											$output  .= '</div>';
										}
									$output  .= '</div>';
								}
							$output  .= '</div>';	
						}
						$output  .= $data_pt_media;

						if ( is_array( $pricing_feature ) && count( $pricing_feature ) ) {
							$output  .= '<div class="xtpb-pricing-feature">';
							foreach ( $pricing_feature as $key => $value ) {
								$output  .= '<div class="repeater-'.$key.'">';
									$output  .= '<div class="xtpb-pricing-list">';
									if ( ! empty( $value['icon_list'] ) ) {
										$output  .= '<i class="' . esc_attr( $value['icon_list'] ) . '"></i>';
									}
									if ( ! empty( $value['feature_text'] ) ) {
										$output  .= '<span>' . esc_html( $value['feature_text'] ) . '</span>';
									}
									$output  .= '</div>';
								$output  .= '</div>';
							}
							$output  .= '</div>';
						}
	
						if ( ! empty( $btn_link['link'] ) ) {
							$output  .= '<a class="xtpb-btn-pricing xtpb-' . esc_attr( $button_display ) . '" ' . $nofolow . ' href="' . esc_url( $btn_link['link'] ) . '" target="' . esc_attr( $target ) . '">' . esc_html( $button )  . '</a>';
						}
	
						if ( ! empty( $additional_info ) ) {
							$output  .= '<span class="xtpb-title-aditional-info">' . wp_kses_post( $additional_info ) . '</span>';
						}

					$output  .= '</div>';//xtpb-pricing-content-two

				} elseif ( $pricing_layout == "three" ) {

					if ( ! empty( $title ) || ! empty( $subtitle ) ) {
						$output  .= '<div class="xtpb-pricing-title-wrap">';
							if ( ! empty( $title ) ) {
								$output  .= '<h3 class="xtpb-pricing-title">' . esc_html( $title ) . '</h3>';
							}
							$output  .= '</div>';
					}

					if ( ! empty( $pricing ) || ! empty( $sales ) || ! empty( $currency ) || ! empty( $period ) ) {
						$output  .= '<div class="xtpb-pricing-content">';	

							if ( ! empty( $pricing ) ) {
								$output  .= '<div class="xtpb-pricing-text-wrapper">';
									if ( ! empty( $sales ) ) {
										$output  .= '<span class="xtpb-title-sales-wrapper">';
											if ( ! empty( $currency ) ) {
												$output  .= '<span class="xtpb-title-currency">' . esc_html( $currency ) . '</span>';
											}
											$output  .= '<span class="xtpb-title-sales">' . esc_html( $sales ) . '</span>';
											$output  .= '</span>';
									}

									if ( ! empty( $currency ) ) {
										$output  .= '<sup><span class="xtpb-title-currency">' . esc_html( $currency ) . '</span></sup>';
									}
									if ( ! empty( $pricing ) ) {
										$output  .= '<span class="xtpb-pricing-text">' . esc_html( $pricing ) . '</span>';
									}
									if ( ! empty( $period ) ) {
										$output  .= '<sub class="xtpb-title-period">' . esc_html( $period ) . '</sub>';
									}
								$output  .= '</div>';
							}
						$output  .= '</div>';
					}

					if ( is_array( $pricing_feature ) && count( $pricing_feature ) ) {
						$output  .= '<div class="xtpb-pricing-feature">';
							foreach ( $pricing_feature as $key => $value ) {
								$output  .= '<div class="repeater-'. esc_attr( $key ) .'">';
									$output  .= '<div class="xtpb-pricing-list">';
									if ( ! empty( $value['icon_list'] ) ) {
										$output  .= '<i class="' . esc_attr( $value['icon_list'] ) . '"></i>';
									}
									if ( ! empty( $value['feature_text'] ) ) {
										$output  .= '<span>' . esc_html( $value['feature_text'] ) .'</span>';
									}
									$output  .= '</div>';
								$output  .= '</div>';
							}
						$output  .= '</div>';
					}

					if ( ! empty( $btn_link['link'] ) ) {
						$output  .= '<a class="xtpb-btn-pricing  xtpb-' . esc_attr( $button_display ) . '" ' . $nofolow . ' href="' . esc_url( $btn_link['link'] ) . '" target="' . esc_attr( $target ) . '">' . esc_html( $button ) . '</a>';
					}

					if ( ! empty( $additional_info ) ) {
						$output  .= '<span class="xtpb-title-aditional-info">' . wp_kses_post( $additional_info ) . '</span>';
					}

				} else {
					$output  .= '<div class="xtpb-pricing-content-one">';
						if ( ! empty( $title ) ) {
							$output  .= '<div class="xtpb-pricing-title-wrap">';
								if ( ! empty( $title ) ) {
									$output  .= '<h3 class="xtpb-pricing-title">' . esc_html( $title ) . '</h3>';
								}
								if ( ! empty( $subtitle ) ) {	
									$output  .= '<span class="xtpb-pricing-subtitle">' . esc_html( $subtitle ) . '</span>';
								}
								$output  .= '</div>';
						}

						$output  .= $data_pt_media;

						if ( ! empty( $pricing ) || ! empty( $sales ) || ! empty( $currency ) || ! empty( $period ) ) {
							$output  .= '<div class="xtpb-pricing-content">';	

								if ( ! empty( $pricing ) ) {
									$output  .= '<div class="xtpb-pricing-text-wrapper">';
										if ( ! empty( $sales ) ) {
											$output  .= '<span class="xtpb-title-sales-wrapper">';
												if ( ! empty( $currency ) ) {
													$output  .= '<span class="xtpb-title-currency">' . esc_html( $currency ) . '</span>';
												}
												$output  .= '<span class="xtpb-title-sales">' . esc_html( $sales ) . '</span>';
												$output  .= '</span>';
										}

										if ( ! empty( $currency ) ) {
											$output  .= '<sup><span class="xtpb-title-currency">' . esc_html( $currency ) . '</span></sup>';
										}
										if ( ! empty( $pricing ) ) {
											$output  .= '<span class="xtpb-pricing-text">' . esc_html( $pricing ) . '</span>';
										}
										if ( ! empty( $period ) ) {
											$output  .= '<sub class="xtpb-title-period">' . esc_html( $period ) . '</sub>';
										}
									$output  .= '</div>';
								}
							$output  .= '</div>';
						}
						if ( isset( $pricing_feature ) && count( $pricing_feature ) ) {
							$output  .= '<div class="xtpb-pricing-feature">';
							foreach ( $pricing_feature as $key => $value ) {
								$output  .= '<div class="repeater-' . esc_attr( $key ) . '">';
									$output  .= '<div class="xtpb-pricing-list">';
									if ( ! empty( $value['icon_list'] ) ) {
										$output  .= '<i class="' . esc_attr( $value['icon_list'] ) . '"></i>';
									}
									if ( ! empty( $value['feature_text'] ) ) {
										$output  .= '<span>' . esc_html( $value['feature_text'] ) . '</span>';
									}
									$output  .= '</div>';
								$output  .= '</div>';
							}
							$output  .= '</div>';
						}

						if ( ! empty( $btn_link['link'] ) ) {
							$output  .= '<a class="xtpb-btn-pricing  xtpb-' . esc_attr( $button_display ) . '" ' . $nofolow . ' href="' . esc_url( $btn_link['link'] ) . '" target="' . esc_attr( $target ) . '">' . esc_html( $button ) . '</a>';
						}

						if ( !empty( $additional_info ) ) {
							$output  .= '<span class="xtpb-title-aditional-info">' . wp_kses_post( $additional_info ) . '</span>';
						}
					$output .= '</div>';	
				}
		$output  .= '</div>';

		return $output;
	}

	// headline Template
	public function getTemplate(){
		$output = '
		<#
			var data_pt_media = "";
			if( data.pt_icon_list ){
				data_pt_media  += "<div class=\'xtpb-pricing-table-icon\'>";
				data_pt_media  += "<i class=\' "+data.pt_icon_list+"\'></i>";
				data_pt_media  += "</div>";
			}
			if(data.featured_check == "1"){
				featured_check = "xtpb-pricing-featured";
			} else {
				featured_check = "";
			}
		#>
		
		<div class="xtpb-pricing-addon-content pricing-layout-{{data.pricing_layout}}">

				<# if( data.pricing_layout == "two" ){ #>
					<div class="xtpb-pricing-content-two">
						<div class="xtpb-pricing-title-wrap">
							<# if( !_.isEmpty(data.title) ){ #>	
								<h3 class="xtpb-pricing-title">{{data.title}}</h3>
							<# } #>
							<# if( !_.isEmpty(data.subtitle) ){ #>	
								<span class="xtpb-pricing-subtitle">{{data.subtitle}}</span>
							<# } #>
							<# if( !_.isEmpty(data.pricing) || !_.isEmpty(data.currency) ){ #>
								<div class="xtpb-pricing-content">			
									<# if( !_.isEmpty(data.pricing) ){ #>
										<div class="xtpb-pricing-text-wrapper">
											<# if( !_.isEmpty(data.sales) ){ #>
												<span class="xtpb-title-sales-wrapper">
													<# if( !_.isEmpty(data.currency) ){ #>
														<span class="xtpb-title-currency">{{data.currency}}</span>
													<# } #>
													<span class="xtpb-title-sales">{{data.sales}}</span>
												</span>
											<# } #>
											<# if( !_.isEmpty(data.currency) ){ #>
												<sup><span class="xtpb-title-currency">{{data.currency}}</span></sup>
											<# } #>
											<# if( !_.isEmpty(data.pricing) ){ #>
											<span class="xtpb-pricing-text">{{data.pricing}}</span>
											<# } #>
											<# if( !_.isEmpty(data.period) ){ #>
												<sub class="xtpb-title-period">{{data.period}}</sub>
											<# } #>
										</div>
									<# } #>
								</div>
							<# } #>
						</div>	
						{{{data_pt_media}}}

					<div class="xtpb-pricing-feature">
						<#  _.forEach(data.pricing_feature, function(value, key) { #>
							<div class="repeater-{{key}}"><div class="xtpb-pricing-list"><i class=" {{value.icon_list}}"></i><span>{{value.feature_text}}</span></div></div>
						<# }); #>
					</div>
	
					<# if( data.btn_link.link ){ #>	
						<a class="xtpb-btn-pricing xtpb-{{ data.button_display }}" {{ data.btn_link.nofolow ? "rel=\"nofollow\"" : "" }} href="{{data.btn_link.link}}" target="{{data.btn_link.window ? "_blank" : "_self"}}">
						<# if(data.icon_position == "left" && !_.isEmpty(data.icon_list)) { #><i class=" {{ data.icon_list }}"></i> <# } #>{{data.button_text}}<# if(data.icon_position == "right" && !_.isEmpty(data.icon_list)) { #> <i class=" {{ data.icon_list }}"></i><# } #>
						</a>
					<# } #>
	
					<# if( !_.isEmpty(data.additional_info) ){ #>
						<span class="xtpb-title-aditional-info">{{data.additional_info}}</span>
					<# } #>
						
				</div>	

			<# } else if( data.pricing_layout == "three" ){ #>

					<div class="xtpb-pricing-title-three">
						<# if( !_.isEmpty(data.title) ){ #>	
							<h3 class="xtpb-pricing-title">{{data.title}}</h3>
						<# } #>
					</div>

					<# if( !_.isEmpty(data.pricing) || !_.isEmpty(data.currency) ){ #>
						<div class="xtpb-pricing-content">			
							<# if( !_.isEmpty(data.pricing) ){ #>
								<div class="xtpb-pricing-text-wrapper">
									<# if( !_.isEmpty(data.sales) ){ #>
										<span class="xtpb-title-sales-wrapper">
											<# if( !_.isEmpty(data.currency) ){ #>
												<span class="xtpb-title-currency">{{data.currency}}</span>
											<# } #>
											<span class="xtpb-title-sales">{{data.sales}}</span>
										</span>
									<# } #>
									<# if( !_.isEmpty(data.currency) ){ #>
										<sup><span class="xtpb-title-currency">{{data.currency}}</span></sup>
									<# } #>
									<# if( !_.isEmpty(data.pricing) ){ #>
									<span class="xtpb-pricing-text">{{data.pricing}}</span>
									<# } #>
									<# if( !_.isEmpty(data.period) ){ #>
										<sub class="xtpb-title-period">{{data.period}}</sub>
									<# } #>
								</div>
							<# } #>
						</div>
					<# } #>

					<div class="xtpb-pricing-feature">
						<#  _.forEach(data.pricing_feature, function(value, key) { #>
							<div class="repeater-{{key}}"><div class="xtpb-pricing-list"><i class=" {{value.icon_list}}"></i><span>{{value.feature_text}}</span></div></div>
						<# }); #>
					</div>

					<# if( data.btn_link.link ){ #>	
						<a class="xtpb-btn-pricing xtpb-{{ data.button_display }}" {{ data.btn_link.nofolow ? "rel=\"nofollow\"" : "" }} href="{{data.btn_link.link}}" target="{{data.btn_link.window ? "_blank" : "_self"}}">
						<# if(data.icon_position == "left" && !_.isEmpty(data.icon_list)) { #><i class=" {{ data.icon_list }}"></i> <# } #>{{data.button_text}}<# if(data.icon_position == "right" && !_.isEmpty(data.icon_list)) { #> <i class=" {{ data.icon_list }}"></i><# } #>
						</a>
					<# } #>

					<# if( !_.isEmpty(data.additional_info) ){ #>
						<span class="xtpb-title-aditional-info">{{data.additional_info}}</span>
					<# } #>

				<# } else { #>
					<div class="xtpb-pricing-content-two">
						<div class="xtpb-pricing-title-wrap">
							<# if( !_.isEmpty(data.title) ){ #>	
								<h3 class="xtpb-pricing-title">{{data.title}}</h3>
							<# } #>
							<# if( !_.isEmpty(data.subtitle) ){ #>	
								<span class="xtpb-pricing-subtitle">{{data.subtitle}}</span>
							<# } #>
						</div>

						{{{data_pt_media}}}

						<# if( !_.isEmpty(data.pricing) || !_.isEmpty(data.currency) ){ #>
							<div class="xtpb-pricing-content">			
								<# if( !_.isEmpty(data.pricing) ){ #>
									<div class="xtpb-pricing-text-wrapper">
										<# if( !_.isEmpty(data.sales) ){ #>
											<span class="xtpb-title-sales-wrapper">
												<# if( !_.isEmpty(data.currency) ){ #>
													<span class="xtpb-title-currency">{{data.currency}}</span>
												<# } #>
												<span class="xtpb-title-sales">{{data.sales}}</span>
											</span>
										<# } #>
										<# if( !_.isEmpty(data.currency) ){ #>
											<sup><span class="xtpb-title-currency">{{data.currency}}</span></sup>
										<# } #>
										<# if( !_.isEmpty(data.pricing) ){ #>
										<span class="xtpb-pricing-text">{{data.pricing}}</span>
										<# } #>
										<# if( !_.isEmpty(data.period) ){ #>
											<sub class="xtpb-title-period">{{data.period}}</sub>
										<# } #>
									</div>
								<# } #>
							</div>
						<# } #>

						<div class="xtpb-pricing-feature">
							<#  _.forEach(data.pricing_feature, function(value, key) { #>
								<div class="repeater-{{key}}"><div class="xtpb-pricing-list"><i class=" {{value.icon_list}}"></i><span>{{value.feature_text}}</span></div></div>
							<# }); #>
						</div>

						<# if( data.btn_link.link ){ #>	
							<a class="xtpb-btn-pricing xtpb-{{ data.button_display }}" {{ data.btn_link.nofolow ? "rel=\"nofollow\"" : "" }} href="{{data.btn_link.link}}" target="{{data.btn_link.window ? "_blank" : "_self"}}">
							<# if(data.icon_position == "left" && !_.isEmpty(data.icon_list)) { #><i class=" {{ data.icon_list }}"></i> <# } #>{{data.button_text}}<# if(data.icon_position == "right" && !_.isEmpty(data.icon_list)) { #> <i class=" {{ data.icon_list }}"></i><# } #>
							</a>
						<# } #>

						<# if( !_.isEmpty(data.additional_info) ){ #>
							<span class="xtpb-title-aditional-info">{{data.additional_info}}</span>
						<# } #>
					</div>	
				<# } #>	
				</div>
		';
		return $output;
	}
}
