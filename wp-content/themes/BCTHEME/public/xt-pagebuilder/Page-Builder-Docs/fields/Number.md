# Number
数字输入字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（number）
title | `optional` | 字符串
desc | `optional` | 字符串
unit | `optional` | 数组
responsive | `optional` | 布尔值（默认：false）
range | `optional` | 数组（见示例）
placeholder | `optional` | 字符串
std | `optional` | 非响应式时为字符串 / 响应式时为数组
selector | `optional` | 字符串 / 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
非多值时返回 `number`  
多值时返回 `object`

## 示例
**响应式**
```php
'addon_number' => array(
    'type' => 'number',
    'title' => __('数字字段','your-textdomain'),
    'placeholder' => __('请输入数字','your-textdomain'),
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
    'responsive' => true,
    'selector' => '{{SELECTOR}} .example-number{ font-size: {{data.addon_number}}; }'
)
```

**非响应式，带单位**
```php
'addon_number' => array(
    'type' => 'number',
    'title' => __('数字字段','your-textdomain'),
    'range' => array(
                'min' => 0,
                'max' => 8,
                'step' => 1,
            ),
    'std' => '12px',
    'unit' => array( '%','px','em' ),
    'selector' => '{{SELECTOR}} .example-slider{ font-size: {{data.addon_number}}; }'
),
```

**非响应式，不带单位**
```php
'addon_number' => array(
    'type' => 'number',
    'title' => __('数字字段','your-textdomain'),
    'range' => array(
                'min' => 0,
                'max' => 100,
                'step' => 1,
            ),
    'std' => '12',
    'selector' 	=> '{{SELECTOR}} .example-slider{ font-size: {{data.addon_number}}px; }'
),
```

## 控制读取
### PHP
在 `rander()` 方法中：
**响应式**
```php
echo '<div>'.$settings['addon_number']['md'].'</div>';
echo '<div>'.$settings['addon_number']['sm'].'</div>';
echo '<div>'.$settings['addon_number']['xs'].'</div>';
echo '<div class="example-slider">数字示例</div>';

```

**非响应式**
```php
echo '<div>'.$settings['addon_number'].'</div>';
echo '<div class="example-slider">数字示例</div>';
```

### JS Template
在 `getTemplate()` 方法中：

**响应式**
```js
<div>{{data.addon_number.md}}</div>
<div>{{data.addon_number.sm}}</div>
<div>{{data.addon_number.xs}}</div>
<div class="example-slider">数字示例</div>
```
或
```js
<div>
<# _.forEach(data.addon_number, function(value, key) { #>
    {{value}}
<# } #>
</div>
<div class="example-slider">数字示例</div>
```

**非响应式**
```js
<div>{{data.addon_number}}</div>
<div class="example-slider">数字示例</div>
```
