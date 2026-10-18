<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_admin() && ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists( 'XTPB_Form_Records' ) ) {
	class XTPB_Form_Records {
		const DB_VERSION = '1.0.0';
		const STATUS_PENDING = '待处理';
		const STATUS_PROCESSING = '处理中';
		const STATUS_DONE = '已处理';
		const SCREEN_PER_PAGE_OPTION = 'xtpb_form_records_per_page';

		private $hook_suffix = '';

		public function __construct() {
			add_action( 'init', array( __CLASS__, 'maybe_create_table' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 30 );
			add_action( 'admin_post_xtpb_export_form_records', array( $this, 'export_records' ) );
			add_action( 'wp_ajax_xtpb_form_record_update_status', array( $this, 'ajax_update_status' ) );
			add_action( 'admin_footer', array( $this, 'admin_footer_assets' ) );
			add_filter( 'set-screen-option', array( $this, 'set_screen_option' ), 10, 3 );
		}

		public static function table_name() {
			global $wpdb;

			return $wpdb->prefix . 'xtpb_form_entries';
		}

		public static function statuses() {
			return array( self::STATUS_PENDING, self::STATUS_PROCESSING, self::STATUS_DONE );
		}

		public static function create_table() {
			global $wpdb;

			$table_name = self::table_name();
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE {$table_name} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				addon_id varchar(64) NOT NULL DEFAULT '',
				page_id bigint(20) unsigned NOT NULL DEFAULT 0,
				page_title text NOT NULL,
				source_url text NOT NULL,
				form_data longtext NOT NULL,
				status varchar(20) NOT NULL DEFAULT '待处理',
				ip_address varchar(100) NOT NULL DEFAULT '',
				user_agent text NOT NULL,
				submitted_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY  (id),
				KEY page_id (page_id),
				KEY status (status),
				KEY submitted_at (submitted_at)
			) {$charset_collate};";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			update_option( 'xtpb_form_records_db_version', self::DB_VERSION );
		}

		public static function maybe_create_table() {
			if ( get_option( 'xtpb_form_records_db_version' ) !== self::DB_VERSION ) {
				self::create_table();
			}
		}

		public static function is_enabled( $settings = array() ) {
			return '1' === (string) xtpb_get_option( 'form_records_enable', '0' );
		}

		public static function insert_entry( $addon_id, $page_id, $submitted_form_data, $settings = array(), $source_url = '' ) {
			if ( ! self::is_enabled( $settings ) || empty( $submitted_form_data ) ) {
				return false;
			}

			self::maybe_create_table();

			global $wpdb;

			$page_id = absint( $page_id );
			$form_data = array();

			foreach ( $submitted_form_data as $field ) {
				$label = isset( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '';
				$value = isset( $field['submitted_data'] ) ? $field['submitted_data'] : '';

				if ( is_array( $value ) ) {
					$value = implode( ', ', array_map( 'sanitize_text_field', $value ) );
				} else {
					$value = sanitize_textarea_field( $value );
				}

				if ( '' === $label && '' === $value ) {
					continue;
				}

				$form_data[] = array(
					'label' => $label,
					'value' => $value,
				);
			}

			if ( empty( $form_data ) ) {
				return false;
			}

			$source_url = self::get_source_url( $source_url, $page_id );
			$page_title = $page_id ? get_the_title( $page_id ) : '';

			return $wpdb->insert(
				self::table_name(),
				array(
					'addon_id'     => sanitize_text_field( $addon_id ),
					'page_id'      => $page_id,
					'page_title'   => sanitize_text_field( $page_title ),
					'source_url'   => esc_url_raw( $source_url ),
					'form_data'    => maybe_serialize( $form_data ),
					'status'       => self::STATUS_PENDING,
					'ip_address'   => self::get_client_ip(),
					'user_agent'   => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
					'submitted_at' => current_time( 'mysql' ),
				),
				array( '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
			);
		}

		public static function get_client_ip() {
			$ip = '';
			$keys = array( 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' );

			foreach ( $keys as $key ) {
				if ( ! empty( $_SERVER[ $key ] ) ) {
					$ip = sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );
					break;
				}
			}

			if ( false !== strpos( $ip, ',' ) ) {
				$ip = trim( current( explode( ',', $ip ) ) );
			}

			return $ip;
		}

		public function add_admin_menu() {
			if ( ! self::is_enabled() ) {
				return;
			}

			$count = $this->get_pending_count();
			$menu_title = __( '表单记录', 'xintheme-pagebuilder' );

			if ( $count > 0 ) {
				$menu_title .= sprintf(
					' <span class="update-plugins count-%1$d"><span class="plugin-count">%1$d</span></span>',
					(int) $count
				);
			}

			$this->hook_suffix = add_submenu_page(
				'xtpb-settings',
				__( '表单记录', 'xintheme-pagebuilder' ),
				$menu_title,
				'manage_options',
				'xtpb-form-records',
				array( $this, 'render_admin_page' )
			);

			if ( $this->hook_suffix ) {
				add_action( 'load-' . $this->hook_suffix, array( $this, 'add_screen_options' ) );
			}
		}

		public function add_screen_options() {
			$this->maybe_export_selected_records();

			$screen = get_current_screen();
			if ( $screen ) {
				add_filter( 'manage_' . $screen->id . '_columns', array( $this, 'get_screen_columns' ) );
			}

			add_screen_option(
				'per_page',
				array(
					'label'   => __( '每页显示条数', 'xintheme-pagebuilder' ),
					'default' => 20,
					'option'  => self::SCREEN_PER_PAGE_OPTION,
				)
			);
		}

		public function get_screen_columns() {
			return array(
				'submitted_at' => __( '提交时间', 'xintheme-pagebuilder' ),
				'source_url'   => __( '来源页面', 'xintheme-pagebuilder' ),
				'form_content' => __( '提交内容', 'xintheme-pagebuilder' ),
				'status'       => __( '状态', 'xintheme-pagebuilder' ),
				'ip_address'   => __( 'IP', 'xintheme-pagebuilder' ),
			);
		}

		public function set_screen_option( $status, $option, $value ) {
			if ( self::SCREEN_PER_PAGE_OPTION === $option ) {
				return max( 1, min( 999, absint( $value ) ) );
			}

			return $status;
		}

		private function get_pending_count() {
			global $wpdb;

			return (int) $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . self::table_name() . ' WHERE status = %s', self::STATUS_PENDING ) );
		}

		public static function get_request_filters() {
			$status = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';
			$page_id = isset( $_GET['source_page_id'] ) ? absint( $_GET['source_page_id'] ) : 0;

			if ( ! in_array( $status, self::statuses(), true ) ) {
				$status = '';
			}

			return array(
				'status'  => $status,
				'page_id' => $page_id,
			);
		}

		public static function get_where_sql( $filters = array() ) {
			global $wpdb;

			$filters = wp_parse_args(
				$filters,
				array(
					'status'  => '',
					'page_id' => 0,
				)
			);

			$where = array( '1=1' );

			if ( ! empty( $filters['status'] ) && in_array( $filters['status'], self::statuses(), true ) ) {
				$where[] = $wpdb->prepare( 'status = %s', $filters['status'] );
			}

			if ( ! empty( $filters['page_id'] ) ) {
				$where[] = $wpdb->prepare( 'page_id = %d', absint( $filters['page_id'] ) );
			}

			return 'WHERE ' . implode( ' AND ', $where );
		}

		public function export_records() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( '你没有权限导出表单记录。', 'xintheme-pagebuilder' ) );
			}

			if ( ! self::is_enabled() ) {
				wp_die( esc_html__( '后台表单提交记录未启用。', 'xintheme-pagebuilder' ) );
			}

			check_admin_referer( 'xtpb_export_form_records' );
			self::maybe_create_table();

			global $wpdb;

			$table_name = self::table_name();
			$where_sql = self::get_where_sql( self::get_request_filters() );
			$rows = $wpdb->get_results( "SELECT * FROM {$table_name} {$where_sql} ORDER BY submitted_at DESC", ARRAY_A );

			self::output_csv( $rows );
		}

		private function maybe_export_selected_records() {
			$action = '';

			if ( isset( $_POST['action'] ) && '-1' !== (string) $_POST['action'] ) {
				$action = sanitize_key( wp_unslash( $_POST['action'] ) );
			} elseif ( isset( $_POST['action2'] ) && '-1' !== (string) $_POST['action2'] ) {
				$action = sanitize_key( wp_unslash( $_POST['action2'] ) );
			}

			if ( 'export' !== $action || empty( $_POST['record_ids'] ) ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( '你没有权限导出表单记录。', 'xintheme-pagebuilder' ) );
			}

			if ( ! self::is_enabled() ) {
				wp_die( esc_html__( '后台表单提交记录未启用。', 'xintheme-pagebuilder' ) );
			}

			check_admin_referer( 'bulk-xtpb_form_records' );

			$ids = wp_parse_id_list( wp_unslash( $_POST['record_ids'] ) );
			if ( empty( $ids ) ) {
				return;
			}

			global $wpdb;

			$table_name = self::table_name();
			$placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );
			$rows = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$table_name} WHERE id IN ({$placeholders}) ORDER BY submitted_at DESC",
					$ids
				),
				ARRAY_A
			);

			self::output_csv( $rows );
		}

		public static function output_csv( $rows ) {
			while ( ob_get_level() ) {
				ob_end_clean();
			}

			nocache_headers();
			header( 'Content-Type: text/csv; charset=UTF-8' );
			header( 'Content-Disposition: attachment; filename=xtpb-form-records-' . date_i18n( 'Ymd-His' ) . '.csv' );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );

			$output = fopen( 'php://output', 'w' );
			if ( false === $output ) {
				exit;
			}

			fwrite( $output, "\xEF\xBB\xBF" );

			$rows = (array) $rows;
			$form_columns = self::get_export_form_columns( $rows );
			$headers = array(
				__( '提交时间', 'xintheme-pagebuilder' ),
				__( '来源页面', 'xintheme-pagebuilder' ),
			);
			$headers = array_merge( $headers, array_values( $form_columns ) );
			$headers = array_merge(
				$headers,
				array(
					__( '状态', 'xintheme-pagebuilder' ),
					__( 'IP', 'xintheme-pagebuilder' ),
				)
			);

			self::write_csv_row(
				$output,
				$headers
			);

			foreach ( $rows as $row ) {
				$source = self::get_row_source_url( $row );
				$form_values = self::get_export_form_values( $row );
				$csv_row = array(
					$row['submitted_at'],
					$source,
				);

				foreach ( $form_columns as $column_key => $column_label ) {
					$csv_row[] = isset( $form_values[ $column_key ] ) ? $form_values[ $column_key ] : '';
				}

				$csv_row[] = $row['status'];
				$csv_row[] = $row['ip_address'];

				self::write_csv_row( $output, $csv_row );
			}

			fclose( $output );
			exit;
		}

		private static function get_export_form_columns( $rows ) {
			$columns = array();

			foreach ( (array) $rows as $row ) {
				$fields = maybe_unserialize( isset( $row['form_data'] ) ? $row['form_data'] : '' );

				if ( ! is_array( $fields ) ) {
					continue;
				}

				foreach ( $fields as $field ) {
					$label = isset( $field['label'] ) ? trim( (string) $field['label'] ) : '';

					if ( '' === $label ) {
						continue;
					}

					$key = self::get_export_form_column_key( $label );
					if ( ! isset( $columns[ $key ] ) ) {
						$columns[ $key ] = $label;
					}
				}
			}

			return $columns;
		}

		private static function get_export_form_values( $row ) {
			$values = array();
			$fields = maybe_unserialize( isset( $row['form_data'] ) ? $row['form_data'] : '' );

			if ( ! is_array( $fields ) ) {
				return $values;
			}

			foreach ( $fields as $field ) {
				$label = isset( $field['label'] ) ? trim( (string) $field['label'] ) : '';
				$value = isset( $field['value'] ) ? $field['value'] : '';

				if ( '' === $label ) {
					continue;
				}

				if ( is_array( $value ) ) {
					$value = implode( ', ', array_map( 'sanitize_text_field', $value ) );
				} else {
					$value = (string) $value;
				}

				$key = self::get_export_form_column_key( $label );
				if ( isset( $values[ $key ] ) && '' !== $values[ $key ] ) {
					$values[ $key ] .= "\n" . $value;
				} else {
					$values[ $key ] = $value;
				}
			}

			return $values;
		}

		private static function get_export_form_column_key( $label ) {
			return md5( wp_strip_all_tags( trim( (string) $label ) ) );
		}

		private static function get_source_url( $source_url = '', $page_id = 0 ) {
			if ( ! empty( $source_url ) ) {
				$source_url = esc_url_raw( $source_url );
				if ( $page_id && self::is_root_url_with_query( $source_url ) ) {
					$query = wp_parse_url( $source_url, PHP_URL_QUERY );
					if ( $query ) {
						return esc_url_raw( get_permalink( absint( $page_id ) ) . '?' . $query );
					}
				}

				return $source_url;
			}

			$referer = wp_get_raw_referer();
			if ( ! empty( $referer ) ) {
				return esc_url_raw( $referer );
			}

			if ( $page_id ) {
				return esc_url_raw( get_permalink( absint( $page_id ) ) );
			}

			return home_url( add_query_arg( array(), isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '' ) );
		}

		private static function is_root_url_with_query( $url ) {
			$path = wp_parse_url( $url, PHP_URL_PATH );
			$query = wp_parse_url( $url, PHP_URL_QUERY );

			return ( '' === (string) $path || '/' === $path ) && ! empty( $query );
		}

		public static function get_row_source_url( $row ) {
			if ( ! empty( $row['source_url'] ) ) {
				return $row['source_url'];
			}

			if ( ! empty( $row['page_id'] ) ) {
				return get_permalink( absint( $row['page_id'] ) );
			}

			return '';
		}

		private static function write_csv_row( $output, $row ) {
			$escaped = array();

			foreach ( $row as $value ) {
				$value = str_replace( array( "\r\n", "\r" ), "\n", (string) $value );
				$value = str_replace( '"', '""', $value );
				$escaped[] = '"' . $value . '"';
			}

			fwrite( $output, implode( ',', $escaped ) . "\r\n" );
		}

		public function render_admin_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( '你没有权限访问此页面。', 'xintheme-pagebuilder' ) );
			}

			if ( ! self::is_enabled() ) {
				wp_die( esc_html__( '后台表单提交记录未启用。', 'xintheme-pagebuilder' ) );
			}

			self::maybe_create_table();

			$table = new XTPB_Form_Records_List_Table();
			$table->prepare_items();
			?>
			<div class="wrap xtpb-form-records-page">
				<h1><?php esc_html_e( '表单记录', 'xintheme-pagebuilder' ); ?></h1>

				<?php $this->render_filters(); ?>

				<form method="post">
					<input type="hidden" name="page" value="xtpb-form-records">
					<?php $table->display(); ?>
				</form>
			</div>
			<?php
		}

		private function render_filters() {
			$filters = self::get_request_filters();
			$status = $filters['status'];
			$page_id = $filters['page_id'];
			$sources = self::get_source_pages();
			$export_url = wp_nonce_url(
				add_query_arg(
					array(
						'action'         => 'xtpb_export_form_records',
						'status'         => $status,
						'source_page_id' => $page_id,
					),
					admin_url( 'admin-post.php' )
				),
				'xtpb_export_form_records'
			);
			?>
			<form method="get" class="xtpb-form-records-filters">
				<input type="hidden" name="page" value="xtpb-form-records">
				<select name="status">
					<option value=""><?php esc_html_e( '全部状态', 'xintheme-pagebuilder' ); ?></option>
					<?php foreach ( self::statuses() as $single_status ) : ?>
						<option value="<?php echo esc_attr( $single_status ); ?>" <?php selected( $status, $single_status ); ?>><?php echo esc_html( $single_status ); ?></option>
					<?php endforeach; ?>
				</select>

				<select name="source_page_id">
					<option value="0"><?php esc_html_e( '全部来源页面', 'xintheme-pagebuilder' ); ?></option>
					<?php foreach ( $sources as $source ) : ?>
						<option value="<?php echo esc_attr( $source['page_id'] ); ?>" <?php selected( $page_id, $source['page_id'] ); ?>><?php echo esc_html( $source['title'] ); ?></option>
					<?php endforeach; ?>
				</select>

				<?php submit_button( __( '筛选', 'xintheme-pagebuilder' ), '', 'filter_action', false ); ?>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=xtpb-form-records' ) ); ?>"><?php esc_html_e( '重置', 'xintheme-pagebuilder' ); ?></a>
				<a class="button button-primary" href="<?php echo esc_url( $export_url ); ?>"><?php esc_html_e( '导出', 'xintheme-pagebuilder' ); ?></a>
			</form>
			<?php
		}

		public static function get_source_pages() {
			global $wpdb;

			$rows = $wpdb->get_results( 'SELECT page_id, MAX(page_title) AS page_title FROM ' . self::table_name() . ' WHERE page_id > 0 GROUP BY page_id ORDER BY page_title ASC', ARRAY_A );
			$sources = array();

			foreach ( (array) $rows as $row ) {
				$title = $row['page_title'] ? $row['page_title'] : sprintf( __( '页面 #%d', 'xintheme-pagebuilder' ), absint( $row['page_id'] ) );
				$sources[] = array(
					'page_id' => absint( $row['page_id'] ),
					'title'   => $title,
				);
			}

			return $sources;
		}

		public function ajax_update_status() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'msg' => __( '权限不足。', 'xintheme-pagebuilder' ) ), 403 );
			}

			check_ajax_referer( 'xtpb_form_record_status', 'nonce' );

			$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
			$status = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : '';

			if ( ! $id || ! in_array( $status, self::statuses(), true ) ) {
				wp_send_json_error( array( 'msg' => __( '参数错误。', 'xintheme-pagebuilder' ) ) );
			}

			global $wpdb;
			$result = $wpdb->update( self::table_name(), array( 'status' => $status ), array( 'id' => $id ), array( '%s' ), array( '%d' ) );

			if ( false === $result ) {
				wp_send_json_error( array( 'msg' => __( '状态更新失败。', 'xintheme-pagebuilder' ) ) );
			}

			wp_send_json_success( array( 'msg' => __( '状态已更新。', 'xintheme-pagebuilder' ) ) );
		}

		public function admin_footer_assets() {
			$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
			if ( ! $screen || false === strpos( $screen->id, 'xtpb-form-records' ) ) {
				return;
			}
			?>
			<style>
				.xtpb-form-records-filters { margin: 12px 0; display: flex; gap: 8px; align-items: center; }
				.xtpb-form-records-page .column-cb { width: 36px; }
				.xtpb-form-records-page .column-status { width: 120px; }
				.xtpb-form-records-page .column-submitted_at { width: 160px; }
				.xtpb-form-records-page .column-source_url { width: 240px; }
				.xtpb-form-record-fields p { margin: 0 0 5px; }
				.xtpb-form-record-status { min-width: 96px; font-weight: 600; border-width: 1px; }
				.xtpb-form-record-status.is-pending { background-color: #fcf0f1; border-color: #d63638; color: #b32d2e; }
				.xtpb-form-record-status.is-processing { background-color: #fff8e5; border-color: #dba617; color: #8a5a00; }
				.xtpb-form-record-status.is-done { background-color: #edfaef; border-color: #00a32a; color: #007017; }
			</style>
			<script>
				jQuery(function($){
					function syncRecordStatusClass($select) {
						$select.removeClass('is-pending is-processing is-done');

						if ($select.val() === '<?php echo esc_js( self::STATUS_PENDING ); ?>') {
							$select.addClass('is-pending');
						} else if ($select.val() === '<?php echo esc_js( self::STATUS_PROCESSING ); ?>') {
							$select.addClass('is-processing');
						} else if ($select.val() === '<?php echo esc_js( self::STATUS_DONE ); ?>') {
							$select.addClass('is-done');
						}
					}

					$('.xtpb-form-record-status').each(function(){
						syncRecordStatusClass($(this));
					});

					$(document).on('change', '.xtpb-form-record-status', function(){
						var $select = $(this);
						syncRecordStatusClass($select);
						$select.prop('disabled', true);

						$.post(ajaxurl, {
							action: 'xtpb_form_record_update_status',
							id: $select.data('id'),
							status: $select.val(),
							nonce: '<?php echo esc_js( wp_create_nonce( 'xtpb_form_record_status' ) ); ?>'
						}).always(function(){
							$select.prop('disabled', false);
						});
					});
				});
			</script>
			<?php
		}
	}
}

if ( is_admin() && class_exists( 'WP_List_Table' ) && ! class_exists( 'XTPB_Form_Records_List_Table' ) ) {
	class XTPB_Form_Records_List_Table extends WP_List_Table {
		public function __construct() {
			parent::__construct(
				array(
					'singular' => 'xtpb_form_record',
					'plural'   => 'xtpb_form_records',
					'ajax'     => false,
				)
			);
		}

		public function get_columns() {
			return array(
				'cb'           => '<input type="checkbox" />',
				'submitted_at' => __( '提交时间', 'xintheme-pagebuilder' ),
				'source_url'   => __( '来源页面', 'xintheme-pagebuilder' ),
				'form_content' => __( '提交内容', 'xintheme-pagebuilder' ),
				'status'       => __( '状态', 'xintheme-pagebuilder' ),
				'ip_address'   => __( 'IP', 'xintheme-pagebuilder' ),
			);
		}

		protected function get_sortable_columns() {
			return array(
				'submitted_at' => array( 'submitted_at', false ),
				'status'       => array( 'status', false ),
			);
		}

		protected function get_bulk_actions() {
			return array(
				'export' => __( '导出', 'xintheme-pagebuilder' ),
				'delete' => __( '删除', 'xintheme-pagebuilder' ),
			);
		}

		public function prepare_items() {
			global $wpdb;

			$this->process_bulk_action();

			$per_page = (int) get_user_option( XTPB_Form_Records::SCREEN_PER_PAGE_OPTION );
			if ( $per_page < 1 ) {
				$per_page = 20;
			}
			$current_page = $this->get_pagenum();
			$where_sql = XTPB_Form_Records::get_where_sql( XTPB_Form_Records::get_request_filters() );
			$table_name = XTPB_Form_Records::table_name();
			$total_items = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table_name} {$where_sql}" );
			$offset = ( $current_page - 1 ) * $per_page;

			$this->items = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$table_name} {$where_sql} ORDER BY FIELD(status, %s, %s, %s), submitted_at DESC LIMIT %d OFFSET %d",
					XTPB_Form_Records::STATUS_PENDING,
					XTPB_Form_Records::STATUS_PROCESSING,
					XTPB_Form_Records::STATUS_DONE,
					$per_page,
					$offset
				),
				ARRAY_A
			);

			$hidden = get_hidden_columns( get_current_screen() );
			$this->_column_headers = array( $this->get_columns(), $hidden, $this->get_sortable_columns() );
			$this->set_pagination_args(
				array(
					'total_items' => $total_items,
					'per_page'    => $per_page,
				)
			);
		}

		public function process_bulk_action() {
			$action = $this->current_action();
			if ( 'delete' !== $action || empty( $_POST['record_ids'] ) ) {
				return;
			}

			check_admin_referer( 'bulk-' . $this->_args['plural'] );

			global $wpdb;
			$ids = wp_parse_id_list( wp_unslash( $_POST['record_ids'] ) );

			if ( empty( $ids ) ) {
				return;
			}

			$wpdb->query( 'DELETE FROM ' . XTPB_Form_Records::table_name() . ' WHERE id IN (' . implode( ',', $ids ) . ')' );
		}

		protected function column_cb( $item ) {
			return '<input type="checkbox" name="record_ids[]" value="' . esc_attr( $item['id'] ) . '" />';
		}

		protected function column_submitted_at( $item ) {
			return esc_html( $item['submitted_at'] );
		}

		protected function column_source_url( $item ) {
			$title = $item['page_title'] ? $item['page_title'] : sprintf( __( '页面 #%d', 'xintheme-pagebuilder' ), absint( $item['page_id'] ) );
			$url = XTPB_Form_Records::get_row_source_url( $item );

			if ( $url ) {
				return '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $url ) . '</a><br><small>' . esc_html( $title ) . '</small>';
			}

			return esc_html( $title );
		}

		protected function column_form_content( $item ) {
			$fields = maybe_unserialize( $item['form_data'] );
			if ( ! is_array( $fields ) ) {
				return '';
			}

			$output = '<div class="xtpb-form-record-fields">';
			foreach ( $fields as $field ) {
				$label = isset( $field['label'] ) ? $field['label'] : '';
				$value = isset( $field['value'] ) ? $field['value'] : '';
				$output .= '<p><strong>' . esc_html( $label ) . '：</strong>' . nl2br( esc_html( $value ) ) . '</p>';
			}
			$output .= '</div>';

			return $output;
		}

		protected function column_status( $item ) {
			$status_class = 'is-pending';
			if ( XTPB_Form_Records::STATUS_PROCESSING === $item['status'] ) {
				$status_class = 'is-processing';
			} elseif ( XTPB_Form_Records::STATUS_DONE === $item['status'] ) {
				$status_class = 'is-done';
			}

			$output = '<select class="xtpb-form-record-status ' . esc_attr( $status_class ) . '" data-id="' . esc_attr( $item['id'] ) . '">';
			foreach ( XTPB_Form_Records::statuses() as $status ) {
				$output .= '<option value="' . esc_attr( $status ) . '" ' . selected( $item['status'], $status, false ) . '>' . esc_html( $status ) . '</option>';
			}
			$output .= '</select>';

			return $output;
		}

		protected function column_ip_address( $item ) {
			return esc_html( $item['ip_address'] );
		}

		protected function column_default( $item, $column_name ) {
			return isset( $item[ $column_name ] ) ? esc_html( $item[ $column_name ] ) : '';
		}
	}
}
