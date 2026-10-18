<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('XTPB_Frontend')){

	class XTPB_Frontend{

		/**
		 * XTPB_Frontend constructor.
		 */
		public function __construct() {
			add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));

			//enqueue scripts
			add_action('wp_enqueue_scripts', array($this, 'enqueue_common_scripts'));
			//enqueue style
			add_action('wp_enqueue_scripts', array($this, 'enqueue_common_style'), 1);

			//Load Addon Specific scripts
			add_action('wp_enqueue_scripts', array($this, 'enqueue_addon_handler_scripts'), 30);
			add_action('wp_enqueue_scripts', array($this, 'enqueue_page_bundle_script'), 35);
			//Load Addon Specific style
			add_action('wp_enqueue_scripts', array($this, 'enqueue_addon_handler_style'), 30);

			//Modify the_content with page builder data
			add_filter('the_content', array($this, 'filter_content_with_pagebuilder'));

			if ( ! xtpb_helper()->is_load_editor_iframe()){
				add_filter( 'post_class', array($this, 'xtpb_container_post_class') );
				add_filter( 'body_class', array($this, 'xtpb_container_body_class') );
			}

			/**
			 * Add a menu link in top admin bar
			 */
			if (xtpb_helper()->can_edit_editor()){
				add_action('admin_bar_menu', array($this, 'add_toolbar_items'), 100);
			}
		}

		public function enqueue_frontend_scripts(){
			do_action('xtpb_pre_load_frontend_scripts');
			//Frontend Scripts load goes here

			//End Frontend
			do_action('xtpb_post_load_frontend_scripts');
		}

		/**
		 * Load Scripts in frontend
		 */
		public function enqueue_common_scripts(){
			// Register Frontend
			wp_register_script( 'jquery.magnific-popup' , XTPB_DIR_URL.'assets/js/jquery.magnific-popup.min.js', array('jquery'),false, true );
			wp_register_script( 'xtpb-slick-slider' , XTPB_DIR_URL.'assets/js/slick/slick.min.js', array('jquery'),'1.8.0', true);
			wp_register_script( 'jquery.easypiechart' , XTPB_DIR_URL.'assets/js/jquery.easypiechart.min.js', array('jquery'),false, true);
			wp_register_script( 'jquery.inview' , XTPB_DIR_URL.'assets/js/jquery.inview.min.js',array(),false,true );
			// Enqueue Frontend
			wp_enqueue_script( 'wppagebuilder-main',XTPB_DIR_URL.'assets/js/main.js',array('jquery'),false,true );
		}

		/**
		 * Load in frontend View
		 */
		public function enqueue_common_style(){
			$post_id = $this->get_runtime_builder_post_id();

			wp_register_style( 'magnific-popup', XTPB_DIR_URL . 'assets/css/magnific-popup.css',false,'all');
			wp_register_style('xtpb-slick-slider-css', XTPB_DIR_URL.'assets/js/slick/slick.css', array(),'1.8.0' );
			wp_register_style('xtpb-slick-slider-css-theme', XTPB_DIR_URL.'assets/js/slick/slick-theme.css', array(),'1.8.0' );
			
			wp_enqueue_style( 'animate', XTPB_DIR_URL . 'assets/css/animate.min.css',false,'all');
			wp_enqueue_style( 'remixicon', XTPB_DIR_URL . 'assets/css/remixicon.css', false, XTPB_VERSION );

			wp_enqueue_style( 'xtpb-fonts', XTPB_DIR_URL . 'assets/css/xtpb-fonts.css',false,'all');
			//wp_enqueue_style( 'magnific-popup', XTPB_DIR_URL . 'assets/css/magnific-popup.css',false,'all');
			wp_enqueue_style( 'xtpb-main', XTPB_DIR_URL . 'assets/css/xtpb-main.css',array(),'all');

			/**
			 * Load page specific css from file or inline style
			 */
			if ( $post_id ) {
				$css_save_as = xtpb_get_option( 'css_save_as' );
				if ( $css_save_as === 'filesystem' ) {
					$css_path = $this->ensure_page_css_file( $post_id );
					if ( file_exists( $css_path ) ) {
						$upload_dir  = wp_get_upload_dir();
						$css_dir_url = trailingslashit( $upload_dir['baseurl'] );
						$css_url     = $css_dir_url . "xt-pagebuilder/xtpb-page-css-{$post_id}.css";
						if ( ! xtpb_helper()->is_editor_screen() ) {
							wp_enqueue_style( "xtpb-page-{$post_id}", $css_url, array(), filemtime( $css_path ), 'all' );
						}
					}
				}else{
					$getCssFromMeta = $this->get_page_css( $post_id );
					wp_add_inline_style('xtpb-main', $getCssFromMeta);
				}
			}

		}

		private function get_page_css( $post_id ) {
			$meta_css     = (string) get_post_meta( $post_id, '_xtpb_page_css', true );
			$page_content = get_post_meta( $post_id, '_xtpb_content', true );
			$addon_css    = xtpb_helper()->get_addon_page_css_from_content( $page_content );
			$target_css   = $meta_css;

			if ( $addon_css && ! xtpb_helper()->has_generated_addon_css( $target_css ) ) {
				$target_css = $addon_css . $target_css;
			}

			return xtpb_rebuild_google_fonts_css( $target_css, $page_content );
		}

		private function ensure_page_css_file( $post_id ) {
			$upload_dir     = wp_get_upload_dir();
			$upload_css_dir = trailingslashit( $upload_dir['basedir'] );
			$css_path       = $upload_css_dir . "xt-pagebuilder/xtpb-page-css-{$post_id}.css";
			$current_css    = file_exists( $css_path ) ? file_get_contents( $css_path ) : '';
			$target_css     = $this->get_page_css( $post_id );

			if ( '' === $target_css ) {
				return $css_path;
			}

			if ( file_exists( $css_path ) && $current_css === $target_css ) {
				return $css_path;
			}

			$this->write_page_asset_file( $upload_css_dir, $css_path, $target_css );
			return $css_path;
		}

		private function get_page_js_path( $post_id ) {
			$upload_dir      = wp_get_upload_dir();
			$upload_asset_dir = trailingslashit( $upload_dir['basedir'] );

			return $upload_asset_dir . "xt-pagebuilder/xtpb-page-js-{$post_id}.js";
		}

		private function ensure_page_js_file( $post_id ) {
			$js_path    = $this->get_page_js_path( $post_id );
			$current_js = file_exists( $js_path ) ? file_get_contents( $js_path ) : '';
			$target_js  = (string) get_post_meta( $post_id, '_xtpb_page_js', true );

			if ( '' === $target_js ) {
				$this->delete_page_asset_file( $js_path );
				return $js_path;
			}

			if ( file_exists( $js_path ) && $current_js === $target_js ) {
				return $js_path;
			}

			$upload_dir = wp_get_upload_dir();
			$this->write_page_asset_file( trailingslashit( $upload_dir['basedir'] ), $js_path, $target_js );

			return $js_path;
		}

		private function write_page_asset_file( $upload_css_dir, $asset_path, $content ) {
			global $wp_filesystem;

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem( false, $upload_css_dir, true );
			}

			$dir = $upload_css_dir . 'xt-pagebuilder/';
			if ( ! $wp_filesystem || ! is_object( $wp_filesystem ) ) {
				return;
			}

			if ( ! $wp_filesystem->is_dir( $dir ) ) {
				$wp_filesystem->mkdir( $dir );
			}

			$wp_filesystem->put_contents( $asset_path, $content );
		}

		private function delete_page_asset_file( $asset_path ) {
			global $wp_filesystem;

			if ( ! file_exists( $asset_path ) ) {
				return true;
			}

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem( false, dirname( $asset_path ), true );
			}

			if ( ! $wp_filesystem || ! is_object( $wp_filesystem ) ) {
				return false;
			}

			return $wp_filesystem->delete( $asset_path );
		}

		public function used_addons_in_page(){
			$post_id = $this->get_runtime_builder_post_id();
			if ( ! $post_id ) {
				return array();
			}

			return xtpb_helper()->get_used_addon_names_from_content( get_post_meta( $post_id, '_xtpb_content', true ) );
		}
		/**
		 * Enquee scripts which called in addon and registered to WordPress
		 * @since v.1.0.0
		 */
		public function enqueue_addon_handler_scripts(){
			$post_id = $this->get_runtime_builder_post_id();
			if ( ! $post_id )
				return;
			$page_content      = get_post_meta( $post_id, '_xtpb_content', true );
			$used_addons_names = $this->used_addons_in_page();
			$merged_handles    = array_flip( xtpb_get_merged_addon_script_handles() );

			//Checking used addons if used any script or style from Enqueue
			$get_addon_classes = xtpb_helper()->get_addon_instances();

			$registered_scripts = array();
			if ( ! empty($get_addon_classes) && count($get_addon_classes)){
				foreach ($get_addon_classes as $class_instance){
					if (in_array($class_instance->get_name(), $used_addons_names)){
						if (method_exists($class_instance, 'get_enqueue_script')){
							$enqueue_scripts = $class_instance->get_enqueue_script();
							if ( ! empty($enqueue_scripts) && count($enqueue_scripts)){
								foreach ($enqueue_scripts as $script){
									if ( isset( $merged_handles[ $script ] ) ) {
										continue;
									}
									$registered_scripts[$script] = $script;
								}
							}
						}
					}
				}
			}

			foreach ( xtpb_get_page_addon_script_dependency_handles_from_content( $page_content ) as $script ) {
				$registered_scripts[ $script ] = $script;
			}

			//Enqueue script used by addons
			if ( ! empty($registered_scripts) && count($registered_scripts)){
				foreach ($registered_scripts as $script){
					if (wp_script_is($script,'registered')){
						wp_enqueue_script($script);
					}
				}
			}
		}

		public function enqueue_page_bundle_script() {
			$post_id = $this->get_runtime_builder_post_id();
			if ( ! $post_id ) {
				return;
			}

			$page_content = get_post_meta( $post_id, '_xtpb_content', true );
			$page_js      = xtpb_build_page_addon_js( $page_content );
			$saved_page_js = (string) get_post_meta( $post_id, '_xtpb_page_js', true );

			if ( $saved_page_js !== $page_js ) {
				update_post_meta( $post_id, '_xtpb_page_js', wp_slash( $page_js ) );
			}

			if ( '' === $page_js ) {
				return;
			}

			$deps         = array_unique( array_merge( array( 'wppagebuilder-main' ), xtpb_get_page_addon_script_dependency_handles_from_content( $page_content ) ) );
			$handle       = "xtpb-page-js-{$post_id}";
			$save_as      = xtpb_get_page_script_save_as();

			if ( 'filesystem' === $save_as ) {
				$js_path = $this->ensure_page_js_file( $post_id );
				if ( file_exists( $js_path ) ) {
					$upload_dir = wp_get_upload_dir();
					$js_url     = trailingslashit( $upload_dir['baseurl'] ) . "xt-pagebuilder/xtpb-page-js-{$post_id}.js";
					wp_enqueue_script( $handle, $js_url, $deps, filemtime( $js_path ), true );
				}

				return;
			}

			wp_register_script( $handle, false, $deps, false, true );
			wp_enqueue_script( $handle );
			wp_add_inline_script( $handle, $page_js );
		}

		/**
		 * Enquee style from registered Style
		 * @since v.1.0.0
		 */
		public function enqueue_addon_handler_style(){
			if ( ! $this->get_runtime_builder_post_id())
				return;

			$used_addons_names = $this->used_addons_in_page();
			$get_addon_classes = xtpb_helper()->get_addon_instances();

			$registered_scripts = array();
			if ( ! empty($get_addon_classes) && count($get_addon_classes)){
				foreach ($get_addon_classes as $class_instance){
					if (in_array($class_instance->get_name(), $used_addons_names)) {
						if ( method_exists( $class_instance, 'get_enqueue_style' ) ) {
							$enqueue_scripts = $class_instance->get_enqueue_style();
							if ( ! empty( $enqueue_scripts ) && count( $enqueue_scripts ) ) {
								foreach ( $enqueue_scripts as $script ) {
									$registered_scripts[ $script ] = $script;
								}
							}
						}
					}
				}
			}

			if ( ! empty($registered_scripts) && count($registered_scripts)){
				foreach ($registered_scripts as $script){
					if (wp_style_is($script,'registered')){
						wp_enqueue_style($script);
					}
				}
			}
		}


		/**
		 * Filter the_content with WP PageBuilder Content
		 */

		function filter_content_with_pagebuilder($content){
			// A module can legitimately filter its own rich text while the layout is
			// being generated. Never treat that nested content as the page itself.
			if ( xtpb_is_rendering_builder_content() ) {
				return $content;
			}

			$page_id = get_the_ID();
			$is_editor_iframe = xtpb_helper()->is_load_editor_iframe();
			$page_builder_content = get_post_meta($page_id, '_xtpb_content', true);

			/**
			 * In the builder editor iframe, always render stored builder content when it exists.
			 * On the public/frontend side, only replace the content when builder rendering is active.
			 */
			if ( ! empty($page_builder_content) && ( $is_editor_iframe || xtpb_helper()->is_builder_activated( $page_id ) ) ) {
				xtpb_is_rendering_builder_content( true );

				try {
					$settings = xtpb_normalize_builder_content_icons( json_decode( $page_builder_content, true ) );
					include XTPB_DIR_PATH.'classes/Layout_Generator.php';
					ob_start();
					$generate = new XTPB_Layout_Generator;
					echo $generate->generate($settings);
					$content = ob_get_clean();
				} finally {
					xtpb_is_rendering_builder_content( false );
				}

				return $content;
			}
			

			return $content;
		}

		private function get_runtime_builder_post_id() {
			$post_id = xtpb_helper()->is_xtpb_single();
			if ( $post_id ) {
				return $post_id;
			}

			if ( xtpb_helper()->is_load_editor_iframe() ) {
				$post_id = get_the_ID();
				if ( $post_id && xtpb_helper()->has_builder_content( $post_id ) ) {
					return $post_id;
				}
			}

			return 0;
		}

		/**
		 * @param $classes
		 *
		 * @return array
		 *
		 * Add post classes when the current singular page is rendered by the builder.
		 */
		function xtpb_container_post_class( $classes ) {
			global $post;

			if ( ! xtpb_helper()->is_xtpb_single()){
				return $classes;
			}

			$classes[] = "type-xtpb";
			$classes[] = "xtpb-{$post->post_type}";
			$classes[] = "xtpb-{$post->post_type}-{$post->ID}";
			return $classes;
		}

		/**
		 * @param $classes
		 *
		 * @return array
		 *
		 * Add body classes when the current singular page is rendered by the builder.
		 */
		public function xtpb_container_body_class( $classes ){
			global $post;

			if ( ! xtpb_helper()->is_xtpb_single()){
				return $classes;
			}

			$classes[] = "xtpb-body";
			$classes[] = "xtpb-body-{$post->post_type}";
			$classes[] = "xtpb-body-{$post->post_type}-{$post->ID}";
			$classes[] = "xtpb-body-single-{$post->ID}";
			return $classes;
		}


		/**
		 * @param $admin_bar
		 *
		 * Add a edit with page builder link in admin menu
		 */

		function add_toolbar_items($admin_bar){
			if ( current_user_can( 'manage_options' ) ) {
				$admin_bar->add_menu(
					array(
						'id'    => 'xtpb-admin-bar',
						'title' => __( '页面构建器', 'xintheme-pagebuilder' ),
						'href'  => admin_url( 'admin.php?page=xtpb-settings' ),
						'meta'  => array(
							'title' => __( '页面构建器', 'xintheme-pagebuilder' ),
						),
					)
				);
				$admin_bar->add_menu(
					array(
						'parent' => 'xtpb-admin-bar',
						'id'     => 'xtpb-rebuild-page-assets',
						'title'  => __( '重新生成缓存', 'xintheme-pagebuilder' ),
						'href'   => '#',
						'meta'   => array(
							'title' => __( '重新生成所有页面构建器页面的 CSS 和 JS。', 'xintheme-pagebuilder' ),
							'class' => 'xtpb-rebuild-page-assets-toolbar',
						),
					)
				);
			}

			if (is_singular()){
				global $post;

				$xtpb_supports_post_types = xtpb_helper()->xtpb_supports_post_types();
				if ( in_array($post->post_type, $xtpb_supports_post_types)) {
					$link = xtpb_helper()->get_editor_url( get_the_ID() );
					$admin_bar->add_menu(
						array(
							'id'    => 'edit-with-xtpb',
							'title' => __( '使用页面构建器编辑', 'xintheme-pagebuilder' ),
							'href'  => $link,
							'meta'  => array(
								'title' => __( '使用页面构建器编辑', 'xintheme-pagebuilder' ),
							),
						)
					);
				}
			}
		}




	}
}
