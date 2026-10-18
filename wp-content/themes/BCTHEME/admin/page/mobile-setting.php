<?php 
class WPJAM_Mobile_Foot{
	public static function __callStatic($method, $args){
		$handler	= wpjam_get_handler([
			'option_name'	=> 'wpjam-theme',
			'items_field'	=> 'mobile_foot',
		]);

		return wpjam_call_handler($handler, $method, ...$args);
	}

	public static function render_item($item){
		$item['foot_menu_ico']	= $item['foot_menu_ico'] ? '<img src="'.$item['foot_menu_ico'].'" width="40" />' : '';

		return $item;
	}

	public static function get_fields($action_key='', $post_id=0){
		return  [
			'foot_menu_text'		=> [
				'title'				=> '菜单文本',
				'type'				=> 'text',
				'show_admin_column'	=>  true,
			],
			'foot_menu_ico'			=> [
				'title'				=> '菜单图标',
				'type'				=> 'img',
				'item_type'			=> 'url',
				'size'				=> '100×100',
				'button_text'		=> '上传菜单图标',
				'description'		=> '',
				'show_admin_column'	=>  true,
			],
			'foot_menu_type' 		=> [
				'title'				=> '菜单类型', 
				'type'				=> 'radio',
				'options'			=> ['link'=>'跳转链接','img'=>'弹出图片','copy'=>'一键复制','phone'=>'拨打电话','form'=>'弹出表单','language'=>'语言选择器（仅支持TranslatePress插件）'],
				'show_admin_column'	=>  true,
			],
			'foot_menu_url' 		=> [
				'title'				=> '跳转链接',
				'type'				=> 'url',
				'show_if'			=> ['key'=>'foot_menu_type','value'=>'link'],
			],
			'foot_menu_phone' 		=> [
				'title'				=> '联系电话',
				'type'				=> 'text',
				'show_if'			=> ['key'=>'foot_menu_type','value'=>'phone'],
			],
			'foot_menu_img' 		=> [
				'title'				=> '弹出图片',
				'type'				=> 'img',
				'item_type'			=> 'url',
				'size'				=> '100×100',
				'show_if'			=> ['key'=>'foot_menu_type','value'=>'img'],
				'button_text'		=> '上传图片',
				'description'		=> '',
			],
			'foot_menu_form_title'  => [
				'title'				=> '表单标题',
				'type'				=> 'text',
				'required'			=> true,
				'show_if'			=> ['key'=>'foot_menu_type','value'=>'form'],
			],
			'foot_menu_form'		=> array_merge(wpjam_get_post_id_field('form'),[
					'title'			=> '表单',
					'show_if'		=> ['key'=>'foot_menu_type','value'=>'form'],
					'required'
				]
			),
			'foot_menu_copy' 		=> [
				'title'				=> '复制内容',
				'type'				=> 'text',
				'show_if'			=> ['key'=>'foot_menu_type','value'=>'copy'],
				'description'		=> '被复制的内容，例如：你的微信号',
			],
			'foot_menu_copy_tip' 	=> [
				'title'				=> '提示语',
				'type'				=> 'text',
				'show_if'			=> ['key'=>'foot_menu_type','value'=>'copy'],
				'description'		=> '复制成功提示语，例如：微信号复制成功，请打开微信添加好友',
			],
			'foot_menu_open_wechat' => [
				'title'				=> '打开微信',
				'type'				=> 'checkbox',
				'show_if'			=> ['key'=>'foot_menu_type','value'=>'copy'],
				'description'		=> '复制成功后跳转到微信APP',
			],
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