# 插件与主题内嵌双模式

XinTheme PageBuilder 支持两种加载方式：

1. 作为独立插件放在 `wp-content/plugins/xt-pagebuilder/` 中启用。
2. 作为主题内置功能放在当前主题目录中，由主题 `functions.php` 引入。

## 插件模式

插件模式保持 WordPress 标准插件用法不变：

```php
wp-content/plugins/xt-pagebuilder/xt-pagebuilder.php
```

在后台插件列表启用后，程序会使用插件路径与插件 URL 加载资源，并通过插件激活钩子初始化默认配置。

## 主题内嵌模式

将整个 `xt-pagebuilder` 目录复制到主题中，例如：

```php
wp-content/themes/your-theme/inc/xt-pagebuilder/
```

然后在主题 `functions.php` 中引入入口文件：

```php
require_once get_template_directory() . '/inc/xt-pagebuilder/xt-pagebuilder.php';
```

如果放在子主题中，可使用：

```php
require_once get_stylesheet_directory() . '/inc/xt-pagebuilder/xt-pagebuilder.php';
```

主题内嵌模式会自动识别当前位置，使用主题目录 URL 加载 `assets/`、`addons/` 等资源，并使用 `load_theme_textdomain()` 加载语言文件。

## 初始化规则

插件模式使用 `register_activation_hook()` 初始化默认配置。

主题内嵌模式没有插件激活钩子，因此会在以下场景初始化默认配置：

1. 切换到当前主题时，通过 `after_switch_theme` 初始化。
2. 已启用主题中新增 PageBuilder 后，在后台首次加载时检查并补齐默认配置。

## 注意事项

- 同一个站点不要同时启用插件版和主题内嵌版。
- 主题内嵌时应保留完整目录结构，包括 `assets/`、`addons/`、`classes/`、`core/`、`inc/`。
- 如果主题中使用子目录存放 PageBuilder，入口文件必须仍然是 `xt-pagebuilder.php`。
- 插件后台列表中的“设置、文档、支持”等插件行链接只会在插件模式显示。
