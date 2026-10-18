<?php
// 获取根分类
$cat_root_id = get_category_root_id($cat);

// 获取分类元数据
$meta_keys = ['filter_menu', 'filter_title', 'filter_desc', 'reset_button', 'reset_button_text'];
$meta_data = [];

// 从当前分类和根分类中获取数据
foreach ($meta_keys as $key) {
	$meta_data[$key] = get_term_meta($cat, $key, true);
	if (empty($meta_data[$key])) {
		$meta_data[$key] = get_term_meta($cat_root_id, $key, true);
	}
}

// 开始输出
echo '<div class="nav-filter-box results-none reset-' . esc_attr($meta_data['reset_button']) . '">';

if (!empty($meta_data['filter_title']) || !empty($meta_data['filter_desc']) || !empty($meta_data['reset_button'])) {
	echo '<div class="nav-filter-title">';
	if (!empty($meta_data['filter_title'])) {
		echo '<h3>' . esc_html($meta_data['filter_title']) . '</h3>';
	}
	if (!empty($meta_data['filter_desc'])) {
		echo '<p>' . esc_html($meta_data['filter_desc']) . '</p>';
	}

	// 重置按钮
	if (!empty($meta_data['reset_button']) && $meta_data['reset_button'] === 'top') {
		echo '<button class="reset-filters">' . esc_html($meta_data['reset_button_text']) . '</button>';
	}

	echo '</div>';
}

// 筛选菜单
if (!empty($meta_data['filter_menu'])) {
	wp_nav_menu([
		'menu' => $meta_data['filter_menu'],
		'container'     => false,
		'menu_class'    => 'nav-filter-menu',
		'menu_id'       => 'nav-filter-menu',
		'walker'        => new Dahuzi_Filter_Walker_Menu(),
	]);
}

// 重置按钮
if (!empty($meta_data['reset_button']) && $meta_data['reset_button'] === 'bottom') {
	echo '<button class="reset-filters">' . esc_html($meta_data['reset_button_text']) . '</button>';
}

//统计筛选结果
echo '<p class="filter-results-count"></p>';

echo '</div>';

?>


