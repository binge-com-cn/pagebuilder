<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('XTPB_Helper')) {
	class XTPB_Helper {
		private $addon_instances = null;
		private $generated_addon_css_marker = 'XTPB merged addon css';

		public function __construct() {
			if ( defined( 'XTPB_MODE' ) && 'plugin' === XTPB_MODE ) {
				add_filter( 'plugin_action_links_' . XTPB_BASENAME, array($this, 'plugin_action_links_callback') );
				add_filter( 'plugin_row_meta' , array($this, 'plugin_row_meta_callback'), 10, 2 );
			}

			add_filter( 'wp_kses_allowed_html', array( $this, 'custom_wpkses_post_tags' ), 10, 2 );
		}

		/**
		 * @param int $post_id
		 *
		 * @return mixed
		 *
		 * 返回编辑器 URL
		 *
		 * @since v.1.0.0
		 */
		public function get_editor_url($post_id = 0){
			$edit_link = add_query_arg( array( 'post' => $post_id, 'action' => 'xtpb_editor' ), admin_url( 'post.php' ) );
			return apply_filters( 'xtpb_get_editor_url', $edit_link, $post_id );
		}

		public function is_script_debug(){
			return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
		}


		/**
		 * @param $post_id
		 *
		 * Setup post data based on post id
		 *
		 * @since v.1.0.0
		 */
		public function setup_post_data( $post_id ) {
			if ( $post_id === get_the_ID()) {
				return;
			}
			$GLOBALS['post'] = get_post( $post_id );
			setup_postdata( $GLOBALS['post'] );
		}


		/**
		 *
		 * 判断编辑器是否处于打开状态
		 *
		 * @since V.1.0.0
		 * @return bool
		 *
		 * @since v.1.0.0
		 */
		public function is_editor_screen(){
			if ( ! empty($_GET['action']) &&  $_GET['action'] === 'xtpb_editor'){
				return true;
			}
			return false;
		}

		/**
		 * @return bool
		 *
		 * @since v.1.0.0
		 */
		public function is_load_editor_iframe(){
			if ( ! empty($_GET['load_for']) && $_GET['load_for'] === 'xtpb_editor_iframe' ){
				return true;
			}
			return false;
		}

		/**
		 * Determine whether the current singular page is actively rendered by the builder.
		 *
		 * @return bool|int
		 */
		public function is_xtpb_single(){
			$post_id = get_queried_object_id();
			if ( ! $post_id ) {
				$post_id = get_the_ID();
			}
			if ( ! $post_id){
				return false;
			}
			$post = get_post($post_id);
			if ( ! $post ) {
				return false;
			}
			if ( ! is_singular($post->post_type)){
				return false;
			}

			if ( ! $this->is_builder_activated( $post_id ) ) {
				return false;
			}
			return $post_id;
		}

		/**
		 * Get the current rendering/editor source for a post.
		 *
		 * @param int $post_id Post ID.
		 *
		 * @return string
		 */
		public function get_current_post_editor( $post_id = 0 ) {
			$post_id = $post_id ? absint( $post_id ) : get_the_ID();
			if ( ! $post_id ) {
				return '';
			}

			return (string) get_post_meta( $post_id, '_xtpb_current_post_editor', true );
		}

		/**
		 * Determine whether the post currently renders with builder content.
		 *
		 * @param int $post_id Post ID.
		 *
		 * @return bool
		 */
		public function is_builder_activated( $post_id = 0 ) {
			return $this->get_current_post_editor( $post_id ) === 'xtpb_builder_activated';
		}

		/**
		 * Persist the current rendering/editor source for a post.
		 *
		 * @param int    $post_id Post ID.
		 * @param string $editor  Editor mode.
		 *
		 * @return bool|int
		 */
		public function set_current_post_editor( $post_id, $editor ) {
			return update_post_meta( absint( $post_id ), '_xtpb_current_post_editor', sanitize_text_field( $editor ) );
		}

		/**
		 * Remove the persisted current rendering/editor source for a post.
		 *
		 * @param int $post_id Post ID.
		 *
		 * @return bool|int
		 */
		public function clear_current_post_editor( $post_id ) {
			return delete_post_meta( absint( $post_id ), '_xtpb_current_post_editor' );
		}

		/**
		 * Get the pending non-builder editor switch for a post.
		 *
		 * @param int $post_id Post ID.
		 *
		 * @return string
		 */
		public function get_pending_post_editor( $post_id = 0 ) {
			$post_id = $post_id ? absint( $post_id ) : get_the_ID();
			if ( ! $post_id ) {
				return '';
			}

			return (string) get_post_meta( $post_id, '_xtpb_pending_post_editor', true );
		}

		/**
		 * Persist a pending non-builder editor switch for a post.
		 *
		 * @param int    $post_id Post ID.
		 * @param string $editor  Pending editor mode.
		 *
		 * @return bool|int
		 */
		public function set_pending_post_editor( $post_id, $editor ) {
			return update_post_meta( absint( $post_id ), '_xtpb_pending_post_editor', sanitize_text_field( $editor ) );
		}

		/**
		 * Clear the pending non-builder editor switch for a post.
		 *
		 * @param int $post_id Post ID.
		 *
		 * @return bool|int
		 */
		public function clear_pending_post_editor( $post_id ) {
			return delete_post_meta( absint( $post_id ), '_xtpb_pending_post_editor' );
		}

		/**
		 * Determine whether the post has stored builder content data.
		 *
		 * @param int $post_id Post ID.
		 *
		 * @return bool
		 */
		public function has_builder_content( $post_id = 0 ) {
			$post_id = $post_id ? absint( $post_id ) : get_the_ID();
			if ( ! $post_id ) {
				return false;
			}

			$page_builder_content = get_post_meta( $post_id, '_xtpb_content', true );
			return ! empty( $page_builder_content );
		}

		/**
		 * @return mixed
		 *
		 * return options
		 *
		 * @since v.1.0.0
		 */
		public function xtpb_options(){
			return apply_filters('xtpb_options', get_option( 'xtpb_options' ));
		}

		/**
		 * @return mixed
		 *
		 * 获取支持的文章类型
		 * @since v.1.0.0
		 */
		public function xtpb_supports_post_types(){
			$xtpb_option = $this->xtpb_options();
			$support_post_type = array();
			if ( is_array( $xtpb_option['supported_post_type'] ) ){
				if( ! empty($xtpb_option['supported_post_type'] ) ){
					$support_post_type = $xtpb_option['supported_post_type'];
				}
			}
			return apply_filters('xtpb_supports_post_type', $support_post_type);
		}

		/**
		 * @return array
		 *
		 * get exclude roles
		 * @since v.1.0.0
		 */
		public function xtpb_exclude_roles(){
			$exclude = array();
			$xtpb_options = $this->xtpb_options();

			if ( ! empty( $xtpb_options['exclude_role'] ) ) {
				$exclude = $xtpb_options['exclude_role'];
			}

			return $exclude;
		}

		/**
		 * @return bool
		 *
		 * @since v.1.0.0
		 */
		public function can_edit_editor() {
			if ( ! is_user_logged_in() ) {
				return false;
			}
			$user_meta = get_userdata( get_current_user_id() );

			$xtpb_options = $this->xtpb_options();

			$included_users_roles = isset( $xtpb_options['include_role'] ) ? $xtpb_options['include_role'] : array();

			$bool = false;

			if ( ! empty( $included_users_roles ) ) {
				if ( count( array_intersect( $user_meta->roles, $included_users_roles ) ) > 0 ) {
					$bool = true;
				}
			}
			return $bool;
		}

		/**
		 * @return array
		 *
		 * Get All Addon Class Name
		 *
		 * @since v.1.0.0
		 */
		public function get_addon_classes(){
			$addons = array(
				'XTPB_Addon_Accordion',
				'XTPB_Addon_Alert',
				'XTPB_Addon_Animated_Number',
				'XTPB_Addon_Blocknumber',
				'XTPB_Addon_Button',
				'XTPB_Addon_Button_Group',
				'XTPB_Addon_Carousel',
				'XTPB_Addon_Form',
				'XTPB_Addon_Feature_Box',
				'XTPB_Addon_Flip_Box',
				'XTPB_Addon_Heading',
				'XTPB_Addon_Icon',
				'XTPB_Addon_Image',
				'XTPB_Addon_Image_Hover',
				'XTPB_Addon_Person',
				'XTPB_Addon_Person_Carousel',
				'XTPB_Addon_Pie_Progress',
				'XTPB_Addon_Posts_Grid',
				'XTPB_Addon_Pricing_Table',
				'XTPB_Addon_Progress_Bar',
				'XTPB_Addon_Raw_Html',
				'XTPB_Addon_Social_Button',
				'XTPB_Addon_Tab',
				'XTPB_Addon_Testimonial',
				'XTPB_Addon_Testimonial_Carousel',
				'XTPB_Addon_Text_Block',
				'XTPB_Addon_Video',
				'XTPB_Addon_Video_Popup',
			);
			$addons = apply_filters('xtpb_available_addons', $addons);
			$modules = $this->get_module_classes();

			return array_values( array_unique( array_merge( $addons, $modules ) ) );
		}

		/**
		 * @return array
		 *
		 * Get All Module Class Name
		 */
		public function get_module_classes() {
			$modules = array();

			return apply_filters( 'xtpb_available_modules', $modules );
		}

		/**
		 * @return int
		 *
		 * Get the editor ID
		 */
		public function get_the_editor_post_id(){
			$id = 0;
			if ($this->is_editor_screen()){
				$id = get_the_ID();
			}

			return $id;
		}

		public function get_class_by_addon_name($addon_name = null){
			if ( ! $addon_name){
				return false;
			}

			foreach ( $this->get_addon_instances() as $class_instance ) {
				if ( method_exists( $class_instance, 'get_name' ) && $addon_name === $class_instance->get_name() ) {
					return $class_instance;
				}
			}
			return false;
		}

		public function get_addon_instances() {
			if ( null !== $this->addon_instances ) {
				return $this->addon_instances;
			}

			$this->addon_instances = array();
			$get_addon_classes     = $this->get_addon_classes();

			if ( ! empty( $get_addon_classes ) && count( $get_addon_classes ) ) {
				foreach ( $get_addon_classes as $addon_class ) {
					if ( class_exists( $addon_class ) ) {
						$this->addon_instances[ $addon_class ] = new $addon_class;
					}
				}
			}

			return $this->addon_instances;
		}

		public function get_used_addon_names_from_content( $content ) {
			$rows = $this->normalize_builder_content( $content );

			if ( ! is_array( $rows ) || ! count( $rows ) ) {
				return array();
			}

			return array_values( array_unique( $this->collect_used_addons_from_rows( $rows ) ) );
		}

		public function get_addon_page_css_from_content( $content ) {
			return xtpb_build_page_addon_base_css( $content );
		}

		public function has_page_animation_from_content( $content ) {
			$rows = $this->normalize_builder_content( $content );

			if ( ! is_array( $rows ) || ! count( $rows ) ) {
				return false;
			}

			return $this->rows_have_animation( $rows );
		}

		public function has_generated_addon_css( $css ) {
			return is_string( $css ) && false !== strpos( $css, $this->generated_addon_css_marker );
		}

		private function normalize_builder_content( $content ) {
			if ( is_string( $content ) ) {
				$content = json_decode( $content );
			}

			return is_array( $content ) ? $content : array();
		}

		private function collect_used_addons_from_rows( $rows ) {
			$used_addons = array();

			foreach ( $rows as $row ) {
				if ( empty( $row->columns ) || ! is_array( $row->columns ) ) {
					continue;
				}

				foreach ( $row->columns as $column ) {
					if ( empty( $column->addons ) || ! is_array( $column->addons ) ) {
						continue;
					}

					$used_addons = array_merge( $used_addons, $this->collect_used_addons_from_items( $column->addons ) );
				}
			}

			return $used_addons;
		}

		private function rows_have_animation( $rows ) {
			foreach ( $rows as $row ) {
				if ( $this->row_has_animation( $row ) ) {
					return true;
				}
			}

			return false;
		}

		private function row_has_animation( $row ) {
			$row_settings = isset( $row->settings ) ? (array) $row->settings : array();

			if ( ! empty( $row_settings['row_animation'] ) ) {
				$row_animation = (array) $row_settings['row_animation'];
				if ( ! empty( $row_animation['itemOpen'] ) ) {
					return true;
				}
			}

			if ( empty( $row->columns ) || ! is_array( $row->columns ) ) {
				return false;
			}

			foreach ( $row->columns as $column ) {
				if ( $this->column_has_animation( $column ) ) {
					return true;
				}
			}

			return false;
		}

		private function column_has_animation( $column ) {
			$col_settings = isset( $column->settings ) ? (array) $column->settings : array();

			if ( ! empty( $col_settings['col_animation'] ) ) {
				$col_animation = (array) $col_settings['col_animation'];
				if ( ! empty( $col_animation['itemOpen'] ) ) {
					return true;
				}
			}

			if ( empty( $column->addons ) || ! is_array( $column->addons ) ) {
				return false;
			}

			return $this->addons_have_animation( $column->addons );
		}

		private function addons_have_animation( $addons ) {
			foreach ( $addons as $addon ) {
				if ( isset( $addon->type ) && 'inner_row' === $addon->type ) {
					if ( $this->row_has_animation( $addon ) ) {
						return true;
					}
					continue;
				}

				$addon_settings = isset( $addon->settings ) ? (array) $addon->settings : array();
				if ( empty( $addon_settings['addon_animation'] ) ) {
					continue;
				}

				$addon_animation = (array) $addon_settings['addon_animation'];
				if ( ! empty( $addon_animation['itemOpen'] ) ) {
					return true;
				}
			}

			return false;
		}

		private function collect_used_addons_from_items( $addons ) {
			$used_addons = array();

			foreach ( $addons as $addon ) {
				if ( isset( $addon->type ) && 'inner_row' === $addon->type ) {
					$used_addons = array_merge( $used_addons, $this->collect_used_addons_from_rows( array( $addon ) ) );
					continue;
				}

				if ( isset( $addon->name ) ) {
					$used_addons[] = $addon->name;
				}
			}

			return $used_addons;
		}

		public function plugin_action_links_callback( $links ){
			$actionsLinks =  array();
			$actionsLinks['xtpb_settings'] = '<a href="' . admin_url( 'admin.php?page=xtpb-settings' ) . '">'.__('设置', 'xintheme-pagebuilder').'</a>';
			return array_merge( $actionsLinks, $links );
		}

		public function plugin_row_meta_callback( $links , $plugin ){
			if( defined( 'XTPB_MODE' ) && 'plugin' === XTPB_MODE && XTPB_BASENAME === $plugin ){
			$actionsLinks =  array(
				'xtpb_docs' =>  '<a href="https://docs.xintheme.com/xintheme-pagebuilder/" target="_blank">'.__('文档', 'xintheme-pagebuilder').'</a>',
				'xtpb_dev_docs' =>  '<a href="https://github.com/xintheme/XinTheme-PageBuilder" target="_blank">'.__('开发文档', 'xintheme-pagebuilder').'</a>',
				'xtpb_support' =>  '<a href="https://www.xintheme.com/support/" target="_blank">'.__('免费支持', 'xintheme-pagebuilder').'</a>',
			);
				$links = array_merge( $links, $actionsLinks );
			}
			return $links;
		}

		/**
		 * Adding custom tags to allowed html
		 */
		public function custom_wpkses_post_tags( $tags, $context ) {

			if ( $this->has_builder_content( get_the_ID() ) ) {
				$tags['iframe'] = array(
					'src'             => true,
					'height'          => true,
					'width'           => true,
					'frameborder'     => true,
					'allowfullscreen' => true,
				);
			}
		
			return $tags;
		}

		/**
		 * Verify ajax call
		 */
		public static function xtpb_verify_ajax_call() {
			check_ajax_referer( 'xtpb-ajax-verification', 'security' );

			// Check if user has the WP capability to import layout data.
			if ( false === xtpb_helper()->can_edit_editor() ) {
				wp_die( _e( '你的用户角色权限不足，无法访问此功能。', 'xintheme-pagebuilder' ) );
			}
		}
	}
}


/**
 * @param null $option
 *
 * @return null
 *
 * 从辅助函数获取插件配置选项
 */
if ( ! function_exists('xtpb_get_option')){
	function xtpb_get_option($option = null){
		$xtpb_options = get_option('xtpb_options');
		$value = null;
		if ( $xtpb_options ){
			if ( array_key_exists( $option, $xtpb_options ) ){
				$value = $xtpb_options[$option];
			}
		}

		return $value;
	}
}

if ( ! function_exists( 'xtpb_google_fonts_enabled' ) ) {
	function xtpb_google_fonts_enabled() {
		$option = xtpb_get_option( 'google_fonts_enable' );

		if ( null === $option || '' === $option ) {
			return true;
		}

		return in_array( $option, array( '1', 1, true, 'yes', 'on' ), true );
	}
}

if ( ! function_exists( 'xtpb_google_fonts_local_enabled' ) ) {
	function xtpb_google_fonts_local_enabled() {
		if ( ! xtpb_google_fonts_enabled() ) {
			return false;
		}

		$option = xtpb_get_option( 'google_fonts_local' );

		return in_array( $option, array( '1', 1, true, 'yes', 'on' ), true );
	}
}

if ( ! function_exists( 'xtpb_google_fonts_import_pattern' ) ) {
	function xtpb_google_fonts_import_pattern() {
		return '/@import\s+url\((["\'])(https:\/\/fonts\.googleapis\.com\/css[^"\']+)\1\);/i';
	}
}

if ( ! function_exists( 'xtpb_google_fonts_local_import_pattern' ) ) {
	function xtpb_google_fonts_local_import_pattern() {
		return '/@import\s+url\((["\'])([^"\']*\/xt-pagebuilder\/google-fonts\/[^"\']+)\1\);/i';
	}
}

if ( ! function_exists( 'xtpb_google_fonts_upload_dir' ) ) {
	function xtpb_google_fonts_upload_dir() {
		$upload_dir = wp_get_upload_dir();

		return array(
			'dir' => trailingslashit( $upload_dir['basedir'] ) . 'xt-pagebuilder/google-fonts/',
			'url' => trailingslashit( $upload_dir['baseurl'] ) . 'xt-pagebuilder/google-fonts/',
		);
	}
}

if ( ! function_exists( 'xtpb_write_file_contents' ) ) {
	function xtpb_write_file_contents( $file_path, $contents ) {
		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem( false, dirname( $file_path ), true );
		}

		if ( ! $wp_filesystem || ! is_object( $wp_filesystem ) ) {
			return false;
		}

		return $wp_filesystem->put_contents( $file_path, $contents );
	}
}

if ( ! function_exists( 'xtpb_ensure_directory_exists' ) ) {
	function xtpb_ensure_directory_exists( $dir ) {
		if ( is_dir( $dir ) ) {
			return true;
		}

		return wp_mkdir_p( $dir );
	}
}

if ( ! function_exists( 'xtpb_google_fonts_fetch_remote' ) ) {
	function xtpb_google_fonts_fetch_remote( $url ) {
		$response = wp_remote_get(
			$url,
			array(
				'timeout'    => 20,
				'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== (int) $code ) {
			return new WP_Error( 'xtpb_google_font_request_failed', 'Google Fonts request failed.' );
		}

		return wp_remote_retrieve_body( $response );
	}
}

if ( ! function_exists( 'xtpb_google_fonts_local_import' ) ) {
	function xtpb_google_fonts_local_import( $import_rule ) {
		if ( ! preg_match( xtpb_google_fonts_import_pattern(), $import_rule, $matches ) ) {
			return $import_rule;
		}

		$remote_css_url = $matches[2];
		$paths          = xtpb_google_fonts_upload_dir();
		$css_hash       = md5( $remote_css_url );
		$css_filename   = 'google-font-' . $css_hash . '.css';
		$css_file       = $paths['dir'] . $css_filename;
		$css_url        = $paths['url'] . $css_filename;

		if ( file_exists( $css_file ) ) {
			return "@import url('" . esc_url_raw( $css_url ) . "');";
		}

		$remote_css = xtpb_google_fonts_fetch_remote( $remote_css_url );
		if ( is_wp_error( $remote_css ) || ! is_string( $remote_css ) || '' === trim( $remote_css ) ) {
			return $import_rule;
		}

		if ( ! xtpb_ensure_directory_exists( $paths['dir'] ) ) {
			return $import_rule;
		}

		$processed_css = preg_replace_callback(
			'/url\((["\']?)(https:\/\/fonts\.gstatic\.com\/[^"\')]+)\1\)/i',
			function ( $asset_matches ) use ( $paths ) {
				$remote_asset_url = $asset_matches[2];
				$path_info        = wp_parse_url( $remote_asset_url, PHP_URL_PATH );
				$extension        = pathinfo( $path_info, PATHINFO_EXTENSION );
				$extension        = $extension ? $extension : 'woff2';
				$asset_hash       = md5( $remote_asset_url );
				$asset_filename   = 'google-font-' . $asset_hash . '.' . $extension;
				$asset_file       = $paths['dir'] . $asset_filename;
				$asset_url        = $paths['url'] . $asset_filename;

				if ( ! file_exists( $asset_file ) ) {
					$remote_asset = xtpb_google_fonts_fetch_remote( $remote_asset_url );
					if ( is_wp_error( $remote_asset ) || ! is_string( $remote_asset ) || '' === $remote_asset ) {
						return $asset_matches[0];
					}

					if ( ! xtpb_write_file_contents( $asset_file, $remote_asset ) ) {
						return $asset_matches[0];
					}
				}

				return "url('" . esc_url_raw( $asset_url ) . "')";
			},
			$remote_css
		);

		if ( ! xtpb_write_file_contents( $css_file, $processed_css ) ) {
			return $import_rule;
		}

		return "@import url('" . esc_url_raw( $css_url ) . "');";
	}
}

if ( ! function_exists( 'xtpb_process_google_fonts_css' ) ) {
	function xtpb_process_google_fonts_css( $css = '' ) {
		if ( ! is_string( $css ) || '' === $css ) {
			return $css;
		}

		$imports = array();
		$css_without_imports = $css;

		if ( preg_match_all( xtpb_google_fonts_import_pattern(), $css_without_imports, $google_matches ) ) {
			$imports = array_merge( $imports, $google_matches[0] );
			$css_without_imports = str_replace( $google_matches[0], '', $css_without_imports );
		}

		if ( preg_match_all( xtpb_google_fonts_local_import_pattern(), $css_without_imports, $local_matches ) ) {
			$imports = array_merge( $imports, $local_matches[0] );
			$css_without_imports = str_replace( $local_matches[0], '', $css_without_imports );
		}

		if ( empty( $imports ) ) {
			return $css;
		}

		if ( ! xtpb_google_fonts_enabled() ) {
			return $css_without_imports;
		}

		$imports = array_unique( $imports );

		if ( xtpb_google_fonts_local_enabled() ) {
			$imports = array_map(
				function( $import_rule ) {
					if ( preg_match( xtpb_google_fonts_import_pattern(), $import_rule ) ) {
						return xtpb_google_fonts_local_import( $import_rule );
					}
					return $import_rule;
				},
				$imports
			);
		}

		$imports = array_filter( array_unique( $imports ) );

		return implode( '', $imports ) . $css_without_imports;
	}
}

if ( ! function_exists( 'xtpb_google_fonts_system_families' ) ) {
	function xtpb_google_fonts_system_families() {
		return array(
			'Arial',
			'Tahoma',
			'Verdana',
			'Helvetica',
			'Times New Roman',
			'Trebuchet MS',
			'Georgia',
			'PingFang SC',
			'Hiragino Sans GB',
			'Microsoft YaHei',
			'SimHei',
			'SimSun',
			'KaiTi',
			'FangSong',
		);
	}
}

if ( ! function_exists( 'xtpb_strip_google_fonts_imports' ) ) {
	function xtpb_strip_google_fonts_imports( $css = '' ) {
		if ( ! is_string( $css ) || '' === $css ) {
			return $css;
		}

		$css = preg_replace( xtpb_google_fonts_import_pattern(), '', $css );
		$css = preg_replace( xtpb_google_fonts_local_import_pattern(), '', $css );

		return $css;
	}
}

if ( ! function_exists( 'xtpb_collect_font_families_from_value' ) ) {
	function xtpb_collect_font_families_from_value( $value, &$families ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $key => $item ) {
				if ( 'fontFamily' === $key && is_string( $item ) && '' !== trim( $item ) ) {
					$families[] = trim( $item );
					continue;
				}

				xtpb_collect_font_families_from_value( $item, $families );
			}
		} elseif ( is_object( $value ) ) {
			foreach ( get_object_vars( $value ) as $key => $item ) {
				if ( 'fontFamily' === $key && is_string( $item ) && '' !== trim( $item ) ) {
					$families[] = trim( $item );
					continue;
				}

				xtpb_collect_font_families_from_value( $item, $families );
			}
		}
	}
}

if ( ! function_exists( 'xtpb_get_google_font_imports_from_content' ) ) {
	function xtpb_get_google_font_imports_from_families( $families = array() ) {
		if ( ! xtpb_google_fonts_enabled() ) {
			return '';
		}

		$families = array_map(
			function( $family ) {
				return is_string( $family ) ? trim( $family ) : '';
			},
			(array) $families
		);
		$families = array_values( array_unique( array_filter( $families ) ) );
		$families = array_values( array_diff( $families, xtpb_google_fonts_system_families() ) );

		if ( empty( $families ) ) {
			return '';
		}

		$google_families = array_map(
			function( $family ) {
				return str_replace( ' ', '+', $family );
			},
			$families
		);

		$imports = "@import url('https://fonts.googleapis.com/css?family=" . implode( '|', $google_families ) . "');";

		if ( xtpb_google_fonts_local_enabled() ) {
			$imports = xtpb_process_google_fonts_css( $imports );
		}

		return $imports;
	}
}

if ( ! function_exists( 'xtpb_get_google_font_families_from_content' ) ) {
	function xtpb_get_google_font_families_from_content( $content ) {
		if ( is_string( $content ) ) {
			$decoded_content = json_decode( $content, true );
			if ( is_array( $decoded_content ) ) {
				$content = $decoded_content;
			}
		}

		$families = array();
		xtpb_collect_font_families_from_value( $content, $families );
		$families = array_map(
			function( $family ) {
				return is_string( $family ) ? trim( $family ) : '';
			},
			$families
		);
		$families = array_values( array_unique( array_filter( $families ) ) );

		return array_values( array_diff( $families, xtpb_google_fonts_system_families() ) );
	}
}

if ( ! function_exists( 'xtpb_get_google_font_imports_from_content' ) ) {
	function xtpb_get_google_font_imports_from_content( $content ) {
		if ( ! xtpb_google_fonts_enabled() ) {
			return '';
		}

		return xtpb_get_google_font_imports_from_families(
			xtpb_get_google_font_families_from_content( $content )
		);
	}
}

if ( ! function_exists( 'xtpb_rebuild_google_fonts_css' ) ) {
	function xtpb_rebuild_google_fonts_css( $css = '', $content = array() ) {
		$css = xtpb_strip_google_fonts_imports( $css );

		if ( ! xtpb_google_fonts_enabled() ) {
			return $css;
		}

		return xtpb_get_google_font_imports_from_content( $content ) . $css;
	}
}

if ( ! function_exists('get_xtpb_array_value_by_key')){
	function get_xtpb_array_value_by_key($array = array(), $key = null){
		$value = null;

		if ( key_exists($key, $array)){
			$value = $array[$key];
		}
		return $value;
	}
}

/**
 * @return array
 *
 * Return all image sizes
 */
if ( ! function_exists('xtpb_get_all_image_sizes')){
	function xtpb_get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$image_sizes = array();
		$default_image_sizes = get_intermediate_image_sizes();

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
			$image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
			$image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
		}

		if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		return $image_sizes;
	}
}

/**
 * @return array
 *
 * Return ready made options for image sizes
 */
if ( ! function_exists('xtpb_getLall_image_sizes_option')){
	function xtpb_getLall_image_sizes_option(){
		$sizes = xtpb_get_all_image_sizes();

		$sizes_option = array();
		foreach ($sizes as $key => $size){
			$sizes_option[$key] = ucwords(str_replace(array('_', '-'), ' ', $key))." - ({$size['width']} x {$size['height']}) ";
		}
		return $sizes_option;
	}
}

/**
 * @return array
 */
if ( ! function_exists('xtpb_get_category_lists')){
	function xtpb_get_category_lists(){
		$cat = array();
		$categories = get_categories();

		foreach ($categories as $category){
			$cat[$category->cat_ID] = $category->cat_name;
		}

		return $cat;
	}
}

if ( ! function_exists('xtpb_get_tags_lists')){
	function xtpb_get_tags_lists(){
		$tagArr = array();
		$tags = get_tags();

		foreach ($tags as $tag){
			$tagArr[$tag->term_id] = $tag->name;
		}

		return $tagArr;
	}
}

if ( ! function_exists('xtpb_get_saved_addon_settings')) {
	function xtpb_get_saved_addon_settings( $addon_id = 0, $page_id = 0 ) {
		if ( ! $addon_id || ! $page_id ) {
			return false;
		}

		$addon_id = (int) $addon_id;

		$page_builder_content = get_post_meta( $page_id, '_xtpb_content', true );
		$settings             = json_decode( $page_builder_content, true );

		foreach ( $settings as $setting ) {
			$columns_count = 0;
			if ( isset( $setting['columns'] ) ) {
				$columns_count = count( $setting['columns'] );
			}

			if ( $columns_count ) {
				foreach ( $setting['columns'] as $col ) {
					$addons_count = 0;
					if ( isset( $col['addons'] ) ) {
						$addons_count = count( $col['addons'] );
					}

					if ( $addons_count ) {
						foreach ( $col['addons'] as $addon ) {

							$addon_type = '';
							if ( ! empty( $addon['type'] ) ) {
								$addon_type = $addon['type'];
							}

							if ( $addon_type === 'inner_row' ) {
								$the_addon = array();
								if ( isset( $addon['columns'] ) && ! empty( $addon['columns'] ) ) {
									foreach ( $addon['columns'] as $inner_columns ) {
										foreach ( $inner_columns['addons'] as $inner_addon ) {
											$the_addon = $inner_addon;
										}
									}
								}
								$saved_addon_id = (int) isset( $the_addon ) ? $the_addon['id'] : 0;
								if ( $addon_id === $saved_addon_id ) {
									return $the_addon;
								}
							} else {
								if ( $addon_type === 'addon' ) {
									$saved_addon_id = (int) isset( $addon ) ? $addon['id'] : 0;
									if ( $addon_id === $saved_addon_id ) {
										return $addon;
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

if ( ! function_exists('xtpb_get_saved_addons_by_name')){

	function xtpb_get_saved_addons_by_name($addon_name = false, $page_id = 0){
		if ( ! $addon_name || ! $page_id){
			return false;
		}

		$target_addon_name = $addon_name;

		$page_builder_content = get_post_meta($page_id, '_xtpb_content', true);
		$settings = json_decode($page_builder_content, true);

		$addons = array();
		if( is_array( $settings ) ){
			foreach ( $settings as $setting ){
				$columns_count = 0;
				if ( isset($setting['columns'])){
					$columns_count = count($setting['columns']);
				}

				if ($columns_count){
					foreach ($setting['columns'] as $col){
						$addons_count = 0;
						if ( isset($col['addons'])){
							$addons_count = count($col['addons']);
						}

						if ($addons_count) {
							
							foreach ( $col['addons'] as $addon ) {

								$addon_type = '';
								if ( ! empty($addon['type'])){
									$addon_type = $addon['type'];
								}

									if ( $addon_type === 'inner_row' ) {
										$the_addon = array();
										if ( isset( $addon['columns'] ) && ! empty( $addon['columns'] ) ) {
											foreach ( $addon['columns'] as $inner_columns ) {
												if ( empty( $inner_columns['addons'] ) || ! is_array( $inner_columns['addons'] ) ) {
													continue;
												}

												foreach ( $inner_columns['addons'] as $inner_addon ) {
													$the_addon = $inner_addon;
												}
											}
										}
										$inner_addon_name = isset( $the_addon['name'] ) ? $the_addon['name'] : '';
										if ( $target_addon_name === $inner_addon_name ) {
											$addons[] = $the_addon;
										}
									} else {
										if ( $addon_type === 'addon' ) {
											$saved_addon_name = isset( $addon['name'] ) ? $addon['name'] : '';
											if ( $target_addon_name === $saved_addon_name ) {
												$addons[] = $addon;
											}
										}
								}
							}
						}
					}
				}
			}
		}

		return $addons;
	}
}

if ( ! function_exists( 'xtpb_get_used_addons_from_content' ) ) {
	function xtpb_get_used_addons_from_content( $page_builder_data = array() ) {
		return xtpb_helper()->get_used_addon_names_from_content( $page_builder_data );
	}
}

if ( ! function_exists( 'xtpb_get_addon_slug' ) ) {
	function xtpb_get_addon_slug( $addon_name ) {
		$slug = preg_replace( '/^xtpb_/', '', $addon_name );
		$slug_map = array(
			'block_number' => 'blocknumber',
			'posts_grid'   => 'posts',
		);

		return isset( $slug_map[ $slug ] ) ? $slug_map[ $slug ] : $slug;
	}
}

	if ( ! function_exists( 'xtpb_get_addon_base_css_path' ) ) {
		function xtpb_get_addon_base_css_path( $addon_name ) {
			$slug = xtpb_get_addon_slug( $addon_name );
			return XTPB_DIR_PATH . 'addons/' . $slug . '/assets/css/frontend.css';
		}
	}

	if ( ! function_exists( 'xtpb_get_addon_base_css_paths' ) ) {
		function xtpb_get_addon_base_css_paths( $addon_name ) {
			$paths    = array();
			$instance = xtpb_helper()->get_class_by_addon_name( $addon_name );

			if ( $instance ) {
				if ( method_exists( $instance, 'get_css_files' ) ) {
					$paths = array_merge( $paths, (array) $instance->get_css_files() );
				} elseif ( method_exists( $instance, 'get_css_file' ) ) {
					$paths[] = $instance->get_css_file();
				} elseif ( method_exists( $instance, 'get_base_css_paths' ) ) {
					$paths = array_merge( $paths, (array) $instance->get_base_css_paths() );
				} elseif ( method_exists( $instance, 'get_base_css_path' ) ) {
					$paths[] = $instance->get_base_css_path();
				}
			}

			$paths[] = xtpb_get_addon_base_css_path( $addon_name );
			$paths   = apply_filters( 'xtpb_addon_base_css_paths', $paths, $addon_name, $instance );
			$paths   = array_filter( array_map( 'wp_normalize_path', (array) $paths ) );

			return array_values( array_unique( $paths ) );
		}
	}

if ( ! function_exists( 'xtpb_get_addon_frontend_script_config' ) ) {
	function xtpb_get_addon_frontend_script_config() {
		return array(
			'xtpb_accordion'             => array( 'deps' => array( 'jquery' ) ),
			'xtpb_alert'                 => array( 'deps' => array( 'jquery' ) ),
			'xtpb_animated_number'       => array( 'deps' => array( 'jquery', 'jquery.inview' ) ),
			'xtpb_flip_box'              => array( 'deps' => array( 'jquery' ) ),
			'xtpb_image'                 => array( 'deps' => array( 'jquery', 'jquery.magnific-popup' ) ),
			'xtpb_person_carousel'       => array( 'deps' => array( 'jquery', 'xtpb-slick-slider' ) ),
			'xtpb_pie_progress'          => array( 'deps' => array( 'jquery', 'jquery.easypiechart', 'jquery.inview' ) ),
			'xtpb_tab'                   => array( 'deps' => array( 'jquery' ) ),
			'xtpb_testimonial_carousel'  => array( 'deps' => array( 'jquery', 'xtpb-slick-slider' ) ),
			'xtpb_video_popup'           => array( 'deps' => array( 'jquery', 'jquery.magnific-popup' ) ),
		);
	}
}

if ( ! function_exists( 'xtpb_get_page_script_save_as' ) ) {
	function xtpb_get_page_script_save_as() {
		$js_save_as = xtpb_get_option( 'js_save_as' );
		if ( $js_save_as ) {
			return $js_save_as;
		}

		$css_save_as = xtpb_get_option( 'css_save_as' );
		if ( $css_save_as ) {
			return $css_save_as;
		}

		return 'filesystem';
	}
}

if ( ! function_exists( 'xtpb_get_page_addon_script_config' ) ) {
	function xtpb_get_page_addon_script_config() {
		return array(
			'xtpb_accordion'            => array(
				'files' => array( XTPB_DIR_PATH . 'addons/accordion/assets/js/frontend.js' ),
				'deps'  => array( 'jquery' ),
			),
			'xtpb_alert'                => array(
				'files' => array( XTPB_DIR_PATH . 'addons/alert/assets/js/frontend.js' ),
				'deps'  => array( 'jquery' ),
			),
			'xtpb_animated_number'      => array(
				'files' => array( XTPB_DIR_PATH . 'addons/animated_number/assets/js/frontend.js' ),
				'deps'  => array( 'jquery', 'jquery.inview' ),
			),
			'xtpb_carousel'             => array(
				'files' => array(
					XTPB_DIR_PATH . 'addons/carousel/assets/js/slick-slider-animation.js',
					XTPB_DIR_PATH . 'addons/carousel/assets/js/thm-slider-main.js',
				),
				'deps'  => array( 'jquery', 'xtpb-slick-slider' ),
			),
			'xtpb_flip_box'             => array(
				'files' => array( XTPB_DIR_PATH . 'addons/flip_box/assets/js/frontend.js' ),
				'deps'  => array( 'jquery' ),
			),
			'xtpb_form'                 => array(
				'files' => array( XTPB_DIR_PATH . 'addons/form/assets/js/main.js' ),
				'deps'  => array( 'jquery' ),
			),
			'xtpb_image'                => array(
				'files' => array( XTPB_DIR_PATH . 'addons/image/assets/js/frontend.js' ),
				'deps'  => array( 'jquery', 'jquery.magnific-popup' ),
			),
			'xtpb_person_carousel'      => array(
				'files' => array( XTPB_DIR_PATH . 'addons/person_carousel/assets/js/frontend.js' ),
				'deps'  => array( 'jquery', 'xtpb-slick-slider' ),
			),
			'xtpb_pie_progress'         => array(
				'files' => array( XTPB_DIR_PATH . 'addons/pie_progress/assets/js/frontend.js' ),
				'deps'  => array( 'jquery', 'jquery.easypiechart', 'jquery.inview' ),
			),
			'xtpb_posts_grid'           => array(
				'files' => array( XTPB_DIR_PATH . 'addons/posts/assets/js/posts-addon.js' ),
				'deps'  => array( 'jquery' ),
			),
			'xtpb_tab'                  => array(
				'files' => array( XTPB_DIR_PATH . 'addons/tab/assets/js/frontend.js' ),
				'deps'  => array( 'jquery' ),
			),
			'xtpb_testimonial_carousel' => array(
				'files' => array( XTPB_DIR_PATH . 'addons/testimonial_carousel/assets/js/frontend.js' ),
				'deps'  => array( 'jquery', 'xtpb-slick-slider' ),
			),
			'xtpb_video_popup'          => array(
				'files' => array( XTPB_DIR_PATH . 'addons/video_popup/assets/js/frontend.js' ),
				'deps'  => array( 'jquery', 'jquery.magnific-popup' ),
			),
		);
	}
}

if ( ! function_exists( 'xtpb_get_page_addon_script_prelude' ) ) {
	function xtpb_get_page_addon_script_prelude( $addon_name ) {
		switch ( $addon_name ) {
			case 'xtpb_form':
				return 'window.xtpb_form = window.xtpb_form || ' . wp_json_encode(
					array(
						'ajax_url'           => admin_url( 'admin-ajax.php' ),
						'form_addon_dir_url' => XTPB_DIR_URL . 'addons/form/',
					)
				) . ';';

			case 'xtpb_posts_grid':
				return 'window.xtpb_posts_addon = window.xtpb_posts_addon || ' . wp_json_encode(
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'xtpb-posts-addon' ),
					)
				) . ';';
		}

		return '';
	}
}

if ( ! function_exists( 'xtpb_get_addon_instance_script_files' ) ) {
	function xtpb_get_addon_instance_script_files( $addon_name ) {
		$files    = array();
		$instance = xtpb_helper()->get_class_by_addon_name( $addon_name );

		if ( $instance ) {
			if ( method_exists( $instance, 'get_js_files' ) ) {
				$files = array_merge( $files, (array) $instance->get_js_files() );
			} elseif ( method_exists( $instance, 'get_js_file' ) ) {
				$files[] = $instance->get_js_file();
			} elseif ( method_exists( $instance, 'get_base_js_paths' ) ) {
				$files = array_merge( $files, (array) $instance->get_base_js_paths() );
			} elseif ( method_exists( $instance, 'get_base_js_path' ) ) {
				$files[] = $instance->get_base_js_path();
			}
		}

		$files = array_filter( array_map( 'wp_normalize_path', (array) $files ) );

		return array_values( array_unique( $files ) );
	}
}

if ( ! function_exists( 'xtpb_get_page_addon_script_files' ) ) {
	function xtpb_get_page_addon_script_files( $addon_name ) {
		$config_map = xtpb_get_page_addon_script_config();
		$files      = array();

		if ( ! empty( $config_map[ $addon_name ]['files'] ) ) {
			$files = array_merge( $files, (array) $config_map[ $addon_name ]['files'] );
		}

		$files = array_merge( $files, xtpb_get_addon_instance_script_files( $addon_name ) );
		$files = array_filter( array_map( 'wp_normalize_path', (array) $files ) );

		return array_values( array_unique( $files ) );
	}
}

if ( ! function_exists( 'xtpb_get_addon_instance_script_dependency_handles' ) ) {
	function xtpb_get_addon_instance_script_dependency_handles( $addon_name ) {
		$deps     = array();
		$instance = xtpb_helper()->get_class_by_addon_name( $addon_name );

		if ( $instance ) {
			if ( method_exists( $instance, 'get_js_dependencies' ) ) {
				$deps = array_merge( $deps, (array) $instance->get_js_dependencies() );
			} elseif ( method_exists( $instance, 'get_js_deps' ) ) {
				$deps = array_merge( $deps, (array) $instance->get_js_deps() );
			}
		}

		$deps = array_filter( array_map( 'trim', (array) $deps ) );

		return array_values( array_unique( $deps ) );
	}
}

if ( ! function_exists( 'xtpb_get_page_addon_script_dependency_handles' ) ) {
	function xtpb_get_page_addon_script_dependency_handles( $addon_names = array() ) {
		$config_map = xtpb_get_page_addon_script_config();
		$deps       = array();

		foreach ( (array) $addon_names as $addon_name ) {
			if ( ! empty( $config_map[ $addon_name ]['deps'] ) ) {
				foreach ( $config_map[ $addon_name ]['deps'] as $dep ) {
					$deps[ $dep ] = $dep;
				}
			}

			foreach ( xtpb_get_addon_instance_script_dependency_handles( $addon_name ) as $dep ) {
				$deps[ $dep ] = $dep;
			}
		}

		return array_values( $deps );
	}
}

if ( ! function_exists( 'xtpb_get_page_addon_script_dependency_handles_from_content' ) ) {
	function xtpb_get_page_addon_script_dependency_handles_from_content( $page_builder_data = array() ) {
		return xtpb_get_page_addon_script_dependency_handles( xtpb_get_used_addons_from_content( $page_builder_data ) );
	}
}

if ( ! function_exists( 'xtpb_get_merged_addon_script_handles' ) ) {
	function xtpb_get_merged_addon_script_handles() {
		return array_merge(
			array_values( xtpb_get_addon_frontend_script_handles( array_keys( xtpb_get_addon_frontend_script_config() ) ) ),
			array(
				'xtpb-form.main.js',
				'xtpb-posts-addon',
				'thm.carousel.main',
				'thm.slider.custom.main',
				'thm.carousel.slick.main',
			)
		);
	}
}

if ( ! function_exists( 'xtpb_build_page_addon_js' ) ) {
	function xtpb_build_page_addon_js( $page_builder_data = array() ) {
		$addon_names = xtpb_get_used_addons_from_content( $page_builder_data );
		return xtpb_build_addon_js_bundle( $addon_names );
	}
}

if ( ! function_exists( 'xtpb_build_addon_js_bundle' ) ) {
	function xtpb_build_addon_js_bundle( $addon_names = array() ) {
		if ( empty( $addon_names ) ) {
			return '';
		}

		$js         = "/* XTPB merged addon js */\nwindow.XTPB_PAGE_JS = window.XTPB_PAGE_JS || {};\n";
		$has_script = false;

		foreach ( $addon_names as $addon_name ) {
			$files = xtpb_get_page_addon_script_files( $addon_name );
			if ( empty( $files ) ) {
				continue;
			}

			$prelude = xtpb_get_page_addon_script_prelude( $addon_name );
			if ( '' !== $prelude ) {
				$js .= "\n/* addon prelude: {$addon_name} */\n{$prelude}\n";
			}

			foreach ( $files as $path ) {
				if ( ! file_exists( $path ) ) {
					continue;
				}

				$addon_js = file_get_contents( $path );
				if ( ! is_string( $addon_js ) || '' === trim( $addon_js ) ) {
					continue;
				}

				$has_script = true;
				$js        .= "\n/* addon: {$addon_name} | file: " . basename( $path ) . " */\n" . $addon_js . "\n";
			}
		}

		if ( ! $has_script ) {
			return '';
		}

		return trim( $js );
	}
}

if ( ! function_exists( 'xtpb_build_editor_addon_js' ) ) {
	function xtpb_build_editor_addon_js() {
		$addon_names = array_keys( xtpb_get_page_addon_script_config() );

		foreach ( xtpb_helper()->get_addon_instances() as $instance ) {
			if ( method_exists( $instance, 'get_name' ) ) {
				$addon_names[] = $instance->get_name();
			}
		}

		return xtpb_build_addon_js_bundle( array_values( array_unique( $addon_names ) ) );
	}
}

if ( ! function_exists( 'xtpb_get_editor_addon_script_dependency_handles' ) ) {
	function xtpb_get_editor_addon_script_dependency_handles() {
		$addon_names = array_keys( xtpb_get_page_addon_script_config() );

		foreach ( xtpb_helper()->get_addon_instances() as $instance ) {
			if ( method_exists( $instance, 'get_name' ) ) {
				$addon_names[] = $instance->get_name();
			}
		}

		return xtpb_get_page_addon_script_dependency_handles( array_values( array_unique( $addon_names ) ) );
	}
}

if ( ! function_exists( 'xtpb_get_addon_frontend_script_path' ) ) {
	function xtpb_get_addon_frontend_script_path( $addon_name ) {
		$slug = xtpb_get_addon_slug( $addon_name );
		return XTPB_DIR_PATH . 'addons/' . $slug . '/assets/js/frontend.js';
	}
}

if ( ! function_exists( 'xtpb_get_addon_frontend_script_url' ) ) {
	function xtpb_get_addon_frontend_script_url( $addon_name ) {
		$slug = xtpb_get_addon_slug( $addon_name );
		return XTPB_DIR_URL . 'addons/' . $slug . '/assets/js/frontend.js';
	}
}

if ( ! function_exists( 'xtpb_get_addon_frontend_script_handle' ) ) {
	function xtpb_get_addon_frontend_script_handle( $addon_name ) {
		$slug = str_replace( '_', '-', xtpb_get_addon_slug( $addon_name ) );
		return 'xtpb-addon-' . $slug . '-frontend';
	}
}

if ( ! function_exists( 'xtpb_register_addon_frontend_script' ) ) {
	function xtpb_register_addon_frontend_script( $addon_name ) {
		$config_map = xtpb_get_addon_frontend_script_config();
		if ( empty( $config_map[ $addon_name ] ) ) {
			return false;
		}

		$path = xtpb_get_addon_frontend_script_path( $addon_name );
		if ( ! file_exists( $path ) ) {
			return false;
		}

		$handle = xtpb_get_addon_frontend_script_handle( $addon_name );
		$deps   = isset( $config_map[ $addon_name ]['deps'] ) ? $config_map[ $addon_name ]['deps'] : array( 'jquery' );

		wp_register_script( $handle, xtpb_get_addon_frontend_script_url( $addon_name ), $deps, filemtime( $path ), true );

		return $handle;
	}
}

if ( ! function_exists( 'xtpb_get_addon_frontend_script_handles' ) ) {
	function xtpb_get_addon_frontend_script_handles( $addon_names = array() ) {
		$handles = array();

		foreach ( (array) $addon_names as $addon_name ) {
			$handle = xtpb_register_addon_frontend_script( $addon_name );
			if ( $handle ) {
				$handles[ $handle ] = $handle;
			}
		}

		return array_values( $handles );
	}
}

if ( ! function_exists( 'xtpb_get_all_addon_frontend_script_handles' ) ) {
	function xtpb_get_all_addon_frontend_script_handles() {
		return xtpb_get_addon_frontend_script_handles( array_keys( xtpb_get_addon_frontend_script_config() ) );
	}
}

	if ( ! function_exists( 'xtpb_get_addon_base_css' ) ) {
		function xtpb_get_addon_base_css( $addon_name ) {
			$css = '';

			foreach ( xtpb_get_addon_base_css_paths( $addon_name ) as $path ) {
				if ( ! file_exists( $path ) || ! is_readable( $path ) ) {
					continue;
				}

				$file_css = file_get_contents( $path );
				if ( is_string( $file_css ) && '' !== trim( $file_css ) ) {
					$css .= "\n/* file: " . basename( $path ) . " */\n" . $file_css . "\n";
				}
			}

			return $css;
		}
	}

if ( ! function_exists( 'xtpb_get_all_addons_base_css' ) ) {
	function xtpb_get_all_addons_base_css() {
		$css = '';

		foreach ( xtpb_helper()->get_addon_instances() as $instance ) {
			if ( ! method_exists( $instance, 'get_name' ) ) {
				continue;
			}

			$addon_css = xtpb_get_addon_base_css( $instance->get_name() );
			if ( '' !== $addon_css ) {
				$css .= "\n" . $addon_css;
			}
		}

		return $css;
	}
}

if ( ! function_exists( 'xtpb_build_page_addon_base_css' ) ) {
	function xtpb_build_page_addon_base_css( $page_builder_data = array() ) {
		$addon_names = xtpb_get_used_addons_from_content( $page_builder_data );
		if ( empty( $addon_names ) ) {
			return '';
		}

		$css = "/* XTPB merged addon css */\n";
		foreach ( $addon_names as $addon_name ) {
			$addon_css = xtpb_get_addon_base_css( $addon_name );
			if ( '' !== $addon_css ) {
				$css .= "\n/* addon: {$addon_name} */\n" . $addon_css . "\n";
			}
		}

		return $css;
	}
}

if ( ! function_exists( 'xtpb_get_fontawesome_to_remixicon_map' ) ) {
	function xtpb_get_fontawesome_to_remixicon_map() {
		return array(
			'fa-plus'                     => 'ri-add-line',
			'fa-plus-circle'              => 'ri-add-circle-line',
			'fa-plus-square'              => 'ri-add-box-line',
			'fa-arrow-circle-right'       => 'ri-arrow-right-circle-line',
			'fa-angle-right'              => 'ri-arrow-right-s-line',
			'fa-angle-left'               => 'ri-arrow-left-s-line',
			'fa-angle-down'               => 'ri-arrow-down-s-line',
			'fa-angle-up'                 => 'ri-arrow-up-s-line',
			'fa-angle-double-right'       => 'ri-arrow-right-double-line',
			'fa-angle-double-left'        => 'ri-arrow-left-double-line',
			'fa-chevron-right'            => 'ri-arrow-right-s-line',
			'fa-chevron-left'             => 'ri-arrow-left-s-line',
			'fa-chevron-circle-right'     => 'ri-arrow-right-circle-line',
			'fa-caret-right'              => 'ri-arrow-right-s-fill',
			'fa-home'                     => 'ri-home-4-line',
			'fa-star'                     => 'ri-star-line',
			'fa-arrows-alt'               => 'ri-drag-move-line',
			'fa-cog'                      => 'ri-settings-3-line',
			'fa-apple'                    => 'ri-apple-fill',
			'fa-facebook'                 => 'ri-facebook-fill',
			'fa-facebook-f'               => 'ri-facebook-fill',
			'fa-facebook-square'          => 'ri-facebook-box-fill',
			'fa-twitter'                  => 'ri-twitter-fill',
			'fa-twitter-square'           => 'ri-twitter-line',
			'fa-youtube'                  => 'ri-youtube-fill',
			'fa-youtube-square'           => 'ri-youtube-fill',
			'fa-vimeo'                    => 'ri-vimeo-fill',
			'fa-vimeo-square'             => 'ri-vimeo-fill',
			'fa-google'                   => 'ri-google-fill',
			'fa-google-plus'              => 'ri-google-fill',
			'fa-google-plus-square'       => 'ri-google-fill',
			'fa-dribbble'                 => 'ri-dribbble-line',
			'fa-github'                   => 'ri-github-fill',
			'fa-github-alt'               => 'ri-github-fill',
			'fa-github-square'            => 'ri-github-fill',
			'fa-amazon'                   => 'ri-amazon-fill',
			'fa-behance'                  => 'ri-behance-fill',
			'fa-codepen'                  => 'ri-codepen-fill',
			'fa-flickr'                   => 'ri-flickr-fill',
			'fa-foursquare'               => 'ri-map-pin-line',
			'fa-git'                      => 'ri-git-branch-line',
			'fa-git-square'               => 'ri-git-branch-line',
			'fa-gitlab'                   => 'ri-gitlab-fill',
			'fa-linux'                    => 'ri-ubuntu-fill',
			'fa-whatsapp'                 => 'ri-whatsapp-fill',
			'fa-tumblr'                   => 'ri-tumblr-fill',
			'fa-tumblr-square'            => 'ri-tumblr-fill',
			'fa-trello'                   => 'ri-trello-fill',
			'fa-soundcloud'               => 'ri-soundcloud-fill',
			'fa-spotify'                  => 'ri-spotify-fill',
			'fa-skype'                    => 'ri-skype-fill',
			'fa-slack'                    => 'ri-slack-fill',
			'fa-qq'                       => 'ri-qq-fill',
			'fa-reddit'                   => 'ri-reddit-fill',
			'fa-reddit-square'            => 'ri-reddit-fill',
			'fa-reddit-alien'             => 'ri-reddit-fill',
			'fa-tripadvisor'              => 'ri-map-2-line',
			'fa-pinterest'                => 'ri-pinterest-fill',
			'fa-pinterest-p'              => 'ri-pinterest-fill',
			'fa-pinterest-square'         => 'ri-pinterest-fill',
			'fa-product-hunt'             => 'ri-shopping-bag-3-line',
			'fa-paypal'                   => 'ri-paypal-fill',
			'fa-medium'                   => 'ri-medium-fill',
			'fa-instagram'                => 'ri-instagram-fill',
			'fa-linkedin'                 => 'ri-linkedin-fill',
			'fa-linkedin-square'          => 'ri-linkedin-box-fill',
			'fa-android'                  => 'ri-android-fill',
			'fa-btc'                      => 'ri-bit-coin-fill',
			'fa-cc-stripe'                => 'ri-bank-card-fill',
			'fa-digg'                     => 'ri-global-line',
			'fa-dropbox'                  => 'ri-dropbox-fill',
			'fa-delicious'                => 'ri-bookmark-fill',
			'fa-cc-visa'                  => 'ri-visa-fill',
			'fa-html5'                    => 'ri-html5-fill',
			'fa-slideshare'               => 'ri-slideshow-line',
			'fa-skyatlas'                 => 'ri-global-line',
			'fa-stack-overflow'           => 'ri-stack-fill',
			'fa-stumbleupon-circle'       => 'ri-compass-3-line',
			'fa-stumbleupon'              => 'ri-compass-3-line',
			'fa-wordpress'                => 'ri-wordpress-fill',
			'fa-xing'                     => 'ri-xing-fill',
			'fa-xing-square'              => 'ri-xing-fill',
			'fa-yahoo'                    => 'ri-mail-line',
			'fa-bitbucket'                => 'ri-bitbucket-fill',
			'fa-css3'                     => 'ri-css3-fill',
			'fa-cc-mastercard'            => 'ri-mastercard-fill',
			'fa-cc-paypal'                => 'ri-paypal-fill',
			'fa-cc-discover'              => 'ri-bank-card-fill',
			'fa-cc-amex'                  => 'ri-bank-card-fill',
			'fa-dashcube'                 => 'ri-dashboard-line',
			'fa-drupal'                   => 'ri-drupal-fill',
			'fa-jsfiddle'                 => 'ri-javascript-fill',
			'fa-joomla'                   => 'ri-article-line',
			'fa-imdb'                     => 'ri-film-line',
			'fa-vk'                       => 'ri-vk-fill',
			'fa-houzz'                    => 'ri-home-gear-line',
			'fa-odnoklassniki'            => 'ri-chat-smile-3-line',
			'fa-odnoklassniki-square'     => 'ri-chat-smile-3-line',
			'fa-snapchat'                 => 'ri-snapchat-fill',
			'fa-snapchat-square'          => 'ri-snapchat-fill',
			'fa-telegram'                 => 'ri-telegram-fill',
			'fa-twitch'                   => 'ri-twitch-fill',
			'fa-weibo'                    => 'ri-weibo-fill',
			'fa-weixin'                   => 'ri-wechat-fill',
			'fa-yelp'                     => 'ri-map-pin-user-line',
			'fa-windows'                  => 'ri-windows-fill',
			'fa-question-circle'          => 'ri-question-line',
			'fa-times-circle'             => 'ri-close-circle-line',
			'fa-share'                    => 'ri-external-link-line',
			'fa-pencil-alt'               => 'ri-pencil-line',
			'fa-trash'                    => 'ri-delete-bin-line',
			'fa-trash-alt'                => 'ri-delete-bin-line',
			'fa-check'                    => 'ri-check-line',
			'fa-check-square-o'           => 'ri-checkbox-circle-line',
			'fa-lock'                     => 'ri-lock-line',
			'fa-unlock'                   => 'ri-lock-unlock-line',
			'fa-laptop'                   => 'ri-computer-line',
			'fa-tablet-alt'               => 'ri-tablet-line',
			'fa-mobile-alt'               => 'ri-smartphone-line',
			'fa-align-left'               => 'ri-align-left',
			'fa-align-center'             => 'ri-align-center',
			'fa-align-right'              => 'ri-align-right',
			'fa-align-justify'            => 'ri-align-justify',
			'fa-paste'                    => 'ri-clipboard-line',
			'fa-clone'                    => 'ri-file-copy-line',
			'fa-times'                    => 'ri-close-line',
		);
	}
}

if ( ! function_exists( 'xtpb_normalize_icon_class' ) ) {
	function xtpb_normalize_icon_class( $icon_class ) {
		if ( ! is_string( $icon_class ) ) {
			return $icon_class;
		}

		$icon_class = trim( $icon_class );
		if ( '' === $icon_class || false === strpos( $icon_class, 'fa' ) ) {
			return $icon_class;
		}

		if ( 0 === strpos( $icon_class, 'ri-' ) || false !== strpos( $icon_class, ' ri-' ) || 0 === strpos( $icon_class, 'xtpb-font-' ) ) {
			return $icon_class;
		}

		$map = xtpb_get_fontawesome_to_remixicon_map();

		if ( isset( $map[ $icon_class ] ) ) {
			return $map[ $icon_class ];
		}

		if ( preg_match( '/\b(fa-[a-z0-9-]+)\b/', $icon_class, $matches ) ) {
			$fa_icon = $matches[1];

			if ( isset( $map[ $fa_icon ] ) ) {
				return $map[ $fa_icon ];
			}
		}

		return 'ri-question-line';
	}
}

if ( ! function_exists( 'xtpb_is_rendering_builder_content' ) ) {
	/**
	 * Track whether the_content is currently rendering a page-builder layout.
	 *
	 * Modules may run the_content for their own rich-text or queried post content.
	 * Without this context flag that nested filter pass can render the current
	 * builder page again and recurse until PHP runs out of memory.
	 */
	function xtpb_is_rendering_builder_content( $rendering = null ) {
		static $is_rendering = false;

		if ( null !== $rendering ) {
			$is_rendering = (bool) $rendering;
		}

		return $is_rendering;
	}
}

if ( ! function_exists( 'xtpb_normalize_builder_content_icons' ) ) {
	function xtpb_normalize_builder_content_icons( $content ) {
		$was_json = is_string( $content );

		if ( $was_json ) {
			$decoded = json_decode( $content, true );
			if ( JSON_ERROR_NONE !== json_last_error() ) {
				return $content;
			}
			$content = $decoded;
		}

		$normalized = xtpb_normalize_builder_content_icons_value( $content );

		if ( $was_json ) {
			return wp_json_encode( $normalized );
		}

		return $normalized;
	}
}

if ( ! function_exists( 'xtpb_normalize_builder_content_icons_value' ) ) {
	function xtpb_normalize_builder_content_icons_value( $value, $key = '' ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $item_key => $item_value ) {
				$value[ $item_key ] = xtpb_normalize_builder_content_icons_value( $item_value, $item_key );
			}

			return $value;
		}

		if ( is_object( $value ) ) {
			foreach ( $value as $item_key => $item_value ) {
				$value->{$item_key} = xtpb_normalize_builder_content_icons_value( $item_value, $item_key );
			}

			return $value;
		}

		if ( is_string( $value ) && false !== strpos( $value, 'fa' ) && preg_match( '/(?:^|_)(icon|navigation)(?:_|$)/', (string) $key ) ) {
			return xtpb_normalize_icon_class( $value );
		}

		return $value;
	}
}
