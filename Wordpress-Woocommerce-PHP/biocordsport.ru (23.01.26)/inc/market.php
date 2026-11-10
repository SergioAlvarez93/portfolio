<section class="market">
    <?php if ($value = get_field('market_title')) { ?>
        <div class="container">
            <h2 class="classic-h2 market__title"><?= esc_html($value); ?></h2>
        </div>
    <?php } ?>

    <!-- Фильтр категорий -->
    <?php
    $categories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'number'     => 1,
    ]);

    if (!empty($categories) && !is_wp_error($categories)) { ?>
        <div class="market__cat-container">
            <div class="filter-swiper">
                <div class="swiper-wrapper">
                    <?php do_action('before_market_loop'); ?>
                </div>
            </div>
            <div class="market-button-prev">
                <img src="<?= get_template_directory_uri(); ?>/img/arrow-back-basic.svg" alt="">
            </div>
            <div class="market-button-next">
                <img src="<?= get_template_directory_uri(); ?>/img/arrow_right_slider.png" alt="">
            </div>
        </div>
    <?php } ?>

    <div class="container">
        <div id="loading-indicator" style="display:none; text-align:center; padding:30px 0;">
            Загрузка...
        </div>

        <?php
        // Определяем количество товаров для первой загрузки
        $initial_per_page = wp_is_mobile() ? 3 : 3;

        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $initial_per_page,
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        ];

        $products_query = new WP_Query($args);
        $total_products = $products_query->found_posts;
        ?>

        <ul class="market-list products columns-3" id="products-container">
            <?php while ($products_query->have_posts()) {
                $products_query->the_post(); ?>
                <?php wc_get_template_part('content', 'market'); ?>
            <?php } ?>
        </ul>

        <?php if ($total_products > $initial_per_page) { ?>
            <div class="market-load-more button"
                data-loaded="<?= $initial_per_page ?>"
                data-total="<?= $total_products ?>"
                data-category="all">
                Показать ещё
            </div>
        <?php } ?>

        <?php wp_reset_postdata(); ?>
    </div>
</section>