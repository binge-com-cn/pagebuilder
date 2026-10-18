<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Download {
	public function get_name() { return 'theme_download'; }
	public function get_title() { return '资料下载'; }
	public function get_class() { return 'xtpb-theme-download'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-download/assets/css/frontend.css' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-download/assets/img/modules-download.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'资料下载', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'contact_style'=>array( 'type'=>'select', 'title'=>'列表样式', 'values'=>array( 'style_1'=>'1行1个（无间距）', 'style_2'=>'1行1个（有间距）', 'style_3'=>'1行2个' ), 'std'=>'style_1', 'section'=>'模块设置' ),
			'module_bg'=>array( 'type'=>'media', 'title'=>'背景图片', 'std'=>array( 'url'=>'' ), 'section'=>'模块设置' ),
			'download_items'=>array(
				'type'=>'repeatable', 'title'=>'指定下载资料', 'label'=>'download_id', 'section'=>'内容设置',
				'std'=>array( array( 'download_id'=>'' ) ),
				'attr'=>array( 'download_id'=>array( 'type'=>'select', 'title'=>'下载资料', 'values'=>$this->download_options() ) ),
			),
		);
	}

	private function download_options() {
		$options=array( ''=>'请选择下载资料' );
		$posts=get_posts( array( 'post_type'=>'download', 'post_status'=>array( 'publish','draft','private' ), 'posts_per_page'=>-1, 'orderby'=>'title', 'order'=>'ASC' ) );
		foreach ( $posts as $post ) { $options[ (string) $post->ID ]=$post->post_title ?: sprintf( '下载资料 #%d', $post->ID ); }
		return $options;
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$rows=isset( $s['download_items'] ) && is_array( $s['download_items'] ) ? $s['download_items'] : array();
		$ids=array_values( array_filter( array_map( function( $row ){ return is_array( $row ) ? absint( $row['download_id'] ?? 0 ) : 0; }, $rows ) ) );
		if ( ! $ids ) { return ''; }
		$query=new WP_Query( array( 'post_type'=>'download', 'post_status'=>'publish', 'post__in'=>$ids, 'orderby'=>'post__in', 'posts_per_page'=>count( $ids ), 'no_found_rows'=>true ) );
		if ( ! $query->have_posts() ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$style=!empty( $s['contact_style'] ) ? sanitize_html_class( $s['contact_style'] ) : 'style_1';
		$bg=$this->media_url( $s['module_bg'] ?? '' );
		ob_start(); ?>
		<div class="module-theme-download"><div class="section"<?php echo $bg ? ' style="background:url(' . esc_url( $bg ) . ') no-repeat center 0 / cover"' : ''; ?>><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title<?php echo $bg ? ' text-colour-white' : ''; ?>"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<div class="list-six <?php echo esc_attr( $style ); ?>">
				<?php while ( $query->have_posts() ) : $query->the_post(); echo $this->render_post( get_post(), $style ); endwhile; ?>
			</div>
		</div></div></div>
		<?php wp_reset_postdata(); return ob_get_clean();
	}

	private function render_post( $post, $style ) {
		$url=get_post_meta( $post->ID, 'download_url', true );
		$image=wpjam_get_post_thumbnail_url();
		$excerpt=get_the_excerpt( $post );
		ob_start(); ?>
		<div class="post-download">
			<?php if ( $image ) : ?><div class="download-img"><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_the_title( $post ) ); ?>"></div><?php endif; ?>
			<div class="download-title"><h2><?php echo esc_html( get_the_title( $post ) ); ?></h2><?php if ( $excerpt ) : ?><p><?php echo esc_html( $excerpt ); ?></p><?php endif; ?><?php if ( 'style_3' === $style && $url ) : ?><?php echo $this->button( $url ); ?><?php endif; ?></div>
			<?php if ( 'style_3' !== $style && $url ) : ?><?php echo $this->button( $url ); ?><?php endif; ?>
			<?php if ( $url ) : ?><a class="download-link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="nofollow noopener" aria-label="<?php echo esc_attr( sprintf( __( '下载：%s', 'modular_theme' ), get_the_title( $post ) ) ); ?>"></a><?php endif; ?>
		</div>
		<?php return ob_get_clean();
	}

	private function button( $url ) { return '<div class="download-button"><a href="'.esc_url( $url ).'" target="_blank" rel="nofollow noopener"><i class="ri-download-cloud-line"></i> '.esc_html__( '点击下载', 'modular_theme' ).'</a></div>'; }
	private function media_url( $v ) { return is_array( $v ) ? ( $v['url'] ?? '' ) : ( is_string( $v ) ? $v : '' ); }

}
