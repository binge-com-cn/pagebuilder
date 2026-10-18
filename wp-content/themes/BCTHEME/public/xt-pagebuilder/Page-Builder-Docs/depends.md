# Depends

有些情况下，你需要在组件里使用条件字段。

XinTheme PageBuilder 支持 `!=` 和 `=` 两种条件判断。

### 单个条件
当 `Button Style` 的值为 `primary` 时，显示 `Button Color` 字段。
```php
'button_style' => array(
    'type' => 'select',
    'title' => __('按钮样式','xintheme-pagebuilder'),
    'values' => array(
        'primary' => __('主要按钮','xintheme-pagebuilder'),
        'success' => __('成功按钮','xintheme-pagebuilder'),
        'info' => __('信息按钮','xintheme-pagebuilder'),
    ),
    'std' => 'primary',
),
'button_color' => array(
    'type' => 'color',
    'title' => __('按钮颜色','xintheme-pagebuilder'),
    'depends' => array( array('button_style', '=', 'primary' ) ),
),
```

### 多个值条件
当 `Button Style` 的值为 `primary` 或 `info` 时，显示 `Button Color` 字段。
```php
'button_style' => array(
    'type' => 'select',
    'title' => __('按钮样式','xintheme-pagebuilder'),
    'values' => array(
        'primary' => __('主要','xintheme-pagebuilder'),
        'success' => __('成功','xintheme-pagebuilder'),
        'info' => __('信息','xintheme-pagebuilder'),
    ),
    'std' => 'primary',
),
'button_color' => array(
    'type' => 'color',
    'title' => __('按钮颜色','xintheme-pagebuilder'),
    'depends' => array( array('button_style', '=', array('primary','info') ) ),
),
```

### 多字段组合条件
当 `Button Style` 的值为 `primary` 且 `Button Type` 的值为 `solid` 时，显示 `Button Color` 字段。
```php
'button_style' => array(
    'type' => 'select',
    'title' => __('样式','xintheme-pagebuilder'),
    'values' => array(
        'primary' => __('主要','xintheme-pagebuilder'),
        'success' => __('成功','xintheme-pagebuilder'),
        'info' => __('信息','xintheme-pagebuilder'),
    ),
    'std' => 'primary',
),
'button_type' => array(
    'type' => 'select',
    'title' => __('按钮类型','xintheme-pagebuilder'),
    'values' => array(
        'solid' => __('实线边框','xintheme-pagebuilder'),
        'dotted' => __('点状边框','xintheme-pagebuilder'),
    ),
    'std' => 'solid',
),
'button_color' => array(
    'type' => 'color',
    'title' => __('按钮颜色','xintheme-pagebuilder'),
    'depends' => array( 
        array('button_style', '=', 'primary' ), 
        array('button_type', '=', 'solid' ),
    ),
),
```

### 单个不等于条件
当 `Button Style` 的值不等于 `primary` 时，显示 `Button Color` 字段。
```php
'button_style' => array(
    'type' => 'select',
    'title' => __('按钮样式','xintheme-pagebuilder'),
    'values' => array(
        'primary' => __('主要按钮','xintheme-pagebuilder'),
        'success' => __('成功按钮','xintheme-pagebuilder'),
        'info' => __('信息按钮','xintheme-pagebuilder'),
    ),
    'std' => 'primary',
),
'button_color' => array(
    'type' => 'color',
    'title' => __('按钮颜色','xintheme-pagebuilder'),
    'depends' => array( array('button_style', '!=', 'primary' ) ),
),
```
