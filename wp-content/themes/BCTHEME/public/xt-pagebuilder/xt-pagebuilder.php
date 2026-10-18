<?php
/*
 * Plugin Name:       页面构建器
 * Plugin URI:        https://www.xintheme.com/product/xintheme-pagebuilder/
 * Description:       页面构建器是一款 WordPress 拖放式建站工具。无需编写代码，即可在几分钟内创建精美的网站。
 * Version:           1.0.0
 * Author:            XinTheme
 * Author URI:        https://xintheme.com
 * Text Domain:       xintheme-pagebuilder
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( defined( 'XTPB_VERSION' ) ) {
    return;
}

if ( ! function_exists( 'xtpb_builder_path_is_inside' ) ) {
    function xtpb_builder_path_is_inside( $path, $base_path ) {
        $path      = trailingslashit( wp_normalize_path( $path ) );
        $base_path = trailingslashit( wp_normalize_path( $base_path ) );

        return 0 === strpos( $path, $base_path );
    }
}

if ( ! function_exists( 'xtpb_builder_is_plugin_mode' ) ) {
    function xtpb_builder_is_plugin_mode( $file ) {
        $file_path = dirname( $file );

        if ( defined( 'WP_PLUGIN_DIR' ) && xtpb_builder_path_is_inside( $file_path, WP_PLUGIN_DIR ) ) {
            return true;
        }

        return defined( 'WPMU_PLUGIN_DIR' ) && xtpb_builder_path_is_inside( $file_path, WPMU_PLUGIN_DIR );
    }
}

if ( ! function_exists( 'xtpb_builder_theme_dir_url' ) ) {
    function xtpb_builder_theme_dir_url( $file ) {
        $dir = trailingslashit( wp_normalize_path( dirname( $file ) ) );

        $theme_locations = array(
            get_stylesheet_directory() => get_stylesheet_directory_uri(),
            get_template_directory()   => get_template_directory_uri(),
        );

        foreach ( $theme_locations as $theme_path => $theme_uri ) {
            $theme_path = trailingslashit( wp_normalize_path( $theme_path ) );

            if ( 0 === strpos( $dir, $theme_path ) ) {
                $relative_path = ltrim( substr( $dir, strlen( $theme_path ) ), '/' );
                return trailingslashit( $theme_uri ) . $relative_path;
            }
        }

        return plugin_dir_url( $file );
    }
}

define( 'XTPB_FILE', __FILE__ );
define( 'XTPB_MODE', xtpb_builder_is_plugin_mode( __FILE__ ) ? 'plugin' : 'theme' );

// Language Load
add_action( 'init', 'xtpb_builder_language_load');
function xtpb_builder_language_load(){
    if ( 'plugin' === XTPB_MODE ) {
        $plugin_dir = basename( dirname( XTPB_FILE ) ) . "/languages/";
        load_plugin_textdomain( 'xintheme-pagebuilder', false, $plugin_dir );
        return;
    }

    load_theme_textdomain( 'xintheme-pagebuilder', XTPB_DIR_PATH . 'languages' );
}

// Define Version
define('XTPB_VERSION', '1.0.0');

// Define License
define('XTPB_LICENSE', 'free');

// Define Dir URL
define('XTPB_DIR_URL', 'plugin' === XTPB_MODE ? plugin_dir_url( __FILE__ ) : xtpb_builder_theme_dir_url( __FILE__ ));

// Define Physical Path
define('XTPB_DIR_PATH', plugin_dir_path( __FILE__ ));

//XinTheme PageBuilder Base Name
define('XTPB_BASENAME', 'plugin' === XTPB_MODE ? plugin_basename( __FILE__ ) : '');

// Include Require File
require_once XTPB_DIR_PATH.'core/initial-setup.php'; // Initial Setup Data

// Version Check & Include Core
if ( ! version_compare( PHP_VERSION, '5.4', '>=' ) ) {
    add_action('admin_notices', array('XTPB_Initial_Setup', 'php_error_notice')); // PHP Version Check
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.5', '>=' ) ) {
    add_action('admin_notices', array('XTPB_Initial_Setup', 'worpress_error_notice')); // WordPress Version Check
} else {
    require_once XTPB_DIR_PATH.'inc/XTPB.php';              // Loading Page Builder Main Files
}

// Initialize Data When plugins Activated
function xtpb_builder_activate() {
    XTPB_Initial_Setup::initialize_data();

    if ( class_exists( 'XTPB_Form_Records' ) ) {
        XTPB_Form_Records::create_table();
    }
}

function xtpb_builder_maybe_initialize_theme_data() {
    if ( ! get_option( 'xtpb_is_used' ) ) {
        xtpb_builder_activate();
    }
}

if ( 'plugin' === XTPB_MODE ) {
    register_activation_hook( __FILE__, 'xtpb_builder_activate' );
} else {
    add_action( 'after_switch_theme', 'xtpb_builder_activate' );
    add_action( 'admin_init', 'xtpb_builder_maybe_initialize_theme_data' );
}
