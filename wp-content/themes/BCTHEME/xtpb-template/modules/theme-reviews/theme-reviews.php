<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Theme_Reviews {
	public function get_name() { return 'theme_reviews'; }
	public function get_title() { return '客户评价'; }
	public function get_class() { return 'xtpb-theme-reviews'; }
	public function get_css_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-reviews/assets/css/frontend.css' ); }
	public function get_js_files() { return array( get_template_directory() . '/xtpb-template/modules/theme-reviews/assets/js/frontend.js' ); }
	public function get_thumb() { return get_template_directory_uri() . '/xtpb-template/modules/theme-reviews/assets/img/modules-reviews.jpg'; }
	public function get_category_name() { return 'Modular'; }

	public function get_settings() {
		return array(
			'module_title'=>array( 'type'=>'text', 'title'=>'模块标题', 'std'=>'客户评价', 'section'=>'模块设置' ),
			'module_desc'=>array( 'type'=>'textarea', 'title'=>'模块简介', 'std'=>'', 'section'=>'模块设置' ),
			'mask_image'=>array( 'type'=>'switch', 'title'=>'渐变遮挡层', 'desc'=>'模块左右两侧显示渐变遮挡层', 'std'=>0, 'section'=>'模块设置' ),
			'review_groups'=>array(
				'type'=>'repeatable', 'title'=>'评价滚动组', 'label'=>'item_number', 'section'=>'内容设置',
				'std'=>array( array(
					'item_autoplay_pauseonmouseenter'=>0, 'item_allowtouchmove'=>0, 'item_autoplay_disableoninteraction'=>0, 'item_reversedirection'=>0, 'item_speed'=>10000, 'item_number'=>3,
					'item_reviews'=>array( array( 'reviews_icon'=>'5', 'reviews_img'=>array( 'url'=>'' ), 'reviews_name'=>'客户姓名', 'reviews_tag'=>'客户职位', 'reviews_desc'=>'这里是客户评价内容。' ) ),
				) ),
				'attr'=>array(
					'item_autoplay_pauseonmouseenter'=>array( 'type'=>'switch', 'title'=>'鼠标悬停暂停自动播放', 'std'=>0 ),
					'item_allowtouchmove'=>array( 'type'=>'switch', 'title'=>'允许触摸滑动', 'std'=>0 ),
					'item_autoplay_disableoninteraction'=>array( 'type'=>'switch', 'title'=>'交互后暂停自动播放', 'std'=>0 ),
					'item_reversedirection'=>array( 'type'=>'switch', 'title'=>'反方向滚动', 'std'=>0 ),
					'item_speed'=>array( 'type'=>'number', 'title'=>'切换速度', 'desc'=>'毫秒，默认 10000', 'std'=>10000 ),
					'item_number'=>array( 'type'=>'number', 'title'=>'桌面端显示数量', 'desc'=>'建议 3 或 4', 'std'=>3 ),
					'item_reviews'=>array(
						'type'=>'repeatable', 'title'=>'客户评价', 'label'=>'reviews_name',
						'std'=>array( array( 'reviews_icon'=>'5', 'reviews_img'=>array( 'url'=>'' ), 'reviews_name'=>'客户姓名', 'reviews_tag'=>'客户职位', 'reviews_desc'=>'这里是客户评价内容。' ) ),
						'attr'=>array(
							'reviews_icon'=>array( 'type'=>'select', 'title'=>'评分', 'values'=>array( '5'=>'★★★★★', '4'=>'★★★★', '3'=>'★★★', '2'=>'★★', '1'=>'★' ), 'std'=>'5' ),
							'reviews_img'=>array( 'type'=>'media', 'title'=>'头像', 'desc'=>'建议尺寸：100 × 100 px；不上传则不显示' ),
							'reviews_name'=>array( 'type'=>'text', 'title'=>'姓名' ),
							'reviews_tag'=>array( 'type'=>'text', 'title'=>'职位' ),
							'reviews_desc'=>array( 'type'=>'textarea', 'title'=>'评价内容' ),
						),
					),
				),
			),
		);
	}

	public function render( $data=null ) {
		$s=isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$groups=isset( $s['review_groups'] ) && is_array( $s['review_groups'] ) ? $s['review_groups'] : array();
		if ( ! $groups ) { return ''; }
		$title=!empty( $s['module_title'] ) ? sanitize_text_field( $s['module_title'] ) : '';
		$desc=!empty( $s['module_desc'] ) ? sanitize_textarea_field( $s['module_desc'] ) : '';
		ob_start(); ?>
		<div class="module-theme-reviews"><div class="section"><div class="container">
			<?php if ( $title || $desc ) : ?><div class="section-title"><?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?><?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?></div><?php endif; ?>
			<div class="reviews-module<?php echo ! empty( $s['mask_image'] ) ? ' mask-image' : ''; ?>">
				<?php foreach ( $groups as $group ) : $group=is_array( $group ) ? $group : array(); $reviews=isset( $group['item_reviews'] ) && is_array( $group['item_reviews'] ) ? $group['item_reviews'] : array(); if ( ! $reviews ) { continue; } ?>
					<div class="swiper reviews-slider" data-speed="<?php echo esc_attr( ! empty( $group['item_speed'] ) ? absint( $group['item_speed'] ) : 10000 ); ?>" data-items="<?php echo esc_attr( ! empty( $group['item_number'] ) ? absint( $group['item_number'] ) : 3 ); ?>" data-pause="<?php echo ! empty( $group['item_autoplay_pauseonmouseenter'] ) ? '1' : '0'; ?>" data-touch="<?php echo ! empty( $group['item_allowtouchmove'] ) ? '1' : '0'; ?>" data-disable="<?php echo ! empty( $group['item_autoplay_disableoninteraction'] ) ? '1' : '0'; ?>" data-reverse="<?php echo ! empty( $group['item_reversedirection'] ) ? '1' : '0'; ?>"><div class="swiper-wrapper">
						<?php foreach ( $reviews as $review ) : $review=is_array( $review ) ? $review : array(); if ( empty( $review['reviews_name'] ) && empty( $review['reviews_desc'] ) ) { continue; } $image=$this->media_url( isset( $review['reviews_img'] ) ? $review['reviews_img'] : '' ); $score=max( 1, min( 5, absint( isset( $review['reviews_icon'] ) ? $review['reviews_icon'] : 5 ) ) ); ?><div class="swiper-slide"><div class="reviews-item"><div class="reviews-score"><div class="score-icon" aria-label="<?php echo esc_attr( sprintf( '%d 星', $score ) ); ?>"><?php for ( $i=1; $i<=5; $i++ ) : ?><i class="ri-star-<?php echo $i <= $score ? 'fill' : 'line'; ?>" aria-hidden="true"></i><?php endfor; ?></div><?php if ( ! empty( $review['reviews_desc'] ) ) : ?><?php echo wpautop( wp_kses_post( $review['reviews_desc'] ) ); ?><?php endif; ?></div><div class="reviews-customer"><?php if ( $image ) : ?><div class="customer-avatar"><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( isset( $review['reviews_name'] ) ? $review['reviews_name'] : '' ); ?>"></div><?php endif; ?><div><?php if ( ! empty( $review['reviews_name'] ) ) : ?><h6><?php echo esc_html( $review['reviews_name'] ); ?></h6><?php endif; ?><?php if ( ! empty( $review['reviews_tag'] ) ) : ?><span><?php echo esc_html( $review['reviews_tag'] ); ?></span><?php endif; ?></div></div></div></div><?php endforeach; ?>
					</div></div>
				<?php endforeach; ?>
			</div>
		</div></div></div>
		<?php return ob_get_clean();
	}

	private function media_url( $value ) { return is_array( $value ) ? ( isset( $value['url'] ) ? $value['url'] : '' ) : ( is_string( $value ) ? $value : '' ); }

	public function getTemplate() {
		return '
		<# var groups=_.isArray(data.review_groups)?data.review_groups:[];var enabled=function(value){return value===true||value===1||value==="1";}; #><# if(groups.length){ #><div class="module-theme-reviews"><div class="section"><div class="container"><# if((data.module_title||data.module_desc)){ #><div class="section-title"><# if(data.module_title){ #><strong>{{ data.module_title }}</strong><# } #><# if(data.module_desc){ #><p>{{ data.module_desc }}</p><# } #></div><# } #><div class="reviews-module<# if(data.mask_image){ #> mask-image<# } #>"><# _.forEach(groups,function(group){group=group||{};var reviews=_.isArray(group.item_reviews)?group.item_reviews:[];if(!reviews.length){return;} #><div class="swiper reviews-slider" data-speed="{{ group.item_speed||10000 }}" data-items="{{ group.item_number||3 }}" data-pause="{{ enabled(group.item_autoplay_pauseonmouseenter)?1:0 }}" data-touch="{{ enabled(group.item_allowtouchmove)?1:0 }}" data-disable="{{ enabled(group.item_autoplay_disableoninteraction)?1:0 }}" data-reverse="{{ enabled(group.item_reversedirection)?1:0 }}"><div class="swiper-wrapper"><# _.forEach(reviews,function(item){item=item||{};var image=item.reviews_img&&typeof item.reviews_img==="object"?(item.reviews_img.url||""):(item.reviews_img||"");var score=Math.max(1,Math.min(5,parseInt(item.reviews_icon||5,10)));if(!item.reviews_name&&!item.reviews_desc){return;} #><div class="swiper-slide"><div class="reviews-item"><div class="reviews-score"><div class="score-icon"><# _.each([1,2,3,4,5],function(star){ #><i class="ri-star-{{ star<=score?"fill":"line" }}"></i><# }); #></div><# if(item.reviews_desc){ #><p>{{ item.reviews_desc }}</p><# } #></div><div class="reviews-customer"><# if(image){ #><div class="customer-avatar"><img src="{{ image }}" alt="{{ item.reviews_name||"" }}"></div><# } #><div><# if(item.reviews_name){ #><h6>{{ item.reviews_name }}</h6><# } #><# if(item.reviews_tag){ #><span>{{ item.reviews_tag }}</span><# } #></div></div></div></div><# }); #></div></div><# }); #></div></div></div></div><# } #>';
	}
}
