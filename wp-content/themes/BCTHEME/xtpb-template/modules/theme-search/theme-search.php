<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Search {

	public function get_name() {
		return 'theme_search';
	}

	public function get_title() {
		return '搜索词+搜索框';
	}

	public function get_class() {
		return 'xtpb-theme-search';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-search/assets/css/frontend.css',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-search/assets/img/modules-search.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'module_title' => array(
				'type'    => 'text',
				'title'   => '模块标题',
				'std'     => '热门搜索：',
				'section' => '模块设置',
			),
			'search' => array(
				'type'    => 'switch',
				'title'   => '显示搜索框',
				'desc'    => '在模块右侧显示搜索框',
				'std'     => 1,
				'section' => '模块设置',
			),
			'search_items' => array(
				'type'    => 'repeatable',
				'title'   => '搜索关键词',
				'label'   => 'text',
				'section' => '内容设置',
				'std'     => array(
					array(
						'text' => '企业建站',
						'url'  => '#',
					),
				),
				'attr'    => array(
					'text' => array(
						'type'  => 'text',
						'title' => '关键词',
						'std'   => '企业建站',
					),
					'url' => array(
						'type'  => 'text',
						'title' => '跳转链接',
						'std'   => '#',
					),
				),
			),
		);
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items    = isset( $settings['search_items'] ) && is_array( $settings['search_items'] ) ? $settings['search_items'] : array();

		if ( empty( $items ) ) {
			return '';
		}

		$title       = ! empty( $settings['module_title'] ) ? sanitize_text_field( $settings['module_title'] ) : '';
		$show_search = ! empty( $settings['search'] );

		ob_start();
		?>
		<div class="module-theme-search">
			<div class="container clearfix">
				<div class="hot">
					<?php if ( $title ) : ?>
						<strong><?php echo esc_html( $title ); ?></strong>
					<?php endif; ?>
					<?php foreach ( $items as $item ) : ?>
						<?php
						$item = is_array( $item ) ? $item : array();
						$text = ! empty( $item['text'] ) ? sanitize_text_field( $item['text'] ) : '';
						$url  = ! empty( $item['url'] ) ? $item['url'] : '';
						?>
						<?php if ( $text ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $text ); ?></a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php if ( $show_search ) : ?>
					<div class="hform">
						<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<label class="screen-reader-text" for="xtpb-theme-search-<?php echo esc_attr( $this->get_instance_id( $data ) ); ?>"><?php esc_html_e( '搜索：', 'modular_theme' ); ?></label>
							<input id="xtpb-theme-search-<?php echo esc_attr( $this->get_instance_id( $data ) ); ?>" name="s" type="search" placeholder="<?php esc_attr_e( '输入关键词进行搜索...', 'modular_theme' ); ?>">
							<button type="submit" aria-label="<?php esc_attr_e( '搜索', 'modular_theme' ); ?>"><i class="ri-search-line" aria-hidden="true"></i></button>
						</form>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private function get_instance_id( $data ) {
		if ( isset( $data['id'] ) ) {
			return sanitize_html_class( (string) $data['id'] );
		}

		return 'preview';
	}

	public function getTemplate() {
		return '
		<#
		var items = _.isArray(data.search_items) ? data.search_items : [];
		var title = data.module_title || "";
		var showSearch = !!data.search;
		#>
		<# if (items.length) { #>
			<div class="module-theme-search">
				<div class="container clearfix">
					<div class="hot">
						<# if (title) { #>
							<strong>{{ title }}</strong>
						<# } #>
						<# _.forEach(items, function(item) { item = item || {}; #>
							<# if (item.text) { #>
								<a href="{{ item.url || "" }}" target="_blank" rel="noopener">{{ item.text }}</a>
							<# } #>
						<# }); #>
					</div>
					<# if (showSearch) { #>
						<div class="hform">
							<form role="search" method="get" action="' . esc_url( home_url( '/' ) ) . '">
								<label class="screen-reader-text">' . esc_html__( '搜索：', 'modular_theme' ) . '</label>
								<input name="s" type="search" placeholder="' . esc_attr__( '输入关键词进行搜索...', 'modular_theme' ) . '">
								<button type="submit" aria-label="' . esc_attr__( '搜索', 'modular_theme' ) . '"><i class="ri-search-line" aria-hidden="true"></i></button>
							</form>
						</div>
					<# } #>
				</div>
			</div>
		<# } #>
		';
	}
}
