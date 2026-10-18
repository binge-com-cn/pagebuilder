<?php

//为WordPress分类页面添加规范 URL 标签，避免重复内容对 SEO 的负面影响
function add_category_canonical_tag() {
    if (is_category() || is_tag() || is_tax()) {
        $canonical_url = get_term_link(get_queried_object());
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '"/>' . "\n";
    }
}
add_action('wp_head', 'add_category_canonical_tag');

//添加网站菜单
register_nav_menus([
	'primary'		=>'主菜单',
	'top'			=>'顶部菜单',
]);

//下载
wpjam_register_post_type('download', [
	'label'				=> __( '下载', 'modular_theme' ),
	'menu_icon'			=> 'dashicons-download',
	'menu_position'		=> 4,
	'supports'			=> ['title', 'excerpt', 'thumbnail'],
	'rewrite'			=> false,
	'public'			=> true,  // 设置为 false 禁用详情页链接
	'has_archive'		=> 'downloads',
	'permastruct'		=> 'download/%post_id%/',
]);

wpjam_register_taxonomy('download_type', [
	'label'				=> '下载分类',
	'public'			=> true,
	'hierarchical'		=> true,
	'show_ui'			=> true,
	'show_in_nav_menus'	=> true,
	'rewrite'			=> false,
	'query_var'			=> true,
	//'sortable'		=> true,
	'levels'			=> 1,
	'object_type'		=> ['download'],
	'supports'			=> ['name', 'slug'],
	'permastruct'		=> '/%download_type%/',

]);

//常见问题
wpjam_register_post_type('faq', [
	'label'				=> 'FAQ',
	'menu_icon'			=> 'dashicons-editor-help',
	'menu_position'		=> 4,
	'supports'			=> ['title', 'editor'],
	'rewrite'			=> false,
	'public'			=> true,  // 设置为 false 禁用详情页链接
	'has_archive'		=> 'faqs',
	//'taxonomies'      => array('faq_type'), // 指定支持的分类法
	'permastruct'		=> 'faq/%post_id%/',
]);

wpjam_register_taxonomy('faq_type', [
	'label'				=> 'FAQ分类',
	'public'			=> true,
	'hierarchical'		=> true,
	'show_ui'			=> true,
	'show_in_nav_menus'	=> true,
	'rewrite'			=> false,
	'query_var'			=> true,
	//'sortable'		=> true,
	'levels'			=> 1,
	'object_type'		=> ['faq'],
	'supports'			=> ['name', 'slug'],
	'permastruct'		=> '/%faq_type%/',

]);

//团队
wpjam_register_post_type('team', [
	'labels' => array(
		'name'                => '团队',
		'singular_name'       => '团队成员',
		'add_new'             => '添加成员',
		'add_new_item'        => '添加新成员',
		'edit_item'           => '编辑成员',
		'new_item'            => '新成员',
		'view_item'           => '查看团队成员',
		'search_items'        => '搜索团队成员',
		'not_found'           => '未找到团队成员',
		'not_found_in_trash'  => '回收站中没有找到成员',
		'all_items'           => '所有成员',
	),
	'menu_icon'			=> 'dashicons-groups',
	'menu_position'		=> 4,
	'supports'			=> ['title', 'editor', 'thumbnail'], //excerpt
	'rewrite'			=> false,
	'public'			=> true,  // 设置为 false 禁用详情页链接
	'has_archive'		=> 'teams',
	//'taxonomies'      => array('faq_type'), // 指定支持的分类法
	'permastruct'		=> 'team/%postname%/',
]);

//禁用古腾堡小工具
add_filter('gutenberg_use_widgets_block_editor', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');

//移除默认相册样式
add_filter( 'use_default_gallery_style', '__return_false' );

add_action('after_setup_theme', function(){
	load_theme_textdomain( 'modular_theme', get_template_directory() . '/languages' ); //加载语言包
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('style', ['toc', 'comment-digg']);
	add_theme_support('script', ['wpjam-ajax', 'toc', 'comment-action', 'form-submit']);
});

//兼容wordpress6.7版本 加载语言包
/* 6.7.1已修复
add_action('init', function() {
	global $l10n, $wp_textdomain_registry;
	$domain = 'modular_theme';
	$locale = get_locale();
	$wp_textdomain_registry->set($domain, $locale, get_template_directory() . '/languages');
	if ( isset( $l10n[ $domain ] )) {
		unset( $l10n[ $domain ] );
	}
	load_theme_textdomain($domain, get_template_directory() . '/languages');
});
*/


//载入JS、CSS
add_action('wp_enqueue_scripts', function () {
	//版本号
	$main_theme		= wp_get_theme(get_template());
	$theme_version	= $main_theme->get('Version');

	//Gutenberg Default Style
	//wp_enqueue_style('wp-block-default', get_template_directory_uri().'/static/css/wp-block-default.css');

	//相关插件css
	wp_enqueue_style('theme-plugins', get_template_directory_uri().'/static/css/theme-plugins-'.$theme_version.'.css');
	//Themes Style
	wp_enqueue_style('theme-main', get_template_directory_uri().'/static/css/theme-main-'.$theme_version.'.css');

	wp_add_inline_style('theme-main', ':root {'."\n".implode("\n", wpjam_map([
		'nav_color_bg'		=> '#c00d23',
		'nav_color_current'	=> '#000000',
		'nav_color_text'	=> '#ffffff',
		'nav_color_text2'	=> '#ffffff',

		'bar_color_bg'		=> '#000000',
		'bar_color_current'	=> '#c00d23',
		'bar_color_text'	=> '#ffffff',
		'bar_color_text2'	=> '#ffffff',
		'bar_color_line'	=> '#292929',

		'mobile_nav_bg'		=> '#ffffff',
		'mobile_nav_text'	=> '#000000'
	], fn($d, $k)=> "\t".'--'.$k.':'.(wpjam_theme_get_setting($k) ?: $d).';'))."\n".'}');

	//相关插件js
	wp_enqueue_script('theme-plugins',	get_template_directory_uri() . '/static/js/theme-plugins-'.$theme_version.'.js', ['jquery'], '', true);
	//Themes Script
	wp_enqueue_script('theme-main',	get_template_directory_uri() . '/static/js/theme-main-'.$theme_version.'.js', ['jquery'], '', true);

	//二维码
	if( is_singular() ){
		wp_enqueue_script('theme-qrcode',	get_template_directory_uri() . '/static/js/jquery.qrcode.min.js', ['jquery'], '', false);
	}

	// 文章筛选
	if (is_category()) {
		$object_id = get_queried_object_id();
		$root_cat_id = get_category_root_id($object_id);

		// 尝试获取分类的筛选条件
		$cat_filter = get_term_meta($object_id, 'cat_filter', true);

		// 如果当前分类没有筛选条件，则尝试从根分类中获取
		if (empty($cat_filter) && !empty($root_cat_id)) {
			$cat_filter = get_term_meta($root_cat_id, 'cat_filter', true);
		}

		// 如果有筛选条件，则加载筛选脚本
		if (!empty($cat_filter)) {
			wp_enqueue_script('theme-filter', get_template_directory_uri() . '/static/js/post-filter.js', ['jquery'], '', true);
		}
	}


	//AJAX
	//wp_add_inline_script('theme-main', 'var ajaxurl	= "'.admin_url('admin-ajax.php').'";', 'before');

});

//移除jquery-migrate
function remove_jquery_migrate( $scripts ) {
	if (is_admin()) {
		return;
	}
	if ( isset( $scripts->registered['jquery'] ) ) {
		$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
	}
}
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );

//AJAX
function add_inline_script_ajax_dahuzi() {
	if (is_category()) {
		$category_id = get_queried_object_id(); // 获取当前分类的ID
		$inline_script = 'var ajaxurl = "' . admin_url('admin-ajax.php') . '";';
		$inline_script .= 'var categoryId = ' . $category_id . ';';
		wp_add_inline_script('theme-main', $inline_script, 'before');
	} else {
		// 如果不是分类页面，仍然添加 ajaxurl
		wp_add_inline_script('theme-main', 'var ajaxurl = "' . admin_url('admin-ajax.php') . '";', 'before');
	}
}
add_action('wp_enqueue_scripts', 'add_inline_script_ajax_dahuzi');

//页面支持摘要
add_action('init', fn()=>add_post_type_support('page', array('excerpt')));

// 如果有二级菜单 增加下拉图标
add_filter('walker_nav_menu_start_el', function($output, $item, $depth){
	if(isset($item->classes) && is_array($item->classes) && in_array('menu-item-has-children', $item->classes)) {
		$output .= '<i class="icon-drop-down ri-arrow-down-s-line"></i>';
	}

	return $output;
}, 10, 3);

//后台添加css
add_action('admin_head', function(){
	echo "<style>
	.wpjam-thumbnail-wrap img{min-height:50px;object-fit:cover}
	#wp-product_excerpt-media-buttons{display:none}
	#product_gallery div.wpjam-img img{max-width:100px!important;max-height:none!important}
	#product_feature div.wpjam-img img{max-width:50px!important;max-height:none!important}
	#product_feature .sub-field-label{min-width:auto}
	#product_feature div.mu-item.field-group .sub-field{margin-right:20px}
	#type_options{padding:4px;overflow:auto;max-height:400px}
	#type_options::-webkit-scrollbar{-webkit-appearance:none}
	#type_options::-webkit-scrollbar:vertical{width:6px}
	#type_options::-webkit-scrollbar:horizontal{height:6px}
	#type_options::-webkit-scrollbar-thumb{border-radius:8px;background-color:rgba(0,0,0,.5)}
	#tr_gallery_item #gallery_item .wpjam-img img{max-width:200px !important}

	/*分享设置*/
	#share_style_options label,#share_variant_options label{width:200px}
	#share_style_options label img,#share_variant_options label img{width:100%;max-width:100%!important;opacity:.4}
	#share_style_options label.checked img,#share_variant_options label.checked img{opacity:1}

	/*页面Banner图片滤镜设置*/
	.post-type-page #tr_page_banner>td{display:flex;align-items:center;flex-wrap:wrap}
	.post-type-page #tr_page_banner .wp-color-result.button{margin-bottom:0}
	.post-type-page #tr_page_banner label[for=banner_text_color]{margin-bottom:10px}
	.post-type-page #tr_page_banner label[for=banner_text_color]+.wp-picker-container{width:calc(100% - 70px);margin-bottom:10px}

	/*页面管理 模块缩略图预览*/
	.module-preview{background-size:100% !important}

	/*页面管理 客户评价模块 头像*/
	#item_reviews .wpjam-img img {width:60px;height:60px;object-fit:cover;border-radius:50px}

	/*头部样式设置*/
	#header_style_options .image-radio input~img{max-width:500px!important;object-fit:contain;object-position:top}
	#header_style_options{gap:20px;align-items:stretch}
	#header_style_options label{text-align:center;font-size:12px;line-height:1.4;background:#dcdcde;color:#000;font-weight:500;padding-bottom:5px;width:min-content;position:relative;cursor:pointer;border:1px solid #ddd}
	#header_style_options label.checked{background:#e71c1c;color:#fff}
	#header_style_options .image-radio input+img{box-shadow:none;background:#fff}

	/*列表样式*/
	#cat_list_type_options .image-radio input~img{max-width:200px!important;height:130px;object-fit:contain;object-position:top}
	#cat_list_type_options,
	#list_one_mobile_options{gap:10px 10px;align-items:stretch}
	#cat_list_type_options label,
	#list_one_mobile_options label,
	#list_three_mobile_options label,
	#list_two_mobile_options label,
	#list_blog_mobile_options label{text-align:center;font-size:14px;line-height:1.4;background:#666;color:#fff;padding-bottom:5px;width:min-content;position:relative;cursor:pointer}
	#cat_list_type_options label.checked,
	#list_one_mobile_options label.checked,
	#list_three_mobile_options label.checked,
	#list_two_mobile_options label.checked,
	#list_blog_mobile_options label.checked{background:#e71c1c}
	#cat_list_type_options .image-radio input+img,
	#list_one_mobile_options .image-radio input+img,
	#list_three_mobile_options .image-radio input+img,
	#list_two_mobile_options .image-radio input+img
	#list_bolg_mobile_options .image-radio input+img{box-shadow:none;background:#fff;width:170px}

	/*后台页面模块 选择模块界面美化*/
	#list_table_action_form #type_options{gap:10px 10px;align-items:stretch}
	#list_table_action_form #type_options label{text-align:center;font-size:14px;line-height:1.4;background:#666;color:#fff;padding-bottom:5px}
	#list_table_action_form #type_options label.checked{background:#e71c1c}
	#list_table_action_form .image-radio input:checked+img{box-shadow:none}

	/*经典编辑器 修复SEO设置 描述框错位*/
	#seo_description{width:100%}

	/*合伙伙伴-2 模块设置*/
	#item_partners div.wpjam-img img{max-width:100px!important}
	#item_partners div.field-group{align-items:center}

	/*登录页面设置*/
	#tr_login_logo div.wpjam-img img{width:200px!important;height:auto!important}

	</style>";
});


/** --------------------------------------------------------------------------------- *
 *  给图片添加跳转链接字段 （本主题仅用于相册）
 *  --------------------------------------------------------------------------------- */
// 添加自定义字段到媒体编辑页面
function add_gallery_link_url_field($form_fields, $post) {
    $form_fields['gallery_link_url'] = array(
        'label' => '图片跳转链接',
        'input' => 'text',
        'value' => get_post_meta($post->ID, 'gallery_link_url', true),
    );
    return $form_fields;
}

add_filter('attachment_fields_to_edit', 'add_gallery_link_url_field', 10, 2);

// 保存自定义字段值
function save_gallery_link_url_field($post, $attachment) {
    if (isset($attachment['gallery_link_url'])) {
        update_post_meta($post['ID'], 'gallery_link_url', esc_url($attachment['gallery_link_url']));
    }
    return $post;
}

add_filter('attachment_fields_to_save', 'save_gallery_link_url_field', 10, 2);

//替换相册中的图片链接
function custom_gallery_link($content, $id) {
	//图片链接
	$image_url = wp_get_attachment_url($id);
    // 获取图片的 gallery_link_url
    $gallery_link_url = get_post_meta($id, 'gallery_link_url', true);

    // 如果有 gallery_link_url，则替换链接
    if ($gallery_link_url) {
        $content = preg_replace('/<a(.*?)href=[\'"]?([^\'">]+)[\'"]?(.*?)>/i', '<a$1href="' . esc_url($gallery_link_url) . '" target="_blank"$3>', $content);
    }else{
    	$content = preg_replace('/<a(.*?)href=[\'"]?([^\'">]+)[\'"]?(.*?)>/i', '<a$1href="' . esc_url($image_url) . '" data-fancybox=""$3>', $content);
    }

    return $content;
}
add_filter('wp_get_attachment_link', 'custom_gallery_link', 10, 6);

/** --------------------------------------------------------------------------------- *
 *  自动添加暗箱标签属性
 *  --------------------------------------------------------------------------------- */
add_filter('the_content', function ($content){
    global $post;
    $pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|webp)('|\")(.*?)>(.*?)<\/a>/i";
    $replacement = '<a$1href=$2$3.$4$5 data-fancybox="post-images"$6>$7</a>';
    $content = preg_replace($pattern, $replacement, $content);
    
    return $content;
});

/** --------------------------------------------------------------------------------- *
 *  文章外链 自动添加nofollow标签
 *  --------------------------------------------------------------------------------- */
add_filter('the_content', function ($content) {
	if( !wpjam_theme_get_setting('single_link_nofollow') ){
		return $content;
	}

	// 正则表达式匹配所有的链接标签
	$regexp = '/<a\s[^>]*href=(["\'])(.*?)\1[^>]*>/i';

	// 获取当前网站的 URL，用于判断内部链接，移除协议部分
	$srcUrl = preg_replace('/https?:\/\//', '', get_option('siteurl'));

	// 匹配文章内容中的所有链接
	if (preg_match_all($regexp, $content, $matches, PREG_SET_ORDER)) {
		foreach ($matches as $match) {
			$tag = $match[0];   // 完整的 <a> 标签
			$url = $match[2];   // href 中的 URL，可能包含协议

			// 去除协议部分进行域名比较
			$urlDomain = preg_replace('/https?:\/\//', '', $url);

			// 如果不是内部链接，添加属性
			if (stripos($urlDomain, $srcUrl) === false) {
				$noFollow = '';

				// 添加 target="_blank" 属性
				if (!preg_match('/target\s*=\s*"_blank"/i', $tag)) {
					$noFollow .= ' target="_blank"';
				}

				// 添加 rel="nofollow" 属性
				if (!preg_match('/rel\s*=\s*"(nofollow|dofollow)"/i', $tag)) {
					$noFollow .= ' rel="nofollow"';
				}

				// 将 nofollow 属性添加到 <a> 标签中
				$newTag = rtrim($tag, '>') . $noFollow . '>';

				// 替换内容中的原始 <a> 标签
				$content = str_replace($tag, $newTag, $content);
			}
		}
	}

	// 处理 WordPress 特殊标签的转义
	$content = str_replace(']]>', ']]&gt;', $content);

	return $content;
});

/** --------------------------------------------------------------------------------- *
 *  移除标题分隔符中的空格
 *  --------------------------------------------------------------------------------- */
function remove_title_delimiter_spaces_dahuzi($title) {

	//判断是否移除
	if( !wpjam_theme_get_setting('remove_title_delimiter_spaces') ){
		return $title;
	}

    // 使用正则表达式去除分隔符前后的空格
    return str_replace(' - ', '-', $title);
}
add_filter('document_title', 'remove_title_delimiter_spaces_dahuzi');

/** --------------------------------------------------------------------------------- *
 *  超级菜单
 *  --------------------------------------------------------------------------------- */
include_once TEMPLATEPATH.'/public/menu.php';

/** --------------------------------------------------------------------------------- *
 *  文章筛选
 *  --------------------------------------------------------------------------------- */
include_once TEMPLATEPATH.'/public/post-filter.php';

/** --------------------------------------------------------------------------------- *
 *  移除头像功能
 *  --------------------------------------------------------------------------------- */
/*
//禁止加载头像
add_filter('get_avatar', '__return_false');
//禁止获取头像 URL
add_filter('get_avatar_url', '__return_false');
//禁用后台设置中的头像选项
add_filter('pre_option_show_avatars', '__return_zero');

//移除options-discussion.php页面菜单 后台-设置-讨论
function remove_discussion_settings_page() {
    remove_submenu_page('options-general.php', 'options-discussion.php');
}
add_action('admin_menu', 'remove_discussion_settings_page', 100);

//完全禁用options-discussion.php页面访问
function disable_discussion_settings_page() {
	$current_screen = get_current_screen();
	if ($current_screen && $current_screen->id === 'options-discussion') {
		wp_die('此页面已被禁用');
	}
}
add_action('current_screen', 'disable_discussion_settings_page');
*/

/** --------------------------------------------------------------------------------- *
 *  移除站点健康页面
 *  --------------------------------------------------------------------------------- */
function remove_site_health_page() {
	remove_submenu_page('tools.php', 'site-health.php');
}
add_action('admin_menu', 'remove_site_health_page', 100);

/** --------------------------------------------------------------------------------- *
 *  修改WP默认站点地图
 *  --------------------------------------------------------------------------------- */

// 禁用用户站点地图
function dahuzi_disable_sitemap_users($provider, $name) {
	return ($name == 'users') ? false : $provider;
}
add_filter('wp_sitemaps_add_provider', 'dahuzi_disable_sitemap_users', 10, 2);

// 禁止访问用户站点地图 URL
function dahuzi_block_user_sitemap_access() {
	if ( strpos( $_SERVER['REQUEST_URI'], 'wp-sitemap-users' ) !== false ) {
		// 告诉 WordPress 当前是 404 页面
		global $wp_query;
		$wp_query->set_404();
		$wp_query->is_404 = true;

		// 加载 404 模板
		include(get_query_template('404'));
		exit; // 停止进一步执行
    }
}
add_action( 'template_redirect', 'dahuzi_block_user_sitemap_access' );

/** --------------------------------------------------------------------------------- *
 *  移除用户资料中的  姓氏、名字、网址
 *  --------------------------------------------------------------------------------- */
function remove_default_user_fields($user) {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.user-first-name-wrap, .user-last-name-wrap, .user-url-wrap').forEach(function(el) {
                el.remove();
            });
        });
    </script>
    <?php
}
add_action('show_user_profile', 'remove_default_user_fields');
add_action('edit_user_profile', 'remove_default_user_fields');

/** --------------------------------------------------------------------------------- *
 *  给 wordpress_test_cookie 增加HttpOnly属性
 *  --------------------------------------------------------------------------------- */
add_action('login_init', function() {
    if (!headers_sent()) {
        setcookie(
            'wordpress_test_cookie', // Cookie 名称
            'WP Cookie check',       // Cookie 值
            0,                       // 过期时间
            COOKIEPATH,              // 路径
            COOKIE_DOMAIN,           // 域
            is_ssl(),                // 是否启用 Secure 属性（基于 HTTPS）
            true                     // HttpOnly 属性，防止 JavaScript 访问
        );
    }
});

