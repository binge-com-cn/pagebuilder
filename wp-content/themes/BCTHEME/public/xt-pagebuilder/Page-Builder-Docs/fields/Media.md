# Media
WordPress 媒体管理器字段。

## 返回值
始终返回 `object`

## 参数
参数 | 类型 | 说明
--- | --- | ---
type | `required` | 固定字符串（media）
title | `optional` | 字符串
desc | `optional` | 字符串
multiple | `optional` | 布尔值（默认：false）
std | `optional` | 字符串
tab | `optional` | 字符串（style）
section | `optional` | 字符串

## 示例
**单个**
```php
'addon_media' => array(
    'type' => 'media',
    'title' => __('媒体字段','your-textdomain'),
    'std' => array( 'url' => 'http://example.com/example1.jpg')
)
```
不支持 `selector` 参数。

**多个**
```php
'addon_media' => array(
    'type' => 'media',
    'title' => __('媒体字段','your-textdomain'),
    'multiple' => true,
    'std' => array(
        array( 'url' => 'http://example.com/example1.jpg'),
        array( 'url' => 'http://example.com/example2.jpg')
    )
)
```
不支持 `selector` 参数。

## 控制读取
### PHP
在 `rander()` 方法中：
**单个**
```php
echo '<div>'.$data['settings']['addon_media']['url'].'</div>';
echo '<div>'.$data['settings']['addon_media']['id'].'</div>';
```
**多个**
```php
echo '<div>';
foreach( $data['settings']['addon_media'] as $value) {
    echo $value['url'];
}
echo '</div>';
```

### JS Template
在 `getTemplate()` 方法中：
**单个**
```js
<div>{{data.addon_media.url}}</div>
<div>{{data.addon_media.id}}</div>
```
**多个**
```js
<div>
<# _.forEach(data.addon_media, function(value, key) { #>
    {{value.url}}
<# } #>
</div>
```
