<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Features_2 {
	public function get_name() { return 'theme_features_2'; }
	public function get_title() { return '特点介绍-2'; }
	public function get_class() { return 'xtpb-theme-features-2'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-features-2/assets/css/frontend.css' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-features-2/assets/img/modules-features-2.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'特点介绍', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'features_style'=>array( 'type'=>'select', 'title'=>'显示列数', 'values'=>array( 'style_2'=>'两列', 'style_3'=>'三列', 'style_4'=>'四列' ), 'std'=>'style_4', 'section'=>'模块设置' ),
			'features_type'=>array( 'type'=>'select', 'title'=>'卡片样式', 'values'=>array( 'features-normal'=>'常规', 'features-shadow'=>'阴影', 'features-border'=>'边框' ), 'std'=>'features-normal', 'section'=>'模块设置' ),
			'bg_img'=>array( 'type'=>'media', 'title'=>'背景图片', 'std'=>array( 'url'=>'' ), 'section'=>'模块设置' ),
			'features_items'=>array(
				'type'=>'repeatable', 'title'=>'特点项目', 'label'=>'item_title', 'section'=>'内容设置',
				'std'=>array( array( 'item_img'=>array( 'url'=>'' ), 'item_title'=>'图片标题', 'item_desc'=>'特点描述内容', 'item_url'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ) ) ),
				'attr'=>array(
					'item_img'=>array( 'type'=>'media', 'title'=>'图片', 'desc'=>'建议尺寸：200 × 100 px' ),
					'item_title'=>array( 'type'=>'text', 'title'=>'图片标题' ),
					'item_desc'=>array( 'type'=>'textarea', 'title'=>'描述' ),
					'item_url'=>array( 'type'=>'link', 'title'=>'跳转链接' ),
				),
			),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items=isset( $s['features_items'] ) && is_array( $s['features_items'] ) ? $s['features_items'] : array();
		if ( ! $items ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$columns=!empty( $s['features_style'] ) ? sanitize_html_class( $s['features_style'] ) : 'style_4';
		$type=!empty( $s['features_type'] ) ? sanitize_html_class( $s['features_type'] ) : 'features-normal';
		$bg=$this->media_url( $s['bg_img'] ?? '' );
		ob_start(); ?>
		<div class="module-theme-features-2"><div class="section<?php echo $bg ? ' mbbg-img' : ''; ?>"<?php echo $bg ? ' style="background:url(' . esc_url( $bg ) . ') no-repeat center 0 / cover #f5f5f5"' : ''; ?>><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<div class="md-features <?php echo esc_attr( $columns ); ?>"><div class="features-module <?php echo esc_attr( $type ); ?>"><?php foreach ( $items as $item ) { echo $this->render_item( is_array( $item ) ? $item : array() ); } ?></div></div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function render_item( $item ) {
		$image=$this->media_url( $item['item_img'] ?? '' ); $title=!empty( $item['item_title'] ) ? sanitize_text_field( $item['item_title'] ) : ''; $desc=!empty( $item['item_desc'] ) ? wp_kses_post( $item['item_desc'] ) : ''; $link=$this->link_data( $item['item_url'] ?? '' );
		ob_start(); ?><div class="features-item"><?php if ( $title || $image ) : ?><div class="item-img"><?php if ( $image ) : ?><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $this->image_alt( $image ) ); ?>"><?php endif; ?><?php if ( $title ) : ?><span><?php echo esc_html( $title ); ?></span><?php endif; ?><?php if ( $link['url'] ) : ?><a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?> aria-label="<?php echo esc_attr( $title ); ?>"></a><?php endif; ?></div><?php endif; ?><?php if ( $desc ) : ?><div class="item-text"><?php echo wpautop( $desc ); ?></div><?php endif; ?></div><?php return ob_get_clean();
	}

	private function media_url( $v ) { return is_array( $v ) ? ( $v['url'] ?? '' ) : ( is_string( $v ) ? $v : '' ); }
	private function image_alt( $url ) { return function_exists( 'dahuzi_get_image_alt' ) ? dahuzi_get_image_alt( $url ) : ''; }
	private function link_data( $v ) { if ( is_array( $v ) ) { $url=$v['link'] ?? ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); } else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; } $rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; } return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) ); }

	public function getTemplate() {
		return '
		<# var items=_.isArray(data.features_items)?data.features_items:[];var bg=data.bg_img&&typeof data.bg_img==="object"?(data.bg_img.url||""):(data.bg_img||"");function autop(value){value=String(value||"").replace(/\\r\\n?/g,"\\n").trim();if(!value){return "";}if(/<p(?:\\s|>)/i.test(value)){return value;}return value.split(/\\n\\s*\\n+/).map(function(paragraph){return "<p>"+paragraph.trim().replace(/\\n/g,"<br>")+"</p>";}).join("");} #><# if(items.length){ #><div class="module-theme-features-2"><div class="section<# if(bg){ #> mbbg-img<# } #>"<# if(bg){ #> style="background:url({{ bg }}) no-repeat center 0 / cover #f5f5f5"<# } #>><div class="container"><# if((data.module_title||data.module_desc)){ #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><div class="md-features {{ data.features_style || "style_4" }}"><div class="features-module {{ data.features_type || "features-normal" }}"><# _.forEach(items,function(item){item=item||{};var image=item.item_img&&typeof item.item_img==="object"?(item.item_img.url||""):(item.item_img||"");var link=item.item_url&&typeof item.item_url==="object"?(item.item_url.link||""):(item.item_url||""); #><div class="features-item"><# if(item.item_title||image){ #><div class="item-img"><# if(image){ #><img loading="lazy" src="{{ image }}" alt=""><# } #><# if(item.item_title){ #><span>{{ item.item_title }}</span><# } #><# if(link){ #><a href="{{ link }}"></a><# } #></div><# } #><# if(item.item_desc){ #><div class="item-text">{{{ autop(item.item_desc) }}}</div><# } #></div><# }); #></div></div></div></div></div><# } #>';
	}
}
