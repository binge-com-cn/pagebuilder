<?php

/**
 * 模块页面
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2023-04-28 16:18:02
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-04-22 16:44:49
 */

get_header();

$page_value	= is_home() ? '' : get_queried_object_id();
$page_type	= is_home() ? 'key' : 'id';
$parsed		= WPJAM_Module_Page::parse($page_value, $page_type);

if(is_wp_error($parsed)){
	wp_die($parsed);
}

$module_page	= $parsed['module_page'];
$modules		= $parsed['modules'];
$page_style		= $module_page['style'] ? ' style="'.$module_page['style'].'"' : '';

// $icons		= $parsed['icons']; // 浮动图标处理

?>

<?php //页面内容 ?>
<main <?php echo $page_style; ?> id="module_page-<?php echo $module_page['id']?>">
	<div class="page-module">
	<?php
	foreach($modules as $module){
		$module_title	= $module['title'];
		$module_desc	= $module['excerpt'];
		$module_setting	= $module['setting'];
		$module_content	= $module['content'];
		$module_style	= $module['style'] ? ' style="'.$module['style'].'"' : '';
		//模块动画
		if( !empty($module_setting['animation']) ){
			$wow_style = 'wow '.$module_setting['animation'];
		}else{
			$wow_style = 'animation';
		}
		?>
		<div class="module-<?php echo $module['type']; ?>" id="module-<?php echo $module['id']?>"<?php echo $module_style;?>>
			<?php
			//echo $module_title;

			//兼容子主题
			$module_file = get_stylesheet_directory().'/template/module-page/'.$module['type'].'.php';
			if (!file_exists($module_file)) {
				// 如果文件不存在，则在父主题中查找文件
				$module_file = get_template_directory().'/template/module-page/'.$module['type'].'.php';
			}
			include $module_file;

			// 从 $module['content'] 取出数据填充，有些设置需要从 $module_setting 获取
			?>
		</div>
	<?php } ?>
	</div>
</main>

<?php
get_footer();