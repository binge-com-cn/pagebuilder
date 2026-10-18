<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Text {
	public function get_name() { return 'theme_text'; }
	public function get_title() { return '文本内容'; }
	public function get_class() { return 'xtpb-theme-text'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-text/assets/css/frontend.css' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-text/assets/img/modules-text.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'文本内容', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'text_style'=>array( 'type'=>'select', 'title'=>'内容对齐', 'values'=>array( 'text-left'=>'左对齐', 'text-center'=>'居中' ), 'std'=>'text-left', 'section'=>'模块设置' ),
			'item_content'=>array( 'type'=>'editor', 'title'=>'内容', 'std'=>'<p>在这里输入文本内容。</p>', 'section'=>'内容设置' ),
			'item_btn_text'=>array( 'type'=>'text', 'title'=>'按钮文本', 'std'=>'', 'section'=>'按钮设置' ),
			'item_btn_url'=>array( 'type'=>'link', 'title'=>'按钮链接', 'std'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ), 'depends'=>array( array( 'item_btn_text', '!=', '' ) ), 'section'=>'按钮设置' ),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$content=isset( $s['item_content'] ) && is_string( $s['item_content'] ) ? $s['item_content'] : '';
		if ( '' === trim( $content ) && empty( $s['item_btn_text'] ) ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$style=isset( $s['text_style'] ) && 'text-center' === $s['text_style'] ? 'text-center' : 'text-left';
		$link=$this->link_data( isset( $s['item_btn_url'] ) ? $s['item_btn_url'] : '' );
		ob_start(); ?>
		<div class="module-theme-text"><div class="section"><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<div class="text-module <?php echo esc_attr( $style ); ?>"><?php echo do_shortcode( $content ); ?><?php if ( ! empty( $s['item_btn_text'] ) && $link['url'] ) : ?><div class="text-btn"><a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>><?php echo esc_html( $s['item_btn_text'] ); ?></a></div><?php endif; ?></div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function link_data( $v ) { if ( is_array( $v ) ) { $url=isset( $v['link'] ) ? $v['link'] : ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); } else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; } $rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; } return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) ); }

	public function getTemplate() {
		return '
		<# var style=data.text_style==="text-center"?"text-center":"text-left";var link=data.item_btn_url&&typeof data.item_btn_url==="object"?(data.item_btn_url.link||""):(data.item_btn_url||""); #><div class="module-theme-text"><div class="section"><div class="container"><# if((data.module_title||data.module_desc)){ #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><div class="text-module {{ style }}">{{{ data.item_content||"" }}}<# if(data.item_btn_text&&link){ #><div class="text-btn"><span>{{ data.item_btn_text }}</span></div><# } #></div></div></div></div>';
	}
}
