# Color2
带渐变输入的颜色字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（color2）
title | `optional` | 字符串
desc | `optional` | 字符串
placeholder | `optional` | 字符串
std | `optional` | 字符串（颜色值）
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
clip | `optional` | 布尔值（默认：false）
section | `optional` | 字符串

## 返回值
始终返回 `string`（HEX 颜色值）

## 示例
```php
'addon_color'=> array(
    'type' => 'color2',
    'title' => '颜色字段 2',
    'std' => array(
					'colorType' => 'color',
					'colorColor' => '#f8f8f8',
					'clip' => false
		),
    'selector' => '{{SELECTOR}} .example-color'
)
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data['settings']['addon_color']['colorType'].'</div>';
echo '<div class="example-color">颜色示例</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_color.colorType}}</div>
<div class="example-color">颜色示例</div>
```
