<?php
namespace XTPB;

if ( ! defined( 'ABSPATH' ) ) exit;

class Xtpb_autoloader {

	private static $classes_map = array(
		'XTPB_Editor_Management'=> 'classes/Editor_Management.php',
		'XTPB_Helper'			=> 'classes/Helper.php',
		'XTPB_Assets'			=> 'classes/Assets.php',
		'XTPB_General'			=> 'classes/General.php',
		'XTPB_Options'			=> 'classes/Options.php',
		'XTPB_Ajax'				=> 'classes/Ajax.php',
		'XTPB_Frontend' 		=> 'classes/Frontend.php',
		'XTPB_Form_Records'	=> 'classes/Form_Records.php',
		'XTPB_Theme_Manager'	=> 'classes/Theme_Manager.php',
		'Compatibility'			=> 'classes/Compatibility.php',
		'XTPB_Base_Data'		=> 'classes/Base_Data.php', //No need intensiet, it's contain initial data
	);

	public static function run() {
		spl_autoload_register([ __CLASS__, 'loader' ]);
	}

	private static function loader($className) {
		if ( ! class_exists($className)){
			$file_name = '';
			if ( ! empty( self::$classes_map[$className] )){
				$file_name = XTPB_DIR_PATH.self::$classes_map[$className];
			}else{
				$className = strtolower(
					preg_replace(
						array('/([a-z])([A-Z])/', '/_/', '/\\\/'),
						array('$1-$2', '-', DIRECTORY_SEPARATOR),
						$className
					)
				);

				$file_name = XTPB_DIR_PATH.'classes/'.$className.'.php';
			}

			if (file_exists($file_name) && is_readable( $file_name ) ) {
				require_once $file_name;
			}
		}
	}

}
