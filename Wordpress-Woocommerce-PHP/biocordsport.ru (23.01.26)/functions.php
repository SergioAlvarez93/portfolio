<?php

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('fancybox', get_template_directory_uri() . '/css/fancybox.css', [], '5.0.36');
    wp_enqueue_style('swiper', get_template_directory_uri() . '/css/swiper-bundle.min.css', [], '11.1.0');
    wp_enqueue_style('style-min', get_template_directory_uri() . '/css/style.css', [], filemtime(TEMPLATEPATH . '/css/style.css'));
    wp_enqueue_style('responsive-min', get_template_directory_uri() . '/css/responsive.css', [], filemtime(TEMPLATEPATH . '/css/responsive.css'));
    wp_enqueue_script('fancybox-js', get_template_directory_uri() . '/js/fancybox.umd.js', array(), false, true);
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/js/swiper-bundle.min.js', array(), false, true);
    wp_enqueue_script('imask-js', get_template_directory_uri() . '/js/imask.js', array(), false, true);
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

// ДОБАВЛЕНИЕ КАСТОМНЫХ ПОЛЕЙ ACF В ВАРИАЦИИ WOOCOMMERCE

add_action('woocommerce_product_after_variable_attributes', function ($loop, $variation_data, $variation) {
    $fields = acf_get_fields('group_69e9c34477a7b');
    if (!$fields) return;
    
    echo '<div class="custom-variation-fields" style="padding:15px; background:#f9f9f9; margin:10px 0; border-radius:4px;">';
    acf_form_data(['post_id' => $variation->ID, 'form' => false]);
    
    foreach ($fields as $field) {
        $field['prefix'] = 'acf[' . $variation->ID . ']';
        $field['value'] = get_field($field['name'], $variation->ID, false);
        
        echo '<div style="margin-bottom:15px;">';
        echo '<label style="font-weight:600; display:block; margin-bottom:5px;">' . $field['label'] . '</label>';
        
        if ($field['type'] === 'gallery') {
            $value = $field['value'] ?: [];
            echo '<div class="acf-gallery-wrap" data-var="' . $variation->ID . '" data-field="' . $field['key'] . '">';
            
            if (!empty($value)) {
                foreach ((array)$value as $img_id) {
                    echo '<input type="hidden" name="acf[' . $variation->ID . '][' . $field['key'] . '][]" value="' . $img_id . '">';
                }
            } else {
                echo '<input type="hidden" name="acf[' . $variation->ID . '][' . $field['key'] . '][]" value="">';
            }
            
            echo '<button type="button" class="button gallery-upload">📷 Выбрать изображения</button>';
            echo '<button type="button" class="button gallery-clear" style="margin-left:5px;">🗑️ Очистить</button>';
            echo '<div class="gallery-preview" style="margin-top:10px; display:flex; flex-wrap:wrap; gap:5px;">';
            foreach ((array)$value as $img_id) {
                echo wp_get_attachment_image($img_id, 'thumbnail', false, ['style' => 'width:70px; height:70px; object-fit:cover; border-radius:3px;']);
            }
            echo '</div></div>';
        } else {
            acf_render_field($field);
        }
        
        echo '</div>';
    }
    echo '</div>';
}, 10, 3);

add_action('woocommerce_save_product_variation', function ($variation_id) {
    if (isset($_POST['acf'][$variation_id])) {
        $global = $_POST['acf'];
        $data = $_POST['acf'][$variation_id];
        
        foreach ($data as $key => $value) {
            if (is_array($value) && isset($value[0]) && $value[0] === '') {
                $data[$key] = [];
            }
        }
        
        $_POST['acf'] = $data;
        acf_save_post($variation_id);
        $_POST['acf'] = $global;
    }
});

add_action('admin_footer', function () {
    if (get_post_type() !== 'product') return;
    ?>
    <script>
    jQuery($ => {
        $(document).on('click', '.gallery-upload', function(e) {
            e.preventDefault();
            const $wrap = $(this).closest('.acf-gallery-wrap');
            const varId = $wrap.data('var');
            const fieldKey = $wrap.data('field');
            
            const frame = wp.media({
                title: 'Выберите изображения',
                button: { text: 'Выбрать' },
                multiple: true
            });
            
            frame.on('select', () => {
                const ids = frame.state().get('selection').map(m => m.id);
                $wrap.find('input[type="hidden"]').remove();
                ids.forEach(id => {
                    $wrap.prepend('<input type="hidden" name="acf[' + varId + '][' + fieldKey + '][]" value="' + id + '">');
                });
                
                $wrap.find('.gallery-preview').html(ids.map(id => {
                    const thumb = wp.media.attachment(id).get('sizes')?.thumbnail?.url || '';
                    return '<img src="' + thumb + '" style="width:70px; height:70px; object-fit:cover; border-radius:3px;">';
                }).join(''));
                
                $('#variable_product_options').trigger('woocommerce_variations_input_changed');
                $wrap.closest('.woocommerce_variation').addClass('variation-needs-update');
                $('button.save-variation-changes').removeAttr('disabled');
            });
            
            frame.open();
        });
        
        $(document).on('click', '.gallery-clear', function(e) {
            e.preventDefault();
            const $wrap = $(this).closest('.acf-gallery-wrap');
            const varId = $wrap.data('var');
            const fieldKey = $wrap.data('field');
            
            $wrap.find('input[type="hidden"]').remove();
            $wrap.prepend('<input type="hidden" name="acf[' + varId + '][' + fieldKey + '][]" value="">');
            $wrap.find('.gallery-preview').empty();
            
            $('#variable_product_options').trigger('woocommerce_variations_input_changed');
            $wrap.closest('.woocommerce_variation').addClass('variation-needs-update');
            $('button.save-variation-changes').removeAttr('disabled');
        });
    });
    </script>
    <?php
});

// *****************************************************

// ДОБАВЛЯЕМ ШОРТКОД ДЛЯ ВИТРИНЫ
function showcase_products_shortcode()
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'showcase',
                'value' => '1',
                'compare' => '='
            )
        )
    );
    $query = new WP_Query($args);
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    }
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('showcase_products', 'showcase_products_shortcode');

// ФУНКЦИЯ ПРОВЕРКИ НАЛИЧИЯ ТОВАРОВ ДЛЯ ВИТРИНЫ
function has_showcase_products()
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => 'showcase',
                'value' => '1',
                'compare' => '='
            )
        ),
        'fields' => 'ids'
    );
    $query = new WP_Query($args);
    return $query->have_posts();
}

// ФУНКЦИЯ ПРОВЕРКИ НАЛИЧИЯ ТОВАРОВ В ПРИНЦИПЕ
function has_any_products()
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 1,
        'fields' => 'ids'
    );
    $query = new WP_Query($args);
    return $query->have_posts();
}

// КАСТОМНЫЙ ЭКШН ДЛЯ ВЫВОДА ПОЛНОГО РАЗМЕРА ИЗОБРАЖЕНИЯ
function custom_woocommerce_before_shop_loop_item_title_full($arg)
{
    global $product;
    do_action('woocommerce_show_product_loop_sale_flash');

    $image_id = $product->get_image_id();
    if ($image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        if ($arg === 'market-list-img') {
            echo '<div class="' . $arg  . '__wrap"><img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="' . $arg . '" /></div>';
        } else {
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="' . $arg . '" />';
        }
    }
}

$showcaseClass = 'showcase-product-img-box__img';
add_action('woocommerce_before_shop_loop_item_title_showcase', function () use ($showcaseClass) {
    custom_woocommerce_before_shop_loop_item_title_full($showcaseClass);
});
$marketClass = 'market-list-img';
add_action('woocommerce_before_shop_loop_item_title_market', function () use ($marketClass) {
    custom_woocommerce_before_shop_loop_item_title_full($marketClass);
});

// ВЫВОД КНОПОК КАТЕГОРИЙ (ФИЛЬТР)

function display_product_category_filter()
{
    static $called = false;
    if ($called) return;
    $called = true;

    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
        'exclude' => get_option('default_product_cat'),
    ]);

    if (is_wp_error($categories) || empty($categories)) return;

    echo '<div class="swiper-slide filter-btn filter-active" data-category="all">';
    echo '<div class="cat-filter-btn__wrap"><a class="cat-filter-btn" data-category="all" data-content="ВСЕ ПРОДУКТЫ">';
    echo 'ВСЕ ПРОДУКТЫ</a></div>';
    echo '</div>';

    foreach ($categories as $category) {
        if ($category->slug === 'all' || $category->term_id == get_option('default_product_cat')) continue;
        echo '<div class="swiper-slide filter-btn" data-category="' . esc_attr($category->slug) . '">';
        echo '<div class="cat-filter-btn__wrap"><a class="cat-filter-btn" data-category="' . esc_attr($category->slug) . '" data-content="' . esc_attr($category->name) . '">';
        echo esc_html($category->name) . '</a></div>';
        echo '</div>';
    }
}
add_action('before_market_loop', 'display_product_category_filter', 10);

// Подключение скриптов фильтра
function enqueue_filter_scripts()
{
    wp_enqueue_script('market-filter', get_template_directory_uri() . '/js/market-filter.js', ['jquery'], null, true);
    wp_localize_script('market-filter', 'marketFilter', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('filter_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_filter_scripts');

// Обработка AJAX фильтра
add_action('wp_ajax_filter_products', 'handle_product_filter');
add_action('wp_ajax_nopriv_filter_products', 'handle_product_filter');

function handle_product_filter()
{
    check_ajax_referer('filter_nonce', 'nonce');

    $category_slug = $_POST['category'] ?? 'all';
    $per_page = !empty($_POST['per_page']) ? intval($_POST['per_page']) : 3;

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ];

    if ($category_slug !== 'all') {
        $args['tax_query'] = [[
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $category_slug,
        ]];
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'market');
        }
    } else {
        echo '<p>Товары не найдены</p>';
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    $total = $query->found_posts;

    $button = ($total > $per_page)
        ? '<div class="market-load-more button" data-loaded="' . $per_page . '" data-total="' . $total . '" data-category="' . esc_attr($category_slug) . '">Показать ещё</div>'
        : '';

    wp_send_json_success([
        'html'   => $html,
        'button' => $button,
    ]);
}

// ПОДКЛЮЧАЕМ LOAD MORE — ЭТОТ ХЭНДЛЕР ОСТАЛСЯ, НО ТЕПЕРЬ ОН УЧИТЫВАЕТ КАТЕГОРИЮ

add_action('wp_ajax_market_loadmore', 'market_loadmore_handler');
add_action('wp_ajax_nopriv_market_loadmore', 'market_loadmore_handler');

function market_loadmore_handler()
{
    check_ajax_referer('market_loadmore_nonce', 'nonce');

    $offset = intval($_POST['offset'] ?? 0);
    $limit  = intval($_POST['limit'] ?? 3);
    $category = $_POST['category'] ?? 'all'; // ← ГЛАВНОЕ: ЧИТАЕМ КАТЕГОРИЮ

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'offset'         => $offset,
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ];

    if ($category !== 'all' && !empty($category)) {
        $args['tax_query'] = [[
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $category,
        ]];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'market');
        }
    }

    wp_die();
}

// Подключение скрипта load more
add_action('wp_enqueue_scripts', 'market_load_more_enqueue');
function market_load_more_enqueue()
{
    wp_enqueue_script('market-load-more', get_template_directory_uri() . '/js/market-load-more.js', [], '1.1', true);
    wp_localize_script('market-load-more', 'marketLoadMore', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('market_loadmore_nonce')
    ]);
}

// *****************************************************************************

// ПОДКЛЮЧАЕМ МОДАЛКУ ДЛЯ ЗАКАЗА ТОВАРОВ

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

    $variations = $product->get_available_variations();
    $default_variation_id = $variations[0]['variation_id'] ?? null;
    
    $variations_data = [];
    
    foreach ($variations as $index => $variation) {
        $var_id = $variation['variation_id'];
        $attrs = [];
        
        foreach ($variation['attributes'] as $attr_name => $attr_value) {
            $clean_name = str_replace('attribute_', '', $attr_name);
            $attrs[$clean_name] = $attr_value;
        }
        
        $variations_data[] = [
            'id' => $var_id,
            'index' => $index,
            'attributes' => $attrs,
            'ozon' => get_field('variations_ozon', $var_id) ?: '',
            'wildberries' => get_field('variations_wildberries', $var_id) ?: '',
            'gallery' => get_field('variations_gallery', $var_id) ?: [],
        ];
    }

    $gallery = get_field('variations_gallery', $default_variation_id) ?: [];
    $ozon = get_field('variations_ozon', $default_variation_id) ?: '';
    $wb = get_field('variations_wildberries', $default_variation_id) ?: '';

    $currBigImgLink = null;
    $currBigImgAlt = null;
    
    if (!empty($gallery)) {
        $gallery = array_reverse($gallery);
        $currBigImgLink = $gallery[0]['url'];
        $currBigImgAlt = $gallery[0]['alt'];
    }

    ob_start();
?>
    <div class="modal-product-flexbox" data-product-id="<?= $product_id; ?>">
        <div class="modal-product-flexbox-gallery" data-gallery-container>
            <?php if (!empty($gallery)) { ?>
                <div class="modal-product-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($gallery as $image) { ?>
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
            <?php if ($currBigImgLink) { ?>
                <div class="modal-product-flexbox-gallery__main">
                    <img src="<?= $currBigImgLink; ?>" class="modal-product-flexbox-gallery__main-item" alt="<?= $currBigImgAlt; ?>">
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
                        <?php if ($label === 'Количество капсул' || $label === 'Активное вещество' || $label === 'Масса нетто') { ?>
                            <li class="modal-product-flexbox-content-list__item">
                                <div class="modal-product-flexbox-content-list__item-attribute-name"><?= $label . ': ' ?></div>
                                <div class="modal-product-flexbox-content-list__item-attribute-value">
                                    <?php 
                                    if (!empty($terms)) {
                                        $count = count($terms);
                                        foreach ($terms as $index => $term): 
                                            $parts = explode(' ', $term, 2);
                                            $number = $parts[0];
                                            
                                            if (($label === 'Количество капсул' || $label === 'Масса нетто') && $count > 1) {
                                                $activeClass = ($index == $variation_index) ? ' variation-option__active' : '';
                                                echo '<span class="variation-option' . $activeClass . '" data-variation-index="' . $index . '" data-attribute-value="' . esc_attr($term) . '">' . $number . '</span>';
                                            } else {
                                                echo '<span data-attribute-value="' . esc_attr($term) . '">' . $number . '</span>';
                                            }
                                            
                                            if ($index < $count - 1) {
                                                echo ' | ';
                                            } else {
                                                echo ' ' . $parts[1];
                                            }
                                        endforeach;
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            <?php } ?>
            <?php if ($value = get_field('product_components_and_recomendations', $product_id)) { ?>
                <div class="modal-product-flexbox-content__descr">
                    <?= $value; ?>
                </div>
            <?php } ?>
            <div class="modal-product-flexbox-content__links">
                <span class="modal-product-flexbox-content__links-title">КУПИТЬ:</span>
                <div class="modal-product-flexbox-content__links-box" data-links-container>
                    <?php if ($ozon) { ?>
                        <a class="ozon-btn" href="<?= esc_url($ozon); ?>" target="_blank">OZON</a>
                    <?php } ?>
                    <?php if ($wb) { ?>
                        <a class="wildberries-btn" href="<?= esc_url($wb); ?>" target="_blank">WILDBERRIES</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <script id="variations-data" type="application/json">
        <?= json_encode($variations_data, JSON_UNESCAPED_UNICODE); ?>
    </script>
<?php
    wp_send_json_success(ob_get_clean());
}

wp_enqueue_script('product-modal', get_template_directory_uri() . '/js/product-modal.js', ['jquery'], null, true);
wp_localize_script('product-modal', 'market_ajax', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('market_ajax_nonce')
]);

// *************************************

// ОТРИСОВЫВАЕМ ВАРИАЦИЮ ТОВАРА

add_action('wp_ajax_update_product_modal_variation', 'update_product_modal_variation');
add_action('wp_ajax_nopriv_update_product_modal_variation', 'update_product_modal_variation');

function update_product_modal_variation() {
    if (!wp_verify_nonce($_POST['nonce'], 'market_ajax_nonce')) {
        wp_send_json_error('Security failed');
    }

    $product_id = intval($_POST['product_id']);
    $variation_index = intval($_POST['variation_index']);
    
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error('Product not found');
    }

    $variations = $product->get_available_variations();
    $selected_variation = $variations[$variation_index] ?? null;
    
    if (!$selected_variation) {
        wp_send_json_error('Variation not found');
    }

    $var_id = $selected_variation['variation_id'];
    
    $variations_data = [];
    foreach ($variations as $index => $variation) {
        $v_id = $variation['variation_id'];
        $attrs = [];
        
        foreach ($variation['attributes'] as $attr_name => $attr_value) {
            $clean_name = str_replace('attribute_', '', $attr_name);
            $attrs[$clean_name] = $attr_value;
        }
        
        $variations_data[] = [
            'id' => $v_id,
            'index' => $index,
            'attributes' => $attrs,
            'ozon' => get_field('variations_ozon', $v_id) ?: '',
            'wildberries' => get_field('variations_wildberries', $v_id) ?: '',
            'gallery' => get_field('variations_gallery', $v_id) ?: [],
        ];
    }

    $gallery = get_field('variations_gallery', $var_id) ?: [];
    $ozon = get_field('variations_ozon', $var_id) ?: '';
    $wb = get_field('variations_wildberries', $var_id) ?: '';

    $currBigImgLink = null;
    $currBigImgAlt = null;
    
    if (!empty($gallery)) {
        $gallery = array_reverse($gallery);
        $currBigImgLink = $gallery[0]['url'];
        $currBigImgAlt = $gallery[0]['alt'];
    }

    ob_start();
    ?>
    <div class="modal-product-flexbox" data-product-id="<?= $product_id; ?>">
        <div class="modal-product-flexbox-gallery" data-gallery-container>
            <?php if (!empty($gallery)) { ?>
                <div class="modal-product-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($gallery as $image) { ?>
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
            <?php if ($currBigImgLink) { ?>
                <div class="modal-product-flexbox-gallery__main">
                    <img src="<?= $currBigImgLink; ?>" class="modal-product-flexbox-gallery__main-item" alt="<?= $currBigImgAlt; ?>">
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
                        <?php if ($label === 'Количество капсул' || $label === 'Активное вещество' || $label === 'Масса нетто') { ?>
                            <li class="modal-product-flexbox-content-list__item">
                                <div class="modal-product-flexbox-content-list__item-attribute-name"><?= $label . ': ' ?></div>
                                <div class="modal-product-flexbox-content-list__item-attribute-value">
                                    <?php 
                                    if (!empty($terms)) {
                                        $count = count($terms);
                                        foreach ($terms as $index => $term): 
                                            $parts = explode(' ', $term, 2);
                                            $number = $parts[0];
                                            
                                            if ($label === 'Количество капсул' || $label === 'Масса нетто') {
                                                $activeClass = ($index == $variation_index) ? ' variation-option__active' : '';
                                                echo '<span class="variation-option' . $activeClass . '" data-variation-index="' . $index . '" data-attribute-value="' . esc_attr($term) . '">' . $number . '</span>';
                                            } else {
                                                echo '<span data-attribute-value="' . esc_attr($term) . '">' . $number . '</span>';
                                            }
                                            
                                            if ($index < $count - 1) {
                                                echo ' | ';
                                            } else {
                                                echo ' ' . $parts[1];
                                            }
                                        endforeach;
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            <?php } ?>
            <?php if ($value = get_field('product_components_and_recomendations', $product_id)) { ?>
                <div class="modal-product-flexbox-content__descr">
                    <?= $value; ?>
                </div>
            <?php } ?>
            <div class="modal-product-flexbox-content__links">
                <span class="modal-product-flexbox-content__links-title">КУПИТЬ:</span>
                <div class="modal-product-flexbox-content__links-box" data-links-container>
                    <?php if ($ozon) { ?>
                        <a class="ozon-btn" href="<?= esc_url($ozon); ?>" target="_blank">OZON</a>
                    <?php } ?>
                    <?php if ($wb) { ?>
                        <a class="wildberries-btn" href="<?= esc_url($wb); ?>" target="_blank">WILDBERRIES</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <script id="variations-data" type="application/json">
        <?= json_encode($variations_data, JSON_UNESCAPED_UNICODE); ?>
    </script>
    <?php
    wp_send_json_success(['html' => ob_get_clean()]);
}

// ****************************

add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('custom-logo');
