<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Breadcrumb {

	public function get_name() {
		return 'theme_breadcrumb';
	}

	public function get_title() {
		return '面包屑导航+搜索框';
	}

	public function get_class() {
		return 'xtpb-theme-breadcrumb';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-breadcrumb/assets/css/frontend.css',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-breadcrumb/assets/img/modules-breadcrumb.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'search' => array(
				'type'    => 'switch',
				'title'   => '显示搜索框',
				'desc'    => '在模块右侧显示搜索框',
				'std'     => 1,
				'section' => '模块设置',
			),
		);
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();

		ob_start();
		?>
		<div class="module-theme-breadcrumb">
			<div class="breadcrumb-search">
				<div class="container">
					<?php if ( function_exists( 'get_breadcrumbs' ) ) : ?>
						<?php get_breadcrumbs(); ?>
					<?php endif; ?>
					<?php if ( ! empty( $settings['search'] ) ) : ?>
						<div class="search-form">
							<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<label class="screen-reader-text" for="xtpb-breadcrumb-search-<?php echo esc_attr( $this->get_instance_id( $data ) ); ?>"><?php esc_html_e( '搜索：', 'modular_theme' ); ?></label>
								<input id="xtpb-breadcrumb-search-<?php echo esc_attr( $this->get_instance_id( $data ) ); ?>" name="s" type="search" placeholder="<?php esc_attr_e( '输入关键词进行搜索...', 'modular_theme' ); ?>">
								<button type="submit" aria-label="<?php esc_attr_e( '搜索', 'modular_theme' ); ?>"><i class="ri-search-line" aria-hidden="true"></i></button>
							</form>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private function get_instance_id( $data ) {
		return isset( $data['id'] ) ? sanitize_html_class( (string) $data['id'] ) : 'preview';
	}

	public function getTemplate() {
		return '
		<div class="module-theme-breadcrumb">
			<div class="breadcrumb-search"><div class="container">
				<div class="breadcrumb"><i class="ri-map-pin-fill"></i> <strong>' . esc_html__( '当前位置：', 'modular_theme' ) . '</strong><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( '首页', 'modular_theme' ) . '</a><i class="ri-arrow-right-s-line"></i><span>' . esc_html__( '当前页面', 'modular_theme' ) . '</span></div>
				<# if (data.search) { #><div class="search-form"><form role="search" method="get" action="' . esc_url( home_url( '/' ) ) . '"><label class="screen-reader-text">' . esc_html__( '搜索：', 'modular_theme' ) . '</label><input name="s" type="search" placeholder="' . esc_attr__( '输入关键词进行搜索...', 'modular_theme' ) . '"><button type="submit" aria-label="' . esc_attr__( '搜索', 'modular_theme' ) . '"><i class="ri-search-line" aria-hidden="true"></i></button></form></div><# } #>
			</div></div>
		</div>';
	}
}
