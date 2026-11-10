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

add_filter('rest_authentication_errors', function($result) {
    $secret = $_SERVER['HTTP_X_API_KEY'] ?? '';

    if ($secret === 'aJ7kL2mN9pQrS4uV8wXyZ1bC3dE5fG6hI0jKlMnOpQrStUvWxYz') {
        return $result;
    }

    if (!current_user_can('administrator')) {
        return new WP_Error('rest_forbidden', 'Нет ключа', ['status' => 401]);
    }

    return $result;
});

remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');

add_filter('tiny_mce_before_init', function($init) {
    $init['forced_root_block'] = false;
    $init['force_br_newlines'] = false;
    $init['force_p_newlines'] = false;
    return $init;
});

add_filter('acf/format_value/type=wysiwyg', function($value) {
    return $value;
}, 10, 1);

add_filter('acf_the_content', function($value) {
    return $value;
}, 10, 1);

add_filter('acf/format_value', function($value, $post_id, $field) {
    if (is_string($value) && strpos($value, '&lt;') !== false) {
        $value = html_entity_decode($value);
    }
    return $value;
}, 20, 3);

add_action('rest_api_init', function() {
    register_rest_field('post', 'acf_raw', [
        'get_callback' => function($post) {
            $fields = get_fields($post['id'], false);     
            return $fields;
        },
        'schema' => null,
    ]);
});

function revalidate_nextjs($path = '/') {
    $secret = '0589ea0d-1a31-4827-ba00-e5c9ce87c4ff';
    $url = 'https://anyprint.pro/api/revalidate?secret=' . $secret . '&path=' . $path;
    wp_remote_get($url);
}

add_action('save_post', function($post_id, $post, $update) {
    if (wp_is_post_revision($post_id) || $post->post_status !== 'publish') return;

    $path = '/';
    if ($post->post_type === 'page' && $post->post_name !== 'sample-page') {
        $path = '/' . $post->post_name;
    } elseif ($post->post_type === 'post') {
        $path = '/blog/' . $post->post_name;
    }

    revalidate_nextjs($path);
}, 10, 3);

add_action('acf/save_post', function($post_id) {
    if ($post_id === 'options') {
        revalidate_nextjs('/');
    }
}, 20);

add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('custom-logo');
