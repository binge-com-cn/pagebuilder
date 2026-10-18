# Icon
图标选择字段（Remix Icon 与 XTPB 字体图标）。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（icon）
title | `optional` | 字符串
desc | `optional` | 字符串
std | `optional` | 字符串
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `string`

## 示例
```php
'addon_icon' => array(
    'type' => 'icon',
    'title' => __('图标字段','your-textdomain'),
    'std' => 'ri-home-4-line',
)
```
不支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div><i class="'.$data["settings"]["addon_icon"].'"></i></div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div><i class="{{data.addon_icon}}"></i></div>

```
