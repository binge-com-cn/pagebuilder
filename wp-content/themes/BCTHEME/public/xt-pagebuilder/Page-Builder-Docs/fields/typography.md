# typography
字体样式输入字段（简化设置）。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（typography）
title | `optional` | 字符串
desc | `optional` | 字符串
std | `optional` | 数组
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `object`

## 示例
```php
'data_typography' => array(
	'type' => 'typography',
	'title' => __('排版字段','your-textdomain'),
	'std' => array(
		'fontFamily' 	=> '', // Google 字体名称
		'fontSize' 		=> [ 'md'=>'20px', 'sm'=>'18px', 'xs'=>'16px' ], // 0 到 400
		'lineHeight' 	=> [ 'md'=>'20px', 'sm'=>'18px', 'xs'=>'16px' ], // 0 到 400
		'fontWeight' 	=> '200', // 100 到 900
		'textTransform' => '', // 可选值：none、inherit、capitalize、lowercase、uppercase
		'fontStyle' 	=> '', // 可选值：normal、italic、oblique
		'letterSpacing' => [ 'md'=>'0px', 'sm'=>'0px', 'xs'=>'0px' ],  // 0 到 20
		),
	'selector' => '{{SELECTOR}} .example-typography'
)
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data["settings"]["data_typography"]["fontFamily"].'</div>';
echo '<div class="example-typography">排版示例</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.data_typography.fontFamily}}</div>
<div>{{data.data_typography.fontSize}}</div>
<div>{{data.data_typography.lineHeight}}</div>
<div>{{data.data_typography.fontWeight}}</div>
<div>{{data.data_typography.textTransform}}</div>
<div>{{data.data_typography.fontStyle}}</div>
<div>{{data.data_typography.letterSpacing}}</div>
<div class="example-typography">排版示例</div>
```
