<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Compatibility {

	protected $is_current_editor_gutenberg = false;

	public function __construct() {
		$this->wp_importer_escape(); // WordPress default importer JSON slash issue
		add_action( 'admin_print_scripts-edit.php', array( $this, 'xtpb_add_btn_to_gutenberg_dropdown'), 20 ); // Gutenberg
		add_action( 'init', array( $this, 'add_image_size'), 30 );  // Image Size Add for Post Addons

		add_action( 'updated_post_meta', array( $this, 'thirdparty_pagebuilder_comparability_check' ) , 12, 4 );
		
		if(version_compare(XTPB_VERSION, '1.2.0', '>=')){
			add_action( 'admin_init', array( $this, 'xtpb_replace_font_awesome_icon_class' ));
		}

		if( defined('GIVE_VERSION') ){
			add_action( 'xtpb_enqueue_scripts_in_editor', array( $this, 'xtpb_give_compatibility' ) ); // Give Plugins Compatibility
		}


		/**
         * Gutenberg
         */

        if (is_admin()){
	        add_action('admin_body_class', array($this, 'add_extra_body_class'));
	        add_action( 'enqueue_block_editor_assets', array($this, 'add_required_assets') );
	        add_action( 'admin_footer', array($this, 'add_required_template_to_gutenberg_editor') );
        }
	}

	/**
	 * @since 1.0.2
	 * Give Plugins Compatibility Callback
	 */
	public function xtpb_give_compatibility(){
		wp_deregister_script( 'give' );
	}

	/**
	 * Thirdparty Page builder Comparability Check
	 * @since v 1.0.0
	 */
		function thirdparty_pagebuilder_comparability_check( $meta_id, $post_id, $meta_key, $meta_value ){
			if( ($meta_key == '_elementor_data') || // Elementor
			    ($meta_key == '_themify_builder_settings_json') || // Themify
			    ($meta_key == '_et_pb_use_builder' &&  $meta_value == 'on' ) || // Divi
			    ($meta_key == '_wpb_vc_js_status' &&  $meta_value == 'true' ) || // Visual Composer
			    ($meta_key == 'panels_data') // SiteOrigin
			){
				xtpb_helper()->clear_current_post_editor( $post_id );
			}
		}


	/**
	 * Replace FontAwesome classes with Remix Icon classes.
	 */
	function xtpb_replace_font_awesome_icon_class(){
		if ( get_option( 'xtpb_remixicon_upgrade' ) != 'completed' ) {
			global $wpdb;
			$results  = $wpdb->get_results( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_xtpb_content'" );

			foreach ( $results as $result ) {
				$data = xtpb_normalize_builder_content_icons( $result->meta_value );
				if ( $data !== $result->meta_value ) {
					update_post_meta( $result->post_id, '_xtpb_content', wp_slash( $data ) );
				}
			}

			update_option( 'xtpb_remixicon_upgrade', 'completed' );
		}
	}




	/**
	 * @since 1.0.1
	 * Gutenberg dropdown menu add
	 */
	public function wp_importer_escape(){
		if( /* defined('WP_LOAD_IMPORTERS') && */ is_admin() ){
			add_filter( 'wp_import_post_meta', array( $this, 'import_post_meta_escape' ) );
		}
	}
	public function import_post_meta_escape( $post_meta ){
		foreach ( $post_meta as &$meta ) {
			if ( '_xtpb_content' === $meta['key'] ) {
				$meta['value'] = wp_slash( $meta['value'] );
				break;
			}
		}
		return $post_meta;
	}

	/**
	 * @since 1.0.0
	 * Gutenberg dropdown menu add
	 */
	public function xtpb_add_btn_to_gutenberg_dropdown(){
		global $typenow;
		$xtpb_page_url = xtpb_helper()->get_editor_url();
		$xtpb_page_url = add_query_arg( array('post_type' => $typenow), $xtpb_page_url);
		$supported_post_types    = xtpb_helper()->xtpb_supports_post_types();
		if (! in_array($typenow, $supported_post_types)){
			return;
		}
		?>
        <script type="text/javascript">
            document.addEventListener( 'DOMContentLoaded', function() {
                var dropdown = document.querySelector( '#split-page-title-action .dropdown' );
                if ( ! dropdown ) {
                    return;
                }
                var url = '<?php echo $xtpb_page_url; ?>';
                dropdown.insertAdjacentHTML( 'afterbegin', '<a href="' + url + '">WP Pagebuilder</a>' );
            } );
        </script>
		<?php
	}

	/**
	 * @since 1.0.0
	 * Add Image Size for Posts Addons
	 */
	public function add_image_size() {
		add_image_size( 'xtpb-medium', 600, 450, true );
	}

		public function add_extra_body_class($classes){
			global $pagenow;

		if ($pagenow == 'post.php') {
			$current_editor = xtpb_helper()->get_current_post_editor( get_the_ID() );

		    if ($current_editor){
			    $classes .= " currently-activated-editor-{$current_editor} ";
		    }
		}
		return $classes;
	}

	public function add_required_assets(){
	    $this->is_current_editor_gutenberg = true;
    }

	public function add_required_template_to_gutenberg_editor() {
		if ( ! $this->is_current_editor_gutenberg )
		    return;
		?>
        <script id="xintheme-pagebuilder-to-gutenberg-switch-mode" type="text/html">
            <div id="xintheme-pagebuilder-switch-to-gutenberg">
                <a href="<?php echo admin_url('post.php?post='.get_the_ID().'&action=xtpb_editor') ?>" id="xintheme-pagebuilder-switch-to-gutenberg-btn" class="xtpb-backend-btn xtpb-primary-btn edit-with-xtpb-builder" data-warning="<?php echo esc_attr__( '这将打开页面构建器编辑器；只有在保存了页面构建器内容后，前端页面才会切换为页面构建器输出。是否继续？', 'xintheme-pagebuilder' ); ?>">
	                <?php echo __( '使用页面构建器编辑', 'xintheme-pagebuilder' ); ?>
                </a>
	            <a href="<?php echo admin_url('post.php?post='.get_the_ID().'&action=edit') ?>" class="xtpb-backend-btn xtpb-back-to-gutenberg" data-warning="<?php echo esc_attr__( '切换后，你可以继续编辑 Gutenberg 内容；前端页面会在你重新保存文章后，才切换为显示 Gutenberg 编辑器中的内容。当前页面构建器内容不会自动转换。是否继续？', 'xintheme-pagebuilder' ); ?>">
		            <?php echo __( '切换回 Gutenberg', 'xintheme-pagebuilder' ); ?>
	            </a>
            </div>
        </script>
		<?php
	}


}
