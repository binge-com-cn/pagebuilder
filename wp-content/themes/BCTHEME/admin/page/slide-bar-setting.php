<?php 
class WPJAM_Slide_Bar{
	public static function __callStatic($method, $args){
		$handler	= wpjam_get_handler([
			'option_name'	=> 'wpjam-theme',
			'items_field'	=> 'slide_bar',
		]);

		return wpjam_call_handler($handler, $method, ...$args);
	}

	public static function render_item($item){
		$item['slide_bar_ico']	= $item['slide_bar_ico'] ? '<img src="'.$item['slide_bar_ico'].'" width="40" />' : '';

		return $item;
	}

	public static function get_fields($action_key='', $post_id=0){
		return  [
			'slide_bar_type' 		=> [
				'title'				=> '类型', 
				'type'				=> 'radio',
				'options'			=> ['link'=>'跳转链接','img'=>'弹出图片','copy'=>'一键复制','phone'=>'拨打电话','form'=>'弹出表单'],
				'show_admin_column'	=> true,
			],
			'slide_bar_text'		=> [
				'title'				=> '标题',
				'type'				=> 'text',
				'show_admin_column'	=> true,
			],
			'slide_bar_desc'		=> [
				'title'				=> '描述',
				'type'				=> 'text',
				'show_if'			=> ['key'=>'slide_bar_type','value'=>['link','copy','phone','form']],
				'show_admin_column'	=> true,
			],
			'slide_bar_ico'			=> [
				'title'				=> '图标',
				'type'				=> 'img',
				'item_type'			=> 'url',
				'size'				=> '100×100',
				'button_text'		=> '上传图标',
				'description'		=> '',
				'show_admin_column'	=> true,
			],
			'slide_bar_url' 		=> [
				'title'				=> '跳转链接',
				'type'				=> 'text',
				'show_if'			=> ['key'=>'slide_bar_type','value'=>'link'],
			],
			'slide_bar_phone' 		=> [
				'title'				=> '联系电话',
				'type'				=> 'text',
				'show_if'			=> ['key'=>'slide_bar_type','value'=>'phone'],
			],
			'slide_bar_img' 		=> [
				'title'				=> '弹出图片',
				'type'				=> 'img',
				'item_type'			=> 'url',
				'size'				=> '100×100',
				'show_if'			=> ['key'=>'slide_bar_type','value'=>'img'],
				'button_text'		=> '上传图片',
				'description'		=> '',
			],
			'slide_bar_form_title'  => [
				'title'				=> '表单标题',
				'type'				=> 'text',
				'required'			=> true,
				'show_if'			=> ['key'=>'slide_bar_type','value'=>'form'],
			],
			'slide_bar_form'		=> array_merge(wpjam_get_post_id_field('form'),[
					'title'			=> '表单',
					'show_if'		=> ['key'=>'slide_bar_type','value'=>'form'],
					'required'
				]
			),
			'slide_bar_copy' 		=> [
				'title'				=> '复制内容',
				'type'				=> 'text',
				'show_if'			=> ['key'=>'slide_bar_type','value'=>'copy'],
				'description'		=> '被复制的内容，例如：你的微信号',
			],
			'slide_bar_copy_tip' 	=> [
				'title'				=> '提示语',
				'type'				=> 'text',
				'show_if'			=> ['key'=>'slide_bar_type','value'=>'copy'],
				'description'		=> '复制成功提示语，例如：微信号复制成功，请打开微信添加好友',
			],
			'slide_bar_open_wechat' => [
				'title'				=> '打开微信',
				'type'				=> 'checkbox',
				'show_if'			=> ['key'=>'slide_bar_type','value'=>'copy'],
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