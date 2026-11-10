<?php
define('SITE_LANG', (strpos($_SERVER['HTTP_HOST'], 'en.') === 0) ? 'en' : 'ru');

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('fancybox', get_template_directory_uri() . '/css/fancybox.css', [], '5.0.36');
    wp_enqueue_style('swiper', get_template_directory_uri() . '/css/swiper-bundle.min.css', [], '11.1.0');
    wp_enqueue_style('style-min', get_template_directory_uri() . '/css/style.css', [], filemtime(TEMPLATEPATH . '/css/style.css'));
    wp_enqueue_style('responsive-min', get_template_directory_uri() . '/css/responsive.css', [], filemtime(TEMPLATEPATH . '/css/responsive.css'));
    wp_enqueue_script('fancybox-js', get_template_directory_uri() . '/js/fancybox.umd.js', array(), false, true);
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/js/swiper-bundle.min.js', array(), false, true);
    wp_enqueue_script('imask-js', get_template_directory_uri() . '/js/imask.js', array(), false, true);
    wp_enqueue_script('main-js', get_template_directory_uri() . '/js/main.js', array(), filemtime(get_template_directory() . '/js/main.js'), true);
    wp_localize_script('main-js', 'filter_products', wp_create_nonce('load_products'));
});

// *******************************

add_action('after_setup_theme', 'theme_register_nav_menu');

function theme_register_nav_menu()
{
    register_nav_menu('Navigation', 'Навигация');
    register_nav_menu('Navigation EN', 'Навигация английская версия');
}

add_filter('upload_mimes', 'svg_upload_allow');

function svg_upload_allow($mimes)
{
    $mimes['svg']  = 'image/svg+xml';

    return $mimes;
}

add_filter('wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5);

function add_custom_class_to_menu_li($classes, $item, $args, $depth)
{
    $custom_class = 'navigation__item';

    $classes[] = $custom_class;

    return $classes;
}

add_filter('nav_menu_css_class', 'add_custom_class_to_menu_li', 10, 4);

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

// Добавление кастомных полей для вариаций WooCommerce

// ==============================================
// 1. ВЫВОД ПОЛЕЙ В КАЖДОЙ ВАРИАЦИИ
// ==============================================
add_action('woocommerce_product_after_variable_attributes', function ($loop, $variation_data, $variation) {
    echo '<div class="acf-variation-fields" data-post-id="' . $variation->ID . '">';

    // Необходимые скрытые поля ACF (nonce и т.д.)
    acf_form_data([
        'post_id' => $variation->ID,
        'form' => false
    ]);

    // Получаем группы полей для вариации
    $groups = acf_get_field_groups(['post_id' => $variation->ID]);

    foreach ($groups as $group) {
        // Заголовок группы
        echo '<h4 style="margin:20px 0 10px; padding:8px; background:#f0f0f1; border-left:4px solid #2271b1;">';
        echo esc_html($group['title']);
        echo '</h4>';

        $fields = acf_get_fields($group);
        if (empty($fields)) continue;

        foreach ($fields as $field) {
            // Устанавливаем префикс и значение
            $field['prefix'] = 'acf[' . $variation->ID . ']';
            $field['value']  = get_field($field['name'], $variation->ID, false);

            // ==========================================
            // ОБРАБОТКА В ЗАВИСИМОСТИ ОТ ТИПА ПОЛЯ
            // ==========================================
            if ($field['type'] === 'image' || $field['type'] === 'file') {
                // === МЕДИА ПОЛЯ (изображения/файлы) ===
                $value = $field['value'];
                $preview = $value ? wp_get_attachment_image($value, 'thumbnail') : '';
?>
                <div class="acf-field acf-field-image" style="margin-bottom:15px; padding:15px; background:#fff; border:1px solid #ddd; border-radius:4px;">
                    <!-- Заголовок поля -->
                    <div class="acf-label" style="margin-bottom:10px;">
                        <label style="font-weight:600; font-size:14px;">
                            <?php echo esc_html($field['label']); ?>
                        </label>
                        <?php if (!empty($field['instructions'])): ?>
                            <p class="description" style="color:#666; font-size:12px; margin:3px 0 0;">
                                <?php echo esc_html($field['instructions']); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Само поле -->
                    <div class="acf-input">
                        <input type="hidden"
                            name="<?php echo 'acf[' . $variation->ID . '][' . $field['key'] . ']'; ?>"
                            value="<?php echo esc_attr($value); ?>"
                            class="js-media-input"
                            data-variation="<?php echo $variation->ID; ?>"
                            data-field="<?php echo $field['key']; ?>"
                            data-field-name="<?php echo esc_attr($field['name']); ?>">

                        <!-- Превью -->
                        <div class="js-media-preview" style="margin-bottom:10px; min-height:50px;">
                            <?php if ($preview): ?>
                                <div style="display:inline-block; position:relative;">
                                    <?php echo $preview; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Кнопки управления -->
                        <div>
                            <button type="button"
                                class="button button-primary js-media-upload"
                                data-variation="<?php echo $variation->ID; ?>"
                                data-field="<?php echo $field['key']; ?>">
                                📷 Выбрать изображение
                            </button>

                            <?php if ($value): ?>
                                <button type="button"
                                    class="button js-media-remove"
                                    data-variation="<?php echo $variation->ID; ?>"
                                    data-field="<?php echo $field['key']; ?>"
                                    style="margin-left:5px;">
                                    🗑️ Удалить
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
            } else {
                // === ТЕКСТОВЫЕ ПОЛЯ (все остальные типы) ===
            ?>
                <div style="margin-bottom:15px; padding:15px; background:#fff; border:1px solid #ddd; border-radius:4px;">
                    <!-- Заголовок поля -->
                    <div class="acf-label" style="margin-bottom:8px;">
                        <label for="<?php echo esc_attr($field['key']); ?>" style="font-weight:600; font-size:14px;">
                            <?php echo esc_html($field['label']); ?>
                        </label>
                        <?php if (!empty($field['instructions'])): ?>
                            <p class="description" style="color:#666; font-size:12px; margin:3px 0 0;">
                                <?php echo esc_html($field['instructions']); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Само поле (рендерится ACF) -->
                    <div class="acf-input">
                        <?php acf_render_field($field); ?>
                    </div>
                </div>
    <?php
            }
        }
    }

    echo '</div>';
}, 10, 3);


// ==============================================
// 2. СОХРАНЕНИЕ ПОЛЕЙ
// ==============================================
add_action('woocommerce_save_product_variation', function ($variation_id) {
    if (isset($_POST['acf'][$variation_id]) && is_array($_POST['acf'][$variation_id])) {
        // Сохраняем глобальный массив
        $global_acf = $_POST['acf'] ?? [];

        // Подменяем данными текущей вариации
        $_POST['acf'] = $_POST['acf'][$variation_id];

        // Сохраняем через стандартный ACF
        acf_save_post($variation_id);

        // Восстанавливаем глобальный массив
        $_POST['acf'] = $global_acf;
    }
}, 10, 1);


// ==============================================
// 3. JS ДЛЯ РАБОТЫ МЕДИАТЕКИ
// ==============================================
add_action('admin_footer', function () {
    // Только на страницах товаров
    if (get_post_type() !== 'product') return;
    ?>
    <script>
        jQuery(function($) {
            // ======================================
            // ОТКРЫТИЕ МЕДИАТЕКИ
            // ======================================
            $(document).on('click', '.js-media-upload', function(e) {
                e.preventDefault();

                var $btn = $(this);
                var variationId = $btn.data('variation');
                var fieldKey = $btn.data('field');

                // Находим нужные элементы
                var $input = $('input.js-media-input[data-variation="' + variationId + '"][data-field="' + fieldKey + '"]');
                var $preview = $btn.closest('.acf-input').find('.js-media-preview');

                // Создаем медиа-фрейм
                var frame = wp.media({
                    title: 'Выберите изображение',
                    button: {
                        text: 'Выбрать'
                    },
                    multiple: false,
                    library: {
                        type: 'image'
                    }
                });

                // Обработка выбора
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();

                    // Сохраняем ID
                    $input.val(attachment.id).trigger('change');

                    // Обновляем превью
                    var previewHtml = '<img src="' + attachment.sizes.thumbnail.url + '" style="max-width:150px; max-height:150px; border:1px solid #ddd; padding:3px;">';
                    $preview.html(previewHtml);

                    // Добавляем кнопку удаления если её нет
                    if ($btn.siblings('.js-media-remove').length === 0) {
                        $btn.after('<button type="button" class="button js-media-remove" data-variation="' + variationId + '" data-field="' + fieldKey + '" style="margin-left:5px;">🗑️ Удалить</button>');
                    }
                });

                // Открываем медиатеку
                frame.open();
            });

            // ======================================
            // УДАЛЕНИЕ ИЗОБРАЖЕНИЯ
            // ======================================
            $(document).on('click', '.js-media-remove', function(e) {
                e.preventDefault();

                var $btn = $(this);
                var variationId = $btn.data('variation');
                var fieldKey = $btn.data('field');

                // Находим нужные элементы
                var $input = $('input.js-media-input[data-variation="' + variationId + '"][data-field="' + fieldKey + '"]');
                var $preview = $btn.closest('.acf-input').find('.js-media-preview');

                // Очищаем значения
                $input.val('').trigger('change');
                $preview.empty();

                // Удаляем кнопку удаления
                $btn.remove();
            });

            // ======================================
            // ОБНОВЛЕНИЕ ПЕРЕД ОТПРАВКОЙ ФОРМЫ
            // ======================================
            $('#post').on('submit', function() {
                $('.js-media-input').trigger('change');
            });

            // ======================================
            // ПОДДЕРЖКА НОВЫХ ВАРИАЦИЙ (AJAX)
            // ======================================
            $(document).on('woocommerce_variations_added', function() {
                // Ничего не делаем, обработчики работают через делегирование
            });
        });
    </script>

    <!-- Немного стилей для красоты -->
    <style>
        .acf-variation-fields {
            margin: 20px 0;
        }

        .acf-variation-fields .acf-field-image .button-primary {
            background: #2271b1;
            border-color: #2271b1;
        }

        .acf-variation-fields .acf-field-image .button-primary:hover {
            background: #135e96;
        }

        .js-media-preview img {
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
<?php
}, 100);

// ***************************************************

// Фильтр товаров

add_action('wp_ajax_load_products_section', 'ajax_load_products');
add_action('wp_ajax_nopriv_load_products_section', 'ajax_load_products');

function ajax_load_products() {
    check_ajax_referer('load_products', 'nonce');

    $category_slug = $_POST['category'] ?? '';
    $paged         = (int) ($_POST['paged'] ?? 1);
    $posts_per_page = 3;

    $cache_key = 'products_data_' . md5($category_slug . '_' . $paged . '_' . SITE_LANG);
    $cached_posts = wp_cache_get($cache_key, 'product_queries');
    
    if ($cached_posts === false) {        
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $posts_per_page,
            'paged'          => $paged,
            'tax_query'      => $category_slug ? [[
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category_slug
            ]] : [],
            'order'        => 'ASC'
        ];
               
        if (defined('SITE_LANG') && SITE_LANG !== 'en') {
            $args['meta_query'] = [
                'relation' => 'OR',
                [
                    'key'     => 'ru_exclude',
                    'value'   => '1',
                    'compare' => '!='
                ],
                [
                    'key'     => 'ru_exclude',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }

        $query = new WP_Query($args);
        $posts = $query->posts;
        $max_pages = $query->max_num_pages;
               
        $cached_posts = [
            'posts' => $posts,
            'max_pages' => $max_pages
        ];
        
        wp_cache_set($cache_key, $cached_posts, 'product_queries', 3600);
    } else {       
        $query = new WP_Query();
        $query->posts = $cached_posts['posts'];
        $query->post_count = count($cached_posts['posts']);
        $query->max_num_pages = $cached_posts['max_pages'];
        $max_pages = $cached_posts['max_pages'];
    }
    
    ob_start();
    if (!empty($query->posts)) {
        foreach ($query->posts as $post) {
            setup_postdata($post);
            wc_get_template_part('content', 'product_custom');
        }
        wp_reset_postdata();
    } else {
        echo '<div class="no-products">Товаров нет</div>';
    }
    $html = ob_get_clean();

    wp_send_json([
        'html'      => $html,
        'has_more'  => $paged < $max_pages,
        'next_page' => $paged + 1
    ]);
}

// **************

// Поиск товара

add_action('wp_ajax_live_product_search', 'ajax_live_product_search');
add_action('wp_ajax_nopriv_live_product_search', 'ajax_live_product_search');

function ajax_live_product_search() {
    check_ajax_referer('load_products', 'nonce');

    $search = trim($_POST['search'] ?? '');
    if (empty($search)) wp_die();

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        's'              => $search,
        'no_found_rows'  => true,
    ];

    add_filter('posts_join', function($join) {
        global $wpdb;
        $join .= " LEFT JOIN {$wpdb->postmeta} pm1 ON {$wpdb->posts}.ID = pm1.post_id AND pm1.meta_key = 'product_name_ru'";
        $join .= " LEFT JOIN {$wpdb->postmeta} pm2 ON {$wpdb->posts}.ID = pm2.post_id AND pm2.meta_key = 'product_short_desc_ru'";
        $join .= " LEFT JOIN {$wpdb->postmeta} pm3 ON {$wpdb->posts}.ID = pm3.post_id AND pm3.meta_key = 'product_detail_desc_ru'";
        return $join;
    });

    add_filter('posts_where', function($where) use ($search) {
        global $wpdb;
        $like = '%' . $wpdb->esc_like($search) . '%';
        $where .= $wpdb->prepare(
            " OR pm1.meta_value LIKE %s OR pm2.meta_value LIKE %s OR pm3.meta_value LIKE %s",
            $like, $like, $like
        );
        return $where;
    });

    add_filter('posts_clauses', function($clauses) use ($search) {
        global $wpdb;
        $like = '%' . $wpdb->esc_like($search) . '%';

        $clauses['fields'] .= ",
            (CASE 
                WHEN {$wpdb->posts}.post_title LIKE '$like' THEN 20
                WHEN pm1.meta_value LIKE '$like' THEN 15
                WHEN {$wpdb->posts}.post_content LIKE '$like' THEN 5
                WHEN pm2.meta_value LIKE '$like' THEN 4
                WHEN pm3.meta_value LIKE '$like' THEN 1
                ELSE 0 
            END) AS relevance_score";        

        $clauses['orderby'] = "relevance_score DESC, {$wpdb->posts}.post_date DESC";

        return $clauses;
    });

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product_custom');
        }
    } else {
        $noProducts = SITE_LANG === 'en' ? "No products found" : "Товаров не найдено";
        echo '<div class="no-products">' . $noProducts . '</div>';
    }
    echo ob_get_clean();

    wp_reset_postdata();
    wp_die();
}

// ************

// Отключаем обновление ACF

add_filter('auto_update_plugin', function ($update, $item) {
    if ($item->slug === 'advanced-custom-fields-pro') {
        return false;
    }
    return $update;
}, 10, 2);

//************************

// Добавляем кастомную таксономию для ACF "Атрибуты/Количество"

add_action('acf/init', function() {
    acf_add_local_field_group([
        'key'      => 'group_pa_quantity_field',
        'location' => [
            [
                [
                    'param'    => 'taxonomy',
                    'operator' => '==',
                    'value'    => 'pa_quantity',
                ],
            ],
        ],
    ]);
});

// ************************************************************

// Анимация кнопки обратной связи CF7

function add_data_text_to_cf7_submit_wrapper_v2( $form ) {
    $pattern = '/<p([^>]*)>\s*<input[^>]*type=["\']submit["\'][^>]*value=["\']([^"\']*)["\'][^>]*>.*?<\/p>/is';

    $form = preg_replace_callback( $pattern, function( $matches ) {
        $existing_attrs = $matches[1]; 
        $button_text    = $matches[2]; 

        $new_p = '<p data-text="' . esc_attr( $button_text ) . '"' . $existing_attrs . '>';

        return preg_replace( '/<p[^>]*>/', $new_p, $matches[0], 1 );
    }, $form );

    return $form;
}
add_filter( 'wpcf7_form_elements', 'add_data_text_to_cf7_submit_wrapper_v2' );

// **********************************

// Реализация возможности получать дубль сообщения с форм обратной связи в админку

function create_cf7_messages_cpt() {
    $args = array(
        'label'               => 'Сообщения CF7',
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-email-alt',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'has_archive'         => false,
        'supports'            => array( 'title' ),
        'show_in_rest'        => false,
    );
    register_post_type( 'cf7_message', $args );
}
add_action( 'init', 'create_cf7_messages_cpt' );

add_action( 'wpcf7_before_send_mail', 'save_cf7_data_to_cpt_and_acf' );

function save_cf7_data_to_cpt_and_acf( $contact_form ) {
    $submission = WPCF7_Submission::get_instance();

    if ( $submission ) {
        $posted_data = $submission->get_posted_data();
        $form_id = $contact_form->id();        
        $name = isset($posted_data['text-576']) ? $posted_data['text-576'] : 'Без имени';
        $email = isset($posted_data['email-795']) ? $posted_data['email-795'] : '';
        $message = isset($posted_data['textarea-665']) ? $posted_data['textarea-665'] : '';

        $title = $name . ' (ID формы: ' . $form_id . ')';

        $new_message_id = wp_insert_post( array(
            'post_title'  => $title,
            'post_type'   => 'cf7_message',
            'post_status' => 'private',
        ) );

        if ( ! is_wp_error( $new_message_id ) && $new_message_id > 0 ) {                 
            update_field( 'field_69df7703ece83', $name, $new_message_id );      
            update_field( 'field_69df77a5ece84', $email, $new_message_id );     
            update_field( 'field_69df77bcece85', $message, $new_message_id );   
        }
    }
}

// *******************************************************************************

add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('custom-logo');
