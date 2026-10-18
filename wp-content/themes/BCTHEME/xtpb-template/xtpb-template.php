<?php

//加载 组件/模块 目录
function xintheme_site_module_include() {
    $module_dir = array_filter( glob( get_template_directory() . '/xtpb-template/modules/*' ), 'is_dir' );

    if ( count( $module_dir ) > 0 ) {
        foreach ( $module_dir as $key => $value ) {
            $module_dir_name = str_replace( dirname( $value ) . '/', '', $value );
            $file_name = get_template_directory() . '/xtpb-template/modules/' . $module_dir_name . '/' . $module_dir_name . '.php';
            if ( file_exists( $file_name ) ) {
                include_once $file_name;
            }
        }
    }
}
add_action( 'init', 'xintheme_site_module_include' );

//添加模块
add_filter( 'xtpb_available_modules', 'xintheme_site_modules' );
if ( ! function_exists('xintheme_site_modules')){
    function xintheme_site_modules($addons){
        $addons[] = 'Theme_Slider';
        $addons[] = 'Theme_Search';
		$addons[] = 'Theme_About';
		$addons[] = 'Theme_Form';
		$addons[] = 'Theme_Aptitude';
		$addons[] = 'Theme_Breadcrumb';
		$addons[] = 'Theme_Contact';
		$addons[] = 'Theme_Count';
		$addons[] = 'Theme_Cross_Layout';
		$addons[] = 'Theme_Download';
		$addons[] = 'Theme_Faq_2';
		$addons[] = 'Theme_Faq';
		$addons[] = 'Theme_Features';
		$addons[] = 'Theme_Features_2';
		$addons[] = 'Theme_Gallery';
		$addons[] = 'Theme_History';
		$addons[] = 'Theme_History_2';
		$addons[] = 'Theme_Html';
		$addons[] = 'Theme_ImgText_Banner';
		$addons[] = 'Theme_Links';
		$addons[] = 'Theme_Case';
		$addons[] = 'Theme_News';
		$addons[] = 'Theme_News_2';
		$addons[] = 'Theme_News_3';
		$addons[] = 'Theme_Partners';
		$addons[] = 'Theme_Partners_2';
		$addons[] = 'Theme_Post';
		$addons[] = 'Theme_Post_2';
		$addons[] = 'Theme_Pricing';
		$addons[] = 'Theme_Product_Tab';
		$addons[] = 'Theme_Reviews';
		$addons[] = 'Theme_Service';
		$addons[] = 'Theme_Shortcode';
		$addons[] = 'Theme_Tab';
		$addons[] = 'Theme_Tab_2';
		$addons[] = 'Theme_Team';
		$addons[] = 'Theme_Text';
		$addons[] = 'Theme_Video';
		return $addons;
    }
}

// 添加页面模板
add_filter( 'xtpb_add_layout', 'xintheme_site_pagebuilder_layouts' );
if ( ! function_exists( 'xintheme_site_pagebuilder_layouts' ) ) {
    function xintheme_site_pagebuilder_layouts( $layouts ) {
        $preview = get_template_directory_uri() . '/xtpb-template/modules/theme-slider/assets/img/modules-slider.jpg';

        $layouts[] = array(
            'name'      => 'Modular 页面模板',
            'category'  => array( 'Modular' ),
            'preview'   => $preview,
            'preview2x' => $preview,
            'single'    => array(
                array(
                    'name'      => '测试页面',
                    'preview'   => $preview,
                    'preview2x' => $preview,
                    'file'      => get_template_directory() . '/xtpb-template/layouts/export-5063.json',
                    'liveurl'   => 'https://modular.xintheme.cn/'
                ),
            ),
        );

        return $layouts;
    }
}

// 添加区块模板
add_filter( 'xtpb_add_block', 'xintheme_site_pagebuilder_blocks' );
if ( ! function_exists( 'xintheme_site_pagebuilder_blocks' ) ) {
    function xintheme_site_pagebuilder_blocks( $blocks ) {
        $preview = get_template_directory_uri() . '/xtpb-template/modules/theme-slider/assets/img/modules-slider.jpg';

        $blocks[] = array(
            'name'    => '测试幻灯片区块',
            'file'    => get_template_directory() . '/xtpb-template/blocks/export-6922.json',
            'banner'  => $preview,
            'section' => 'Modular',
            'liveurl' => 'https://modular.xintheme.cn/',
        );

        return $blocks;
    }
}

// 为组件加载js
//function admin_enqueue_scripts_in_editor() {
//    wp_enqueue_script('theme-animation', get_template_directory_uri() . '/assets/js/theme-animation.js', array('jquery'),false, true);
//    wp_enqueue_script('theme-main', get_template_directory_uri() . '/assets/js/theme-main.js', array('jquery'),false, true);
//}
//add_action('xtpb_enqueue_scripts_in_editor', 'admin_enqueue_scripts_in_editor');
