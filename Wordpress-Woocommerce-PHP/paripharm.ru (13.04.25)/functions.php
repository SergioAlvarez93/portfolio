<?php

add_action('wp_enqueue_scripts', function () {
    wp_deregister_script('jquery');
    wp_enqueue_style('fancybox', get_template_directory_uri() . '/css/fancybox.css', [], '5.0.36');
    wp_enqueue_style('swiper', get_template_directory_uri() . '/css/swiper-bundle.min.css', [], '11.1.0');
    wp_enqueue_style('style-min', get_template_directory_uri() . '/css/style.min.css', [], filemtime(TEMPLATEPATH . '/css/style.min.css'));
    wp_enqueue_style('responsive-min', get_template_directory_uri() . '/css/responsive.min.css', [], filemtime(TEMPLATEPATH . '/css/responsive.min.css'));
    wp_enqueue_script('fancybox-js', get_template_directory_uri() . '/js/fancybox.umd.js', array(), false, true);
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/js/swiper-bundle.min.js', array(), false, true);
    wp_enqueue_script('imask-js', get_template_directory_uri() . '/js/imask.js', array(), false, true);
    wp_enqueue_script('main-js', get_template_directory_uri() . '/js/main.js', array(), false, true);
}, 100);

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

function add_custom_class_to_menu_li($classes, $item, $args, $depth)
{
    $custom_class = 'navigation__item';

    $classes[] = $custom_class;

    return $classes;
}

add_filter('nav_menu_css_class', 'add_custom_class_to_menu_li', 10, 4);

function add_custom_class_to_specific_menu_link($atts, $item, $args, $depth)
{
    if ($item->ID == 28) {
        $custom_class = 'scroll';

        if (isset($atts['class'])) {
            $atts['class'] .= ' ' . $custom_class;
        } else {
            $atts['class'] = $custom_class;
        }
    }

    return $atts;
}

add_filter('nav_menu_link_attributes', 'add_custom_class_to_specific_menu_link', 10, 4);

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

function send_to_bitrix24($contact_form)
{
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        $posted_data = $submission->get_posted_data();

        $name = isset($posted_data['text-261']) ? $posted_data['text-261'] : '';
        $phone = isset($posted_data['tel-578']) ? $posted_data['tel-578'] : '';
        $message = isset($posted_data['textarea-679']) ? $posted_data['textarea-679'] : '';

        $webhook_url = 'https://biokord.bitrix24.ru/rest/1850/5o0ati2wlrczgakg/';

        $data = [
            'fields' => [
                'TITLE' => 'Заявка с сайта paripharm.ru',
                'NAME' => $name,
                'PHONE' => [['VALUE' => $phone, 'VALUE_TYPE' => 'WORK']],
                'COMMENTS' => $message,
            ],
            'params' => ['REGISTER_SONET_EVENT' => 'Y'],
        ];

        $response = wp_remote_post($webhook_url . 'crm.lead.add', [
            'body' => json_encode($data),
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        if (is_wp_error($response)) {
            error_log('Ошибка отправки данных в Битрикс24: ' . $response->get_error_message());
        } else {
            error_log('Данные успешно отправлены в Битрикс24: ' . print_r($response, true));
        }
    }
}

add_action('wpcf7_mail_sent', 'send_to_bitrix24');


add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('custom-logo');
