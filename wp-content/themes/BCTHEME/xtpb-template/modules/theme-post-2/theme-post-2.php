<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Post_2 {
	public function get_name() { return 'theme_post_2'; }
	public function get_title() { return '文章/资讯（滚动）'; }
	public function get_class() { return 'xtpb-theme-post-2'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-post-2/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-post-2/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-post-2/assets/img/modules-post-2.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'文章资讯', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'cat_post'=>array( 'type'=>'select', 'title'=>'调用内容', 'values'=>array( 'cat_id'=>'分类文章', 'post_id'=>'指定文章' ), 'std'=>'cat_id', 'section'=>'内容设置' ),
			'cat_id'=>array( 'type'=>'select', 'title'=>'文章分类', 'values'=>$this->category_options(), 'std'=>'', 'depends'=>array( array( 'cat_post', '=', 'cat_id' ) ), 'section'=>'内容设置' ),
			'post_items'=>array(
				'type'=>'repeatable', 'title'=>'指定文章', 'label'=>'post_id', 'section'=>'内容设置', 'depends'=>array( array( 'cat_post', '=', 'post_id' ) ),
				'std'=>array( array( 'post_id'=>'' ) ),
				'attr'=>array( 'post_id'=>array( 'type'=>'select', 'title'=>'文章', 'values'=>$this->post_options() ) ),
			),
			'post_thumb_ratio'=>array( 'type'=>'select', 'title'=>'缩略图比例', 'values'=>array( 'ratio-1-1'=>'1:1', 'ratio-4-3'=>'4:3', 'ratio-3-2'=>'3:2', 'ratio-16-9'=>'16:9', 'ratio-21-9'=>'21:9' ), 'std'=>'ratio-4-3', 'section'=>'模块设置' ),
			'post_category'=>array( 'type'=>'switch', 'title'=>'显示分类目录', 'std'=>0, 'section'=>'显示设置' ),
			'post_date'=>array( 'type'=>'switch', 'title'=>'显示发布时间', 'std'=>0, 'section'=>'显示设置' ),
			'list_style'=>array( 'type'=>'select', 'title'=>'桌面端显示数量', 'values'=>array( 'style_2'=>'2 篇', 'style_3'=>'3 篇', 'style_4'=>'4 篇', 'style_5'=>'5 篇' ), 'std'=>'style_3', 'section'=>'显示设置' ),
			'post_per_page'=>array( 'type'=>'number', 'title'=>'文章总数', 'std'=>6, 'depends'=>array( array( 'cat_post', '=', 'cat_id' ) ), 'section'=>'显示设置' ),
			'more_url'=>array( 'type'=>'switch', 'title'=>'显示查看更多', 'std'=>0, 'section'=>'按钮设置' ),
			'more_btn_text'=>array( 'type'=>'text', 'title'=>'按钮文本', 'std'=>'查看更多', 'depends'=>array( array( 'more_url', '=', 1 ) ), 'section'=>'按钮设置' ),
			'more_btn_url'=>array( 'type'=>'link', 'title'=>'按钮跳转链接', 'std'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ), 'depends'=>array( array( 'more_url', '=', 1 ) ), 'section'=>'按钮设置' ),
		);
	}

	private function category_options() { $options=array( ''=>'全部分类' ); foreach ( get_categories( array( 'hide_empty'=>false ) ) as $category ) { $options[ (string) $category->term_id ]=$category->name; } return $options; }
	private function post_options() { $options=array( ''=>'请选择文章' ); foreach ( get_posts( array( 'post_type'=>'post', 'post_status'=>array( 'publish','draft','private' ), 'posts_per_page'=>-1, 'orderby'=>'date', 'order'=>'DESC' ) ) as $post ) { $options[ (string) $post->ID ]=$post->post_title ?: sprintf( '文章 #%d', $post->ID ); } return $options; }

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$query=$this->get_query( $s );
		if ( ! $query || ! $query->have_posts() ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$columns=$this->columns( isset( $s['list_style'] ) ? $s['list_style'] : '' );
		$ratio=$this->thumbnail_ratio( $s );
		$link=$this->link_data( isset( $s['more_btn_url'] ) ? $s['more_btn_url'] : '' );
		$button=!empty( $s['more_url'] ) && !empty( $s['more_btn_text'] ) && $link['url'];
		ob_start(); ?>
		<div class="module-theme-post-2 <?php echo esc_attr( $ratio ); ?>"><div class="section theme-post swiper-parent"><div class="container">
			<?php if ( $title || $desc || $button ) : ?><div class="post-box-heading"><div><?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php if ( $button ) : ?><?php $this->render_button( $s['more_btn_text'], $link ); ?><?php endif; ?></div><?php endif; ?>
			<div class="post-slider-overflow"><div class="swiper theme-post-2-slider" data-columns="<?php echo esc_attr( $columns ); ?>"><div class="swiper-wrapper post-box-list">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?><div class="swiper-slide"><?php $this->render_card( $s ); ?></div><?php endwhile; ?>
			</div></div></div>
			<div class="swiper-navigation"><div class="navigation-wrapper"><button type="button" class="swiper-nav swiper-nav-prev" aria-label="<?php esc_attr_e( '上一篇', 'modular_theme' ); ?>"><i class="ri-arrow-left-line" aria-hidden="true"></i></button><button type="button" class="swiper-nav swiper-nav-next" aria-label="<?php esc_attr_e( '下一篇', 'modular_theme' ); ?>"><i class="ri-arrow-right-line" aria-hidden="true"></i></button></div><?php if ( $button ) : ?><?php $this->render_button( $s['more_btn_text'], $link ); ?><?php endif; ?></div>
		</div></div></div>
		<?php wp_reset_postdata(); return ob_get_clean();
	}

	private function get_query( $s ) {
		$args=array( 'post_type'=>'post', 'post_status'=>'publish', 'ignore_sticky_posts'=>true, 'no_found_rows'=>true );
		if ( isset( $s['cat_post'] ) && 'post_id' === $s['cat_post'] ) {
			$rows=isset( $s['post_items'] ) && is_array( $s['post_items'] ) ? $s['post_items'] : array();
			$ids=array_values( array_filter( array_map( function( $row ){ return is_array( $row ) ? absint( isset( $row['post_id'] ) ? $row['post_id'] : 0 ) : 0; }, $rows ) ) );
			if ( ! $ids ) { return null; }
			$args['post__in']=$ids; $args['orderby']='post__in'; $args['posts_per_page']=count( $ids );
		} else { $args['posts_per_page']=!empty( $s['post_per_page'] ) ? absint( $s['post_per_page'] ) : 6; if ( ! empty( $s['cat_id'] ) ) { $args['cat']=absint( $s['cat_id'] ); } }
		return new WP_Query( $args );
	}

	private function render_card( $s ) { $categories=get_the_category(); $thumbnail_url=wpjam_get_post_thumbnail_url(); ?>
		<article class="post-item"><?php if ( $thumbnail_url ) : ?><figure class="post-image"><a href="<?php the_permalink(); ?>"><img loading="lazy" src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"></a></figure><?php endif; ?><div class="post-text"><?php if ( ! empty( $s['post_category'] ) || ! empty( $s['post_date'] ) ) : ?><div class="post-meta"><?php if ( ! empty( $s['post_category'] ) && $categories ) : ?><a class="post-category" href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"><?php echo esc_html( $categories[0]->name ); ?></a><?php endif; ?><?php if ( ! empty( $s['post_date'] ) ) : ?><div class="post-date"><span><?php echo esc_html( get_the_date() ); ?></span></div><?php endif; ?></div><?php endif; ?><a class="post-title" href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a><div class="post-excerpt"><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 60, '...' ) ); ?></p></div></div></article><?php
	}

	private function render_button( $text, $link ) { ?><a class="btn-more" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>><?php echo esc_html( $text ); ?><i class="ri-arrow-right-line" aria-hidden="true"></i></a><?php }
	private function columns( $style ) { $map=array( 'style_2'=>2, 'style_3'=>3, 'style_4'=>4, 'style_5'=>5 ); return isset( $map[ $style ] ) ? $map[ $style ] : 3; }
	private function thumbnail_ratio( $s ) { $ratio=isset( $s['post_thumb_ratio'] ) ? $s['post_thumb_ratio'] : 'ratio-4-3'; return in_array( $ratio, array( 'ratio-1-1','ratio-4-3','ratio-3-2','ratio-16-9','ratio-21-9' ), true ) ? $ratio : 'ratio-4-3'; }
	private function link_data( $v ) { if ( is_array( $v ) ) { $url=isset( $v['link'] ) ? $v['link'] : ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); } else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; } $rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; } return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) ); }

}
