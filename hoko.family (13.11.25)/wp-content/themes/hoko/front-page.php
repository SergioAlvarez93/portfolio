<?php
/*
Template Name: Главная
*/
?>
<?php get_header(); ?>
<main class="main">
    <section class="presentation">
        <div class="presentation__wrap">
            <div class="presentation-grid">
                <?php if ($value = get_field('presentation_bg')) { ?>
                    <div class="presentation-grid__bg">
                        <img
                            src="<?= $value['url']; ?>"
                            alt="<?= $value['alt'] ?>"
                            loading="lazy">
                    </div>
                <?php } ?>
                <div class="container presentation-grid__content">
                    <?php if ($value = get_field('slogan')) { ?>
                        <?= $value; ?>
                    <?php } ?>
                    <?php if ($value = get_field('mob_presentation_bg')) { ?>
                        <div class="presentation-grid__mob-bg">
                            <img src="<?= $value['url']; ?>" class="presentation-grid__mob-bg-item jar" alt="<?= $value['alt']; ?>">
                        </div>
                    <?php } ?>
                    <?php
                    $images = get_field('standarts_icons');
                    if ($images) { ?>
                        <div class="presentation-grid__gallery">
                            <?php foreach ($images as $image) { ?>
                                <img src="<?= esc_url($image['url']) ?>" style="<?= $image['description'] ?>" alt="<?= $image['alt'] ?>">
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <a href="<?= get_home_url(); ?>/#request" class="classic-btn header-btn presentation-grid__mob-btn">Обратная связь</a>
                </div>
            </div>
        </div>
    </section>
    <?php
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 1, // Проверяем наличие хотя бы одного товара
    );
    $product_query = new WP_Query($args);

    if ($product_query->have_posts()) {
        include get_template_directory() . '/inc/showcase.php';
    }
    wp_reset_postdata();
    ?>
    <section class="advantages">
        <div class="container">
            <div class="advantage-flexbox">
                <div class="advantage-flexbox-titleAndLogo">
                    <?php if ($value = get_field('advantages_title')) { ?>
                        <?= $value; ?>
                    <?php } ?>
                    <?php if ($value = get_field('advantages_img')) { ?>
                        <img src="<?= $value['url']; ?>" class="advantage-flexbox-titleAndLogo__logo" alt="<?= $value['alt']; ?>">
                    <?php } ?>
                </div>
                <?php
                if (have_rows('advantages_list')) { ?>
                    <div class="advantage-flexbox-content">
                        <?php while (have_rows('advantages_list')) {
                            the_row(); ?>
                            <div class="advantage-flexbox-content__item" data-index="<?= get_row_index(); ?>">
                                <?php if ($value = get_sub_field('img')) { ?>
                                    <img src="<?= $value['url']; ?>" class="advantage-flexbox-content__item-logo" alt="<?= $value['alt'] ?>">
                                <?php } ?>
                                <div class="advantage-flexbox-content__box">
                                    <?php if ($value = get_sub_field('title')) { ?>
                                        <div class="advantage-flexbox-content__item-title"><?= $value; ?></div>
                                    <?php } ?>
                                    <?php if ($value = get_sub_field('txt')) { ?>
                                        <p class="advantage-flexbox-content__item-txt"><?= $value; ?></p>
                                </div>
                            <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <?php
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 1, // Проверяем наличие хотя бы одного товара
    );
    $product_query = new WP_Query($args);

    if ($product_query->have_posts()) {
        include get_template_directory() . '/inc/market.php';
    }
    wp_reset_postdata();
    ?>
    <section id="comunnity" class="comunnity">
        <div class="comunnity__wrap-outer">
            <div class="comunnity__wrap-inner">
                <div class="container">
                    <div class="comunnity-grid">
                        <div class="comunnity-grid-content">
                            <?php if ($value = get_field('comunnity_title')) { ?>
                                <h4 class="classic-h4 comunnity-grid-content__title"><?= $value; ?></h4>
                            <?php } ?>
                            <?php if ($value = get_field('comunnity_txt')) { ?>
                                <p class="comunnity-grid-content__text"><?= $value; ?></p>
                            <?php } ?>
                            <a href="https://vk.com/club232636586" class="classic-btn comunnity-grid-content__btn" target="_blank">Быть в тренде</a>
                        </div>
                        <?php if ($value = get_field('comunnity_img')) { ?>
                            <div class="comunnity-grid-img">
                                <img src="<?= $value['url']; ?>" class="comunnity-grid-img__item" alt="<?= $value['alt']; ?>">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="bloggers" class="bloggers">
        <?php if (get_field('bloggers_title') || get_field('bloggers_logo')) { ?>
            <div class="container">
                <div class="bloggers-flexbox">
                    <?php if ($value = get_field('bloggers_logo')) { ?>
                        <div class="bloggers-flexbox__img-wrap">
                            <img src="<?= $value['url'] ?>" class="bloggers-flexbox__img" alt="<?= $value['alt'] ?>">
                        </div>
                        <?php if ($value = get_field('bloggers_title')) { ?>
                            <?= $value; ?>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php
        $args = array(
            'post_type' => 'blogger',
            'posts_per_page' => -1,
            'order' => 'ASC',
        );
        $blogger_query = new WP_Query($args);

        if ($blogger_query->have_posts()) { ?>
            <div class="bloggers__container">
                <div class="bloggers-gallery">
                    <div class="bloggers-gallery-slider">
                        <div class="swiper-wrapper">
                            <?php while ($blogger_query->have_posts()) {
                                $blogger_query->the_post(); ?>
                                <div class="swiper-slide">
                                    <?php if ($value = get_the_post_thumbnail_url($blogger_query->post->ID, 'full')) { ?>
                                        <a href="<?= (get_field('blogger_video')) ? get_field('blogger_video')['url'] : ''; ?>" data-fancybox="gallery" data-caption=""><img src="<?= $value; ?>" class="bloggers-gallery-slider__img" alt="<?= get_the_post_thumbnail_caption($blogger_query->post->ID) ?>"></a>
                                    <?php } else { ?>
                                        <img src="<?= get_template_directory_uri() ?>/img/bloggers/preview/default_bg.webp" class="bloggers-gallery-slider__img" alt="Обложка для видео блоггера по умолчанию">
                                    <?php } ?>

                                    <img src="<?= get_template_directory_uri() ?>/img/bloggers/right_arrow.svg" class="bloggers-gallery-slider__right-arrow" alt="Кнопка воспроизведения видео">
                                    <?php if ($value = get_field('blogger_link')) { ?>
                                        <a href="<?= $value; ?>" class="bloggers-gallery-slider__content-box-link" target="_blank">
                                            <div class="bloggers-gallery-slider__content">
                                                <?php if ($value = get_field('blogger_icon')) { ?>
                                                    <img src="<?= $value['url']; ?>" class="bloggers-gallery-slider__content-img" alt="<?= $value['alt']; ?>">
                                                <?php } ?>
                                                <div class="bloggers-gallery-slider__content-box">
                                                    <?php if ($value = get_the_title()) { ?>
                                                        <div class="bloggers-gallery-slider__content-box-title"><?= $value; ?></div>
                                                    <?php } ?>
                                                    <?php if ($value = get_field('blogger_link')) { ?>
                                                        <p class="bloggers-gallery-slider__content-box-text"><?= $value; ?></p>
                                                    <?php } ?>
                                                </div>
                                                <img src="<?= get_template_directory_uri() ?>/img/bloggers/simple_arrow.svg" alt="Иконка стрелки вправо, ссылка на блоггера">
                                            </div>
                                        </a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
        wp_reset_postdata();
        ?>
    </section>
    <section class="reviews">
        <?php if ($value = get_field('reviews_title')) { ?>
            <div class="container">
                <?= $value; ?>
            </div>
        <?php } ?>
        <?php
        $args = array(
            'post_type' => 'review',
            'posts_per_page' => -1,
            'order' => 'ASC',
        );
        $review_query = new WP_Query($args);
        if ($review_query->have_posts()) { ?>
            <div class="reviews__container">
                <div class="reviews-gallery">
                    <div class="reviews-gallery-slider">
                        <div class="swiper-wrapper">
                            <?php while ($review_query->have_posts()) {
                                $review_query->the_post(); ?>
                                <div class="swiper-slide">
                                    <div class="reviews-gallery-slider-box">
                                        <div class="reviews-gallery-slider-box__logo">
                                            <div class="reviews-gallery-slider-box__logo-inner">
                                                <?php if (get_field('reviews_sex') == "male") { ?>
                                                    <img src="<?= get_template_directory_uri() ?>/img/male.svg" class="reviews-gallery-slider-box__logo-inner-img" alt="Иконка автора отзыва мужчины">
                                                <?php } else { ?>
                                                    <img src="<?= get_template_directory_uri() ?>/img/female.svg" class="reviews-gallery-slider-box__logo-inner-img" alt="Иконка автора отзыва женщины">
                                                <?php } ?>
                                                <?php if ($value = get_field('reviews_marketplace')) { ?>
                                                    <div class="reviews-gallery-slider-box__logo-inner-marketplace"><?= $value; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="reviews-gallery-slider-box__content">
                                            <?php if ($value = get_the_title()) { ?>
                                                <div class="reviews-gallery-slider-box__content-title"><?= $value; ?></div>
                                            <?php } ?>
                                            <?php if ($value = get_field('reviews_txt')) { ?>
                                                <p class="reviews-gallery-slider-box__content-text"><?= $value; ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
        wp_reset_postdata();
        ?>
    </section>
    <section id="request" class="request">
        <div class="container">
            <div class="request-grid">
                <div class="request-grid-content">
                    <?php if ($value = get_field('request_title')) { ?>
                        <h4 class="classic-h4 request-grid-content__title"><?= $value; ?></h4>
                    <?php } ?>
                    <?php if ($value = get_field('request_text')) { ?>
                        <p class="request-grid-content__text"><?= $value; ?></p>
                    <?php } ?>
                    <div class="request-form">
                        <?php echo do_shortcode('[contact-form-7 id="3e7b3a4" title="Request Form"]') ?>
                    </div>
                </div>
                <div class="request-grid-bg">
                    <?php if ($value = get_field('request_img')) { ?>
                        <img src="<?= $value['url']; ?>" class="request-grid-bg__item" alt="<?= $value['alt']; ?>">
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>