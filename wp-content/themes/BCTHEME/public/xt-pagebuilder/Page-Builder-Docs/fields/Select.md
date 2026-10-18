# Select
下拉选择字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（select）
title | `optional` | 字符串
desc | `optional` | 字符串
placeholder | `optional` | 字符串
multiple | `optional` | 布尔值（默认：false）
responsive | `optional` | 布尔值（默认：false）
std | `optional` | 字符串（multiple: false）/ 数组（multiple: true）
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
非多选时返回 `string`  
多选时返回 `object`

## 示例

**响应式**
```php
'addon_select' => array(
    'type' => 'select',
    'title' => __('选择字段','your-textdomain'),
    'placeholder' => __('请选择','your-textdomain'),
    'responsive' => true,
    'values' => array(
        'block' => '块级',
        'inline-block' => '行内块',
        'flex' => '弹性布局',
    ),
    'std' => array(
        'md' => 'block',
        'sm' => 'flex',
        'xs' => 'flex',
    ),
),
```
不支持 `selector` 参数。

**多选**
```php
'addon_select' => array(
    'type' => 'select',
    'title' => __('选择字段','your-textdomain'),
    'placeholder' => __('请选择','your-textdomain'),
    'multiple' => true,
    'values' => array(
        'block' => '块级',
        'inline-block' => '行内块',
        'flex' => '弹性布局',
    ),
    'std' => 'block,flex',
),
```
不支持 `selector` 参数。

**非响应式 + 非多选**
```php
'addon_select' => array(
    'type' => 'select',
    'title' => __('选择字段','your-textdomain'),
    'placeholder' => __('请选择','your-textdomain'),
    'values' => array(
        'block' => '块级',
        'inline-block' => '行内块',
        'flex' => '弹性布局',
    ),
    'std' => 'block',
    'selector' => '{{SELECTOR}} .example-select{ display:{{data.addon_select}}; }'
),
```
支持 `selector` 参数。

**响应式 + 多选**
```php
'addon_select' => array(
    'type' => 'select',
    'title' => __('选择字段','your-textdomain'),
    'placeholder' => __('请选择','your-textdomain'),
    'responsive' => true,
    'multiple' => true,
    'values' => array(
        'block' => '块级',
        'flex' => '行内块',
        'flex' => '弹性布局',
    ),
    'std' => array(
        'md' => 'block,flex',
        'sm' => 'flex,flex',
        'xs' => 'flex',
    ),
),
```
不支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：

**响应式**
```php
echo '<div>'.$data['settings']['addon_select']['md'].'</div>';
echo '<div>'.$data['settings']['addon_select']['sm'].'</div>';
echo '<div>'.$data['settings']['addon_select']['xs'].'</div>';
```

**多选**
```php
echo $data['settings']['addon_select']
```

**非响应式 + 非多选**
```php
echo '<div>'.$data['settings']['addon_select'].'</div>';
```

**响应式 + 多选**
```php
foreach( $data['settings']['addon_select'] as $key => $value ){
    echo '<div>'.$value.'</div>';
}
```

### JS Template
在 `getTemplate()` 方法中：

**响应式**
```js
<div>
<# _.forEach(data.addon_select, function(value, key) { #>
    {{value}}
<# } #>
</div>
```

**多选**
```js
<div>{{data.addon_select}}</div>
```

**非响应式 + 非多选**
```js
<div>{{data.addon_select}}</div>
```

**响应式 + 多选**
```js
<div>
<# _.forEach(data.addon_select, function(value, key) { #>
    {{value}}
<# } #>
</div>
```
