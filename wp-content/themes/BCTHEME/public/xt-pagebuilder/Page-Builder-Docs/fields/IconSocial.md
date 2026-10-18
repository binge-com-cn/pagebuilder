# IconSocial
社交图标输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（iconsocial）
title | `optional` | 字符串
desc | `optional` | 字符串
std | `optional` | 字符串
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `string`

## 示例
```php
'addon_iconsocial' => array(
    'type' => 'iconsocial',
    'title' => __('社交图标字段','your-textdomain'),
    'std' => 'ri-facebook-fill',
)
```
不支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div><i class="'.$data['settings']['addon_iconsocial'].'"></i></div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div><i class="{{data.addon_iconsocial}}"></i></div>
```
