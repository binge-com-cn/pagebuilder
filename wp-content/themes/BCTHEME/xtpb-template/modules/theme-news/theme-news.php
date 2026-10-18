<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_News {

	public function get_name() {
		return 'theme_news';
	}

	public function get_title() {
		return '新闻资讯';
	}

	public function get_class() {
		return 'xtpb-theme-news';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-news/assets/css/frontend.css',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-news/assets/img/modules-news.jpg';
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
			'cat_style' => array(
				'type'    => 'select',
				'title'   => '显示列数',
				'values'  => array(
					'style_1' => '两列',
					'style_2' => '三列',
				),
				'std'     => 'style_2',
				'section' => '模块设置',
			),
			'post_time' => array(
				'type'    => 'switch',
				'title'   => '显示发布时间',
				'std'     => 0,
				'section' => '模块设置',
			),
			'post_per_page' => array(
				'type'    => 'number',
				'title'   => '文章数量',
				'desc'    => '每个分类显示的文章数量，默认 6 篇',
				'std'     => 6,
				'section' => '模块设置',
			),
			'category_items' => array(
				'type'    => 'repeatable',
				'title'   => '新闻分类',
				'label'   => 'cat_id',
				'section' => '内容设置',
				'std'     => array(
					array(
						'cat_id'  => '',
						'cat_img' => array( 'url' => '' ),
					),
				),
				'attr'    => array(
					'cat_id' => array(
						'type'   => 'select',
						'title'  => '分类目录',
						'values' => $this->category_options(),
					),
					'cat_img' => array(
						'type'  => 'media',
						'title' => '分类缩略图',
						'desc'  => '建议尺寸：720 × 200 px',
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
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items    = isset( $settings['category_items'] ) && is_array( $settings['category_items'] ) ? $settings['category_items'] : array();

		if ( empty( $items ) ) {
			return '';
		}

		$title     = ! empty( $settings['module_title'] ) ? sanitize_text_field( $settings['module_title'] ) : '';
		$desc      = ! empty( $settings['module_desc'] ) ? sanitize_textarea_field( $settings['module_desc'] ) : '';
		$cat_style = isset( $settings['cat_style'] ) && 'style_1' === $settings['cat_style'] ? 'style_1' : 'style_2';
		$per_page  = ! empty( $settings['post_per_page'] ) ? absint( $settings['post_per_page'] ) : 6;

		ob_start();
		?>
		<div class="module-theme-news">
			<div class="section">
				<div class="container">
					<?php if ( $title || $desc ) : ?>
						<div class="section-title">
							<?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?>
							<?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="md-news">
						<?php foreach ( $items as $item ) : ?>
							<?php
							$item     = is_array( $item ) ? $item : array();
							$cat_id   = ! empty( $item['cat_id'] ) ? absint( $item['cat_id'] ) : 0;
							$category = $cat_id ? get_category( $cat_id ) : null;

							if ( ! $category || is_wp_error( $category ) ) {
								continue;
							}

							$category_url = get_category_link( $cat_id );
							$image_url    = $this->media_url( isset( $item['cat_img'] ) ? $item['cat_img'] : '' );
							$query        = new WP_Query(
								array(
									'post_type'           => 'post',
									'post_status'         => 'publish',
									'posts_per_page'      => $per_page,
									'ignore_sticky_posts' => true,
									'no_found_rows'       => true,
									'cat'                 => $cat_id,
								)
							);
							?>
							<div class="cat-item <?php echo esc_attr( $cat_style ); ?>">
								<div class="cat-title">
									<a href="<?php echo esc_url( $category_url ); ?>"><h3><?php echo esc_html( $category->name ); ?></h3></a>
									<a href="<?php echo esc_url( $category_url ); ?>" class="more"><span><?php esc_html_e( '查看更多', 'modular_theme' ); ?></span><i class="ri-arrow-right-double-line" aria-hidden="true"></i></a>
								</div>
								<?php if ( $image_url ) : ?>
									<div class="cat-img"><a href="<?php echo esc_url( $category_url ); ?>"><img loading="lazy" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category->name ); ?>"></a></div>
								<?php endif; ?>
								<?php if ( $query->have_posts() ) : ?>
									<ul class="news-list<?php echo empty( $settings['post_time'] ) ? ' no-time' : ''; ?>">
										<?php while ( $query->have_posts() ) : $query->the_post(); ?>
											<li>
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
												<?php if ( ! empty( $settings['post_time'] ) ) : ?><span><?php echo esc_html( get_the_date() ); ?></span><?php endif; ?>
											</li>
										<?php endwhile; ?>
									</ul>
								<?php else : ?>
									<p class="news-empty"><?php esc_html_e( '暂无相关内容', 'modular_theme' ); ?></p>
								<?php endif; ?>
							</div>
							<?php wp_reset_postdata(); ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}

	private function media_url( $value ) {
		if ( is_array( $value ) ) {
			return isset( $value['url'] ) ? $value['url'] : '';
		}

		return is_string( $value ) ? $value : '';
	}
}
