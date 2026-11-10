<?php
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('sal', get_template_directory_uri() . '/css/sal.css', [], '1.0');
    wp_enqueue_style('swiper', get_template_directory_uri() . '/css/swiper-bundle.min.css', [], '11.1.0');
    wp_enqueue_style('style-min', get_template_directory_uri() . '/css/style.min.css', [], filemtime(TEMPLATEPATH . '/css/style.min.css'));
    wp_enqueue_style('responsive-min', get_template_directory_uri() . '/css/responsive.min.css', [], filemtime(TEMPLATEPATH . '/css/responsive.min.css'));
    wp_enqueue_script('sal-js', get_template_directory_uri() . '/js/sal.js', array(), false, true);
    wp_enqueue_script('vk-js', 'https://vk.com/js/api/openapi.js?168', array(), false, true);
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/js/swiper-bundle.min.js', array(), false, true);
    wp_enqueue_script('imask-js', get_template_directory_uri() . '/js/imask.js', array(), false, true);
    wp_enqueue_script('main', get_template_directory_uri() . '/js/main.min.js', array(), false, true);
    $telegram = get_field('telegram', 'option');
    wp_localize_script('main', 'main_vars', array(
        'telegram' => $telegram
    ));
});


add_action('after_setup_theme', 'theme_register_nav_menu');

function theme_register_nav_menu()
{
    register_nav_menu('Navigation', 'Навигация');
}

function add_custom_class_to_menu_li($classes, $item, $args, $depth)
{
    $custom_class = 'navbar-flexbox__item';

    $classes[] = $custom_class;

    return $classes;
}

add_filter('nav_menu_css_class', 'add_custom_class_to_menu_li', 10, 4);

function add_custom_class_to_menu_links($atts, $item, $args)
{
    if (!isset($atts['class'])) {
        $atts['class'] = 'navbar-flexbox__item_link scroll';
    } else {
        $atts['class'] .= ' navbar-flexbox__item_link scroll';
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_custom_class_to_menu_links', 10, 3);

function svg_upload_allow($mimes)
{
    $mimes['svg']  = 'image/svg+xml';

    return $mimes;
}

add_filter('wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5);

function fix_svg_mime_type($data, $file, $filename, $mimes, $real_mime = '')
{

    if (version_compare($GLOBALS['wp_version'], '5.1.0', '>='))
        $dosvg = in_array($real_mime, ['image/svg', 'image/svg+xml']);
    else
        $dosvg = ('.svg' === strtolower(substr($filename, -4)));

    if ($dosvg) {

        if (current_user_can('manage_options')) {

            $data['ext']  = 'svg';
            $data['type'] = 'image/svg+xml';
        } else {
            $data['ext'] = $type_and_ext['type'] = false;
        }
    }

    return $data;
}

function disable_wpautop_for_acf_wysiwyg()
{
    remove_filter('acf_the_content', 'wpautop');
    remove_filter('acf_the_content', 'shortcode_unautop');
}
add_action('acf/init', 'disable_wpautop_for_acf_wysiwyg');

add_filter('wpcf7_form_elements', function ($content) {
    $content = preg_replace('/<br[^>]*>/i', '', $content);
    $content = preg_replace('/<p[^>]*>/', '', $content);
    $content = str_replace('</p>', '', $content);
    return $content;
});

add_action('admin_menu', function () {
    remove_menu_page(menu_slug: 'edit.php');
    remove_menu_page(menu_slug: 'edit-comments.php');
});

add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('custom-logo');
