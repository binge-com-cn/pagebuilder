<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Pricing {
	public function get_name() { return 'theme_pricing'; }
	public function get_title() { return '价格表'; }
	public function get_class() { return 'xtpb-theme-pricing'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-pricing/assets/css/frontend.css' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-pricing/assets/img/modules-pricing.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'价格方案', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'pricing_list'=>array( 'type'=>'select', 'title'=>'显示列数', 'values'=>array( '2'=>'2 列', '3'=>'3 列', '4'=>'4 列' ), 'std'=>'3', 'section'=>'模块设置' ),
			'pricing_items'=>array(
				'type'=>'repeatable', 'title'=>'价格方案', 'label'=>'item_title', 'section'=>'内容设置',
				'std'=>array( array(
					'item_title'=>'专业版', 'item_desc'=>'适合成长中的企业', 'item_tag'=>'最受欢迎', 'item_price'=>'¥2000<span>/年</span>', 'item_price_d'=>'按年付费',
					'item_btn_text'=>'联系我们', 'item_btn_url'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ), 'item_btn_color'=>'#12715b', 'item_btn_desc'=>'',
					'item_details_title'=>'服务项目', 'item_details'=>array( array( 'details_icon'=>'checkbox', 'details_text'=>'服务项目内容' ) ),
				) ),
				'attr'=>array(
					'item_title'=>array( 'type'=>'text', 'title'=>'标题', 'desc'=>'例如：免费版 / 专业版 / 企业版' ),
					'item_desc'=>array( 'type'=>'text', 'title'=>'描述' ),
					'item_tag'=>array( 'type'=>'text', 'title'=>'特殊标签', 'desc'=>'例如：最受欢迎的' ),
					'item_price'=>array( 'type'=>'text', 'title'=>'价格', 'desc'=>'支持安全 HTML，例如：¥2000&lt;span&gt;/年&lt;/span&gt;' ),
					'item_price_d'=>array( 'type'=>'text', 'title'=>'价格描述' ),
					'item_btn_text'=>array( 'type'=>'text', 'title'=>'按钮文本' ),
					'item_btn_url'=>array( 'type'=>'link', 'title'=>'按钮跳转链接', 'std'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ) ),
					'item_btn_color'=>array( 'type'=>'color', 'title'=>'按钮颜色', 'std'=>'#12715b' ),
					'item_btn_desc'=>array( 'type'=>'text', 'title'=>'按钮描述' ),
					'item_details_title'=>array( 'type'=>'text', 'title'=>'服务项目标题' ),
					'item_details'=>array(
						'type'=>'repeatable', 'title'=>'服务项目', 'label'=>'details_text',
						'std'=>array( array( 'details_icon'=>'checkbox', 'details_text'=>'服务项目内容' ) ),
						'attr'=>array(
							'details_icon'=>array( 'type'=>'select', 'title'=>'状态', 'values'=>array( 'checkbox'=>'✓ 包含', 'close'=>'✗ 不包含' ), 'std'=>'checkbox' ),
							'details_text'=>array( 'type'=>'text', 'title'=>'项目内容' ),
						),
					),
				),
			),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items=isset( $s['pricing_items'] ) && is_array( $s['pricing_items'] ) ? $s['pricing_items'] : array();
		if ( ! $items ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$columns=in_array( (string) ( isset( $s['pricing_list'] ) ? $s['pricing_list'] : '' ), array( '2','3','4' ), true ) ? (string) $s['pricing_list'] : '3';
		ob_start(); ?>
		<div class="module-theme-pricing"><div class="section"><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<div class="pricing-module pricing-list-<?php echo esc_attr( $columns ); ?>">
				<?php foreach ( $items as $item ) : $item=is_array( $item ) ? $item : array(); $details=isset( $item['item_details'] ) && is_array( $item['item_details'] ) ? $item['item_details'] : array(); $link=$this->link_data( isset( $item['item_btn_url'] ) ? $item['item_btn_url'] : '' ); ?>
					<div class="pricing-wrapper">
						<?php if ( ! empty( $item['item_tag'] ) ) : ?><span class="pricing-tag"><?php echo esc_html( $item['item_tag'] ); ?></span><?php endif; ?>
						<?php if ( ! empty( $item['item_title'] ) || ! empty( $item['item_desc'] ) ) : ?><div class="pricing-title"><?php if ( ! empty( $item['item_title'] ) ) : ?><h3><?php echo esc_html( $item['item_title'] ); ?></h3><?php endif; ?><?php if ( ! empty( $item['item_desc'] ) ) : ?><p><?php echo esc_html( $item['item_desc'] ); ?></p><?php endif; ?></div><?php endif; ?>
						<div class="pricing-price">
							<?php if ( ! empty( $item['item_price'] ) ) : ?><h5><?php echo wp_kses_post( $item['item_price'] ); ?></h5><?php endif; ?>
							<?php if ( ! empty( $item['item_price_d'] ) ) : ?><span><?php echo esc_html( $item['item_price_d'] ); ?></span><?php endif; ?>
							<?php if ( ! empty( $item['item_btn_text'] ) || ! empty( $item['item_btn_desc'] ) ) : ?><div class="pricing-btn"><?php if ( ! empty( $item['item_btn_text'] ) ) : ?><a href="<?php echo esc_url( $link['url'] ?: '#' ); ?>"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?><?php echo ! empty( $item['item_btn_color'] ) ? ' style="background:' . esc_attr( $item['item_btn_color'] ) . '"' : ''; ?>><?php echo esc_html( $item['item_btn_text'] ); ?></a><?php endif; ?><?php if ( ! empty( $item['item_btn_desc'] ) ) : ?><span><?php echo esc_html( $item['item_btn_desc'] ); ?></span><?php endif; ?></div><?php endif; ?>
						</div>
						<?php if ( ! empty( $item['item_details_title'] ) || $details ) : ?><div class="pricing-details"><?php if ( ! empty( $item['item_details_title'] ) ) : ?><div class="details-title"><span><?php echo esc_html( $item['item_details_title'] ); ?></span></div><?php endif; ?><?php foreach ( $details as $detail ) : $detail=is_array( $detail ) ? $detail : array(); if ( empty( $detail['details_text'] ) ) { continue; } $icon=isset( $detail['details_icon'] ) && 'close' === $detail['details_icon'] ? 'close' : 'checkbox'; ?><div class="details-item"><i class="ri-<?php echo esc_attr( $icon ); ?>-circle-fill" aria-hidden="true"></i><span><?php echo esc_html( $detail['details_text'] ); ?></span></div><?php endforeach; ?></div><?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function link_data( $v ) { if ( is_array( $v ) ) { $url=isset( $v['link'] ) ? $v['link'] : ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); } else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; } $rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; } return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) ); }

	public function getTemplate() {
		return '
		<# var items=_.isArray(data.pricing_items)?data.pricing_items:[];var columnValue=String(data.pricing_list);var columns=["2","3","4"].indexOf(columnValue)!==-1?columnValue:"3"; #><# if(items.length){ #><div class="module-theme-pricing"><div class="section"><div class="container"><# if((data.module_title||data.module_desc)){ #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><div class="pricing-module pricing-list-{{ columns }}"><# _.forEach(items,function(item){item=item||{};var details=_.isArray(item.item_details)?item.item_details:[]; #><div class="pricing-wrapper"><# if(item.item_tag){ #><span class="pricing-tag">{{ item.item_tag }}</span><# } #><# if(item.item_title||item.item_desc){ #><div class="pricing-title"><# if(item.item_title){ #><h3>{{ item.item_title }}</h3><# } #><# if(item.item_desc){ #><p>{{ item.item_desc }}</p><# } #></div><# } #><div class="pricing-price"><# if(item.item_price){ #><h5>{{{ item.item_price }}}</h5><# } #><# if(item.item_price_d){ #><span>{{ item.item_price_d }}</span><# } #><# if(item.item_btn_text||item.item_btn_desc){ #><div class="pricing-btn"><# if(item.item_btn_text){ #><span class="pricing-button" style="background:{{ item.item_btn_color||"#12715b" }}">{{ item.item_btn_text }}</span><# } #><# if(item.item_btn_desc){ #><span>{{ item.item_btn_desc }}</span><# } #></div><# } #></div><# if(item.item_details_title||details.length){ #><div class="pricing-details"><# if(item.item_details_title){ #><div class="details-title"><span>{{ item.item_details_title }}</span></div><# } #><# _.forEach(details,function(detail){detail=detail||{};if(!detail.details_text){return;}var icon=detail.details_icon==="close"?"close":"checkbox"; #><div class="details-item"><i class="ri-{{ icon }}-circle-fill"></i><span>{{ detail.details_text }}</span></div><# }); #></div><# } #></div><# }); #></div></div></div></div><# } #>';
	}
}
