<?php
/*
Template Name: Главная
*/
?>
<?php get_header(); ?>
<main class="main">
    <?php if (get_field('presentation_large_logo') || get_field('presentation_bg') || get_field('presentation_txt') || get_field('presentation_txt_en')) { ?>
        <section class="presentation">
            <div class="container">
                <div class="left-container">
                    <?php if ($value = get_field('presentation_large_logo')) { ?>
                        <div class="presentation-large-logo__wrap">
                            <img class="presentation-large-logo" src="<?= $value['url']; ?>" alt="<?= $value['alt']; ?>">
                        </div>
                    <?php } ?>
                    <?php if (get_field('presentation_bg') || get_field('presentation_txt') || get_field('presentation_txt_en')) { ?>
                        <div class="presentation-grid">
                            <?php if (SITE_LANG === 'en') { ?> 
                                <?php if ($value = get_field('presentation_txt_en')) { ?> 
                                    <h1 class="classic-h1 presentation-txt"><?= $value; ?></h1>
                                <?php } ?>
                             <?php } else { ?>
                                <?php if ($value = get_field('presentation_txt')) { ?>
                                    <h1 class="classic-h1 presentation-txt"><?= $value; ?></h1>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($value = get_field('presentation_bg')) { ?>
                                <div class="presentation-bg__wrap">
                                    <picture>
                                        <?php if ($mobileValue = get_field('presentation_bg_mob')) { ?> 
                                            <source media="(max-width: 767px)" srcset="<?= $mobileValue['url']; ?>">
                                        <?php } ?>                                                                                                                        
                                        <source media="(min-width: 768px)" srcset="<?= $value['url']; ?>">                                        
                                        <img src="<?= $value['url']; ?>" class="presentation-bg" alt="<?= $value['alt']; ?>">
                                    </picture>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php if (SITE_LANG !== 'en') { if (have_rows('realisation_forms')) { ?>
        <section class="realisation">
            <div class="realisation-container">
                <div class="realisation-track">
                    <div class="realisation-flex">
                        <?php while (have_rows('realisation_forms')) {
                            the_row(); ?>
                            <div class="realisation-flex-box">
                                <?php if ($value = get_sub_field('icon')) { ?>
                                    <img src="<?= $value['url']; ?>" class="realisation-flex-box__img" alt="<?= $value['alt']; ?>">
                                <?php } ?>
                                <?php if ($value = get_sub_field('name')) { ?>
                                    <div class="realisation-flex-box__name"><?= $value; ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="realisation-flex realisation-flex__double" aria-hidden="true">
                        <?php while (have_rows('realisation_forms')) {
                            the_row(); ?>
                            <div class="realisation-flex-box">
                                <?php if ($value = get_sub_field('icon')) { ?>
                                    <img src="<?= $value['url']; ?>" class="realisation-flex-box__img" alt="<?= $value['alt']; ?>">
                                <?php } ?>
                                <?php if ($value = get_sub_field('name')) { ?>
                                    <div class="realisation-flex-box__name"><?= $value; ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php } 
    } else {
       if (have_rows('realisation_forms_en')) { ?>
        <section class="realisation">
            <div class="realisation-container">
                <div class="realisation-track">
                    <div class="realisation-flex">
                        <?php while (have_rows('realisation_forms_en')) {
                            the_row(); ?>
                            <div class="realisation-flex-box">
                                <?php if ($value = get_sub_field('icon')) { ?>
                                    <img src="<?= $value['url']; ?>" class="realisation-flex-box__img" alt="<?= $value['alt']; ?>">
                                <?php } ?>
                                <?php if ($value = get_sub_field('name')) { ?>
                                    <div class="realisation-flex-box__name"><?= $value; ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="realisation-flex realisation-flex__double" aria-hidden="true">
                        <?php while (have_rows('realisation_forms_en')) {
                            the_row(); ?>
                            <div class="realisation-flex-box">
                                <?php if ($value = get_sub_field('icon')) { ?>
                                    <img src="<?= $value['url']; ?>" class="realisation-flex-box__img" alt="<?= $value['alt']; ?>">
                                <?php } ?>
                                <?php if ($value = get_sub_field('name')) { ?>
                                    <div class="realisation-flex-box__name"><?= $value; ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php }  
    } ?>
    <?php $args = [
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'post_status'    => 'publish',
        'tax_query'      => [
            [
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => 'novinka',
            ]
        ],
        'order'          => 'ASC'
    ];
    $query = new WP_Query($args);
    $noveltyTitle = SITE_LANG === 'en' ? 'New arrivals' : 'Новинки';
    if ($query->have_posts()) {
        echo '<section class="novelty"><div class="container"><h2 class="classic-h2 novelty__title">' . $noveltyTitle . '</h2><div class="left-container"><ul class="novelty-products">';
        while ($query->have_posts()) {
            $query->the_post();
            setup_postdata($post);
            wc_get_template_part('content', 'product_custom_new');
        };
        echo '</ul></div></div></section>';
        wp_reset_postdata();
    } ?>
    <?php if (get_field('family_bg') and get_field('family_bg_mob')) { ?>
        <section class="family">
            <picture class="family-bg__wrap">
                <source srcset="<?= get_field('family_bg_mob')['url'] ?>" media="(max-width: 767px)" alt="<?= get_field('family_bg_mob')['alt'] ?>">
                <img src="<?= get_field('family_bg')['url'] ?>" class="family-bg" alt="<?= get_field('family_bg')['alt'] ?>">
            </picture>
        </section>
    <?php } ?>
    <?php if (wp_count_posts('product')->publish > 0 || wp_count_terms('product_cat') > 0 || get_field('catalog_title') || get_field('catalog_title_en')) { ?>
        <section class="catalog" id="catalog">
            <div class="container">
                <?php if (wp_count_terms('product_cat') > 0 || get_field('catalog_title') || get_field('catalog_title_en')) { ?>
                    <div class="catalog-flex">
                        <?php if (SITE_LANG !== 'en') { ?> 
                            <?php if ($value = get_field('catalog_title')) { ?>
                                <h2 class="classic-h2 catalog-flex=title"><?= $value; ?></h2>
                            <?php } ?> 
                        <?php } else { ?>
                            <?php if ($value = get_field('catalog_title_en')) { ?>
                                <h2 class="classic-h2 catalog-flex=title"><?= $value; ?></h2>
                            <?php } ?>
                        <?php } ?>
                        <?php if (wp_count_terms('product_cat') > 0) { ?>
                            <?php
                            $categories = get_terms([
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => false,
                                'fields'     => 'all',
                                'order'      => 'DESC',
                            ]);
                            $result = [];
                            $result = array_map(function ($cat) {
                                return ['name' => $cat->name, 'name_en' => get_field('cat_name_en', $cat), 'slug' => $cat->slug];
                            }, $categories); ?>
                            <div class="catalog-flex-filter">
                                <div class="swiper-wrapper">
                                    <?php foreach ($result as $item) { ?>
                                        <?php if (SITE_LANG == 'en') { ?>
                                            <a class="swiper-slide catalog-flex-filter__item" data-filter="<?= $item['slug'] ?>"><?= $item['name_en'] ?></a>
                                        <?php } else { ?>
                                            <a class="swiper-slide catalog-flex-filter__item" data-filter="<?= $item['slug'] ?>"><?= $item['name'] ?></a>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <div class="catalog-flex-filter-prev swiper-button-disabled">
                                    <img src="<?= get_template_directory_uri(); ?>/img/arrow-right.svg" alt="Предыдущий слайд">
                                </div>
                                <div class="catalog-flex-filter-next swiper-button-disabled">
                                    <img src="<?= get_template_directory_uri(); ?>/img/arrow-right.svg" alt="Следующий слайд">
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="search-wrap">
                    <input type="text" id="product-search" placeholder="<?= SITE_LANG === 'en' ? 'Search by name...' : 'Поиск по названию...' ?>" autocomplete="off">
                    <div id="search-results" class="catalog-items-container" style="display:none;"></div>
                </div>
                <?php if (wp_count_posts('product')->publish > 0) { ?>
                    <div class="catalog-items-container" id="category-products"></div>
                    <div class="catalog-items-container" id="search-results" style="display:none;"></div>
                    <div class="load-more-wrap">
                        <button id="load-more-btn" class="button" style="display:none;" data-text="<?= SITE_LANG === 'en' ? 'Show more' : 'Показать ещё' ?>">
                            <?= SITE_LANG === 'en' ? 'Show more' : 'Показать ещё' ?>
                        </button>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
    <?php if (SITE_LANG !== 'en') { ?> 
        <?php if (get_field('marketplaces_title') || have_rows('marketplaces_list') || get_field('marketplaces_img')) { ?>
            <section class="marketplaces" id="buy">
                <div class="container">
                    <?php if ($value = get_field('marketplaces_title')) { ?> 
                        <h2 class="classic-h2 marketplaces__title"><?= $value; ?></h2> 
                    <?php } ?>  
                    <?php if (have_rows('marketplaces_list')) { ?> 
                        <div class="marketplaces-grid">
                            <?php while (have_rows('marketplaces_list')) {
                            the_row(); ?>
                                <?php if (get_sub_field('logo') || get_sub_field('link')) { ?>
                                    <div class="marketplaces-grid__item">
                                        <?php if ($value = get_sub_field('link')) { ?> 
                                            <a href="<?= $value; ?>" class="marketplaces-grid-img__wrap" target="_blank">
                                                <?php if ($img = get_sub_field('logo')) { ?> 
                                                    <img src="<?= $img['url']; ?>" class="marketplaces-grid-img" alt="<?= $img['alt']; ?>">
                                                <?php } ?>
                                            </a> 
                                        <?php } ?>
                                    </div>
                                <?php } ?> 
                            <?php } ?>  
                        </div>
                    <?php } ?> 
                    <?php if ($value = get_field('marketplaces_img')) { ?> 
                        <div class="marketplaces__img-wrap">
                            <img src="<?= $value['url']; ?>" class="marketplaces__img" alt="<?= $value['alt']; ?>">
                        </div> 
                    <?php } ?>
                </div>
            </section>
        <?php } ?>
    <?php } else { ?>
        <?php if (get_field('marketplaces_title_en') || have_rows('marketplaces_list_en') || get_field('marketplaces_img')) { ?>
            <section class="marketplaces" id="buy">
                <div class="container">
                    <?php if ($value = get_field('marketplaces_title_en')) { ?> 
                        <h2 class="classic-h2 marketplaces__title"><?= $value; ?></h2> 
                    <?php } ?>  
                    <?php if (have_rows('marketplaces_list_en')) { ?> 
                        <div class="marketplaces-grid">
                            <?php while (have_rows('marketplaces_list_en')) {
                            the_row(); ?>
                                <?php if (get_sub_field('logo') || get_sub_field('link')) { ?>
                                    <div class="marketplaces-grid__item">
                                        <?php if ($value = get_sub_field('link')) { ?> 
                                            <a href="<?= $value; ?>" class="marketplaces-grid-img__wrap" target="_blank">
                                                <?php if ($img = get_sub_field('logo')) { ?> 
                                                    <img src="<?= $img['url']; ?>" class="marketplaces-grid-img" alt="<?= $img['alt']; ?>">
                                                <?php } ?>
                                            </a> 
                                        <?php } ?>
                                    </div>
                                <?php } ?> 
                            <?php } ?>  
                        </div>
                    <?php } ?> 
                    <?php if ($value = get_field('marketplaces_img')) { ?> 
                        <div class="marketplaces__img-wrap">
                            <img src="<?= $value['url']; ?>" class="marketplaces__img" alt="<?= $value['alt']; ?>">
                        </div> 
                    <?php } ?>
                </div>
            </section>
        <?php } ?>
    <?php } ?>
    <?php if (SITE_LANG !== 'en') { if (have_rows('advertisement_list')) { ?>
        <section class="advertisement">
            <div class="advertisement-container">
                <div class="advertisement-track">
                    <div class="advertisement-flex">
                        <?php while (have_rows('advertisement_list')) {
                            the_row(); ?>
                            <div class="advertisement-flex-box">
                                <?php if ($value = get_sub_field('icon')) { ?>
                                    <img src="<?= $value['url']; ?>" class="advertisement-flex-box__img" alt="<?= $value['alt']; ?>">
                                <?php } ?>
                                <?php if ($value = get_sub_field('name')) { ?>
                                    <div class="advertisement-flex-box__name"><?= $value; ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="advertisement-flex advertisement-flex__double" aria-hidden="true">
                        <?php while (have_rows('advertisement_list')) {
                            the_row(); ?>
                            <div class="advertisement-flex-box">
                                <?php if ($value = get_sub_field('icon')) { ?>
                                    <img src="<?= $value['url']; ?>" class="advertisement-flex-box__img" alt="<?= $value['alt']; ?>">
                                <?php } ?>
                                <?php if ($value = get_sub_field('name')) { ?>
                                    <div class="advertisement-flex-box__name"><?= $value; ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php } 
    } else {
       if (have_rows('advertisement_list_en')) { ?>
        <section class="advertisement">
            <div class="advertisement-container">
                <div class="advertisement-track">
                    <div class="advertisement-flex">
                        <?php while (have_rows('advertisement_list_en')) {
                            the_row(); ?>
                            <div class="advertisement-flex-box">
                                <?php if ($value = get_sub_field('icon')) { ?>
                                    <img src="<?= $value['url']; ?>" class="advertisement-flex-box__img" alt="<?= $value['alt']; ?>">
                                <?php } ?>
                                <?php if ($value = get_sub_field('name')) { ?>
                                    <div class="advertisement-flex-box__name"><?= $value; ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="advertisement-flex advertisement-flex__double" aria-hidden="true">
                        <?php while (have_rows('advertisement_list_en')) {
                            the_row(); ?>
                            <div class="advertisement-flex-box">
                                <?php if ($value = get_sub_field('icon')) { ?>
                                    <img src="<?= $value['url']; ?>" class="advertisement-flex-box__img" alt="<?= $value['alt']; ?>">
                                <?php } ?>
                                <?php if ($value = get_sub_field('name')) { ?>
                                    <div class="advertisement-flex-box__name"><?= $value; ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php }  
    } ?>
    <?php if (SITE_LANG !== 'en') { ?> 
        <?php if (get_field('production_title') || have_rows('production_process') || get_field('production_img')) { ?>
            <section class="production" id="manufacturing">
                <div class="container">
                    <div class="production-flexbox">
                        <?php if (get_field('production_title') || have_rows('production_process')) { ?>
                            <div class="production-flexbox-content">
                                <?php if ($value = get_field('production_title')) { ?> 
                                    <h2 class="classic-h2 production-flexbox-content__title"><?= $value; ?></h2>
                                <?php } ?>
                                <?php if (have_rows('production_process')) { ?>
                                    <ul class="production-flexbox-content-list">
                                        <?php while (have_rows('production_process')) {
                                        the_row(); ?>
                                            <?php if (get_sub_field('icon') || get_sub_field('txt')) { ?>
                                                <li class="production-flexbox-content-list-item">
                                                    <?php if ($value = get_sub_field('icon')) { ?> 
                                                        <div class="production-flexbox-content-list-item__img-wrap">
                                                            <img src="<?= $value['url']; ?>" class="production-flexbox-content-list-item__img" alt="<?= $value['alt']; ?>">
                                                        </div> 
                                                    <?php } ?>
                                                    <?php if ($value = get_sub_field('txt')) { ?> 
                                                        <p class="production-flexbox-content-list-item__txt"><?= $value; ?></p> 
                                                    <?php } ?>
                                                </li>
                                            <?php } ?> 
                                        <?php } ?>  
                                    </ul>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($value = get_field('production_img')) { ?>
                            <div class="production-img">
                                <img src="<?= $value['url']; ?>" class="production-img__item" alt="<?= $value['alt']; ?>">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        <?php } ?>
    <?php } else { ?>
        <?php if (get_field('production_title_en') || have_rows('production_process_en') || get_field('production_img')) { ?>
            <section class="production" id="manufacturing">
                <div class="container">
                    <div class="production-flexbox">
                        <?php if (get_field('production_title_en') || have_rows('production_process_en')) { ?>
                            <div class="production-flexbox-content">
                                <?php if ($value = get_field('production_title_en')) { ?> 
                                    <h2 class="classic-h2 production-flexbox-content__title"><?= $value; ?></h2>
                                <?php } ?>
                                <?php if (have_rows('production_process_en')) { ?>
                                    <ul class="production-flexbox-content-list">
                                        <?php while (have_rows('production_process_en')) {
                                        the_row(); ?>
                                            <?php if (get_sub_field('icon') || get_sub_field('txt')) { ?>
                                                <li class="production-flexbox-content-list-item">
                                                    <?php if ($value = get_sub_field('icon')) { ?> 
                                                        <div class="production-flexbox-content-list-item__img-wrap">
                                                            <img src="<?= $value['url']; ?>" class="production-flexbox-content-list-item__img" alt="<?= $value['alt']; ?>">
                                                        </div> 
                                                    <?php } ?>
                                                    <?php if ($value = get_sub_field('txt')) { ?> 
                                                        <p class="production-flexbox-content-list-item__txt"><?= $value; ?></p> 
                                                    <?php } ?>
                                                </li>
                                            <?php } ?> 
                                        <?php } ?>  
                                    </ul>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($value = get_field('production_img')) { ?>
                            <div class="production-img">
                                <img src="<?= $value['url']; ?>" class="production-img__item" alt="<?= $value['alt']; ?>">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        <?php } ?>
    <?php } ?>
    <?php if (SITE_LANG !== 'en') { ?> 
        <?php if (get_field('faq_title') || get_field('faq_img') || have_rows('faq_repeater')) { ?> 
            <section class="faq" id="faq">
                <div class="container">
                    <?php if ($value = get_field('faq_title')) { ?> 
                        <h2 class="classic-h2 faq__title"><?= $value; ?> </h2>
                    <?php } ?>
                    <?php if (get_field('faq_img') || have_rows('faq_repeater')) { ?> 
                        <div class="faq-flex">
                            <?php if ($value = get_field('faq_img')) { ?> 
                                <div class="faq-flex-img">
                                    <img src="<?= $value['url']; ?>" class="faq-flex-img__item" alt="<?= $value['alt']; ?>">
                                </div> 
                            <?php } ?>
                            <?php if ($value = have_rows('faq_repeater')) { ?> 
                                <div class="faq-repeater">
                                    <?php while (have_rows('faq_repeater')) { the_row(); ?>
                                        <?php if (get_sub_field('question') || get_sub_field('answer')) { ?> 
                                            <div class="faq-repeater-item <?php if (get_row_index() === 1) { echo "faq-repeater-item-active"; } ?>">
                                                <?php if ($value = get_sub_field('question')) { ?> 
                                                    <div class="faq-repeater-item-question">
                                                        <div class="faq-repeater-item-question-plus">
                                                            <img src="<?= get_template_directory_uri(); ?>/img/plus.svg" class="faq-repeater-item-question-plus__item" alt="Открывашка вопроса">
                                                        </div>
                                                        <div class="faq-repeater-item-question-txt"><?= $value; ?></div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($value = get_sub_field('answer')) { ?> 
                                                    <div class="faq-repeater-item-answer">
                                                        <div class="faq-repeater-item-answer__inner"><?= $value; ?></div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </section>
        <?php } ?>          
    <?php } else { ?>
        <?php if (get_field('faq_title_en') || get_field('faq_img') || have_rows('faq_repeater_en')) { ?> 
            <section class="faq" id="faq">
                <div class="container">
                    <?php if ($value = get_field('faq_title_en')) { ?> 
                        <h2 class="classic-h2 faq__title"><?= $value; ?> </h2>
                    <?php } ?>
                    <?php if (get_field('faq_img') || have_rows('faq_repeater_en')) { ?> 
                        <div class="faq-flex">
                            <?php if ($value = get_field('faq_img')) { ?> 
                                <div class="faq-flex-img">
                                    <img src="<?= $value['url']; ?>" class="faq-flex-img__item" alt="<?= $value['alt']; ?>">
                                </div> 
                            <?php } ?>
                            <?php if ($value = have_rows('faq_repeater_en')) { ?> 
                                <div class="faq-repeater">
                                    <?php while (have_rows('faq_repeater_en')) { the_row(); ?>
                                        <?php if (get_sub_field('question') || get_sub_field('answer')) { ?> 
                                            <div class="faq-repeater-item <?php if (get_row_index() === 1) { echo "faq-repeater-item-active"; } ?>">
                                                <?php if ($value = get_sub_field('question')) { ?> 
                                                    <div class="faq-repeater-item-question">
                                                        <div class="faq-repeater-item-question-plus">
                                                            <img src="<?= get_template_directory_uri(); ?>/img/plus.svg" class="faq-repeater-item-question-plus__item" alt="Открывашка вопроса">
                                                        </div>
                                                        <div class="faq-repeater-item-question-txt"><?= $value; ?></div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($value = get_sub_field('answer')) { ?> 
                                                    <div class="faq-repeater-item-answer">
                                                        <div class="faq-repeater-item-answer__inner"><?= $value; ?></div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </section>
        <?php } ?>          
    <?php } ?>  
    <section class="message" id="message">
        <div class="container">
            <div class="message-flex">
                <?php if (SITE_LANG !== 'en') { ?> 
                    <div class="message-form"><?= do_shortcode('[contact-form-7 id="d6f72d8" title="Форма"]') ?></div>
                <?php } else { ?>   
                    <div class="message-form"><?= do_shortcode('[contact-form-7 id="e6dd645" title="Форма_en"]') ?></div>
                <?php } ?> 
                <?php if ($value = get_field('message_img')) { ?>
                <div class="message-img">
                    <img src="<?= $value['url']; ?>" class="message-img__item" alt="<?= $value['alt']; ?>">
                </div>                      
                <?php } ?>           
            </div>
        </div>
    </section>
<?php get_footer(); ?>