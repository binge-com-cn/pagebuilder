<?php

add_filter('wpjam_theme_setting', function(){

	//分享按钮样式
	$share_style = $share_variant = [];
	for($i=1; $i<=8; $i++){
		$share_style[$i]	= ['image'=>get_template_directory_uri().'/static/images/share-style/style-'.$i.'.svg'];
	}
	for($i=1; $i<=2; $i++){
		$share_variant[$i]	= ['image'=>get_template_directory_uri().'/static/images/share-style/variant-'.$i.'.svg'];
	}

	//头部样式
	/*
	$header_style = [];
	for($i=1; $i<=3; $i++){
		$header_style['style_'.$i]	= ['image'=>get_template_directory_uri().'/static/images/modules-thumbnail/header-'.$i.'.png'];
	}
	*/

	$sections	= [
		//头部设置
		'header'			=>[
			'title'			=>'头部设置', 
			'fields'		=>[
				'header_style'			=> [
					'title'				=> '头部样式', 
					'type'				=> 'radio',
					'options'			=> [
						'style_1' => ['title' => '样式-1（上Logo，下菜单）', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/header-1.jpg'],
						'style_2' => ['title' => '样式-2（左Logo，右菜单）', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/header-2.jpg'],
						'style_3' => ['title' => '样式-3（透明导航栏）', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/header-3.jpg'],
					],
					//'options'			=> ['style_1'=>'样式-1','style_2'=>'样式-2','style_3'=>'样式-3'],
				],
				'header_line'			=> [
					'title'				=> '菜单分割线',
					'type'				=> 'checkbox',
					'class'				=> 'switch',
					'show_if'			=> ['key'=>'header_style','value'=>'style_3'],
					'description'		=> ''
				],
				'nav_color'				=> [
					'title'				=> '导航栏配色',
					'type'				=> 'fieldset',
					'group'				=> true,
					'fields'				=> [
						'nav_color_bg'		=> [
							'title'			=> '',
							'type'			=> 'color',
							'value'			=> '#c00d23',
							'description'	=> '导航栏背景颜色',
							'show_if'		=> ['key'=>'header_style','value'=>'style_1'],
						],
						'nav_color_current'	=> [
							'title'  		=> '',
							'type'  		=> 'color',
							'value'  		=> '#000',
							'description'	=> '导航栏高亮颜色',
							'show_if'		=> ['key'=>'header_style','value'=>'style_1'],
						],
						'nav_color_text'	=> [
							'title'  		=> '',
							'type'  		=> 'color',
							'value'  		=> '#fff',
							'description'	=> '导航栏文本颜色',
							'show_if'		=> ['key'=>'header_style','value'=>['style_1','style_3']],
						],
						'nav_color_text2'	=> [
							'title'  		=> '',
							'type'  		=> 'color',
							'value'  		=> '#fff',
							'description'	=> '导航栏高亮文本颜色',
						],
					]
				],
				'header-logo'			=> [
					'title'				=> 'Logo设置',
					'type'				=> 'fieldset',//mu-fields //fieldset
					//'group'			=> true,
					'fields'			=> [
						'logo'			=> [
							'title'		=> '网站Logo',
							'type'		=> 'img',
							'item_type'	=> 'url',
							'size'		=> '292×100',
							'description'=> '默认导航栏Logo',
						],
						'logo_2'		=> [
							'title'		=> '网站Logo',
							'type'		=> 'img',
							'item_type'	=> 'url',
							'size'		=> '292×100',
							'show_if'	=> ['key'=>'header_style','value'=>'style_3'],
							'description'=> '悬浮导航栏Logo',
						],
  						'logo_title'	=> [
							'title'		=> '右侧标题',
							'type'		=> 'text',
							'show_if'	=> ['key'=>'header_style','value'=>'style_1'],
							'style'		=> 'width:400px',
							//'class'	=> 'all-options',
						],
						'logo_desc'		=> [
							'title'		=> '右侧描述',
							'type'		=> 'text',
							'show_if'	=> ['key'=>'header_style','value'=>'style_1'],
							//'class'	=> 'all-options',
							'style'		=> 'width:400px',

						],
					]
				],
				'header-tel'			=> [
					'title'				=> '联系电话',
					'type'				=> 'fieldset',//mu-fields //fieldset
					//'group'			=> true,
					'fields'			=> [
  						'tel_title'		=> [
							'title'		=> '电话标题',
							'type'		=> 'text',
							//'class'	=> 'all-options',
							'style'		=> 'width:400px',
						],
						'tel_number'	=> [
							'title'		=> '电话号码',
							'type'		=> 'text',
							//'class'	=> 'all-options',
							'style'		=> 'width:400px',

						],
					],
					'show_if'			=> ['key'=>'header_style','value'=>'style_1'],
				],
				'header_notice'			=> [
					'title'				=> '顶部公告栏',
					'type'				=> 'textarea',
					'style'				=> 'width:480px',
				],

			]
		],

		//文章页面
		'single'			=> [
			'title'			=> '文章页面', 
			'fields'		=> [
				'single_related'		=> [
					'title'				=> '自定义相关推荐',
					'type'				=> 'checkbox',
					'description'		=> '开启后可以在编辑文章的时候设置自定义相关推荐文章，不设置则默认按同分类文章推荐'
				],
				'single_link_nofollow'	=> [
					'title'				=> '文章外链 自动nofollow',
					'type'				=> 'checkbox',
					'description'		=> '对文章中的所有外部链接自动添加nofollow标签'
				],
				'single_indent'			=> [
					'title'				=> '文章缩进',
					'type'				=> 'checkbox',
					'description'		=> '文章段落首行缩进'
				],
				'single_banner'			=> [
					'title'				=> '文章页Banner',
					'type'				=> 'checkbox',
					'description'		=> '文章详情页面，顶部显示当前所属分类的Banner内容。'
				],
				'single_excerpt'		=> [
					'title'				=> '文章摘要',
					'type'				=> 'checkbox',
					'description'		=> '文章详情页面，内容顶部显示「文章摘要」。'
				],
				'single_date'			=> [
					'title'				=> '文章发布时间',
					'type'				=> 'checkbox',
					'description'		=> '文章详情页面中显示「文章发布时间」。'
				],
				'single_category'		=> [
					'title'				=> '文章分类目录',
					'type'				=> 'checkbox',
					'description'		=> '文章详情页面中显示「文章分类目录」。'
				],
				'single_views'			=> [
					'title'				=> '文章浏览量',
					'type'				=> 'checkbox',
					'description'		=> '文章详情页面中显示「文章浏览量」。'
				],
				'single_custom_text'	=> [
					'title'				=> '自定义文本',
					'type'				=> 'fieldset',
					//'group'			=> true,
					'fields'				=> [
						'text_product_details'	=> [
							'title'				=> '产品详情',
							'type'				=> 'text',
							'value'				=> '产品详情',
							//'description'		=> '',
						],
						'text_product_related'	=> [
							'title'				=> '相关产品',
							'type'				=> 'text',
							'value'				=> '相关产品',
							//'description'		=> '',
						],
						'text_single_related'	=> [
							'title'				=> '相关推荐',
							'type'				=> 'text',
							'value'				=> '相关推荐',
							//'description'		=> '',
						],
					]
				],
			]
		],

		//网站配色
		'theme-color'		=> [
			'title'			=> '网站配色', 
			'fields'		=> [
				//网站主配色
				'global_color'	=> [
					'title'		=> '网站主配色',
					'type'		=> 'color',
					'value'		=> '#c00d23'
				],

				'slide_bar-color'		=> [
					'title'				=> '客服工具配色',
					'type'				=> 'fieldset',
					'group'				=> true,
					'fields'				=> [
						'bar_color_bg'		=> [
							'title'			=> '',
							'type'			=> 'color',
							'value'			=> '#000',
							'description'	=> '背景颜色',
						],
						'bar_color_current'	=> [
							'title'  		=> '',
							'type'  		=> 'color',
							'value'  		=> '#c00d23',
							'description'	=> '高亮颜色',
						],
						'bar_color_text'	=> [
							'title'  		=> '',
							'type'  		=> 'color',
							'value'  		=> '#fff',
							'description'	=> '文本颜色',
						],
						'bar_color_text2'	=> [
							'title'  		=> '',
							'type'  		=> 'color',
							'value'  		=> '#fff',
							'description'	=> '高亮文本颜色',
						],
						'bar_color_line'	=> [
							'title'  		=> '',
							'type'  		=> 'color',
							'value'  		=> '#292929',
							'description'	=> '分割线颜色',
						],
					]
				],

				'mobile_nav-color'		=> [
					'title'				=> '手机端底部菜单配色',
					'type'				=> 'fieldset',
					'group'				=> true,
					'fields'				=> [
						'mobile_nav_bg'		=> [
							'title'			=> '',
							'type'			=> 'color',
							'value'			=> '#fff',
							'description'	=> '背景颜色',
						],
						'mobile_nav_text'	=> [
							'title'  		=> '',
							'type'  		=> 'color',
							'value'  		=> '#000',
							'description'	=> '文本颜色',
						],
					]
				],
			]
		],

		'theme-sidebar'	=> [
			'title'		=> '侧栏位置', 
			'fields'	=> [
				'theme_sidebar'	=> [
					'title'		=> '侧边栏显示位置', 
					'type'		=> 'radio',
					'options'	=> ['left'=>'左边','right'=>'右边'],
				],
			]
		],

		'post-share'	=> [
			'title'		=> '文章分享', 
			'summary'	=> '',
			'fields'	=> [
				'post_share'		=> [
					'title'			=> '分享开关',
					'type'			=> 'checkbox',
					'class'			=> 'switch',
					'description'	=> '开启后在文章页面会显示分享按钮，你可以在下面的设置中自定义添加要分享的平台...'
				],
				'share_title'		=> [
					'title'			=> '标题',
					'type'			=> 'text',
					'value'			=> '分享',
					'show_if'		=> ['key'=>'post_share','value'=>'1'],
				],
				'share_platforms'		=> [
					'title'				=> '平台',
					'type'				=> 'mu-fields',//mu-fields //fieldset
					'group'				=> true,
					//'direction'		=>'row',
					'fields'			=> [
  						'platforms_title'	=> [
							'title'			=> '',
							'type'			=> 'select',
							'options'		=> [
								'wechat'		=> '微信',
								'weibo'			=> '微博',
								'facebook'		=> 'Facebook',
								'twitter'		=> 'X (ex twitter)',
								'reddit'		=> 'Reddit',
								'pinterest'		=> 'Pinterest',
								'email'			=> 'Email',
								'linkedin'		=> 'LinkedIn',
								'stumbleupon'	=> 'StumbleUpon',
								'whatsapp'		=> 'WhatsApp',
								'vk'			=> 'vKontakte',
								'copy'			=> '复制链接',
							],
							//'class'	=> 'all-options',
							//'style'	=> 'width: 320px;',
						],
					],
					'show_if'			=> ['key'=>'post_share','value'=>'1'],
				],
				'share_style'	=> [
					'title'		=> '按钮样式',
					'type'		=> 'radio',
					'options'	=> $share_style,
					'show_if'	=> ['key'=>'post_share','value'=>'1'],
				],
				'share_variant'	=> [
					'title'		=> '按钮风格',
					'type'		=> 'radio',
					'options'	=> $share_variant,
					'show_if'	=> ['key'=>'share_style','value'=>['1','2','3','4','6','7']],
				],
				
			]
		],

		'theme-other'	=> [
			'title'		=> '其他设置', 
			'summary'	=> '',
			'fields'	=> [
				'remove_search'		=> [
					'title'			=> '移除前端搜索框',
					'type'			=> 'checkbox',
					'class'			=> 'switch',
					'description'	=> '开启后将移除网站前端所有位置的搜索框'
				],
				'remove_title_delimiter_spaces'	=> [
					'title'						=> '移除标题分隔符中的空格',
					'type'						=> 'checkbox',
					'class'						=> 'switch',
					'description'				=> '<br>移除前：文章标题 - 网站标题<br>移除后：文章标题-网站标题'
				],
			]
		],

		'theme-update'	=> [
			'title'		=> '禁用主题更新', 
			'summary'	=> '<strong>禁用更新后请停止更新WPJAM插件，以免出现不兼容的情况。</strong>',
			'fields'	=> [
				'theme_update'		=> [
					'title'			=> '禁用主题更新',
					'type'			=> 'checkbox',
					'class'			=> 'switch',
					'description'	=> '如果你修改过主题文件或者不想收到主题更新推送，请开启！'
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