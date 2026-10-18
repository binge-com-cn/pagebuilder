# Background2
带透明度字段的背景设置。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（background2）
title | `optional` | 字符串
desc | `optional` | 字符串
opacity | `optional` | 布尔值（默认：false）
std | `optional` | 字符串
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `object`

## 示例
```php
'addon_background2' => array(
    'type' => 'background2',
    'title' => __('背景字段','your-textdomain'),
    'opacity' => true,
    'selector' => '{{SELECTOR}} .example-background2',
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
            'Opacity' => 1,
            'bgHoverType' => 'color',
            'bgHoverColor' => '',
            'bgHoverImage' => array(),
            'bgHoverimgPosition' => '',
            'bgHoverimgAttachment' => '',
            'bgimgHoverRepeat' => '',
            'bgimgHoverSize' => '',
            'bgHoverDefaultColor' => '',
            'bgHoverGradient' => array(),
            'hoverOpacity' => 1,
        )
    )
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data["settings"]["addon_background2"]["bgColor"].'</div>';
echo '<div class="example-background2">背景示例 2</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_background2.bgColor}}</div>
<div class="example-background2">背景示例 2</div>
```
