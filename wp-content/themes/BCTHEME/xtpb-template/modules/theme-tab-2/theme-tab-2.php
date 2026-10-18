<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Tab_2 {
	public function get_name() { return 'theme_tab_2'; }
	public function get_title() { return '自定义 TAB（左侧菜单）'; }
	public function get_class() { return 'xtpb-theme-tab-2'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-tab-2/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-tab-2/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-tab-2/assets/img/modules-tab-2.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'自定义 TAB', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'radius'=>array( 'type'=>'number', 'title'=>'圆角', 'desc'=>'px，建议 8 px', 'std'=>8, 'section'=>'样式设置' ),
			'tab_items'=>array(
				'type'=>'repeatable', 'title'=>'TAB 内容', 'label'=>'item_tab_menu', 'section'=>'内容设置',
				'std'=>array( array( 'item_tab_menu'=>'TAB 菜单', 'item_title'=>'内容标题', 'item_desc'=>'<p>这里是 TAB 的内容。</p>', 'item_btn_text'=>'查看更多', 'item_btn_url'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ) ) ),
				'attr'=>array(
					'item_tab_menu'=>array( 'type'=>'text', 'title'=>'TAB 菜单名' ),
					'item_title'=>array( 'type'=>'text', 'title'=>'标题' ),
					'item_desc'=>array( 'type'=>'editor', 'title'=>'描述' ),
					'item_btn_text'=>array( 'type'=>'text', 'title'=>'按钮文本' ),
					'item_btn_url'=>array( 'type'=>'link', 'title'=>'按钮跳转链接', 'std'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ) ),
				),
			),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items=isset( $s['tab_items'] ) && is_array( $s['tab_items'] ) ? array_values( array_filter( $s['tab_items'], function( $item ){ return is_array( $item ) && ! empty( $item['item_tab_menu'] ); } ) ) : array();
		if ( ! $items ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$radius=isset( $s['radius'] ) ? min( 100, absint( $s['radius'] ) ) : 8;
		ob_start(); ?>
		<div class="module-theme-tab-2"><div class="section" style="--btn-radius:<?php echo esc_attr( $radius ); ?>px"><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<div class="module-tab-wrapper"><div class="module-tab-menu" role="tablist"><ul><?php foreach ( $items as $index=>$item ) : ?><li class="<?php echo 0 === $index ? 'selected' : ''; ?>"><button type="button" role="tab" aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"><?php echo esc_html( $item['item_tab_menu'] ); ?></button></li><?php endforeach; ?></ul></div><div class="module-tab-box">
				<?php foreach ( $items as $index=>$item ) : $link=$this->link_data( isset( $item['item_btn_url'] ) ? $item['item_btn_url'] : '' ); ?><div class="tab-item" role="tabpanel"<?php echo 0 === $index ? '' : ' hidden'; ?>><div class="tab-text"><?php if ( ! empty( $item['item_title'] ) ) : ?><h3><?php echo esc_html( $item['item_title'] ); ?></h3><?php endif; ?><?php if ( ! empty( $item['item_desc'] ) ) : ?><?php echo apply_filters( 'the_content', $item['item_desc'] ); ?><?php endif; ?><?php if ( ! empty( $item['item_btn_text'] ) && $link['url'] ) : ?><div class="tab-btn"><a href="<?php echo esc_url( $link['url'] ); ?>" class="see-more"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>><span class="see-more-color"></span><p><?php echo esc_html( $item['item_btn_text'] ); ?> <i class="ri-arrow-right-line" aria-hidden="true"></i></p></a></div><?php endif; ?></div></div><?php endforeach; ?>
			</div></div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function link_data( $v ) { if ( is_array( $v ) ) { $url=isset( $v['link'] ) ? $v['link'] : ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); } else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; } $rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; } return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) ); }

	public function getTemplate() {
		return '
		<# var items=_.filter(_.isArray(data.tab_items)?data.tab_items:[],function(item){return item&&item.item_tab_menu;});var radius=Math.min(100,Math.max(0,parseInt(data.radius||0,10))); #><# if(items.length){ #><div class="module-theme-tab-2"><div class="section" style="--btn-radius:{{ radius }}px"><div class="container"><# if((data.module_title||data.module_desc)){ #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><div class="module-tab-wrapper"><div class="module-tab-menu"><ul><# _.forEach(items,function(item,index){ #><li<# if(index===0){ #> class="selected"<# } #>><button type="button">{{ item.item_tab_menu }}</button></li><# }); #></ul></div><div class="module-tab-box"><# _.forEach(items,function(item,index){var link=item.item_btn_url&&typeof item.item_btn_url==="object"?(item.item_btn_url.link||""):(item.item_btn_url||""); #><div class="tab-item"<# if(index!==0){ #> hidden<# } #>><div class="tab-text"><# if(item.item_title){ #><h3>{{ item.item_title }}</h3><# } #><# if(item.item_desc){ #>{{{ item.item_desc }}}<# } #><# if(item.item_btn_text&&link){ #><div class="tab-btn"><span class="see-more"><span class="see-more-color"></span><p>{{ item.item_btn_text }} <i class="ri-arrow-right-line"></i></p></span></div><# } #></div></div><# }); #></div></div></div></div></div><# } #>';
	}
}
