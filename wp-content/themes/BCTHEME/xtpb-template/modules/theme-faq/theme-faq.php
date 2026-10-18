<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Faq {
	public function get_name() { return 'theme_faq'; }
	public function get_title() { return '常见问题'; }
	public function get_class() { return 'xtpb-theme-faq'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-faq/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-faq/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-faq/assets/img/modules-faq.jpg'; }
	public function get_category_name() { return 'Modular'; }
	public function get_editor_render_mode() { return 'server'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'常见问题', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'contact_style'=>array( 'type'=>'select', 'title'=>'列表样式', 'values'=>array( 'style_1'=>'1行2个', 'style_2'=>'1行1个（宽度铺满）', 'style_3'=>'1行1个（宽度70%，居中显示）' ), 'std'=>'style_1', 'section'=>'模块设置' ),
			'module_bg'=>array( 'type'=>'media', 'title'=>'背景图片', 'std'=>array( 'url'=>'' ), 'section'=>'模块设置' ),
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
		$style=!empty( $s['contact_style'] ) ? sanitize_html_class( $s['contact_style'] ) : 'style_1';
		$bg=$this->media_url( $s['module_bg'] ?? '' );
		$posts=$ids ? get_posts( array( 'post_type'=>'faq', 'post_status'=>'publish', 'post__in'=>$ids, 'orderby'=>'post__in', 'posts_per_page'=>count( $ids ), 'no_found_rows'=>true ) ) : array();
		if ( ! $title && ! $desc && ! $posts ) { return ''; }
		ob_start(); ?>
		<div class="module-theme-faq"><div class="section"<?php echo $bg ? ' style="background:url(' . esc_url( $bg ) . ') no-repeat center 0 / cover"' : ''; ?>><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title<?php echo $bg ? ' text-colour-white' : ''; ?>"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<?php if ( $posts ) : ?><div class="list-five <?php echo esc_attr( $style ); ?>"><?php if ( 'style_1' === $style ) : ?><?php foreach ( array_chunk( $posts, (int) ceil( count( $posts ) / 2 ) ) as $group ) : ?><div class="post-group"><?php foreach ( $group as $post ) { echo $this->render_post( $post ); } ?></div><?php endforeach; ?><?php else : ?><?php foreach ( $posts as $post ) { echo $this->render_post( $post ); } ?><?php endif; ?></div><?php endif; ?>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function render_post( $post ) {
		$previous_post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;
		$GLOBALS['post'] = $post;
		setup_postdata( $post );
		$content = apply_filters( 'the_content', $post->post_content );

		if ( $previous_post instanceof WP_Post ) {
			$GLOBALS['post'] = $previous_post;
			setup_postdata( $previous_post );
		} else {
			wp_reset_postdata();
		}

		ob_start(); ?><div class="post-accordion"><div class="faq-accordion-title" tabindex="0" role="button" aria-expanded="false"><h2><?php echo esc_html( get_the_title( $post ) ); ?></h2><i class="ri-arrow-down-s-line" aria-hidden="true"></i></div><div class="accordion-desc"><?php echo $content; ?></div></div><?php return ob_get_clean();
	}

	private function media_url( $v ) { return is_array( $v ) ? ( $v['url'] ?? '' ) : ( is_string( $v ) ? $v : '' ); }
}
