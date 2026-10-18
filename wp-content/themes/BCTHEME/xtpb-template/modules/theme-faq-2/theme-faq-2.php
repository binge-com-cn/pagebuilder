<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Faq_2 {
	public function get_name() { return 'theme_faq_2'; }
	public function get_title() { return '常见问题-2'; }
	public function get_class() { return 'xtpb-theme-faq-2'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-faq-2/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-faq-2/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-faq-2/assets/img/modules-faq-2.jpg'; }
	public function get_category_name() { return 'Modular'; }
	public function get_editor_render_mode() { return 'server'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'常见问题', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'module_bg'=>array( 'type'=>'media', 'title'=>'背景图片', 'std'=>array( 'url'=>'' ), 'section'=>'模块设置' ),
			'more_btn_text'=>array( 'type'=>'text', 'title'=>'查看更多按钮', 'std'=>'', 'section'=>'按钮设置' ),
			'more_btn_url'=>array( 'type'=>'link', 'title'=>'跳转链接', 'std'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ), 'depends'=>array( array( 'more_btn_text', '!=', '' ) ), 'section'=>'按钮设置' ),
			'faq_items'=>array(
				'type'=>'repeatable', 'title'=>'指定常见问题', 'label'=>'faq_id', 'section'=>'内容设置',
				'std'=>array( array( 'faq_id'=>'' ) ),
				'attr'=>array( 'faq_id'=>array( 'type'=>'select', 'title'=>'常见问题', 'values'=>$this->faq_options() ) ),
			),
		);
	}

	private function faq_options() {
		$options=array( ''=>'请选择常见问题' );
		$posts=get_posts( array( 'post_type'=>'faq', 'post_status'=>array( 'publish','draft','private' ), 'posts_per_page'=>-1, 'orderby'=>'title', 'order'=>'ASC' ) );
		foreach ( $posts as $post ) { $options[ (string) $post->ID ]=$post->post_title ?: sprintf( '常见问题 #%d', $post->ID ); }
		return $options;
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$rows=isset( $s['faq_items'] ) && is_array( $s['faq_items'] ) ? $s['faq_items'] : array();
		$ids=array_values( array_filter( array_map( function( $row ){ return is_array( $row ) ? absint( $row['faq_id'] ?? 0 ) : 0; }, $rows ) ) );
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$bg=$this->media_url( $s['module_bg'] ?? '' );
		$link=$this->link_data( $s['more_btn_url'] ?? '' );
		$show_button=!empty( $s['more_btn_text'] ) && $link['url'];
		$query=$ids ? new WP_Query( array( 'post_type'=>'faq', 'post_status'=>'publish', 'post__in'=>$ids, 'orderby'=>'post__in', 'posts_per_page'=>count( $ids ), 'no_found_rows'=>true ) ) : null;
		$has_faqs=$query && $query->have_posts();
		if ( ! $title && ! $desc && ! $show_button && ! $has_faqs ) { return ''; }
		ob_start(); ?>
		<div class="module-theme-faq-2"><div class="section"<?php echo $bg ? ' style="background:url(' . esc_url( $bg ) . ') no-repeat center 0 / cover"' : ''; ?>><div class="container"><div class="faq-inner<?php echo $has_faqs ? '' : ' faq-empty'; ?>">
			<?php if ( $title || $desc || $show_button ) : ?><div class="faq-title"><div class="title-panel"><?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?><?php if ( $show_button ) : ?><a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>><span><?php echo esc_html( $s['more_btn_text'] ); ?></span><i class="ri-arrow-right-line"></i></a><?php endif; ?></div></div><?php endif; ?>
			<?php if ( $has_faqs ) : ?><div class="faq-list"><ul><?php while ( $query->have_posts() ) : $query->the_post(); ?><li class="post-accordion"><h3 class="faq-2-accordion-title" tabindex="0" role="button" aria-expanded="false"><?php the_title(); ?></h3><div class="accordion-desc"><?php echo apply_filters( 'the_content', get_the_content() ); ?></div></li><?php endwhile; ?></ul></div><?php endif; ?>
		</div></div></div></div>
		<?php if ( $query ) { wp_reset_postdata(); } return ob_get_clean();
	}

	private function media_url( $v ) { return is_array( $v ) ? ( $v['url'] ?? '' ) : ( is_string( $v ) ? $v : '' ); }
	private function link_data( $v ) {
		if ( is_array( $v ) ) { $url=$v['link'] ?? ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); } else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; }
		$rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; }
		return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) );
	}
}
