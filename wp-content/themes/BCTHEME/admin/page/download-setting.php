<?php
//Download页设置
add_filter('wpjam_theme_setting', function(){

	$sections = [

		'page-banner'		=> [
			'title'			=> 'Banner图片',
			'fields'		=> [
				'download_banner'			=> [
					'title'					=> 'Banner图片',
					'type'					=> 'mu-fields',//mu-fields //fieldset
					//'group'				=> true,
					'fields'				=> [
						'banner_img'		=> [
							'title'			=> '电脑端：',
							'type'			=> 'img',
							'item_type'		=> 'url',
							//'size'		=> '292×50'
							//'description' => '建议尺寸：1920 × 550 px'
						],
						'banner_img_mb' 	=> [
							'title'       	=> '手机端：',
							'type'        	=> 'img',
							'item_type'   	=> 'url',
							//'required'  	=> true,
							//'description' => '建议尺寸：790 × 560 px'
						],
						'banner_title'		=> [
							'title'			=> '标题：',
							'type'			=> 'text',
							'style'			=> 'width:500px',
						],
						'banner_desc'		=> [
							'title'			=> '描述：',
							'type'			=> 'text',
							'show_if'		=> ['key'=>'banner_title', 'compare'=>'!=', 'value'=>''],
							'style'			=> 'width:500px',
						],
						'banner_text_color'	=> [
							'title'			=> '文本颜色：',
							'type'			=> 'color',
							'description'	=> '文本颜色：标题 和 描述 的文本颜色',
							'show_if'		=> ['key'=>'banner_title', 'compare'=>'!=', 'value'=>''],
							'data-alpha-enabled' => true
						], 
						'banner_mask_color'	=> [
							'title'			=> '图片滤镜：',
							'type'			=> 'color',
							'description'	=> '图片滤镜：给图片增加一个 自定义颜色 的遮罩层',
							'data-alpha-enabled' => true
						], 
						'banner_url'		=> [
							'title'			=> '跳转链接：',
							'type'			=> 'url',
							//'class'		=> 'all-options',
							'style'			=> 'width:500px',
						],
						'banner_button'		=> [
							'title'			=> '按钮文本',
							'type'			=> 'text',
							'description'	=> '留空则点击图片进行跳转',
							'show_if'		=> ['key'=>'banner_url', 'compare'=>'!=', 'value'=>''],
							'style'			=> 'width:500px',
						],
						'banner_button_bg'	=> [
							'title'			=> '按钮颜色：',
							'type'			=> 'color',
							'description'	=> '按钮颜色：整个按钮颜色',
							'show_if'		=> ['key'=>'banner_button', 'compare'=>'!=', 'value'=>''],
							'data-alpha-enabled' => true
						], 
						'banner_button_co'	=> [
							'title'			=> '文本颜色：',
							'type'			=> 'color',
							'description'	=> '文本颜色：按钮文本颜色',
							'show_if'		=> ['key'=>'banner_button', 'compare'=>'!=', 'value'=>''],
							'data-alpha-enabled' => true
						], 
						'banner_target'		=> [
							'title'			=> '打开方式：',
							'type'			=> 'select',
							'options'		=> [
								''			=> '当前窗口打开',
								'_blank'	=> '新窗口打开'
							],
							'show_if'		=> ['key'=>'banner_url', 'compare'=>'!=', 'value'=>'']
						],
					]
				],
				'download_banner_h'	=> [
					'title'			=> '图片高度',
					'type'			=> 'fields',
					'fields'		=> [
						'download_banner_pc_h'	=> [
							'type'			=> 'number',
							'class'			=> 'small-text',
							'before'		=> 'PC端:',
							'after'			=> 'px &nbsp;&nbsp;&nbsp;&nbsp;'
						], 
						'download_banner_mb_h'	=> [
							'type'			=> 'number',
							'class'			=> 'small-text',
							'before'		=> '手机端:',
							'after'			=> 'px'
						],
					],
					'description'	=> '留空则不限制，显示图片原始比例'
				],
			]
		],

	];

	$field_validate	= function($value){
		flush_rewrite_rules();

		return $value;
	};

	return compact('sections', 'field_validate');
});