# 自定义模块
你可以在自己的 WordPress 主题或插件中为 *XinTheme PageBuilder* 添加任意数量的自定义模块。  
模块和组件的开发方式基本一致，但它会显示在编辑器左侧新增的 `模块` 菜单中，而不是 `组件` 菜单中。

只需要添加一个过滤器，并在过滤器中注册模块类名即可。

```php
add_filter( 'xtpb_available_modules', 'prefix_custom_module_include' );
if ( ! function_exists( 'prefix_custom_module_include' ) ) {
    function prefix_custom_module_include( $modules ) {
        $modules[] = 'Custom_Module';
        // Add Other Custom Module class name in here, at a time.
        return $modules;
    }
}
```

## 一些说明
01. `get_name()` 必须唯一，且不能包含空格。
02. `get_title()` 可以包含空格，它会作为模块名称显示。
03. `get_icon()` 支持 Remix Icon 与 XTPB [默认图标]。
04. `get_thumb()` 用于模块列表缩略图。建议传入一张模块预览图 URL。
05. `get_category_name()` 用于模块分类名称，模块列表会按分类折叠显示。
06. `get_class()` 可选。返回的 class 会在模块拖入页面后，自动追加到它所在行（`xtpb-section`）的 `row_class` 上。如果模块插入在内层行中，则会追加到对应内层行的 `row_class`。也可以使用 `get_row_class()`、`get_column_class()`、`get_addon_class()` 分别给行、列、模块组件追加 class。
07. `get_settings()` 这里可以使用任意[field type](https://github.com/xintheme/XinTheme-PageBuilder#field-type)。
08. `render()` 负责前台输出模块 HTML。模块适合固定布局、通过设置项控制内容和样式的场景。
09. `getTemplate()` 可以提升实时编辑速度。它使用 [Lodash](https://lodash.com/docs/4.17.10#template) 模板语法，请按 Lodash 模板文档编写。
10. `get_enqueue_script()` 与 `get_enqueue_style()` 用于为当前模块声明专属脚本和样式。只有当页面中存在该模块时才会加载。你只需要在主题或插件中提前 `wp_register_script` 和 `wp_register_style`，不要在这里直接 `wp_enqueue_script` 或 `wp_enqueue_style`。
11. `get_css_files()` 与 `get_js_files()` 用于声明模块参与页面 CSS/JS 缓存生成的本地文件路径。它们返回的是服务器文件路径，不是 URL。适合把主题内自定义模块的 `frontend.css`、`frontend.js` 合并到页面构建器生成的资源中。

```php
class Custom_Module {
    public function get_name() {
        return 'custom_media_text_module';
    }

    public function get_title() {
        return '左图右文模块';
    }

    public function get_icon() {
        return 'ri-layout-4-line';
    }

    public function get_thumb() {
        return plugin_dir_url( __FILE__ ) . 'assets/module-media-text.jpg';
    }

    public function get_category_name() {
        return '图文模块';
    }

    public function get_class() {
        return 'module-media-text module-highlight';
    }

    public function get_column_class() {
        return 'module-media-text-column';
    }

    public function get_addon_class() {
        return 'module-media-text-addon';
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
            get_template_directory() . '/xt-pagebuilder/modules/page-hero/assets/css/frontend.css',
        );
    }

    public function get_js_files() {
        return array(
            get_template_directory() . '/xt-pagebuilder/modules/page-hero/assets/js/frontend.js',
        );
    }

    public function get_js_dependencies() {
        return array( 'jquery' );
    }
    */

    public function get_settings() {
        return array(
            'module_image' => array(
                'type'  => 'media',
                'title' => '图片',
            ),
            'module_title' => array(
                'type'  => 'text',
                'title' => '标题',
                'std'   => '模块标题',
            ),
            'module_content' => array(
                'type'  => 'textarea',
                'title' => '内容',
                'std'   => '这里是模块内容说明。',
            ),
            'module_button_text' => array(
                'type'  => 'text',
                'title' => '按钮文字',
                'std'   => '了解更多',
            ),
            'module_button_link' => array(
                'type'  => 'link',
                'title' => '按钮链接',
            ),
        );
    }

    public function render( $data = null ) {
        $settings = $data['settings'];

        $image_url = ! empty( $settings['module_image']['url'] ) ? $settings['module_image']['url'] : '';
        $title     = ! empty( $settings['module_title'] ) ? $settings['module_title'] : '';
        $content   = ! empty( $settings['module_content'] ) ? $settings['module_content'] : '';
        $btn_text  = ! empty( $settings['module_button_text'] ) ? $settings['module_button_text'] : '';
        $btn_link  = ! empty( $settings['module_button_link']['link'] ) ? $settings['module_button_link']['link'] : '#';

        ob_start();
        ?>
        <div class="custom-media-text-module">
            <div class="custom-media-text-module__media">
                <?php if ( $image_url ) : ?>
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                <?php endif; ?>
            </div>
            <div class="custom-media-text-module__content">
                <?php if ( $title ) : ?>
                    <h3><?php echo esc_html( $title ); ?></h3>
                <?php endif; ?>

                <?php if ( $content ) : ?>
                    <div class="custom-media-text-module__text"><?php echo wp_kses_post( $content ); ?></div>
                <?php endif; ?>

                <?php if ( $btn_text ) : ?>
                    <a href="<?php echo esc_url( $btn_link ); ?>" class="custom-media-text-module__button">
                        <?php echo esc_html( $btn_text ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function getTemplate() {
        return '<div class="custom-media-text-module">
            <div class="custom-media-text-module__media">
                <# if ( data.module_image && data.module_image.url ) { #>
                    <img src="{{ data.module_image.url }}" alt="{{ data.module_title }}">
                <# } #>
            </div>
            <div class="custom-media-text-module__content">
                <# if ( data.module_title ) { #>
                    <h3>{{ data.module_title }}</h3>
                <# } #>

                <# if ( data.module_content ) { #>
                    <div class="custom-media-text-module__text">{{ data.module_content }}</div>
                <# } #>

                <# if ( data.module_button_text ) { #>
                    <a href="{{ data.module_button_link.link ? data.module_button_link.link : \"#\" }}" class="custom-media-text-module__button">
                        {{ data.module_button_text }}
                    </a>
                <# } #>
            </div>
        </div>';
    }
}
```

## 模块 CSS/JS 文件
如果自定义模块放在主题中，并且希望模块 CSS、JS 跟随页面构建器一起重新生成缓存，可以在模块类中添加 `get_css_files()` 与 `get_js_files()`：

```php
public function get_css_files() {
    return array(
        get_template_directory() . '/xt-pagebuilder/modules/page-hero/assets/css/frontend.css',
    );
}

public function get_js_files() {
    return array(
        get_template_directory() . '/xt-pagebuilder/modules/page-hero/assets/js/frontend.js',
    );
}

public function get_js_dependencies() {
    return array( 'jquery' );
}
```

说明：

- 返回值必须是本地绝对路径，例如 `get_template_directory() . '/xt-pagebuilder/modules/page-hero/assets/css/frontend.css'`。
- 不要返回 `get_template_directory_uri()` 这类 URL。
- 可以返回多个文件路径，页面构建器会在重新生成页面 CSS/JS 缓存时读取这些文件。
- 如果合并的 JS 依赖 WordPress 已注册脚本，可以通过 `get_js_dependencies()` 返回依赖 handle，例如 `jquery`。
- `get_css_files()` 与 `get_js_files()` 只影响页面级资源缓存生成；如果还需要按需加载已注册的前台样式或脚本，继续使用 `get_enqueue_style()`、`get_enqueue_script()`。

### 资源加载与合并的区别

`get_enqueue_script()`、`get_enqueue_style()` 返回的是 WordPress 已注册的资源 handle。页面使用到当前模块时，页面构建器会按需执行 `wp_enqueue_script()`、`wp_enqueue_style()` 加载这些资源，但不会把文件内容合并进页面缓存。

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

## 模块默认 Class
如果你希望模块拖入页面后，自动给所在行追加自定义 class，可以使用：

```php
public function get_class() {
    return 'test1 test2';
}
```

`get_class()` 等同于行 class。也可以显式使用 `get_row_class()`：

```php
public function get_row_class() {
    return 'module-row-class';
}
```

如果需要同时给模块所在列和模块组件本身添加 class，可以使用：

```php
public function get_column_class() {
    return 'module-column-class';
}

public function get_addon_class() {
    return 'module-addon-class';
}
```

兼容别名：

- `get_col_class()` 等同于 `get_column_class()`。
- `get_component_class()` 等同于 `get_addon_class()`。

说明：

- 返回值支持多个 class，使用空格分隔。
- 行 class 会自动合并到目标行的 `row_class` 中。
- 列 class 会自动合并到目标列的 `col_class` 中。
- 模块组件 class 会自动合并到当前模块组件的 `addon_class` 中。
- 不会覆盖原来已有的 class。
- 同名 class 会自动去重。
