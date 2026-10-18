<?php

//自定义品牌
include_once TEMPLATEPATH .'/admin/page/branding-setting.php';

add_action('admin_footer', 'wp_enqueue_editor');

wpjam_add_menu_page('theme-setting', array(
	'menu_title'	=> '主题设置',
	'icon'			=> 'dashicons-art',
	'capability'	=> 'manage_options',
	'position'		=> '9999',
	'function'		=> 'option',
	'subs'			=> [
		'theme-setting'		=> [
			'menu_title'	=> '基础设置',
			'function'		=> 'option',
			'option_name'	=> 'wpjam-theme',
			'page_file'		=> TEMPLATEPATH .'/admin/page/setting.php',
		],
		'theme-foot'		=> [
			'menu_title'	=> '页脚设置',
			'function'		=> 'option',
			'option_name'	=> 'wpjam-theme',
			'page_file'		=> TEMPLATEPATH .'/admin/page/foot-setting.php',
		],
		'theme-product'		=> [
			'menu_title'	=> '产品设置',
			'function'		=> 'option',
			'option_name'	=> 'wpjam-theme',
			'page_file'		=> TEMPLATEPATH .'/admin/page/product-setting.php',
		],
		'theme-product-btn'	=> [
			'menu_title'	=> '└ 自定义按钮',
			'page_title'	=> '自定义按钮',
			'function'		=> 'list',
			'list_table'	=> 'WPJAM_Product_Button',
			'page_file'		=> TEMPLATEPATH .'/admin/page/product-button-setting.php',
		],
		'theme-product-tab'	=> [
			'menu_title'	=> '└ 产品TAB栏',
			'page_title'	=> '产品TAB',
			'function'		=> 'list',
			'list_table'	=> 'WPJAM_Product_Tab',
			'page_file'		=> TEMPLATEPATH .'/admin/page/product-tab-setting.php',
		],
		'theme-slide-bar'	=> [
			'menu_title'	=> '客服工具',
			'function'		=> 'list',
			'list_table'	=> 'WPJAM_Slide_Bar',
			'page_file'		=> TEMPLATEPATH .'/admin/page/slide-bar-setting.php',
		],
		'theme-mobile'		=> [
			'menu_title'	=> '└ 手机端底部菜单',
			'page_title'	=> '手机端底部菜单',
			'function'		=> 'list',
			'list_table'	=> 'WPJAM_Mobile_Foot',
			'page_file'		=> TEMPLATEPATH .'/admin/page/mobile-setting.php',
		],
		'theme-login'		=> [
			'menu_title'	=> '登录页设置',
			'function'		=> 'option',
			'option_name'	=> 'wpjam-theme',
			'page_file'		=> TEMPLATEPATH .'/admin/page/login-setting.php',
		],

	]	
));

//FAQ页设置
wpjam_add_menu_page('archive-faq', [
	'menu_title'	=> '页面设置',
	'parent'		=> 'faqs',
	'function'		=> 'option',
	'option_name'	=> 'wpjam-theme',
	'page_file'		=> TEMPLATEPATH .'/admin/page/faq-setting.php',	
]);
//Download页设置
wpjam_add_menu_page('archive-download', [
	'menu_title'	=> '页面设置',
	'parent'		=> 'downloads',
	'function'		=> 'option',
	'option_name'	=> 'wpjam-theme',
	'page_file'		=> TEMPLATEPATH .'/admin/page/download-setting.php',	
]);

//文章扩展选项
wpjam_register_post_option('post_product', [
	'title'				=> '产品设置',
	'post_type'			=> 'post',
	'list_table'		=> true,
	'priority'			=> 'high',
	'fields'			=> [
		'product_gallery'			=> [
			'title'					=> '产品图集',
			'type'					=> 'mu-fields',//mu-fields //fieldset
			'group'					=> true,
			'fields'				=> [
				'gallery_type'		=> [
					'type'			=> 'select',
					'options'		=> [
						'img'		=> '图片',
						'video'		=> '视频'
					],
				],
				'product_img'		=> [
					'type'			=> 'img',
					'item_type'		=> 'url',
					'description'	=> '',
					//'show_if'		=> ['key'=>'gallery_type','value'=>'img']
				],
				'product_video'		=> [
					'placeholder'	=> '请输入视频链接',
					'type'			=> 'text',
					'show_if'		=> ['key'=>'gallery_type','value'=>'video']
				],
			]
		],
		'product_excerpt'	=> [
			'title'			=> '产品摘要',
			'type'			=> 'editor',
		],
	]
]);

//团队扩展选项
wpjam_add_menu_page('archive-team', [
	'menu_title'	=> '页面设置',
	'parent'		=> 'teams',
	'function'		=> 'option',
	'option_name'	=> 'wpjam-theme',
	'page_file'		=> TEMPLATEPATH .'/admin/page/team-setting.php',	
]);
wpjam_register_post_option('post_team', [
	'title'				=> '成员信息',
	'post_type'			=> 'team',
	'list_table'		=> true,
	//'context'			=> 'side', //显示在侧栏
	'priority'			=> 'high',
	'fields'			=> [
		'team_position'		=> [
			'title'			=> '职位',
			'placeholder'	=> '职位',
			'type'			=> 'text',
			'style'			=> 'width:100%',
		],
		'team_contact'				=> [
			'title'					=> '联系方式',
			'type'					=> 'mu-fields',//mu-fields //fieldset
			//'group'				=> true,
			'fields'				=> [
				'team_contact_type' 	=> [
					'title'				=> '按钮类型', 
					'type'				=> 'select',
					'options'			=> ['link'=>'跳转链接','img'=>'弹出图片','copy'=>'一键复制','phone'=>'拨打电话','form'=>'弹出表单'],
				],
				'team_contact_icon'	=> [
					'title'			=> '图标',
					'placeholder'	=> '粘贴 remixicon 图标代码',
					'type'			=> 'text',
					'description'	=> '图标代码获取途径：https://www.remixicon.cn',
					'style'			=> 'width:500px',
				],

				'team_contact_url' 		=> [
					'title'				=> '跳转链接',
					'type'				=> 'text',
					'show_if'			=> ['key'=>'team_contact_type','value'=>'link'],
					'style'				=> 'width:500px',
				],
				'team_contact_phone' 	=> [
					'title'				=> '联系电话',
					'type'				=> 'text',
					'show_if'			=> ['key'=>'team_contact_type','value'=>'phone'],
					'style'				=> 'width:500px',
				],
				'team_contact_img' 		=> [
					'title'				=> '弹出图片',
					'type'				=> 'img',
					'item_type'			=> 'url',
					'size'				=> '100×100',
					'show_if'			=> ['key'=>'team_contact_type','value'=>'img'],
					'button_text'		=> '上传图片',
					'description'		=> '',
					'style'				=> 'width:500px',
				],
				'team_contact_form_title'  	=> [
					'title'					=> '表单标题',
					'type'					=> 'text',
					'required'				=> true,
					'show_if'				=> ['key'=>'team_contact_type','value'=>'form'],
					'style'					=> 'width:500px',
				],
				'team_contact_form'			=> array_merge(wpjam_get_post_id_field('form'),[
						'title'				=> '表单',
						'show_if'			=> ['key'=>'team_contact_type','value'=>'form'],
						'style'				=> 'width:500px',
						'required'
					]
				),
				'team_contact_copy' 		=> [
					'title'					=> '复制内容',
					'type'					=> 'text',
					'show_if'				=> ['key'=>'team_contact_type','value'=>'copy'],
					'description'			=> '被复制的内容，例如：你的微信号',
					'style'					=> 'width:500px',
				],
				'team_contact_copy_tip' 	=> [
					'title'					=> '提示语',
					'type'					=> 'text',
					'show_if'				=> ['key'=>'team_contact_type','value'=>'copy'],
					'description'			=> '复制成功提示语，例如：微信号复制成功，请打开微信添加好友',
					'style'					=> 'width:500px',
				],
				'team_contact_open_wechat' 	=> [
					'title'					=> '打开微信',
					'type'					=> 'checkbox',
					'show_if'				=> ['key'=>'team_contact_type','value'=>'copy'],
					'description'			=> '复制成功后跳转到微信APP',
				],
			]
		],
	]
]);

//自定义相关推荐
$single_related = wpjam_theme_get_setting('single_related');
if ( !empty($single_related) ){
	wpjam_register_post_option('related', [
		'title'		=> '相关推荐',
		'post_type'	=> 'post',
		'list_table'=> true,
		'priority'	=> 'high',
		'fields'	=> [
			'related_post' => wpjam_get_post_id_field('post',['title'=>'指定文章','type'=>'mu-text','style' => 'width: 100%;','description'	=> '自定义相关推荐文章，不设置则默认按同分类文章推荐',]),
		]
	]);
}

//下载扩展选项
wpjam_add_post_type_field('download', 'download_url', [
	'title'		=> '下载地址',
	'type'		=> 'textarea',
	'style'		=> 'width:100%;height:46px',
]);

function wpjam_theme_admin_page_load($screen){

	// 获取WordPress菜单
	$menus = wp_get_nav_menus();
	// 定义一个空数组，用于存储菜单数据
	$menu_options = array(
		'' => '请选择一个菜单', // 空白的默认选项
	);
	// 将菜单添加到选项数组中
	foreach ($menus as $menu) {
		$menu_options[$menu->term_id] = $menu->name;
	}

	//页面扩展选项
	wpjam_add_post_type_field('page', 'page_banner', [
		'title'			=> 'Banner设置',
		'description'	=> '只有上传图片后此处的设置才会有效。',
		'type'			=> 'fields',
		'fields'		=> [
			'banner_text_color'		=> [
				'title'				=> '文本颜色：',
				'type'				=> 'color',
				'data-alpha-enabled'=> true
			], 
			'banner_mask_color'		=> [
				'title'				=> '图片滤镜：',
				'type'				=> 'color',
				'data-alpha-enabled'=> true
			],
		],
	]);

	wpjam_add_post_type_field('page', 'page_widgets', [
		'title'			=> '独立小工具',
		'type'			=> 'checkbox',
		'description'	=> '创建独立侧栏小工具，开启后此页面侧栏到「小工具」中进行单独设置'
	]);

	wpjam_add_post_type_field('page', 'page_menu', [
		'title'			=> '页面菜单',
		'type'			=> 'select',
		'options'		=> $menu_options, // 输出菜单选项数组
		'description'	=> '显示在页面内容顶部，不支持二级菜单'
	]);

	//产品TAB
	if($screen->base == 'edit' && $screen->post_type == 'post'){
		wpjam_register_posts_column('product_tabs', [
			'style'		=> 'width: 200px',
			'title'		=> '产品TAB',
			'callback'	=> ['WPJAM_Post_Product_Tab', 'column_callback']
		]);

		foreach(WPJAM_Post_Product_Tab::get_item_actions() as $action_key => $args){
			foreach(['title', 'page_title'] as $key){
				if(isset($args[$key])){
					$args[$key]		= str_replace('项目', 'TAB', $args[$key]);
					$args['fields']	= ['WPJAM_Post_Product_Tab', 'get_fields'];
				}
			}

			wpjam_register_list_table_action($action_key, $args);
		}

	}elseif(in_array($screen->base, ['term', 'edit-tags']) && ( $screen->taxonomy == 'category' || $screen->taxonomy == 'post_tag') ){
		//分类扩展
		wpjam_register_term_option('layout', [
			'title'			=> '列表样式',
			'taxonomy'		=> 'category', 
			'list_table'	=> true,
			'action'		=> 'edit',
			'fields'		=> [
				'tip'				=> [
					'title'			=> '请注意：',
					'type'			=> 'view',
					'description'	=> '子分类设置留空时，会继承父分类的设置'
				],
				'cat_widgets'		=> [
					'title'			=> '独立侧栏小工具',
					'type'			=> 'checkbox',
					'description'	=> '创建独立侧栏小工具，开启后到「外观-小工具」进行设置',
					'show_if'		=> ['cat_list_type', 'IN', ['one', 'two', 'three']]
				],
				'cat_list_type'		=> [
					'title'			=> '列表样式',
					'type'			=> 'radio',
					//'options'		=> ['one'=>'','two'=>'','three'=>'','four'=>'']
					'options'		=> [
						//'one'	=>'产品4列',
						'one'   => ['title' => '产品4列', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-1.jpg'],
						'two'	=> ['title' => '产品3列', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-2.jpg'],
						'three'	=> ['title' => '新闻资讯', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-3.jpg'],
						'blog'	=> ['title' => '新闻资讯', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-4.jpg']
					],
					'value'			=> 'one',
				],
				'list_three_thumb'	=> [
					'title'			=> '文章缩略图',
					'type'			=> 'checkbox',
					'description'	=> '在文章列表中显示文章缩略图',
					//'value'		=> 1,
					'show_if'		=> ['cat_list_type', '=', 'three']
				],
				'list_thumb_ratio'	=> [
					'title'			=> '缩略图比例',
					'type'			=> 'select',
					'options'		=> [
						''				=> '原始比例',
						'ratio-1-1'		=> '1:1',
						'ratio-4-3'		=> '4:3',
						'ratio-3-2'		=> '3:2',
						'ratio-16-9'	=> '16:9',
						'ratio-21-9'	=> '21:9',
					],
					'description'	=> '统一控制电脑端和手机端的文章缩略图比例；留空显示图片原始比例。'
				],
				'list_one_mobile'	=> [
					'title'			=> '手机端 列表样式',
					'type'			=> 'radio',
					'options'		=> [
						'one'	=> ['title' => '样式-1', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-1-mobile-1.jpg'],
						'two'	=> ['title' => '样式-2', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-1-mobile-2.jpg']
					],
					'value'			=> 'one',
					'show_if'		=> ['cat_list_type', '=', 'one']
				],
				'list_two_mobile'	=> [
					'title'			=> '手机端 列表样式',
					'type'			=> 'radio',
					'options'		=> [
						'one'=>	['title' => '样式-1', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-1-mobile-1.jpg'],
						'two'=> ['title' => '样式-1', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-1-mobile-2.jpg'],
					],
					'value'			=> 'one',
					'show_if'		=> ['cat_list_type', '=', 'two']
				],
				'list_three_mobile'	=> [
					'title'			=> '手机端 列表样式',
					'type'			=> 'radio',
					'options'		=> [
						'one'=> ['title' => '样式-1', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-3-mobile-1.jpg'],
						'two'=> ['title' => '样式-2', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-3-mobile-2.jpg'],
						'three'=> ['title' => '样式-3', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-3-mobile-3.jpg'],
					],
					'value'			=> 'one',
					'show_if'		=> ['cat_list_type', '=', 'three']
				],
				'list_blog_mobile'	=> [
					'title'			=> '手机端 列表样式',
					'type'			=> 'radio',
					'options'		=> [
						'one'=> ['title' => '样式-1', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-blog-mobile-1.jpg'],
						'two'=> ['title' => '样式-2', 'image' => get_template_directory_uri().'/static/images/modules-thumbnail/cat-list-style-blog-mobile-2.jpg'],
					],
					'value'			=> 'one',
					'show_if'		=> ['cat_list_type', '=', 'blog']
				],
				'post_meta'			=> [
					'title'			=> '显示设置',
					'type'			=> 'fields',
					'fields'		=> [
						'post_category'		=> [
							//'title'		=> '', 
							'type'			=> 'checkbox',
							'class'			=> 'switch',
							'before'		=> '分类目录',
						], 
						'post_date'			=> [
							//'title'		=> '', 
							'type'			=> 'checkbox',
							'class'			=> 'switch',
							'before'		=> '发布时间',
							//'after'		=> '<br><br>',
						],
						'list_style' 		=> [
							'before'		=> '每行显示',
							'after' 		=> '篇文章',
							'type'			=> 'select',
							'class'			=> 'small-text',
							'options'		=> ['style_2'=>'2篇','style_3'=>'3篇','style_4'=>'4篇','style_5'=>'5篇'],
							'value'			=> 'style_3',
						],
					],
					'show_if'		=> ['cat_list_type', '=', 'blog']
					//'description'	=> ''
				],
				/*
				'post_orderby'		=> [
					'title'			=> '文章显示顺序',
					'type'			=> 'select',
					'options'		=> [
						'new'		=> '最新发布',
						'rand'		=> '随机显示',
						'views'		=> '最多浏览'
					],
				],
				*/
			]
		]);

		//Banner图片
		wpjam_register_term_option('banner', [
			'title'			=> 'Banner图片',
			'taxonomy'		=> 'category', 
			'list_table'	=> true,
			'action'		=> 'edit',
			'fields'		=> [
				'cat_banner'				=> [
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
							'style'			=> 'width:400px',
						],
						'banner_desc'		=> [
							'title'			=> '描述：',
							'type'			=> 'text',
							'style'			=> 'width:400px',
							'show_if'		=> ['key'=>'banner_title', 'compare'=>'!=', 'value'=>'']
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
							'data-alpha-enabled'=> true
						],
						'banner_url'		=> [
							'title'			=> '跳转链接：',
							'type'			=> 'url',
							'style'			=> 'width:400px',
							//'class'		=> 'all-options',
						],
						'banner_button'		=> [
							'title'			=> '按钮文本',
							'type'			=> 'text',
							'description'	=> '留空则点击图片进行跳转',
							'style'			=> 'width:400px',
							'show_if'		=> ['key'=>'banner_url', 'compare'=>'!=', 'value'=>'']
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
				'cat_banner_h'		=> [
					'title'			=> '图片高度',
					'type'			=> 'fields',
					'fields'		=> [
						'cat_banner_pc_h'	=> [
							'type'			=> 'number',
							'class'			=> 'small-text',
							'before'		=> 'PC端:',
							'after'			=> 'px &nbsp;&nbsp;&nbsp;&nbsp;'
						], 
						'cat_banner_mb_h'	=> [
							'type'			=> 'number',
							'class'			=> 'small-text',
							'before'		=> '手机端:',
							'after'			=> 'px'
						],
					],
					'description'	=> '留空则不限制，显示图片原始比例'
				],
			]
		]);

		
		// 获取WordPress菜单
		/*
		$menus = wp_get_nav_menus();
		// 定义一个空数组，用于存储菜单数据
		$menu_options = array(
			'' => '请选择一个菜单', // 空白的默认选项
		);
		// 将菜单添加到选项数组中
		foreach ($menus as $menu) {
			$menu_options[$menu->term_id] = $menu->name;
		}
		*/
		//多重筛选
		wpjam_register_term_option('post-filter', [
			'title'			=> '多重筛选',
			'taxonomy'		=> 'category', 
			'list_table'	=> true,
			'action'		=> 'edit',
			'fields'		=> [
				'tip'				=> [
					'title'			=> '请注意：',
					'type'			=> 'view',
					'description'	=> '子分类设置留空时，会继承父分类的设置'
				],
				'cat_filter'		=> [
					'title'			=> '多重筛选',
					'type'			=> 'checkbox',
					'description'	=> '显示文章多重筛选'
				],
				'filter_menu'		=> [
					'title'			=> '筛选菜单',
					'type'			=> 'select',
					'options'		=> $menu_options, // 输出菜单选项数组
				],
				'filter_title'		=> [
					'title'			=> '标题',
					'type'			=> 'text',
					'show_if'		=> ['key'=>'cat_filter', 'value'=>'1']
				],
				'filter_desc'		=> [
					'title'			=> '描述',
					'type'			=> 'text',
					'show_if'		=> ['key'=>'cat_filter', 'value'=>'1']
				],
				'reset_button'		=> [
					'title'			=> '重置按钮',
					'type'			=> 'select',
					'options'		=> [
						''			=> '不显示重置按钮',
						'top'		=> '显示在筛选菜单 标题栏',
						'bottom'	=> '显示在筛选菜单 底部',
					],
					'show_if'		=> ['key'=>'cat_filter', 'value'=>'1']
				],
				'reset_button_text'	=> [
					'title'			=> '按钮文本',
					'type'			=> 'text',
					'value'			=> '',
					'show_if'		=> ['key'=>'reset_button', 'compare'=>'!=', 'value'=>'']
				],
				'filter_tag'		=> [
					'title'			=> '标签 匹配规则',
					'type'			=> 'select',
					'options'		=> [
						'IN'		=> '标准模式（满足任意一个所选条件即可）',
						'AND'		=> '严格模式（所选条件必须满足）',
					],
					'show_if'		=> ['key'=>'cat_filter', 'value'=>'1']
				],
				'filter_cat'		=> [
					'title'			=> '分类 匹配规则',
					'type'			=> 'select',
					'options'		=> [
						'IN'		=> '标准模式（满足任意一个所选条件即可）',
						'AND'		=> '严格模式（所选条件必须满足）',
					],
					'description'	=> '「严格模式」会导致查询尝试匹配父分类及其子分类的所有条件，如果一级分类下面还有子分类，则不支持添加一级分类，因为父分类和子分类是独立的分类项，无法同时满足，因此无法获取查询结果。（简单点说，如果使用严格模式 且 分类中存在子分类，不要在筛选菜单中添加一级分类）',
					'show_if'		=> ['key'=>'cat_filter', 'value'=>'1']
				],
				'filter_cat_tag'	=> [
					'title'			=> '标签+分类 匹配规则',
					'type'			=> 'select',
					'options'		=> [
						'OR'		=> '标准模式（满足任意一个所选条件即可）',
						'AND'		=> '严格模式（所选条件必须满足）',
					],
					'show_if'		=> ['key'=>'cat_filter', 'value'=>'1']
				],
			]
		]);
	}
}

wpjam_register_builtin_page_load('category-layout', [
	'base'		=> ['term', 'edit-tags', 'edit'], 
	'callback'	=> 'wpjam_theme_admin_page_load'
]);

//文章列表中 产品tab设置
class WPJAM_Post_Product_Tab extends WPJAM_Post{
	use WPJAM_Items_Trait;

	public function get_items(){
		return $this->product_tabs ?: [];
	}

	public function update_items($tabs){
		return $this->meta_input('product_tabs', $tabs);
	}

	public static function get_instance($post=null, $post_type=null, $wp_error=false){
		$post	= $post ?: get_post();
		$post	= static::validate($post, $post_type);

		if(is_wp_error($post)){
			return $wp_error ? $post : null;
		}

		return call_user_func([self::class, 'instance'], $post->ID);
	}

	public static function get_fields($action_key='', $id=0){
		return  [
			'tab_title' 	=> ['title'=>'TAB标题',	'type'=>'text',		'required'=>true, 'show_admin_column'=>true],
			'tab_content'	=> ['title'=>'TAB详情',	'type'=>'editor'],
		];
	}

	public static function column_callback($post_id){
		$tabs	= get_post_meta($post_id, 'product_tabs', true) ?:[];

		return ['items'=>$tabs, 'args'=>[
			'item_type'	=> 'text',
			'sortable'	=> true,
			'text_key'	=> 'tab_title',
			'max_items'	=> 5,
		]];
	}
}
