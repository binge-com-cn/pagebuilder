<?php
/** Template Name: Fluffy · 品牌首页（主题头尾） */
if (!defined('ABSPATH')) { exit; }
get_header();
while (have_posts()) { the_post(); the_content(); }
get_footer();
