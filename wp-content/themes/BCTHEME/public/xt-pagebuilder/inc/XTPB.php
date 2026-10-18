<?php

class XTPB{
	public static $instance = null;

	public $xtpb_general;
	public $xtpb_editor;
	public $xtpb_assets;
	public $xtpb_options;
	public $xtpb_ajax;
	public $xtpb_frontend;
	public $xtpb_form_records;
	public $xtpb_theme_manager;
	public $compatibility;

	/**
	 * XTPB constructor
	 */
	public function __construct() {
		$this->register_autoloader();
		add_action( 'init', array($this, 'init'), 0 );
		add_action('init', array($this, 'load_addons_and_initialize'), 11);
	}

	public function init(){
		$this->init_components();
	}

	/**
	 * @return null|XTPB
	 *
	 * instance of XTPB Class
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			do_action( 'xtpb_loaded' );
		}

		return self::$instance;
	}

	/**
	 * Auto load class
	 */
	private function register_autoloader() {
		require XTPB_DIR_PATH . '/inc/class-xtpb-autoloader.php';
		\XTPB\Xtpb_autoloader::run();
	}

	/**
	 * Init all components
	 *
	 * @since v.1.0.0
	 */
	private function init_components(){
		$this->xtpb_frontend		= new XTPB_Frontend();
		$this->xtpb_editor			= new XTPB_Editor_Management();
		$this->xtpb_assets			= new XTPB_Assets();
		$this->xtpb_general			= new XTPB_General();
		$this->xtpb_options			= new XTPB_Options();
		$this->xtpb_ajax			= new XTPB_Ajax();
		$this->xtpb_form_records	= new XTPB_Form_Records();
		$this->xtpb_theme_manager 	= new XTPB_Theme_Manager();
		$this->compatibility 		= new Compatibility();
	}

	/**
	 * Get all addons and Initialize them
	 */
	function load_addons_and_initialize(){
		$addons = xtpb_helper()->get_addon_classes();
		$initializeAddonClasses = array();
		do_action('before_xtpb_addon_initialize');
		foreach ($addons as $addon){
			if (class_exists($addon)){
				do_action('before_xtpb_addon_initializing_'.$addon);
				$initializeAddonClasses[$addon] = new $addon;
				do_action('after_xtpb_addon_initializing_'.$addon);
			}
		}
		do_action('after_xtpb_addon_initialize');
		apply_filters('xtpb_initialize_addons', $initializeAddonClasses);
	}
}



if (! function_exists('xtpb_init')){
	function xtpb_init(){
		XTPB::instance();
	}
}
xtpb_init();


if ( ! function_exists('xtpb_helper')){
	function xtpb_helper(){
		static $helper = null;

		if ( null === $helper ) {
			$helper = new XTPB_Helper();
		}

		return $helper;
	}
}

/**
 * @param null $addon_name
 *
 * @return null
 */
if ( ! function_exists('addon_instance')){
	function addon_instance($addon_name = null){
		if ( ! $addon_name ) {
			return null;
		}

		return xtpb_helper()->get_class_by_addon_name( $addon_name );
	}
}
