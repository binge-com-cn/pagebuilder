<?php

/**
 * Template: 普通文章页面
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-08-19 17:00:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-06-18 16:22:21
 */

//头部
get_header();

//产品图集
$product_gallery = get_post_meta(get_the_ID(), 'product_gallery', true);

if( !empty($product_gallery) ){
	//产品文章页面
	get_template_part( 'template/single', 'product' );
}elseif( is_attachment() ){
	//附件页面
	get_template_part( 'template/single', 'attachment' );
}else{
	//普通文章页面
	get_template_part( 'template/single', 'default' );
}

//页脚
get_footer();