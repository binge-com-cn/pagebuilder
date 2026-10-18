<?php

/**
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-09-25 17:02:34
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-10-24 18:20:04
 */

/** --------------------------------------------------------------------------------- *
 *  多重筛选 专用菜单
 *  --------------------------------------------------------------------------------- */
class Dahuzi_Filter_Walker_Menu extends Walker_Nav_Menu {
    // 开始渲染子菜单（即二级菜单）
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth); // 缩进
        $classes = ['sub-menu']; // 子菜单的 CSS 类
        $class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $output .= "\n" . $indent . '<ul' . $class_names . '>' . "\n"; // 输出子菜单的 <ul> 标签
    }

    // 开始渲染菜单项
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : ''; // 缩进

        $class_names = $value = '';

        // 提取菜单项的类
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        if ($depth == 0) {

            $custom_classes = array_filter($classes, function($class) {
                // 过滤掉 WordPress 自动添加的类，保留自定义类
                return strpos($class, 'menu-item') === false && strpos($class, 'current-menu') === false;
            });

            // 如果存在自定义类名，添加到 class 属性中
            if (!empty($custom_classes)) {
                $class_names = ' class="' . esc_attr(join(' ', $custom_classes)) . '"';
                $class_names = ($class_names != ' class=""') ? $class_names : ''; //多这一步 只为了移除空白的class属性
            }

            // 对于一级菜单项，使用 <h3> 标签
            //$output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';
            $output .= $indent . '<li' . $class_names . '>';
            $output .= '<h3>'; // 开始 <h3> 标签
        } else {
            // 对于二级及以下菜单项，使用 <li> 标签
            //$output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';
            $output .= $indent . '<li>';
        }

        // 添加菜单项的属性
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
     
        if ($item->object == 'post_tag') {
            // 如果是标签菜单项
            $tags = get_term_by('id', $item->object_id, 'post_tag');
            $attributes .= ' value="' . $tags->term_id . '"';
            $attributes .= ' name="tag"';
        } else {
            // 如果是分类菜单项
            $attributes .= ' value="' . $item->object_id . '"';
            $attributes .= ' name="cat"';
        }

        // 生成菜单项的输出
        $item_output = $args->before;

        if ($depth == 0) {
            // For top-level items, place the label inside <h3>
            $item_output .= apply_filters( 'the_title', $item->title, $item->ID );
        } else {
            // For sub-items, place the label inside <li>
            $item_output .= '<label><input' . $attributes . ' type="checkbox" title="' . apply_filters( 'the_title', $item->title, $item->ID ) . '"><span>';
            $item_output .= apply_filters( 'the_title', $item->title, $item->ID );
            $item_output .= '</span></label>';
        }

        $item_output .= $args->link_before . $args->link_after;

        if ($depth == 0) {
            // 结束 <h3> 标签
            $item_output .= '</h3>';
        }

        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id);
    }

    // 结束渲染菜单项
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $indent = ($depth) ? str_repeat("\t", $depth) : ''; // 缩进
        $output .= $indent . '</li>' . "\n"; // 结束 <li> 标签
    }

    // 结束渲染子菜单（即二级菜单）
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth); // 缩进
        $output .= $indent . '</ul>' . "\n"; // 结束子菜单的 <ul> 标签
    }
}

