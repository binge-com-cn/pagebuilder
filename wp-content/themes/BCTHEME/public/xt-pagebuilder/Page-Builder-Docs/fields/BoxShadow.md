# BoxShadow
盒子阴影输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（boxshadow）
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
'widget_boxshadow' => array(
	'type' => 'boxshadow',
	'title' => __('阴影字段','your-textdomain'),
	'std' => array(
		    'shadowValue'=> array( 'top' => '120px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ), 
		    'shadowColor' 	=> '#ffffff' 
		),
	'selector' => '{{SELECTOR}} .example-boxshadow'
)
```
支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data["settings"]["widget_boxshadow"]["shadowValue"]["top"].'</div>';
echo '<div>'.$data["settings"]["widget_boxshadow"]["shadowColor"].'</div>';
echo '<div class="example-boxshadow">阴影示例</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.widget_border.shadowValue.top}}</div>
<div>{{data.widget_border.shadowColor}}</div>
<div class="example-boxshadow">阴影示例</div>
```
