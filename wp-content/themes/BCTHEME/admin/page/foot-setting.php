<?php

add_filter('wpjam_theme_setting', function(){

	$sections	= [

		//页脚横幅
		'footer-banner'		=> [
			'title'			=> '页脚横幅', 
			'fields'		=> [

				'show_footer_banner'	=> [
					'title'				=> '显示页脚横幅',
					'type'				=> 'checkbox',
					'class'				=> 'switch',
				],
				'footer_b_title'		=> [
					'title'				=> '标题',
					'type'				=> 'text',
					'style'				=> 'width:400px',
					'show_if'			=> ['key'=>'show_footer_banner','value'=>true],
				],
				'footer_b_desc'			=> [
					'title'				=> '描述',
					'type'				=> 'text',
					'style'				=> 'width:400px',
					'show_if'			=> ['key'=>'show_footer_banner','value'=>true],
				],
				'footer_b_btn_type' 	=> [
					'title'				=> '按钮类型', 
					'type'				=> 'select',
					'options'			=> ['none'=>'不显示','btn'=>'自定义按钮','search'=>'搜索框'],
					'show_if'			=> ['key'=>'show_footer_banner','value'=>true],
				],
				'footer_b_btn_text' 	=> [
					'title'				=> '按钮文本',
					'type'				=> 'text',
					'style'				=> 'width:400px',
					'show_if'			=> ['key'=>'footer_b_btn_type','value'=>'btn'],
				],
				'footer_b_btn_url' 		=> [
					'title'				=> '按钮跳转链接',
					'type'				=> 'url',
					'style'				=> 'width:400px',
					'show_if'			=> ['key'=>'footer_b_btn_type','value'=>'btn'],
				],

			],
		],

		//友情链接
		'foot-link'			=> [
			'title'			=> '友情链接', 
			'fields'		=> [
				'show_footer_link'	=> [
					'title'			=> '显示友情链接',
					'type'			=> 'checkbox',
					'class'			=> 'switch',
					'description'	=> ''
				],
				'mb_remove_footer_link'	=> [
					'title'			=> '移除手机端友情链接',
					'type'			=> 'checkbox',
					'class'			=> 'switch',
					'description'	=> '',
					'show_if'		=> ['key'=>'show_footer_link','value'=>true],
				],
				'link_title'		=> [
					'title'			=> '标题',
					'type'			=> 'text',
					'show_if'		=> ['key'=>'show_footer_link','value'=>true],
				],
				'footer_link'		=> [
					'title'			=> '友情链接',
					'type'			=> 'mu-fields',//mu-fields //fieldset
					'group'			=> true,
					'show_if'		=> ['key'=>'show_footer_link','value'=>true],
					'fields'		=> [
						'link_text' 	=> [
							'title'		=> '链接文本',
							'type'		=> 'text',
							'style'		=> 'width:200px',
						],
						'link_url'	=> [
							'title'	=> '跳转链接',
							'type'	=> 'text',
						],
					],
				],

			]
		],

		'footer'			=>[
			'title'			=>'页脚版权', 
			'fields'		=>[
				'footer_copyright'		=> [
					'title'				=> '页脚版权信息',
					'type'				=> 'textarea',
					'style'				=> 'width: 400px;',
				],
				'xintheme_link'			=> [
					'title'				=> 'XinTheme版权',
					'type'				=> 'checkbox',
					'description'		=> '移除XinTheme版权信息'
				],

			],
		],
		
		'icp'			=>[
			'title'			=>'网站备案号', 
			'fields'		=>[
				'footer_icp'			=> [
					'title'				=> 'ICP备案号',
					'type'				=> 'text',
					'style'				=> 'width: 400px;',
				],
				'footer_gaba'			=> [
					'title'				=> '公安备案号',
					'type'				=> 'text',
					'style'				=> 'width: 400px;',
				],
			],
		],

		//网站配色
		'theme-color'		=> [
			'title'			=> '页脚配色', 
			'fields'		=> [
				'footer_img'	=> [
					'title'		=> '页脚背景图片',
					'type'		=> 'img',
					'item_type'	=> 'url',
					//'size'		=> '292×100'
				],
				//页脚配色
				'footer-color'			=> [
					'title'				=> '背景颜色',
					'type'				=> 'fieldset',//mu-fields //fieldset
					'fields'			=> [
						'footer_color' 		=> [
							'title'			=> '背景颜色',
							'type'			=> 'color',
							'value'			=> '#0a0b14',
							'data-alpha-enabled' => true
						],
					]
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