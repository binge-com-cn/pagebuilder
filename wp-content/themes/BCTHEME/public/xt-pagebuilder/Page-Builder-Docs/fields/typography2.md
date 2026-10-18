# Typography2
字体样式输入字段（完整设置）。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（typography2）
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
'addon_typography2' => array(
	'type' => 'typography2',
	'title' => __('排版字段 2','your-textdomain'),
	'std' => array(
		    'textDecoration' => 'underline',
		    'fontStyle' => 'italic',
		    'textTransform' => 'uppercase',
		    'fontWeight' => 700
		),
	'selector' => '{{SELECTOR}} .example-fontstyle'
)
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data['settings']['addon_typography2']['fontStyle'].'</div>';
echo '<div class="example-fontstyle">字体样式示例</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_typography2.fontStyle}}</div>
<div class="example-fontstyle">字体样式示例</div>
```
