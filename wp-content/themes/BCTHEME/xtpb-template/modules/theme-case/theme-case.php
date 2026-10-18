<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Case {

	public function get_name() {
		return 'theme_case';
	}

	public function get_title() {
		return '案例展示';
	}

	public function get_class() {
		return 'xtpb-theme-case';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-case/assets/css/frontend.css',
		);
	}

	public function get_js_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-case/assets/js/frontend.js',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-case/assets/img/modules-case.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'module_title' => array(
				'type'    => 'text',
				'title'   => '模块标题',
				'std'     => '案例展示',
				'section' => '模块设置',
			),
			'module_desc' => array(
				'type'    => 'textarea',
				'title'   => '模块简介',
				'std'     => '',
				'section' => '模块设置',
			),
			'cat_id' => array(
				'type'    => 'select',
				'title'   => '文章分类',
				'values'  => $this->category_options(),
				'std'     => '',
				'section' => '内容设置',
			),
			'post_per_page' => array(
				'type'    => 'number',
				'title'   => '文章数量',
				'desc'    => '默认显示 6 篇',
				'std'     => 6,
				'section' => '内容设置',
			),
			'post_thumb_ratio' => array(
				'type'    => 'select',
				'title'   => '缩略图比例',
				'values'  => array(
					'ratio-1-1'  => '1:1',
					'ratio-4-3'  => '4:3',
					'ratio-3-2'  => '3:2',
					'ratio-16-9' => '16:9',
					'ratio-21-9' => '21:9',
				),
				'std'     => 'ratio-4-3',
				'section' => '模块设置',
			),
			'cat_url' => array(
				'type'    => 'switch',
				'title'   => '显示查看更多',
				'desc'    => '跳转到所选分类的文章列表',
				'std'     => 0,
				'section' => '导航设置',
			),
			'cat_next_prev' => array(
				'type'    => 'switch',
				'title'   => '显示切换按钮',
				'std'     => 0,
				'section' => '导航设置',
			),
			'cat_pagination' => array(
				'type'    => 'switch',
				'title'   => '显示分页按钮',
				'std'     => 0,
				'section' => '导航设置',
			),
		);
	}

	private function category_options() {
		$options    = array( '' => '全部分类' );
		$categories = get_categories( array( 'hide_empty' => false ) );

		foreach ( $categories as $category ) {
			$options[ (string) $category->term_id ] = $category->name;
		}

		return $options;
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$cat_id   = ! empty( $settings['cat_id'] ) ? absint( $settings['cat_id'] ) : 0;
		$per_page = ! empty( $settings['post_per_page'] ) ? absint( $settings['post_per_page'] ) : 6;
		$args      = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $per_page,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		if ( $cat_id ) {
			$args['cat'] = $cat_id;
		}

		$query = new WP_Query( $args );
		$title = ! empty( $settings['module_title'] ) ? sanitize_text_field( $settings['module_title'] ) : '';
		$desc  = ! empty( $settings['module_desc'] ) ? sanitize_textarea_field( $settings['module_desc'] ) : '';
		$ratio = isset( $settings['post_thumb_ratio'] ) ? $settings['post_thumb_ratio'] : 'ratio-4-3';
		$ratio = in_array( $ratio, array( 'ratio-1-1', 'ratio-4-3', 'ratio-3-2', 'ratio-16-9', 'ratio-21-9' ), true ) ? $ratio : 'ratio-4-3';

		ob_start();
		?>
		<div class="module-theme-case <?php echo esc_attr( $ratio ); ?>">
			<div class="section">
				<div class="container">
					<?php if ( $title || $desc ) : ?>
						<div class="section-title">
							<?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?>
							<?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $query->have_posts() ) : ?>
						<div class="swiper md-case<?php echo ! empty( $settings['cat_pagination'] ) ? ' show-pagination' : ''; ?>">
							<div class="swiper-wrapper">
								<?php while ( $query->have_posts() ) : $query->the_post(); ?>
									<div class="swiper-slide">
									<div class="case-item">
										<a href="<?php the_permalink(); ?>">
											<?php $thumbnail_url = wpjam_get_post_thumbnail_url(); ?>
											<?php if ( $thumbnail_url ) : ?>
												<div class="img-cover"><img loading="lazy" src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"></div>
											<?php endif; ?>
												<div class="text">
													<h2><?php the_title(); ?></h2>
													<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 36, '...' ) ); ?></p>
												</div>
											</a>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						</div>
						<?php if ( ! empty( $settings['cat_pagination'] ) ) : ?><div class="swiper-pagination"></div><?php endif; ?>
						<?php if ( ! empty( $settings['cat_next_prev'] ) ) : ?>
							<div class="swiper-button-next"></div><div class="swiper-button-prev"></div>
						<?php endif; ?>
					<?php else : ?>
						<p class="case-empty"><?php esc_html_e( '暂无相关内容', 'modular_theme' ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $settings['cat_url'] ) && $cat_id ) : ?>
						<div class="case-more"><a href="<?php echo esc_url( get_category_link( $cat_id ) ); ?>" class="cat-url"><?php esc_html_e( '查看更多', 'modular_theme' ); ?></a></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		wp_reset_postdata();

		return ob_get_clean();
	}

}
