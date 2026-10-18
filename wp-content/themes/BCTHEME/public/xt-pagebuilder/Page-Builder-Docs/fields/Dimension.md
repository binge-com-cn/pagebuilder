# Dimension
尺寸输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（dimension）
title | `optional` | 字符串
desc | `optional` | 字符串
responsive | `optional` | 布尔值（默认：false）
std | `optional` | 数组
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `object`

## 示例
**响应式**
```php
'addon_dimension' => array(
	'type' => 'dimension',
	'title'	=> __('尺寸字段','your-textdomain'),
	'std' => array(
		'md' => array( 'top' => '10px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
		'sm' => array( 'top' => '9px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
		'xs' => array( 'top' => '7px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
		),
	'unit' => array( 'px','em','%' ),
	'responsive' => true,
	'selector' => '{{SELECTOR}} .example-dimension{ margin: {{data.addon_dimension}}; }'
)
```

**非响应式**
```php
'addon_dimension' => array(
	'type' => 'dimension',
	'title'	=> __('尺寸字段','your-textdomain'),
	'std' => array(
                'top' => '10px', 
                'right' => '0px', 
                'bottom' => '0px', 
                'left' => '0px'
            ),
	'unit' => array( 'px','em','%' ),
	'selector' => '{{SELECTOR}} .example-dimension{ margin: {{data.addon_dimension}}; }'
)
```

**非响应式且不带单位**
```php
'addon_dimension' => array(
	'type' => 'dimension',
	'title'	=> __('尺寸字段','your-textdomain'),
	'std' => array( 
                'top' => '10',
                'right' => '0',
                'bottom' => '0',
                'left' => '0'
            ),
)
```

## 控制读取
### PHP
在 `rander()` 方法中

**响应式**
```php
foreach( $value as $key => $val ){
    echo '<div>'.$val["top"].'</div>';
    echo '<div>'.$val["right"].'</div>';
    echo '<div>'.$val["bottom"].'</div>';
    echo '<div>'.$val["left"].'</div>';
}
echo '<div class="example-dimension">尺寸示例</div>';
```

**非响应式**
```php
echo '<div>'.$data["settings"]["addon_dimension"]["top"].'</div>';
echo '<div>'.$data["settings"]["addon_dimension"]["right"].'</div>';
echo '<div>'.$data["settings"]["addon_dimension"]["bottom"].'</div>';
echo '<div>'.$data["settings"]["addon_dimension"]["left"].'</div>';
echo '<div class="example-dimension">尺寸示例</div>';
```

**非响应式且不带单位**
```php
echo '<div>'.$data["settings"]["addon_dimension"]["top"].'</div>';
echo '<div>'.$data["settings"]["addon_dimension"]["right"].'</div>';
echo '<div>'.$data["settings"]["addon_dimension"]["bottom"].'</div>';
echo '<div>'.$data["settings"]["addon_dimension"]["left"].'</div>';
```

### JS Template
在 `getTemplate()` 方法中：

**响应式**
```js
<div>
<# _.forEach(data.addon_dimension, function(value, key) { #>
    {{value.top}}
    {{value.right}}
    {{value.bottom}}
    {{value.left}}
<# } #>
</div>
<div class="example-dimension">尺寸示例</div>
```

**非响应式**
```js
<div>{{data.addon_dimension}}</div>
<div class="example-dimension">尺寸示例</div>
```

**非响应式且不带单位**
```js
<div>{{data.addon_dimension}}</div>
```
