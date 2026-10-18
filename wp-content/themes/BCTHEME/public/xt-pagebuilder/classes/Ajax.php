<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('XTPB_Ajax')){

	class XTPB_Ajax{

		protected $api_base_url = '';
		protected $xtpb_api_request_body;
		protected $xtpb_api_request_body_default;

		/**
		 * XTPB_Ajax constructor.
		 */
		public function __construct() {
			$this->api_base_url = $this->resolve_api_base_url();
			$this->xtpb_api_request_body_default = array(
				'request_from'  => 'xtpb',
				'request_xtpb_version'  => XTPB_VERSION,
			);
			$this->xtpb_api_request_body = apply_filters('xtpb_api_request_body', array());

			//add_action( 'wp_ajax_nopriv_addonsdata', array($this, 'xtpb_addons_single_data') );
			add_action( 'wp_ajax_addonsdata', array($this, 'xtpb_addons_single_data') );
			add_action( 'wp_ajax_xtpb_page_save', array($this, 'xtpb_page_save') );
			add_action( 'wp_ajax_xtpb_pagebuilder_section_action', array($this, 'xtpb_pagebuilder_section_action') );
			add_action( 'wp_ajax_xtpb_google_fonts_editor_import', array($this, 'xtpb_google_fonts_editor_import') );
			add_action( 'wp_ajax_xtpb_google_fonts_editor_imports', array($this, 'xtpb_google_fonts_editor_imports') );

			//Render Addon for no template
			add_action('wp_ajax_xtpb_render_addon', array($this, 'xtpb_render_addon'));

			//Delete Css with Post
			add_action( 'before_delete_post', array($this, 'delete_css_file_with_post') );

			// Get The Url Of the Custom Size Image
			add_action('wp_ajax_xtpb_image_size_url', array($this, 'xtpb_image_size_url'));

			// Search posts for async form selects.
			add_action('wp_ajax_xtpb_search_posts', array($this, 'xtpb_search_posts'));

			// 从服务器获取模板
			add_action('wp_ajax_xtpb_load_page_template', array($this, 'xtpb_load_page_template'));
			add_action('wp_ajax_xtpb_import_single_page_template', array($this, 'xtpb_import_single_page_template'));

			//Get Blocks
			add_action('wp_ajax_xtpb_get_blocks', array($this, 'xtpb_get_blocks') );

			//Clear cache action
			add_action('wp_ajax_xtpb_clear_cache', array($this, 'xtpb_clear_cache') );
			add_action('wp_ajax_xtpb_rebuild_page_assets', array($this, 'xtpb_rebuild_page_assets') );

			// Section And Template Add
			add_filter('xtpb_add_block', array( $this , 'xtpb_add_block_callback' ) );
			add_filter('xtpb_add_layout', array( $this , 'xtpb_add_layout_callback' ) );
		}

		protected function resolve_api_base_url() {
			$default_url = 'http://test.local/wp-json/restapi/v2/';
			$api_base_url = defined( 'XTPB_LIBRARY_SERVICE_URL' ) && XTPB_LIBRARY_SERVICE_URL
				? XTPB_LIBRARY_SERVICE_URL
				: $default_url;
			$api_base_url = trailingslashit( $api_base_url );

			return apply_filters( 'xtpb_library_service_url', $api_base_url );
		}

		protected function get_library_request_timeout() {
			return (int) apply_filters( 'xtpb_library_request_timeout', 120 );
		}

		protected function ensure_directory_exists( $dir ) {
			if ( is_dir( $dir ) ) {
				return true;
			}

			return wp_mkdir_p( $dir );
		}

		protected function write_file_contents( $file_path, $contents ) {
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

		protected function write_json_file( $file_path, $data ) {
			return $this->write_file_contents( $file_path, wp_json_encode( $data ) );
		}

		protected function delete_file_if_exists( $file_path ) {
			global $wp_filesystem;

			if ( ! file_exists( $file_path ) ) {
				return true;
			}

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem( false, dirname( $file_path ), true );
			}

			if ( ! $wp_filesystem || ! is_object( $wp_filesystem ) ) {
				return false;
			}

			return $wp_filesystem->delete( $file_path );
		}

		protected function delete_directory_if_exists( $dir_path ) {
			global $wp_filesystem;

			if ( ! is_dir( $dir_path ) ) {
				return true;
			}

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem( false, dirname( $dir_path ), true );
			}

			if ( ! $wp_filesystem || ! is_object( $wp_filesystem ) ) {
				return false;
			}

			return $wp_filesystem->delete( untrailingslashit( $dir_path ), true );
		}

		protected function get_page_assets_dir() {
			$upload_dir = wp_upload_dir();
			return trailingslashit( $upload_dir['basedir'] ) . 'xt-pagebuilder/';
		}

		protected function get_builder_asset_post_ids( $offset = 0, $limit = 20 ) {
			$query = new WP_Query(
				array(
					'post_type'              => 'any',
					'post_status'            => 'any',
					'posts_per_page'         => $limit,
					'offset'                 => $offset,
					'fields'                 => 'ids',
					'orderby'                => 'ID',
					'order'                  => 'ASC',
					'no_found_rows'          => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'meta_query'             => array(
						'relation' => 'AND',
						array(
							'key'     => '_xtpb_current_post_editor',
							'value'   => 'xtpb_builder_activated',
							'compare' => '=',
						),
						array(
							'key'     => '_xtpb_content',
							'value'   => '',
							'compare' => '!=',
						),
					),
				)
			);

			return array(
				'ids'   => $query->posts,
				'total' => (int) $query->found_posts,
			);
		}

		protected function strip_page_common_css( $css ) {
			$patterns = array(
				'/\.xtpb-builder-container \.xtpb-section > \.xtpb-section__inner,\.xtpb-carousel-content-wrap \.xtpb-container\{max-width:\d+px;padding-left:\d+px;padding-right:\d+px;box-sizing:border-box;\}/',
				'/@media \(max-width:767px\)\{\.xtpb-builder-container \.xtpb-section > \.xtpb-section__inner,\.xtpb-carousel-content-wrap \.xtpb-container\{padding-left:\d+px;padding-right:\d+px;\}\}/',
				'/\.xtpb-builder-container \.xtpb-container-full\{max-width:none;\}/',
				'/\.xtpb-builder-container \.xtpb-section__grid,\.xtpb-builder-container \.xtpb-inner-section__grid\{gap:var\(--xtpb-gap,\d+px\);\}/',
			);

			return preg_replace( $patterns, '', $css );
		}

		protected function rebuild_single_page_assets( $post_id ) {
			$page_builder_data = get_post_meta( $post_id, '_xtpb_content', true );

			if ( empty( $page_builder_data ) ) {
				return array(
					'success' => false,
					'message' => __( '页面构建器内容为空。', 'xintheme-pagebuilder' ),
				);
			}

			$asset_dir = $this->get_page_assets_dir();
			if ( ! $this->ensure_directory_exists( $asset_dir ) ) {
				return array(
					'success' => false,
					'message' => __( '无法创建页面资源目录。', 'xintheme-pagebuilder' ),
				);
			}

			$css_path  = $asset_dir . "xtpb-page-css-{$post_id}.css";
			$js_path   = $asset_dir . "xtpb-page-js-{$post_id}.js";
			$page_css  = (string) get_post_meta( $post_id, '_xtpb_page_css', true );
			$addon_css = xtpb_helper()->get_addon_page_css_from_content( $page_builder_data );

			$page_css = $this->strip_page_common_css( $page_css );
			if ( $addon_css && ! xtpb_helper()->has_generated_addon_css( $page_css ) ) {
				$page_css = $addon_css . $page_css;
			}
			$page_css = xtpb_rebuild_google_fonts_css( $page_css . self::get_content_common_css(), $page_builder_data );
			update_post_meta( $post_id, '_xtpb_page_css', $page_css );

			$page_js = xtpb_build_page_addon_js( $page_builder_data );
			update_post_meta( $post_id, '_xtpb_page_js', wp_slash( $page_js ) );

			$css_saved = $this->write_file_contents( $css_path, $page_css );
			$js_saved  = true;

			if ( '' !== $page_js ) {
				$js_saved = $this->write_file_contents( $js_path, $page_js );
			} else {
				$this->delete_file_if_exists( $js_path );
			}

			if ( ! $css_saved || ! $js_saved ) {
				return array(
					'success' => false,
					'message' => __( '资源文件写入失败。', 'xintheme-pagebuilder' ),
				);
			}

			return array(
				'success' => true,
				'message' => __( '资源已重新生成。', 'xintheme-pagebuilder' ),
			);
		}

		public function xtpb_add_block_callback( $sections = array() ) {
			return $sections;
		}

		public function xtpb_add_layout_callback( $templates = array() ) {
			return $templates;
		}

		public function xtpb_image_size_url(){

			XTPB_Helper::xtpb_verify_ajax_call();

			if ( empty($_POST['id']) || empty($_POST['size']) ){
				wp_send_json_error();
				return;
			}
			$image_data = wp_get_attachment_image_src(  $_POST['id'], $_POST['size'] );
			if( isset($image_data[0]) ){
				wp_send_json_success( $image_data[0] );
			}else{
				wp_send_json_error();
			}
		}

		public function xtpb_search_posts(){
			XTPB_Helper::xtpb_verify_ajax_call();

			$post_type = isset( $_POST['post_type'] ) ? sanitize_key( wp_unslash( $_POST['post_type'] ) ) : 'post';
			$search    = isset( $_POST['s'] ) ? sanitize_text_field( wp_unslash( $_POST['s'] ) ) : '';
			$limit     = isset( $_POST['limit'] ) ? absint( $_POST['limit'] ) : 20;
			$include   = isset( $_POST['include'] ) ? wp_unslash( $_POST['include'] ) : array();

			if ( ! post_type_exists( $post_type ) ) {
				wp_send_json_success( array( 'options' => array() ) );
			}

			if ( is_string( $include ) ) {
				$include = explode( ',', $include );
			}

			if ( ! is_array( $include ) ) {
				$include = array();
			}

			$include = array_values( array_filter( array_map( 'absint', $include ) ) );
			$limit   = min( max( $limit, 1 ), 50 );

			$args = array(
				'post_type'              => $post_type,
				'post_status'            => 'publish',
				'posts_per_page'         => $include ? count( $include ) : $limit,
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			);

			if ( $include ) {
				$args['post__in'] = $include;
				$args['orderby']  = 'post__in';
			} else {
				$args['s']       = $search;
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
			}

			$posts   = get_posts( $args );
			$options = array();

			foreach ( $posts as $post ) {
				$title = get_the_title( $post );

				$options[] = array(
					'value' => (string) $post->ID,
					'label' => ( $title ? $title : __( '(无标题)', 'xintheme-pagebuilder' ) ) . ' (#' . $post->ID . ')',
				);
			}

			wp_send_json_success( array( 'options' => $options ) );
		}

		public function xtpb_addons_single_data(){
			XTPB_Helper::xtpb_verify_ajax_call();

			$addon_id = isset( $_POST['id'] ) ? sanitize_html_class( wp_unslash( $_POST['id'] ) ) : '';
			$addon_name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';

			if ( ! $addon_id || ! $addon_name ) {
				wp_send_json_error();
			}

			$addons_data = array();
			$addons_data['assets'] = array();
			$addons_data['html'] = '<div id="xtpb-addon-' . $addon_id . '" class="clearfix" ></div>';
			$addons_data['status'] = true;

			$available_addons = xtpb_helper()->get_addon_classes();
			if( !empty($available_addons) ) {
				foreach ( $available_addons as $class ) {
					$data = new $class();
					if( $data->get_name() === $addon_name ){
						$total_data = $data->get_settings();
						if( is_array($total_data) ){
							if( !empty($total_data) ){
								$total = array();
								foreach ($total_data as $key => $value) {
									if( isset($value['std']) ){
										$total[$key] = $value['std'];
									}else{
										$total[$key] = '';
									}
								}
								$addons_data['formData'] = wp_json_encode( $total );
							}
						}
					}
				}
			}

			wp_send_json( $addons_data );
		}

		public function xtpb_google_fonts_editor_import() {
			XTPB_Helper::xtpb_verify_ajax_call();

			$family = isset( $_POST['family'] ) ? sanitize_text_field( wp_unslash( $_POST['family'] ) ) : '';

			if ( '' === $family || in_array( $family, xtpb_google_fonts_system_families(), true ) ) {
				wp_send_json_success(
					array(
						'import' => '',
					)
				);
			}

			$import_rule = "@import url('https://fonts.googleapis.com/css?family=" . str_replace( ' ', '+', $family ) . "');";

			if ( xtpb_google_fonts_local_enabled() ) {
				$import_rule = xtpb_google_fonts_local_import( $import_rule );
			}

			wp_send_json_success(
				array(
					'import' => is_string( $import_rule ) ? $import_rule : '',
				)
			);
		}

		public function xtpb_google_fonts_editor_imports() {
			XTPB_Helper::xtpb_verify_ajax_call();

			$families = isset( $_POST['families'] ) ? wp_unslash( $_POST['families'] ) : array();

			if ( is_string( $families ) ) {
				$families = array_map( 'trim', explode( ',', $families ) );
			}

			if ( ! is_array( $families ) ) {
				$families = array();
			}

			$families = array_map( 'sanitize_text_field', $families );
			$families = array_values( array_unique( array_filter( $families ) ) );

			wp_send_json_success(
				array(
					'import' => xtpb_get_google_font_imports_from_families( $families ),
				)
			);
		}

		/**
		 * Return Common CSS from the Path
		 */
		public static function get_content_common_css(){
			$css = '';
			$get_content_width            = (int) xtpb_get_option('xtpb_container_width');
			$xtpb_container_gap           = xtpb_get_option('xtpb_container_side_gap');
			$xtpb_container_gap_mobile    = xtpb_get_option('xtpb_container_side_gap_mobile');
			$xtpb_col_spacing             = (int) xtpb_get_option('xtpb_col_spacing');

			if ( ! $get_content_width ) {
				$get_content_width = 1140;
			}

			if ( null === $xtpb_container_gap || '' === $xtpb_container_gap ) {
				$xtpb_container_gap = 20;
			} else {
				$xtpb_container_gap = (int) $xtpb_container_gap;
			}

			if ( null === $xtpb_container_gap_mobile || '' === $xtpb_container_gap_mobile ) {
				$xtpb_container_gap_mobile = 10;
			} else {
				$xtpb_container_gap_mobile = (int) $xtpb_container_gap_mobile;
			}

			if ( ! $xtpb_col_spacing ) {
				$xtpb_col_spacing = 30;
			}

			$css .= ".xtpb-builder-container .xtpb-section > .xtpb-section__inner,.xtpb-carousel-content-wrap .xtpb-container{max-width:{$get_content_width}px;padding-left:{$xtpb_container_gap}px;padding-right:{$xtpb_container_gap}px;box-sizing:border-box;}";
			$css .= "@media (max-width:767px){.xtpb-builder-container .xtpb-section > .xtpb-section__inner,.xtpb-carousel-content-wrap .xtpb-container{padding-left:{$xtpb_container_gap_mobile}px;padding-right:{$xtpb_container_gap_mobile}px;}}";
			$css .= ".xtpb-builder-container .xtpb-container-full{max-width:none;}";
			$css .= ".xtpb-builder-container .xtpb-section__grid,.xtpb-builder-container .xtpb-inner-section__grid{gap:var(--xtpb-gap,{$xtpb_col_spacing}px);}";
			return $css;
		}

		/**
		 * Validate CSS.
		 *
		 * Checks for imbalanced braces, brackets, and comments.
		 * Notifications are rendered when the customizer state is saved.
		 *
		 * @since 4.7.0
		 * @since 4.9.0 Checking for balanced characters has been moved client-side via linting in code editor.
		 *
		 * @param string $css The input string.
		 * @return true|WP_Error True if the input was validated, otherwise WP_Error.
		 */
		public function validate( $css ) {
			$validity = new WP_Error();

			if ( preg_match( '#</?\w+#', $css ) ) {
				$validity->add( 'illegal_markup', __( 'Markup is not allowed in CSS.' ) );
			}

			if ( ! $validity->has_errors() ) {
				return true;
			}
			return $validity;
		}


		/**
		 * Save Page Builder data wp post meta
		 */
		public function  xtpb_page_save(){
			global $wp_filesystem;
			if ( ! $wp_filesystem ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}

			XTPB_Helper::xtpb_verify_ajax_call();

			$page_id = isset( $_POST['page_id'] ) ? (int) sanitize_text_field( $_POST['page_id'] ) : 0;
			$page_builder_data = isset( $_POST['page_builder_data'] ) ? $_POST['page_builder_data'] : array();
			$page_builder_data = xtpb_normalize_builder_content_icons( $page_builder_data );
			$xtpb_page_css = isset( $_POST['xtpb_page_css'] ) ? strip_tags( stripslashes( $_POST['xtpb_page_css'] ), '<style>' ) : '';
			
			$xtpb_page_css = xtpb_helper()->get_addon_page_css_from_content( $page_builder_data ) . $xtpb_page_css . $this->get_content_common_css();
			$xtpb_page_css = xtpb_rebuild_google_fonts_css( $xtpb_page_css, $page_builder_data );

			update_post_meta($page_id, '_xtpb_content', $page_builder_data);
			update_post_meta($page_id, '_xtpb_page_css', $xtpb_page_css);
			update_post_meta($page_id, '_xtpb_page_js', wp_slash( xtpb_build_page_addon_js( $page_builder_data ) ));
			xtpb_helper()->set_current_post_editor( $page_id, 'xtpb_builder_activated' );
			xtpb_helper()->clear_pending_post_editor( $page_id );

			$success_array = array( 'msg' => __('页面构建器内容已更新。', 'xintheme-pagebuilder') );
			$error_array = array();

			$filename = "xtpb-page-css-{$page_id}.css";
			$js_filename = "xtpb-page-js-{$page_id}.js";
			$page_js = (string) get_post_meta( $page_id, '_xtpb_page_js', true );

			$upload_dir = wp_upload_dir();
			$dir = trailingslashit($upload_dir['basedir']) . 'xt-pagebuilder/';

			WP_Filesystem( false, $upload_dir['basedir'], true );

			if( ! $wp_filesystem->is_dir( $dir ) ) {
				$wp_filesystem->mkdir( $dir );
			}
			//If fail to save css in directory, then it will show a message to user
			if ( ! $wp_filesystem->put_contents( $dir . $filename, $xtpb_page_css ) ) {
				$error_array['msg_error'] = __('由于权限限制，CSS 无法保存。', 'xintheme-pagebuilder');
			}
			if ( '' !== $page_js ) {
				if ( ! $wp_filesystem->put_contents( $dir . $js_filename, $page_js ) ) {
					$error_array['msg_error_js'] = __( '由于权限限制，JS 无法保存。', 'xintheme-pagebuilder' );
				}
			} elseif ( $wp_filesystem->exists( $dir . $js_filename ) ) {
				$wp_filesystem->delete( $dir . $js_filename );
			}

			$msg_array = array_merge($success_array, $error_array);
			wp_send_json_success($msg_array);
		}

		public function xtpb_pagebuilder_section_action(){
			XTPB_Helper::xtpb_verify_ajax_call();

			$action_type 	= isset( $_POST['actionType'] ) ? sanitize_text_field( $_POST['actionType'] ) : 'get';
			$section_id 	= isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : 0;
			$db_section 	= get_option( 'xtpb_section', false );
			if( $action_type == 'save' ){
				$id 			= isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : 0;
				$title 			= isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
				$category 		= isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';
				$section_data 	= isset( $_POST['section_data'] ) ? json_decode( stripslashes_deep( $_POST['section_data'] ) ) : array();
				$preview_data   = isset( $_POST['preview_data'] ) ? wp_unslash( $_POST['preview_data'] ) : '';
				$preview_url    = $preview_data ? $this->save_section_preview_image( $id, $preview_data ) : '';
				if( $db_section ){
					$db_section = json_decode( $db_section );
					$db_section->$id = array(
						'id'      => $id,
						'title'   => $title,
						'category'=> $category,
						'block'   => $section_data,
						'preview' => $preview_url,
					);
					update_option( 'xtpb_section', json_encode($db_section) );
				} else {
					add_option(
						'xtpb_section',
						json_encode(
							array(
								$id => array(
									'id'      => $id,
									'title'   => $title,
									'category'=> $category,
									'block'   => $section_data,
									'preview' => $preview_url,
								),
							)
						)
					);
				}
				wp_send_json_success(
					array(
						'id'      => (int) $id,
						'preview' => $preview_url,
					)
				);
			} elseif(  $action_type == 'get' ){
				$sections = json_decode( (string) get_option( 'xtpb_section', '' ), true );
				wp_send_json( is_array( $sections ) ? $sections : array() );
			} elseif ( 'rename' === $action_type ) {
				$title = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
				$category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

				if ( $db_section && $title ) {
					$db_section = json_decode( $db_section );

					if ( isset( $db_section->$section_id ) ) {
						$db_section->$section_id->title = $title;
						$db_section->$section_id->category = $category;
						update_option( 'xtpb_section', wp_json_encode( $db_section ) );
					}
				}

				wp_send_json_success();
			} elseif ( 'reorder' === $action_type ) {
				$ids = isset( $_POST['ids'] ) ? json_decode( wp_unslash( $_POST['ids'] ), true ) : array();

				if ( $db_section && is_array( $ids ) ) {
					$db_section = json_decode( $db_section );
					$ordered_sections = new \stdClass();

					foreach ( $ids as $id ) {
						$id = (string) absint( $id );

						if ( $id && isset( $db_section->$id ) ) {
							$ordered_sections->$id = $db_section->$id;
						}
					}

					foreach ( get_object_vars( $db_section ) as $id => $section ) {
						if ( ! isset( $ordered_sections->$id ) ) {
							$ordered_sections->$id = $section;
						}
					}

					update_option( 'xtpb_section', wp_json_encode( $ordered_sections ) );
				}

				wp_send_json_success();
			} else {
				if( $db_section ){
					$db_section = json_decode( $db_section );
					$preview = isset( $db_section->$section_id->preview ) ? $db_section->$section_id->preview : '';
					$this->delete_section_preview_image( $section_id, $preview );
					unset( $db_section->$section_id );
					update_option( 'xtpb_section', json_encode($db_section) );
				}
				wp_send_json_success();
			}
		}

		private function get_saved_section_preview_directory() {
			$upload_dir = wp_upload_dir();

			return array(
				'path' => trailingslashit( $upload_dir['basedir'] ) . 'xt-pagebuilder/saved-sections/',
				'url'  => trailingslashit( $upload_dir['baseurl'] ) . 'xt-pagebuilder/saved-sections/',
			);
		}

		private function save_section_preview_image( $section_id, $preview_data ) {
			if ( ! is_string( $preview_data ) || 0 !== strpos( $preview_data, 'data:image/' ) ) {
				return '';
			}

			if ( ! preg_match( '#^data:image/([a-zA-Z0-9.+-]+);base64,(.*)$#', $preview_data, $matches ) ) {
				return '';
			}

			$extension_map = array(
				'webp' => 'webp',
				'png'  => 'png',
				'jpeg' => 'jpg',
				'jpg'  => 'jpg',
			);
			$image_type = strtolower( $matches[1] );

			if ( ! isset( $extension_map[ $image_type ] ) ) {
				return '';
			}

			$binary = base64_decode( $matches[2], true );
			if ( false === $binary ) {
				return '';
			}

			$preview_dir = $this->get_saved_section_preview_directory();
			if ( ! file_exists( $preview_dir['path'] ) ) {
				wp_mkdir_p( $preview_dir['path'] );
			}

			$this->delete_section_preview_image( $section_id );

			$extension = $extension_map[ $image_type ];
			$filename = 'section-' . absint( $section_id ) . '.' . $extension;
			$file_path = $preview_dir['path'] . $filename;

			if ( ! $this->write_file_contents( $file_path, $binary ) ) {
				return '';
			}

			return $preview_dir['url'] . $filename;
		}

		private function delete_section_preview_image( $section_id, $preview_url = '' ) {
			$preview_dir = $this->get_saved_section_preview_directory();
			$extensions  = array( 'webp', 'png', 'jpg', 'jpeg' );

			if ( $preview_url ) {
				$path = wp_parse_url( $preview_url, PHP_URL_PATH );
				$file = $path ? basename( $path ) : '';
				if ( $file ) {
					$full_path = $preview_dir['path'] . $file;
					$this->delete_file_if_exists( $full_path );
				}
			}

			foreach ( $extensions as $extension ) {
				$file_path = $preview_dir['path'] . 'section-' . absint( $section_id ) . '.' . $extension;
				$this->delete_file_if_exists( $file_path );
			}
		}

		public function xtpb_render_addon(){

			XTPB_Helper::xtpb_verify_ajax_call();

			$addon_name = null;
			if (empty($_POST['addon'])){
				wp_send_json_error();
			}
			$addon = $_POST['addon'];
			$addon_name = $addon['name'];
			try{
				$addon_instantce = addon_instance($addon_name);
				if ( method_exists($addon_instantce, 'render')){
					$rendered_addon =  $addon_instantce->render($addon);
					wp_send_json_success(array('html' => $rendered_addon, 'assets' => array() ));
				}
			}catch (\Exception $e){
				wp_send_json_error(array('msg' => $e->getMessage()));
			}
		}


		/**
		 * @since 1.0.0
		 * 删除生成的 CSS 文件
		 */
		function delete_css_file_with_post( $postid ){
			$is_xtpb_content = xtpb_helper()->has_builder_content( $postid );
			if ( ! $is_xtpb_content){
				return;
			}

			$filename = "xtpb-page-css-{$postid}.css";
			$js_filename = "xtpb-page-js-{$postid}.js";

			$upload_dir = wp_upload_dir();
			$dir = trailingslashit($upload_dir['basedir']) . 'xt-pagebuilder/';
			$this->delete_file_if_exists( $dir . $filename );
			$this->delete_file_if_exists( $dir . $js_filename );
		}

		/**
		 * @since 1.0.0-BETA
		 * Get Template
		 */
		public function xtpb_load_page_template(){

			XTPB_Helper::xtpb_verify_ajax_call();

			$cachedTemplateFile = "xtpb-templates.json";
			$cache_time = (60*60*24*7); //cached for 7 days
			$force_refresh = ! empty( $_POST['force_refresh'] );

			$templateData = array();

			$upload_dir = wp_upload_dir();
			$dir = trailingslashit($upload_dir['basedir']) . 'xt-pagebuilder/cache/templates/';
			$file_path_name = $dir . $cachedTemplateFile;

			if ( ! $force_refresh && file_exists($file_path_name) && (filemtime($file_path_name) + $cache_time) > time()){
				$getTemplatesFromCached = file_get_contents($file_path_name);
				$templateData = json_decode($getTemplatesFromCached, true);
				if ( ! is_array( $templateData ) ) {
					$templateData = array();
				}
				$cached_at = sprintf(
					/* translators: %s: human-readable cache age. */
					__( '上次缓存：%s前', 'xintheme-pagebuilder' ),
					human_time_diff( filemtime( $file_path_name ), current_time( 'timestamp' ) )
				);
				$thirparty_template = $this->xtpb_get_thirdparty_template();

				if( !empty( $thirparty_template ) ){
					$templateData = array_merge( $templateData, $thirparty_template );
				}

				wp_send_json(array('success' => true, 'cached_at' => $cached_at,  'data' => $templateData));
			}else{
				$templateData = $this->load_templates_from_remote();
				if ( ! is_array( $templateData ) ) {
					$templateData = array();
				}
				$thirparty_template = $this->xtpb_get_thirdparty_template();
				if( !empty( $thirparty_template ) ){
					$templateData = array_merge( $templateData, $thirparty_template );
				}

				$cached_at = __( '刚刚更新', 'xintheme-pagebuilder' );
			}

			wp_send_json(
				array(
					'success'   => true,
					'cached_at' => $cached_at,
					'data'      => $templateData,
				)
			);
		}

		/**
		 * @since 1.0.0
		 * Get Thirdparty Templates
		 */
		public function xtpb_get_thirdparty_template(){
			$templateData = array();

			$templates = apply_filters( 'xtpb_add_layout', array() );
			if( !empty( $templates ) ){
				foreach( $templates as $value ){
					if( isset( $value['name'], $value['category'], $value['preview2x'], $value['preview'], $value['single'] ) ){
						if( is_array( $value['single'] ) && !empty( $value['single'] ) ){
							$category = array();
							foreach( $value['category'] as $val ){ $category[] = array( 'name' => $val, 'slug' => $val ); }
							$p_id = time().rand(1,1000);
							$templateData[] = array(
								'name' => $value['name'],
								'category' => $category,
								'ID' => $p_id,
								'parentID' => 0,
								'preview' => $value['preview'],
								'preview_m' => $value['preview2x'],
								'thirdparty' => true,
							);
							foreach( $value['single'] as $v ){
								if( $v['file'] ){
									$templateData[] = array(
										'name' => $value["name"].'&#8211;'.$v["name"],
										'category' => $category,
										'ID' => time().rand(1,1000),
										'parentID' => $p_id,
										'preview' => $v['preview'],
										'preview_m' => $v['preview2x'],
										'file' => $v['file'],
										'liveurl' => isset( $v['liveurl'] ) ? $v['liveurl'] : '',
										'thirdparty' => true,
									);
								}
							}
						}
					}
				}
			}
			return $templateData;
		}



		/**
		 * @since 1.0.0-BETA
		 * Load Template from Server
		 */
		public function load_templates_from_remote(){
			$apiUrl = $this->api_base_url.'templates';
			$post_args = array( 'timeout' => $this->get_library_request_timeout() );
			$body_param = array_merge($this->xtpb_api_request_body_default, array( 'request_for' => 'get_all_template'));
			$post_args['body'] = array_merge($body_param, $this->xtpb_api_request_body);
			$tempalteRequest = wp_remote_post($apiUrl, $post_args);
			if (is_wp_error($tempalteRequest)){
				wp_send_json_error(array('messages' => $tempalteRequest->get_error_messages()));
			}
			$templateData = json_decode($tempalteRequest['body'], true);
			$cachedTemplateFile = "xtpb-templates.json";
			$upload_dir = wp_upload_dir();
			$dir = trailingslashit($upload_dir['basedir']) . 'xt-pagebuilder/cache/templates/';
			$file_path_name = $dir . $cachedTemplateFile;
			if ( ! $this->ensure_directory_exists( $dir ) ) {
				return $templateData;
			}
			$this->write_json_file( $file_path_name, $templateData ); // Put template content to cached directory
			return $templateData;
		}

		/**
		 * @since 1.0.0
		 * Import single template
		 */
		public function xtpb_import_single_page_template() {
			XTPB_Helper::xtpb_verify_ajax_call();

			$template_id = isset( $_POST['template_id'] ) ? (int) sanitize_text_field( $_POST['template_id'] ) : 0;
			$fileUrl = isset( $_POST['fileUrl'] ) ? $_POST['fileUrl'] : '';

			if( !$fileUrl ){
				$cache_time = (60*60*24*7); //cached for 7 days
				$cachedTemplateFile = "xtpb-single-template-{$template_id}.json";
				$upload_dir = wp_upload_dir();
				$dir = trailingslashit($upload_dir['basedir']) . 'xt-pagebuilder/cache/templates/';
				$file_path_name = $dir . $cachedTemplateFile;
				// Checking if exists file and cache validity true
				if (file_exists($file_path_name) && (filemtime($file_path_name) + $cache_time) > time()){
					$getTemplatesFromCached = file_get_contents($file_path_name);
					$templateData = json_decode($getTemplatesFromCached, true);
				}else{
					$templateData = $this->load_and_cache_single_template($template_id);
				}
			}else{
				if (file_exists($fileUrl)){
					$getContent = file_get_contents($fileUrl);
					$templateData = json_decode($getContent, true);
				}else{
					$templateData = $this->load_and_cache_single_template($template_id);
				}
			}
			wp_send_json_success($templateData);
		}


		/**
		 * @since 1.0.0-BETA
		 * Load single template and cache it
		 */
		public function load_and_cache_single_template($template_id = 0){
			if ( ! $template_id){
				return false;
			}
			$apiUrl = $this->api_base_url.'single-template';

			$post_args = array( 'timeout' => $this->get_library_request_timeout() );

			$body_param = array_merge($this->xtpb_api_request_body_default,
				array( 'request_for' => 'get_single_template', 'template_id' => $template_id)
			);
			$post_args['body'] = array_merge($body_param, $this->xtpb_api_request_body);
			$tempalteRequest = wp_remote_post($apiUrl, $post_args);

			if (is_wp_error($tempalteRequest)){
				wp_send_json_error(array('messages' => $tempalteRequest->get_error_messages()));
			}

			$templateData = json_decode($tempalteRequest['body'], true);
			if (!empty($templateData['success']) && $templateData['success']) {
				$templateData = json_decode( $templateData['rawData'], true );

				//If data not null, or not empty
				if (! empty($templateData)){
					$cachedTemplateFile = "xtpb-single-template-{$template_id}.json";
					$upload_dir         = wp_upload_dir();
					$dir                = trailingslashit($upload_dir['basedir']).'xt-pagebuilder/cache/templates/';

					$file_path_name = $dir.$cachedTemplateFile;

					if ( ! $this->ensure_directory_exists( $dir ) ) {
						return $templateData;
					}
					//Put template content to cached directory
					$this->write_json_file( $file_path_name, $templateData );
				}
			}
			return $templateData;
		}

		/**
		 * @since 1.0.0
		 * Get all blocks from cache or request
		 */
		public function xtpb_get_blocks(){
			$cache_time = (60*60*24*7); //cached for 7 days
			$force_refresh = ! empty( $_POST['force_refresh'] );

			$cachedTemplateFile = "xtpb-blocks.json";
			$upload_dir = wp_upload_dir();
			$dir = trailingslashit($upload_dir['basedir']) . 'xt-pagebuilder/cache/blocks/';
			$file_path_name = $dir . $cachedTemplateFile;

			//Checking if exists file and cache validity true
			if ( ! $force_refresh && file_exists($file_path_name) && (filemtime($file_path_name) + $cache_time) > time()){
				$getTemplatesFromCached = file_get_contents($file_path_name);
				$blocksData = json_decode($getTemplatesFromCached, true);
			}else{
				$blocksData = $this->cacheBlocks();
			}

			if ( ! is_array( $blocksData ) ) {
				$blocksData = array();
			}

			// Thirdparty Blocks
			$sections = apply_filters( 'xtpb_add_block', array() );
			if( !empty( $sections ) ){
				foreach( $sections as $value ){
					if( isset( $value['name'], $value['file'], $value['banner'], $value['section'] ) ){
						$raw_block_data = array();
						if ( file_exists( $value['file'] ) ) {
							$raw_block_data = json_decode( file_get_contents( $value['file'] ), true );
						}

						$blocksData[] = array(
							'name' => $value['name'],
							'ID' => time().rand(1,1000),
							'rawData' => is_array( $raw_block_data ) ? $raw_block_data : array(),
							'banner' => $value['banner'],
							'thirdparty' => true,
							'liveurl' => ( isset($value['liveurl']) ? $value['liveurl'] : '' ),
							'type' => array( array( 'label'=> $value["section"] , 'value' => $value["section"] ) )
						);
					}
				}
			}
			wp_send_json_success($blocksData);
		}

		public function cacheBlocks(){
			$cachedTemplateFile = "xtpb-blocks.json";
			$upload_dir         = wp_upload_dir();
			$dir                = trailingslashit($upload_dir['basedir']).'xt-pagebuilder/cache/blocks/';

			$apiUrl = $this->api_base_url.'sections';

			$post_args = array( 'timeout' => $this->get_library_request_timeout() );
			$body_param = array_merge($this->xtpb_api_request_body_default, array( 'request_for' => 'xtpb_get_blocks'));
			$post_args['body'] = array_merge($body_param, $this->xtpb_api_request_body);
			$tempalteRequest = wp_remote_post($apiUrl, $post_args);

			if (is_wp_error($tempalteRequest)){
				return array();
			}

			$blocksRemoteData = json_decode(trim($tempalteRequest['body']), true);
			if ( ! is_array( $blocksRemoteData ) ) {
				return array();
			}

			$newBlockdagta = array();
			foreach ($blocksRemoteData as $block){
				if ( ! is_array( $block ) ) {
					continue;
				}

				$rowData = isset( $block['rawData'] ) ? $block['rawData'] : array();
				if ( is_string( $rowData ) ) {
					$decoded = json_decode( $rowData, true );
					$block['rawData'] = is_array( $decoded ) ? $decoded : array();
				} elseif ( is_array( $rowData ) ) {
					$block['rawData'] = $rowData;
				} else {
					$block['rawData'] = array();
				}

				$newBlockdagta[] = $block;
			}

			$file_path_name = $dir.$cachedTemplateFile;
			if ( ! $this->ensure_directory_exists( $dir ) ) {
				return $newBlockdagta;
			}
			//Put template content to cached directory
			$this->write_json_file( $file_path_name, $newBlockdagta );

			return $newBlockdagta;
		}

		/**
		 * @since 1.0.0-BETA
		 * Action for cache delete directory and files
		 */
		public function xtpb_clear_cache(){
			check_ajax_referer( 'xtpb-nonce-string', 'xtpb_nonce');

			$upload_dir     = wp_upload_dir();
			$dirPattern     = trailingslashit($upload_dir['basedir']).'xt-pagebuilder/cache/';

			$isDone = $this->deleteRecursiveDirFile($dirPattern);
			if ($isDone){
				wp_send_json_success();
			}
			wp_send_json_error();
		}

		public function xtpb_rebuild_page_assets() {
			check_ajax_referer( 'xtpb-nonce-string', 'xtpb_nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => __( '你的权限不足，无法执行此操作。', 'xintheme-pagebuilder' ) ), 403 );
			}

			$offset = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;
			$limit  = isset( $_POST['limit'] ) ? absint( $_POST['limit'] ) : 20;
			$limit  = min( max( $limit, 1 ), 50 );
			$result = $this->get_builder_asset_post_ids( $offset, $limit );
			$done   = 0;
			$failed = array();

			foreach ( $result['ids'] as $post_id ) {
				$rebuild_result = $this->rebuild_single_page_assets( $post_id );

				if ( ! empty( $rebuild_result['success'] ) ) {
					$done++;
					continue;
				}

				$failed[] = array(
					'id'      => $post_id,
					'title'   => get_the_title( $post_id ),
					'message' => isset( $rebuild_result['message'] ) ? $rebuild_result['message'] : __( '未知错误。', 'xintheme-pagebuilder' ),
				);
			}

			$next_offset = $offset + count( $result['ids'] );
			wp_send_json_success(
				array(
					'total'       => $result['total'],
					'processed'   => $next_offset,
					'done'        => $done,
					'failed'      => $failed,
					'next_offset' => $next_offset,
					'finished'    => $next_offset >= $result['total'],
				)
			);
		}

		/**
		 * @since 1.0.0-BETA
		 * Delete Recursively directory and files
		 */
		public function deleteRecursiveDirFile($path = null){
			if (!$path){
				return false;
			}

			$path = trailingslashit($path);
			$files = glob($path.'*');

			foreach ($files as $file) {
				is_dir($file) ? $this->deleteRecursiveDirFile($file) : $this->delete_file_if_exists( $file );
			}
			return $this->delete_directory_if_exists( $path );
		}



	}
}
