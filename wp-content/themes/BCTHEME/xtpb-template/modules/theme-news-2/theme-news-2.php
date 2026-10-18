<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_News_2 {

	public function get_name() {
		return 'theme_news_2';
	}

	public function get_title() {
		return '新闻资讯-2';
	}

	public function get_class() {
		return 'xtpb-theme-news-2';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-news-2/assets/css/frontend.css',
		);
	}

	public function get_js_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-news-2/assets/js/frontend.js',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-news-2/assets/img/modules-news-2.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'module_title' => array(
				'type'    => 'text',
				'title'   => '模块标题',
				'std'     => '新闻资讯',
				'section' => '模块设置',
			),
			'module_desc' => array(
				'type'    => 'textarea',
				'title'   => '模块简介',
				'std'     => '',
				'section' => '模块设置',
			),
			'post_per_page' => array(
				'type'    => 'number',
				'title'   => '文章数量',
				'desc'    => '每个分类显示的文章数量，默认 6 篇',
				'std'     => 6,
				'section' => '模块设置',
			),
			'cat_url' => array(
				'type'    => 'switch',
				'title'   => '显示查看更多',
				'desc'    => '跳转到当前分类的文章列表',
				'std'     => 0,
				'section' => '模块设置',
			),
			'category_items' => array(
				'type'    => 'repeatable',
				'title'   => '新闻分类',
				'label'   => 'cat_id',
				'section' => '内容设置',
				'std'     => array( array( 'cat_id' => '' ) ),
				'attr'    => array(
					'cat_id' => array(
						'type'   => 'select',
						'title'  => '分类目录',
						'values' => $this->category_options(),
					),
				),
			),
		);
	}

	private function category_options() {
		$options = array( '' => '请选择分类' );

		foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) {
			$options[ (string) $category->term_id ] = $category->name;
		}

		return $options;
	}

	public function render( $data = null ) {
		$settings   = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items      = isset( $settings['category_items'] ) && is_array( $settings['category_items'] ) ? $settings['category_items'] : array();
		$categories = $this->valid_categories( $items );

		if ( empty( $categories ) ) {
			return '';
		}

		$title    = ! empty( $settings['module_title'] ) ? sanitize_text_field( $settings['module_title'] ) : '';
		$desc     = ! empty( $settings['module_desc'] ) ? sanitize_textarea_field( $settings['module_desc'] ) : '';
		$per_page = ! empty( $settings['post_per_page'] ) ? absint( $settings['post_per_page'] ) : 6;

		ob_start();
		?>
		<div class="module-theme-news-2">
			<div class="section">
				<div class="container">
					<?php if ( $title || $desc ) : ?>
						<div class="section-title">
							<?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?>
							<?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( count( $categories ) > 1 ) : ?>
						<ul class="tab-post-menu mb-tab-menu">
							<?php foreach ( $categories as $index => $category ) : ?>
								<li<?php echo 0 === $index ? ' class="active"' : ''; ?>><button type="button"><?php echo esc_html( $category->name ); ?></button></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<div class="swiper theme-news-2-slider">
						<div class="swiper-wrapper">
							<?php foreach ( $categories as $category ) : ?>
								<div class="swiper-slide"><div class="md-news">
									<?php $this->render_category_posts( $category, $per_page ); ?>
									<?php if ( ! empty( $settings['cat_url'] ) ) : ?><a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="cat-url"><?php esc_html_e( '查看更多', 'modular_theme' ); ?></a><?php endif; ?>
								</div></div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}

	private function valid_categories( $items ) {
		$categories = array();

		foreach ( $items as $item ) {
			$cat_id   = is_array( $item ) && ! empty( $item['cat_id'] ) ? absint( $item['cat_id'] ) : 0;
			$category = $cat_id ? get_category( $cat_id ) : null;

			if ( $category && ! is_wp_error( $category ) ) {
				$categories[] = $category;
			}
		}

		return $categories;
	}

	private function render_category_posts( $category, $per_page ) {
		$query = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => $per_page,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
				'cat'                 => $category->term_id,
			)
		);

		if ( ! $query->have_posts() ) {
			echo '<p class="news-empty">' . esc_html__( '暂无相关内容', 'modular_theme' ) . '</p>';
			return;
		}

		while ( $query->have_posts() ) {
			$query->the_post();
			?>
			<a href="<?php the_permalink(); ?>" class="news-itme">
				<?php $thumbnail_url = wpjam_get_post_thumbnail_url(); ?>
				<?php if ( $thumbnail_url ) : ?><div class="itme-img"><img loading="lazy" src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"></div><?php endif; ?>
				<div class="itme-text">
					<h2><?php the_title(); ?></h2>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 55, '...' ) ); ?></p>
				</div>
			</a>
			<?php
		}

		wp_reset_postdata();
	}

}
