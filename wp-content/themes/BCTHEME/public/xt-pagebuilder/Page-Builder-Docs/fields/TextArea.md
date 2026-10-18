# TextArea
多行文本输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（textarea）
title | `optional` | 字符串
desc | `optional` | 字符串
placeholder | `optional` | 字符串
std | `optional` | 字符串
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `string`

## 示例
```php
'addon_textarea' => array(
    'type' => 'textarea',
    'title' => __('多行文本字段','your-textdomain'),
    'placeholder' => __('请输入多行文本','your-textdomain'),
    'std' => '这是默认多行文本'
)
```
`Textarea` 场景下不支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data['settings']['addon_textarea'].'</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_textarea}}</div>
```
