<?php
/*
Template Name: Главная
*/
?>
<?php get_header(); ?>
<main class="main">
    <section class="presentation">
        <div class="presentation-grid">
            <div class="presentation-bg">
                <?php /* <picture>
                    <source srcset="<?= get_template_directory_uri(); ?>/img/presentation/presentation_bg.png" media="(min-width: 768px)" />
                    <img src="<?= get_template_directory_uri(); ?>/img/presentation/presentation_mob_bg.png" alt="Фон секции презентации" />
                </picture> */ ?>
                <?php if ($value = get_field('presentation_video')) { ?>
                    <video autoplay loop muted playsinline class="presentation-bg__video">
                        <source src="<?= $value['url']; ?>" type="video/mp4">
                    </video>
                <?php } ?>
                <?php if ($value = get_field('presentation_video_mob')) { ?>
                    <video autoplay loop muted playsinline class="presentation-bg__video presentation-bg__video_mob">
                        <source src="<?= $value['url']; ?>" type="video/mp4">
                    </video>
                <?php } ?>
            </div>
            <div class="container">
                <a href="<?= get_home_url(); ?>/#request" class="classic-btn presentation-btn">Обратная связь</a>
            </div>
        </div>
    </section>
    <?php if (have_rows('realisation_forms')) { ?>
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
    <?php } ?>
    <section class="purpose">
        <div class="container">
            <?php if ($value = get_field('purpose_title')) { ?>
                <?= $value; ?>
            <?php } ?>
            <?php /* СТАРАЯ ВЕРСИЯ PURPOSE
            if (have_rows('purpose_items')) { ?>
                <div class="purpose-grid">
                    <?php while (have_rows('purpose_items')) {
                        the_row(); ?>
                        <div class="purpose-grid__item purpose-grid-inner" data-block-type="<?= get_sub_field('item_type') ?>">
                            <?php if (get_sub_field('title') || get_sub_field('icon')) { ?>
                                <div class="purpose-grid-inner__content-wrap">
                                    <?php if ($value = get_sub_field('title')) { ?>
                                        <div class="purpose-grid-inner__txt"><?= $value; ?></div>
                                    <?php } ?>
                                    <?php if ($value = get_sub_field('icon')) { ?>
                                        <img class="purpose-grid-inner__icon" src="<?= $value['url']; ?>" alt="<?= $value['alt']; ?>">
                                    <?php } ?>
                                </div>
                                <?php if (get_sub_field('vid') || get_sub_field('vid_mob')) { ?>
                                    <div class="purpose-grid-inner__img-wrap">
                                        <?php /* <picture>
                                            <?php if ($mobImg = get_sub_field('img_mob')) { ?>
                                                <source srcset="<?= $mobImg['url']; ?>" alt="<?= $mobImg['alt']; ?>" media="(max-width: 767px)">
                                            <?php } ?>
                                            <img src="<?= $value['url']; ?>" class="purpose-grid-inner__img" alt="<?= $value['alt']; ?>">
                                        </picture>
                                                */ /*?>
                                        <?php /* if ($value = get_sub_field('vid')) { ?>
                                            <video autoplay loop muted playsinline class="purpose-grid-inner__video">
                                                <source src="<?= $value['url'] ?>" type="video/mp4">
                                            </video>
                                        <?php } ?>
                                        <?php if ($value = get_sub_field('vid_mob')) { ?>
                                            <video autoplay loop muted playsinline class="purpose-grid-inner__video_mob">
                                                <source src="<?= $value['url'] ?>" type="video/mp4">
                                            </video>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } */

            $images = get_field('purpose_gallery');

            if ($images) { ?>
                <div class="purpose-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image) { ?>
                            <div class="swiper-slide purpose-swiper-slide">
                                <img src="<?php echo $image['url']; ?>" class="purpose-swiper-slide__img" alt="<?php echo $image['alt']; ?>" />
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
    <section class="advantages">
        <div class="container">
            <?php
            if (have_rows('advantages_items')) { ?>
                <div class="advantages-grid">
                    <?php while (have_rows('advantages_items')) {
                        the_row(); ?>
                        <?php if (get_sub_field('img') || get_sub_field('title') || get_sub_field('txt')) { ?>
                            <article class="advantages-grid__item">
                                <?php if ($value = get_sub_field('img')) { ?>
                                    <img src="<?= $value['url']; ?>" class="advantages-grid__item-img" alt="<?= $value['alt']; ?>">
                                <?php } ?>
                                <?php if ($value = get_sub_field('title')) { ?>
                                    <h3 class="classic-h3 advantages-grid__item-title"><?= $value; ?></h3>
                                <?php } ?>
                                <?php if ($value = get_sub_field('txt')) { ?>
                                    <p class="advantages-grid__item-txt"><?= $value; ?></p>
                                <?php } ?>
                            </article>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if (get_field('advantages_title') || have_rows('advantages_icons')) { ?>
                <div class="advantages-flexbox">
                    <?php if ($value = get_field('advantages_title')) { ?>
                        <h3 class="classic-h3 advantages-flexbox__title"><?= $value; ?></h3>
                    <?php } ?>
                    <?php
                    if (have_rows('advantages_icons')) { ?>
                        <div class="advantages-icons">
                            <div class="advantages-icons__wrap">
                                <?php while (have_rows('advantages_icons')) {
                                    the_row(); ?>
                                    <?php if (get_sub_field('icon') and get_sub_field('width')) { ?>
                                        <div class="advantages-icons__item-box" style="max-width: <?= get_sub_field('width'); ?>;">
                                            <img src="<?= get_sub_field('icon')['url']; ?>" class="advantages-icons__item" alt="<?= get_sub_field('icon')['alt']; ?>">
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </section>
    <?php
    if (has_showcase_products()) {
        include get_template_directory() . '/inc/showcase.php';
    }
    ?>
    <?php
    if (has_showcase_products()) {
        include get_template_directory() . '/inc/market.php';
    }
    ?>
    <section class="choice">
        <div class="container">
            <?php if (get_field('choice_title') || get_field('choice_txt')) { ?>
                <div class="choice__flexbox">
                    <?php if ($value = get_field('choice_title')) { ?>
                        <h2 class="classic-h2 choice__title"><?= $value; ?></h2>
                    <?php } ?>
                    <?php if ($value = get_field('choice_txt')) { ?>
                        <p class="choice__txt"><?= $value; ?></p>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php
            if (have_rows('choice-repeater')) { ?>
                <div class="choice-grid">
                    <?php while (have_rows('choice-repeater')) {
                        the_row(); ?>
                        <?php if ($img = get_sub_field('img')) { ?>
                            <div class="choice-grid__item"
                                <?php if ($value = get_sub_field('col')) { ?>
                                data-col="<?= $value; ?>"
                                <?php } ?>
                                <?php if ($value = get_sub_field('col_mob')) { ?>
                                data-col-mob="<?= $value; ?>"
                                <?php } ?>
                                <?php if ($value = get_sub_field('row')) { ?>
                                data-row="<?= $value; ?>"
                                <?php } ?>
                                <?php if ($value = get_sub_field('row_mob')) { ?>
                                data-row-mob="<?= $value; ?>"
                                <?php } ?>
                                <?php if ($value = get_sub_field('align')) { ?>
                                data-align="<?= $value; ?>"
                                <?php } ?>
                                <?php if ($value = get_sub_field('display_mob')) { ?>
                                data-display-mob="<?= $value; ?>"
                                <?php } ?>>
                                <img src="<?= $img['url']; ?>" class="choice-grid__item-img" alt="<?= $img['alt'] ?>">
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </section>
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
                            <a href="https://t.me/biocordsport" class="classic-btn comunnity-grid-content__btn" target="_blank">Подписаться</a>
                        </div>
                        <?php if ($value = get_field('comunnity_img')) { ?>
                            <picture class="comunnity-grid-img">
                                <?php if ($mobImg = get_field('comunnity_img_mob')) { ?>
                                    <source srcset="<?= $mobImg['url']; ?>" alt="<?= $mobImg['alt']; ?>" media="(max-width: 767px)">
                                <?php } ?>
                                <img src="<?= $value['url']; ?>" class="comunnity-grid-img__item" alt="<?= $value['alt']; ?>">
                            </picture>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="bloggers" class="bloggers">
        <?php if ($value = get_field('bloggers_title')) { ?>
            <div class="container">
                <?= $value; ?>
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
                                        <a href="<?= $value['url']; ?>" class="bloggers-gallery-slider__content-box-link" target="_blank">
                                            <div class="bloggers-gallery-slider__content">
                                                <?php if ($value = get_field('blogger_icon')) { ?>
                                                    <img src="<?= $value['url']; ?>" class="bloggers-gallery-slider__content-img" alt="<?= $value['alt']; ?>">
                                                <?php } ?>
                                                <div class="bloggers-gallery-slider__content-box">
                                                    <?php if ($value = get_the_title()) { ?>
                                                        <div class="bloggers-gallery-slider__content-box-title"><?= $value; ?></div>
                                                    <?php } ?>
                                                    <?php if ($value = get_field('blogger_link')) { ?>
                                                        <p class="bloggers-gallery-slider__content-box-text"><?= $value['url']; ?></p>
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
    <section class="reviews" id="reviews">
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
                                                <?php if ($value = get_field('reviews_icon')) { ?>
                                                    <img src="<?= $value['url']; ?>" class="reviews-gallery-slider-box__logo-inner-img" alt="<?= $value['alt']; ?>">
                                                <?php } else { ?>
                                                    <img src="<?= get_template_directory_uri() ?>/img/default_icon_review.svg" class="reviews-gallery-slider-box__logo-inner-img" alt="Иконка автора отзыва по умолчанию">
                                                <?php } ?>
                                                <?php /* if ($value = get_field('reviews_marketplace')) { ?>
                                                    <div class="reviews-gallery-slider-box__logo-inner-marketplace"><?= $value; ?></div>
                                                <?php } */ ?>
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
    <section class="request" id="request">
        <div class="container">
            <div class="request-grid">
                <div class="request-grid-bg">
                    <?php if ($value = get_field('request_img')) { ?>
                        <img src="<?= $value['url']; ?>" class="request-grid-bg__item" alt="<?= $value['alt']; ?>">
                    <?php } ?>
                </div>
                <div class="request-grid-content">
                    <?php if ($value = get_field('request_title')) { ?>
                        <h4 class="classic-h4 request-grid-content__title"><?= $value; ?></h4>
                    <?php } ?>
                    <div class="request-form">
                        <?php echo do_shortcode('[contact-form-7 id="477a1fb" title="Заявочная форма"]') ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>