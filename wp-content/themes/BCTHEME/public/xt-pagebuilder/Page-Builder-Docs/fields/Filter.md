# Filter
CSS Filter 输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（filter）
title | `optional` | 字符串
desc | `optional` | 字符串
std | `optional` | 对象
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `object`

## 示例
```php
'addon_filter'=> array(
    'type' => 'filter',
	'title' => '滤镜字段',
    'std' => array(
		'brightness' => '100%',
		'contrast' => '100%',
		'grayscale' => '100%',
		'invert' => '100%',
		'huerotate' => '100deg',
		'saturate' => '100%',
		'sepia' => '100%',
	),
    'selector' => '{{SELECTOR}} .example-filter'
)
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data['settings']['addon_filter']['brightness'].'</div>';
echo '<div class="example-filter">滤镜示例</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_filter.brightness}}</div>
<div class="example-filter">滤镜示例</div>
```
