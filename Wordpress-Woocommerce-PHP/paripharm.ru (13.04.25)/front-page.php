<?php
/*
Template Name: Главная
*/
?>
<?php get_header(); ?>
<main class="main">
    <?php if ($value = get_the_content()) { ?>
        <section class="seo-block"><?= $value; ?></section>
    <?php } ?>
    <?php if (have_rows('prev_repeater')) { ?>
        <section class="preview">
           <?php /* <div class="banner-ivent">
                <img class="banner-ivent__img" src="<?= get_template_directory_uri(); ?>/img/banner-ivent.svg" alt="Баннер с мероприятием">
            </div> */ ?>
            <div class="preview-grid">
                <div class="preview-swiper">
                    <div class="swiper-wrapper">
                        <?php while (have_rows('prev_repeater')) {
                            the_row(); ?>
                            <?php if (get_sub_field('img') || get_sub_field('title') || get_sub_field('subtitle') || get_sub_field('url_txt') || get_sub_field('url')) { ?>
                                <div class="preview-swiper__slide swiper-slide">
                                    <?php if ($value = get_sub_field('img')) { ?>
                                        <img src="<?= $value['url']; ?>" decoding="sync" loading="eager" alt="<?= $value['alt']; ?>">
                                    <?php } ?>
                                    <?php if (get_sub_field('title') || get_sub_field('subtitle') || get_sub_field('url_txt') || get_sub_field('url')) { ?>
                                        <div class="preview__content">
                                            <div class="container preview__content-box">
                                                <?php if ($value = get_sub_field('title')) { ?>
                                                    <h1 class="general-title preview__title"><?= $value; ?></h1>
                                                <?php } ?>
                                                <?php if ($value = get_sub_field('subtitle')) { ?>
                                                    <h4 class="classic-h4 preview__subtitle"><?= $value; ?></h4>
                                                <?php } ?>
                                                <?php if (get_sub_field('url_txt') || get_sub_field('url')) { ?>
                                                    <a href="<?php if ($value = get_sub_field('url')) {
                                                                    echo $value;
                                                                } ?>" class="classic-btn preview-btn"><?php if ($value = get_sub_field('url_txt')) {
                                                                                                            echo $value;
                                                                                                        } ?></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="swiper-pagination preview-pagination"></div>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php if (get_field('about_bg') || have_rows('about_repeater') || get_field('about_video') || get_field('about_title') || get_field('about_txt')) { ?>
        <section class="about">
            <?php if ($value = get_field('about_bg')) { ?>
                <div class="about__bg">
                    <img src="<?= $value['url']; ?>" class="about__bg-img" loading="lazy" alt="<?= $value['alt']; ?>">
                </div>
            <?php } ?>
            <?php if (have_rows('about_repeater') || get_field('about_video') || get_field('about_title') || get_field('about_txt')) { ?>
                <div class="container">
                    <?php
                    if (have_rows('about_repeater')) { ?>
                        <div class="about-cards">
                            <?php while (have_rows('about_repeater')) {
                                the_row(); ?>
                                <div class="about-cards__item">
                                    <?php if ($value = get_sub_field('icon')) { ?>
                                        <div class="about-cards__item-icon-wrap">
                                            <img src="<?= $value['url']; ?> " class="about-cards__item-icon" alt="<?= $value['alt']; ?>">
                                        </div>
                                    <?php } ?>
                                    <?php if ($value = get_sub_field('title')) { ?>
                                        <h4 class="classic-h4 about-cards__item-title"><?= $value; ?></h4>
                                    <?php } ?>
                                    <?php if ($value = get_sub_field('subtitle')) { ?>
                                        <p class="general-txt about-cards__item-subtitle"><?= $value; ?></p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if (get_field('about_video') || get_field('about_title') || get_field('about_txt')) { ?>
                        <div class="about-content">
                            <?php if ($value = get_field('about_video')) { ?>
                                <div class="about-content__video"><?= $value; ?></div>
                            <?php } ?>
                            <?php if (get_field('about_subtitle') || get_field('about_title') || get_field('about_txt')) { ?>
                                <div class="about-content__txt-box">
                                    <?php if ($value = get_field('about_subtitle')) { ?>
                                        <h2 class="classic-h3 about-content__txt-box-subtitle"><?= $value; ?></h2>
                                    <?php } ?>
                                    <?php if ($value = get_field('about_title')) { ?>
                                        <h2 class="classic-h2 about-content__txt-box-title"><?= $value; ?></h2>
                                    <?php } ?>
                                    <?php if ($value = get_field('about_txt')) { ?>
                                        <p class="general-txt about-content__txt-box-txt"><?= $value; ?></p>
                                    <?php } ?>
                                    <a href="#gallery" class="classic-btn about-content__txt-box-btn">Узнать больше</a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </section>
    <?php } ?>
    <?php if (get_field('products_bg') || have_rows('products_repeater') || get_field('products_title') || get_field('products_subtitle')) { ?>
        <section class="products" id="products">
            <div class="products__wrap">
                <?php if ($value = get_field('products_bg')) { ?>
                    <div class="products__bg-wrap">
                        <img src="<?= $value['url']; ?>" alt="<?= $value['alt']; ?>" class="products__bg">
                    </div>
                <?php } ?>
                <div class="container">
                    <?php if ($value = get_field('products_title')) { ?>
                        <h2 class="classic-h2 products__title"><?= $value; ?></h2>
                    <?php } ?>
                    <?php if ($value = get_field('products_subtitle')) { ?>
                        <p class="general-txt products__subtitle"><?= $value; ?></p>
                    <?php } ?>
                    <?php
                    if (have_rows('products_repeater')) { ?>
                        <div class="products-cards">
                            <?php while (have_rows('products_repeater')) {
                                the_row(); ?>
                                <?php if (get_sub_field('icon') || get_sub_field('title') || get_sub_field('txt')) { ?>
                                    <div class="products-cards__item">
                                        <div class="products-cards-item-box">
                                            <?php if ($value = get_sub_field('icon')) { ?>
                                                <div class="products-cards-item-box__icon-wrap">
                                                    <img src="<?= $value['url']; ?>" class="products-cards-item-box__icon" alt="<?= $value['alt']; ?>">
                                                </div>
                                            <?php } ?>
                                            <?php if (get_sub_field('title') || get_sub_field('txt')) { ?>
                                                <div class="products-cards-item-box__content">
                                                    <?php if ($value = get_sub_field('title')) { ?>
                                                        <h3 class="classic-h3 products-cards-item-box__content-title"><?= $value; ?> </h3>
                                                    <?php } ?>
                                                    <?php if ($value = get_sub_field('txt')) { ?>
                                                        <p class="general-txt products-cards-item-box__content-txt"><?= $value; ?> </p>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php if (get_field('gallery_title') || get_field('gallery_txt') || get_field('gallery_items')) { ?>
        <section class="gallery" id="gallery">
            <?php if (get_field('gallery_title') || get_field('gallery_txt')) { ?>
                <div class="container">
                    <?php if ($value = get_field('gallery_title')) { ?>
                        <h2 class="classic-h2 gallery__title"><?= $value; ?></h2>
                    <?php } ?>
                    <?php if ($value = get_field('gallery_txt')) { ?>
                        <p class="general-txt gallery__txt"><?= $value; ?> <a href="#contacts" class="gallery__link"><?php the_field('gallery_txt-link') ?></a></p>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if ($values = get_field('gallery_items')) { ?>
                <div class="gallery__container">
                    <div class="gallery-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($values as $value) { ?>
                                <div class="gallery-swiper__slide swiper-slide">
                                    <a href="<?= $value['url'] ?>" data-fancybox="gallery" class="gallery-swiper__slide-link" data-caption="<?= $value["description"]; ?>">
                                        <img src="<?= $value['url']; ?>" class="gallery-swiper__slide-img" loading="lazy" alt="<?= $value['alt']; ?>">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination gallery-pagination"></div>
            <?php } ?>
        </section>
    <?php } ?>
    <?php if (have_rows('workers_repeater')) { ?>
        <section class="workers">
            <div class="container">
                <div class="workers-grid">
                    <?php while (have_rows('workers_repeater')) {
                        the_row(); ?>
                        <?php if (get_sub_field('icon') || get_sub_field('title') || get_sub_field('subtitle')) { ?>
                            <div class="workers-grid__item">
                                <?php if ($value = get_sub_field('icon')) { ?>
                                    <div class="workers-grid__item-img-wrap">
                                        <img src="<?= $value['url']; ?> " class="workers-grid__item-img" alt="<?= $value['alt']; ?>">
                                    </div>
                                <?php } ?>
                                <?php if ($value = get_sub_field('title')) { ?>
                                    <h3 class="classic-h3 workers-grid__item-title"><?= $value; ?></h3>
                                <?php } ?>
                                <?php if ($value = get_sub_field('subtitle')) { ?>
                                    <p class="general-txt workers-grid__item-txt"><?= $value; ?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php
    $count = 0;
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'paged'         => get_query_var('paged') ? get_query_var('paged') : 1,
    );

    $products_query = new WP_Query($args);

    if ($products_query->have_posts()) {
    ?>
        <section class="product" id="product">
            <div class="container">
                <?php if ($value = get_field('catalog_title')) { ?>
                    <h2 class="classic-h2 product__title"><?= esc_html($value); ?></h2>
                <?php } ?>
                <?php if ($value = get_field('catalog_note')) { ?>
                    <p class="general-txt product__note"><?= esc_html($value); ?></p>
                <?php } ?>
                <div class="product-grid">
                    <?php
                    while ($products_query->have_posts()) {
                        $products_query->the_post();
                        if (get_field('product_title') || get_field('product_txt') || get_field('product_note') || get_field('product_price')) {
                    ?>
                            <article class="product-grid__item" data-count="<?= $count ?>">
                                <?php if ($value = get_field('product_title')) { ?>
                                    <h3 class="classic-h3 product-grid__item-title"><?= esc_html($value); ?></h3>
                                <?php } ?>
                                <?php if ($value = get_field('product_txt')) { ?>
                                    <p class="general-txt product-grid__item-txt"><?= esc_html($value); ?></p>
                                <?php } ?>
                                <?php if ($value = get_field('product_note')) { ?>
                                    <p class="general-txt product-grid__item-note"><?= esc_html($value); ?></p>
                                <?php } ?>
                                <?php if ($value = get_field('product_price')) { ?>
                                    <div class="product-grid__item-price-box">
                                        <button class="general-txt product-grid__item-price-box-btn">Заказать</button>
                                        <?php /*<span class="product-grid__item-price-box-curr-price"><?= esc_html($value); ?></span>*/ ?>
                                    </div>
                                <?php } ?>
                            </article>
                    <?php
                        }
                        $count++;
                    }
                    ?>
                </div>
                <div class="product-buttons">
                    <?php if ($products_query->found_posts > $per_page) { ?>
                        <button id="load-more" class="classic-btn load-more-button" data-page="1" data-max-pages="<?= esc_attr($products_query->max_num_pages); ?>">Показать еще</button>
                    <?php } ?>
                    <?php if ($value = get_field('catalog', 9)) { ?>
                        <a href="<?= esc_url($value); ?>" class="classic-btn product-download-link" target="_blank">Скачать каталог</a>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php
        wp_reset_postdata();
    } else {
        echo '<section class="product"><div class="container"><p class="general-txt product__not-found">Товары не найдены</p></div></section>';
    }
    ?>
    <?php if (shortcode_exists('contact-form-7') || get_field('contacts_title') || get_field('contacts_subtitle') || get_field('tg_qr', 'option') || get_field('tg_icon', 'option') || get_field('tg_txt', 'option') || get_field('tg_link', 'option')) { ?>
        <section class="contacts" id="contacts">
            <div class="contacts__wrap">
                <?php if ($value = get_field('contacts_bg')) { ?>
                    <div class="contacts__bg-wrap">
                        <img src="<?= $value; ?>" class="contacts__bg" alt="contacts-bg">
                    </div>
                <?php } ?>
                <div class="container">
                    <?php if ($value = get_field('contacts_title')) { ?>
                        <h2 class="classic-h2 contacts__title"><?= $value; ?></h2>
                    <?php } ?>
                    <?php if ($value = get_field('contacts_subtitle')) { ?>
                        <p class="general-txt contacts__subtitle"><?= $value; ?> </p>
                    <?php } ?>
                    <?php if (shortcode_exists('contact-form-7') || get_field('tg_qr', 'option') || get_field('tg_icon', 'option') || get_field('tg_txt', 'option') || get_field('tg_link', 'option')) { ?>
                        <div class="contacts-grid">
                            <?php if (shortcode_exists('contact-form-7')) { ?>
                                <div class="contacts-grid__item">
                                    <?php echo do_shortcode('[contact-form-7 id="0a15662" title="Контактная форма 1"]'); ?>
                                </div>
                            <?php } ?>
                            <?php if (get_field('tg_qr', 'option') || get_field('tg_icon', 'option') || get_field('tg_txt', 'option') || get_field('tg_link', 'option')) { ?>
                                <div class="contacts-grid__item">
                                    <?php if ($value = get_field('tg_qr', 'option')) { ?>
                                        <div class="tg-qr__wrap">
                                            <img class="tg-qr" src="<?= $value; ?>" alt="Qr-код телеграм для Парифарм (Paripharm)">
                                        </div>
                                    <?php } ?>
                                    <?php if (get_field('tg_icon', 'option') || get_field('tg_txt', 'option') || get_field('tg_link', 'option')) { ?>
                                        <div class="tg-btn__wrap">
                                            <a href="<?php if ($value = get_field('tg_link', 'option')) {
                                                            echo $value;
                                                        } ?>" class="classic-btn tg-btn" target="_blank">
                                                <?php if ($value = get_field('tg_icon', 'option')) { ?>
                                                    <img src="<?= $value; ?>" class="tg-btn__image" alt="Иконка телеграм (telegram)">
                                                <?php  } ?>
                                                <?php if ($value = get_field('tg_txt', 'option')) { ?>
                                                    <div class="tg-btn__txt"><?= $value; ?></div>
                                                <?php  } ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            </div>
        </section>
    <?php } ?>
</main>
<?php get_footer(); ?>