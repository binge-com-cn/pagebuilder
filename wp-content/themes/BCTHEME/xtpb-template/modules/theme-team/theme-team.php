<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Team {
	public function get_name() {
		return 'theme_team';
	}

	public function get_title() {
		return '团队成员';
	}

	public function get_class() {
		return 'xtpb-theme-team';
	}

	public function get_css_files() {
		return array(
			get_template_directory() . '/xtpb-template/modules/theme-team/assets/css/frontend.css',
		);
	}

	public function get_thumb() {
		return get_template_directory_uri() . '/xtpb-template/modules/theme-team/assets/img/modules-team.jpg';
	}

	public function get_category_name() {
		return 'Modular';
	}

	public function get_settings() {
		return array(
			'module_title' => array(
				'type'    => 'text',
				'title'   => '模块标题',
				'std'     => '团队成员',
				'section' => '模块设置',
			),
			'module_desc' => array(
				'type'    => 'textarea',
				'title'   => '模块简介',
				'std'     => '',
				'section' => '模块设置',
			),
			'module_bg' => array(
				'type'    => 'media',
				'title'   => '背景图片',
				'std'     => array( 'url' => '' ),
				'section' => '模块设置',
			),
			'posts_per_page' => array(
				'type'    => 'number',
				'title'   => '成员数量',
				'desc'    => '默认显示 8 名成员',
				'std'     => 8,
				'section' => '内容设置',
			),
			'show_team_more' => array(
				'type'    => 'switch',
				'title'   => '显示查看更多',
				'std'     => 0,
				'section' => '按钮设置',
			),
			'team_more_title' => array(
				'type'    => 'text',
				'title'   => '按钮标题',
				'std'     => '查看更多',
				'depends' => array( array( 'show_team_more', '=', 1 ) ),
				'section' => '按钮设置',
			),
		);
	}

	public function render( $data = null ) {
		$settings = isset( $data['settings'] ) && is_array( $data['settings'] ) ? $data['settings'] : array();
		$per_page = ! empty( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 8;
		$query     = new WP_Query(
			array(
				'post_type'      => 'team',
				'post_status'    => 'publish',
				'posts_per_page' => $per_page,
				'no_found_rows'  => true,
			)
		);

		if ( ! $query->have_posts() ) {
			return '';
		}

		$title = ! empty( $settings['module_title'] ) ? sanitize_text_field( $settings['module_title'] ) : '';
		$desc  = ! empty( $settings['module_desc'] ) ? sanitize_textarea_field( $settings['module_desc'] ) : '';
		$bg    = $this->media_url( isset( $settings['module_bg'] ) ? $settings['module_bg'] : '' );

		ob_start();
		?>
		<div class="module-theme-team">
			<div class="section"<?php echo $bg ? ' style="background:url(' . esc_url( $bg ) . ') no-repeat center 0 / cover"' : ''; ?>>
				<div class="container">
					<?php if ( $title || $desc ) : ?>
						<div class="section-title<?php echo $bg ? ' text-colour-white' : ''; ?>">
							<?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?>
							<?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="team-post-list">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php $thumbnail_url = wpjam_get_post_thumbnail_url(); ?>
						<div class="item">
							<?php if ( $thumbnail_url ) : ?><div class="img"><a href="<?php the_permalink(); ?>"><img loading="lazy" src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"></a></div><?php endif; ?>
								<div class="info">
									<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<?php $position = get_post_meta( get_the_ID(), 'team_position', true ); ?>
									<?php if ( $position ) : ?><p><?php echo esc_html( $position ); ?></p><?php endif; ?>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
					<?php if ( ! empty( $settings['show_team_more'] ) && get_post_type_archive_link( 'team' ) ) : ?>
						<div class="more-btn"><a href="<?php echo esc_url( get_post_type_archive_link( 'team' ) ); ?>"><?php echo esc_html( ! empty( $settings['team_more_title'] ) ? $settings['team_more_title'] : '查看更多' ); ?></a></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		wp_reset_postdata();

		return ob_get_clean();
	}

	private function media_url( $value ) {
		return is_array( $value ) ? ( isset( $value['url'] ) ? $value['url'] : '' ) : ( is_string( $value ) ? $value : '' );
	}

}
