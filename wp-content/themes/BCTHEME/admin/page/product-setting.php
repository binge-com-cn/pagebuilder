<?php

add_filter('wpjam_theme_setting', function(){

	$sections	= [ 

		'product-setting'	=> [
			'title'			=> '产品页设置', 
			'fields'		=> [
				'product_banner'	=> [
					'title'			=> '产品页Banner',
					'type'			=> 'checkbox',
					'description'	=> '产品详情页面，顶部显示当前所属分类的Banner内容。'
				],
				'product_cat' 		=> [
					'title'			=> '产品分类',
					'type'			=> 'checkbox',
					'description'	=> '显示产品分类目录（在标题上方）',
				],
			]
		],

		'product-feature'	=> [
			'title'			=> '特色标签', 
			'fields'		=> [
  				'product_feature_style'	=> [
					'title'				=> '特色标签-样式', 
					'type'				=> 'radio',
					'options'			=> ['one'=>'样式-1','two'=>'样式-2','three'=>'样式-3'],
				],
				'product_feature'		=> [
					'title'				=> '特色标签-内容',
					'type'				=> 'mu-fields',//mu-fields //fieldset
					'group'				=> true,
					'max_items'			=> 6,
					'fields'			=> [
						'feature_img' 		=> [
							'title'			=> '图标',
							'type'			=> 'img',
							'item_type'		=> 'url',
							'size'			=> '50×50',
							'button_text'	=> '上传图标',
							'description'	=> '',
						],
						'feature_text' 		=> [
							'title'			=> '标题',
							'type'			=> 'text',
						],
					]
				],
			]
		],

		'product-tel'	=> [
			'title'		=> '联系电话', 
			'fields'	=> [
				'product_tel'				=> [
					'title'					=> '联系电话',
					'type'					=> 'fieldset',//mu-fields //fieldset
					//'group'				=> true,
					'fields'				=> [
						'product_teltitle'	=> [
							'title'			=> '小标题',
							'type'			=> 'text',
						],
						'product_telnum'	=> [
							'title'			=> '电话号码',
							'type'			=> 'text',
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