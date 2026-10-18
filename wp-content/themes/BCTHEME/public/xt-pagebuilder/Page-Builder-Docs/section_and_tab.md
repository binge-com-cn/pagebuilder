# Section 与 Tab
如果你希望某个字段显示在 `style` 标签页下，需要在参数中加入 `'tab' => 'style'`。

如果不设置 `section`，字段会自动归入 `General` 分组；如果设置了 `section`，字段就会进入指定分组。

### 只有 Tab，没有 Section
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
    'tab' => 'style',
),
```

### General 标签页中的 Section
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
    'section' => '样式',
),
'button_type' => array(
    'type' => 'select',
    'title' => __('按钮类型','xintheme-pagebuilder'),
    'values' => array(
        'solid' => __('实线边框','xintheme-pagebuilder'),
        'dotted' => __('点状边框','xintheme-pagebuilder'),
    ),
    'std' => 'solid',
    'section' => '样式',
),
'button_color' => array(
    'type' => 'color',
    'title' => __('按钮颜色','xintheme-pagebuilder'),
    'section' => '颜色',
),
```

### Style 标签页中的 Section
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
    'tab' => 'style',
    'section' => '样式',
),
'button_type' => array(
    'type' => 'select',
    'title' => __('按钮类型','xintheme-pagebuilder'),
    'values' => array(
        'solid' => __('实线边框','xintheme-pagebuilder'),
        'dotted' => __('点状边框','xintheme-pagebuilder'),
    ),
    'std' => 'solid',
    'tab' => 'style',
    'section' => '样式',
),
'button_color' => array(
    'type' => 'color',
    'title' => __('按钮颜色','xintheme-pagebuilder'),
    'tab' => 'style',
    'section' => '颜色',
),
```
