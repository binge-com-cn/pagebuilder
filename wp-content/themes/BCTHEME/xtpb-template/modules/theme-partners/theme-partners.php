<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Partners {
	public function get_name() { return 'theme_partners'; }
	public function get_title() { return '合作伙伴（静态）'; }
	public function get_class() { return 'xtpb-theme-partners'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-partners/assets/css/frontend.css' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-partners/assets/img/modules-partners.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title' => array( 'type' => 'text', 'title' => '模块标题', 'std' => '合作伙伴', 'section' => '模块设置' ),
			'module_desc' => array( 'type' => 'textarea', 'title' => '模块简介', 'std' => '', 'section' => '模块设置' ),
			'image_style' => array(
				'type' => 'select', 'title' => '每行显示', 'std' => 'style_5', 'section' => '样式设置',
				'values' => array( 'style_4' => '四列', 'style_5' => '五列' ),
			),
			'image_shadow' => array(
				'type' => 'select', 'title' => '图片边框', 'std' => 'none-border', 'section' => '样式设置',
				'values' => array( 'none-border' => '无边框', 'image-border' => '默认边框', 'image-shadow' => '阴影效果' ),
			),
			'partner_items' => array(
				'type' => 'repeatable', 'title' => '合作伙伴', 'label' => 'image_alt', 'section' => '内容设置',
				'std' => array( array( 'image' => array( 'url' => '' ), 'image_alt' => '合作伙伴', 'image_url' => array( 'link' => '', 'window' => false, 'nofolow' => false ) ) ),
				'attr' => array(
					'image' => array( 'type' => 'media', 'title' => '图片', 'desc' => '建议尺寸：360 × 210 px' ),
					'image_alt' => array( 'type' => 'text', 'title' => '图片说明', 'desc' => '用于图片 Alt 属性；留空时读取媒体库替代文本' ),
					'image_url' => array( 'type' => 'link', 'title' => '跳转链接', 'std' => array( 'link' => '', 'window' => false, 'nofolow' => false ) ),
				),
			),
		);
	}

	public function render( $data = null ) {
		$s = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items = isset( $s['partner_items'] ) && is_array( $s['partner_items'] ) ? $s['partner_items'] : array();
		if ( ! $items ) { return ''; }

		$title = ! empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc = ! empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$columns = isset( $s['image_style'] ) && 'style_4' === $s['image_style'] ? 'style_4' : 'style_5';
		$border = isset( $s['image_shadow'] ) && in_array( $s['image_shadow'], array( 'image-border', 'image-shadow' ), true ) ? $s['image_shadow'] : 'none-border';

		ob_start(); ?>
		<div class="module-theme-partners"><div class="section"><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<div class="md-partners <?php echo esc_attr( $columns . ' ' . $border ); ?>"><ul>
				<?php foreach ( $items as $item ) : ?>
					<?php
					$item = is_array( $item ) ? $item : array();
					$image = $this->media_data( isset( $item['image'] ) ? $item['image'] : '' );
					if ( ! $image['url'] ) { continue; }
					$link = $this->link_data( isset( $item['image_url'] ) ? $item['image_url'] : '' );
					$alt = ! empty( $item['image_alt'] ) ? sanitize_text_field( $item['image_alt'] ) : $this->attachment_alt( $image );
					?>
					<li>
						<?php if ( $link['url'] ) : ?><a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>><?php endif; ?>
						<img loading="lazy" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $alt ); ?>">
						<?php if ( $link['url'] ) : ?></a><?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul></div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function media_data( $value ) {
		if ( is_array( $value ) ) { return array( 'url' => isset( $value['url'] ) ? $value['url'] : '', 'id' => isset( $value['id'] ) ? absint( $value['id'] ) : 0 ); }
		return array( 'url' => is_string( $value ) ? $value : '', 'id' => 0 );
	}

	private function attachment_alt( $image ) {
		$id = $image['id'] ? $image['id'] : attachment_url_to_postid( $image['url'] );
		return $id ? (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) : '';
	}

	private function link_data( $value ) {
		if ( is_array( $value ) ) { $url = isset( $value['link'] ) ? $value['link'] : ''; $target = ! empty( $value['window'] ); $nofollow = ! empty( $value['nofolow'] ) || ! empty( $value['nofollow'] ); } else { $url = is_string( $value ) ? $value : ''; $target = false; $nofollow = false; }
		$rel = array(); if ( $target ) { $rel[] = 'noopener'; } if ( $nofollow ) { $rel[] = 'nofollow'; }
		return array( 'url' => $url, 'target' => $target, 'rel' => implode( ' ', $rel ) );
	}

	public function getTemplate() {
		return '
		<# var items=_.isArray(data.partner_items)?data.partner_items:[];var columns=data.image_style==="style_4"?"style_4":"style_5";var border=["image-border","image-shadow"].indexOf(data.image_shadow)!==-1?data.image_shadow:"none-border"; #>
		<# if(items.length){ #><div class="module-theme-partners"><div class="section"><div class="container"><# if((data.module_title||data.module_desc)){ #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><div class="md-partners {{ columns }} {{ border }}"><ul><# _.forEach(items,function(item){item=item||{};var image=item.image&&typeof item.image==="object"?(item.image.url||""):(item.image||"");var link=item.image_url&&typeof item.image_url==="object"?(item.image_url.link||""):(item.image_url||"");if(!image){return;} #><li><# if(link){ #><a href="{{ link }}"><# } #><img src="{{ image }}" alt="{{ item.image_alt||"" }}"><# if(link){ #></a><# } #></li><# }); #></ul></div></div></div></div><# } #>';
	}
}
