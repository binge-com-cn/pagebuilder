<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Partners_2 {
	public function get_name() { return 'theme_partners_2'; }
	public function get_title() { return '合作伙伴（滚动）'; }
	public function get_class() { return 'xtpb-theme-partners-2'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-partners-2/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-partners-2/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-partners-2/assets/img/modules-partners-2.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title' => array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'合作伙伴', 'section'=>'模块设置' ),
			'module_desc' => array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'mask_image' => array( 'type'=>'switch', 'title'=>'渐变遮挡层', 'desc'=>'模块左右两侧显示渐变遮挡层', 'std'=>0, 'section'=>'模块设置' ),
			'partner_groups' => array(
				'type'=>'repeatable', 'title'=>'滚动组', 'label'=>'item_number', 'section'=>'内容设置',
				'std'=>array( array(
					'item_autoplay_pauseonmouseenter'=>0, 'item_allowtouchmove'=>0, 'item_autoplay_disableoninteraction'=>0,
					'item_reversedirection'=>0, 'item_speed'=>10000, 'item_number'=>5, 'item_style'=>'text_img_2',
					'item_partners'=>array( array( 'partners_img'=>array( 'url'=>'' ), 'partners_text'=>'合作伙伴' ) ),
				) ),
				'attr'=>array(
					'item_autoplay_pauseonmouseenter'=>array( 'type'=>'switch', 'title'=>'鼠标悬停暂停自动播放', 'std'=>0 ),
					'item_allowtouchmove'=>array( 'type'=>'switch', 'title'=>'允许触摸滑动', 'std'=>0 ),
					'item_autoplay_disableoninteraction'=>array( 'type'=>'switch', 'title'=>'交互后暂停自动播放', 'std'=>0 ),
					'item_reversedirection'=>array( 'type'=>'switch', 'title'=>'反方向滚动', 'std'=>0 ),
					'item_speed'=>array( 'type'=>'number', 'title'=>'切换速度', 'desc'=>'毫秒，默认 10000', 'std'=>10000 ),
					'item_number'=>array( 'type'=>'number', 'title'=>'桌面端显示数量', 'desc'=>'建议 5 或 6', 'std'=>5 ),
					'item_style'=>array(
						'type'=>'select', 'title'=>'显示样式', 'std'=>'text_img_2',
						'values'=>array( 'text_img'=>'Logo+文本（左右）', 'text_img_2'=>'Logo+文本（上下）', 'text'=>'只显示文本', 'img'=>'只显示 Logo' ),
					),
					'item_partners'=>array(
						'type'=>'repeatable', 'title'=>'合作伙伴', 'label'=>'partners_text',
						'std'=>array( array( 'partners_img'=>array( 'url'=>'' ), 'partners_text'=>'合作伙伴' ) ),
						'attr'=>array(
							'partners_img'=>array( 'type'=>'media', 'title'=>'Logo', 'desc'=>'建议尺寸：100 × 100 px' ),
							'partners_text'=>array( 'type'=>'text', 'title'=>'文本' ),
						),
					),
				),
			),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$groups=isset( $s['partner_groups'] ) && is_array( $s['partner_groups'] ) ? $s['partner_groups'] : array();
		if ( ! $groups ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		ob_start(); ?>
		<div class="module-theme-partners-2"><div class="section"><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<div class="partners-2-module<?php echo ! empty( $s['mask_image'] ) ? ' mask-image' : ''; ?>">
				<?php foreach ( $groups as $group ) : ?>
					<?php $group=is_array( $group ) ? $group : array(); $partners=isset( $group['item_partners'] ) && is_array( $group['item_partners'] ) ? $group['item_partners'] : array(); if ( ! $partners ) { continue; } ?>
					<div class="swiper partners-2-slider" data-speed="<?php echo esc_attr( ! empty( $group['item_speed'] ) ? absint( $group['item_speed'] ) : 10000 ); ?>" data-items="<?php echo esc_attr( ! empty( $group['item_number'] ) ? absint( $group['item_number'] ) : 5 ); ?>" data-pause="<?php echo ! empty( $group['item_autoplay_pauseonmouseenter'] ) ? '1' : '0'; ?>" data-touch="<?php echo ! empty( $group['item_allowtouchmove'] ) ? '1' : '0'; ?>" data-disable="<?php echo ! empty( $group['item_autoplay_disableoninteraction'] ) ? '1' : '0'; ?>" data-reverse="<?php echo ! empty( $group['item_reversedirection'] ) ? '1' : '0'; ?>">
						<div class="swiper-wrapper"><?php foreach ( $partners as $partner ) : $partner=is_array( $partner ) ? $partner : array(); $image=$this->media_url( isset( $partner['partners_img'] ) ? $partner['partners_img'] : '' ); $text=!empty( $partner['partners_text'] ) ? sanitize_text_field( $partner['partners_text'] ) : ''; if ( ! $image && ! $text ) { continue; } $style=$this->item_style( isset( $group['item_style'] ) ? $group['item_style'] : '' ); ?><div class="swiper-slide"><div class="partners-item partners-style-<?php echo esc_attr( $style ); ?>"><?php if ( $image && 'text' !== $style ) : ?><div class="partners-img"><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $text ); ?>"></div><?php endif; ?><?php if ( $text && 'img' !== $style ) : ?><h6><?php echo esc_html( $text ); ?></h6><?php endif; ?></div></div><?php endforeach; ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function media_url( $value ) { return is_array( $value ) ? ( isset( $value['url'] ) ? $value['url'] : '' ) : ( is_string( $value ) ? $value : '' ); }
	private function item_style( $value ) { return in_array( $value, array( 'text_img', 'text_img_2', 'text', 'img' ), true ) ? $value : 'text_img_2'; }

	public function getTemplate() {
		return '
		<# var groups=_.isArray(data.partner_groups)?data.partner_groups:[];var enabled=function(value){return value===true||value===1||value==="1";}; #><# if(groups.length){ #><div class="module-theme-partners-2"><div class="section"><div class="container"><# if((data.module_title||data.module_desc)){ #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><div class="partners-2-module<# if(data.mask_image){ #> mask-image<# } #>"><# _.forEach(groups,function(group){group=group||{};var partners=_.isArray(group.item_partners)?group.item_partners:[];var style=["text_img","text_img_2","text","img"].indexOf(group.item_style)!==-1?group.item_style:"text_img_2";if(!partners.length){return;} #><div class="swiper partners-2-slider" data-speed="{{ group.item_speed||10000 }}" data-items="{{ group.item_number||5 }}" data-pause="{{ enabled(group.item_autoplay_pauseonmouseenter)?1:0 }}" data-touch="{{ enabled(group.item_allowtouchmove)?1:0 }}" data-disable="{{ enabled(group.item_autoplay_disableoninteraction)?1:0 }}" data-reverse="{{ enabled(group.item_reversedirection)?1:0 }}"><div class="swiper-wrapper"><# _.forEach(partners,function(item){item=item||{};var image=item.partners_img&&typeof item.partners_img==="object"?(item.partners_img.url||""):(item.partners_img||"");if(!image&&!item.partners_text){return;} #><div class="swiper-slide"><div class="partners-item partners-style-{{ style }}"><# if(image&&style!=="text"){ #><div class="partners-img"><img src="{{ image }}" alt="{{ item.partners_text||"" }}"></div><# } #><# if(item.partners_text&&style!=="img"){ #><h6>{{ item.partners_text }}</h6><# } #></div></div><# }); #></div></div><# }); #></div></div></div></div><# } #>';
	}
}
