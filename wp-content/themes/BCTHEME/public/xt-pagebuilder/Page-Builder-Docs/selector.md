# Selector

大多数字段类型都支持 `selector` 参数。

`{{SELECTOR}}` 会被替换为当前组件的唯一 class。

`data.field_key` 会被替换为对应字段的值。

### 单个选择器
```php
'selector' => '{{SELECTOR}} .example-class { font-size: {{data.number_height}}; }',
```

### 使用数组定义多个选择器
```php
'selector' => array(
    '{{SELECTOR}} .example-class-1 { font-size: {{data.number_height}}; }',
    '{{SELECTOR}} .example-class-2 { line-height: {{data.number_height}}; }',
)
```

### 使用字符串定义多个选择器
```php
'selector' => '{{SELECTOR}} .example-class-1 { font-size: {{data.number_height}}; }, {{SELECTOR}} .example-class-2 { line-height: {{data.number_height}}; }'
```

### 可以在一个字段里使用另一个字段的值
```php
'addon_alignment' => array(
    'type' => 'alignment',
    'title' => __('Alignment Field','your-textdomain'),
    'selector' => '{{SELECTOR}} .example-alignment{ color: {{data.addon_color}}; }',
),
'addon_color' => array(
    'type' => 'color',
    'title' => __('Color Field','your-textdomain'),
),
```

### 这些字段类型的 selector 不需要包裹花括号
1. typography
2. typography2
3. gradient
4. border
5. color2
6. boxshadow

```php
'selector' => '{{SELECTOR}} .example-class'
```
