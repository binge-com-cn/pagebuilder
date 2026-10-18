# Border
边框输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（border）
title | `optional` | 字符串
desc | `optional` | 字符串
std | `optional` | 数组
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `object`

## 示例
```php
'addon_border' => array(
    'type' => 'border',
    'title' => __('边框字段','your-textdomain'),
    'std' => array(
        'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ), 
        'borderStyle' =>'solid', 
        'borderColor' => '#cccccc' 
    ),
    'selector' => '{{SELECTOR}} .example-border'
)
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data["settings"]["addon_border."]["borderWidth"]["top"].'</div>';
echo '<div>'.$data["settings"]["addon_border."]["borderStyle"].'</div>';
echo '<div>'.$data["settings"]["addon_border."]["borderColor"].'</div>';
echo '<div class="example-border">边框示例</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_border.borderWidth.top}}</div>
<div>{{data.addon_border.borderStyle}}</div>
<div>{{data.addon_border.borderColor}}</div>
<div class="example-border">边框示例</div>
```
