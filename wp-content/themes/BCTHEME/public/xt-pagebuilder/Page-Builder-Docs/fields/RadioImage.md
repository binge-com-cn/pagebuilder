# RadioImage
图片单选字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（radioimage）
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
'addon_radioimage' => array(
    'type' => 'radioimage',
    'title' => __('图片单选字段','your-textdomain'),
    'values' => array(
            'one' => plugin_dir_url( __FILE__ ).'icon1.png',
            'two' => plugin_dir_url( __FILE__ ).'icon2.png',
            'three' => plugin_dir_url( __FILE__ ).'icon3.png',
	),
    'std' => 'two'
)
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data['settings']['addon_radioimage'].'</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_radioimage}}</div>
```
