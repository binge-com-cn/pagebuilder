# Link
链接输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（textarea）
title | `optional` | 字符串
placeholder | `optional` | 字符串
desc | `optional` | 字符串 / 数组
std | `optional` | 数组
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `object`

## 示例
```php
'addon_link' => array (
    'type' => 'link',
    'title' => __('链接字段','your-textdomain'),
    'std' => '#', // 'std' => array( 'link' => '', 'window' => 0, 'nofolow' => 0 ),
    'placeholder' => '请输入链接'
)
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data["settings"]["addon_link"].'</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{ data.addon_link.link }}</div>
```
