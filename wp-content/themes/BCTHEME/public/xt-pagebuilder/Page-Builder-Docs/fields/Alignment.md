# Alignment
对齐方式字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（alignment）
title | `optional` | 字符串
desc | `optional` | 字符串
std | `optional` | 字符串
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 示例
```php
'addon_alignment' => array(
    'type' => 'alignment',
    'title' => __('对齐方式','xintheme-pagebuilder'),
    'std' => 'center',
    'selector' => '{{SELECTOR}} .example-alignment{ text-align: {{data.addon_alignment}}; }',
)
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data["settings"]["addon_alignment"].'</div>';
echo '<div class="example-alignment">对齐方式</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_alignment}}</div>
<div class="example-alignment">对齐方式</div>
```
