# Checkbox
多选框输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（checkbox）
title | `optional` | 字符串
desc | `optional` | 字符串
values | `optional` | 数组
multiple | `optional` | 布尔值（默认：false）
std | `optional` | 字符串（multiple: false）或数组（multiple: true）
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
单选时返回 `string`  
多选时返回 `array`

## 示例
**单选**
```php
'addon_checkbox' => array (
    'type' => 'checkbox',
    'title' => __('复选框字段','your-textdomain'),
    'values' => array(
		'10px' => '小高度',
		'20px' => '中等高度',
		'40px' => '大高度'
	    ),
    'std' => '20px',
    'selector'	=> '{{SELECTOR}} .example-checkbox{ margin-left:{{data.addon_checkbox}}; }'
)
```
支持 `selector` 参数。

**多选**
```php
'addon_checkbox' => array (
    'type' => 'checkbox',
    'title' => __('复选框标题','your-textdomain'),
    'values' => array(
                    'type1' => '类型 1',
                    'type2' => '类型 2',
                    'type3' => '类型 3'
                ),
    'std' => ['type1','type2'],
    'multiple' => true
)
```

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>'.$data['settings']['addon_checkbox'].'</div>';
<div class="example-checkbox">复选框示例</div>
```

**多选**
```php
echo '<div>';
    foreach($data['settings']['addon_checkbox'] as $value) {
        echo $value;
    }
echo '</div>';
```

### JS Template
在 `getTemplate()` 方法中：

**单选**
```js
<div>{{data.addon_checkbox}}</div>
<div class="example-checkbox">复选框示例</div>
```

**多选**
```js
<div>
<# _.forEach(data.addon_checkbox, function(value, key) { #>
    {{value}}
<# } #>
</div>
```
