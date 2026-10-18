# Animation
动画输入字段（wowjs）。

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（textarea）
title | `optional` | 字符串
desc | `optional` | 字符串
std | `optional` | 数组
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 返回值
始终返回 `object`

## 示例
```php
'addon_animation'=> array(
	'type' => 'animation',
	'title' => __('动画字段','your-textdomain'),
	'std' => array(
	    	'name' => 'wow animated fadeIn',
		'delay' => '300',
		'duration' => '400'
	    ),
)
```
不支持 `selector` 参数。

## 控制读取
### PHP
在 `render()` 方法中：
```php
echo '<div class="sample-animation xtpb-wow xtpb-animated '.$data['settings']['addon_animation']['name'].' '.$data['settings']['addon_animation']['duration'].'" data-xtpb-wow-delay="'.$data['settings']['addon_animation']['delay'].'"> 动画文本 </div>';
```

### JS Template
在 `getTemplate()` 方法中：
```js
<div>{{data.addon_animation.duration}}</div>
```
