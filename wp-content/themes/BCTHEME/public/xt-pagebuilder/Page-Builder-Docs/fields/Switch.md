# Switch
单开关输入字段。

参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（switch）
title | `optional` | 字符串
desc | `optional` | 字符串
std | `optional` | 数字（0 或 1）
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
返回 `0` 或 `1`

## 示例
```php
'addon_switch' => array(
    'type' => 'switch',
    'title' => __('开关字段','your-textdomain'),
    'std' => 0
)
```
支持 `selector` 参数，但通常没有必要使用。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data['settings']['addon_switch'].'</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_switch}}</div>
```
