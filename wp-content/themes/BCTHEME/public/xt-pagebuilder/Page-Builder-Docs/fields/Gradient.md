# Gradient
渐变颜色输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（gradient）
title | `optional` | 字符串
desc | `optional` | 字符串
clip | `optional` | 布尔值（默认：false）
std | `optional` | 数组
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `object`

## 示例
```php
'addon_gradient' => array(
    'type' => 'gradient',
    'title' => __('渐变字段','your-textdomain'),
    'std' => array(
		'bgFirst' => '#ff0000',
		'bgSecond' => '#ffff00',
		'degValue' => 90,
		'direction' => 90,
		'startPosition' => 50,
		'endPosition' => 90,
		'type' => 'linear', 
		'radialValue' => 'center'
	    ),
    'selector' => '{{SELECTOR}} .example-gradient'
)
```

`clip` 参数用于前景文字颜色。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data["settings"]["addon_gradient"]["bgFirst"].'</div>';
echo '<div class="example-gradient">渐变示例</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_gradient.bgFirst}}</div>
<div>{{data.addon_gradient.bgSecond}}</div>
<div class="example-gradient">渐变示例</div>
```
