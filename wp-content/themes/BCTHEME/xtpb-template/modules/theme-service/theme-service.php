<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Service {
	public function get_name() { return 'theme_service'; }
	public function get_title() { return '服务项目'; }
	public function get_class() { return 'xtpb-theme-service'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-service/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-service/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-service/assets/img/modules-service.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'服务项目', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'radius'=>array( 'type'=>'number', 'title'=>'圆角', 'desc'=>'px，建议 8 px', 'std'=>8, 'section'=>'样式设置' ),
			'service_items'=>array(
				'type'=>'repeatable', 'title'=>'服务项目', 'label'=>'item_title', 'section'=>'内容设置',
				'std'=>array( array( 'item_img'=>array( 'url'=>'' ), 'item_title'=>'服务项目', 'item_desc'=>'这里是服务项目的介绍内容。', 'item_url'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ) ) ),
				'attr'=>array(
					'item_img'=>array( 'type'=>'media', 'title'=>'图片', 'desc'=>'建议尺寸：1280 × 540 px' ),
					'item_title'=>array( 'type'=>'text', 'title'=>'标题' ),
					'item_desc'=>array( 'type'=>'textarea', 'title'=>'描述' ),
					'item_url'=>array( 'type'=>'link', 'title'=>'跳转链接', 'std'=>array( 'link'=>'', 'window'=>false, 'nofolow'=>false ) ),
				),
			),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$items=isset( $s['service_items'] ) && is_array( $s['service_items'] ) ? array_slice( $s['service_items'], 0, 5 ) : array();
		if ( ! $items ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		$radius_value=isset( $s['radius'] ) ? $s['radius'] : '';
		$radius='' === $radius_value || null === $radius_value ? 8 : min( 100, absint( $radius_value ) );
		ob_start(); ?>
		<div class="module-theme-service"><div class="section" style="--border-radius:<?php echo esc_attr( $radius ); ?>px"><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<div class="service-list list-item-<?php echo esc_attr( count( $items ) ); ?>"><ul>
				<?php foreach ( $items as $index=>$item ) : $item=is_array( $item ) ? $item : array(); $image=$this->media_url( isset( $item['item_img'] ) ? $item['item_img'] : '' ); $link=$this->link_data( isset( $item['item_url'] ) ? $item['item_url'] : '' ); ?>
					<li<?php echo 0 === $index ? ' class="active"' : ''; ?><?php echo $image ? ' style="background-image:url(' . esc_url( $image ) . ')"' : ''; ?>>
						<div class="service-item-head"><?php if ( ! empty( $item['item_title'] ) ) : ?><h3><?php echo esc_html( $item['item_title'] ); ?></h3><?php endif; ?><?php if ( $link['url'] ) : ?><p><?php esc_html_e( '查看更多', 'modular_theme' ); ?> <i class="ri-arrow-right-line" aria-hidden="true"></i></p><?php endif; ?></div>
						<div class="service-item-info"><?php if ( ! empty( $item['item_title'] ) ) : ?><h3><?php echo esc_html( $item['item_title'] ); ?></h3><?php endif; ?><div class="service-item-info_s"><?php if ( ! empty( $item['item_desc'] ) ) : ?><?php echo wpautop( wp_kses_post( $item['item_desc'] ) ); ?><?php endif; ?><?php if ( $link['url'] ) : ?><span><?php esc_html_e( '查看更多', 'modular_theme' ); ?> <i class="ri-arrow-right-line" aria-hidden="true"></i></span><?php endif; ?></div></div>
						<?php if ( $link['url'] ) : ?><a href="<?php echo esc_url( $link['url'] ); ?>" class="service-item-url"<?php echo $link['target'] ? ' target="_blank"' : ''; ?><?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?> aria-label="<?php echo esc_attr( isset( $item['item_title'] ) ? $item['item_title'] : '' ); ?>"></a><?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul></div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function media_url( $value ) { return is_array( $value ) ? ( isset( $value['url'] ) ? $value['url'] : '' ) : ( is_string( $value ) ? $value : '' ); }
	private function link_data( $v ) { if ( is_array( $v ) ) { $url=isset( $v['link'] ) ? $v['link'] : ''; $target=!empty( $v['window'] ); $nofollow=!empty( $v['nofolow'] ) || !empty( $v['nofollow'] ); } else { $url=is_string( $v ) ? $v : ''; $target=false; $nofollow=false; } $rel=array(); if ( $target ) { $rel[]='noopener'; } if ( $nofollow ) { $rel[]='nofollow'; } return array( 'url'=>$url, 'target'=>$target, 'rel'=>implode( ' ', $rel ) ); }

	public function getTemplate() {
		return '
		<# var items=_.isArray(data.service_items)?data.service_items.slice(0,5):[];var radiusValue=(data.radius===undefined||data.radius===null||data.radius==="")?8:parseInt(data.radius,10);var radius=Math.min(100,Math.max(0,isNaN(radiusValue)?8:radiusValue)); #><# if(items.length){ #><div class="module-theme-service"><div class="section" style="--border-radius:{{ radius }}px"><div class="container"><# if((data.module_title||data.module_desc)){ #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><div class="service-list list-item-{{ items.length }}"><ul><# _.forEach(items,function(item,index){item=item||{};var image=item.item_img&&typeof item.item_img==="object"?(item.item_img.url||""):(item.item_img||"");var link=item.item_url&&typeof item.item_url==="object"?(item.item_url.link||""):(item.item_url||""); #><li<# if(index===0){ #> class="active"<# } #><# if(image){ #> style="background-image:url({{ image }})"<# } #>><div class="service-item-head"><# if(item.item_title){ #><h3>{{ item.item_title }}</h3><# } #><# if(link){ #><p>查看更多 <i class="ri-arrow-right-line"></i></p><# } #></div><div class="service-item-info"><# if(item.item_title){ #><h3>{{ item.item_title }}</h3><# } #><div class="service-item-info_s"><# if(item.item_desc){ #><p>{{ item.item_desc }}</p><# } #><# if(link){ #><span>查看更多 <i class="ri-arrow-right-line"></i></span><# } #></div></div></li><# }); #></ul></div></div></div></div><# } #>';
	}
}
