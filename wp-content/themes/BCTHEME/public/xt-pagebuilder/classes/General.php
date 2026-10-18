<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('XTPB_General')){
	class XTPB_General{
		/**
		 * XTPB_General constructor.
		 */
		public function __construct(){
			if ( xtpb_helper()->can_edit_editor() ) {
				add_filter('post_row_actions', array($this, 'posts_table_row_actions'), 10, 2 );
				add_filter('page_row_actions', array($this, 'posts_table_row_actions'), 10, 2 );
				add_filter('display_post_states' , array($this, 'xtpb_display_post_states'), 10, 2);
			}

			// Check if editor iframe load by get_permalink
			if (xtpb_helper()->is_load_editor_iframe()){
				add_filter('show_admin_bar', '__return_false' ); // Remove Admin Bar
			}
			
			add_action( 'init', array( $this, 'addon_include') ); // Include addon directory
			add_action( 'edit_form_after_title', array( $this, 'after_title_button_add' )); // Add Button After Title
			add_filter( 'the_content', array( $this, 'content_modify_filter' ) ); // Return Content After Modify

		}
		
		/**
		 * @param $actions
		 * @param $post
		 *
		 * @return mixed
		 */
		public function posts_table_row_actions( $actions, $post ) {
			$xtpb_supports_post_types = xtpb_helper()->xtpb_supports_post_types();
			if ( in_array( $post->post_type, $xtpb_supports_post_types ) ) {
				$editor_url = xtpb_helper()->get_editor_url($post->ID);
				$actions['xtpb_editor'] = '<a href="'.$editor_url.'">'.__('使用页面构建器编辑', 'xintheme-pagebuilder' ).'</a>';
			}
			return $actions;
		}

		/**
		 * @param $post_states
		 * @param $post
		 *
		 * @return array
		 *
		 * display post states in the wp post list
		 */
		public function xtpb_display_post_states($post_states, $post){
			require_once(ABSPATH . 'wp-admin/includes/screen.php');
			$screen = get_current_screen();
			if (isset($screen->base)) {
				if ( $screen->base === 'edit'){
					$xtpb_supports_post_types = xtpb_helper()->xtpb_supports_post_types();
					if ( in_array($post->post_type, $xtpb_supports_post_types)) {
						if ( xtpb_helper()->is_builder_activated( $post->ID ) ) {
							$post_states[] = 'BCTheme PageBuilder ';
						}
					}
				}
			}
			return $post_states;
		}

		// Return Content After Modify
		function content_modify_filter( $content ) {
			// Nested the_content calls belong to a module, not to the builder page.
			if ( xtpb_is_rendering_builder_content() ) {
				return $content;
			}

			if ( xtpb_helper()->is_load_editor_iframe() ) {
				return '<main id="xtpb-builder-container" class="xtpb-builder-container">'.$content.'</main>';
			}

			if ( xtpb_helper()->is_builder_activated( get_the_ID() ) ) {
				return '<main id="xtpb-builder-container" class="xtpb-builder-container">'.$content.'</main>';
			}

			return $content;
		}

		// Include addon directory
		public function addon_include(){
			$addon_dir = array_filter(glob( XTPB_DIR_PATH.'addons/*'), 'is_dir');
			if (count($addon_dir) > 0) {
				foreach ($addon_dir as $key => $value) {
					$addon_dir_name = str_replace(dirname($value).'/', '', $value);
					$file_name = XTPB_DIR_PATH . 'addons/'.$addon_dir_name.'/'.$addon_dir_name.'.php';
					if ( file_exists($file_name) ){
						include_once $file_name;
					}
				}
			}
		}

		// Add Button After Title
		public function after_title_button_add($pram){
			global $post;
			$editor = xtpb_helper()->get_current_post_editor( $post->ID );

			$supported_post_types    = xtpb_helper()->xtpb_supports_post_types();
			if( !in_array($pram->post_type, $supported_post_types) ){
				return;
			}
			if (! xtpb_helper()->can_edit_editor()){
				return;
			}
			?>
			<?php if( $editor == 'xtpb_builder_activated' ) { ?>
				<div class="wppagebuilder-edit-button wppagebuilder-edit-hide-editor" data-postid="<?php echo $post->ID; ?>">
			<?php } else { ?>
				<div class="wppagebuilder-edit-button wppagebuilder-edit-show-editor" data-postid="<?php echo $post->ID; ?>">
			<?php } ?>
			<?php if( $editor == 'xtpb_builder_activated' ) { ?>
				<div class="xtpb-edit-button-wrap">
					<div class="xtpb-back-editor">
						<a href="#"
						   class="xtpb-backend-btn xtpb-info-btn use-default-editor"
						   data-warning="<?php echo esc_attr__( '切换后，你可以继续编辑默认编辑器中的内容；前端页面会在你重新保存文章后，才切换为显示默认编辑器中的内容。当前页面构建器内容不会自动转换。是否继续？', 'xintheme-pagebuilder' ); ?>"><?php _e('使用默认编辑器','xintheme-pagebuilder' );?></a>
					</div><!--/.xtpb-back-editor-->
					<div class="xtpb-editor-warper">
						<a href="<?php echo xtpb_helper()->get_editor_url($post->ID); ?>" class="xtpb-backend-btn xtpb-primary-btn edit-with-xtpb-builder"><?php _e('使用页面构建器编辑','xintheme-pagebuilder');?></a>
					</div>
				</div><!--/.xtpb-edit-button-wrap-->
			<?php } else { ?>
				<div class="xtpb-edit-button-default-wrap">
					<div class="xtpb-editor-warper">
						<a href="<?php echo xtpb_helper()->get_editor_url($post->ID); ?>" class="xtpb-backend-btn xtpb-primary-btn edit-with-xtpb-builder"><?php _e('使用页面构建器编辑','xintheme-pagebuilder');?></a>
					</div>
				</div><!--/.xtpb-edit-button-default-wrap-->
			<?php } ?>
			</div><!--/.wppagebuilder-edit-button-->
			<?php
		}

	}
}
