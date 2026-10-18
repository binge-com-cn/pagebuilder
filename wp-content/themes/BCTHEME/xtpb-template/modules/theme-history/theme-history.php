<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_History {
	public function get_name() { return 'theme_history'; }
	public function get_title() { return '时间轴'; }
	public function get_class() { return 'xtpb-theme-history'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-history/assets/css/frontend.css' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-history/assets/img/modules-history.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'发展历程', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'module_bg'=>array( 'type'=>'media', 'title'=>'背景图片', 'std'=>array( 'url'=>'' ), 'section'=>'模块设置' ),
			'history_items'=>array(
				'type'=>'repeatable', 'title'=>'时间轴项目', 'label'=>'item_title', 'section'=>'内容设置',
				'std'=>array( array( 'item_date'=>date( 'Y' ), 'item_title'=>'发展历程标题', 'item_desc'=>'填写发展历程内容。' ) ),
				'attr'=>array(
					'item_date'=>array( 'type'=>'text', 'title'=>'日期' ),
					'item_title'=>array( 'type'=>'text', 'title'=>'标题' ),
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
		ob_start(); ?>
		<div class="module-theme-history"><div class="section"<?php echo $bg ? ' style="background:url(' . esc_url( $bg ) . ') no-repeat center 0 / cover"' : ''; ?>><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title<?php echo $bg ? ' text-colour-white' : ''; ?>"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<div class="main-timeline"><?php foreach ( $items as $item ) : $item=is_array( $item ) ? $item : array(); ?><div class="timeline"><div class="timeline-content"><div class="content"><?php if ( ! empty( $item['item_date'] ) ) : ?><span class="year"><?php echo esc_html( $item['item_date'] ); ?></span><?php endif; ?><?php if ( ! empty( $item['item_title'] ) ) : ?><h4 class="title"><?php echo esc_html( $item['item_title'] ); ?></h4><?php endif; ?><?php if ( ! empty( $item['item_desc'] ) ) : ?><div class="description"><?php echo wpautop( wp_kses_post( $item['item_desc'] ) ); ?></div><?php endif; ?><div class="icon"></div></div></div></div><?php endforeach; ?></div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function media_url( $v ) { return is_array( $v ) ? ( $v['url'] ?? '' ) : ( is_string( $v ) ? $v : '' ); }

	public function getTemplate() {
		return '
		<# var items=_.isArray(data.history_items)?data.history_items:[];var bg=data.module_bg&&typeof data.module_bg==="object"?(data.module_bg.url||""):(data.module_bg||""); #><# if(items.length){ #><div class="module-theme-history"><div class="section"<# if(bg){ #> style="background:url({{ bg }}) no-repeat center 0 / cover"<# } #>><div class="container"><# if((data.module_title||data.module_desc)){ #><div class="section-title<# if(bg){ #> text-colour-white<# } #>"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><div class="main-timeline"><# _.forEach(items,function(item){item=item||{}; #><div class="timeline"><div class="timeline-content"><div class="content"><# if(item.item_date){ #><span class="year">{{ item.item_date }}</span><# } #><# if(item.item_title){ #><h4 class="title">{{ item.item_title }}</h4><# } #><# if(item.item_desc){ #><div class="description">{{{ item.item_desc }}}</div><# } #><div class="icon"></div></div></div></div><# }); #></div></div></div></div><# } #>';
	}
}
