<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Product_Tab {
	public function get_name() { return 'theme_product_tab'; }
	public function get_title() { return '产品展示/TAB切换'; }
	public function get_class() { return 'xtpb-theme-product-tab'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-product-tab/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-product-tab/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-product-tab/assets/img/modules-product-tab.jpg'; }
	public function get_category_name() { return 'Modular'; }
	public function get_editor_render_mode() { return 'server'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'产品展示', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'list_style'=>array( 'type'=>'select', 'title'=>'每行显示', 'values'=>array( 'style_2'=>'两列', 'style_3'=>'三列', 'style_4'=>'四列' ), 'std'=>'style_4', 'section'=>'显示设置' ),
			'post_per_page'=>array( 'type'=>'number', 'title'=>'文章数量', 'desc'=>'每个分类默认显示 6 篇', 'std'=>6, 'section'=>'显示设置' ),
			'post_thumb_ratio'=>array( 'type'=>'select', 'title'=>'缩略图比例', 'values'=>array( 'ratio-1-1'=>'1:1', 'ratio-4-3'=>'4:3', 'ratio-3-2'=>'3:2', 'ratio-16-9'=>'16:9', 'ratio-21-9'=>'21:9' ), 'std'=>'ratio-4-3', 'section'=>'显示设置' ),
			'menu_left'=>array( 'type'=>'switch', 'title'=>'菜单显示在左侧', 'std'=>0, 'section'=>'菜单设置' ),
			'menu_title'=>array( 'type'=>'text', 'title'=>'菜单标题', 'std'=>'产品分类', 'depends'=>array( array( 'menu_left', '=', 1 ) ), 'section'=>'菜单设置' ),
			'menu_tel_title'=>array( 'type'=>'text', 'title'=>'电话标题', 'std'=>'免费服务热线', 'depends'=>array( array( 'menu_left', '=', 1 ) ), 'section'=>'菜单设置' ),
			'menu_tel'=>array( 'type'=>'text', 'title'=>'电话号码', 'std'=>'138-8888-8888', 'depends'=>array( array( 'menu_left', '=', 1 ) ), 'section'=>'菜单设置' ),
			'cat_url'=>array( 'type'=>'switch', 'title'=>'显示查看更多', 'std'=>0, 'section'=>'菜单设置' ),
			'category_items'=>array(
				'type'=>'repeatable', 'title'=>'产品分类', 'label'=>'cat_id', 'section'=>'内容设置',
				'std'=>array( array( 'cat_id'=>'', 'post_items'=>array( array( 'post_id'=>'' ) ) ) ),
				'attr'=>array(
					'cat_id'=>array( 'type'=>'select', 'title'=>'分类目录', 'values'=>$this->category_options() ),
					'post_items'=>array(
						'type'=>'repeatable', 'title'=>'指定文章', 'label'=>'post_id', 'desc'=>'留空则调用当前分类的最新文章',
						'std'=>array( array( 'post_id'=>'' ) ),
						'attr'=>array( 'post_id'=>array( 'type'=>'select', 'title'=>'文章', 'values'=>$this->post_options() ) ),
					),
				),
			),
		);
	}

	private function category_options() { $options=array( ''=>'请选择分类' ); foreach ( get_categories( array( 'hide_empty'=>false ) ) as $category ) { $options[ (string) $category->term_id ]=$category->name; } return $options; }
	private function post_options() { $options=array( ''=>'请选择文章' ); foreach ( get_posts( array( 'post_type'=>'post', 'post_status'=>array( 'publish','draft','private' ), 'posts_per_page'=>-1, 'orderby'=>'date', 'order'=>'DESC' ) ) as $post ) { $options[ (string) $post->ID ]=$post->post_title ?: sprintf( '文章 #%d', $post->ID ); } return $options; }

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items=isset( $s['category_items'] ) && is_array( $s['category_items'] ) ? $s['category_items'] : array();
		$groups=$this->valid_groups( $items );
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		if ( ! $groups && ! $title && ! $desc ) { return ''; }
		$style=in_array( isset( $s['list_style'] ) ? $s['list_style'] : '', array( 'style_2','style_3','style_4' ), true ) ? $s['list_style'] : 'style_4';
		$ratio=$this->thumbnail_ratio( $s );
		ob_start(); ?>
		<div class="module-theme-product-tab <?php echo esc_attr( $ratio ); ?>"><div class="section"><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<?php if ( $groups ) : ?><?php if ( ! empty( $s['menu_left'] ) ) : ?><div class="tab-product-wrapper"><div class="tab-menu-left"><?php if ( ! empty( $s['menu_title'] ) ) : ?><h3><?php echo esc_html( $s['menu_title'] ); ?></h3><?php endif; ?><?php $this->render_menu( $groups ); ?><?php if ( ! empty( $s['menu_tel_title'] ) || ! empty( $s['menu_tel'] ) ) : ?><div class="menu-left-tel"><div class="tel-icon"><i class="ri-customer-service-fill" aria-hidden="true"></i></div><div class="tel-text"><?php if ( ! empty( $s['menu_tel_title'] ) ) : ?><span><?php echo esc_html( $s['menu_tel_title'] ); ?></span><?php endif; ?><?php if ( ! empty( $s['menu_tel'] ) ) : ?><p><?php echo esc_html( $s['menu_tel'] ); ?></p><?php endif; ?></div></div><?php endif; ?></div><?php $this->render_slider( $groups, $s, $style, true ); ?></div>
			<?php else : ?><?php if ( count( $groups ) > 1 ) { $this->render_menu( $groups ); } ?><?php $this->render_slider( $groups, $s, $style, false ); ?><?php endif; ?><?php endif; ?>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function valid_groups( $items ) {
		$groups=array(); foreach ( $items as $item ) { $item=is_array( $item ) ? $item : array(); $id=!empty( $item['cat_id'] ) ? absint( $item['cat_id'] ) : 0; $category=$id ? get_category( $id ) : null; if ( $category && ! is_wp_error( $category ) ) { $groups[]=array( 'category'=>$category, 'post_items'=>isset( $item['post_items'] ) && is_array( $item['post_items'] ) ? $item['post_items'] : array() ); } } return $groups;
	}

	private function render_menu( $groups ) { ?><ul class="tab-post-menu mb-tab-menu"><?php foreach ( $groups as $index=>$group ) : ?><li<?php echo 0 === $index ? ' class="active"' : ''; ?>><button type="button"><?php echo esc_html( $group['category']->name ); ?></button></li><?php endforeach; ?></ul><?php }

	private function render_slider( $groups, $s, $style, $left ) { ?>
		<div class="swiper product-tab-slider <?php echo $left ? 'tab-product-content-2' : 'tab-post-content'; ?>"><div class="swiper-wrapper"><?php foreach ( $groups as $group ) : ?><div class="swiper-slide"><div class="list-one <?php echo esc_attr( $style ); ?>"><?php $this->render_posts( $group, $s ); ?><?php if ( ! empty( $s['cat_url'] ) ) : ?><?php if ( ! $left ) : ?><div class="view-more-btn"><?php endif; ?><a href="<?php echo esc_url( get_category_link( $group['category']->term_id ) ); ?>" class="cat-url"><?php esc_html_e( '查看更多', 'modular_theme' ); ?></a><?php if ( ! $left ) : ?></div><?php endif; ?><?php endif; ?></div></div><?php endforeach; ?></div></div><?php
	}

	private function render_posts( $group, $s ) {
		$ids=array_values( array_filter( array_map( function( $row ){ return is_array( $row ) ? absint( isset( $row['post_id'] ) ? $row['post_id'] : 0 ) : 0; }, $group['post_items'] ) ) );
		$args=array( 'post_type'=>'post', 'post_status'=>'publish', 'ignore_sticky_posts'=>true, 'no_found_rows'=>true );
		if ( $ids ) { $args['post__in']=$ids; $args['orderby']='post__in'; $args['posts_per_page']=count( $ids ); } else { $args['cat']=$group['category']->term_id; $args['posts_per_page']=!empty( $s['post_per_page'] ) ? absint( $s['post_per_page'] ) : 6; }
		$query=new WP_Query( $args );
		if ( ! $query->have_posts() ) { echo '<p class="product-empty">'.esc_html__( '暂无相关内容', 'modular_theme' ).'</p>'; return; }
		while ( $query->have_posts() ) { $query->the_post(); $this->render_card(); }
		wp_reset_postdata();
	}

	private function render_card() { $thumbnail_url=wpjam_get_post_thumbnail_url(); ?><div class="post-item"><a href="<?php the_permalink(); ?>"><?php if ( $thumbnail_url ) : ?><div class="img-cover"><img loading="lazy" src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"></div><?php endif; ?><div class="text"><h2><?php the_title(); ?></h2></div></a></div><?php }
	private function thumbnail_ratio( $s ) { $ratio=isset( $s['post_thumb_ratio'] ) ? $s['post_thumb_ratio'] : 'ratio-4-3'; return in_array( $ratio, array( 'ratio-1-1','ratio-4-3','ratio-3-2','ratio-16-9','ratio-21-9' ), true ) ? $ratio : 'ratio-4-3'; }

}
