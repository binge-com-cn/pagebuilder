<?php
// 	前台使用 wpjam_theme_get_setting('product_tabs');
class WPJAM_Product_Tab{
	public static function __callStatic($method, $args){
		$handler	= wpjam_get_handler([
			'option_name'	=> 'wpjam-theme',
			'items_field'	=> 'product_tabs',
			'max_items'		=> 5,
		]);

		return wpjam_call_handler($handler, $method, ...$args);
	}

	public static function get_fields($action_key='', $post_id=0){
		return  [
			'tab_title' 	=> ['title'=>'TAB标题',	'type'=>'text',		'required'=>true, 'show_admin_column'=>true],
			'tab_content'	=> ['title'=>'TAB详情',	'type'=>'editor'],
		];
	}

	public static function get_list_table(){
		return [
			'plural'	=> 'product_tabs',
			'singular'	=> 'product_tab',
			'model'		=> self::class,
			'sortable'	=> true,
		];
	}
}