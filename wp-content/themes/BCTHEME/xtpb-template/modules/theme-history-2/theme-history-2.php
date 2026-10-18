<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_History_2 {
	public function get_name() { return 'theme_history_2'; }
	public function get_title() { return '时间轴-2'; }
	public function get_class() { return 'xtpb-theme-history-2'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-history-2/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-history-2/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-history-2/assets/img/modules-history-2.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'发展历程', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'module_bg'=>array( 'type'=>'media', 'title'=>'背景图片', 'std'=>array( 'url'=>'' ), 'section'=>'模块设置' ),
			'history_prev_next'=>array( 'type'=>'select', 'title'=>'切换按钮', 'values'=>array( 'style_1'=>'显示左右箭头', 'style_2'=>'不显示左右箭头' ), 'std'=>'style_1', 'section'=>'时间轴设置' ),
			'history_position'=>array( 'type'=>'select', 'title'=>'年份导航位置', 'values'=>array( 'position_top'=>'内容顶部', 'position_bottom'=>'内容底部' ), 'std'=>'position_top', 'section'=>'时间轴设置' ),
			'history_text_color'=>array( 'type'=>'color', 'title'=>'文本颜色', 'std'=>'#000000', 'section'=>'颜色设置' ),
			'history_highlight_color'=>array( 'type'=>'color', 'title'=>'高亮颜色', 'std'=>'', 'section'=>'颜色设置' ),
			'history_items'=>array(
				'type'=>'repeatable', 'title'=>'时间轴项目', 'label'=>'item_date', 'section'=>'内容设置',
				'std'=>array( array( 'item_date'=>date( 'Y' ), 'item_title'=>'发展历程标题', 'item_img'=>array( 'url'=>'' ), 'item_desc'=>'填写发展历程内容。' ) ),
				'attr'=>array(
					'item_date'=>array( 'type'=>'text', 'title'=>'日期' ),
					'item_title'=>array( 'type'=>'text', 'title'=>'标题' ),
					'item_img'=>array( 'type'=>'media', 'title'=>'特色图片' ),
					'item_desc'=>array( 'type'=>'editor', 'title'=>'描述' ),
				),
			),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items=isset( $s['history_items'] ) && is_array( $s['history_items'] ) ? $s['history_items'] : array();
		if ( ! $items ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$bg=$this->media_url( $s['module_bg'] ?? '' );
		$position='position_bottom' === ( $s['history_position'] ?? '' ) ? 'position_bottom' : 'position_top';
		$show_nav='style_2' !== ( $s['history_prev_next'] ?? 'style_1' );
		$text_color=sanitize_hex_color( $s['history_text_color'] ?? '' ) ?: '#000000';
		$custom='--theme-history2-color:'.$text_color.';';
		if ( ! empty( $s['history_highlight_color'] ) && sanitize_hex_color( $s['history_highlight_color'] ) ) { $custom.='--theme-history2-hover-color:'.sanitize_hex_color( $s['history_highlight_color'] ).';'; }
		ob_start(); ?>
		<div class="module-theme-history-2"><div class="section theme-history2"<?php echo $bg ? ' style="background:url(' . esc_url( $bg ) . ') no-repeat center 0 / cover"' : ''; ?>><div class="container" style="<?php echo esc_attr( $custom ); ?>">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<?php if ( 'position_top' === $position ) { echo $this->date_slider( $items, $position, $show_nav ); } ?>
			<div class="history-module"><div class="swiper history-swiper"><div class="swiper-wrapper"><?php foreach ( $items as $item ) : $item=is_array( $item ) ? $item : array(); $image=$this->media_url( $item['item_img'] ?? '' ); ?><div class="swiper-slide"><div class="history-details"><?php if ( $image ) : ?><div class="history-img"><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $this->image_alt( $image ) ); ?>"></div><?php endif; ?><?php if ( ! empty( $item['item_title'] ) || ! empty( $item['item_desc'] ) ) : ?><div class="history-text"><?php if ( ! empty( $item['item_title'] ) ) : ?><h3><?php echo esc_html( $item['item_title'] ); ?></h3><?php endif; ?><?php echo ! empty( $item['item_desc'] ) ? do_shortcode( wp_kses_post( $item['item_desc'] ) ) : ''; ?></div><?php endif; ?></div></div><?php endforeach; ?></div></div></div>
			<?php if ( 'position_bottom' === $position ) { echo $this->date_slider( $items, $position, $show_nav ); } ?>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function date_slider( $items, $position, $show_nav ) {
		ob_start(); ?><div class="swiper history-swiper-date history-<?php echo esc_attr( $position ); ?><?php echo $show_nav ? '' : ' history-prev_next-none'; ?>"><div class="swiper-wrapper"><?php foreach ( $items as $item ) : ?><div class="swiper-slide"><div class="history-date"><p><?php echo esc_html( $item['item_date'] ?? '' ); ?></p></div></div><?php endforeach; ?></div><?php if ( $show_nav ) : ?><div class="history-nav"><div class="swiper-button-prev history-prev"></div><div class="swiper-button-next history-next"></div></div><?php endif; ?></div><?php return ob_get_clean();
	}

	private function media_url( $v ) { return is_array( $v ) ? ( $v['url'] ?? '' ) : ( is_string( $v ) ? $v : '' ); }
	private function image_alt( $url ) { return function_exists( 'dahuzi_get_image_alt' ) ? dahuzi_get_image_alt( $url ) : ''; }

	public function getTemplate() {
		return '
		<# var items=_.isArray(data.history_items)?data.history_items:[];var bg=data.module_bg&&typeof data.module_bg==="object"?(data.module_bg.url||""):(data.module_bg||"");var pos=data.history_position||"position_top";function dates(){var html="<div class=\\"swiper history-swiper-date history-"+pos+(data.history_prev_next==="style_2"?" history-prev_next-none":"")+"\\"><div class=\\"swiper-wrapper\\">";_.forEach(items,function(item){html+="<div class=\\"swiper-slide\\"><div class=\\"history-date\\"><p>"+_.escape((item||{}).item_date||"")+"</p></div></div>";});html+="</div>";if(data.history_prev_next!=="style_2"){html+="<div class=\\"history-nav\\"><div class=\\"swiper-button-prev history-prev\\"></div><div class=\\"swiper-button-next history-next\\"></div></div>";}return html+"</div>";} #><# if(items.length){ #><div class="module-theme-history-2"><div class="section theme-history2"<# if(bg){ #> style="background:url({{ bg }}) no-repeat center 0 / cover"<# } #>><div class="container" style="--theme-history2-color:{{ data.history_text_color || "#000000" }};--theme-history2-hover-color:{{ data.history_highlight_color || "var(--main-color)" }};"><# if((data.module_title||data.module_desc)){ #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><# if(pos==="position_top"){print(dates());} #><div class="history-module"><div class="swiper history-swiper"><div class="swiper-wrapper"><# _.forEach(items,function(item){item=item||{};var image=item.item_img&&typeof item.item_img==="object"?(item.item_img.url||""):(item.item_img||""); #><div class="swiper-slide"><div class="history-details"><# if(image){ #><div class="history-img"><img loading="lazy" src="{{ image }}" alt=""></div><# } #><# if(item.item_title||item.item_desc){ #><div class="history-text"><# if(item.item_title){ #><h3>{{ item.item_title }}</h3><# } #>{{{ item.item_desc || "" }}}</div><# } #></div></div><# }); #></div></div></div><# if(pos==="position_bottom"){print(dates());} #></div></div></div><# } #>';
	}
}
