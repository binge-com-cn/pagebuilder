<?php

/**
 * 授权
 */
require_once get_template_directory().'/public/license/license-manager.php';

if(PHP_VERSION < 7.4){
    if(!is_admin()&&!is_login()){
        wp_die('主题需要PHP 7.4，你的服务器 PHP 版本为：'.PHP_VERSION.'，请升级到 PHP 7.4。');
        exit;
    }
}elseif(!defined('WPJAM_BASIC_PLUGIN_FILE')){
    if(!is_admin()&&!is_login()){
        wp_die('主题基于 WPJAM Basic 插件开发，请先<a href="https://wordpress.org/plugins/wpjam-basic/">下载</a>并<a href="'.admin_url('plugins.php').'">激活</a> WPJAM Basic 插件。');
        exit;
    }
}else{

    /**
     * 加载页面构建器核心
     */
    require_once get_template_directory().'/public/xt-pagebuilder/xt-pagebuilder.php';


    /**
     * 加载页面构建器模板
     */
    require_once get_template_directory().'/xtpb-template/xtpb-template.php';

    include_once TEMPLATEPATH.'/public/hooks.php';
    include_once TEMPLATEPATH.'/public/utils.php';
    include_once TEMPLATEPATH.'/public/widget.php';

    if(is_admin()){
        include_once TEMPLATEPATH.'/admin/admin.php';
    }

}


/** --------------------------------------------------------------------------------- *
 *  去除固定链接中的子分类
 *  去掉前：www.example.com/fenlei/zifenlei/123.html
 *  去掉后：www.example.com/fenlei/123.html
 *  --------------------------------------------------------------------------------- */
/*
add_filter( 'post_link', function($permalink, $post, $leavename){
    // 检查 $post 变量是否是类型为 'post' 的对象
    if (!gettype($post) == 'post') {
        return $permalink;
    }
    // 根据文章类型进行分支处理
    switch ($post->post_type) {
        case 'post':
            // 获取与文章关联的分类
            $cats = get_the_category($post->ID);
            $subcats = array();
            // 遍历分类以查找所有层次的子分类
            foreach( $cats as $cat ) {
                $cat = get_category($cat->term_id);
                // 递归函数来查找所有层次的子分类
                $subcats = array_merge($subcats, get_all_subcategories($cat));
            }
            // 如果存在子分类，则从永久链接中移除其 slug
            if($subcats) {
                foreach($subcats as $subcat) {
                    $subcat = $subcat.'/';
                    $permalink = str_replace($subcat, "", $permalink);
                }
            }
        break;
    }
    return $permalink;
},10,3);

// 递归函数用于获取所有层次的子分类
function get_all_subcategories($category) {
    $subcategories = array();
    // 获取当前分类的子分类
    $children = get_categories( array( 'parent' => $category->term_id ) );
    foreach ($children as $child) {
        // 添加当前子分类到结果数组中
        $subcategories[] = $child->slug;
        // 递归调用以获取当前子分类的子分类
        $subcategories = array_merge($subcategories, get_all_subcategories($child));
    }
    return $subcategories;
}
*/
