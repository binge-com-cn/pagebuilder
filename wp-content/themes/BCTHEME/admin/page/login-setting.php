<?php

add_filter('wpjam_theme_setting', function(){

	$sections	= [

		//登录页面链接
		'custom-login-url'			=> [
			'title'					=> '登录页面链接', 
			'summary'				=> '自定义登录页面链接，并禁止访问默认的登录页面链接（如：/wp-admin、/admin、/login 和 /wp-login.php）',
			'fields'				=> [
				'custom_login_url'	=> [
					'title'			=> '自定义登录页面链接',
					'type'			=> 'checkbox',
					'class'			=> 'switch',
					'description'	=> ''
				],
				'redirect_login'	=> [
					'title'			=> '自定义登录页面链接',
					'type'			=> 'fields',
					'fields'		=> [
						'redirect_login_url'=> [
							'type'			=> 'text',
							//'class'		=> 'small-text',
							'style'			=> 'width:150px',
							'before'		=> esc_url(home_url('/')),
							'value'			=> 'dahuzi',
						], 
					],
					'show_if'		=> ['key'=>'custom_login_url','value'=>true],
					'description'	=> '通过更改登录页面URL来阻止访问 wp-login.php 页面和 wp-admin 目录以保护您的网站'
				],

			]
		],

		//登录页面美化
		'login-style'		=> [
			'title'			=> '登录页面美化', 
			'fields'		=> [
				'login_style'	=> [
					'title'			=> '登录页面美化',
					'type'			=> 'checkbox',
					'class'			=> 'switch',
					'description'	=> ''
				],
				'login_logo_form'	=> [
					'title'			=> '登录框位置',
					'type'			=> 'radio',
					'show_if'		=> ['key'=>'login_style','value'=>true],
					'options'		=> ['left'=>'靠左','middle'=>'居中','right'=>'靠右'],
					'value'			=> 'middle',
				],
				'login_logo'		=> [
					'title'			=> '登录页面Logo',
					'type'			=> 'img',
					'item_type'		=> 'url',
					'size'			=> '200 x 200',
					'show_if'		=> ['key'=>'login_style','value'=>true],
				],
				'login_bgimg'		=> [
					'title'			=> '登录页面背景图片',
					'type'			=> 'img',
					'item_type'		=> 'url',
					'show_if'		=> ['key'=>'login_style','value'=>true],
				],
				'login_lang_false'	=> [
					'title'			=> '移除语言选择器',
					'type'			=> 'checkbox',
					'class'			=> 'switch',
					'description'	=> '移除登录页面的语言选择器',
					'show_if'		=> ['key'=>'login_style','value'=>true],
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