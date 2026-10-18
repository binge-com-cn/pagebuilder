<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'XTPB_Base_Data' ) ) {
	class XTPB_Base_Data {
		protected function get_component_class_name( $data, $target ) {
			$target = sanitize_key( $target );

			if ( 'row' === $target ) {
				if ( method_exists( $data, 'get_row_class' ) ) {
					return trim( $data->get_row_class() );
				}

				return method_exists( $data, 'get_class' ) ? trim( $data->get_class() ) : '';
			}

			if ( 'column' === $target ) {
				if ( method_exists( $data, 'get_column_class' ) ) {
					return trim( $data->get_column_class() );
				}

				return method_exists( $data, 'get_col_class' ) ? trim( $data->get_col_class() ) : '';
			}

			if ( 'addon' === $target ) {
				if ( method_exists( $data, 'get_addon_class' ) ) {
					return trim( $data->get_addon_class() );
				}

				if ( method_exists( $data, 'get_component_class' ) ) {
					return trim( $data->get_component_class() );
				}
			}

			return '';
		}

		// Addons List & Category return
		public function addons_data(){
			$default_addon = array(
				'xtpb_accordion', 'xtpb_alert', 'xtpb_animated_number', 'xtpb_block_number', 'xtpb_button', 'xtpb_button_group',
				'xtpb_form', 'xtpb_feature_box', 'xtpb_flip_box', 'xtpb_heading', 'xtpb_icon', 'xtpb_image',
				'xtpb_image_hover', 'xtpb_person', 'xtpb_person_carousel', 'xtpb_pie_progress', 'xtpb_posts_grid', 'xtpb_pricing_table',
				'xtpb_progress_bar', 'xtpb_raw_html', 'xtpb_social_button', 'xtpb_tab', 'xtpb_testimonial',
				'xtpb_testimonial_carousel', 'xtpb_text_block', 'xtpb_video', 'xtpb_video_popup','xtpb_carousel'
			);

			$available_addons = xtpb_helper()->get_addon_instances();
			$available_modules = xtpb_helper()->get_module_classes();

			$addons_list = array();
			if( !empty($available_addons) ){
				foreach ($available_addons as $addon_class => $data) {
					$addon_name = $data->get_name();
					$addon_settings = $data->get_settings();
					$addon_icons = ( method_exists( $data , 'get_icon' ) ?  $data->get_icon() : 'xtpb-font-wordpress' );
					$is_module = in_array( $addon_class, $available_modules, true );
					$addons_list[$addon_name] = array(
						"type" 		=> "content",
						"addon_name"=> $addon_name,
						"title" 	=> $data->get_title(),
						"name" 		=> $data->get_name(),
						"category"  => ( method_exists( $data , 'get_category_name' ) ? $data->get_category_name() : '' ),
						"visibility"=> true,
						"icon"		=> $addon_icons,
						"thumb"		=> ( method_exists( $data , 'get_thumb' ) ? $data->get_thumb() : '' ),
							"row_class" => $this->get_component_class_name( $data, 'row' ),
							"column_class" => $this->get_component_class_name( $data, 'column' ),
							"addon_class" => $this->get_component_class_name( $data, 'addon' ),
							"attr"		=> array( 'general' => $addon_settings ),
							"defaultAddon" => ( ! $is_module && in_array( $data->get_name(), $default_addon ) ? true : false ),
							"isModule" => $is_module,
							"editor_render_mode" => ( method_exists( $data, 'get_editor_render_mode' ) ? $data->get_editor_render_mode() : 'template' )
						);

					if( method_exists($addon_class, 'getTemplate') ){
						$addons_list[$addon_name]['js_template'] = true;
					} else {
						$addons_list[$addon_name]['js_template'] = false;
					}
				}
			}
			return array( 'addons' => $addons_list );
		}

		public function modules_data() {
			$modules = array();
			$addons = $this->addons_data();

			if ( ! empty( $addons['addons'] ) && is_array( $addons['addons'] ) ) {
				foreach ( $addons['addons'] as $addon_name => $addon ) {
					if ( empty( $addon['isModule'] ) ) {
						continue;
					}

					$modules[ $addon_name ] = $addon;
				}
			}

			return array( 'modules' => $modules );
		}


		public function getSvgShapes() {
			$shape_path = XTPB_DIR_PATH .'assets/shapes';
			$shapes = glob( $shape_path . '/*.svg' );
			$shapeArray = array();
			if( count( $shapes ) ){
				foreach($shapes as $shape){
					$shapeArray[str_replace( array( '.svg',$shape_path.'/' ), '', $shape)] = base64_encode(file_get_contents( $shape ));
				}
			}
			return $shapeArray;
		}

		public function global_attr(){
			$global_attribute = array(
				'style' => array(
					'initialize_empty'=> array(
						'type' => 'firstid',
						'title' => __( 'ID', 'xintheme-pagebuilder' ),
						'desc' => __( '给当前元素设置一个唯一 ID，便于写 CSS 或做锚点跳转。', 'xintheme-pagebuilder' ),
						'std' => '0',
					),
				),
				'advanced' => array(
					'addon_id' => array(
						'type' => 'text',
						'title' => __( 'ID', 'xintheme-pagebuilder' ),
						'desc' => __( '给当前元素设置一个唯一 ID，便于写 CSS 或做锚点跳转。', 'xintheme-pagebuilder' ),
						'std' => '',
					),
					'addon_class' => array(
						'type' => 'text',
						'title' => __( 'CSS 类名', 'xintheme-pagebuilder' ),
					),
					'addon_z_index' => array(
						'type' => 'slider',
						'title' => __( '层级', 'xintheme-pagebuilder' ),
						'range' => array(
							'min' => -10000,
							'max' => 10000,
							'step' => 1,
						),
						'selector' => '.xtpb-builder-container {{SELECTOR}}{ z-index: {{data.addon_z_index}}; }',
					),
					'addon_padding' => array(
						'type' => 'dimension',
						'title' => __( '内边距', 'xintheme-pagebuilder' ),
						'unit' => array( 'px','em','%' ),
						'responsive' => true,
						'selector' => '{{SELECTOR}}{ padding: {{data.addon_padding}}; }',
						'section' => __( '间距', 'xintheme-pagebuilder' ),
					),
					'addon_margin' => array(
						'type' => 'dimension',
						'title' => __( '外边距', 'xintheme-pagebuilder' ),
						'unit' => array( 'px','em','%' ),
						'responsive' => true,
						'selector' => '{{SELECTOR}}{ margin: {{data.addon_margin}}; }',
						'section' => __( '间距', 'xintheme-pagebuilder' ),
					),
					'addon_background'=> array(
						'type' => 'background',
						'title' => __( '背景', 'xintheme-pagebuilder' ),
						'selector' => '{{SELECTOR}}',
						'section' => __( '背景', 'xintheme-pagebuilder' ),
					),
					'addon_color_opacity'=> array(
						'type' => 'color2',
						'title' => __( '遮罩颜色', 'xintheme-pagebuilder' ),
						'std' => '#00aeff',
						'selector' => '{{SELECTOR}}:after',
						'section' => __( '遮罩', 'xintheme-pagebuilder' ),
          			),
					'addon_opacity' => array(
						'type' => 'slider',
						'title' => __( '遮罩透明度', 'xintheme-pagebuilder' ),
						'range' => array(
							'min' => 0,
							'max' => 1,
							'step' => .01,
            		),
						'selector' => '{{SELECTOR}}:after{ content: " "; z-index: 0; display: block; position: absolute; height: 100%; top: 0; left: 0; right: 0; opacity:{{data.addon_opacity}}; }',
						'section' => __( '遮罩', 'xintheme-pagebuilder' ),
					),
					'addon_color_blend' => array(
						'type' => 'select',
						'title' => __( '混合模式', 'xintheme-pagebuilder' ),
						'values' => array(
							'normal' => '普通',
							'multiply' => 'Multiply',
							'screen' => 'Screen',
							'overlay' => 'Overlay',
							'darken' => 'Darken',
							'lighten' => 'Lighten',
							'color-dodge' => 'Color Dodge',
							'saturation' => 'Saturation',
							'luminosity' => 'Luminosity',
							'color' => '颜色',
							'color-burn' => 'Color Burn',
							'exclusion' => 'Exclusion',
							'hue' => 'Hue',
						),
						'section' => __( '遮罩', 'xintheme-pagebuilder' ),
						'selector' => '{{SELECTOR}}:after{ mix-blend-mode:{{data.addon_color_blend}}; }',
					),
					'addon_border' => array(
						'type' => 'border',
						'title' => __( '边框', 'xintheme-pagebuilder' ),
						'std' => array(
							'itemOpenBorder'=> 0,
							'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
							'borderStyle' => 'solid',
							'borderColor' => '#cccccc'
						),
						'selector' => '{{SELECTOR}}',
						'section' => __( '边框', 'xintheme-pagebuilder' ),
					),
					'addon_border_hover' => array(
						'type' => 'border',
						'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
						'std' => array(
							'itemOpenBorder' => 0,
							'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
							'borderStyle' => 'solid',
							'borderColor' => '#cccccc'
						),
						'selector' => '{{SELECTOR}}:hover',
						'section' => __( '边框', 'xintheme-pagebuilder' ),
					),
					'addon_border_radius_check' => array(
						'type' => 'switch',
						'title' => __( '启用边框圆角', 'xintheme-pagebuilder' ),
						'std' => '0',
						'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
					),
					'addon_border_radius' => array(
						'type' => 'dimension',
						'title' => __( '边框圆角', 'xintheme-pagebuilder' ),
						'unit' => array( 'px','em','%' ),
						'responsive' => true,
						'selector' => '{{SELECTOR}}, {{SELECTOR}}:after{ border-radius: {{data.addon_border_radius}}; overflow:hidden; }',
						'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
						'depends' => array( array( 'addon_border_radius_check', '=', '1' ) ),
					),
					'addon_border_radius_hover' => array(
						'type' => 'dimension',
						'title' => __( '悬停圆角', 'xintheme-pagebuilder' ),
						'unit' => array( 'px','em','%' ),
						'responsive' => true,
						'selector' => '{{SELECTOR}}:hover, {{SELECTOR}}:hover:after{ border-radius: {{data.addon_border_radius_hover}};overflow:hidden; }',
						'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
						'depends' => array( array( 'addon_border_radius_check', '=', '1' ) ),
					),
					'addon_boxshadow' => array(
						'type' => 'boxshadow',
						'title' => __( '盒子阴影', 'xintheme-pagebuilder' ),
                        'std' => array(
                            'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                            'shadowColor' => 'rgba(0,0,0,.3)'
                        ),
						'selector' => '{{SELECTOR}}',
						'section' => __( '盒子阴影', 'xintheme-pagebuilder' ),
					),
					'addon_boxshadow_hover' => array(
						'type' => 'boxshadow',
						'title' => __( '悬停盒子阴影', 'xintheme-pagebuilder' ),
                        'std' => array(
                            'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                            'shadowColor' => 'rgba(0,0,0,.3)'
                        ),
						'selector' => '{{SELECTOR}}:hover',
						'section' => __( '盒子阴影', 'xintheme-pagebuilder' ),
					),
					'addon_animation' => array(
						'type' => 'animation',
						'title' => __( '动画', 'xintheme-pagebuilder' ),
						'section' => __( '动画', 'xintheme-pagebuilder' ),
					),
					'addon_wrap_opacity' => array(
						'type' => 'slider',
						'title' => __( '透明度', 'xintheme-pagebuilder' ),
						'range' => array(
							'min' => 0,
							'max' => 1,
							'step' => .01,
						),
						'selector' => '{{SELECTOR}} .xtpb-addon{ opacity:{{data.addon_wrap_opacity}}; }',
						'section' => __( '透明度', 'xintheme-pagebuilder' ),
					),
					'addon_wrap_opacity_hover' => array(
						'type' => 'slider',
						'title' => __( '悬停透明度', 'xintheme-pagebuilder' ),
						'range' => array(
							'min' => 0,
							'max' => 1,
							'step' => .01,
						),
						'selector' => '{{SELECTOR}} .xtpb-addon:hover{ opacity:{{data.addon_wrap_opacity_hover}}; }',
						'section' => __( '透明度', 'xintheme-pagebuilder' ),
					),
					'addon_hidden_xs' => array(
						'type' => 'switch',
						'title' => __( '在手机端隐藏', 'xintheme-pagebuilder' ),
						'section' => __( '响应式', 'xintheme-pagebuilder' ),
						'std' => '0',
					),
					'addon_hidden_sm' => array(
						'type' => 'switch',
						'title' => __( '在平板端隐藏', 'xintheme-pagebuilder' ),
						'section' => __( '响应式', 'xintheme-pagebuilder' ),
						'std' => '0',
					),
					'addon_hidden_md' => array(
						'type' => 'switch',
						'title' => __( '在桌面端隐藏', 'xintheme-pagebuilder' ),
						'section' => __( '响应式', 'xintheme-pagebuilder' ),
						'std' => '0',
					),
					'addon_hidden_lg' => array(
						'type' => 'switch',
						'title' => __( '在大屏设备隐藏', 'xintheme-pagebuilder' ),
						'section' => __( '响应式', 'xintheme-pagebuilder' ),
						'std' => '0',
					),
					'addon_display' => array(
						'type' => 'select',
						'title' => __( '显示方式', 'xintheme-pagebuilder' ),
						'values' => array(
							'inline' => '行内',
							'block' => '块级',
							'inline-block' => '行内块',
							'inherit' => 'Inherit',
							'initial' => 'Initial',
						),
						'section' => __( '显示', 'xintheme-pagebuilder' ),
						'selector' => '{{SELECTOR}}{ display: {{data.addon_display}} ; }',
					),
					'addon_custom_css' => array(
						'type' => 'textarea',
						'title' => __( '自定义 CSS', 'xintheme-pagebuilder' ),
						'section' => __( '自定义 CSS', 'xintheme-pagebuilder' ),
						'placeholder' => __('在选择器前使用 {{SELECTOR}} 以包裹当前元素，否则将全局生效。','xintheme-pagebuilder'),
						'desc' => __( '需要只影响当前元素时，请在选择器前加上 {{SELECTOR}}；不加时会作为全局 CSS 生效。', 'xintheme-pagebuilder' )
					),
				),
			);
			return $global_attribute;
		}


		function column_settings(){
			$colSettings = array(
				'type'  => 'content',
				'title' => __( '列', 'xintheme-pagebuilder' ),
				'attr'  => array(
					'general' => array(
						'col_height' => array(
							'type' => 'slider',
							'title' => __( '最小高度', 'xintheme-pagebuilder' ),
							'std' => array( 'md'=>'0px','sm'=>'0px','xs'=>'0px' ),
							'unit' => array( 'px','em','%' ),
							'range' => array(
								'em' => array(
									'min' => 0,
									'max' => 250,
									'step' =>.1,
								),
								'px' => array(
									'min' => 0,
									'max' => 2000,
									'step' =>1,
								),
								'%' => array(
									'min' => 0,
									'max' => 100,
									'step' => 1,
								),
							),
							'responsive' => true,
							'selector' => '.xtpb-section__grid > {{SELECTOR}}.xtpb-column, .xtpb-inner-section__grid > {{SELECTOR}}.xtpb-column { min-height: {{data.col_height}}; }'
						),
						'col_content' => array(
							'type' => 'select',
							'title' => __( '内容位置', 'xintheme-pagebuilder' ),
							'values' => array(
								'' => '默认',
								'flex-start' => '顶部',
								'center' => '中间',
								'flex-end' => '底部',
							),
							'std' => '',
							'multiple' => false,
							'selector' => '{{SELECTOR}} { display: -webkit-box; display: -ms-flexbox; display: flex; -webkit-box-align: {{data.col_content}}; -ms-flex-align: {{data.col_content}}; align-items: {{data.col_content}}; align-content: {{data.col_content}}; flex-direction: column; }'
						),
						'col_custom_width' => array(
							'type' => 'columnWidth',
							'title' => __( '宽度', 'xintheme-pagebuilder' ),
							'range' => array(
								'min' => 10,
								'max' => 100,
								'step' => 1,
							),
							'responsive' => true,
							'selector' => ''
						),
						'col_addons_space' => array(
							'type' => 'slider',
							'title' => __( '组件间距', 'xintheme-pagebuilder' ),
							'std' => array(
								'md'=>'0',
								'sm'=>'',
								'xs'=>''
							),
							'unit' => array( 'px','em','%' ),
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
						'selector' => implode(
							'',
							array(
								'{{SELECTOR}} > .xtpb-addon-node:not(:last-child),',
								'{{SELECTOR}} > .xtpb-inner-section:not(:last-child),',
								'{{SELECTOR}} > .xtpb-addon-editor-list > div:not(:last-child),',
								'{{SELECTOR}} > .xtpb-addon-editor-list > .xtpb-inner-section-editor-list:not(:last-child)',
								' { margin-bottom: {{data.col_addons_space}}; }',
							)
						)
					),
						'col_animation' => array(
							'type' => 'animation',
							'title' => __( '动画', 'xintheme-pagebuilder' ),
							'section' => __( '动画', 'xintheme-pagebuilder' ),
						),
					),
					'style' => array(
						'col_padding' => array(
							'type' => 'dimension',
							'title' => __( '内边距', 'xintheme-pagebuilder' ),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'selector' => '.xtpb-builder-container .xtpb-section__grid > .xtpb-column{{SELECTOR}}, .xtpb-builder-container .xtpb-inner-section__grid > .xtpb-column{{SELECTOR}} { padding: {{data.col_padding}}; }'
						),
						'col_margin' => array(
							'type' => 'dimension',
							'title' => __( '外边距', 'xintheme-pagebuilder' ),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'selector' => '.xtpb-builder-container .xtpb-section__grid > .xtpb-column{{SELECTOR}}, .xtpb-builder-container .xtpb-inner-section__grid > .xtpb-column{{SELECTOR}} { margin: {{data.col_margin}}; }'
						),
						'col_border' => array(
							'type' => 'border',
							'title' => __( '边框', 'xintheme-pagebuilder' ),
							'std' => array(
								'itemOpenBorder' => 1,
								'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ),
								'borderStyle' => 'solid',
								'borderColor' => '#f5f5f5'
							),
							'selector' => '{{SELECTOR}}',
							'section' => __( '边框', 'xintheme-pagebuilder' ),
						),
						'col_border_hover' => array(
							'type' => 'border',
							'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
							'std' => array(
								'itemOpenBorder' => 1,
								'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ),
								'borderStyle' => 'solid',
								'borderColor' => '#e5e5e5'
							),
							'selector' => '{{SELECTOR}}:hover',
							'section' => __( '边框', 'xintheme-pagebuilder' ),
						),
						'col_boxshadow' => array(
							'type' => 'boxshadow',
							'title' => __( '盒子阴影', 'xintheme-pagebuilder' ),
							'std' => array(
								'itemOpenShadow' => true,
								'shadowValue' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ), 
								'shadowColor' => '#f5f5f5' 
							),
							'section' => __( '阴影', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}}'
						),
						'col_boxshadow:hover' => array(
							'type' => 'boxshadow',
							'title' => __( '悬停盒子阴影', 'xintheme-pagebuilder' ),
							'std' => array(
								'itemOpenShadow' => true,
								'shadowValue' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ), 
								'shadowColor' => '#f5f5f5' 
							),
							'section' => __( '阴影', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}}:hover'
						),
						'col_border_radius_check' => array(
							'type' => 'switch',
							'title' => __( '启用边框圆角', 'xintheme-pagebuilder' ),
							'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
							'std' => '0'
						),
						'col_border_radius' => array(
							'type' => 'dimension',
							'title' => __( '边框圆角', 'xintheme-pagebuilder' ),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'depends' => array(array('col_border_radius_check', '=', '1')),
							'selector' => '{{SELECTOR}},{{SELECTOR}}:after{ border-radius: {{data.col_border_radius}} ; }',
							'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
						),
						'col_border_radius_hover' => array(
							'type' => 'dimension',
							'title' => __( '悬停圆角', 'xintheme-pagebuilder' ),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'depends' => array(array('col_border_radius_check', '=', '1')),
							'selector' => '{{SELECTOR}}:hover,{{SELECTOR}}:hover:after{ border-radius: {{data.col_border_radius_hover}} ; }',
							'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
						),
						'col_background'=> array(
							'type' => 'background',
							'title' => __( '背景', 'xintheme-pagebuilder' ),
							'video' => true,
							'selector' => '{{SELECTOR}}',
							'section' => __( '背景', 'xintheme-pagebuilder' ),
						),
						'col_color_opacity'=> array(
							'type' => 'color2',
							'title' => __( '遮罩颜色', 'xintheme-pagebuilder' ),
							'std' => '#00aeff',
							'selector' => '{{SELECTOR}}:after',
							'section' => __( '遮罩', 'xintheme-pagebuilder' ),
						),
						'col_opacity' => array(
							'type' => 'slider',
							'title' => __( '遮罩透明度', 'xintheme-pagebuilder' ),
							'range' => array(
								'min' => 0,
								'max' => 1,
								'step' => .01,
							),
							'selector' => '{{SELECTOR}}:after{transition: 300ms; content: " "; display: block; position: absolute; height: 100%; top: 0; left: 0; right: 0; opacity: {{data.col_opacity}}; }',
							'section' => __( '遮罩', 'xintheme-pagebuilder' ),
						),
						'col_color_blend' => array(
							'type' => 'select',
							'title' => __( '混合模式', 'xintheme-pagebuilder' ),
							'values' => array(
								'normal' => '普通',
								'multiply' => 'Multiply',
								'screen' => 'Screen',
								'overlay' => 'Overlay',
								'darken' => 'Darken',
								'lighten' => 'Lighten',
								'color-dodge' => 'Color Dodge',
								'saturation' => 'Saturation',
								'luminosity' => 'Luminosity',
								'color' => '颜色',
								'color-burn' => 'Color Burn',
								'exclusion' => 'Exclusion',
								'hue' => 'Hue',
							),
							'section' => __( '遮罩', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}}:after{ mix-blend-mode:{{data.col_color_blend}}; }',
						),
					),
					'advanced' => array(
						'col_id' => array(
							'type' => 'text',
							'title' => __( '列 ID', 'xintheme-pagebuilder' ),
						),
						'col_class' => array(
							'type' => 'text',
							'title' => __( 'CSS 类名', 'xintheme-pagebuilder' ),
						),
						'col_z_index' => array(
							'type' => 'slider',
							'title' => __( '层级', 'xintheme-pagebuilder' ),
							'range' => array(
								'min' => -10000,
								'max' => 10000,
								'step' => 1,
							),
							'selector' => '.xtpb-section__grid .xtpb-column{{SELECTOR}}, .xtpb-inner-section__grid .xtpb-column{{SELECTOR}}{ z-index: {{data.col_z_index}}; }',
						),
						'col_hidden_xs' => array(
							'type' => 'switch',
							'title' => __( '在手机端隐藏', 'xintheme-pagebuilder' ),
							'section' => __( '响应式', 'xintheme-pagebuilder' ),
							'std' => '0',
						),
						'col_hidden_sm' => array(
							'type' => 'switch',
							'title' => __( '在平板端隐藏', 'xintheme-pagebuilder' ),
							'section' => __( '响应式', 'xintheme-pagebuilder' ),
							'std' => '0',
						),
						'col_hidden_md' => array(
							'type' => 'switch',
							'title' => __( '在桌面端隐藏', 'xintheme-pagebuilder' ),
							'section' => __( '响应式', 'xintheme-pagebuilder' ),
							'std' => '0',
						),
						'col_hidden_lg' => array(
							'type' => 'switch',
							'title' => __( '在大屏设备隐藏', 'xintheme-pagebuilder' ),
							'section' => __( '响应式', 'xintheme-pagebuilder' ),
							'std' => '0',
						),
						'col_custom_css'=> array(
							'type' => 'textarea',
							'title' => __( '自定义 CSS', 'xintheme-pagebuilder' ),
							'section' => __( '自定义 CSS', 'xintheme-pagebuilder' ),
							'placeholder' => __('在选择器前使用 {{SELECTOR}} 以包裹当前元素，否则将全局生效。','xintheme-pagebuilder'),
							'desc' => __( '需要只影响当前列时，请在选择器前加上 {{SELECTOR}}；不加时会作为全局 CSS 生效。', 'xintheme-pagebuilder' ),
						),
					)
				)
			);
			return $colSettings;
		}

		function row_settings(){
			$rowSettings = array(
				'type' => 'content',
				'title' => __( '行', 'xintheme-pagebuilder' ),
				'default' => array( 'row_screen' => 'row-default' ),
				'attr' => array(
					'general' => array(
						'row_screen' => array(
							'type'   => 'select',
							'title'  => __( '宽度显示', 'xintheme-pagebuilder' ),
							'values' => array(
								'row-default'                 => '默认宽度',
								'row-stretch'                 => '背景铺满屏幕',
								'row-container-stretch'       => '内容铺满屏幕',
								'container-stretch-no-gutter' => '内容铺满并去掉边距',
							),
							'std'      => 'row-stretch',
							'multiple' => false,
						),
						'row_height' => array(
							'type' => 'slider',
							'title' => __( '最小高度', 'xintheme-pagebuilder' ),
							'range' => array(
								'min' => 1,
								'max' => 2500,
								'step' => 1,
							),
							'responsive' => true,
							'selector' => '{{SELECTOR}}{ min-height: {{data.row_height}}px; }'
						),
						'row_content' => array(
							'type' => 'select',
							'title' => __( '内容位置', 'xintheme-pagebuilder' ),
							'values' => array(
								'' => '默认',
								'flex-start' => '顶部',
								'center' 	    => '居中',
								'flex-end' => '底部',
							),
						'std' => '',
						'multiple' => false,
						'selector' => '.xtpb-section{{SELECTOR}} > .xtpb-section__inner > .xtpb-section__grid, .xtpb-inner-section{{SELECTOR}} > .xtpb-inner-section__inner > .xtpb-inner-section__grid { align-items: {{data.row_content}}; }'
						),
						'row_custom_width' => array(
							'type' => 'slider',
							'title' => __( '自定义宽度', 'xintheme-pagebuilder' ),
							'range' => array(
								'min' => 0,
								'max' => 2500,
								'step' => 1,
							),
							'responsive' => true,
							'selector' => '.xtpb-builder-container .xtpb-section{{SELECTOR}} > .xtpb-section__inner, .xtpb-builder-container .xtpb-section .xtpb-section__inner{{SELECTOR}} { max-width: {{data.row_custom_width}}px; margin-left: auto; margin-right: auto; }'
						),
						'row_custom_gutter' => array(
							'type' => 'slider',
							'title' => __( '列间距', 'xintheme-pagebuilder' ),
							'range' => array(
								'min' => 0,
								'max' => 200,
								'step' => 1,
							),
							'std' => '30',
							'responsive' => true,
							'selector' => array('.xtpb-section{{SELECTOR}} > .xtpb-section__inner > .xtpb-section__grid, .xtpb-section > .xtpb-section__inner{{SELECTOR}} > .xtpb-section__grid, .xtpb-inner-section{{SELECTOR}} > .xtpb-inner-section__inner > .xtpb-inner-section__grid, .xtpb-inner-section > .xtpb-inner-section__inner{{SELECTOR}} > .xtpb-inner-section__grid { --xtpb-gap: {{data.row_custom_gutter}}px; }')
						),
						'row_animation' => array(
							'type' => 'animation',
							'title' => __( '动画', 'xintheme-pagebuilder' ),
							'section' => __( '动画', 'xintheme-pagebuilder' ),
						),
					),
					'style' => array(
						'row_padding' => array(
							'type' => 'dimension',
							'title' => __( '内边距', 'xintheme-pagebuilder' ),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'selector' => '.xtpb-builder-container .xtpb-section{{SELECTOR}}, .xtpb-builder-container .xtpb-section__inner{{SELECTOR}}, .xtpb-builder-container .xtpb-inner-section{{SELECTOR}}, .xtpb-builder-container .xtpb-inner-section__inner{{SELECTOR}} { padding: {{data.row_padding}}; }'
						),
						'row_margin' => array(
							'type' => 'dimension',
							'title' => __( '外边距', 'xintheme-pagebuilder' ),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'selector' => '{{SELECTOR}}{ margin: {{data.row_margin}}; }'
						),
						'row_boxshadow' => array(
							'type' => 'boxshadow',
							'title' => __( '盒子阴影', 'xintheme-pagebuilder' ),
                            'std' => array(
                                'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                                'shadowColor' => 'rgba(0,0,0,.3)'
                            ),
							'selector' => '{{SELECTOR}}',
							'section' => __( '盒子阴影', 'xintheme-pagebuilder' ),
						),
						'row_boxshadow_hover' => array(
							'type' => 'boxshadow',
							'title' => __( '悬停盒子阴影', 'xintheme-pagebuilder' ),
                            'std' => array(
                                'shadowValue' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '5px', 'left' => '0px' ),
                                'shadowColor' => 'rgba(0,0,0,.3)'
                            ),
							'selector' => '{{SELECTOR}}:hover',
							'section' => __( '盒子阴影', 'xintheme-pagebuilder' ),
						),
						'row_border' => array(
							'type' => 'border',
							'title' => __( '边框', 'xintheme-pagebuilder' ),
							'std' => array(
								'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
								'borderStyle' => 'solid',
								'borderColor' => '#cccccc'
							),
							'selector' => '{{SELECTOR}}',
							'section' => __( '边框', 'xintheme-pagebuilder' ),
						),
						'row_border_hover' => array(
							'type' => 'border',
							'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
							'std' => array(
								'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
								'borderStyle' => 'solid',
								'borderColor' => '#cccccc'
							),
							'selector' => '{{SELECTOR}}:hover',
							'section' => __( '边框', 'xintheme-pagebuilder' ),
						),
						'row_border_radius_check' => array(
							'type' => 'switch',
							'title' => __( '启用边框圆角', 'xintheme-pagebuilder' ),
							'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
							'std' => '0'
						),
						'row_border_radius' => array(
							'type' => 'dimension',
							'title' => __( '边框圆角', 'xintheme-pagebuilder' ),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'depends' => array(array('row_border_radius_check', '=', '1')),
							'selector' => '{{SELECTOR}}{ border-radius: {{data.row_border_radius}} ; }',
							'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
						),
						'row_border_radius_hover' => array(
							'type' => 'dimension',
							'title' => __( '悬停圆角', 'xintheme-pagebuilder' ),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'depends' => array(array('row_border_radius_check', '=', '1')),
							'selector' => '{{SELECTOR}}:hover{ border-radius: {{data.row_border_radius_hover}} ; }',
							'section' => __( '边框圆角', 'xintheme-pagebuilder' ),
						),
						'row_background'=> array(
							'type' => 'background',
							'title' => __( '背景', 'xintheme-pagebuilder' ),
							'video' => true,
							'selector' => '{{SELECTOR}}',
							'section' => __( '背景', 'xintheme-pagebuilder' ),
						),
						'row_color_opacity'=> array(
							'type' => 'color2',
							'title' => __( '遮罩背景颜色', 'xintheme-pagebuilder' ),
							'std' => '#00aeff',
							'selector' => '{{SELECTOR}}:after',
							'section' => __( '遮罩', 'xintheme-pagebuilder' ),
						),
						'row_overlay_media' => array(
							'type' => 'media',
							'title' => __( '遮罩图片', 'xintheme-pagebuilder' ),
							'section' => __( '遮罩', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}}:after{ background-image: url("{{data.row_overlay_media}}"); }',
						),
						'row_overlay_position' => array(
							'type' => 'select',
							'title' => __( '图片位置', 'xintheme-pagebuilder' ),
							'values' => array(
								'' => '默认',
								'left top' => 'left top',
								'left center' => 'left center',
								'left bottom' => 'left bottom',
								'right top' => 'right top',
								'right center' => 'right center',
								'right bottom' => 'right bottom',
								'center top' => 'center top',
								'center center' => 'center center',
								'center bottom' => 'center bottom',
							),
							'section' => __( '遮罩', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}}:after{ background-position: {{data.row_overlay_position}}; }',
						),
						'row_overlay_size' => array(
							'type' => 'select',
							'title' => __( '图片尺寸', 'xintheme-pagebuilder' ),
							'values' => array(
								'' => '默认',
								'cover' => 'Cover',
								'auto' => 'Auto',
								'contain' => 'Contain',
							),
							'section' => __( '遮罩', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}}:after{ background-size: {{data.row_overlay_size}}; }',
						),
						'row_opacity' => array(
							'type' => 'slider',
							'title' => __( '遮罩透明度', 'xintheme-pagebuilder' ),
							'range' => array(
								'min' => 0,
								'max' => 1,
								'step' => .01,
							),
							'selector' => '{{SELECTOR}}:after{content: " "; display: block; position: absolute; height: 100%; top: 0; left: 0; right: 0; z-index: -1; opacity: {{data.row_opacity}}; }',
							'section' => __( '遮罩', 'xintheme-pagebuilder' ),
						),
						'row_color_blend' => array(
							'type' => 'select',
							'title' => __( '混合模式', 'xintheme-pagebuilder' ),
							'values' => array(
								'normal' => '普通',
								'multiply' => 'Multiply',
								'screen' => 'Screen',
								'overlay' => 'Overlay',
								'darken' => 'Darken',
								'lighten' => 'Lighten',
								'color-dodge' => 'Color Dodge',
								'saturation' => 'Saturation',
								'luminosity' => 'Luminosity',
								'color' => '颜色',
								'color-burn' => 'Color Burn',
								'exclusion' => 'Exclusion',
								'hue' => 'Hue',
							),
							'section' => __( '遮罩', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}}:after{ mix-blend-mode:{{data.row_color_blend}}; }',
						),
						'row_shape' => array(
							'type' => 'shape',
							'title' => __( '顶部形状', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}}',
							'section' => __( '形状', 'xintheme-pagebuilder' ),
						),
						'row_shape_bottom' => array(
							'type' => 'shape',
							'title' => __( '底部形状', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}}',
							'section' => __( '形状', 'xintheme-pagebuilder' ),
						),
					),
					'advanced' => array(
						'row_id' => array(
							'type' => 'text',
							'title' => __( '行 ID', 'xintheme-pagebuilder' ),
						),
						'row_class' => array(
							'type' => 'text',
							'title' => __( 'CSS 类名', 'xintheme-pagebuilder' ),
						),
						'row_z_index' => array(
							'type' => 'slider',
							'title' => __( '层级', 'xintheme-pagebuilder' ),
							'range' => array(
								'min' => 0,
								'max' => 10000,
								'step' => 1,
							),
							'std' => 0,
							'selector' => '{{SELECTOR}}{ z-index: {{data.row_z_index}}; }',
						),
						'row_hidden_xs' => array(
							'type' => 'switch',
							'title' => __( '在手机端隐藏', 'xintheme-pagebuilder' ),
							'section' => __( '响应式', 'xintheme-pagebuilder' ),
							'std' => '0',
						),
						'row_hidden_sm' => array(
							'type' => 'switch',
							'title' => __( '在平板端隐藏', 'xintheme-pagebuilder' ),
							'section' => __( '响应式', 'xintheme-pagebuilder' ),
							'std' => '0',
						),
						'row_hidden_md' => array(
							'type' => 'switch',
							'title' => __( '在桌面端隐藏', 'xintheme-pagebuilder' ),
							'section' => __( '响应式', 'xintheme-pagebuilder' ),
							'std' => '0',
						),
						'row_hidden_lg' => array(
							'type' => 'switch',
							'title' => __( '在大屏设备隐藏', 'xintheme-pagebuilder' ),
							'section' => __( '响应式', 'xintheme-pagebuilder' ),
							'std' => '0',
						),
						'row_custom_css' => array(
							'type' => 'textarea',
							'title' => __( '自定义 CSS', 'xintheme-pagebuilder' ),
							'section' => __( '自定义 CSS', 'xintheme-pagebuilder' ),
							'placeholder' =>  __('在选择器前使用 {{SELECTOR}} 以包裹当前元素，否则将全局生效。','xintheme-pagebuilder'),
							'desc' =>  __( '需要只影响当前行时，请在选择器前加上 {{SELECTOR}}；不加时会作为全局 CSS 生效。', 'xintheme-pagebuilder' ),
						),
					),
				),
			);
			return $rowSettings;
		}

	}

}
