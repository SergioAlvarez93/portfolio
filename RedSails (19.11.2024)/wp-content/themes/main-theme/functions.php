<?php

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('fancybox', get_template_directory_uri() . '/css/fancybox.css', [], '5.0.36');
    wp_enqueue_style('swiper', get_template_directory_uri() . '/css/swiper-bundle.min.css', [], '11.1.0');
    wp_enqueue_style('style-min', get_template_directory_uri() . '/css/style.min.css', [], filemtime(TEMPLATEPATH . '/css/style.min.css'));
    wp_enqueue_style('responsive-min', get_template_directory_uri() . '/css/responsive.min.css', [], filemtime(TEMPLATEPATH . '/css/responsive.min.css'));
    wp_enqueue_script('fancybox-js', get_template_directory_uri() . '/js/fancybox.umd.js', array(), false, true);
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/js/swiper-bundle.min.js', array(), false, true);
    wp_enqueue_script('imask-js', get_template_directory_uri() . '/js/imask.js', array(), false, true);
    wp_enqueue_script('main-js', get_template_directory_uri() . '/js/main.js', array(), false, true);
});

add_action('after_setup_theme', 'theme_register_nav_menu');

function theme_register_nav_menu()
{
    register_nav_menu('Navigation', 'Навигация');
}

add_filter('upload_mimes', 'svg_upload_allow');

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

remove_filter('the_content', 'wpautop');
add_filter('wpcf7_autop_or_not', '__return_false');

function custom_cf7_submit_button($content)
{
    $content = preg_replace(
        '/<input[^>]+type="submit"[^>]*>/i',
        '<button type="submit" class="wpcf7-form-control wpcf7-submit has-spinner form-submit">submit</button>',
        $content
    );

    return $content;
}
add_filter('wpcf7_form_elements', 'custom_cf7_submit_button');


add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('custom-logo');
