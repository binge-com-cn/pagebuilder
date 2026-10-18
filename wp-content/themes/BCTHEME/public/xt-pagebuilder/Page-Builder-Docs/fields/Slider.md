# Slider
可拖拽数字输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（slider）
title | `optional` | 字符串
desc | `optional` | 字符串
unit | `optional` | 数组
range | `optional` | 数组
responsive | `optional` | 布尔值（默认：false）
range | `optional` | 数组（见示例）
std | `optional` | 字符串
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
`responsive` 为 false 时返回 `Number`  
`responsive` 为 true 时返回 `Array`

## 示例
**响应式**
```php
'addon_slider' => array(
    'type' => 'slider',
    'title' => __('滑块字段','your-textdomain'),
    'responsive' => true,
    'range' => array(
                'em' => array(
                  'min' => 0,
                  'max' => 8,
                  'step' => 1,
                ),
                'px' => array(
                  'min' => 1,
                  'max' => 100,
                  'step' => 1,
                ),
                '%' => array(
                  'min' => 0,
                  'max' => 100,
                  'step' => 1,
                ),
              ),
    'std' => array(
                'md' => '10px',
                'sm' => '11px',
                'xs' => '0px',
            ),
    'unit' => array( '%','px','em' ),
    'selector' => '{{SELECTOR}} .example-slider{ font-size: {{data.addon_slider}}; }'
),
```

**非响应式**
```php
'addon_slider' => array(
    'type' => 'slider',
    'title' => __('滑块字段','your-textdomain'),
    'range' => array(
                'min' => 0,
                'max' => 8,
                'step' => 1,
            ),
    'std' => '12px',
    'unit' => array( '%','px','em' ),
    'selector' => '{{SELECTOR}} .example-slider{ font-size: {{data.addon_slider}}; }'
),
```

**非响应式且不带单位**
```php
'addon_slider' => array(
    'type' => 'slider',
    'title' => '滑块字段',
    'range' => array(
                'min' => 0,
                'max' => 100,
                'step' => 1,
            ),
    'std' => '12',
    'selector' 	=> '{{SELECTOR}} .example-slider{ font-size: {{data.addon_slider}}px; }'
),
```

## 控制读取
### PHP
在 `rander()` 方法中：  
**响应式**
```php
echo '<div>'.$data['settings']['addon_slider']['md'].'</div>';
echo '<div>'.$data['settings']['addon_slider']['sm'].'</div>';
echo '<div>'.$data['settings']['addon_slider']['xs'].'</div>';
```

**非响应式**
```php
echo '<div>'.$data['settings']['addon_slider'].'</div>';
```

### JS Template
在 `getTemplate()` 方法中：

**响应式**
```js
<div>{{data.addon_slider.md}}</div>
<div>{{data.addon_slider.sm}}</div>
<div>{{data.addon_slider.xs}}</div>
```
或
```js
<div>
<# _.forEach(data.addon_slider, function(value, key) { #>
    {{value}}
<# } #>
</div>
```

**非响应式**
```js
<div>{{data.addon_slider}}</div>
```
