# Background
背景字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（background）
title | `optional` | 字符串
desc | `optional` | 字符串
video | `optional` | 布尔值（仅视频背景时返回对应数据）
std | `optional` | 字符串
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `object`

## 示例
```php
'addon_background' => array(
    'type' => 'background',
    'title' => __('背景字段','your-textdomain'),
    'selector' => '{{SELECTOR}} .example-background',
    'std' => array(
	'bgType' => 'color',
	'bgColor' => '',
	'bgImage' => array(),
	'bgimgPosition' => '',
	'bgimgAttachment' => '',
	'bgimgRepeat' => '',
	'bgimgSize' => '',
	'bgDefaultColor' => '',
	'bgGradient' => array(),
	'videoType' => 'local',
	'bgVideo' => array(),
	'bgExternalVideo' => '',
	'bgVideoFallback' =>array(),
	'bgHoverType' => 'color',
	'bgHoverColor' => '',
	'bgHoverImage' => array(),
	'bgHoverimgPosition' => '',
	'bgHoverimgAttachment' => '',
	'bgimgHoverRepeat' => '',
	'bgimgHoverSize' => '',
	'bgHoverDefaultColor' => '',
	'bgHoverGradient' => array(),
    )
)
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data["settings"]["addon_background"]["bgColor"].'</div>';
echo '<div class="example-background">背景示例</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_background.bgColor}}</div>
<div class="example-background">背景示例</div>
```
