<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Gallery {
	public function get_name() { return 'theme_gallery'; }
	public function get_title() { return '相册/图集'; }
	public function get_class() { return 'xtpb-theme-gallery'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-gallery/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-gallery/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-gallery/assets/img/modules-gallery.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'相册/图集', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'list_style'=>array( 'type'=>'select', 'title'=>'每行显示', 'values'=>array( 'style_2'=>'两列', 'style_3'=>'三列', 'style_4'=>'四列', 'style_5'=>'五列' ), 'std'=>'style_4', 'section'=>'模块设置' ),
			'gallery_groups'=>array(
				'type'=>'repeatable', 'title'=>'相册分组', 'label'=>'tab_title', 'section'=>'内容设置',
				'std'=>array( array( 'tab_title'=>'相册标题', 'gallery_target'=>'fancybox', 'gallery_items'=>array( array( 'image'=>array( 'url'=>'' ) ) ) ) ),
				'attr'=>array(
					'tab_title'=>array( 'type'=>'text', 'title'=>'TAB 标题' ),
					'gallery_target'=>array( 'type'=>'select', 'title'=>'图片链接方式', 'values'=>array( 'fancybox'=>'点击放大图片', '_self'=>'当前窗口打开附件链接', '_blank'=>'新窗口打开附件链接' ), 'std'=>'fancybox' ),
					'gallery_items'=>array(
						'type'=>'repeatable', 'title'=>'添加图片', 'label'=>'image_title',
						'std'=>array( array( 'image'=>array( 'url'=>'' ), 'image_title'=>'', 'image_link'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ) ) ),
						'attr'=>array(
							'image'=>array( 'type'=>'media', 'title'=>'图片' ),
							'image_title'=>array( 'type'=>'text', 'title'=>'图片标题', 'desc'=>'留空时读取媒体库标题' ),
							'image_link'=>array( 'type'=>'link', 'title'=>'图片跳转链接', 'desc'=>'留空时读取附件 gallery_link_url' ),
						),
					),
				),
			),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$groups=isset( $s['gallery_groups'] ) && is_array( $s['gallery_groups'] ) ? $s['gallery_groups'] : array();
		if ( ! $groups ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$style=!empty( $s['list_style'] ) ? sanitize_html_class( $s['list_style'] ) : 'style_4';
		ob_start(); ?>
		<div class="module-theme-gallery"><div class="section"><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<?php if ( count( $groups ) > 1 ) : ?><div class="module-tab-menu"><ul><?php foreach ( $groups as $index=>$group ) : if ( empty( $group['tab_title'] ) ) { continue; } ?><li class="<?php echo 0 === $index ? 'selected' : ''; ?>" data-gallery-tab="<?php echo esc_attr( $index ); ?>"><?php echo esc_html( $group['tab_title'] ); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
			<div class="module-tab-box"><?php foreach ( $groups as $index=>$group ) : ?><div class="tab-item <?php echo esc_attr( $style ); ?>" data-gallery-panel="<?php echo esc_attr( $index ); ?>"<?php echo $index ? ' hidden' : ''; ?>><?php foreach ( (array) ( $group['gallery_items'] ?? array() ) as $item ) { echo $this->render_item( is_array( $item ) ? $item : array(), $group, $index ); } ?></div><?php endforeach; ?></div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function render_item( $item, $group, $index ) {
		$image=$this->media_url( $item['image'] ?? '' ); if ( ! $image ) { return ''; }
		$attachment_id=attachment_url_to_postid( strtok( $image, '?' ) );
		$title=!empty( $item['image_title'] ) ? sanitize_text_field( $item['image_title'] ) : ( $attachment_id ? get_the_title( $attachment_id ) : '' );
		$alt=$attachment_id ? get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) : '';
		$link=$this->link_data( $item['image_link'] ?? '' );
		if ( ! $link['url'] && $attachment_id ) { $link['url']=get_post_meta( $attachment_id, 'gallery_link_url', true ); }
		$target=$group['gallery_target'] ?? 'fancybox';
		ob_start(); ?><div class="item-box"><div class="item-img"><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $alt ); ?>"></div><?php if ( $title ) : ?><div class="item-title"><h3><?php echo esc_html( $title ); ?></h3></div><?php endif; ?><?php if ( 'fancybox' === $target ) : ?><a href="<?php echo esc_url( $image ); ?>" class="item-url" data-fancybox="images-<?php echo esc_attr( $index ); ?>" aria-label="<?php echo esc_attr( $title ); ?>"></a><?php elseif ( $link['url'] ) : ?><a href="<?php echo esc_url( $link['url'] ); ?>" class="item-url"<?php echo '_blank' === $target || $link['target'] ? ' target="_blank" rel="noopener"' : ''; ?> aria-label="<?php echo esc_attr( $title ); ?>"></a><?php endif; ?></div><?php return ob_get_clean();
	}

	private function media_url( $v ) { return is_array( $v ) ? ( $v['url'] ?? '' ) : ( is_string( $v ) ? $v : '' ); }
	private function link_data( $v ) { if ( is_array( $v ) ) { return array( 'url'=>$v['link'] ?? '', 'target'=>!empty( $v['window'] ) ); } return array( 'url'=>is_string( $v ) ? $v : '', 'target'=>false ); }

	public function getTemplate() {
		return '
		<# var groups=_.isArray(data.gallery_groups)?data.gallery_groups:[]; #><# if(groups.length){ #><div class="module-theme-gallery"><div class="section"><div class="container"><# if((data.module_title||data.module_desc)){ #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><# if(groups.length>1){ #><div class="module-tab-menu"><ul><# _.forEach(groups,function(group,index){if(group&&group.tab_title){ #><li class="<# if(index===0){ #>selected<# } #>">{{ group.tab_title }}</li><# }}); #></ul></div><# } #><div class="module-tab-box"><# _.forEach(groups,function(group,index){group=group||{};var images=_.isArray(group.gallery_items)?group.gallery_items:[]; #><div class="tab-item {{ data.list_style || "style_4" }}"<# if(index){ #> hidden<# } #>><# _.forEach(images,function(item){item=item||{};var image=item.image&&typeof item.image==="object"?(item.image.url||""):(item.image||""); #><# if(image){ #><div class="item-box"><div class="item-img"><img loading="lazy" src="{{ image }}" alt=""></div><# if(item.image_title){ #><div class="item-title"><h3>{{ item.image_title }}</h3></div><# } #><a href="{{ image }}" class="item-url"></a></div><# } #><# }); #></div><# }); #></div></div></div></div><# } #>';
	}
}
