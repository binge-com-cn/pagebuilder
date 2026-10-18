# Text
文本输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（text）
title | `optional` | 字符串
desc | `optional` | 字符串
placeholder | `optional` | 字符串
std | `optional` | 字符串
selector | `optional` | 字符串（不建议使用）
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `string`

## 示例
```php
'addon_text' => array(
    'type' => 'text',
    'title' => __('文本字段','your-textdomain'),
    'placeholder' => __('请输入文本','your-textdomain'),
    'std' => '文本默认值',
)
```
支持 `selector` 参数，但一般不建议使用。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data['settings']['addon_text'].'</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_text}}</div>
```
