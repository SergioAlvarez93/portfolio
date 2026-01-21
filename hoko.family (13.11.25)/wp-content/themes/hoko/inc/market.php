<section class="market">
    <?php
    $args = array(
        'post_type' => 'product_cat',
        'posts_per_page' => 1, // Проверяем наличие хотя бы одной категории
    );
    $product_cat = new WP_Query($args);

    if ($product_query->have_posts()) { ?>
        <div class="market__cat-container">
            <div class="filter-swiper">
                <div class="swiper-wrapper">
                    <?php do_action('before_market_loop'); ?>
                </div>
            </div>
        </div>
    <?php }
    wp_reset_postdata();
    ?>
    <div class="container">
        <div id="loading-indicator" style="display: none;">Загрузка...</div>
        <ul class="market-list" id="products-container">
            <?php
            $args = [
                'post_type' => 'product',
                'posts_per_page' => -1,
                'order' => 'ASC',
            ];
            $loop = new WP_Query($args);
            if ($loop->have_posts()) {
                while ($loop->have_posts()) {
                    $loop->the_post();
                    wc_get_template_part('content', 'product');
                }
            } else {
                echo '<p>Товары не найдены</p>';
            }
            wp_reset_postdata();
            ?>
        </ul>
    </div>
</section>