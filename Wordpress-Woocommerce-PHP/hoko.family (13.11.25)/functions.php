<?php

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('fancybox', get_template_directory_uri() . '/css/fancybox.css', [], '5.0.36');
    wp_enqueue_style('swiper', get_template_directory_uri() . '/css/swiper-bundle.min.css', [], '11.1.0');
    wp_enqueue_style('style-min', get_template_directory_uri() . '/css/style.min.css', [], filemtime(TEMPLATEPATH . '/css/style.min.css'));
    wp_enqueue_style('responsive-min', get_template_directory_uri() . '/css/responsive.min.css', [], filemtime(TEMPLATEPATH . '/css/responsive.min.css'));
    wp_enqueue_script('fancybox-js', get_template_directory_uri() . '/js/fancybox.umd.js', [], '5.0.36', true);
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/js/swiper-bundle.min.js', [], '11.1.0', true);
    wp_enqueue_script('imask-js', get_template_directory_uri() . '/js/imask.js', [], false, true);
    wp_enqueue_script('main_js', get_template_directory_uri() . '/js/main.js', ['jquery'], filemtime(TEMPLATEPATH . '/js/main.js'), true);
});

add_action('after_setup_theme', 'theme_register_nav_menu');

function theme_register_nav_menu()
{
    register_nav_menu('Navigation', 'Навигация');
    register_nav_menu('Footer', 'Футер');
}

function add_custom_class_to_menu_li($classes, $item, $args, $depth)
{
    $custom_class = 'navigation__item';

    $classes[] = $custom_class;

    return $classes;
}

add_filter('nav_menu_css_class', 'add_custom_class_to_menu_li', 10, 4);

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

add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('custom-logo');


add_action('wp', function () {
    // Создаём новый кастомный action для изображения товара витрины
    add_action('custom_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
    add_action('custom_before_shop_loop_item_title', 'custom_template_loop_product_thumbnail', 10);
});

// Кастомная функция для миниатюры в
function custom_template_loop_product_thumbnail()
{
    global $product;

    // Получаем URL изображения
    $image = get_field('showcase_img')['url'];
    $alt_text = get_field('showcase_img')['alt'];

    if ($image) {
        echo '<img src="' . $image . '" class="showcase-list__img" alt="' . $alt_text . '" decoding="async" fetchpriority="high">';
    }
}

// Переносим хуки из wp в глобальный контекст
add_action('custom_before_shop_loop_item_title_market', 'woocommerce_show_product_loop_sale_flash', 10);
add_action('custom_before_shop_loop_item_title_market', 'custom_template_loop_product_thumbnail_market', 10);

function custom_template_loop_product_thumbnail_market()
{
    global $product;

    if (!is_a($product, 'WC_Product')) {
        error_log('Product not available in custom_template_loop_product_thumbnail_market for ID: ' . get_the_ID());
        return;
    }

    $image_id = $product->get_image_id();
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
    $alt_text = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : $product->get_name();

    if ($image_url) {
        echo '<img src="' . esc_url($image_url) . '" class="market-list__img" alt="' . esc_attr($alt_text) . '" decoding="async" fetchpriority="high">';
    } else {
        error_log('No image found for product ID: ' . $product->get_id());
    }
}

// Создаём новый кастомный action для вывода метатега для каждого отдельного продукта
add_action('custom_show__product_meta', 'bbloomer_show_tags_again_single_product', 10);

function bbloomer_show_tags_again_single_product()
{
    global $product;
    // Получаем строку с метками
    $tag_list = wc_get_product_tag_list($product->get_id());

    // Если метки есть, обрабатываем их
    if ($tag_list) {
        // Удаляем все ссылки и лишние HTML-теги, оставляя только текст
        $clean_tags = strip_tags($tag_list);
        // Разделяем метки по запятой и берем первую (если нужно только одну)
        $tags_array = explode(', ', $clean_tags);
        $first_tag = $tags_array[0];

        // Выводим только значение метки
        echo esc_html($first_tag);
    }
}

// Вывод кнопок категорий
function display_product_category_filter()
{
    // Защита от повторного вызова
    static $called = false;
    if ($called) {
        error_log('display_product_category_filter already called');
        return;
    }
    $called = true;

    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
        // Исключаем категорию по умолчанию, если она есть
        'exclude' => get_option('default_product_cat'), // ID категории по умолчанию
    ]);


    if (is_wp_error($categories) || empty($categories)) {
        error_log('No categories found or error: ' . print_r($categories, true));
        return;
    }

    // Кнопка "Все продукты" с миниатюрой категории по умолчанию
    $default_cat_id = get_option('default_product_cat');
    $default_image_id = $default_cat_id ? get_term_meta($default_cat_id, 'thumbnail_id', true) : 0;
    $default_image_url = '';
    $default_image_alt = wp_get_attachment_caption($default_image_id);

    if ($default_image_id) {
        $image_src = wp_get_attachment_image_src($default_image_id, 'full');
        $default_image_url = $image_src ? $image_src[0] : '';
    }

    echo '<div class="swiper-slide filter-btn filter-active" data-category="all">';
    if ($default_image_url) {
        echo '<img src="' . esc_url($default_image_url) . '" class="filter-swiper__img" alt="' . $default_image_alt . '">';
    }
    echo '<button class="cat-filter-btn" data-category="all">';
    echo 'ВСЕ ПРОДУКТЫ</button>';
    echo '</div>';

    foreach ($categories as $category) {
        // Пропускаем, если категория совпадает с "all" или является категорией по умолчанию
        if ($category->slug === 'all' || $category->term_id == get_option('default_product_cat')) {
            continue;
        }

        $image_id = get_term_meta($category->term_id, 'thumbnail_id', true);
        $image_alt = wp_get_attachment_caption($image_id);
        $image_url = '';
        if ($image_id) {
            $image_src = wp_get_attachment_image_src($image_id, 'full');
            $image_url = $image_src ? $image_src[0] : '';
        }

        echo '<div class="swiper-slide filter-btn" data-category="' . esc_attr($category->slug) . '">';
        if ($image_url) {
            echo '<img src="' . esc_url($image_url) . '" class="filter-swiper__img" alt="' . $image_alt . '">';
        }
        echo '<button class="cat-filter-btn" data-category="' . esc_attr($category->slug) . '">';
        echo esc_html($category->name) . '</button>';
        echo '</div>';
    }
}
add_action('before_market_loop', 'display_product_category_filter', 10);

// Подключение скриптов
function enqueue_filter_scripts()
{
    wp_enqueue_script('market-filter', get_template_directory_uri() . '/js/market-filter.js', ['jquery'], null, true);
    wp_localize_script('market-filter', 'marketFilter', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('filter_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_filter_scripts');

// Обработка AJAX
add_action('wp_ajax_filter_products', 'handle_product_filter');
add_action('wp_ajax_nopriv_filter_products', 'handle_product_filter');

function handle_product_filter()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'filter_nonce')) {
        wp_send_json_error('Security check failed', 403);
    }

    $category_slug = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';

    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'order' => 'ASC',
    ];

    if ($category_slug !== 'all') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $category_slug,
            ],
        ];
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    } else {
        echo '<p>Товары не найдены</p>';
    }
    wp_reset_postdata();

    wp_send_json_success(ob_get_clean());
}

// Подключаем модалку для заказа товаров

add_action('wp_ajax_get_product_modal_data', 'get_product_modal_data');
add_action('wp_ajax_nopriv_get_product_modal_data', 'get_product_modal_data');

function get_product_modal_data()
{
    if (!wp_verify_nonce($_POST['nonce'], 'market_ajax_nonce')) {
        wp_send_json_error('Security failed');
    }

    $product_id = intval($_POST['product_id']);
    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error('Product not found');
    }

    ob_start();
?>
    <div class="modal-product-flexbox">
        <div class="modal-product-flexbox-gallery">
            <?php
            $images = get_field('product_gallery', $product_id);
            $currBigImgLink = null;
            $currBigImgAlt = null;
            if ($images) { ?>
                <?php
                $currBigImgLink = $images[0]["url"];
                $currBigImgAlt = $images[0]["alt"];
                ?>
                <div class="modal-product-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image) { ?>
                            <div class="swiper-slide modal-product-slider__slide">
                                <img src="<?php echo $image['sizes']['large']; ?>" class="modal-product-slider__img" alt="<?php echo $image['alt']; ?>" />
                            </div>
                        <?php } ?>
                    </div>
                    <div class="modal-product-slider__next-btn">
                        <img class="modal-product-slider__next-btn_arrow-down" src="<?= get_template_directory_uri() ?>/img/arrow-down.svg" alt="">
                    </div>
                </div>
            <?php } ?>
            <?php if ($value = $currBigImgLink) { ?>
                <div class="modal-product-flexbox-gallery__main">
                    <img src="<?= $value; ?>" class="modal-product-flexbox-gallery__main-item" alt="<?= $currBigImgAlt; ?>">
                </div>
            <?php } ?>
        </div>
        <div class="modal-product-flexbox-content">
            <div class="modal-product-flexbox-content__title"><?php echo $product->get_name(); ?></div>
            <?php
            $attributes = $product->get_attributes();
            if (!empty($attributes)) { ?>
                <ul class="modal-product-flexbox-content-list">
                    <?php foreach ($attributes as $attribute) {
                        $label = wc_attribute_label($attribute->get_name());
                        $terms = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
                    ?>
                        <li class="modal-product-flexbox-content-list__item">
                            <div class="modal-product-flexbox-content-list__item-attribute-name"><?= $label . ': ' ?></div>
                            <div class="modal-product-flexbox-content-list__item-attribute-value"><?= (!empty($terms) ? implode(', ', $terms) : 'Не указано') ?></div>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <?php if ($product->post->post_content) { ?>
                <div class="modal-product-flexbox-content__descr">
                    <?= $product->post->post_content; ?>
                </div>
            <?php } ?>
            <?php if (get_field('ozon_link', $product_id) || get_field('wildberries_link', $product_id)) { ?>
                <div class="modal-product-flexbox-content__links">
                    <span class="modal-product-flexbox-content__links-title">КУПИТЬ:</span>
                    <div class="modal-product-flexbox-content__links-box">
                        <?php if ($ozon = get_field('ozon_link', $product_id)) { ?>
                            <a class="ozon-btn" href="<?= $ozon['url']; ?>" target="_blank">OZON</a>
                        <?php } ?>
                        <?php if ($wildberries = get_field('wildberries_link', $product_id)) { ?>
                            <a class="wildberries-btn" href="<?= $wildberries['url']; ?>" target="_blank">WILDBERRIES</a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php
    wp_send_json_success(ob_get_clean());
}

wp_enqueue_script('product-modal', get_template_directory_uri() . '/js/product-modal.js', ['jquery'], null, true);
wp_localize_script('product-modal', 'market_ajax', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('market_ajax_nonce')
]);


// Отключаем автоматические проверки обновлений для ядра, плагинов и тем
add_filter('auto_update_theme', '__return_false');
add_filter('auto_update_plugin', '__return_false');
add_filter('auto_update_core', '__return_false');

// Отключаем проверки обновлений при загрузке админки и фронтенда
add_filter('automatic_updater_disabled', '__return_true');
// Убираем планировщик задач для проверки обновлений
add_action('init', 'stop_auto_updates_initialization');
function stop_auto_updates_initialization()
{
    remove_action('init', 'wp_schedule_update_checks');
}
// Более агрессивное отключение проверок через cron
add_action('wp_version_check', 'stop_auto_updates_initialization', 1);
add_action('wp_update_plugins', 'stop_auto_updates_initialization', 1);
add_action('wp_update_themes', 'stop_auto_updates_initialization', 1);


// Полное отключение телеметрии и аналитики WooCommerce
add_filter('woocommerce_admin_disabled', '__return_true');
add_filter('woocommerce_allow_marketplace_suggestions', '__return_false');

// Отключаем WooCommerce Tracker
add_filter('woocommerce_tracker_enabled', '__return_false');

// Отключаем все внешние подключения WooCommerce
add_filter('woocommerce_helper_suppress_admin_notices', '__return_true');
add_action('admin_init', 'disable_woocommerce_telemetry');
function disable_woocommerce_telemetry()
{
    remove_action('admin_enqueue_scripts', 'wc_admin_register_scripts');
    remove_action('admin_enqueue_scripts', 'wc_admin_register_scripts');
}
