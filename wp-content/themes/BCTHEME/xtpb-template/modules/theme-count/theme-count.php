<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Count {
	public function get_name() { return 'theme_count'; }
	public function get_title() { return '滚动数字'; }
	public function get_class() { return 'xtpb-theme-count'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-count/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-count/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-count/assets/img/modules-count.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title' => array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'数据展示', 'section'=>'模块设置' ),
			'module_desc' => array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'module_bg' => array( 'type'=>'media', 'title'=>'背景图片', 'std'=>array( 'url'=>'' ), 'section'=>'模块设置' ),
			'count_style' => array(
				'type'=>'select', 'title'=>'数字项显示',
				'values'=>array( 'style_2'=>'两列', 'style_3'=>'三列', 'style_4'=>'四列' ),
				'std'=>'style_4', 'section'=>'模块设置',
			),
			'text_color' => array(
				'type'=>'select', 'title'=>'文本颜色',
				'values'=>array( 'black'=>'黑色', 'white'=>'白色' ),
				'std'=>'black', 'section'=>'模块设置',
			),
			'text_alignment' => array(
				'type'=>'select', 'title'=>'文本对齐',
				'values'=>array( 'text-left'=>'左对齐', 'text-center'=>'居中' ),
				'std'=>'text-left', 'section'=>'模块设置',
			),
			'count_subtitle' => array( 'type'=>'text', 'title'=>'小标题', 'std'=>'', 'section'=>'文本设置' ),
			'count_headline' => array( 'type'=>'text', 'title'=>'大标题', 'std'=>'', 'section'=>'文本设置' ),
			'count_content' => array( 'type'=>'textarea', 'title'=>'文本内容', 'std'=>'', 'section'=>'文本设置' ),
			'count_btn_text' => array( 'type'=>'text', 'title'=>'按钮标题', 'std'=>'', 'section'=>'文本设置' ),
			'count_btn_url' => array(
				'type'=>'link', 'title'=>'按钮链接',
				'std'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ),
				'depends'=>array( array( 'count_btn_text', '!=', '' ) ), 'section'=>'文本设置',
			),
			'count_items' => array(
				'type'=>'repeatable', 'title'=>'数字项目', 'label'=>'item_desc', 'section'=>'内容设置',
				'std'=>array( array( 'item_img'=>array( 'url'=>'' ), 'item_number'=>100, 'item_number_tag'=>'+', 'item_speed'=>1000, 'item_desc'=>'项目数量' ) ),
				'attr'=>array(
					'item_img'=>array( 'type'=>'media', 'title'=>'图标', 'desc'=>'建议尺寸：100 × 100 px' ),
					'item_number'=>array( 'type'=>'number', 'title'=>'滚动数字', 'std'=>100 ),
					'item_number_tag'=>array( 'type'=>'text', 'title'=>'数字标签', 'desc'=>'例如：年、万元、+' ),
					'item_speed'=>array( 'type'=>'number', 'title'=>'滚动速度', 'desc'=>'毫秒', 'std'=>1000 ),
					'item_desc'=>array( 'type'=>'text', 'title'=>'数字描述' ),
				),
			),
		);
	}

	public function render( $data=null ) {
		$s = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items = isset( $s['count_items'] ) && is_array( $s['count_items'] ) ? $s['count_items'] : array();
		if ( ! $items ) { return ''; }
		$title = ! empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc = ! empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$bg = $this->media_url( $s['module_bg'] ?? '' );
		$color = ! empty( $s['text_color'] ) ? sanitize_html_class( $s['text_color'] ) : 'black';
		$alignment = ! empty( $s['text_alignment'] ) ? sanitize_html_class( $s['text_alignment'] ) : 'text-left';
		$columns = ! empty( $s['count_style'] ) ? sanitize_html_class( $s['count_style'] ) : 'style_4';
		$link = $this->link_data( $s['count_btn_url'] ?? '' );
		ob_start(); ?>
		<div class="module-theme-count"><div class="section text-color-<?php echo esc_attr( $color ); ?>"<?php echo $bg ? ' style="background:url(' . esc_url( $bg ) . ') no-repeat center 0 / cover"' : ''; ?>><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<?php if ( ! empty( $s['count_subtitle'] ) || ! empty( $s['count_headline'] ) || ! empty( $s['count_content'] ) || ( ! empty( $s['count_btn_text'] ) && $link['url'] ) ) : ?>
				<div class="count-text <?php echo esc_attr( $alignment ); ?>">
					<?php if ( ! empty( $s['count_subtitle'] ) ) : ?><div class="text-sub-title"><span><?php echo esc_html( $s['count_subtitle'] ); ?></span></div><?php endif; ?>
					<?php if ( ! empty( $s['count_headline'] ) ) : ?><div class="text-title"><h3><?php echo esc_html( $s['count_headline'] ); ?></h3></div><?php endif; ?>
					<?php echo ! empty( $s['count_content'] ) ? wpautop( wp_kses_post( $s['count_content'] ) ) : ''; ?>
					<?php if ( ! empty( $s['count_btn_text'] ) && $link['url'] ) : ?><a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>><?php echo esc_html( $s['count_btn_text'] ); ?></a><?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="count-module <?php echo esc_attr( $columns ); ?>">
				<?php foreach ( $items as $item ) : $item = is_array( $item ) ? $item : array(); $image = $this->media_url( $item['item_img'] ?? '' ); ?>
					<div class="count-item">
						<?php if ( $image ) : ?><div class="item-img"><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $this->image_alt( $image ) ); ?>"></div><?php endif; ?>
						<?php if ( isset( $item['item_number'] ) && '' !== $item['item_number'] ) : ?><div><span class="timer item-number" data-to="<?php echo esc_attr( floatval( $item['item_number'] ) ); ?>" data-speed="<?php echo esc_attr( max( 0, absint( $item['item_speed'] ?? 1000 ) ) ); ?>">0</span><?php if ( ! empty( $item['item_number_tag'] ) ) : ?><span class="item-number-tag"><?php echo esc_html( $item['item_number_tag'] ); ?></span><?php endif; ?></div><?php endif; ?>
						<?php if ( ! empty( $item['item_desc'] ) ) : ?><p class="item-desc"><?php echo esc_html( $item['item_desc'] ); ?></p><?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function media_url( $v ) { return is_array( $v ) ? ( $v['url'] ?? '' ) : ( is_string( $v ) ? $v : '' ); }
	private function image_alt( $url ) { return function_exists( 'dahuzi_get_image_alt' ) ? dahuzi_get_image_alt( $url ) : ''; }
	private function link_data( $v ) {
		if ( is_array( $v ) ) { $url=$v['link'] ?? ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); }
		else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; }
		$rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; }
		return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) );
	}

	public function getTemplate() {
		return '
		<# var items=_.isArray(data.count_items)?data.count_items:[]; var bg=data.module_bg&&typeof data.module_bg==="object"?(data.module_bg.url||""):(data.module_bg||""); var button=data.count_btn_url&&typeof data.count_btn_url==="object"?data.count_btn_url:{link:data.count_btn_url||""}; #>
		<# if (items.length) { #><div class="module-theme-count"><div class="section text-color-{{ data.text_color || "black" }}"<# if (bg) { #> style="background:url({{ bg }}) no-repeat center 0 / cover"<# } #>><div class="container">
		<# if ((data.module_title||data.module_desc)) { #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #>
		<# if(data.count_subtitle||data.count_headline||data.count_content||(data.count_btn_text&&button.link)){ #><div class="count-text {{ data.text_alignment || "text-left" }}"><# if(data.count_subtitle){ #><div class="text-sub-title"><span>{{ data.count_subtitle }}</span></div><# } #><# if(data.count_headline){ #><div class="text-title"><h3>{{ data.count_headline }}</h3></div><# } #><# if(data.count_content){ #><p>{{ data.count_content }}</p><# } #><# if(data.count_btn_text&&button.link){ #><a href="{{ button.link }}">{{ data.count_btn_text }}</a><# } #></div><# } #>
		<div class="count-module {{ data.count_style || "style_4" }}"><# _.forEach(items,function(item){item=item||{};var image=item.item_img&&typeof item.item_img==="object"?(item.item_img.url||""):(item.item_img||""); #><div class="count-item"><# if(image){ #><div class="item-img"><img loading="lazy" src="{{ image }}" alt=""></div><# } #><# if(item.item_number!==""&&item.item_number!==undefined){ #><div><span class="timer item-number" data-to="{{ item.item_number }}" data-speed="{{ item.item_speed || 1000 }}">{{ item.item_number }}</span><# if(item.item_number_tag){ #><span class="item-number-tag">{{ item.item_number_tag }}</span><# } #></div><# } #><# if(item.item_desc){ #><p class="item-desc">{{ item.item_desc }}</p><# } #></div><# }); #></div>
		</div></div></div><# } #>';
	}
}
