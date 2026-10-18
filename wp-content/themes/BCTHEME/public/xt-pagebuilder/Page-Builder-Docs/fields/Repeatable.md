# Repeatable
可重复分组字段。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（repeatable）
title | `optional` | 字符串
desc | `optional` | 字符串
attr | `optional` | 数组
std | `optional` | 数组
max | `optional` | 数字，限制最多可添加项目数量
tab | `optional` | 字符串（style）
section | `optional` | 字符串

`max` 达到上限后仍会显示“添加项目”和“复制项目”按钮，继续点击时会提示最多可添加的数量。`limit` 也可作为别名使用，推荐统一使用 `max`。

## 返回值
始终返回 `object`

```php
'repeatable_item' => array(
    'title' => '可重复项目',
    'type' => 'repeatable',
    'max' => 5,
    'attr' => array(
	    'icon_list' => array(
		    'type' => 'iconsocial',
		    'title' => '社交图标字段',
		    'std' => 'ri-facebook-fill'
	        ),
		'social_url' => array(
		    'type' => 'text',
		    'title' => '添加社交链接',
		    'std' => '#'
		),
    ),
    'std' => array(
        array( 
            'icon_list' => 'ri-twitch-fill',
            'social_url' => '#',
        ),
        array( 
            'icon_list' => 'ri-twitter-fill',
            'social_url' => '#',
        ),
    ),
)
```

## 控制读取
### PHP
在 `rander()` 方法中：
```php
echo '<div>';
foreach ( $settings['repetable_check'] as $key => $value ){
    echo $value['icon_list'];
    echo $value['social_url'];
}
echo '</div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>
<# _.forEach(data.repetable_check, function(value, key) { #>
    {{value.icon_list}}
    {{value.social_url}}
<# }) #>
</div>
```
