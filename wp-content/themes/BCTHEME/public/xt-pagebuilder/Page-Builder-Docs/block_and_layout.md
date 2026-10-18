# 区块与布局
*XinTheme PageBuilder* 支持第三方区块和布局。
通过插件或主题添加自定义区块、布局都很方便。

## 添加自定义区块
第一步，先用 *XinTheme PageBuilder* 创建一个区块并导出。
第二步，通过 `xtpb_add_block` 过滤器把它注册进去。

### 示例
```php
function prefix_add_block_callback( $blocks ){
	$blocks[] = array(
		'name'    => '区块名称', // 区块名称
		'file'    => plugin_dir_path( __FILE__ ).'test.json', // JSON 导出文件路径
		'banner'  => 'https://builder.xintheme.com/wp-content/uploads/2025/07/Content-1.png', // 横幅图片 URL
		'section' => '分组名称', // 所属分组名称
		'liveurl' => 'https://builder.xintheme.com/call-to-action-item-1/' // 在线预览地址
	);

	return $blocks;
}
add_filter('xtpb_add_block', 'prefix_add_block_callback');
```
*注意：* 除 `liveurl` 外，其余字段都是必填项。

## 添加自定义布局
第一步，先用 *XinTheme PageBuilder* 创建一个布局并导出。
第二步，通过 `xtpb_add_layout` 过滤器把它注册进去。

### 示例
```php
function prefix_add_layout_callback( $layouts ){
	$layouts[] = array(
		'name' => '示例模板', // 布局名称
		'category' => array( '分类名称', '创意设计' ), // 分类必须是数组
		'preview2x' => 'https://builder.xintheme.com/wp-content/uploads/2025/07/Content-1.png', // 列表高清预览图
		'preview' => 'https://builder.xintheme.com/wp-content/uploads/2025/07/Pricing-table-1.png', // 列表预览图
		'single' => array(
			array(
				'name' => '首页', // 内页布局名称
				'preview' => 'https://builder.xintheme.com/wp-content/uploads/2025/07/Content-1.png', // 内页预览图
				'preview2x' => 'https://builder.xintheme.com/wp-content/uploads/2025/07/Pricing-table-1.png', // 内页高清预览图
				'file' => plugin_dir_path( __FILE__ ).'template1.json', // 导出的 JSON 文件路径
				'liveurl' => 'https://builder.xintheme.com/agency-home/' // 在线预览地址
			),
			array(
				'name' => '关于我们',
				'preview' => 'https://builder.xintheme.com/wp-content/uploads/2025/07/Content-1.png',
				'preview2x' => 'https://builder.xintheme.com/wp-content/uploads/2025/07/Pricing-table-1.png',
				'file' => plugin_dir_path( __FILE__ ).'template2.json',
				'liveurl' => 'https://builder.xintheme.com/agency-home/'
			)
		)
	);
	return $layouts;
}
add_filter('xtpb_add_layout', 'prefix_add_layout_callback');
```
*注意：* 除 `liveurl` 外，其余字段都是必填项。
