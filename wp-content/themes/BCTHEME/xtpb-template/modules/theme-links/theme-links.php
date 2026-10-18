<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Links {
	public function get_name() { return 'theme_links'; }
	public function get_title() { return '友情链接'; }
	public function get_class() { return 'xtpb-theme-links'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-links/assets/css/frontend.css' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-links/assets/img/modules-links.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'友情链接', 'section'=>'模块设置' ),
			'link_items'=>array(
				'type'=>'repeatable', 'title'=>'链接项目', 'label'=>'text', 'section'=>'内容设置',
				'std'=>array( array( 'text'=>'XinTheme', 'url'=>array( 'link'=>'https://www.xintheme.com/', 'window'=>true, 'nofolow'=>false ) ) ),
				'attr'=>array(
					'text'=>array( 'type'=>'text', 'title'=>'链接文本' ),
					'url'=>array( 'type'=>'link', 'title'=>'跳转链接' ),
				),
			),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items=isset( $s['link_items'] ) && is_array( $s['link_items'] ) ? $s['link_items'] : array();
		if ( ! $items ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		ob_start(); ?>
		<div class="module-theme-links"><div class="section"><div class="container"><div class="md-link"><?php if ( $title ) : ?><h3><?php echo esc_html( $title ); ?></h3><?php endif; ?><ul><?php foreach ( $items as $item ) : $item=is_array( $item ) ? $item : array(); $link=$this->link_data( $item['url'] ?? '' ); if ( empty( $item['text'] ) || ! $link['url'] ) { continue; } ?><li><a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>><?php echo esc_html( $item['text'] ); ?></a></li><?php endforeach; ?></ul></div></div></div></div>
		<?php return ob_get_clean();
	}

	private function link_data( $v ) { if ( is_array( $v ) ) { $url=$v['link'] ?? ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); } else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; } $rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; } return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) ); }

	public function getTemplate() {
		return '
		<# var items=_.isArray(data.link_items)?data.link_items:[]; #><# if(items.length){ #><div class="module-theme-links"><div class="section"><div class="container"><div class="md-link"><# if(data.module_title){ #><h3>{{ data.module_title }}</h3><# } #><ul><# _.forEach(items,function(item){item=item||{};var link=item.url&&typeof item.url==="object"?(item.url.link||""):(item.url||"");if(item.text&&link){ #><li><a href="{{ link }}">{{ item.text }}</a></li><# }}); #></ul></div></div></div></div><# } #>';
	}
}
