<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Posts_Grid{

	protected $settings = array();

	public function __construct() {
		add_action('wp_enqueue_scripts', array($this, 'register_required_assets'));
		add_action('xtpb_enqueue_scripts', array($this, 'register_required_assets'));
		add_action('wp_ajax_xtpb_posts_addon_load_more', array($this, 'xtpb_posts_addon_load_more'));
		add_action('wp_ajax_nopriv_xtpb_posts_addon_load_more', array($this, 'xtpb_posts_addon_load_more'));
	}

	public function get_name(){
		return 'xtpb_posts_grid';
	}
	public function get_title(){
		return __( '文章列表-网格', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-interface';
	}

	public function register_required_assets(){
		wp_register_style( 'xtpb-posts-css', XTPB_DIR_URL . 'addons/posts/assets/css/posts-addon.css' );
		wp_register_script( 'xtpb-posts-addon', XTPB_DIR_URL . 'addons/posts/assets/js/posts-addon.js', array('jquery'), false, true );
		wp_localize_script('xtpb-posts-addon', 'xtpb_posts_addon', array(
			'ajax_url'  => admin_url('admin-ajax.php'),
			'nonce'     => wp_create_nonce( 'xtpb-posts-addon' ),
		) );
	}

	public function get_enqueue_script(){
		return array( 'xtpb-posts-addon' );
	}

	public function get_enqueue_style(){
		return array( 'xtpb-posts-css' );
	}

	public function get_editor_render_mode() {
		return 'server';
	}

	// Settings Fields
	public function get_settings() {
		$settings = array(
			'posts_column' => array(
				'type'      => 'slider',
				'title'     => __('列','xintheme-pagebuilder'),
				'responsive' => true,
				'std' => array(
					'md' => '3',
					'sm' => '2',
					'xs' => '1',
				),
				'step' => 1,
				'max' => 6,
				'min' => 1,
			),
			'posts_per_page' => array(
				'type'      => 'number',
				'title'     => __('每页文章数','xintheme-pagebuilder'),
				'std'       => '3'
			),
			'posts_image_size' => array(
				'type'      => 'select',
				'title'     => __('图片尺寸','xintheme-pagebuilder'),
				'values'    => xtpb_getLall_image_sizes_option(),
				'std'       => 'xtpb-medium',
			),
			'posts_title_position' => array(
				'type'      => 'select',
				'title'     => __('标题位置','xintheme-pagebuilder'),
				'values'    => array(
					'above_meta'       => __('元数据上方','xintheme-pagebuilder'),
					'below_meta'       => __('元数据下方','xintheme-pagebuilder'),
				),
				'std'       => 'above_meta',
			),
			'posts_title' => array(
				'type' => 'switch',
				'title' => __('标题','xintheme-pagebuilder'),
				'std' => '1'
			),
			'posts_title_tag' => array(
				'type'      => 'select',
				'title'     => __('标题标签','xintheme-pagebuilder'),
				'values'    => array(
					'h1'       => 'H1',
					'h2'       => 'H2',
					'h3'       => 'H3',
					'h4'       => 'H4',
					'h5'       => 'H5',
					'h6'       => 'H6',
					'span' 	   => 'span',
					'p' 		=> 'p',
					'div' 		=> 'div',
				),
				'std'       => 'h3',
			),
			'posts_excerpt' => array(
				'type' => 'switch',
				'title' => __('摘要','xintheme-pagebuilder'),
				'std' => '1'
			),
			'posts_excerpt_length' => array(
				'type'      => 'number',
				'title'     => __('摘要长度','xintheme-pagebuilder'),
				'std'       => '30'
			),
			'posts_metadata' => array(
				'type'      => 'select',
				'multiple'  => true,
				'title'     => __('元数据','xintheme-pagebuilder'),
				'values'    => array(
					'author'        => __('作者','xintheme-pagebuilder'),
					'date'          => __('日期','xintheme-pagebuilder'),
					'time'          => __('时间','xintheme-pagebuilder'),
					'comments'      => __('评论','xintheme-pagebuilder'),
					'tags'          => __('标签','xintheme-pagebuilder'),
					'categories'    => __('分类','xintheme-pagebuilder'),
				),
				'std'       => array('author','date','comments'),
			),
			'posts_separator' => array(
				'type'      => 'text',
				'title'     => __('分隔符','xintheme-pagebuilder'),
				'std'       => '/'
			),
			'posts_read_more' => array(
				'type' => 'switch',
				'title' => __('阅读更多','xintheme-pagebuilder'),
				'std' => '1'
			),
			'posts_read_more_text' => array(
				'type'      => 'text',
				'title'     => __('阅读更多文字','xintheme-pagebuilder'),
				'std'       => __('阅读更多 »','xintheme-pagebuilder')
			),

			//Query Section
			'post_type' => array(
				'type'      => 'select',
				'title'     => __('文章类型','xintheme-pagebuilder'),
				'values'    => get_post_types(array('public' => true)),
				'std'       => 'post',
				'section' => __('高级查询','xintheme-pagebuilder'),
			),
			'posts_categories' => array(
				'type'      => 'select',
				'multiple'  => true,
				'title'     => __('分类','xintheme-pagebuilder'),
				'values'    => xtpb_get_category_lists(),
				'std'       => array(),
				'section' => __('高级查询','xintheme-pagebuilder'),
			),
			'posts_tags' => array(
				'type'      => 'select',
				'multiple'  => true,
				'title'     => __('标签','xintheme-pagebuilder'),
				'values'    => xtpb_get_tags_lists(),
				'std'       => array(),
				'section' => __('高级查询','xintheme-pagebuilder'),
			),
			'post_order_by' => array(
				'type'      => 'select',
				'title'     => __('排序依据','xintheme-pagebuilder'),
				'values'    => array(
					'title' => __('标题','xintheme-pagebuilder'),
					'date' => __('日期','xintheme-pagebuilder'),
					'menu_order' => __('菜单顺序','xintheme-pagebuilder'),
					'comments_count' => __('评论数','xintheme-pagebuilder'),
					'random' => __('随机','xintheme-pagebuilder'),
				),
				'std'       => 'date',
				'section' => __('高级查询','xintheme-pagebuilder'),
			),

			'post_order' => array(
				'type'      => 'select',
				'title'     => __('排序','xintheme-pagebuilder'),
				'values'    => array(
					'desc' => __('降序','xintheme-pagebuilder'),
					'asc' => __('升序','xintheme-pagebuilder'),
				),
				'std'       => 'desc',
				'section' => __('高级查询','xintheme-pagebuilder'),
			),
			'posts_ids' => array(
				'type'      => 'text',
				'title'     => __('ID 列表','xintheme-pagebuilder'),
				'std'       => '',
				'section' => __('高级查询','xintheme-pagebuilder'),
			),
			'posts_exclude_ids' => array(
				'type'      => 'text',
				'title'     => __('排除 ID 列表','xintheme-pagebuilder'),
				'std'       => '',
				'section' => __('高级查询','xintheme-pagebuilder'),
				'desc' => __('从查询结果中排除这些 ID，多个 ID 用英文逗号分隔。','xintheme-pagebuilder'),
			),

			//分页
			'posts_enable_pagination' => array(
				'type' => 'switch',
				'title' => __('分页','xintheme-pagebuilder'),
				'std' => '0',
				'section' => __('分页','xintheme-pagebuilder'),
			),

			'post_pagination_type' => array(
				'type'      => 'select',
				'title'     => __('分页类型','xintheme-pagebuilder'),
				'values'    => array(
					'numbers_next_previous' => __('数字 + 上一页/下一页','xintheme-pagebuilder'),
					'next_previous' => __('上一页/下一页','xintheme-pagebuilder'),
					'numbers' => __('数字','xintheme-pagebuilder'),
					'load_more' => __('加载更多直到页尾','xintheme-pagebuilder'),
				),
				'std'       => 'numbers_next_previous',
				'section' => __('分页','xintheme-pagebuilder'),
			),

			// Style Area

			//title
			'post_title_color' => array(
				'type' => 'color',
				'title' => __('标题颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('文章标题','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-title a { color: {{data.post_title_color}}; }'
			),
			'post_title_hcolor' => array(
				'type' => 'color',
				'title' => __('标题悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('文章标题','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-title a:hover { color: {{data.post_title_hcolor}}; }'
			),
			'post_title_size' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-post-grid-title',
				'section' => __('文章标题','xintheme-pagebuilder'),
				'tab' => 'style',
			),
			'title_item_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'std' => array(
					'md' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '10px', 'left' => '0px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-post-grid-title { margin: {{data.title_item_margin}}; }',
				'section' => __('文章标题','xintheme-pagebuilder'),
			),

			//post meta
			'post_meta_item_color' => array(
				'type' => 'color',
				'title' => __('元数据项颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('文章元数据','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-meta,{{SELECTOR}} .xtpb-post-grid-meta a { color: {{data.post_meta_item_color}}; }'
			),
			'post_meta_item_hcolor' => array(
				'type' => 'color',
				'title' => __('元数据项悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('文章元数据','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-meta a:hover { color: {{data.post_meta_item_hcolor}}; }'
			),
			'post_meta_item' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'12px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-meta',
				'section' => __('文章元数据','xintheme-pagebuilder'),
				'tab' => 'style',
			),
			'post_meta_item_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-post-grid-meta { margin: {{data.post_meta_item_margin}}; }',
				'section' => __('文章元数据','xintheme-pagebuilder'),
			),

			//separator
			'post_meta_size' => array(
				'type' => 'slider',
				'title' => __('字体大小','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 10,
						'step' => .1,
					),
					'px' => array(
						'min' => 5,
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
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __('元数据分隔符','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-postmeta-sept { font-size: {{data.post_meta_size}}; }'
			),
			'post_meta_height' => array(
				'type' => 'slider',
				'title' => __('行高','xintheme-pagebuilder'),
				'std' => array(
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 10,
						'step' => .1,
					),
					'px' => array(
						'min' => 5,
						'max' => 100,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'tab' => 'style',
				'section' => __('元数据分隔符','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-postmeta-sept { line-height: {{data.post_meta_height}}; }'
			),
			'post_meta_separator' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('元数据分隔符','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-postmeta-sept { color: {{data.post_meta_separator}}; }'
			),
			'post_meta_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'std' => array(
					'md' => array( 'top' => '0px', 'right' => '8px', 'bottom' => '0px', 'left' => '8px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-postmeta-sept { margin: {{data.post_meta_margin}}; }',
				'section' => __('元数据分隔符','xintheme-pagebuilder'),
			),

			//image
			'post_img_width' => array(
				'type' => 'slider',
				'title' => __('图片宽度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 50,
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
				'responsive' => true,
				'tab' => 'style',
				'section' => __('文章图片','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-img img { width: {{data.post_img_width}}; }'
			),
			'post_img_height' => array(
				'type' => 'slider',
				'title' => __('图片高度','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 50,
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
				'responsive' => true,
				'tab' => 'style',
				'section' => __('文章图片','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-img img { height: {{data.post_img_height}}; }'
			),
			'post_border_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __('文章图片','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-img img { border-radius: {{data.post_border_radius}}; }'
			),
			'post_img_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('文章图片','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'std' => array(
					'md' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '15px', 'left' => '0px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'selector' => '{{SELECTOR}} .xtpb-post-grid-img img { margin: {{data.post_img_margin}}; }'
			),

			//Content
			'post_content_color' => array(
				'type' => 'color',
				'title' => __('内容颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('文章内容','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-intro { color: {{data.post_content_color}}; }'
			),
			'post_content_size' => array(
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
				'selector' => '{{SELECTOR}} .xtpb-post-grid-intro',
				'section' => __('文章内容','xintheme-pagebuilder'),
				'tab' => 'style',
			),
			'post_content_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'std' => array(
					'md' => array( 'top' => '10px', 'right' => '0px', 'bottom' => '15px', 'left' => '0px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-intro { margin: {{data.post_content_margin}}; }',
				'section' => __('文章内容','xintheme-pagebuilder'),
			),

			//Button
			'post_button_color' => array(
				'type' => 'color',
				'title' => __('按钮颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('文章按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn { color: {{data.post_button_color}}; }'
			),
			'post_button_hcolor' => array(
				'type' => 'color',
				'title' => __('按钮悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('文章按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn:hover { color: {{data.post_button_hcolor}}; }'
			),
			'post_btton_bg' => array(
				'type' => 'color2',
				'title' => __('按钮背景','xintheme-pagebuilder'),
				'clip' => false,
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn',
				'tab' => 'style',
				'section' => __('文章按钮','xintheme-pagebuilder'),
			),
			'post_btton_hover_bg' => array(
				'type' => 'color2',
				'title' => __('按钮悬停背景','xintheme-pagebuilder'),
				'clip' => false,
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn:hover',
				'tab' => 'style',
				'section' => __('文章按钮','xintheme-pagebuilder'),
			),
			'post_button_size' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'12px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn',
				'section' => __('文章按钮','xintheme-pagebuilder'),
				'tab' => 'style',
			),
			'post_button_border' => array(
				'type' => 'border',
				'title' => __('边框','xintheme-pagebuilder'),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __('文章按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn'
			),
			'post_border_hcolor' => array(
				'type' => 'border',
				'title' => __('悬停边框','xintheme-pagebuilder'),
				'std' => array(
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __('文章按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn:hover'
			),
			'post_buttom_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __('文章按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn { border-radius: {{data.post_buttom_radius}}; }'
			),
			'post_buttom_hradius' => array(
				'type' => 'dimension',
				'title' => __('悬停边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __('文章按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn:hover { border-radius: {{data.post_buttom_hradius}}; }'
			),
			'post_button_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn { margin: {{data.post_button_margin}}; }',
				'section' => __('文章按钮','xintheme-pagebuilder'),
			),
			'post_button_padding' => array(
				'type' => 'dimension',
				'title' => __('内边距','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn { padding: {{data.post_button_padding}}; }',
				'section' => __('文章按钮','xintheme-pagebuilder'),
			),
			'post_button_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'section' => __('文章按钮','xintheme-pagebuilder'),
				'selector' => '{{SELECTOR}} .xtpb-post-grid-btn-wrap { text-align: {{data.post_button_align}}; }'
			),

			//加载更多
			'post_load_more_color' => array(
				'type' => 'color',
				'title' => __('按钮颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('加载更多','xintheme-pagebuilder'),
				'std' => '#fff',
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn { color: {{data.post_load_more_color}}; }'
			),
			'post_load_more_hcolor' => array(
				'type' => 'color',
				'title' => __('按钮悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('加载更多','xintheme-pagebuilder'),
				'std' => '#fff',
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn:hover { color: {{data.post_load_more_hcolor}}; }'
			),
			'post_load_more_bg' => array(
				'type' => 'color2',
				'title' => __('按钮背景','xintheme-pagebuilder'),
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#3666E4',
					'clip' => false
				),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn',
				'tab' => 'style',
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'section' => __('加载更多','xintheme-pagebuilder'),
			),
			'post_load_more_hover_bg' => array(
				'type' => 'color2',
				'title' => __('按钮悬停背景','xintheme-pagebuilder'),
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#2a55c5',
					'clip' => false
				),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn:hover',
				'tab' => 'style',
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'section' => __('加载更多','xintheme-pagebuilder'),
			),
			'post_load_more_size' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'12px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn',
				'section' => __('加载更多','xintheme-pagebuilder'),
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'tab' => 'style',
			),
			'post_load_more_border' => array(
				'type' => 'border',
				'title' => __('边框','xintheme-pagebuilder'),
				'std' => array(
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __('加载更多','xintheme-pagebuilder'),
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn'
			),
			'post_loadmore_border_hcolor' => array(
				'type' => 'border',
				'title' => __('悬停边框','xintheme-pagebuilder'),
				'std' => array(
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#cccccc' 
				),
				'tab' => 'style',
				'section' => __('加载更多','xintheme-pagebuilder'),
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn:hover'
			),
			'post_load_more_radius' => array(
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
				'section' => __('加载更多','xintheme-pagebuilder'),
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn { border-radius: {{data.post_load_more_radius}}; }'
			),
			'post_load_more_hradius' => array(
				'type' => 'dimension',
				'title' => __('悬停边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __('加载更多','xintheme-pagebuilder'),
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn:hover { border-radius: {{data.post_load_more_hradius}}; }'
			),
			'post_load_more_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn { margin: {{data.post_load_more_margin}}; }',
				'section' => __('加载更多','xintheme-pagebuilder'),
			),
			'post_load_more_padding' => array(
				'type' => 'dimension',
				'title' => __('内边距','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'std' => array(
					'md' => array( 'top' => '10px', 'right' => '25px', 'bottom' => '10px', 'left' => '25px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-loadmore-btn { padding: {{data.post_load_more_padding}}; }',
				'section' => __('加载更多','xintheme-pagebuilder'),
			),
			'post_load_more_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'section' => __('加载更多','xintheme-pagebuilder'),
				'std' => array( 'md' => 'center', 'sm' => '', 'xs' => '' ),
				'depends' => array(array('post_pagination_type', '=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-pagination{ text-align: {{data.post_load_more_align}}; }'
			),

			//Navigation
			'post_nav_color' => array(
				'type' => 'color',
				'title' => __('导航颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __('导航','xintheme-pagebuilder'),
				'std' => '#000',
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-paginate-link { color: {{data.post_nav_color}}; }'
			),
			'post_nav_hcolor' => array(
				'type' => 'color',
				'title' => __('导航悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'std' => '#fff',
				'section' => __('导航','xintheme-pagebuilder'),
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-paginate-link:hover,{{SELECTOR}} .paginate-active { color: {{data.post_nav_hcolor}}; }'
			),
			'post_nav_bg' => array(
				'type' => 'color2',
				'title' => __('导航背景','xintheme-pagebuilder'),
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#fff',
					'clip' => false
				),
				'selector' => '{{SELECTOR}} .xtpb-posts-paginate-link',
				'tab' => 'style',
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'section' => __('导航','xintheme-pagebuilder'),
			),
			'post_nav_hover_bg' => array(
				'type' => 'color2',
				'title' => __('导航悬停背景','xintheme-pagebuilder'),
				'clip' => false,
				'std' => array(
					'colorType' => 'color',
					'colorColor' => '#3666E4',
					'clip' => false
				),
				'selector' => '{{SELECTOR}} .xtpb-posts-paginate-link:hover, {{SELECTOR}} .paginate-active',
				'tab' => 'style',
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'section' => __('导航','xintheme-pagebuilder'),
			),
			'post_nav_size' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'12px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-posts-paginate-link',
				'section' => __('导航','xintheme-pagebuilder'),
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'tab' => 'style',
			),
			'post_navborder' => array(
				'type' => 'border',
				'title' => __('边框','xintheme-pagebuilder'),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#f5f5f5' 
				),
				'tab' => 'style',
				'section' => __('导航','xintheme-pagebuilder'),
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-paginate-link'
			),
			'post_nav_border_hcolor' => array(
				'type' => 'border',
				'title' => __('悬停边框','xintheme-pagebuilder'),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ), 
					'borderStyle' => 'solid', 
					'borderColor' => '#3666E4' 
				),
				'tab' => 'style',
				'section' => __('导航','xintheme-pagebuilder'),
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-paginate-link:hover,{{SELECTOR}} .paginate-active'
			),
			'post_nav_radius' => array(
				'type' => 'dimension',
				'title' => __('边框圆角','xintheme-pagebuilder'),
				'unit' => array( '%','px','em' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __('导航','xintheme-pagebuilder'),
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-paginate-link { border-radius: {{data.post_nav_radius}}; }'
			),
			'post_nav_margin' => array(
				'type' => 'dimension',
				'title' => __('外边距','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'std' => array(
					'md' => array( 'top' => '0px', 'right' => '5px', 'bottom' => '0px', 'left' => '0px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-paginate-link { margin: {{data.post_nav_margin}}; }',
				'section' => __('导航','xintheme-pagebuilder'),
			),
			'post_nav_padding' => array(
				'type' => 'dimension',
				'title' => __('内边距','xintheme-pagebuilder'),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'std' => array(
					'md' => array( 'top' => '5px', 'right' => '15px', 'bottom' => '5px', 'left' => '15px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'selector' => '{{SELECTOR}} .xtpb-posts-paginate-link { padding: {{data.post_nav_padding}}; }',
				'section' => __('导航','xintheme-pagebuilder'),
			),
			'post_nav_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'responsive' => true,
				'tab' => 'style',
				'section' => __('导航','xintheme-pagebuilder'),
				'std' => array( 'md' => 'center', 'sm' => '', 'xs' => '' ),
				'depends' => array(array('post_pagination_type', '!=', array('load_more'))),
				'selector' => '{{SELECTOR}} .xtpb-posts-addon-pagination{ text-align: {{data.post_nav_align}}; }'
			),

		);

		return $settings;
	}

	/**
	 * Sanitize array
	 * 
	 * @param array $settings
	 * 
	 * @return array $settings
	 */
	private function xtpb_sanitize_array( $settings = array() ) {
		$sanitized_array = array();

		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $setting ) {
				if ( is_array( $setting ) ) {
					$sanitized_array[ $key ] = $this->xtpb_sanitize_array( $setting );
				} else {
					$key                     = sanitize_text_field( $key );
					$setting                 = sanitize_text_field( $setting );
					$sanitized_array[ $key ] = $setting;
				}
			}
		}

		return $sanitized_array;
	}


	// Posts Render HTML
	public function render($data = null){
		$settings 				= $data['settings'];
		$runtime_page_id        = isset( $data['_page_id'] ) ? absint( $data['_page_id'] ) : get_the_ID();
		$runtime_paged          = isset( $data['_paged'] ) ? max( 1, absint( $data['_paged'] ) ) : 1;

		$this->settings = $settings;
		add_filter('excerpt_more', array($this, 'excerpt_more_text'), 30);
		add_filter('excerpt_length', array($this, 'excerpt_length'), 30);

		$posts_layout           = isset($settings['posts_layout']) ? sanitize_text_field( $settings['posts_layout'] ) : '';
		$posts_column        	= isset($settings['posts_column']) ? $this->xtpb_sanitize_array( $settings['posts_column'] ) : array();
		$posts_title_position   = isset($settings['posts_title_position']) ? sanitize_text_field( $settings['posts_title_position'] ) : 'above_meta';
		$posts_per_page         = isset($settings['posts_per_page']) ? sanitize_text_field( $settings['posts_per_page'] ) : '-1';
		$posts_image_size       = isset($settings['posts_image_size']) ? sanitize_text_field( $settings['posts_image_size'] ) : 'xtpb-medium';
		$posts_image_position   = isset($settings['posts_image_position']) ? sanitize_text_field( $settings['posts_image_position'] ) : '';
		$posts_title            = (bool) isset($settings['posts_title']) ? sanitize_text_field( $settings['posts_title'] ) : false;
		$posts_title_tag        = isset($settings['posts_title_tag']) ? sanitize_text_field( $settings['posts_title_tag'] ) : 'h3';
		$posts_excerpt          = (bool) isset($settings['posts_excerpt']) ? sanitize_text_field( $settings['posts_excerpt'] ) : false;
		$posts_excerpt_length   = isset($settings['posts_excerpt_length']) ? sanitize_text_field( $settings['posts_excerpt_length'] ) : 30;
		$posts_metadata         = isset($settings['posts_metadata']) ? $this->xtpb_sanitize_array( $settings['posts_metadata'] ) : array();
		$posts_separator        = isset($settings['posts_separator']) ? sanitize_text_field( $settings['posts_separator'] ) : '/';
		$posts_separator        = '<span class="xtpb-postmeta-sept">' . wp_kses_post( $posts_separator ) . '</span>';
		$posts_read_more        = (bool) isset($settings['posts_read_more']) ? sanitize_text_field( $settings['posts_read_more'] ) : false;
		$posts_read_more_text   = isset($settings['posts_read_more_text']) ? sanitize_text_field( $settings['posts_read_more_text'] ) : __( '阅读更多 »', 'xintheme-pagebuilder' );

		//Advance Query
		$post_type              = isset($settings['post_type']) ? sanitize_text_field( $settings['post_type'] ) : 'post';
		$posts_categories       = isset($settings['posts_categories']) ? $this->xtpb_sanitize_array( $settings['posts_categories'] ) : array();
		$posts_tags             = isset($settings['posts_tags']) ? $this->xtpb_sanitize_array( $settings['posts_tags'] ) : array();
		$post_order_by          = isset($settings['post_order_by']) ? sanitize_text_field( $settings['post_order_by'] ) : 'date';
		$post_order             = isset($settings['post_order']) ? sanitize_text_field( $settings['post_order'] ) : 'desc';
		$posts_ids              = isset($settings['posts_ids']) ? sanitize_text_field( $settings['posts_ids'] ) : false;
		$posts_exclude_ids      = isset($settings['posts_exclude_ids']) ? sanitize_text_field( $settings['posts_exclude_ids'] ) : false;

		//分页
		$posts_enable_pagination   = (bool) isset($settings['posts_enable_pagination']) ? sanitize_text_field( $settings['posts_enable_pagination'] ) : false;
		$post_pagination_type      = isset($settings['post_pagination_type']) ? sanitize_text_field( $settings['post_pagination_type'] ) : 'numbers_next_previous';

		//Query Arguments
		$args = array(
			'post_type'         => $post_type,
			'post_status'       => 'publish',
			'posts_per_page'    => $posts_per_page,
			'orderby'           => $post_order_by,
			'order'             => $post_order,
		);

		//pagination
		if ( $posts_enable_pagination ) {
			$paged = $runtime_paged;
			$current_page = $paged;
			$args['paged'] = $paged;
			$paged = $paged+1;
		}

		if ( $posts_ids ) {
			$args['post__in'] = explode( ',', $posts_ids );
		}
		if ( $posts_exclude_ids ) {
			$args['post__not_in'] = explode( ',', $posts_exclude_ids );
		}
		if ( is_array( $posts_categories ) && count( $posts_categories ) ) {
			$args['category__in'] = $posts_categories;
		}
		if ( is_array( $posts_tags ) && count( $posts_tags ) ) {
			$args['tag__in'] = $posts_tags;
		}

		$the_query = new WP_Query( $args );

		ob_start();
		if ( $the_query->have_posts() ) {
			?>
	
            <div class="xtpb-posts-addon-content" data-page-id="<?php echo esc_attr( $runtime_page_id ); ?>">
					<div class="xtpb-addons-col">
						<?php 
						if ( ! is_array( $posts_metadata ) ) {
							$posts_metadata = explode( ',', $posts_metadata );
						}

						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$post_title = get_the_title();
							$post_excerpt = get_the_excerpt();
							$post_permalink = get_the_permalink();

							//MetaData
							$meta_data_print = '';
							if ( count( $posts_metadata ) ) {
								$meta_data = array();
								if ( in_array( 'author', $posts_metadata ) ) {
									$meta_data['author'] = get_the_author();
								}
								if ( in_array( 'date', $posts_metadata ) ) {
									$meta_data['date'] = get_the_date();
								}
								if ( in_array( 'time', $posts_metadata ) ) {
									$meta_data['time'] = get_the_time();
								}
								if ( in_array( 'comments', $posts_metadata ) ) {
									$meta_data['comments'] = get_comments_number() . ' ' . __( '评论', 'xintheme-pagebuilder' );
								}
								if ( in_array( 'tags', $posts_metadata ) ) {
									if ( get_the_tag_list() ) {
										$meta_data['tags'] = get_the_tag_list( '', ', ' );
									}
								}
								if ( in_array( 'categories', $posts_metadata ) ) {
									if ( get_the_category_list() ) {
										$meta_data['categories'] = get_the_category_list( ', ' );
									}
								}
								$meta_data_arr   = apply_filters( 'wppn_posts_addon_meta_data', $meta_data );
								$meta_data_print = implode( "{$posts_separator}", $meta_data_arr );
							}
							//Post feature Image
							$img_url = get_the_post_thumbnail_url( get_the_ID(), $posts_image_size );
							?>
							<div class="xtpb-addons-col-md<?php echo esc_attr( $posts_column['md'] ); ?> xtpb-addons-col-sm<?php echo esc_attr( $posts_column['sm'] ); ?> xtpb-addons-col-xs<?php echo esc_attr( $posts_column['xs'] ); ?>">
								
								<?php

								echo '<div class="xtpb-post-grid-wrap xtpb-post-grid-one">';
								if ( has_post_thumbnail() ) {
									echo '<div class="xtpb-post-grid-img">';
									echo '<a href="' . esc_url( $post_permalink ) . '"><img src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $post_title ) . '"/></a>';
									echo '</div>';//xtpb-post-grid-img
								}
								echo '<div class="xtpb-post-grid-content">';
								if ( $posts_title_position == 'above_meta' ) {
									if ( $posts_title ) {
										echo '<' . esc_attr( $posts_title_tag ) . ' class="xtpb-post-grid-title"><a href="' . esc_url( $post_permalink ) . '">' . esc_html( $post_title ) . '</a></' . esc_attr( $posts_title_tag ) . '>';
									}
								}
								if ( count( $posts_metadata ) ) {
									echo '<div class="xtpb-post-grid-meta">' . wp_kses_post( $meta_data_print ) . '</div>';
								}
								if ( $posts_title_position == 'below_meta' ) {
									if ( $posts_title ) {
										echo '<' . esc_attr( $posts_title_tag ) . ' class="xtpb-post-grid-title"><a href="' . esc_url( $post_permalink ) . '">' . esc_html( $post_title ) . '</a></' . esc_attr( $posts_title_tag ) . '>';
									}
								}
								if ( $posts_excerpt ) {
									echo '<div class="xtpb-post-grid-intro">' . wp_kses_post( $post_excerpt ) . '</div>';
								}
								if ( $posts_read_more ) {
									echo '<div class="xtpb-post-grid-btn-wrap"><a class="xtpb-post-grid-btn" href="' . esc_url( $post_permalink ) . '">' . esc_html( $posts_read_more_text ) . '</a></div>';
								}
								echo '</div>';//xtpb-post-grid-content
								echo '</div>';//xtpb-post-grid-wrap
								?>
							</div> <!--/.xtpb-addons-col-->
							<?php
						}
						wp_reset_postdata();
						?>
					</div><!--/.xtpb-addons-col-->

					<?php
					if ( $posts_enable_pagination ) {
						$max_pages = $the_query->max_num_pages;
						$data_paged = ( $paged > $max_pages ) ? $max_pages : $paged;

						echo "<div class='xtpb-posts-addon-pagination'>";

							$previous_page = $paged - 2;

							if ( $post_pagination_type === 'numbers_next_previous' ) {
								if ( $current_page > 1 ) {
									echo '<a href="#" class="xtpb-posts-paginate-link" data-paged="' . esc_attr( $previous_page ) . '"><i class="ri-arrow-left-s-line"></i></a>';
								} else {
									echo '<span class="xtpb-posts-paginate-link xtpb-posts-paginate-link-disable"><i class="ri-arrow-left-s-line"></i></span>';
								}

								for ( $i = 1; $i <= $max_pages; $i++ ) {
									$active_class = ( $i == $current_page ) ? 'paginate-active' : '';
									echo '<a href="#" class="xtpb-posts-paginate-link ' . esc_attr( $active_class ) . '" data-paged="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</a>';
								}
								if ( $current_page < $max_pages ) {
									echo '<a href="#" class="xtpb-posts-paginate-link" data-paged="' . esc_attr( $data_paged ) . '"><i class="ri-arrow-right-s-line"></i></a>';
								} else {
									echo '<span class="xtpb-posts-paginate-link xtpb-posts-paginate-link-disable"><i class="ri-arrow-right-s-line"></i></span>';
								}
							}
							if ( $post_pagination_type === 'numbers' ) {
								for ( $i = 1; $i <= $max_pages; $i++ ) {
									$active_class = ( $i == $current_page ) ? 'paginate-active' : '';
									echo '<a href="#" class="xtpb-posts-paginate-link ' . esc_attr( $active_class ) . '" data-paged="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</a>';
								}
							}
							if ( $post_pagination_type === 'next_previous' ) {
								if ( $current_page > 1 ) {
									echo '<a href="#" class="xtpb-posts-paginate-link" data-paged="' . esc_attr( $previous_page ) . '"><i class="ri-arrow-left-s-line"></i></a>';
								} else {
									echo "<span class='xtpb-posts-paginate-link xtpb-posts-paginate-link-disable'><i class='ri-arrow-left-s-line'></i></span>";
								}

								if ( $current_page < $max_pages ) {
									echo '<a href="#" class="xtpb-posts-paginate-link" data-paged="' . esc_attr( $data_paged ) . '"><i class="ri-arrow-right-s-line"></i></a>';
								} else {
									echo "<span class='xtpb-posts-paginate-link xtpb-posts-paginate-link-disable'><i class='ri-arrow-right-s-line'></i></span>";
								}
							}
							if ( $post_pagination_type === 'load_more' ) {
								echo '<a href="#" data-paged="' . esc_attr( $paged ) . '" class="xtpb-posts-addon-loadmore-btn">' . esc_html__( '加载更多', 'xintheme-pagebuilder' ) . '</a>';
							}
						echo '</div>';//xtpb-posts-addon-pagination
						
					}
					?>
            </div><!--/.xtpb-posts-addon-content-->
			<?php
		}

		return ob_get_clean();
	}

	public function excerpt_length(){
		return isset( $this->settings['posts_excerpt_length'] ) ? $this->settings['posts_excerpt_length'] : 30;
	}

	public function excerpt_more_text($more){
		return '';
	}

	public function xtpb_posts_addon_load_more(){
	    check_ajax_referer( 'xtpb-posts-addon', 'nonce' );

	    $addon_id   = isset( $_POST['addon_id'] ) ? absint( $_POST['addon_id'] ) : 0;
	    $page_id    = isset( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : 0;
	    $paged      = isset( $_POST['paged'] ) ? max( 1, absint( $_POST['paged'] ) ) : 1;

	    $addon = xtpb_get_saved_addon_settings( $addon_id, $page_id );

	    if ( empty( $addon ) || ! is_array( $addon ) || empty( $addon['name'] ) || 'xtpb_posts_grid' !== $addon['name'] ) {
	    	wp_send_json_error( array( 'msg' => __( '文章列表组件配置不存在。', 'xintheme-pagebuilder' ) ) );
	    }

	    $addon['_page_id'] = absint( $page_id );
	    $addon['_paged'] = $paged;

	    wp_send_json_success( array( 'html' => $this->render( $addon ) ) );
    }

public function getTemplate(){
	$output = '
	<#
	function xtpbSwitchOn(value) {
		return String(value) === "1" || value === true || value === 1;
	}

	function xtpbSafeTitleTag(tag) {
		var allowTags = ["h1", "h2", "h3", "h4", "h5", "h6", "span", "p", "div"];
		tag = tag || "h3";
		return _.indexOf(allowTags, tag) !== -1 ? tag : "h3";
	}

	function xtpbPreviewImage() {
		return "data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27640%27 height=%27360%27 viewBox=%270 0 640 360%27%3E%3Crect width=%27640%27 height=%27360%27 rx=%2716%27 fill=%27%23f3f4f6%27/%3E%3Ctext x=%27320%27 y=%27180%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27 font-size=%2728%27 fill=%27%239ca3af%27%3E%E6%96%87%E7%AB%A0%E5%9B%BE%E7%89%87%3C/text%3E%3C/svg%3E";
	}

	var postsColumn = data.posts_column || {};
	var columnMd = postsColumn.md || "3";
	var columnSm = postsColumn.sm || "2";
	var columnXs = postsColumn.xs || "1";

	var postsPerPage = parseInt(data.posts_per_page || 3, 10);
	if (!postsPerPage || postsPerPage < 1) {
		postsPerPage = 3;
	}

	var postsTitlePosition = data.posts_title_position || "above_meta";
	var postsTitle = xtpbSwitchOn(data.posts_title);
	var postsTitleTag = xtpbSafeTitleTag(data.posts_title_tag);

	var postsExcerpt = xtpbSwitchOn(data.posts_excerpt);
	var postsMetadata = data.posts_metadata || ["author", "date", "comments"];

	if (typeof postsMetadata === "string") {
		postsMetadata = postsMetadata ? postsMetadata.split(",") : [];
	}

	var postsSeparatorText = data.posts_separator || "/";
	var postsSeparator = \'<span class="xtpb-postmeta-sept">\' + postsSeparatorText + \'</span>\';

	var postsReadMore = xtpbSwitchOn(data.posts_read_more);
	var postsReadMoreText = data.posts_read_more_text || "阅读更多 »";

	var postsEnablePagination = xtpbSwitchOn(data.posts_enable_pagination);
	var postPaginationType = data.post_pagination_type || "numbers_next_previous";

	var posts = data.posts || [];

	/*
	 * 如果后期你做 AJAX 真实文章预览，可以把真实文章数据放到 data.posts：
	 * [
	 *   {
	 *     title: "文章标题",
	 *     url: "#",
	 *     thumb: "图片地址",
	 *     excerpt: "摘要",
	 *     author: "作者",
	 *     date: "2026-05-27",
	 *     time: "12:00",
	 *     comments: "0 评论",
	 *     tags: "标签",
	 *     categories: "分类"
	 *   }
	 * ]
	 *
	 * 现在没有 data.posts 时，自动生成占位文章。
	 */
	if (!posts.length) {
		for (var i = 1; i <= postsPerPage; i++) {
			posts.push({
				title: "文章标题预览 " + i,
				url: "javascript:void(0);",
				thumb: xtpbPreviewImage(),
				excerpt: "这里是文章摘要预览内容，前端页面会根据查询条件显示真实文章数据。",
				author: "admin",
				date: "2026-05-27",
				time: "12:00",
				comments: "0 评论",
				tags: "标签",
				categories: "分类"
			});
		}
	}

	function xtpbBuildMeta(post) {
		var meta = [];

		if (_.indexOf(postsMetadata, "author") !== -1 && post.author) {
			meta.push(post.author);
		}

		if (_.indexOf(postsMetadata, "date") !== -1 && post.date) {
			meta.push(post.date);
		}

		if (_.indexOf(postsMetadata, "time") !== -1 && post.time) {
			meta.push(post.time);
		}

		if (_.indexOf(postsMetadata, "comments") !== -1 && post.comments) {
			meta.push(post.comments);
		}

		if (_.indexOf(postsMetadata, "tags") !== -1 && post.tags) {
			meta.push(post.tags);
		}

		if (_.indexOf(postsMetadata, "categories") !== -1 && post.categories) {
			meta.push(post.categories);
		}

		return meta.join(postsSeparator);
	}
	#>

	<# if (posts.length) { #>
		<div class="xtpb-posts-addon-content" data-page-id="">
			<div class="xtpb-addons-col">

				<# _.forEach(posts, function(post) { #>
					<#
					var postTitle = post.title || "";
					var postUrl = post.url || "javascript:void(0);";
					var postThumb = post.thumb || "";
					var postExcerpt = post.excerpt || "";
					var metaHtml = xtpbBuildMeta(post);
					#>

					<div class="xtpb-addons-col-md{{ columnMd }} xtpb-addons-col-sm{{ columnSm }} xtpb-addons-col-xs{{ columnXs }}">

						<div class="xtpb-post-grid-wrap xtpb-post-grid-one">

							<# if (postThumb) { #>
								<div class="xtpb-post-grid-img">
									<a href="{{ postUrl }}">
										<img src="{{ postThumb }}" alt="{{ postTitle }}">
									</a>
								</div>
							<# } #>

							<div class="xtpb-post-grid-content">

								<# if (postsTitlePosition === "above_meta" && postsTitle && postTitle) { #>
									<{{ postsTitleTag }} class="xtpb-post-grid-title">
										<a href="{{ postUrl }}">{{ postTitle }}</a>
									</{{ postsTitleTag }}>
								<# } #>

								<# if (postsMetadata.length && metaHtml) { #>
									<div class="xtpb-post-grid-meta">
										{{{ metaHtml }}}
									</div>
								<# } #>

								<# if (postsTitlePosition === "below_meta" && postsTitle && postTitle) { #>
									<{{ postsTitleTag }} class="xtpb-post-grid-title">
										<a href="{{ postUrl }}">{{ postTitle }}</a>
									</{{ postsTitleTag }}>
								<# } #>

								<# if (postsExcerpt && postExcerpt) { #>
									<div class="xtpb-post-grid-intro">
										{{{ postExcerpt }}}
									</div>
								<# } #>

								<# if (postsReadMore) { #>
									<div class="xtpb-post-grid-btn-wrap">
										<a class="xtpb-post-grid-btn" href="{{ postUrl }}">
											{{ postsReadMoreText }}
										</a>
									</div>
								<# } #>

							</div>

						</div>

					</div>
				<# }); #>

			</div>

			<# if (postsEnablePagination) { #>
				<div class="xtpb-posts-addon-pagination">

					<# if (postPaginationType === "numbers_next_previous") { #>
						<span class="xtpb-posts-paginate-link xtpb-posts-paginate-link-disable">
							<i class="ri-arrow-left-s-line"></i>
						</span>

						<a href="#" class="xtpb-posts-paginate-link paginate-active" data-paged="1">1</a>
						<a href="#" class="xtpb-posts-paginate-link" data-paged="2">2</a>
						<a href="#" class="xtpb-posts-paginate-link" data-paged="3">3</a>

						<a href="#" class="xtpb-posts-paginate-link" data-paged="2">
							<i class="ri-arrow-right-s-line"></i>
						</a>
					<# } #>

					<# if (postPaginationType === "numbers") { #>
						<a href="#" class="xtpb-posts-paginate-link paginate-active" data-paged="1">1</a>
						<a href="#" class="xtpb-posts-paginate-link" data-paged="2">2</a>
						<a href="#" class="xtpb-posts-paginate-link" data-paged="3">3</a>
					<# } #>

					<# if (postPaginationType === "next_previous") { #>
						<span class="xtpb-posts-paginate-link xtpb-posts-paginate-link-disable">
							<i class="ri-arrow-left-s-line"></i>
						</span>

						<a href="#" class="xtpb-posts-paginate-link" data-paged="2">
							<i class="ri-arrow-right-s-line"></i>
						</a>
					<# } #>

					<# if (postPaginationType === "load_more") { #>
						<a href="#" data-paged="2" class="xtpb-posts-addon-loadmore-btn">
							加载更多
						</a>
					<# } #>

				</div>
			<# } #>

		</div>
	<# } #>
	';

	return $output;
}

}
