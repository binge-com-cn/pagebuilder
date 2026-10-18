<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('XTPB_Initial_Setup')) {
    class XTPB_Initial_Setup{
        protected static $_instance = null;
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        // Initialize Save Data
        public static function initialize_data(){

            // Activating default post type
            if ( ! get_option( 'xtpb_is_used' ) ) {
	            $xtpb_options = ( array ) get_option( 'xtpb_options' );
	            $xtpb_options['supported_post_type'] = array( 'post', 'page' );
                $xtpb_options['include_role'] = array(
                    'administrator',
                    'editor',
                    'author',
                    'contributor'
                );
	            $xtpb_options['css_save_as'] = 'wp_head';
	            update_option( 'xtpb_options', $xtpb_options );
	            update_option( 'xtpb_is_used', XTPB_VERSION );
            }
            $xtpb_options = ( array ) get_option( 'xtpb_options' );
            $xtpb_options['supported_post_type'] = array( 'post', 'page' );
            $xtpb_options['include_role'] = array(
                'administrator',
                'editor',
                'author',
                'contributor'
            );
            $xtpb_options['css_save_as'] = 'wp_head';
            update_option( 'xtpb_options', $xtpb_options );
            update_option( 'xtpb_is_used', XTPB_VERSION );
        }

        // PHP Error Notice
        public static function php_error_notice(){
            $message = sprintf( esc_html__( 'BCTheme PageBuilder 要求 PHP 版本为 %s 或更高。', 'xintheme-pagebuilder' ), '5.4' );
            $html_message = sprintf( '<div class="notice notice-error is-dismissible">%s</div>', wpautop( $message ) );
            echo wp_kses_post( $html_message );
        }

        // Wordpress Error Notice
        public static function worpress_error_notice(){
            $message = sprintf( esc_html__( 'BCTheme PageBuilder 要求 WordPress 版本为 %s 或更高。', 'xintheme-pagebuilder'
            ), '4.7' );
            $html_message = sprintf( '<div class="notice notice-error is-dismissible">%s</div>', wpautop( $message ) );
            echo wp_kses_post( $html_message );
        }

    }
}
