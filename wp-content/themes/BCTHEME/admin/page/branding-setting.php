<?php

// 删除仪表盘不必要的 Widget
function custom_remove_dashboard_widgets() {
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); //WordPress 新闻
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); //快速草稿
}
add_action('wp_dashboard_setup', 'custom_remove_dashboard_widgets');

//自定义品牌菜单
add_action('admin_menu', function () {
    add_submenu_page(
        'themes.php', // 父菜单 (设置菜单)
        '自定义品牌', // 页面标题
        '自定义品牌', // 菜单标题
        'manage_options', // 权限
        'theme-info-settings', // 菜单 slug
        'render_theme_info_settings' // 页面回调函数
    );
});

// 页面回调函数
function render_theme_info_settings() {

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['theme_info_submit'])) {
        // 检查表单安全性
        check_admin_referer('theme_info_settings_form');

        // 定义默认值
        $default_options = [
            'screenshot'        => get_template_directory_uri() . '/screenshot.png',
            'theme_name'        => 'Modular',
            'theme_uri'         => 'https://www.xintheme.com/theme/188027.html',
            'description'       => 'WPJAM Modular Themes',
            'author'            => 'XinTheme 新主题 + WordPress果酱',
            'author_uri'        => 'https://www.xintheme.com/',
            'disable_menu'      => false,
            'hide_wpjam'        => false,
        ];

        // 获取提交的表单数据，合并默认值和用户提交的数据
        $options = array_merge($default_options, [
            'screenshot'       => !empty($_POST['screenshot']) ? sanitize_text_field($_POST['screenshot']) : $default_options['screenshot'],
            'theme_name'       => !empty($_POST['theme_name']) ? sanitize_text_field($_POST['theme_name']) : $default_options['theme_name'],
            'theme_uri'        => !empty($_POST['theme_uri']) ? esc_url_raw($_POST['theme_uri']) : $default_options['theme_uri'],
            'description'      => !empty($_POST['description']) ? sanitize_textarea_field($_POST['description']) : $default_options['description'],
            'author'           => !empty($_POST['author']) ? sanitize_text_field($_POST['author']) : $default_options['author'],
            'author_uri'       => !empty($_POST['author_uri']) ? esc_url_raw($_POST['author_uri']) : $default_options['author_uri'],
            'disable_menu'     => !empty($_POST['disable_menu']) ? true : false,
            //'hide_wpjam'     => !empty($_POST['hide_wpjam']) ? true : false, // 如果需要处理 hide_wpjam，则解注释此行
        ]);

        // 更新选项
        update_option('theme_info_options', $options);

        echo '<div class="updated"><p>设置已保存。</p></div>';
    }

    // 获取选项
    $options = get_option('theme_info_options', []);
    ?>

    <div class="wrap">
        <h1>自定义品牌设置</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="screenshot">主题图片</label></th>
                    <td>
                        <input type="text" id="screenshot" name="screenshot" value="<?php echo esc_attr(!empty($options['screenshot']) ? $options['screenshot'] : get_template_directory_uri() . '/screenshot.png'); ?>" class="regular-text">
                        <button type="button" class="button upload-button">上传图片</button>
                        <p class="description">图片宽高比：4:3，推荐大小：1200px × 900px</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="theme_name">主题名称</label></th>
                    <td><input type="text" id="theme_name" name="theme_name" value="<?php echo esc_attr(!empty($options['theme_name']) ? $options['theme_name'] : 'Modular'); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="theme_uri">主题链接</label></th>
                    <td><input type="url" id="theme_uri" name="theme_uri" value="<?php echo esc_url(!empty($options['theme_uri']) ? $options['theme_uri'] : 'https://www.xintheme.com/theme/188027.html'); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="description">主题介绍</label></th>
                    <td><textarea id="description" name="description" rows="5" class="regular-text"><?php echo esc_textarea(!empty($options['description']) ? $options['description'] : 'WPJAM Modular Themes'); ?></textarea><p class="description">一段简短的描述文本...</p></td>
                </tr>
                <tr>
                    <th scope="row"><label for="author">主题作者</label></th>
                    <td><input type="text" id="author" name="author" value="<?php echo esc_attr(!empty($options['author']) ? $options['author'] : 'XinTheme 新主题 + WordPress果酱'); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="author_uri">作者链接</label></th>
                    <td><input type="url" id="author_uri" name="author_uri" value="<?php echo esc_url(!empty($options['author_uri']) ? $options['author_uri'] : 'https://www.xintheme.com/'); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">移除「自定义品牌」菜单</th>
                    <td>
                        <input type="checkbox" id="disable_menu" name="disable_menu" value="1" <?php checked(!empty($options['disable_menu'])); ?>>
                        <label for="disable_menu">移除当前页面的菜单项，但依旧可以通过链接访问：<br><?php echo admin_url('themes.php?page=' . $_GET['page']);?></label>
                    </td>
                </tr>
                <!--tr>
                    <th scope="row">屏蔽 WPJAM 插件显示</th>
                    <td>
                        <input type="checkbox" id="hide_wpjam" name="hide_wpjam" value="1" <?php //checked(!empty($options['hide_wpjam'])); ?>>
                        <label for="hide_wpjam">屏蔽 WPJAM 插件在后台插件列表中的显示</label>
                    </td>
                </tr-->
            </table>
            <?php wp_nonce_field('theme_info_settings_form'); ?>
            <?php submit_button('保存设置', 'primary', 'theme_info_submit'); ?>
        </form>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            $('.upload-button').click(function (e) {
                e.preventDefault();
                var customUploader = wp.media({
                    title: '选择图片',
                    button: {
                        text: '选择图片'
                    },
                    multiple: false
                }).on('select', function () {
                    var attachment = customUploader.state().get('selection').first().toJSON();
                    $('#screenshot').val(attachment.url);
                }).open();
            });
        });
    </script>

    <?php
}

// 屏蔽菜单显示逻辑
add_action('admin_menu', function () {
    $options = get_option('theme_info_options', []);
    if (!empty($options['disable_menu'])) {
        remove_submenu_page('themes.php', 'theme-info-settings');
    }
}, 99);

// 屏蔽 WPJAM 插件逻辑
/*
add_filter('all_plugins', function ($plugins) {
    $options = get_option('theme_info_options', []);
    if (!empty($options['hide_wpjam'])) {
        foreach ($plugins as $plugin_file => $plugin_data) {
            if (strpos($plugin_file, 'wpjam') !== false) {
                unset($plugins[$plugin_file]);
            }
        }
    }
    return $plugins;
});
*/

//动态修改主题信息
add_action('update_option_theme_info_options', function () {
    $options = get_option('theme_info_options', []);
    $theme_path = get_stylesheet_directory() . '/style.css';

    if (file_exists($theme_path)) {
        $style_data = file_get_contents($theme_path);

        // 动态替换主题信息
        $style_data = preg_replace('/Theme Name:(.*)/', 'Theme Name: ' . esc_html($options['theme_name'] ?? ''), $style_data);
        $style_data = preg_replace('/Theme URI:(.*)/', 'Theme URI: ' . esc_url($options['theme_uri'] ?? ''), $style_data);
        $style_data = preg_replace('/Description:(.*)/', 'Description: ' . esc_html($options['description'] ?? ''), $style_data);
        $style_data = preg_replace('/Author:(.*)/', 'Author: ' . esc_html($options['author'] ?? ''), $style_data);
        $style_data = preg_replace('/Author URI:(.*)/', 'Author URI: ' . esc_url($options['author_uri'] ?? ''), $style_data);

        file_put_contents($theme_path, $style_data);
    }
});

//修改主题screenshot图片
add_filter('wp_prepare_themes_for_js', function ($themes) {
    $options = get_option('theme_info_options', []);
    $screenshot = $options['screenshot'] ?? '';

    if (!$screenshot) return $themes;

    $current_theme = get_stylesheet();
    if (isset($themes[$current_theme])) {
        $themes[$current_theme]['screenshot'][0] = esc_url($screenshot);
    }

    return $themes;
});


// 更新主题后重新保存设置，避免被主题文件覆盖自定义品牌设置
add_action('upgrader_process_complete', 'update_theme_info_after_update', 10, 2);
function update_theme_info_after_update($upgrader_object, $options) {
    // 检查是否是更新主题操作
    if ($options['type'] === 'theme' && isset($options['action']) && $options['action'] === 'update') {
        // 获取保存的主题设置
        $options = get_option('theme_info_options', []);
        
        // 设置主题样式表路径
        $theme_path = get_stylesheet_directory() . '/style.css';

        // 检查主题样式表文件是否存在
        if (file_exists($theme_path)) {
            // 读取样式表内容
            $style_data = file_get_contents($theme_path);

            // 动态替换主题信息
            $style_data = preg_replace('/Theme Name:(.*)/', 'Theme Name: ' . esc_html($options['theme_name'] ?? ''), $style_data);
            $style_data = preg_replace('/Theme URI:(.*)/', 'Theme URI: ' . esc_url($options['theme_uri'] ?? ''), $style_data);
            $style_data = preg_replace('/Description:(.*)/', 'Description: ' . esc_html($options['description'] ?? ''), $style_data);
            $style_data = preg_replace('/Author:(.*)/', 'Author: ' . esc_html($options['author'] ?? ''), $style_data);
            $style_data = preg_replace('/Author URI:(.*)/', 'Author URI: ' . esc_url($options['author_uri'] ?? ''), $style_data);

            // 写回更新后的样式表
            file_put_contents($theme_path, $style_data);
        }
    }
}



