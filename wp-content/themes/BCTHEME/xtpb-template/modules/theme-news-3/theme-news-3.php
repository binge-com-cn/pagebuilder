<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_News_3 {
	public function get_name() { return 'theme_news_3'; }
	public function get_title() { return '新闻资讯-3'; }
	public function get_class() { return 'xtpb-theme-news-3'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-news-3/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-news-3/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-news-3/assets/img/modules-news-3.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title' => array( 'type' => 'text', 'title' => '模块标题', 'std' => '新闻资讯', 'section' => '模块设置' ),
			'module_desc' => array( 'type' => 'textarea', 'title' => '模块简介', 'std' => '', 'section' => '模块设置' ),
			'list_style' => array(
				'type' => 'select', 'title' => '列表样式', 'std' => 'style_1', 'section' => '模块设置',
				'values' => array( 'style_1' => '样式-1（显示日期）', 'style_2' => '样式-2（不显示日期）' ),
			),
			'cat_url' => array( 'type' => 'switch', 'title' => '显示查看更多', 'std' => 0, 'section' => '模块设置' ),
			'category_items' => array(
				'type' => 'repeatable', 'title' => '调用分类', 'label' => 'cat_id', 'section' => '内容设置',
				'std' => array( array( 'cat_id' => '' ) ),
				'attr' => array( 'cat_id' => array( 'type' => 'select', 'title' => '分类目录', 'values' => $this->category_options() ) ),
			),
			'large_type' => array(
				'type' => 'select', 'title' => '大图文章', 'std' => 'new_post', 'section' => '大图设置',
				'values' => array( 'new_post' => '最新文章', 'custom_image' => '自定义图文' ),
			),
			'large_img' => array( 'type' => 'media', 'title' => '图片', 'section' => '大图设置', 'depends' => array( array( 'large_type', '=', 'custom_image' ) ) ),
			'large_title' => array( 'type' => 'text', 'title' => '标题', 'section' => '大图设置', 'depends' => array( array( 'large_type', '=', 'custom_image' ) ) ),
			'large_desc' => array( 'type' => 'textarea', 'title' => '描述', 'section' => '大图设置', 'depends' => array( array( 'large_type', '=', 'custom_image' ) ) ),
			'large_url' => array(
				'type' => 'link', 'title' => '跳转链接', 'section' => '大图设置',
				'std' => array( 'link' => '', 'window' => false, 'nofolow' => false ),
				'depends' => array( array( 'large_type', '=', 'custom_image' ) ),
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
		$s = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items = isset( $s['category_items'] ) && is_array( $s['category_items'] ) ? $s['category_items'] : array();
		$categories = $this->valid_categories( $items );
		if ( ! $categories ) { return ''; }
		$title = ! empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc = ! empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		ob_start(); ?>
		<div class="module-theme-news-3"><div class="section"><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<?php if ( count( $categories ) > 1 ) : ?><ul class="tab-post-menu mb-tab-menu"><?php foreach ( $categories as $index => $category ) : ?><li<?php echo 0 === $index ? ' class="active"' : ''; ?>><button type="button"><?php echo esc_html( $category->name ); ?></button></li><?php endforeach; ?></ul><?php endif; ?>
			<div class="swiper theme-news-3-slider"><div class="swiper-wrapper">
				<?php foreach ( $categories as $category ) : ?><div class="swiper-slide"><div class="md-news">
					<?php $this->render_large_item( $category, $s ); ?>
					<?php $this->render_post_list( $category, $s ); ?>
					<?php if ( ! empty( $s['cat_url'] ) ) : ?><a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="cat-url"><?php esc_html_e( '查看更多', 'modular_theme' ); ?></a><?php endif; ?>
				</div></div><?php endforeach; ?>
			</div></div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function valid_categories( $items ) {
		$categories = array();
		foreach ( $items as $item ) {
			$id = is_array( $item ) && ! empty( $item['cat_id'] ) ? absint( $item['cat_id'] ) : 0;
			$category = $id ? get_category( $id ) : null;
			if ( $category && ! is_wp_error( $category ) ) { $categories[] = $category; }
		}
		return $categories;
	}

	private function render_large_item( $category, $s ) {
		if ( isset( $s['large_type'] ) && 'custom_image' === $s['large_type'] ) {
			$image = $this->media_url( isset( $s['large_img'] ) ? $s['large_img'] : '' );
			$link = $this->link_data( isset( $s['large_url'] ) ? $s['large_url'] : '' );
			if ( ! $image && empty( $s['large_title'] ) && empty( $s['large_desc'] ) ) { return; }
			?>
			<div class="news-large">
				<?php if ( $image ) : ?><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( isset( $s['large_title'] ) ? $s['large_title'] : '' ); ?>"><?php endif; ?>
				<?php if ( ! empty( $s['large_title'] ) || ! empty( $s['large_desc'] ) ) : ?><div class="large-text"><?php if ( ! empty( $s['large_title'] ) ) : ?><h2><?php echo esc_html( $s['large_title'] ); ?></h2><?php endif; ?><?php if ( ! empty( $s['large_desc'] ) ) : ?><p><?php echo esc_html( $s['large_desc'] ); ?></p><?php endif; ?></div><?php endif; ?>
				<?php if ( $link['url'] ) : ?><a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?> aria-label="<?php echo esc_attr( isset( $s['large_title'] ) ? $s['large_title'] : '' ); ?>"></a><?php endif; ?>
			</div>
			<?php return;
		}

		$query = $this->post_query( $category->term_id, 1, 0 );
		if ( $query->have_posts() ) {
			$query->the_post(); ?>
			<div class="news-large">
				<?php $thumbnail_url = wpjam_get_post_thumbnail_url(); ?>
				<?php if ( $thumbnail_url ) : ?><img loading="lazy" src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"><?php endif; ?>
				<div class="large-text"><h2><?php the_title(); ?></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 55, '...' ) ); ?></p></div>
				<a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>"></a>
			</div>
			<?php
		}
		wp_reset_postdata();
	}

	private function render_post_list( $category, $s ) {
		$offset = isset( $s['large_type'] ) && 'custom_image' === $s['large_type'] ? 0 : 1;
		$query = $this->post_query( $category->term_id, 4, $offset );
		?>
		<div class="news-list">
			<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="news-itme">
					<?php if ( ! isset( $s['list_style'] ) || 'style_2' !== $s['list_style'] ) : ?><div class="itme-time"><span><?php echo esc_html( get_the_date( 'm-d' ) ); ?></span><span><?php echo esc_html( get_the_date( 'Y' ) ); ?></span></div><?php endif; ?>
					<div class="itme-text"><h2><?php the_title(); ?></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 55, '...' ) ); ?></p></div>
				</a>
			<?php endwhile; else : ?><p class="news-empty"><?php esc_html_e( '暂无相关内容', 'modular_theme' ); ?></p><?php endif; ?>
		</div>
		<?php wp_reset_postdata();
	}

	private function post_query( $cat_id, $per_page, $offset ) {
		return new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $per_page, 'offset' => $offset, 'ignore_sticky_posts' => true, 'no_found_rows' => true, 'cat' => $cat_id ) );
	}

	private function media_url( $value ) { return is_array( $value ) ? ( isset( $value['url'] ) ? $value['url'] : '' ) : ( is_string( $value ) ? $value : '' ); }
	private function link_data( $value ) {
		if ( is_array( $value ) ) { $url = isset( $value['link'] ) ? $value['link'] : ''; $target = ! empty( $value['window'] ); $nofollow = ! empty( $value['nofolow'] ) || ! empty( $value['nofollow'] ); } else { $url = is_string( $value ) ? $value : ''; $target = false; $nofollow = false; }
		$rel = array(); if ( $target ) { $rel[] = 'noopener'; } if ( $nofollow ) { $rel[] = 'nofollow'; }
		return array( 'url' => $url, 'target' => $target, 'rel' => implode( ' ', $rel ) );
	}

}
