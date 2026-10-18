<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('XTPB_Addon_Carousel')) {
	class XTPB_Addon_Carousel {

		public function __construct() {
			//Register scripts
			add_action('wp_enqueue_scripts', array($this, 'register_required_script'));
			add_action('xtpb_enqueue_scripts', array($this, 'register_required_script'));

			//Load scripts at builder
			add_action('xtpb_enqueue_scripts', array($this, 'add_scripts'));
		}

		public function register_required_script(){
			wp_register_script('thm.carousel.slick.main', XTPB_DIR_URL.'addons/carousel/assets/libraries/slick/slick.min.js', array('jquery'), false, true);
			wp_register_script('thm.carousel.main', XTPB_DIR_URL.'addons/carousel/assets/js/slick-slider-animation.js', array('jquery'), false, true);
			wp_register_script('thm.slider.custom.main', XTPB_DIR_URL.'addons/carousel/assets/js/thm-slider-main.js', array('thm.carousel.slick.main'), false, true);
			wp_register_style('thm.carousel.animate', XTPB_DIR_URL.'addons/carousel/assets/libraries/animate.css/animate.min.css');
			wp_register_style('thm.carousel.slick', XTPB_DIR_URL.'addons/carousel/assets/libraries/slick/slick.min.css');
			wp_register_style('thm.carousel.slick.theme', XTPB_DIR_URL.'addons/carousel/assets/libraries/slick/slick-theme.min.css');
			wp_register_style('thm.carousel.style', XTPB_DIR_URL.'addons/carousel/assets/css/slider.css');
		}

		//Add Frontend builder Script
		public function add_scripts(){
			$scripts = $this->get_enqueue_script();
			$styles = $this->get_enqueue_style();

			if (is_array($scripts) && count($scripts)){
				foreach ($scripts as $script){
					wp_enqueue_script($script);
				}
			}

			if (is_array($styles) && count($styles)){
				foreach ($styles as $style){
					wp_enqueue_style($style);
				}
			}
		}


		public function get_enqueue_script(){
			return array( 'thm.carousel.slick.main', 'thm.carousel.main', 'thm.slider.custom.main' );
		}

		public function get_enqueue_style(){
			return array( 'thm.carousel.animate', 'thm.carousel.slick', 'thm.carousel.slick.theme', 'thm.carousel.style' );
		}


		public function get_name() {
			return 'xtpb_carousel';
		}

		public function get_title() {
			return __( '轮播图', 'xintheme-pagebuilder' );
		}

		public function get_icon() {
			return 'xtpb-font-full-slider';
		}

		// Settings Fields
		public function get_settings() {

			$settings = array(
				'arrow_option' => array(
					'type' => 'switch',
					'title' => __('导航选项','xintheme-pagebuilder'),
					'std' => '1',
					'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
				),
				'control_option' => array(
					'type' => 'switch',
					'title' => __('控制选项','xintheme-pagebuilder'),
					'std' => '1',
					'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
				),
				'autoplay_option' => array(
					'type' => 'switch',
					'title' => __('自动播放选项','xintheme-pagebuilder'),
					'std' => '1',
					'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
				),
				'animated_speed' => array(
					'type' => 'number',
					'title' => __('动画速度','xintheme-pagebuilder'),
					'std' => '600',
					'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
					'depends' => array(array('autoplay_option', '=', '1')),
				),
				'slide_height' => array(
					'type' => 'slider',
					'title' => __('轮播高度','xintheme-pagebuilder'),
					'unit' => array( 'px','%','em' ),
					'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 90,
							'step' => .1,
						),
						'px' => array(
							'min' => 0,
							'max' => 1200,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
					'std' => array(
						'md' => '600px',
						'sm' => '',
						'xs' => '',
					),
					'responsive' => true,
					'section' => __( '轮播设置', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel-content-wrap { height: {{data.slide_height}}; }',
				),
				'carosuel_list' => array(
					'title' => __('轮播图','xintheme-pagebuilder'),
					'type' => 'repeatable',
					'label' => 'title',
					'std' => array(
						array(
							'title' => __( '独特设计', 'xintheme-pagebuilder' ),
							'subtitle' => __( '国际化视觉设计', 'xintheme-pagebuilder' ),
							'content' => __( '为你的下一个项目快速创建页面布局，内置精心设计的区块和模板。', 'xintheme-pagebuilder' ),
							'slider_type' => 'right_media',
							'media_type'  => 'media_image',
							'imageupload' => array('url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-medium.jpg' ),
							'video_url'  => 'https://www.youtube.com/watch?v=_cTAlxWtmS4',
							'intro_color' => '#656565',
							'button_text' => __( '立即开始', 'xintheme-pagebuilder' ),
							'button_link' => array( 'link'=>'#','window'=>false,'nofolow'=>false ),
							'btn_color'   => '#fff',
							'btn_hover_color'   => '#fff',
							'slide_height' => array(
								'md' => '600px',
								'sm' => '',
								'xs' => '',
							),
							'title_color' => '#000',
							'subtitle_color' => '#151515',
							'carosuel_bg' =>array(
								'bgType' => 'color',
								'bgColor' => '#fff',
							),
							'btn_background' => array(
								'clip' => false,
								'colorType' => 'color',
								'colorColor' => '#26c266',
							),
							'btn_hover_background' => array(
								'clip' => false,
								'colorType' => 'color',
								'colorColor' => '#1fa957',
							),
							'btn_radius' => array(
								'md' => '3px',
								'sm' => '',
								'xs' => '',
							),
							'btn_padding' => array(
								'md' => array( 'top' => '8px', 'right' => '25px', 'bottom' => '8px', 'left' => '25px' ),
								'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
								'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
							),

						),
						array(
							'title' => __( '灵活结构', 'xintheme-pagebuilder' ),
							'subtitle' => __( '拖拽式页面构建器', 'xintheme-pagebuilder' ),
							'content' => __( '通过拖拽方式组织页面内容，快速搭建清晰、可维护的页面结构。', 'xintheme-pagebuilder' ),
							'slider_type' => 'left_media',
							'media_type'  => 'media_image',
							'imageupload' => array('url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-medium.jpg' ),
							'video_url'  => 'https://www.youtube.com/watch?v=_cTAlxWtmS4',
							'intro_color' => '#656565',
							'button_text' => __( '了解更多', 'xintheme-pagebuilder' ),
							'button_link' => array( 'link'=>'#','window'=>false,'nofolow'=>false ),
							'slide_height' => array(
								'md' => '600px',
								'sm' => '',
								'xs' => '',
							),
							'carosuel_bg' =>array(
								'bgType' => 'color',
								'bgColor' => '#fff',
							),
							'title_color' => '#000',
							'subtitle_color' => '#151515',
							'btn_color'   => '#fff',
							'btn_hover_color'   => '#fff',
							'btn_background' => array(
								'clip' => false,
								'colorType' => 'color',
								'colorColor' => '#3666E4',
							),
							'btn_hover_background' => array(
								'clip' => false,
								'colorType' => 'color',
								'colorColor' => '#2955ca',
							),
							'btn_radius' => array(
								'md' => '3px',
								'sm' => '',
								'xs' => '',
							),
							'btn_padding' => array(
								'md' => array( 'top' => '8px', 'right' => '25px', 'bottom' => '8px', 'left' => '25px' ),
								'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
								'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
							),
						),
					),

					'attr' => array(
						'slider_type' => array(
							'type' => 'select',
							'title' => __('内容类型','xintheme-pagebuilder'),
							'values' => array(
								'center_content' => __('仅内容','xintheme-pagebuilder'),
								'right_media' => __('媒体在右侧','xintheme-pagebuilder'),
								'left_media' => __('媒体在左侧','xintheme-pagebuilder'),
							),
							'std' => 'right_media',
							'section' => __( '幻灯片', 'xintheme-pagebuilder' ),
						),
						'media_type' => array(
							'type' => 'select',
							'title' => __('类型','xintheme-pagebuilder'),
							'values' => array(
								'media_image' => __('图片','xintheme-pagebuilder'),
								'media_video' => __('视频','xintheme-pagebuilder'),
							),
							'std' => 'media_image',
							'section' => __( '幻灯片', 'xintheme-pagebuilder' ),
							'depends' => array(array('slider_type', '=', array('right_media','left_media'))),
						),
						'imageupload' => array(
							'type' => 'media',
							'title' => __('上传内容图片','xintheme-pagebuilder'),
							'section' => __( '幻灯片', 'xintheme-pagebuilder' ),
							'std' => array( 'url' => XTPB_DIR_URL.'assets/img/placeholder/xtpb-medium.jpg' ),
							'depends' => array(array('media_type', '=', array('media_image'))),
						),
						'image_animation'=> array(
							'type' => 'animation',
							'title' => __( '图片动画', 'xintheme-pagebuilder' ),
							'section' => __( '幻灯片', 'xintheme-pagebuilder' ),
							'std' => array(
								'name' => 'wow animated fadeIn',
								'delay' => '300',
								'duration' => '400'
							),
							'depends' => array(array('media_type', '=', array('media_image'))),
						),
						'video_url' => array(
							'type' => 'text',
							'title' => __('视频 URL','xintheme-pagebuilder'),
							'std' => 'https://www.youtube.com/watch?v=_cTAlxWtmS4',
							'depends' => array(array('media_type', '=', array('media_video'))),
							'section' => __( '幻灯片', 'xintheme-pagebuilder' ),
						),
						'video_info' => array(
							'type' => 'switch',
							'title' => __('隐藏视频信息','xintheme-pagebuilder'),
							'std' => '1',
							'depends' => array(array('media_type', '=', array('media_video'))),
							'section' => __( '幻灯片', 'xintheme-pagebuilder' ),
						),
						'video_animation'=> array(
							'type' => 'animation',
							'title' => __( '视频动画', 'xintheme-pagebuilder' ),
							'section' => __( '幻灯片', 'xintheme-pagebuilder' ),
							'std' => array(
								'name' => 'wow animated fadeIn',
								'delay' => '300',
								'duration' => '400'
							),
							'depends' => array(array('media_type', '=', array('media_video'))),
						),
						'content_align' => array(
							'type' => 'alignment',
							'title' => __('内容对齐','xintheme-pagebuilder'),
							'std' => array( 'md' => 'center', 'sm' => '', 'xs' => '' ),
							'responsive' => true,
							'selector' => '{{SELECTOR}} .xtpb-carousel-content-in { text-align: {{data.content_align}}; }',
							'section' => __( '幻灯片', 'xintheme-pagebuilder' ),
						),

						//title
						'title' => array(
							'type' => 'text',
							'title' => __('标题','xintheme-pagebuilder'),
							'section' => __( '标题', 'xintheme-pagebuilder' ),
							'std'=> __( '幻灯片标题', 'xintheme-pagebuilder' ),
						),
						'selector' => array(
							'type' => 'select',
							'title' => __('标题标签','xintheme-pagebuilder'),
							'values' => array(
								'h1' => 'h1',
								'h2' => 'h2',
								'h3' => 'h3',
								'h4' => 'h4',
								'h5' => 'h5',
								'h6' => 'h6',
							),
							'std' => 'h2',
							'section' => __( '标题', 'xintheme-pagebuilder' ),
						),
						'title_animation'=> array(
							'type' => 'animation',
							'title' => __( '动画', 'xintheme-pagebuilder' ),
							'section' => __( '标题', 'xintheme-pagebuilder' ),
							'std' => array(
								'name' => 'wow animated fadeIn',
								'delay' => '300',
								'duration' => '400'
							),
						),
						'title_color' => array(
							'type' => 'color',
							'title' => __('标题颜色','xintheme-pagebuilder'),
							'section' => __( '标题', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-carousel-title { color: {{data.title_color}}; }'
						),
						'title_fontstyle' => array(
							'type' => 'typography',
							'title' => __( '标题排版', 'xintheme-pagebuilder' ),
							'std' => array(
								'fontFamily' => '',
								'fontSize' => [ 'md'=>'25px', 'sm'=>'', 'xs'=>'' ],
								'lineHeight' => [ 'md'=>'', 'sm'=>'', 'xs'=>'' ],
								'fontWeight' => '700',
								'textTransform' => '',
								'fontStyle' => '',
								'letterSpacing' => [ 'md'=>'0px', 'sm'=>'', 'xs'=>'' ],
							),
							'selector' => '{{SELECTOR}} .xtpb-carousel-title',
							'section' => __( '标题', 'xintheme-pagebuilder' ),
						),
						'title_margin' => array(
							'type' => 'dimension',
							'title' => __( '标题外边距', 'xintheme-pagebuilder' ),
							'std' => array(
								'md' => array( 'top' => '15px', 'right' => '15px', 'bottom' => '15px', 'left' => '15px' ),
								'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
								'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
							),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'section' => __( '标题', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-carousel-title{ margin: {{data.title_margin}}; }'
						),

						//sub title
						'subtitle' => array(
							'type' => 'textarea',
							'title' => __('副标题','xintheme-pagebuilder'),
							'section' => __( '副标题', 'xintheme-pagebuilder' ),
							'std' => __( 'WordPress 页面构建器', 'xintheme-pagebuilder' ),
						),
						'subselector' => array(
							'type' => 'select',
							'title' => __( '副标题标签','xintheme-pagebuilder' ),
							'values' => array(
								'h1' => 'h1',
								'h2' => 'h2',
								'h3' => 'h3',
								'h4' => 'h4',
								'h5' => 'h5',
								'h6' => 'h6',
							),
							'std' => 'h3',
							'section' => __( '副标题', 'xintheme-pagebuilder' ),
						),
						'subtitle_animation'=> array(
							'type' => 'animation',
							'title' => __( '副标题动画', 'xintheme-pagebuilder' ),
							'section' => __( '副标题', 'xintheme-pagebuilder' ),
							'std' => array(
								'name' => 'wow animated fadeInDown',
								'delay' => '300',
								'duration' => '400'
							),
						),
						'subtitle_color' => array(
							'type' => 'color',
							'title' => __('副标题颜色','xintheme-pagebuilder'),
							'section' => __( '副标题', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-carousel-subtitle { color: {{data.subtitle_color}}; }'
						),
						'subtitle_fontstyle' => array(
							'type' => 'typography',
							'title' => __( '副标题排版', 'xintheme-pagebuilder' ),
							'std' => array(
								'fontFamily' => '',
								'fontSize' => [ 'md'=>'18px', 'sm'=>'', 'xs'=>'' ],
								'lineHeight' => [ 'md'=>'', 'sm'=>'', 'xs'=>'' ],
								'fontWeight' => '700',
								'textTransform' => '',
								'fontStyle' => '',
								'letterSpacing' => [ 'md'=>'0px', 'sm'=>'', 'xs'=>'' ],
							),
							'selector' => '{{SELECTOR}} .xtpb-carousel-subtitle',
							'section' => __( '副标题', 'xintheme-pagebuilder' ),
						),
						'subtitle_margin' => array(
							'type' => 'dimension',
							'title' => __( '副标题外边距', 'xintheme-pagebuilder' ),
							'std' => array(
								'md' => array( 'top' => '15px', 'right' => '15px', 'bottom' => '15px', 'left' => '15px' ),
								'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
								'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
							),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'section' => __( '副标题', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-carousel-subtitle{ margin: {{data.subtitle_margin}}; }'
						),

						//content
						'content' => array(
							'type' => 'textarea',
							'title' => __('添加内容','xintheme-pagebuilder'),
							'std' => __( '在这里添加轮播图的正文内容。', 'xintheme-pagebuilder' ),
							'section' => __( '内容', 'xintheme-pagebuilder' ),
						),
						'content_animation'=> array(
							'type' => 'animation',
							'title' => __( '内容动画', 'xintheme-pagebuilder' ),
							'section' => __( '内容', 'xintheme-pagebuilder' ),
							'std' => array(
								'name' => 'wow animated fadeIn',
								'delay' => '300',
								'duration' => '400'
							),
						),
						'intro_color' => array(
							'type' => 'color',
							'title' => __('内容文本颜色','xintheme-pagebuilder'),
							'section' => __( '内容', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-carousel-content { color: {{data.intro_color}}; }'
						),
						'intro_fontstyle' => array(
							'type' => 'typography',
							'title' => __( '内容排版', 'xintheme-pagebuilder' ),
							'std' => array(
								'fontFamily' => '',
								'fontSize' => [ 'md'=>'14px', 'sm'=>'', 'xs'=>'' ],
								'lineHeight' => [ 'md'=>'', 'sm'=>'', 'xs'=>'' ],
								'fontWeight' => '700',
								'textTransform' => '',
								'fontStyle' => '',
								'letterSpacing' => [ 'md'=>'0px', 'sm'=>'', 'xs'=>'' ],
							),
							'selector' => '{{SELECTOR}} .xtpb-carousel-content',
							'section' => __( '内容', 'xintheme-pagebuilder' ),
						),
						'intro_margin' => array(
							'type' => 'dimension',
							'title' => __( '内容外边距', 'xintheme-pagebuilder' ),
							'std' => array(
								'md' => array( 'top' => '15px', 'right' => '15px', 'bottom' => '15px', 'left' => '15px' ),
								'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
								'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
							),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'section' => __( '内容', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-carousel-content { margin: {{data.intro_margin}}; }'
						),

						// button one
						'button_text' => array(
							'type' => 'text',
							'title' => __('按钮文字','xintheme-pagebuilder'),
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
							'std' => __( '按钮', 'xintheme-pagebuilder' )
						),
						'button_link' => array(
							'type' => 'link',
							'title' => __('按钮链接','xintheme-pagebuilder'),
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
						),
						'btn_animation1'=> array(
							'type' => 'animation',
							'title' => __( '按钮一动画', 'xintheme-pagebuilder' ),
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
							'std' => array(
								'name' => 'wow animated fadeInLeft',
								'delay' => '300',
								'duration' => '400'
							),
						),
						'btn_fontstyle' => array(
							'type' => 'typography',
							'title' => __( '按钮排版', 'xintheme-pagebuilder' ),
							'std' => array(
								'fontFamily' => '',
								'fontSize' => [ 'md'=>'14px', 'sm'=>'', 'xs'=>'' ],
								'lineHeight' => [ 'md'=>'', 'sm'=>'', 'xs'=>'' ],
								'fontWeight' => '700',
								'textTransform' => '',
								'fontStyle' => '',
								'letterSpacing' => [ 'md'=>'0px', 'sm'=>'', 'xs'=>'' ],
							),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel1',
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
						),
						'btn_color' => array(
							'type' => 'color',
							'title' => __('按钮颜色','xintheme-pagebuilder'),
							'clip' => true,
							'std' => '#fff',
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel1 { color: {{data.btn_color}}; }'
						),
						'btn_hover_color' => array(
							'type' => 'color',
							'title' => __('按钮悬停颜色','xintheme-pagebuilder'),
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
							'clip' => true,
							'std' => '#fff',
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel1:hover { color: {{data.btn_hover_color}}; }'
						),
						'btn_background' => array(
							'type' => 'color2',
							'title' => __('按钮背景','xintheme-pagebuilder'),
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
							'clip' => false,
							'std' => array(
								'clip' => false,
								'colorType' => 'color',
								'colorColor' => '#26c266',
							),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel1'
						),
						'btn_hover_background' => array(
							'type' => 'color2',
							'title' => __('按钮悬停背景','xintheme-pagebuilder'),
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
							'clip' => false,
							'std' => array(
								'clip' => false,
								'colorType' => 'color',
								'colorColor' => '#1fa957',
							),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel1:before, {{SELECTOR}} .xtpb-btn-carousel1:hover'
						),
						'button_border' => array(
							'type' => 'border',
							'title' => __( '按钮边框', 'xintheme-pagebuilder' ),
							'std' => [
								'borderWidth' => ['top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px'],
								'borderStyle' => 'solid',
								'borderColor' => '#cccccc'
							],
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel1, {{SELECTOR}} .xtpb-btn-carousel1:before',
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
						),
						'border_hcolor' => array(
							'type' => 'border',
							'title' => __( '按钮悬停边框', 'xintheme-pagebuilder' ),
							'std' => array(
								'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
								'borderStyle' => 'solid',
								'borderColor' => '#cccccc'
							),
							'tab' => 'style',
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel1:hover, {{SELECTOR}} .xtpb-btn-carousel1:hover:before'
						),
						'btn_radius' => array(
							'type' => 'dimension',
							'title' => __('边框圆角','xintheme-pagebuilder'),
							'unit' => array( 'px','%','em' ),
							'std' => array(
								'md' => array( 'top' => '4px', 'right' => '4px', 'bottom' => '4px', 'left' => '4px' ),
								'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
								'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
							),
							'responsive' => true,
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel1, {{SELECTOR}} .xtpb-btn-carousel1:before { border-radius: {{data.btn_radius}}; }'
						),
						'btn_padding' => array(
							'type' => 'dimension',
							'title' => __( '按钮内边距', 'xintheme-pagebuilder' ),
							'std' => array(
								'md' => array( 'top' => '10px', 'right' => '15px', 'bottom' => '10px', 'left' => '15px' ),
								'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
								'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
							),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel1 { padding: {{data.btn_padding}}; }'
						),
						'btn_margin' => array(
							'type' => 'dimension',
							'title' => __( '按钮外边距', 'xintheme-pagebuilder' ),
							'std' => array(
								'md' => array( 'top' => '0', 'right' => '5px', 'bottom' => '0', 'left' => '0' ),
								'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
								'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
							),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'section' => __( '按钮一', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel1 { margin: {{data.btn_margin}}; }'
						),

						//btn two
						'button_text2' => array(
							'type' => 'text',
							'title' => __('按钮文字 2','xintheme-pagebuilder'),
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
							'std' => __( '按钮', 'xintheme-pagebuilder' )
						),
						'button_link2' => array(
							'type' => 'link',
							'title' => __('按钮链接 2','xintheme-pagebuilder'),
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
						),
						'btn_animation2'=> array(
							'type' => 'animation',
							'title' => __( '按钮二动画', 'xintheme-pagebuilder' ),
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
							'std' => array(
								'name' => 'wow animated fadeInRight',
								'delay' => '300',
								'duration' => '400'
							),
						),
						'btn_fontstyle2' => array(
							'type' => 'typography',
							'title' => __( '按钮二排版', 'xintheme-pagebuilder' ),
							'std' => array(
								'fontFamily' => '',
								'fontSize' => [ 'md'=>'14px', 'sm'=>'', 'xs'=>'' ],
								'lineHeight' => [ 'md'=>'', 'sm'=>'', 'xs'=>'' ],
								'fontWeight' => '700',
								'textTransform' => '',
								'fontStyle' => '',
								'letterSpacing' => [ 'md'=>'', 'sm'=>'', 'xs'=>'' ],
							),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel2',
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
						),
						'btn_color2' => array(
							'type' => 'color',
							'title' => __('按钮颜色 2','xintheme-pagebuilder'),
							'clip' => true,
							'std' => '#fff',
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel2 { color: {{data.btn_color2}}; }'
						),
						'btn_hover_color2' => array(
							'type' => 'color',
							'title' => __('按钮悬停颜色 2','xintheme-pagebuilder'),
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel2:hover { color: {{data.btn_hover_color2}}; }'
						),
						'btn_background2' => array(
							'type' => 'color2',
							'title' => __('按钮背景 2','xintheme-pagebuilder'),
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
							'clip' => false,
							'std' => array(
								'clip' => false,
								'colorType' => 'color',
								'colorColor' => '#3666E4',
							),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel2'
						),
						'btn_hover_background2' => array(
							'type' => 'color2',
							'title' => __('按钮悬停背景 2','xintheme-pagebuilder'),
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
							'clip' => false,
							'std' => array(
								'clip' => false,
								'colorType' => 'color',
								'colorColor' => '#2955ca',
							),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel2:before, {{SELECTOR}} .xtpb-btn-carousel2:hover'
						),

						'button_border2' => array(
							'type' => 'border',
							'title' => __( '按钮二边框', 'xintheme-pagebuilder' ),
							'std' => [
								'borderWidth' => ['top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px'],
								'borderStyle' => 'solid',
								'borderColor' => '#cccccc'
							],
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel2, {{SELECTOR}} .xtpb-btn-carousel2:before',
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
						),
						'button_border2_hover' => array(
							'type' => 'border',
							'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
							'std' => array(
								'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
								'borderStyle' => 'solid',
								'borderColor' => '#cccccc'
							),
							'tab' => 'style',
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel2:hover, {{SELECTOR}} .xtpb-btn-carousel2:hover:before'
						),
						'btn_radius2' => array(
							'type' => 'dimension',
							'title' => __('按钮 2 圆角','xintheme-pagebuilder'),
							'unit' => array( 'px','%','em' ),
							'responsive' => true,
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel2, {{SELECTOR}} .xtpb-btn-carousel2:before { border-radius: {{data.btn_radius2}}; }'
						),
						'btn_padding2' => array(
							'type' => 'dimension',
							'title' => __( '按钮二内边距', 'xintheme-pagebuilder' ),
							'std' => array(
								'md' => array( 'top' => '10px', 'right' => '15px', 'bottom' => '10px', 'left' => '15px' ),
								'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
								'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
							),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel2 { padding: {{data.btn_padding2}}; }'
						),
						'btn_margin2' => array(
							'type' => 'dimension',
							'title' => __( '按钮二外边距', 'xintheme-pagebuilder' ),
							'std' => array(
								'md' => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '5px' ),
								'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
								'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
							),
							'unit' => array( 'px','em','%' ),
							'responsive' => true,
							'section' => __( '按钮二', 'xintheme-pagebuilder' ),
							'selector' => '{{SELECTOR}} .xtpb-btn-carousel2 { margin: {{data.btn_margin2}}; }'
						),

						//style
						'carosuel_bg' => array(
							'type' => 'background',
							'title' => __('背景图片','xintheme-pagebuilder'),
							'section' => __( '背景', 'xintheme-pagebuilder' ),
							'std' => '',
							'selector' => '{{SELECTOR}} .xtpb-carousel-content-wrap',
						),
						'overlay_en' => array(
							'type' => 'switch',
							'title' => __('隐藏遮罩层','xintheme-pagebuilder'),
							'std' => '0',
							'section' => __( '背景', 'xintheme-pagebuilder' ),
						),
						'carosuel_bg_overlay' => array(
							'type' => 'color2',
							'title' => __('背景图片遮罩层','xintheme-pagebuilder'),
							'section' => __( '背景', 'xintheme-pagebuilder' ),
							'clip' => false,
							'std' => array(
								'clip' => false,
								'colorType' => 'color',
								'colorColor' => '#3666E4',
							),
							'depends' => array(array('overlay_en', '=', '1')),
							'selector' => '{{SELECTOR}} .xtpb-carousel-overlay'
						),
					),
				),


				// nav
				'nav_color' => array(
					'type' => 'color',
					'title' => __('导航颜色','xintheme-pagebuilder'),
					'tab' => 'style',
					'section' => __( '导航', 'xintheme-pagebuilder' ),
					'std'     => '#fff',
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-arrow { color: {{data.nav_color}}; }',
				),
				'nav_bg' => array(
					'type' => 'color2',
					'title' => __('导航背景','xintheme-pagebuilder'),
					'tab' => 'style',
					'section' => __( '导航', 'xintheme-pagebuilder' ),
					'clip' => false,
					'std' => array(
						'clip' => false,
						'colorType' => 'color',
						'colorColor' => '#252525',
					),
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-arrow',
				),
				'nav_width' => array(
					'type' => 'slider',
					'title' => __('导航宽度','xintheme-pagebuilder'),
					'unit' => array( 'px','%','em' ),
					'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 10,
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
						'md' => '40px',
						'sm' => '',
						'xs' => '',
					),
					'responsive' => true,
					'tab' => 'style',
					'section' => __( '导航', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-arrow { width: {{data.nav_width}}; }'
				),
				'nav_height' => array(
					'type' => 'slider',
					'title' => __('导航高度','xintheme-pagebuilder'),
					'unit' => array( 'px','%','em' ),
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
					'std' => array(
						'md' => '40px',
						'sm' => '',
						'xs' => '',
					),
					'responsive' => true,
					'tab' => 'style',
					'section' => __( '导航', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-arrow { height: {{data.nav_height}}; }{{SELECTOR}} .xtpb-carousel .slick-arrow { line-height: {{data.nav_height}}; }'
				),
				'nav_radius' => array(
					'type' => 'dimension',
					'title' => __('边框圆角','xintheme-pagebuilder'),
					'unit' => array( 'px','%','em' ),
					'std' => array(
						'md' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ),
						'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
						'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
					),
					'responsive' => true,
					'tab' => 'style',
					'section' => __( '导航', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-arrow { border-radius: {{data.nav_radius}}; }'
				),
				'nav_position' => array(
					'type' => 'slider',
					'title' => __('导航位置','xintheme-pagebuilder'),
					'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 10,
							'step' => .1,
						),
						'px' => array(
							'min' => -100,
							'max' => 350,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
					'unit' => array( 'px','%','em' ),
					'std' => array(
						'md' => '50%',
						'sm' => '',
						'xs' => '',
					),
					'responsive' => true,
					'tab' => 'style',
					'section' => __( '导航', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-arrow { top: {{data.nav_position}}; }'
				),
				// style
				'dot_bg' => array(
					'type' => 'color2',
					'title' => __('圆点背景','xintheme-pagebuilder'),
					'tab' => 'style',
					'section' => __( '圆点', 'xintheme-pagebuilder' ),
					'clip' => false,
					'std' => array(
						'clip' => false,
						'colorType' => 'color',
						'colorColor' => '#3666E4',
					),
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-dots li button',
				),
				'dots_width' => array(
					'type' => 'slider',
					'title' => __('圆点宽度','xintheme-pagebuilder'),
					'unit' => array( 'px','%','em' ),
					'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 10,
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
						'md' => '40px',
						'sm' => '',
						'xs' => '',
					),
					'responsive' => true,
					'tab' => 'style',
					'section' => __( '圆点', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-dots li button,{{SELECTOR}} .xtpb-carousel .slick-dots li{ width: {{data.dots_width}}; }'
				),
				'dots_height' => array(
					'type' => 'slider',
					'title' => __('圆点高度','xintheme-pagebuilder'),
					'unit' => array( 'px','%','em' ),
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
					'std' => array(
						'md' => '5px',
						'sm' => '',
						'xs' => '',
					),
					'responsive' => true,
					'tab' => 'style',
					'section' => __( '圆点', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-dots li button,{{SELECTOR}} .xtpb-carousel .slick-dots li{ height: {{data.dots_height}}; }'
				),
				'dots_radius' => array(
					'type' => 'dimension',
					'title' => __('边框圆角','xintheme-pagebuilder'),
					'unit' => array( 'px','%','em' ),
					'std' => array(
						'md' => array( 'top' => '2px', 'right' => '2px', 'bottom' => '2px', 'left' => '2px' ),
						'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
						'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
					),
					'responsive' => true,
					'tab' => 'style',
					'section' => __( '圆点', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-dots li button{ border-radius: {{data.dots_radius}}; }'
				),
				'dots_position' => array(
					'type' => 'slider',
					'title' => __('圆点位置','xintheme-pagebuilder'),
					'range' => array(
						'em' => array(
							'min' => 0,
							'max' => 10,
							'step' => .1,
						),
						'px' => array(
							'min' => -100,
							'max' => 350,
							'step' => 1,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
					'unit' => array( 'px','%','em' ),
					'std' => array(
						'md' => '35px',
						'sm' => '',
						'xs' => '',
					),
					'responsive' => true,
					'tab' => 'style',
					'section' => __( '圆点', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel .slick-dots { bottom: {{data.dots_position}}; }'
				),
				'content_padding' => array(
					'type' => 'dimension',
					'title' => __( '内容内边距', 'xintheme-pagebuilder' ),
					'std' => array(
						'md' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
						'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
						'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					),
					'unit' => array( 'px','em','%' ),
					'responsive' => true,
					'tab' => 'style',
					'section' => __( '内容', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel-content-in { padding: {{data.content_padding}}; }'
				),
				'content_margin' => array(
					'type' => 'dimension',
					'title' => __( '内容外边距', 'xintheme-pagebuilder' ),
					'std' => array(
						'md' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
						'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
						'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					),
					'unit' => array( 'px','em','%' ),
					'responsive' => true,
					'tab' => 'style',
					'section' => __( '内容', 'xintheme-pagebuilder' ),
					'selector' => '{{SELECTOR}} .xtpb-carousel-content-in { margin: {{data.content_margin}}; }'
				),
			);

			return $settings;
		}

		// Alert Render HTML
		public function render($data = null){
			$output = '';
			$settings = $data['settings'];

			$attrSettings = $settings;
			unset($attrSettings['carosuel_list']);

			$items = isset($settings["carosuel_list"]) ? $settings["carosuel_list"] : array();

			ob_start();

			?>
            <div class="xtpb-carousel-addon-content">
                <div class="xtpb-carousel stick-dots" data-addon-id="<?php echo esc_attr( $data['id'] ); ?>" data-settings="<?php echo esc_attr(json_encode($attrSettings));  ?>" >

					<?php foreach ( $items as $key => $value ) {

					//selector
					$selector    = get_xtpb_array_value_by_key( $value, 'selector' ) ? get_xtpb_array_value_by_key( $value, 'selector' ) : "h2";
					$subselector = get_xtpb_array_value_by_key( $value, 'subselector' ) ? get_xtpb_array_value_by_key( $value, 'subselector' ) : "h3";

					// button
					if ( ! empty( get_xtpb_array_value_by_key( $value, 'button_link' )['link'] ) ) {
						$target  = ! empty( get_xtpb_array_value_by_key( $value, 'button_link' )['window'] ) ? 'target=_blank' : 'target=_self';
						$nofolow = ! empty( get_xtpb_array_value_by_key( $value, 'button_link' )['nofolow'] ) ? 'rel=nofolow' : "";
					}
					if ( ! empty( get_xtpb_array_value_by_key( $value, 'button_link2' )['link'] ) ) {
						$target2  = ! empty( get_xtpb_array_value_by_key( $value, 'button_link2' )['window'] ) ? 'target=_blank' : 'target=_self';
						$nofolow2 = ! empty( get_xtpb_array_value_by_key( $value, 'button_link2' )['nofolow'] ) ? 'rel=nofolow' : "";
					}

					//video
					$video_url = get_xtpb_array_value_by_key( $value, 'video_url' );
					if ( $video_url ) {
						$video = parse_url( $video_url );
						switch ( $video['host'] ) {
							case 'youtu.be':
								$id  = trim( $video['path'], '/' );
								$videosrc = '//www.youtube.com/embed/' . $id . '?&showinfo=' . get_xtpb_array_value_by_key( $value, 'video_info' );
								break;

							case 'www.youtube.com':
							case 'youtube.com':
								parse_str( $video['query'], $query );
								$id  = $query['v'];
								$videosrc = '//www.youtube.com/embed/' . $id . '?&showinfo=' . get_xtpb_array_value_by_key( $value, 'video_info' );
								break;

							case 'vimeo.com':
							case 'www.vimeo.com':
								$id  = trim( $video['path'], '/' );
								$videosrc = "//player.vimeo.com/video/{$id}";
						}
					}

					// animation
					$titleAnimation = '';
					if (!empty($value['title_animation']['itemOpen'])){
						$enableAnimation = (bool) $value['title_animation']['itemOpen'];
						if ($enableAnimation && ! empty( $value['title_animation']['name'])){
							$titleAnimation = "data-animation-in='{$value['title_animation']['name']}'";
						}
					}
					$SubtitleAnimation = '';
					if (!empty($value['subtitle_animation']['itemOpen'])){
						$enableAnimation = (bool) $value['subtitle_animation']['itemOpen'];
						if ($enableAnimation && ! empty( $value['subtitle_animation']['name'])){
							$SubtitleAnimation = "data-animation-in='{$value['subtitle_animation']['name']}'";
						}
					}
					$ContentAnimation = '';
					if (!empty($value['content_animation']['itemOpen'])){
						$enableAnimation = (bool) $value['content_animation']['itemOpen'];
						if ($enableAnimation && ! empty( $value['content_animation']['name'])){
							$ContentAnimation = "data-animation-in='{$value['content_animation']['name']}'";
						}
					}
					$ImageAnimation = '';
					if (!empty($value['image_animation']['itemOpen'])){
						$enableAnimation = (bool) $value['image_animation']['itemOpen'];
						if ($enableAnimation && ! empty( $value['image_animation']['name'])){
							$ImageAnimation = "data-animation-in='{$value['image_animation']['name']}'";
						}
					}
					$VideoAnimation = '';
					if (!empty($value['video_animation']['itemOpen'])){
						$enableAnimation = (bool) $value['video_animation']['itemOpen'];
						if ($enableAnimation && ! empty( $value['video_animation']['name'])){
							$VideoAnimation = "data-animation-in='{$value['video_animation']['name']}'";
						}
					}
					$Button1Animation = '';
					if (!empty($value['btn_animation1']['itemOpen'])){
						$enableAnimation = (bool) $value['btn_animation1']['itemOpen'];
						if ($enableAnimation && ! empty( $value['btn_animation1']['name'])){
							$Button1Animation = "data-animation-in='{$value['btn_animation1']['name']}'";
						}
					}
					$Button2Animation = '';
					if (!empty($value['btn_animation2']['itemOpen'])){
						$enableAnimation = (bool) $value['btn_animation2']['itemOpen'];
						if ($enableAnimation && ! empty( $value['btn_animation2']['name'])){
							$Button2Animation = "data-animation-in='{$value['btn_animation2']['name']}'";
						}
					}


					$ImageMedia = '';
					if ( get_xtpb_array_value_by_key( $value, 'media_type' ) == "media_video" ) {
						if ( $video_url ) {
							$ImageMedia .= '<div class="xtpb-video-slide">';
							$ImageMedia .= '<iframe height="280" width:="100%" class="xtpb-embed-responsive-item" src="' . esc_url( $videosrc ) . '" allowFullScreen></iframe>';
							$ImageMedia .= '</div>';
						} else {
							if ( ! empty($value['imageupload']['url'])){
								$ImageMedia .= '<img src="' . esc_url( $value['imageupload']['url'] ) . '" class="animated" '.$ImageAnimation.' alt="image" />';
							}
						}
					} else {
						if ( ! empty($value['imageupload']['url'])){
							$ImageMedia .= '<img src="' . esc_url( $value['imageupload']['url'] ) . '" class="animated" '.$ImageAnimation.' alt="image" />';
						}
					}

					// button 1
					$button1 = '';
					if ( ! empty( get_xtpb_array_value_by_key( $value, 'button_link' )['link'] ) ) {
						$button1 .= '<a ' . esc_attr( $nofolow ) . ' href="' . esc_url( $value['button_link']['link'] ) . '" ' . esc_attr( $target ) . ' class="animated xtpb-carousel-btn xtpb-btn-carousel1" '.$Button1Animation.'>' . esc_html( get_xtpb_array_value_by_key( $value, 'button_text' ) ) . '</a>';
					}

					//button 2
					$button2 = '';
					if ( ! empty( get_xtpb_array_value_by_key( $value, 'button_link2' )['link'] ) ) {
						$button2 .= '<a ' . esc_attr( $nofolow2 ) . ' href="' . esc_url( $value['button_link2']['link'] ) . '" ' . esc_attr( $target2 ) . ' class="animated xtpb-carousel-btn xtpb-btn-carousel2" '.$Button2Animation.'>' . esc_html( get_xtpb_array_value_by_key( $value, 'button_text2' ) ) . '</a>';
					}

					?>
            <div class="slick-slide-item repeater-<?php echo $key;?>">
              <div class="xtpb-carousel-content-wrap <?php echo get_xtpb_array_value_by_key( $value, 'slider_type' );?>">

							<?php if( get_xtpb_array_value_by_key( $value, 'overlay_en' ) == 1 ) { ?>
                                <div class="xtpb-carousel-overlay"></div>
							<?php } ?>
                            <div class="xtpb-container">
								<?php if ( get_xtpb_array_value_by_key( $value, 'slider_type' ) == "left_media" ) { ?>
                                    <div class="xtpb-carousel-media xtpb-carousel-media-left">
										<?php echo $ImageMedia;?>
                                    </div>
								<?php } ?>
								<?php if ( get_xtpb_array_value_by_key( $value, 'slider_type' ) == "center_content" ) { ?>
                                <div class="xtpb-carousel-content-in xtpb-carousel-content-middle">
									<?php } else { ?>
                                    <div class="xtpb-carousel-content-in">
										<?php } ?>
										<?php echo '<' . esc_attr( $selector ) . ' class="animated xtpb-carousel-title" '.$titleAnimation.'>' . esc_html( get_xtpb_array_value_by_key( $value, 'title' ) ) . '</' . esc_attr( $selector ) . '>';?>
										<?php echo '<' . esc_attr( $subselector ) . ' class="animated xtpb-carousel-subtitle" '.$SubtitleAnimation.'>' . esc_html( get_xtpb_array_value_by_key( $value, 'subtitle' ) ) . '</' . esc_attr( $subselector ) . '>';?>
										<?php echo '<div class="animated xtpb-carousel-content" '.$SubtitleAnimation.'>' . wp_kses_post( get_xtpb_array_value_by_key( $value, 'content' ) ) . '</div>';?>
										<?php echo $button1; ?>
										<?php echo $button2; ?>
                                    </div> <!--/.xtpb-carousel-content-in-->
									<?php if ( get_xtpb_array_value_by_key( $value, 'slider_type' ) == "right_media" ) { ?>
                                        <div class="xtpb-carousel-media xtpb-carousel-media-right">
											<?php echo $ImageMedia;?>
                                        </div>
									<?php } ?>
                                </div> <!--/.xtpb-container-->
                            </div> <!--/.xtpb-carousel-content-wrap-->
                        </div> <!--/.repeater-->
						<?php } ?>
                    </div> <!--/.xtpb-carousel-->
                </div> <!--/.xtpb-carousel-addon-content-->

			<?php $output .= ob_get_clean();
			return $output;
		}

		// Carousel Template
		public function getTemplate(){
			$output = '
			<div class="xtpb-carousel-addon-content">
					<div class="xtpb-carousel stick-dots" data-addon-id="{{data.id}}" data-settings="{{ JSON.stringify(_.omit(data, [\'carosuel_list\', \'id\', \'htmlContent\', \'type\', \'visibility\'])) }}">
						<#  _.forEach(data.carosuel_list, function(value, key) { #>
							<#
							let videoUrl = value.video_url || ""
							let videosrc = "";
							if ( videoUrl ) {
								let tempAchor = document.createElement("a")
									tempAchor.href = videoUrl
								let videoObj = {
									host    :   tempAchor.hostname,
									path    :   tempAchor.pathname,
									query   :   tempAchor.search.substr(tempAchor.search.indexOf("?") + 1)
								}
								switch( videoObj.host ){
									case "youtu.be":
										var videoId = videoObj.path.trim();
											videosrc = "//www.youtube.com/embed"+ videoId + "?&showinfo="+value.video_info
										break;
									case "www.youtube.com":
									case "youtube.com":
										var queryStr = videoObj.query.split("=");
											videosrc = "//www.youtube.com/embed/"+ queryStr[1] + "?&showinfo="+value.video_info
										break;
									case "www.vimeo.com":
									case "vimeo.com":
										var videoId = videoObj.path.trim();
											videosrc = "//player.vimeo.com/video"+ videoId
										break;
								}
							}

							var ImageMedia = "";
							if ( value.media_type == "media_video" ) {
								if ( videoUrl ) {
									ImageMedia  = "<div class=\'xtpb-video-slide\'><iframe height=\'280\' width:=\'100%\' class=\'xtpb-embed-responsive-item\' src=\'"+videosrc+"\' allowFullScreen></iframe></div>";
								}
							} else {
								if ( value.imageupload ) {
									ImageMedia  = "<img src=\'"+value.imageupload.url+"\' />";
								}
							}

							var selector = value.selector ? value.selector : "h2";
							var subselector = value.subselector ? value.subselector : "h3";
						#>

							<div class="slick-slide-item repeater-{{key}}">
								<div class="xtpb-carousel-content-wrap {{value.slider_type}}">
									<# if( value.overlay_en == 1 ) { #>
										<div class="xtpb-carousel-overlay"></div>
									<# } #>
									<div class="xtpb-container">

										<# if ( value.slider_type == "left_media" ) { #>
										<div class="xtpb-carousel-media xtpb-carousel-media-left">
											{{{ImageMedia}}}
										</div>
										<# } #>

										<# if ( value.slider_type == "center_content" ) { #>
											<div class="xtpb-carousel-content-in xtpb-carousel-content-middle">
										<# } else { #>
											<div class="xtpb-carousel-content-in">
										<# } #>
										
											<{{selector}} class="animated xtpb-carousel-title" <# if(value.title_animation) { #> data-animation-in="{{ value.title_animation.name }}" data-delay-in="{{value.title_animation.delay}}ms" data-duration-in="{{value.title_animation.duration}}ms" <# } #>  >{{{value.title}}}</{{selector}}>
											<{{subselector}} class="animated xtpb-carousel-subtitle" <# if(value.subtitle_animation) { #> data-animation-in="{{ value.subtitle_animation.name }}"  data-delay-in="{{value.subtitle_animation.delay}}ms" data-duration-in="{{value.subtitle_animation.duration}}ms" <# } #> >{{{value.subtitle}}}</{{subselector}}>
											<div class="animated xtpb-carousel-content" <# if(value.content_animation) { #> 
											data-animation-in="{{ value.content_animation.name }}" data-delay-in="{{value.content_animation.delay}}ms" data-duration-in="{{value.content_animation.duration}}ms" <# } #>  >{{{value.content}}} </div>
											<# if( value.button_link && value.button_link.link ){ #>
												<# if(value.btn_animation1) { #>
													<a {{ value.button_link.link ? "href="+value.button_link.link : "" }} {{ value.button_link.window ? "target=_blank" : "" }} {{ value.button_link.nofolow ? "rel=nofolow" : "" }}  class="animated xtpb-carousel-btn xtpb-btn-carousel1" data-animation-in="{{ value.btn_animation1.name }}" data-delay-in="{{value.btn_animation1.delay}}ms" data-duration-in="{{value.btn_animation1.duration}}ms" >
														{{ value.button_text }}
													</a>
												<# } else { #>
													<a {{ value.button_link.link ? "href="+value.button_link.link : "" }} {{ value.button_link.window ? "target=_blank" : "" }} {{ value.button_link.nofolow ? "rel=nofolow" : "" }}  class="animated xtpb-carousel-btn xtpb-btn-carousel1">
														{{ value.button_text }}
													</a>
												<# } #>
											<# } #>
											<# if( value.button_link2 && value.button_link2.link ){ #>
												<# if(value.btn_animation2) { #>
													<a {{ value.button_link2.link ? "href="+value.button_link2.link : "" }} {{ value.button_link2.window ? "target=_blank" : "" }} {{ value.button_link2.nofolow ? "rel=nofolow" : "" }}  class="animated xtpb-carousel-btn xtpb-btn-carousel2" data-animation-in="{{ value.btn_animation2.name }}" data-delay-in="{{value.btn_animation2.delay}}ms" data-duration-in="{{value.btn_animation2.duration}}ms" >
														{{ value.button_text2 }}
													</a>
												<# } else { #>
													<a {{ value.button_link2.link ? "href="+value.button_link2.link : "" }} {{ value.button_link2.window ? "target=_blank" : "" }} {{ value.button_link2.nofolow ? "rel=nofolow" : "" }}  class="animated xtpb-carousel-btn xtpb-btn-carousel2">
														{{ value.button_text2 }}
													</a>
												<# } #>
											<# } #>
										</div>
										<# if ( value.slider_type == "right_media" ) { #>
											<div class="xtpb-carousel-media xtpb-carousel-media-right">
												{{{ImageMedia}}}
											</div>
										<# } #>
									</div>	
						        </div>
						    </div>
						<# }); #>
					</div>
				</div>
		';
			return $output;
		}


	}
}
