<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Accordion{

	public function get_name(){
		return 'xtpb_accordion';
	}
	public function get_title(){
		return '手风琴';
	}
	public function get_icon() {
		return 'xtpb-font-accordian';
	}

	// Accordion Settings Fields
	public function get_settings() {

		$settings = array(

			'accordion_list' => array(
				'title' => __('手风琴','xintheme-pagebuilder'),
				'type'  => 'repeatable',
				'label' => 'title',
				'max' => 3,
				'std' => array(
					array( 
						'title' => '页面构建器',
						'icon_list' => 'ri-star-line',
						'icon_position' => 'left',
						'content' => '强大的页面构建功能，让你无需编写代码，也能快速创建灵活、美观的页面布局。适合企业官网、产品展示、博客资讯等多种网站场景。',
					),
					array( 
						'title' => '拖拽编辑',
						'icon_list' => 'ri-drag-move-line',
						'icon_position' => 'right',
						'content' => '支持直观的拖拽式操作，模块可以自由添加、移动和调整，让页面编辑更加简单高效，降低网站搭建和维护成本。',
					),
					array( 
						'title' => 'WordPress 主题',
						'icon_position' => 'right',
						'content' => '专为 WordPress 打造的主题方案，兼顾设计美观、功能扩展和使用体验，帮助你快速搭建专业的网站。',
					),
				),
				'attr' => array(
					'title' => array(
						'type' => 'text',
						'title' => __('项目标题','xintheme-pagebuilder'),
						'std' => '页面构建器',
					),
					'icon_list' => array(
						'type' => 'icon',
						'title' => __('图标','xintheme-pagebuilder'),
						'std' => 'ri-star-line',
					),
					'icon_position' => array(
						'type' => 'select',
						'title' => __('标题图标位置','xintheme-pagebuilder'),
						'depends' => array(array('icon_list', '!=', '')),
						'values' => array(
							'left' => __('左侧','xintheme-pagebuilder'),
							'right' => __('右侧','xintheme-pagebuilder'),
						),
						'std' => 'left',
					),
					'content' => array(
						'type' => 'editor',
						'title' => __('内容','xintheme-pagebuilder'),
						'std' => '强大的页面构建功能，让你无需编写代码，也能快速创建灵活、美观的页面布局。适合企业官网、产品展示、博客资讯等多种网站场景。',
					),
				),
			),
			'openitem' => array(
				'type' => 'select',
				'title' => __('行为','xintheme-pagebuilder'),
				'values' => array(
					'' => __('默认展开第一项','xintheme-pagebuilder'),
					'show' => __('展开所有项目','xintheme-pagebuilder'),
					'hide' => __('收起所有项目','xintheme-pagebuilder'),
				),
				'std' => '',
			),
			'navigation' => array(
				'type' => 'select',
				'title' => __('导航样式','xintheme-pagebuilder'),
				'values' => array(
					'' => __('无','xintheme-pagebuilder'),
					'ri-add-line' => __('加号','xintheme-pagebuilder'),
					'ri-add-circle-line' => __('圆形加号','xintheme-pagebuilder'),
					'ri-add-box-line' => __('实心方形加号','xintheme-pagebuilder'),
					'ri-arrow-right-circle-line' => __('实心圆形箭头','xintheme-pagebuilder'),
					'ri-arrow-right-s-line' => __('尖角','xintheme-pagebuilder'),
					'ri-arrow-right-double-line' => __('双尖角','xintheme-pagebuilder'),
					'ri-arrow-right-s-line' => __('V 形箭头','xintheme-pagebuilder'),
					'ri-arrow-right-circle-line' => __('圆形 V 形箭头','xintheme-pagebuilder'),
					'ri-arrow-right-s-fill' => __('三角箭头','xintheme-pagebuilder'),
				),
				'std' => 'ri-arrow-right-s-line',
			),
			'navigation_direction' => array(
				'type' => 'select',
				'title' => __('导航方向','xintheme-pagebuilder'),
				'values' => array(
					'left' => __('左侧','xintheme-pagebuilder'),
					'right' => __('右侧','xintheme-pagebuilder'),
				),
				'std' => 'right',
			),

			// Style Area
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
				'selector' => '{{SELECTOR}} .xtpb-accordion-title',
				'section' => '标题',
			),

			'title_color' => array(
				'type' => 'color2',
				'title' => '颜色',
				'clip' => true,
				'selector' => '{{SELECTOR}} .xtpb-accordion-title-content',
				'tab' =>'style',
				'std' => array(
					'colorType' => 'color',
					'clip' => true,
					'colorColor' => '#666666',
				),
				'section' => '标题',
			),
			
			'title_bg2' => array(
				'type' => 'color2',
				'title' => '背景颜色',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title',
				'tab' =>'style',
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#f8f8f8',
					'clip' => false
				),
				'section' => '标题',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title',
			),

			'title_active_color' => array(
				'type' => 'color2',
				'title' => __('激活颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => '标题',
				'clip' => true,
				'std' => array(
					'colorType' => 'color',
					'clip' => true,
					'colorColor' => '#101010',
				),
				'selector' => '{{SELECTOR}} .xtpb-accordion-title.active .xtpb-accordion-title-content'
			),

			'title_active_bg'=> array(
				'type' => 'color2',
				'title' => __('激活背景颜色','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-accordion-title.active',
				'tab' => 'style',
				'clip' => false,
				'std' => array(
					'clip' => false,
					'colorType' => 'color',
					'colorColor' => '#f8f8f8',
				),
				'section' => '标题',
			),
			'title_active_border'=> array(
				'type' => 'color',
				'std' => '#f8f8f8',
				'title' => __('激活边框','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => '标题',
				'std'	=> '#e5e5e5',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title.active { border-color: {{data.title_active_border}}; }'
			),
			'title_border_radius' => array(
				'type' => 'dimension',
				'title' => __('标题边框圆角','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'section' => '标题',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title { border-radius: {{data.title_border_radius}}; }'
			),
			'title_sept_space' => array(
				'type' => 'slider',
				'title' => __('标题分隔间距','xintheme-pagebuilder'),
				'unit' => array( 'px','em' ),
				'range' => array(
						'em' => array(
							'min' => -10,
							'max' => 20,
							'step' => 1,
						),
						'px' => array(
							'min' => -20,
							'max' => 150,
							'step' => 1,
						),
					),
				'std' => array(
						'md' => '-1px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => '标题',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title:not(.active) { margin-top: {{data.title_sept_space}}; }',
			),
			'title_padding' => array(
				'type' => 'dimension',
				'title' => '内边距',
				'std' => array(
						'md' => array( 'top' => '10px', 'right' => '30px', 'bottom' => '10px', 'left' => '15px' ),
						'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
						'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => '标题',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title { padding: {{data.title_padding}}; }'
			),
			'icon_size' => array(
				'type' => 'slider',
				'title' => __('图标尺寸','xintheme-pagebuilder'),
				'unit' => array( 'px','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 100,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
				'std' => array(
						'md' => '12px',
						'sm' => '',
						'xs' => '',
					),
				'responsive' => true,
				'section' => '图标',
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title > i{ font-size: {{data.icon_size}}; }',
			),
			'icon_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => '图标',
				'std'       => '#666666',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title > i{ color: {{data.icon_color}}; }'
			),
			'icon_active_color' => array(
				'type' => 'color',
				'title' => __('激活颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => '图标',
				'std'       => '#101010',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title.active > i { color: {{data.icon_active_color}}; }'
			),
			'nav_icon_size' => array(
				'type' => 'slider',
				'title' => __('导航图标大小','xintheme-pagebuilder'),
				'unit' => array( 'px','em' ),
				'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 100,
							'step' => .1,
						),
						'px' => array(
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
				'section' => '图标',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title .xtpb-toggle-direction{ font-size: {{data.nav_icon_size}}; }',
			),
			'nav_icon_color' => array(
				'type' => 'color',
				'title' => __('导航图标颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => '图标',
				'std'       => '#666666',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title .xtpb-toggle-direction{ color: {{data.nav_icon_color}}; }'
			),

			'nav_icon_active_color' => array(
				'type' => 'color',
				'title' => __('导航图标激活颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => '图标',
				'std'       => '#000000',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title.active .xtpb-toggle-direction{ color: {{data.nav_icon_active_color}}; }'
			),

			'content_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => '内容',
				'std'       => '#888888',
				'selector' => '{{SELECTOR}} .xtpb-accordion-content { color: {{data.content_color}}; }'
			),
			'content_bgcolor' => array(
				'type' => 'color',
				'title' => __('背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => '内容',
				'std'       => '#ffffff',
				'selector' => '{{SELECTOR}} .xtpb-accordion-content { background: {{data.content_bgcolor}}; }'
			),
			'content_style' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-accordion-content',
				'section' => '内容',
			),
			'content_border_radius' => array(
				'type' => 'dimension',
				'title' => __('内容边框圆角','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'section' => '内容',
				'selector' => '{{SELECTOR}} .xtpb-accordion-content { border-radius: {{data.content_border_radius}}; }'
			),
			'content_margin' => array(
				'type' => 'dimension',
				'title' => __('内容外边距','xintheme-pagebuilder'),
				'std' => array(
						'md' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
						'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
						'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
					),
				'responsive' => true,
				'tab' => 'style',
				'section' => '内容',
				'selector' => '{{SELECTOR}} .xtpb-accordion-content { margin: {{data.content_margin}}; }'
			),
			'content_padding' => array(
				'type' => 'dimension',
				'title' => '内边距',
				'std' => array(
						'md' => array( 'top' => '15px', 'right' => '15px', 'bottom' => '15px', 'left' => '15px' ),
						'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
						'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
					),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => '内容',
				'selector' => '{{SELECTOR}} .xtpb-accordion-content{ padding: {{data.content_padding}}; }'
			),
			'content_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'section' => '内容',
				'selector' => '{{SELECTOR}} .xtpb-accordion-content { text-align: {{data.content_align}}; }'
			),
			'item_border' => array(
				'type' => 'border',
				'title' => __('标题边框','xintheme-pagebuilder'),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#e5e5e5' 
				),
				'tab' => 'style',
				'section' => '边框',
				'selector' => '{{SELECTOR}} .xtpb-accordion-title'
			),
			'content_border' => array(
				'type' => 'border',
				'title' => __('内容边框','xintheme-pagebuilder'),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '0px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#e5e5e5' 
				),
				'tab' => 'style',
				'section' => '边框',
				'selector' => '{{SELECTOR}} .xtpb-accordion-content'
			),

		);

		return $settings;
	}

	// Accordion Render HTML
	public function render($data = null){
		$settings 			= $data['settings'];
		$accordion_list 	= isset($settings["accordion_list"]) ? $settings["accordion_list"] : array();
		$openitem 			= isset($settings["openitem"]) ? $settings["openitem"] : '';
		$navigation 		= isset($settings["navigation"]) ? $settings["navigation"] : 'ri-arrow-right-s-line';
		$navigation_direction = isset($settings["navigation_direction"]) ? $settings["navigation_direction"] : 'right';

		$output = '';

		$output  .= '<div class="xtpb-accordion-addon-content">';
		$output  .= '<div class="xtpb-accordion-items">';
		if (is_array($accordion_list) && count($accordion_list)){
			foreach ( $accordion_list as $key => $value ) {
				$output  .= '<div class="xtpb-accordion-item repeater-'.$key.'">';
				$activeClass = (($key == 0 || $openitem == "show") &&  $openitem != "hide") ? "active" : "";
				$output  .= '<div class="xtpb-accordion-title xtpb-toggle-'.$navigation_direction.' '.$activeClass.'">';
				if( $navigation_direction == 'left' ) {
					if($navigation) {
						$output  .= '<span class="xtpb-toggle-direction xtpb-toggle-'.$navigation_direction.'"><i class="' . esc_attr( $navigation ) . '"></i></span>';
					}
				}
				if(get_xtpb_array_value_by_key($value, 'icon_position') == "left" ) {
					if(! empty($value['icon_list'])){
						$output  .= '<i class="'.$value['icon_list'].'"></i>';
					}
				}
				if( get_xtpb_array_value_by_key($value, 'title') ){
					$output  .= '<span class="xtpb-accordion-title-content">' . esc_html( $value['title'] ).'</span>';
				}
				if(get_xtpb_array_value_by_key($value, 'icon_position') == "right" ) {
					if(! empty($value['icon_list'])){
						$output  .= '<i class="'.$value['icon_list'].'"></i>';
					}
				}
				if( $navigation_direction == 'right' ) {
					if($navigation) {
						$output  .= '<span class="xtpb-toggle-direction xtpb-toggle-'.$navigation_direction.'"><i class="' . esc_attr( $navigation ) . '"></i></span>';
					}
				}


				$output  .= '</div>';//xtpb-accordion-title

				$panelStyle = (($key != 0 || $openitem == "hide") && $openitem != "show") ? "display: none;" : "";
				$output  .= '<div class="xtpb-panel-collapse" style="'.$panelStyle.'">';
				$output  .= '<div class="xtpb-accordion-content">' . wp_kses_post( $value['content'] ) . '</div>';
				$output  .= '</div>';//xtpb-panel-collapse
				$output  .= '</div>';
			}
		}

		$output  .= '</div>';
		$output  .= '</div>';

		return $output;
	}

	// Accordion Template
	public function getTemplate(){
		$output = '
			<div class="xtpb-accordion-addon-content">
					<div class="xtpb-accordion-items">
						<#  __.forEach(data.accordion_list, function(value, key) { #>
							<div class="xtpb-accordion-item repeater-{{key}}">

								<# var navigation_direction = data.navigation_direction ? data.navigation_direction : "right" #>
								<# var activeClass = ((key == 0 || data.openitem == "show") &&  data.openitem != "hide") ? "active" : ""; #>
								
								<div class="xtpb-accordion-title xtpb-toggle-{{navigation_direction}} {{ activeClass }}">
									<# if(navigation_direction == "left") { #>
										<# if(data.navigation) { #>
											<span class="xtpb-toggle-direction xtpb-toggle-{{navigation_direction}}"><i class="{{data.navigation}}"></i></span>
										<# } #>	
									<# } #>	
									<# if(value.icon_position == "left" && !__.isEmpty(value.icon_list)) { #> <i class="{{ value.icon_list }}"></i><# } #><span class="xtpb-accordion-title-content">{{value.title}}</span><# if(value.icon_position == "right" && !__.isEmpty(value.icon_list)) { #> <i class=" {{ value.icon_list }}"></i><# } #>

									<# if(navigation_direction == "right") { #>
										<# if(data.navigation) { #>
											<span class="xtpb-toggle-direction xtpb-toggle-{{navigation_direction}}"><i class="{{data.navigation}}"></i></span>
										<# } #>	
									<# } #>	
								</div>

								<# var panelStyle = ((key != 0 || data.openitem == "hide") && data.openitem != "show") ? "display: none;" : ""; #>
								<div class="xtpb-panel-collapse" style="{{ panelStyle }}">
									<div class="xtpb-accordion-content">{{{value.content}}}</div>
								</div>

							</div>
						<# }); #>
					</div>
				</div>
		';
		return $output;
	}

}
