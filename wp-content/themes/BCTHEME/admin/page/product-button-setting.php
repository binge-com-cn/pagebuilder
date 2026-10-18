<?php 
class WPJAM_Product_Button{
	public static function __callStatic($method, $args){
		$handler	= wpjam_get_handler([
			'option_name'	=> 'wpjam-theme',
			'items_field'	=> 'product_btn',
		]);

		return wpjam_call_handler($handler, $method, ...$args);
	}

	public static function get_fields($action_key='', $post_id=0){
		return  [
			'product_btn_text' 		=> [
				'title'				=> '按钮文本',
				'type'				=> 'text',
				'required'			=> true,
				'show_admin_column'	=> true,
			],
			'product_btn_icon' 		=> [
				'title'				=> '按钮图标',
				'type'				=> 'text',
				'description'		=> '粘贴图标代码，例如：&lt;i class="ri-wechat-2-line"&gt;&lt;/i&gt;',
				'show_admin_column'	=> true,
			],
			'product_btn_color'		=> [
				'title'				=> '按钮背景颜色',
				'type'				=> 'color',
			],
			'product_btn_type'		=> [
				'title'				=> '按钮类型', 
				'type'				=> 'radio',
				'options'			=> ['link'=>'跳转链接','img'=>'弹出图片','copy'=>'一键复制','form'=>'弹出表单'],
				'show_admin_column'	=> true,
			],
			'product_btn_url' 		=> [
				'title'				=> '跳转链接',
				'type'				=> 'url',
				'required'			=> true,
				'show_if'			=> ['key'=>'product_btn_type','value'=>'link'],
			],
			'product_btn_img' 		=> [
				'title'				=> '弹出图片',
				'type'				=> 'img',
				'item_type'			=> 'url',
				'size'				=> '100×100',
				'show_if'			=> ['key'=>'product_btn_type','value'=>'img'],
				'button_text'		=> '上传图片',
				'description'		=> '',
			],
			'product_btn_form_title'=> [
				'title'				=> '表单标题',
				'type'				=> 'text',
				'required'			=> true,
				'show_if'			=> ['key'=>'product_btn_type','value'=>'form'],
			],
			'product_btn_form'		=> array_merge(wpjam_get_post_id_field('form'),[
					'title'			=> '表单',
					'show_if'		=> ['key'=>'product_btn_type','value'=>'form'],
					'required'
				]
			),
			'product_btn_copy' 		=> [
				'title'				=> '复制内容',
				'type'				=> 'text',
				'required'			=> true,
				'show_if'			=> ['key'=>'product_btn_type','value'=>'copy'],
				'description'		=> '被复制的内容，例如：你的微信号',
			],
			'product_btn_copy_tip' 	=> [
				'title'				=> '提示语',
				'type'				=> 'text',
				'required'			=> true,
				'show_if'			=> ['key'=>'product_btn_type','value'=>'copy'],
				'description'		=> '复制成功提示语，例如：微信号复制成功，请打开微信添加好友',
			],
			'product_btn_open_wechat' => [
				'title'				=> '打开微信',
				'type'				=> 'checkbox',
				'show_if'			=> ['key'=>'product_btn_type','value'=>'copy'],
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