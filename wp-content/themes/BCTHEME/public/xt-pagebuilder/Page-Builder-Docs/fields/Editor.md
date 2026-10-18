# Editor
WordPress 默认可视化编辑器字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（editor）
title | `optional` | 字符串
desc | `optional` | 字符串
std | `optional` | 字符串
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
返回 `string`（可视化编辑器输出）

## 示例
```php
'addon_editor' => array(
    'type' => 'editor',
    'title' => __('编辑器字段','your-textdomain'),
    'std' => '这是编辑器默认内容',
)
```
不支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data['settings']['addon_editor'].'</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_editor}}</div>
```
