# 自定义组件
你可以在自己的 WordPress 主题或插件中为 *XinTheme PageBuilder* 添加任意数量的自定义组件。只需要添加一个过滤器，并在过滤器中注册组件类名即可。

```php
add_filter( 'xtpb_available_addons', 'prefix_custom_addon_include' );
if ( ! function_exists('prefix_custom_addon_include')){
    function prefix_custom_addon_include($addons){
        $addons[] = 'Custom_Addon';
        // Add Other Custom Addon class name in here, at a time.
        return $addons;
    }
}
```

你可以在我们的插件目录中查看 `plugins_directory/addons` 里的组件写法。下面是一个示例组件代码。

## 一些说明
01. `get_name()` 必须唯一，且不能包含空格。
02. `get_title()` 可以包含空格，它会作为组件名称显示。
03. `get_icon()` 支持 Remix Icon 与 XTPB [默认图标]。
04. `get_category_name()` 如果你想让组件显示在指定分类下，可以使用这个方法。
05. `get_addon_class()` 可选。返回的 class 会在组件添加到页面后，自动追加到组件外层（`xtpb-addon`）的 `addon_class` 上。也可以使用别名 `get_component_class()`。
06. `get_settings()` 这里可以使用任意[field type]。
07. `render()` 负责前台输出。如果你没有实现 `getTemplate()`，实时编辑时会通过 AJAX 调用 `render()`。
08. `getTemplate()` 可以提升前台编辑速度。它使用 [Lodash](https://lodash.com/docs/4.17.10#template) 模板语法，请按 Lodash 模板文档编写。
09. `get_enqueue_script()` 与 `get_enqueue_style()` 用于为当前组件声明专属脚本和样式。只有当页面中存在该组件时才会加载。你只需要在主题或插件中提前 `wp_register_script` 和 `wp_register_style`，不要在这里直接 `wp_enqueue_script` 或 `wp_enqueue_style`。
10. `get_css_files()` 与 `get_js_files()` 用于声明组件参与页面 CSS/JS 缓存生成的本地文件路径。它们返回的是服务器文件路径，不是 URL。

```php
class Custom_Addon{
    public function get_name(){
        return 'checkit';
    }
    public function get_title(){
        return 'Checkit';
    }
    public function get_icon() {
        return 'xtpb-font-trash';
    }
    public function get_category_name(){
        return 'Addon Category';
    }
    public function get_addon_class() {
        return 'custom-addon-wrapper custom-addon-highlight';
    }
    /* 
    public function get_enqueue_script(){
        return array( 'script-name' ); 
    }
    public function get_enqueue_style(){
	return array( 'style-name' );
    }

    public function get_css_files() {
        return array(
            get_template_directory() . '/xt-pagebuilder/addons/custom-addon/assets/css/frontend.css',
        );
    }

    public function get_js_files() {
        return array(
            get_template_directory() . '/xt-pagebuilder/addons/custom-addon/assets/js/frontend.js',
        );
    }

    public function get_js_dependencies() {
        return array( 'jquery' );
    }
    */

    // Textarea Settings Fields
    public function get_settings() {
        $settings = array(
            'custom_addon_textarea' => array(
                'type' => 'textarea',
                'title' => 'Textarea Title',
                'placeholder' => 'Textarea Placeholder',
                'std' => 'Textarea Default'
            ),
        );
        return $settings;
    }

    public function render($data = null){
        $settings = $data['settings'];
        return '<div class="wrapper">TextArea Output:'.$settings['custom_addon_textarea'].'</div>';
    }

    // headline Template
    public function getTemplate(){
        return '<# if(data.custom_addon_textarea){ #>
                    <div class="sample-number1">DATA:{{data.custom_addon_textarea}}</div>
                <# } #>';
    }
}

```

## 组件 CSS/JS 文件
如果自定义组件放在主题中，并且希望组件 CSS、JS 跟随页面构建器一起重新生成缓存，可以在组件类中添加 `get_css_files()` 与 `get_js_files()`：

```php
public function get_css_files() {
    return array(
        get_template_directory() . '/xt-pagebuilder/addons/custom-addon/assets/css/frontend.css',
    );
}

public function get_js_files() {
    return array(
        get_template_directory() . '/xt-pagebuilder/addons/custom-addon/assets/js/frontend.js',
    );
}

public function get_js_dependencies() {
    return array( 'jquery' );
}
```

说明：

- 返回值必须是本地绝对路径，不要返回 URL。
- 可以返回多个文件路径，页面构建器会在重新生成页面 CSS/JS 缓存时读取这些文件。
- 如果合并的 JS 依赖 WordPress 已注册脚本，可以通过 `get_js_dependencies()` 返回依赖 handle，例如 `jquery`。
- `get_css_files()` 与 `get_js_files()` 只影响页面级资源缓存生成；如果还需要按需加载已注册的前台样式或脚本，继续使用 `get_enqueue_style()`、`get_enqueue_script()`。

### 资源加载与合并的区别

`get_enqueue_script()`、`get_enqueue_style()` 返回的是 WordPress 已注册的资源 handle。页面使用到当前组件时，页面构建器会按需执行 `wp_enqueue_script()`、`wp_enqueue_style()` 加载这些资源，但不会把文件内容合并进页面缓存。

`get_js_files()`、`get_css_files()` 返回的是服务器本地文件路径。页面构建器会读取这些文件内容，并合并到页面级缓存文件中，例如：

```text
uploads/xt-pagebuilder/xtpb-page-js-{post_id}.js
uploads/xt-pagebuilder/xtpb-page-css-{post_id}.css
```

对照：

方法 | 返回内容 | 作用
--- | --- | ---
`get_enqueue_script()` | 已注册的 script handle | 按需单独加载 JS，不合并
`get_enqueue_style()` | 已注册的 style handle | 按需单独加载 CSS，不合并
`get_js_files()` | JS 本地文件路径 | 读取并合并进页面 JS 缓存
`get_css_files()` | CSS 本地文件路径 | 读取并合并进页面 CSS 缓存

## 组件默认 Class
如果你希望组件添加到页面后，自动给组件外层追加自定义 class，可以使用：

```php
public function get_addon_class() {
    return 'custom-addon-wrapper custom-addon-highlight';
}
```

也可以使用别名：

```php
public function get_component_class() {
    return 'custom-addon-wrapper custom-addon-highlight';
}
```

说明：

- 返回值支持多个 class，使用空格分隔。
- class 会自动合并到当前组件的 `addon_class` 中。
- 不会覆盖用户在组件高级设置里填写的 `addon_class`。
- 同名 class 会自动去重。
- `get_row_class()`、`get_column_class()` 主要用于自定义模块拖入页面时给所在行、列追加 class；普通组件默认只追加组件自身 class。
