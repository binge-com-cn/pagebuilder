<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('XTPB_Options')){

	class XTPB_Options{
		// Constructor
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}

	public function xintheme_ico(){
		return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxODAgMTgwIj48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6I2EwYTVhYTt9PC9zdHlsZT48L2RlZnM+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTEzIDQwSDE0OFY3MUgxMzZWNTVIMTEzWiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTExNCAxMTZIMTM2VjEwNUgxNDhWMTQxSDExNFoiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTM2IDQwSDg4QzExMCA0MCAxMjQgNTAgMTI0IDY3QzEyNCA3NyAxMTkgODQgMTEwIDg5QzEyMSA5MyAxMjggMTAyIDEyOCAxMTNDMTI4IDEzMCAxMTMgMTQxIDg5IDE0MUgzNlYxMTNINTJWMTI2SDg4QzEwMSAxMjYgMTA5IDEyMSAxMDkgMTEyQzEwOSAxMDMgMTAxIDk4IDg4IDk4SDc4Vjg0SDg4QzEwMCA4NCAxMDYgNzkgMTA2IDcwQzEwNiA2MCA5OSA1NSA4NyA1NUg1MlY4MUgzNlpNNjggNjdIOTRDODggNzUgODQgODQgODQgOTNDODQgMTAyIDg4IDExMCA5NCAxMTZINzBDNjQgMTA4IDYxIDk5IDYxIDkxQzYxIDgyIDY0IDczIDY4IDY3WiIvPjwvc3ZnPg==';
	}
	

		/**
		 * Add Menu Page Callback
		 *
		 * @since 1.0.0
		 */
		public function add_admin_menu() {
			add_menu_page(
				esc_html__( '页面构建器', 'xintheme-pagebuilder' ),
				esc_html__( '页面构建器', 'xintheme-pagebuilder' ),
				'manage_options',
				'xtpb-settings',
				array( $this, 'create_admin_page' ),
				$this->xintheme_ico()
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * @since 1.0.0
		 */
		public function register_settings() {
			register_setting( 'xtpb_options', 'xtpb_options', array( $this, 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {
			if ( $options ) {
				if ( ! empty( $options['css_save_as'] ) ) {
					$options['css_save_as'] = sanitize_text_field( $options['css_save_as'] );
				}
				if ( ! empty( $options['js_save_as'] ) ) {
					$options['js_save_as'] = sanitize_text_field( $options['js_save_as'] );
				}
				if ( isset( $options['xtpb_container_width'] ) ) {
					$options['xtpb_container_width'] = sanitize_text_field( $options['xtpb_container_width'] );
				}
				if ( isset( $options['xtpb_container_side_gap'] ) ) {
					$options['xtpb_container_side_gap'] = sanitize_text_field( $options['xtpb_container_side_gap'] );
				}
				if ( isset( $options['xtpb_container_side_gap_mobile'] ) ) {
					$options['xtpb_container_side_gap_mobile'] = sanitize_text_field( $options['xtpb_container_side_gap_mobile'] );
				}
				if ( isset( $options['xtpb_col_spacing'] ) ) {
					$options['xtpb_col_spacing'] = sanitize_text_field( $options['xtpb_col_spacing'] );
				}
				if ( ! empty( $options['supported_post_type'] ) ) {
					$options['supported_post_type'] = $options['supported_post_type'];
				}
				$options['google_fonts_enable'] = ! empty( $options['google_fonts_enable'] ) ? '1' : '0';
				$options['google_fonts_local']  = ! empty( $options['google_fonts_local'] ) ? '1' : '0';
				$options['form_records_enable'] = ! empty( $options['form_records_enable'] ) ? '1' : '0';
			}
			return $options;
		}

		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function create_admin_page() { ?>
            <div class="wrap">
                <h1><?php esc_html_e( '页面构建器选项', 'xintheme-pagebuilder' ); ?></h1>
				<?php
				if ( isset( $_GET['settings-updated'] ) && 'true' === $_GET['settings-updated'] ) {
					add_settings_error( 'xtpb_options', 'xtpb_options_updated', __( '设置已保存。', 'xintheme-pagebuilder' ), 'updated' );
				}
				?>
				<?php settings_errors( 'xtpb_options' ); ?>

                <form method="post" action="options.php">
					<?php
					settings_fields( 'xtpb_options' );
					$option_data    = get_option( 'xtpb_options' );
					$post_types     = get_post_types( [ 'public' => true ], 'objects' );
					if( isset($post_types['attachment']) ){ unset( $post_types['attachment'] ); }

					$user_roles = get_editable_roles();
					?>

                    <table class="form-table wpex-custom-admin-login-table">

                        <tr>
                            <th scope="row"><?php esc_html_e( '文章类型', 'xintheme-pagebuilder' ); ?></th>
                            <td>
								<?php
								$value = xtpb_get_option('supported_post_type');
								if( $post_types ){
									foreach( $post_types as $post_type ){ ?>
                                        <label>
                                            <input type="checkbox" name="xtpb_options[supported_post_type][]" value="<?php echo $post_type->name; ?>" <?php if( $value ){ if(in_array( $post_type->name, $value )){ echo 'checked'; } } ?> >
                                            <?php echo $post_type->label; ?><br>
                                        </label>
									<?php }
								}
								?>

                                <p class="description"> <?php _e('选择可使用页面构建器编辑的文章类型。', 'xintheme-pagebuilder'); ?></p>
                            </td>
                        </tr>

						<tr>
							<th scope="row"><?php esc_html_e( '允许的用户角色', 'xintheme-pagebuilder' ); ?></th>
							<td>
								<?php
								$included_user_roles = xtpb_get_option( 'include_role' );

								if ( ! is_array( $included_user_roles ) ) {
									$included_user_roles = array();
								}

								if ( ! empty( $user_roles ) ) {
									foreach ( $user_roles as $user_slug => $single_role ) {

										$role_name = isset( $single_role['name'] ) ? $single_role['name'] : $user_slug;

										// 将 WordPress 默认角色名称翻译成当前语言，例如 Administrator -> 管理员
										$role_label = translate_user_role( $role_name );
										?>
										
										<label>
											<input
												type="checkbox"
												name="xtpb_options[include_role][]"
												value="<?php echo esc_attr( $user_slug ); ?>"
												<?php checked( in_array( $user_slug, $included_user_roles, true ) ); ?>
											>
											<?php echo esc_html( $role_label ); ?>
										</label>
										<br>

										<?php
									}
								}
								?>

								<p class="description">
									<?php esc_html_e( '选择允许使用页面构建器编辑本站内容的用户角色。', 'xintheme-pagebuilder' ); ?>
								</p>
							</td>
						</tr>

                        <tr>
                            <th scope="row"><?php _e('内容宽度', 'xintheme-pagebuilder'); ?></th>
                            <td>
                                <input type="text" class="regular-text" id="xtpb_container_width" name="xtpb_options[xtpb_container_width]" value="<?php echo xtpb_get_option('xtpb_container_width'); ?>" placeholder="1140"> px
                                <p class="description"> <?php _e('默认内容宽度为 1140px。你可以设置任意宽度。','xintheme-pagebuilder'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php _e('容器左右间距（PC）', 'xintheme-pagebuilder'); ?></th>
                            <td>
                                <input type="text" class="regular-text" id="xtpb_container_side_gap" name="xtpb_options[xtpb_container_side_gap]" value="<?php echo esc_attr( xtpb_get_option('xtpb_container_side_gap') ); ?>" placeholder="20"> px
                                <p class="description"> <?php _e('用于控制内容容器在 PC 端左右保留的安全留白，默认 20px。','xintheme-pagebuilder'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php _e('容器左右间距（手机）', 'xintheme-pagebuilder'); ?></th>
                            <td>
                                <input type="text" class="regular-text" id="xtpb_container_side_gap_mobile" name="xtpb_options[xtpb_container_side_gap_mobile]" value="<?php echo esc_attr( xtpb_get_option('xtpb_container_side_gap_mobile') ); ?>" placeholder="10"> px
                                <p class="description"> <?php _e('用于控制手机端内容容器左右保留的安全留白，默认 10px。','xintheme-pagebuilder'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php _e('列间距', 'xintheme-pagebuilder'); ?></th>
                            <td>
                                <input type="text" class="regular-text" id="xtpb_col_spacing" name="xtpb_options[xtpb_col_spacing]" value="<?php echo xtpb_get_option('xtpb_col_spacing'); ?>" placeholder="30"> px
                                <p class="description"> <?php _e('默认间距为 30px。你可以设置任意间距。','xintheme-pagebuilder'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php esc_html_e( 'CSS 保存方式', 'xintheme-pagebuilder' ); ?></th>
                            <td>
								<?php $value = $option_data['css_save_as']; ?>
                                <select name="xtpb_options[css_save_as]">
									<?php
									$options = array(
										'filesystem' => __( '文件系统','xintheme-pagebuilder' ),
										'wp_head'   => __( '页头','xintheme-pagebuilder' ),
									);
									foreach ( $options as $id => $label ) { ?>
                                        <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $value, $id, true ); ?>>
											<?php echo strip_tags( $label ); ?>
                                        </option>
									<?php } ?>
                                </select>
                                <p class="description"> <?php _e('选择 CSS 的保存位置。', 'xintheme-pagebuilder'); ?></p>
                            </td>
						</tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'JS 保存方式', 'xintheme-pagebuilder' ); ?></th>
                            <td>
								<?php $js_value = ! empty( $option_data['js_save_as'] ) ? $option_data['js_save_as'] : ( ! empty( $option_data['css_save_as'] ) ? $option_data['css_save_as'] : 'filesystem' ); ?>
                                <select name="xtpb_options[js_save_as]">
									<?php
									$options = array(
										'filesystem' => __( '文件系统','xintheme-pagebuilder' ),
										'inline'     => __( '内联（页脚）','xintheme-pagebuilder' ),
									);
									foreach ( $options as $id => $label ) { ?>
                                        <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $js_value, $id, true ); ?>>
											<?php echo strip_tags( $label ); ?>
                                        </option>
									<?php } ?>
                                </select>
                                <p class="description"> <?php _e('选择页面级 JS 的输出方式。', 'xintheme-pagebuilder'); ?></p>
                            </td>
						</tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( '启用 Google Fonts', 'xintheme-pagebuilder' ); ?></th>
                            <td>
								<?php $google_fonts_enable = array_key_exists( 'google_fonts_enable', (array) $option_data ) ? $option_data['google_fonts_enable'] : '1'; ?>
                                <label>
                                    <input type="checkbox" id="xtpb_google_fonts_enable" name="xtpb_options[google_fonts_enable]" value="1" <?php checked( $google_fonts_enable, '1' ); ?>>
									<?php esc_html_e( '允许编辑器预览和前台页面加载 Google Fonts。', 'xintheme-pagebuilder' ); ?>
                                </label>
                                <p class="description"><?php _e( '关闭后，字体字段仍可保存，但不会自动加载 Google Fonts 文件。', 'xintheme-pagebuilder' ); ?></p>
                            </td>
                        </tr>
                        <tr id="xtpb_google_fonts_local_row" <?php if ( '1' !== (string) $google_fonts_enable ) { echo 'style="display:none;"'; } ?>>
                            <th scope="row"><?php esc_html_e( 'Google Fonts 本地化', 'xintheme-pagebuilder' ); ?></th>
                            <td>
								<?php $google_fonts_local = ! empty( $option_data['google_fonts_local'] ) ? $option_data['google_fonts_local'] : '0'; ?>
                                <label>
                                    <input type="checkbox" name="xtpb_options[google_fonts_local]" value="1" <?php checked( $google_fonts_local, '1' ); ?>>
									<?php esc_html_e( '将前台页面使用的 Google Fonts CSS 和字体文件保存到本地 uploads 目录。', 'xintheme-pagebuilder' ); ?>
                                </label>
                                <p class="description"><?php _e( '仅在启用 Google Fonts 时生效。编辑器实时预览仍可能直接请求远程字体。', 'xintheme-pagebuilder' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( '表单提交记录', 'xintheme-pagebuilder' ); ?></th>
                            <td>
								<?php $form_records_enable = ! empty( $option_data['form_records_enable'] ) ? $option_data['form_records_enable'] : '0'; ?>
                                <label>
                                    <input type="checkbox" name="xtpb_options[form_records_enable]" value="1" <?php checked( $form_records_enable, '1' ); ?>>
									<?php esc_html_e( '启用后台表单提交记录。', 'xintheme-pagebuilder' ); ?>
                                </label>
                                <p class="description"><?php _e( '关闭后不会保存新的表单提交记录，也不会显示“表单记录”后台入口。', 'xintheme-pagebuilder' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( '缓存', 'xintheme-pagebuilder' ); ?></th>
                            <td>
                                <label>
                                    <button type="button" id="xtpb_clear_cache_btn" class="button"><?php _e('清理缓存并同步','xintheme-pagebuilder'); ?></button>
                                    <button type="button" id="xtpb_rebuild_page_assets_btn" class="button button-secondary"><?php _e('重新生成页面资源','xintheme-pagebuilder'); ?></button>
                                    <p class="response-text"></p>
									<p class="description"> <?php _e('清理页面构建器缓存，并与服务器同步内置布局和区块。', 'xintheme-pagebuilder'); ?></p>
									<p class="description"> <?php _e('重新生成所有使用页面构建器编辑页面的 CSS 和 JS 文件。', 'xintheme-pagebuilder'); ?></p>
                                </label>
                            </td>
                        </tr>


                    </table>
					<?php submit_button(); ?>
                </form>
            </div>
            <script>
                (function() {
                    var enableCheckbox = document.getElementById('xtpb_google_fonts_enable');
                    var localRow = document.getElementById('xtpb_google_fonts_local_row');

                    if (!enableCheckbox || !localRow) {
                        return;
                    }

                    function syncGoogleFontsOptions() {
                        localRow.style.display = enableCheckbox.checked ? '' : 'none';
                    }

                    enableCheckbox.addEventListener('change', syncGoogleFontsOptions);
                    syncGoogleFontsOptions();
                }());
            </script>
		<?php }
	}


}
