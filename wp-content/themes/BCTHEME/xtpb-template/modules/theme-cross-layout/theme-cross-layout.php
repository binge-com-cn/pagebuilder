<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Cross_Layout {
	public function get_name() { return 'theme_cross_layout'; }
	public function get_title() { return '图文交叉布局'; }
	public function get_class() { return 'xtpb-theme-cross-layout'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-cross-layout/assets/css/frontend.css' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-cross-layout/assets/img/modules-cross-layout.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'图文标题', 'section'=>'模块设置' ),
			'banner_style'=>array( 'type'=>'select', 'title'=>'模块样式', 'values'=>array( 'style_1'=>'图片靠右显示', 'style_2'=>'图片靠左显示' ), 'std'=>'style_1', 'section'=>'模块设置' ),
			'banner_number'=>array( 'type'=>'text', 'title'=>'模块编号', 'std'=>'01', 'section'=>'内容设置' ),
			'banner_subtitle'=>array( 'type'=>'text', 'title'=>'模块小标题', 'std'=>'SUBTITLE', 'section'=>'内容设置' ),
			'banner_desc'=>array( 'type'=>'editor', 'title'=>'模块描述', 'std'=>'', 'section'=>'内容设置' ),
			'banner_desc_icon'=>array( 'type'=>'switch', 'title'=>'描述文本图标', 'desc'=>'在每段描述文本前面显示图标', 'std'=>0, 'section'=>'内容设置' ),
			'banner_type'=>array( 'type'=>'select', 'title'=>'媒体类型', 'values'=>array( 'img'=>'图片', 'video'=>'视频' ), 'std'=>'img', 'section'=>'媒体设置' ),
			'banner_img'=>array( 'type'=>'media', 'title'=>'特色图片', 'std'=>array( 'url'=>'' ), 'section'=>'媒体设置' ),
			'banner_video'=>array( 'type'=>'text', 'title'=>'视频地址', 'std'=>'', 'depends'=>array( array( 'banner_type', '=', 'video' ) ), 'section'=>'媒体设置' ),
			'banner_btn1_text'=>array( 'type'=>'text', 'title'=>'按钮-1 文本', 'std'=>'', 'section'=>'按钮设置' ),
			'banner_btn1_url'=>array( 'type'=>'link', 'title'=>'按钮-1 链接', 'std'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ), 'depends'=>array( array( 'banner_btn1_text', '!=', '' ) ), 'section'=>'按钮设置' ),
			'banner_btn2_text'=>array( 'type'=>'text', 'title'=>'按钮-2 文本', 'std'=>'', 'section'=>'按钮设置' ),
			'banner_btn2_url'=>array( 'type'=>'link', 'title'=>'按钮-2 链接', 'std'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ), 'depends'=>array( array( 'banner_btn2_text', '!=', '' ) ), 'section'=>'按钮设置' ),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$style=!empty( $s['banner_style'] ) ? sanitize_html_class( $s['banner_style'] ) : 'style_1';
		$image=$this->media_url( $s['banner_img'] ?? '' );
		$video=!empty( $s['banner_video'] ) ? esc_url( $s['banner_video'] ) : '';
		ob_start(); ?>
		<div class="module-theme-cross-layout"><div class="section"><div class="container"><div class="cross-layout <?php echo esc_attr( $style ); ?>">
			<div class="cross-text">
				<h2><?php if ( ! empty( $s['banner_number'] ) ) : ?><i><?php echo esc_html( $s['banner_number'] ); ?></i><?php endif; ?><?php if ( $title ) : ?><span><?php echo esc_html( $title ); ?><?php if ( ! empty( $s['banner_subtitle'] ) ) : ?><small><?php echo esc_html( $s['banner_subtitle'] ); ?></small><?php endif; ?></span><?php endif; ?></h2>
				<?php echo $this->render_media( $image, $video, $s, 'cross-img-mobile' ); ?>
				<div class="cross-desc<?php echo ! empty( $s['banner_desc_icon'] ) ? ' cross-icon' : ''; ?><?php echo empty( $s['banner_number'] ) ? ' cross-no-number' : ''; ?>">
					<?php echo ! empty( $s['banner_desc'] ) ? do_shortcode( wp_kses_post( $s['banner_desc'] ) ) : ''; ?>
					<?php echo $this->render_buttons( $s ); ?>
				</div>
			</div>
			<?php echo $this->render_media( $image, $video, $s, 'cross-img-desktop' ); ?>
		</div></div></div></div>
		<?php return ob_get_clean();
	}

	private function render_media( $image, $video, $s, $class ) {
		if ( ! $image && ! ( 'video' === ( $s['banner_type'] ?? 'img' ) && $video ) ) { return ''; }
		ob_start(); ?><div class="cross-img <?php echo esc_attr( $class ); ?>"><?php if ( $image ) : ?><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $this->image_alt( $image ) ); ?>"><?php endif; ?><?php if ( 'video' === ( $s['banner_type'] ?? 'img' ) && $video ) : ?><a data-fancybox data-type="youtube" href="<?php echo esc_url( $video ); ?>" class="play-view" rel="nofollow"><span class="video-play-icon"><i class="ri-video-fill"></i></span></a><?php endif; ?></div><?php return ob_get_clean();
	}

	private function render_buttons( $s ) {
		if ( empty( $s['banner_btn1_text'] ) && empty( $s['banner_btn2_text'] ) ) { return ''; }
		$out='<div class="btn">';
		foreach ( array( 1, 2 ) as $i ) {
			$text=!empty( $s[ 'banner_btn'.$i.'_text' ] ) ? sanitize_text_field( $s[ 'banner_btn'.$i.'_text' ] ) : '';
			$link=$this->link_data( $s[ 'banner_btn'.$i.'_url' ] ?? '' );
			if ( $text ) { $out.='<a href="'.esc_url( $link['url'] ).'"'.( $link['target'] ? ' target="_blank"' : '' ).( $link['rel'] ? ' rel="'.esc_attr( $link['rel'] ).'"' : '' ).'>'.esc_html( $text ).'</a>'; }
		}
		return $out.'</div>';
	}

	private function media_url( $v ) { return is_array( $v ) ? ( $v['url'] ?? '' ) : ( is_string( $v ) ? $v : '' ); }
	private function image_alt( $url ) { return function_exists( 'dahuzi_get_image_alt' ) ? dahuzi_get_image_alt( $url ) : ''; }
	private function link_data( $v ) {
		if ( is_array( $v ) ) { $url=$v['link'] ?? ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); } else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; }
		$rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; }
		return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) );
	}

	public function getTemplate() {
		return '
		<# var image=data.banner_img&&typeof data.banner_img==="object"?(data.banner_img.url||""):(data.banner_img||""); var showVideo=data.banner_type==="video"&&data.banner_video; function media(css){ if(!image&&!showVideo){return "";} var html="<div class=\\"cross-img "+css+"\\">"; if(image){html+="<img loading=\\"lazy\\" src=\\""+_.escape(image)+"\\" alt=\\"\\">";} if(showVideo){html+="<a data-fancybox data-type=\\"youtube\\" href=\\""+_.escape(data.banner_video)+"\\" class=\\"play-view\\" rel=\\"nofollow\\"><span class=\\"video-play-icon\\"><i class=\\"ri-video-fill\\"></i></span></a>";} return html+"</div>";} #>
		<div class="module-theme-cross-layout"><div class="section"><div class="container"><div class="cross-layout {{ data.banner_style || "style_1" }}"><div class="cross-text"><h2><# if(data.banner_number){ #><i>{{ data.banner_number }}</i><# } #><# if(data.module_title){ #><span>{{ data.module_title }}<# if(data.banner_subtitle){ #><small>{{ data.banner_subtitle }}</small><# } #></span><# } #></h2><# print(media("cross-img-mobile")); #><div class="cross-desc<# if(data.banner_desc_icon){ #> cross-icon<# } #><# if(!data.banner_number){ #> cross-no-number<# } #>"><# if(data.banner_desc){ #>{{{ data.banner_desc }}}<# } #><# if(data.banner_btn1_text||data.banner_btn2_text){ #><div class="btn"><# if(data.banner_btn1_text){ var l1=typeof data.banner_btn1_url==="object"?(data.banner_btn1_url.link||""):(data.banner_btn1_url||""); #><a href="{{ l1 }}">{{ data.banner_btn1_text }}</a><# } #><# if(data.banner_btn2_text){ var l2=typeof data.banner_btn2_url==="object"?(data.banner_btn2_url.link||""):(data.banner_btn2_url||""); #><a href="{{ l2 }}">{{ data.banner_btn2_text }}</a><# } #></div><# } #></div></div><# print(media("cross-img-desktop")); #></div></div></div></div>';
	}
}
