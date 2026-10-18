<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_ImgText_Banner {
	public function get_name() { return 'theme_imgtext_banner'; }
	public function get_title() { return '图文横幅'; }
	public function get_class() { return 'xtpb-theme-imgtext-banner'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-imgtext-banner/assets/css/frontend.css' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-imgtext-banner/assets/img/modules-imgtext-banner.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'准备好开始了吗？', 'section'=>'内容设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'联系我们，获取更多产品与服务信息。', 'section'=>'内容设置' ),
			'banner_style'=>array( 'type'=>'select', 'title'=>'模块样式', 'values'=>array( 'style_1'=>'文本左，按钮右', 'style_2'=>'靠左显示', 'style_3'=>'居中显示' ), 'std'=>'style_1', 'section'=>'模块设置' ),
			'banner_bg'=>array( 'type'=>'media', 'title'=>'背景图片', 'std'=>array( 'url'=>'' ), 'section'=>'模块设置' ),
			'banner_btn_text'=>array( 'type'=>'text', 'title'=>'按钮文本', 'std'=>'联系我们', 'section'=>'按钮设置' ),
			'banner_btn_url'=>array( 'type'=>'link', 'title'=>'按钮链接', 'std'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ), 'depends'=>array( array( 'banner_btn_text', '!=', '' ) ), 'section'=>'按钮设置' ),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? wp_kses_post( $s['module_desc'] ) : '';
		$style=!empty( $s['banner_style'] ) ? sanitize_html_class( $s['banner_style'] ) : 'style_1';
		$bg=$this->media_url( $s['banner_bg'] ?? '' );
		$link=$this->link_data( $s['banner_btn_url'] ?? '' );
		ob_start(); ?>
		<div class="module-theme-imgtext-banner"><div class="module-bg <?php echo esc_attr( $style ); ?>"<?php echo $bg ? ' style="background-image:url(' . esc_url( $bg ) . ')"' : ''; ?>><div class="container"><div class="imgtext-banner"><div class="banner-text"><?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?><?php echo $desc ? wpautop( $desc ) : ''; ?></div><?php if ( ! empty( $s['banner_btn_text'] ) && $link['url'] ) : ?><div class="banner-btn"><a href="<?php echo esc_url( $link['url'] ); ?>" class="btn-transition"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>><?php echo esc_html( $s['banner_btn_text'] ); ?></a></div><?php endif; ?></div></div></div></div>
		<?php return ob_get_clean();
	}

	private function media_url( $v ) { return is_array( $v ) ? ( $v['url'] ?? '' ) : ( is_string( $v ) ? $v : '' ); }
	private function link_data( $v ) { if ( is_array( $v ) ) { $url=$v['link'] ?? ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); } else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; } $rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; } return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) ); }

	public function getTemplate() {
		return '
		<# var bg=data.banner_bg&&typeof data.banner_bg==="object"?(data.banner_bg.url||""):(data.banner_bg||"");var link=data.banner_btn_url&&typeof data.banner_btn_url==="object"?(data.banner_btn_url.link||""):(data.banner_btn_url||""); #><div class="module-theme-imgtext-banner"><div class="module-bg {{ data.banner_style || "style_1" }}"<# if(bg){ #> style="background-image:url({{ bg }})"<# } #>><div class="container"><div class="imgtext-banner"><div class="banner-text"><# if(data.module_title){ #><h2>{{ data.module_title }}</h2><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# if(data.banner_btn_text&&link){ #><div class="banner-btn"><a href="{{ link }}" class="btn-transition">{{ data.banner_btn_text }}</a></div><# } #></div></div></div></div>';
	}
}
