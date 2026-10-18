<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('XTPB_Assets')) {
	class XTPB_Assets {

		private $_post_id;

		public function __construct() {
			if( xtpb_helper()->is_editor_screen() ) {
				$this->_post_id = absint( $_REQUEST['post'] );
			}

			if (xtpb_helper()->is_editor_screen() || xtpb_helper()->is_load_editor_iframe()){
				// 仅在编辑器页面加载
				add_action('xtpb_enqueue_scripts', array($this, 'xtpb_enqueue_scripts'));
				add_action('xtpb_enqueue_scripts', array($this, 'xtpb_enqueue_gblobal_scripts'));
				add_action('xtpb_enqueue_scripts', array($this, 'prune_editor_admin_scripts'), 9999999);
				add_action('xtpb_enqueue_styles', array($this, 'xtpb_enqueue_styles'));
				add_action('wp_print_scripts', array($this, 'force_prune_editor_core_scripts'), 1);
				add_action('wp_print_footer_scripts', array($this, 'force_prune_editor_core_scripts'), 1);
				add_action('wp_print_styles', array($this, 'force_prune_editor_core_styles'), 1);

				add_action('wp_enqueue_scripts', array($this, 'xtpb_enqueue_gblobal_scripts'));
				add_action('wp_enqueue_scripts', array($this, 'xtpb_enqueue_styles'));

				do_action('xtpb_after_enqueue_in_editor');
			}

			// Load backend script
			add_action('admin_enqueue_scripts', array($this, 'xtpb_backend_script_loader'));
			add_action('wp_ajax_xtpb_stage_editor_switch', array($this, 'xtpb_stage_editor_switch_callback'));
		}

		/**
		 * enqueue scripts to the pagebuilder section
		 */
		public function xtpb_enqueue_scripts(){
			global $wp_styles, $wp_scripts;

			if( xtpb_helper()->is_editor_screen() ){

				// Reset global variable
				$wp_scripts = new \WP_Scripts();


				$is_script_debug = xtpb_helper()->is_script_debug();
				$suffix = $is_script_debug ? '' : '.min';
				wp_enqueue_editor();
				wp_enqueue_media();

				wp_enqueue_script( 'xtpb-lodash',XTPB_DIR_URL.'assets/js/lodash.min.js',array(),false,true );
				wp_add_inline_script( 'xtpb-lodash', 'window.__ = _.noConflict();' );
				wp_enqueue_script( 'xtpb-html2canvas', XTPB_DIR_URL . 'assets/js/vendor/html2canvas.min.js', array(), '1.4.1', true );

				wp_enqueue_script( 'engine',XTPB_DIR_URL.'assets/js/engine.js',
					array(
						'jquery',
						'jquery-ui-resizable',
						'wp-auth-check',
						'heartbeat',
						'xtpb-html2canvas',
					), false,true );


				$initial_state = xtpb_normalize_builder_content_icons( json_decode( get_post_meta( $this->_post_id,'_xtpb_content', true ) ) );
				if( empty($initial_state) ){
					$initial_state = array();
				}
				$addons_data = new XTPB_Base_Data();


				$dashboard_page = '#';
				$view_page = '#';
				if (xtpb_helper()->is_editor_screen()){
					$editor_id = xtpb_helper()->get_the_editor_post_id();
					$dashboard_page = admin_url("post.php?post={$editor_id}&action=edit");
					$view_page = get_permalink($editor_id);
				}

				$js_translate = array(
					'add_row' => __('添加行','xintheme-pagebuilder'), 'layouts' => __('布局','xintheme-pagebuilder'), 'default_addons' => __('默认组件','xintheme-pagebuilder'), 
					'modules' => __('模块','xintheme-pagebuilder'), 'add_column' => __('添加列','xintheme-pagebuilder'), 'duplicate_column' => __('复制列','xintheme-pagebuilder'), 
					'disable_column' => __('禁用列','xintheme-pagebuilder'), 'delete_column' => __('删除列','xintheme-pagebuilder'), 'add_inner_row' => __('添加内层行','xintheme-pagebuilder'), 
					'drag_column' => __('拖动列','xintheme-pagebuilder'), 'drag_inner_row' => __('拖动内层行','xintheme-pagebuilder'), 'addons' => __('组件','xintheme-pagebuilder'), 
					'blocks' => __('区块','xintheme-pagebuilder'), 'library' => __('收藏','xintheme-pagebuilder'), 'tools' => __('工具','xintheme-pagebuilder'), 'import' => __('导入','xintheme-pagebuilder'), 
					'export' => __('导出','xintheme-pagebuilder'), 'clear_page_content' => __('清空页面内容','xintheme-pagebuilder'), 'view' => __('查看','xintheme-pagebuilder'), 
					'save' => __('保存','xintheme-pagebuilder'), 'dashboard' => __('仪表盘','xintheme-pagebuilder'), 'edit_page' => __('编辑页面','xintheme-pagebuilder'), 
					'view_page' => __('查看页面','xintheme-pagebuilder'), 'undo' => __('撤销','xintheme-pagebuilder'), 'redo' => __('重做','xintheme-pagebuilder'), 
					'duplicate_row' => __('复制行','xintheme-pagebuilder'), 'save_row' => __('添加到收藏','xintheme-pagebuilder'), 'disable_row' => __('禁用行','xintheme-pagebuilder'), 
					'move_up' => __('上移','xintheme-pagebuilder'), 'move_down' => __('下移','xintheme-pagebuilder'), 'delete_row' => __('删除行','xintheme-pagebuilder'), 
					'preview' => __('预览','xintheme-pagebuilder'), 'save_to_library' => __('添加到收藏','xintheme-pagebuilder'), 
					'discard' => __('取消','xintheme-pagebuilder'), 'title_of_the_custom_section' => __('自定义区块标题','xintheme-pagebuilder'), 'pages' => __('页面','xintheme-pagebuilder'), 
					'categories' => __('分类','xintheme-pagebuilder'), 'search' => __('搜索','xintheme-pagebuilder'), 'apply' => __('应用','xintheme-pagebuilder'), 'add_item' => __('添加项目','xintheme-pagebuilder'), 
					'cancel' => __('取消','xintheme-pagebuilder'), 'name' => __('名称','xintheme-pagebuilder'), 'delay' => __('延迟','xintheme-pagebuilder'), 'duration' => __('持续时间','xintheme-pagebuilder'), 
					'background' => __('背景','xintheme-pagebuilder'), 'hover' => __('悬停','xintheme-pagebuilder'), 'color' => __('颜色','xintheme-pagebuilder'), 'image' => __('图片','xintheme-pagebuilder'), 
					'gradient' => __('渐变','xintheme-pagebuilder'), 'video' => __('视频','xintheme-pagebuilder'), 'position' => __('位置','xintheme-pagebuilder'), 
					'attachment' => __('附件','xintheme-pagebuilder'), 'repeat' => __('重复','xintheme-pagebuilder'), 'size' => __('尺寸','xintheme-pagebuilder'), 'url' => __('URL','xintheme-pagebuilder'), 
					'fallback_image' => __('回退图片','xintheme-pagebuilder'), 'upload_video' => __('上传视频','xintheme-pagebuilder'), 'type' => __('类型','xintheme-pagebuilder'), 
					'background_color' => __('背景颜色','xintheme-pagebuilder'), 'style' => __('样式','xintheme-pagebuilder'), 'horizontal' => __('水平','xintheme-pagebuilder'), 
					'vertical' => __('垂直','xintheme-pagebuilder'), 'blur' => __('模糊','xintheme-pagebuilder'), 'spread' => __('扩散','xintheme-pagebuilder'), 'top' => __('顶部','xintheme-pagebuilder'), 'right' => __('右侧','xintheme-pagebuilder'), 
					'bottom' => __('底部','xintheme-pagebuilder'), 'left' => __('左侧','xintheme-pagebuilder'), 'gradient_type' => __('渐变类型','xintheme-pagebuilder'), 
					'gradient_direction' => __('渐变方向','xintheme-pagebuilder'), 'start_position' => __('起始位置','xintheme-pagebuilder'), 'end_position' => __('结束位置','xintheme-pagebuilder'), 
					'all' => __('全部','xintheme-pagebuilder'), 'xtpb_icon' => __('默认图标','xintheme-pagebuilder'), 'remixIcon' => __('Remix 图标','xintheme-pagebuilder'), 
					'open_in_new_window' => __('在新窗口打开','xintheme-pagebuilder'), 'nofollow' => __('nofollow','xintheme-pagebuilder'), 'delete' => __('删除','xintheme-pagebuilder'), 
					'width' => __('宽度','xintheme-pagebuilder'), 'height' => __('高度','xintheme-pagebuilder'), 'flip' => __('翻转','xintheme-pagebuilder'), 'invert' => __('反相','xintheme-pagebuilder'), 
					'bring_to_front' => __('置于顶层','xintheme-pagebuilder'), 'font_family' => __('字体族','xintheme-pagebuilder'), 'font_size' => __('字体大小','xintheme-pagebuilder'), 
					'line_height' => __('行高','xintheme-pagebuilder'), 'letter_spacing' => __('字间距','xintheme-pagebuilder'), 'font_style' => __('字体样式','xintheme-pagebuilder'), 
					'font_weight' => __('字重','xintheme-pagebuilder'),'text_decoration' => __('文本装饰','xintheme-pagebuilder'), 'text_transform' => __('文本转换','xintheme-pagebuilder'),'disable' => __('禁用','xintheme-pagebuilder'),'enable' => __('启用','xintheme-pagebuilder'), 
					'brightness' => __('亮度','xintheme-pagebuilder'), 'contrast' => __('对比度','xintheme-pagebuilder'), 'grayscale' => __('灰度','xintheme-pagebuilder'), 'invert' => __('反相','xintheme-pagebuilder'), 'hue_rotate' => __('色相旋转','xintheme-pagebuilder'), 'saturate' => __('饱和度','xintheme-pagebuilder'), 'sepia' => __('棕褐色','xintheme-pagebuilder'),       
				);

				$page_data = apply_filters('xtpb_page_data',
					array(
						'ajaxurl'           => admin_url( 'admin-ajax.php' ),
						'xtpb_version'      => XTPB_VERSION,
						'initialState'      => $initial_state,
						'pagebuilder_base'  => XTPB_DIR_URL,
						'script_debug'      => $is_script_debug ? 1 : 0,
						'content_url'       => content_url(),
						'admin_url'         => admin_url('/'),
						'addonsJSON'        => $addons_data->addons_data()['addons'],
						'modulesJSON'       => $addons_data->modules_data()['modules'],
						'rowSettings'       => $addons_data->row_settings(),
						'colSettings'       => $addons_data->column_settings(),
						'globalAttr'        => $addons_data->global_attr(),
						'dashboard_page'    => $dashboard_page,
						'view_page'         => $view_page,
						'xtpb_wp_editor'    => $this->wp_editor_default_template(),
						'SvgShape'			=> $addons_data->getSvgShapes(),
						'xtpbimagesize'		=> xtpb_getLall_image_sizes_option(),
						'i18n'				=> $js_translate,
						'ajax_nonce'        => wp_create_nonce( 'xtpb-ajax-verification' ),
						'google_fonts_enabled' => xtpb_google_fonts_enabled() ? 1 : 0,
						'google_fonts_local'   => xtpb_google_fonts_local_enabled() ? 1 : 0,
						'initial_google_font_families' => xtpb_get_google_font_families_from_content(
							get_post_meta( $this->_post_id, '_xtpb_content', true )
						),
					)
				);
				
				wp_localize_script( 'engine', 'page_data', $page_data );
			}
		}

		public function xtpb_enqueue_gblobal_scripts(){
			// Register JS for Addons
			wp_register_script( 'jquery.magnific-popup',XTPB_DIR_URL.'assets/js/jquery.magnific-popup.min.js',array(),false,true );
			wp_register_script( 'jquery.easypiechart',XTPB_DIR_URL.'assets/js/jquery.easypiechart.min.js',array(),false,true );
			wp_register_script( 'jquery.inview',XTPB_DIR_URL.'assets/js/jquery.inview.min.js',array(),false,true );
			wp_register_script( 'xtpb-slick-slider', XTPB_DIR_URL.'assets/js/slick/slick.min.js', array(),'1.8.0', true);
			wp_register_script( 'wppagebuilder-main',XTPB_DIR_URL.'assets/js/main.js',array('jquery'),false,true );

			if ( xtpb_helper()->is_load_editor_iframe() ) {
				wp_enqueue_script( 'jquery.magnific-popup' );
				wp_enqueue_script( 'jquery.easypiechart' );
				wp_enqueue_script( 'jquery.inview' );
				wp_enqueue_script( 'xtpb-slick-slider' );
				wp_enqueue_script( 'wppagebuilder-main' );
				$this->enqueue_editor_addon_bundle_script( true );
			} else {
				$this->enqueue_editor_addon_bundle_script( false );
			}

			do_action('xtpb_enqueue_scripts_in_editor');
		}

		public function prune_editor_admin_scripts() {
			if ( ! xtpb_helper()->is_editor_screen() ) {
				return;
			}

			global $wp_scripts;

			if ( ! $wp_scripts instanceof \WP_Scripts ) {
				return;
			}

			$root_handles = apply_filters(
				'xtpb_editor_required_script_roots',
				array(
					'engine',
					'xtpb-lodash',
					'xtpb-html2canvas',
					'xtpb-editor-addon-bundle',
					'jquery.magnific-popup',
					'jquery.easypiechart',
					'jquery.inview',
					'xtpb-slick-slider',
					'editor',
					'media-editor',
					'media-views',
					'media-audiovideo',
					'wp-mediaelement',
					'wp-auth-check',
					'heartbeat',
				)
			);

			$protected_handles = array();
			foreach ( $root_handles as $handle ) {
				$protected_handles = array_merge(
					$protected_handles,
					$this->collect_script_dependency_handles( $handle, $wp_scripts )
				);
			}

			$protected_handles = array_unique( array_filter( $protected_handles ) );

			foreach ( (array) $wp_scripts->queue as $handle ) {
				if ( in_array( $handle, $protected_handles, true ) ) {
					continue;
				}

				if ( $this->is_wordpress_core_script_handle( $handle, $wp_scripts ) ) {
					wp_dequeue_script( $handle );
				}
			}

		}

		public function force_prune_editor_core_scripts() {
			if ( ! xtpb_helper()->is_editor_screen() ) {
				return;
			}

			global $wp_scripts;

			if ( ! $wp_scripts instanceof \WP_Scripts ) {
				return;
			}

			$protected_handles = array();
			foreach ( $this->get_editor_required_script_roots() as $handle ) {
				$protected_handles = array_merge(
					$protected_handles,
					$this->collect_script_dependency_handles( $handle, $wp_scripts )
				);
			}

			$protected_handles = array_unique( array_filter( $protected_handles ) );

			foreach ( (array) $wp_scripts->queue as $handle ) {
				if ( in_array( $handle, $protected_handles, true ) ) {
					continue;
				}

				if ( $this->is_wordpress_core_script_handle( $handle, $wp_scripts ) ) {
					wp_dequeue_script( $handle );
					wp_deregister_script( $handle );
				}
			}
		}

		public function force_prune_editor_core_styles() {
			if ( ! xtpb_helper()->is_editor_screen() ) {
				return;
			}

			global $wp_styles;

			if ( ! $wp_styles instanceof \WP_Styles ) {
				return;
			}

			$protected_handles = array();
			foreach ( $this->get_editor_required_style_roots() as $handle ) {
				$protected_handles = array_merge(
					$protected_handles,
					$this->collect_style_dependency_handles( $handle, $wp_styles )
				);
			}

			$protected_handles = array_unique( array_filter( $protected_handles ) );

			foreach ( (array) $wp_styles->queue as $handle ) {
				if ( in_array( $handle, $this->get_editor_excluded_style_handles(), true ) ) {
					wp_dequeue_style( $handle );
					wp_deregister_style( $handle );
					continue;
				}

				if ( in_array( $handle, $protected_handles, true ) ) {
					continue;
				}

				if ( $this->is_wordpress_core_style_handle( $handle, $wp_styles ) ) {
					wp_dequeue_style( $handle );
					wp_deregister_style( $handle );
				}
			}
		}

		private function collect_script_dependency_handles( $handle, $wp_scripts, &$collected = array() ) {
			if ( ! $handle || in_array( $handle, $collected, true ) ) {
				return $collected;
			}

			$collected[] = $handle;

			if ( empty( $wp_scripts->registered[ $handle ] ) ) {
				return $collected;
			}

			$deps = ! empty( $wp_scripts->registered[ $handle ]->deps ) ? (array) $wp_scripts->registered[ $handle ]->deps : array();
			foreach ( $deps as $dep ) {
				$this->collect_script_dependency_handles( $dep, $wp_scripts, $collected );
			}

			return $collected;
		}

		private function collect_style_dependency_handles( $handle, $wp_styles, &$collected = array() ) {
			if ( ! $handle || in_array( $handle, $collected, true ) ) {
				return $collected;
			}

			$collected[] = $handle;

			if ( empty( $wp_styles->registered[ $handle ] ) ) {
				return $collected;
			}

			$deps = ! empty( $wp_styles->registered[ $handle ]->deps ) ? (array) $wp_styles->registered[ $handle ]->deps : array();
			foreach ( $deps as $dep ) {
				$this->collect_style_dependency_handles( $dep, $wp_styles, $collected );
			}

			return $collected;
		}

		private function is_wordpress_core_script_handle( $handle, $wp_scripts ) {
			if ( empty( $wp_scripts->registered[ $handle ] ) ) {
				return false;
			}

			$src = (string) $wp_scripts->registered[ $handle ]->src;

			if ( '' === $src ) {
				return false;
			}

			return false !== strpos( $src, 'wp-admin/js/' )
				|| false !== strpos( $src, '/wp-admin/js/' )
				|| false !== strpos( $src, 'wp-includes/js/' )
				|| false !== strpos( $src, '/wp-includes/js/' )
				|| false !== strpos( $src, 'load-scripts.php' );
		}

		private function is_wordpress_core_style_handle( $handle, $wp_styles ) {
			if ( empty( $wp_styles->registered[ $handle ] ) ) {
				return false;
			}

			$src = (string) $wp_styles->registered[ $handle ]->src;

			if ( '' === $src ) {
				return false;
			}

			return false !== strpos( $src, 'wp-admin/css/' )
				|| false !== strpos( $src, '/wp-admin/css/' )
				|| false !== strpos( $src, 'wp-includes/css/' )
				|| false !== strpos( $src, '/wp-includes/css/' )
				|| false !== strpos( $src, 'load-styles.php' );
		}

		private function get_editor_required_script_roots() {
			return apply_filters(
				'xtpb_editor_required_script_roots',
				array(
					'engine',
					'xtpb-lodash',
					'xtpb-html2canvas',
					'xtpb-editor-addon-bundle',
					'jquery.magnific-popup',
					'jquery.easypiechart',
					'jquery.inview',
					'xtpb-slick-slider',
					'editor',
					'media-editor',
					'media-views',
					'media-audiovideo',
					'wp-mediaelement',
					'wp-auth-check',
					'heartbeat',
				)
			);
		}

		private function get_editor_required_style_roots() {
			return apply_filters(
				'xtpb_editor_required_style_roots',
				array(
					'buttons',
					'imgareaselect',
					'mediaelement',
					'wp-mediaelement',
					'media-views',
					'jquery-ui',
					'animate',
					'remixicon',
					'xtpb-fonts',
					'magnific-popup',
					'xtpb-slick',
					'xtpb-slick-theme',
					'dashicons-xtpb',
					'edit-frontend',
					'react-select',
					'xtpb-main',
				)
			);
		}

		private function get_editor_excluded_style_handles() {
			return apply_filters(
				'xtpb_editor_excluded_style_handles',
				array(
					'admin-bar',
					'thickbox',
					'wp-pointer',
					'wp-block-library',
					'wp-block-library-theme',
					'global-styles',
					'wp-components',
					'wp-reset-editor-styles',
					'wp-format-library',
					'code-editor',
				)
			);
		}

		private function enqueue_editor_addon_bundle_script( $include_main_dependency = true ) {
			$bundle_js = xtpb_build_editor_addon_js();
			if ( '' === $bundle_js ) {
				return;
			}

			$deps   = xtpb_get_editor_addon_script_dependency_handles();
			if ( $include_main_dependency ) {
				array_unshift( $deps, 'wppagebuilder-main' );
			}
			$deps   = array_unique( $deps );
			$handle = 'xtpb-editor-addon-bundle';
			$path   = $this->ensure_editor_addon_js_file( $bundle_js );

			if ( $path && file_exists( $path ) ) {
				$upload_dir = wp_get_upload_dir();
				$url        = trailingslashit( $upload_dir['baseurl'] ) . 'xt-pagebuilder/xtpb-editor-addon-bundle.js';
				wp_enqueue_script( $handle, $url, $deps, filemtime( $path ), true );
				return;
			}

			wp_register_script( $handle, false, $deps, false, true );
			wp_enqueue_script( $handle );
			wp_add_inline_script( $handle, $bundle_js );
		}

		private function ensure_editor_addon_js_file( $content ) {
			$upload_dir = wp_get_upload_dir();
			$base_dir   = trailingslashit( $upload_dir['basedir'] );
			$path       = $base_dir . 'xt-pagebuilder/xtpb-editor-addon-bundle.js';
			$current_js = file_exists( $path ) ? file_get_contents( $path ) : '';

			if ( file_exists( $path ) && $current_js === $content ) {
				return $path;
			}

			global $wp_filesystem;

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem( false, $base_dir, true );
			}

			if ( ! $wp_filesystem || ! is_object( $wp_filesystem ) ) {
				return '';
			}

			$dir = $base_dir . 'xt-pagebuilder/';
			if ( ! $wp_filesystem->is_dir( $dir ) ) {
				$wp_filesystem->mkdir( $dir );
			}

			$wp_filesystem->put_contents( $path, $content );

			return $path;
		}

		/**
		 * Adding stylesheet in the pagebuilder section
		 */

		public function xtpb_enqueue_styles(){
			wp_enqueue_style( 'remixicon', XTPB_DIR_URL . 'assets/css/remixicon.css', false, XTPB_VERSION );
			wp_enqueue_style( 'xtpb-fonts', XTPB_DIR_URL . 'assets/css/xtpb-fonts.css', false, 'all' );
			wp_enqueue_style( 'edit-frontend', XTPB_DIR_URL . 'assets/css/edit.css', false, 'all' );

			if ( xtpb_helper()->is_editor_screen() ) {
				wp_enqueue_style( 'react-select', XTPB_DIR_URL . 'assets/css/react-select.css', false, 'all' );
			}

			if ( xtpb_helper()->is_load_editor_iframe() ) {
				wp_enqueue_style( 'jquery-ui', XTPB_DIR_URL . 'assets/css/jquery-ui.css', false, '1.12.1' );
				wp_enqueue_style( 'animate', XTPB_DIR_URL . 'assets/css/animate.min.css', false, 'all' );
				wp_enqueue_style( 'magnific-popup', XTPB_DIR_URL . 'assets/css/magnific-popup.css', false, 'all' );

				//SLick Slider
				wp_enqueue_style( 'xtpb-slick', XTPB_DIR_URL . 'assets/js/slick/slick.css', array(), '1.8.0' );
				wp_enqueue_style( 'xtpb-slick-theme', XTPB_DIR_URL . 'assets/js/slick/slick-theme.css', array(), '1.8.0' );

				wp_enqueue_style( 'xtpb-main', XTPB_DIR_URL . 'assets/css/xtpb-main.css', array( 'wp-auth-check' ), 'all' );
				wp_add_inline_style( 'xtpb-main', xtpb_get_all_addons_base_css() );
			}

			do_action('xtpb_enqueue_style_in_editor');

			if ( xtpb_helper()->is_editor_screen() ) {
				$this->force_prune_editor_core_styles();
			}
		}


		public function wp_editor_default_template(){
			ob_start();

			wp_editor( '{{WP_EDITOR_CONTENT}}', 'xtpb_wp_editor',
				array(
					'editor_class' => 'xtpb-wp-editor',
					'editor_height' => 250,
					'drag_drop_upload' => true
				)
			);
			return ob_get_clean();
		}

		/**
		 * @param $page
		 *
		 *
		 */
		function xtpb_backend_script_loader($page) {
			wp_enqueue_style( 'xtpb-edit-backend',  XTPB_DIR_URL . 'assets/css/xtpb-backend.css',false,'all');
			wp_enqueue_script( 'xtpb-edit-backend-js',XTPB_DIR_URL.'assets/js/xtpb-backend.js',array(),false,true );
			$is_switched_editor = '';
			if ($page === 'post.php' && ! empty($_GET['post'])){
				$page_id = (int) sanitize_text_field($_GET['post']);
				$get_editor_switched = xtpb_helper()->get_current_post_editor( $page_id );
				if ($get_editor_switched){
					$is_switched_editor = $get_editor_switched;
				}
			}
			$ajax_nonce = wp_create_nonce( "xtpb-nonce-string" );


			$supported_post_types    = xtpb_helper()->xtpb_supports_post_types();

			wp_localize_script( 'xtpb-edit-backend-js', 'xtpb_admin_ajax',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'xtpb_nonce'     => $ajax_nonce,
					'current_editor' => $is_switched_editor,
					'supported_post_type'   => $supported_post_types
				)
			);
		}

		function xtpb_stage_editor_switch_callback(){
			check_ajax_referer( 'xtpb-nonce-string', 'xtpb_nonce' );

			$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
			$pending_editor = isset($_POST['pending_editor']) ? sanitize_text_field($_POST['pending_editor']) : '';

			if ( ! xtpb_helper()->can_edit_editor() || ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
				wp_send_json_error( array( 'success' => false ), 403 );
			}

			if ( $post_id && $pending_editor ) {
				xtpb_helper()->set_pending_post_editor( $post_id, $pending_editor );
				wp_send_json_success(array('success' => true));
			}
		}

	}
}
