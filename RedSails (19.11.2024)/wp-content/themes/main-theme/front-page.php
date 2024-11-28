<?php
/*
Template Name: Главная
*/
?>
<?php get_header(); ?>
<main class="main">
    <section id="summary" class="summary">
        <?php if ($value = get_the_post_thumbnail_url()) { ?>
            <picture class="summary__bg">
                <source srcset="<?= $value; ?>" media="(min-width: 992px)">
                <?php if ($mobBg = get_field('mobile_background')) { ?>
                    <img class="summary__bg-img" src="<?= $mobBg; ?>" alt="catamaran">
                <?php } ?>
            </picture>
        <?php } ?>
        <div class="summary__container">
            <div class="summury-content-fb">
                <div class="classic-h4-box summury-content-fb__subtitle">
                    <div class="line"></div>
                    <h4 class="classic-h4 classic-h4__general summury-content-fb__subtitle-txt">luxury catamaran in phuket area</h4>
                </div>
                <?php if (get_the_title()) { ?>
                    <h1 class="title-h1 summury-content-fb__title"><?php the_title(); ?></h1>
                <?php } ?>
                <?php if (get_the_content()) { ?>
                    <p class="classic-txt summury-content-fb__txt"><?php the_content(); ?></p>
                <?php } ?>
                <a href="#scheme" class="summury-content-fb__link">learn more</a>
            </div>
        </div>
    </section>
    <?php
    if (have_rows('characteristic_repeater')) {
    ?>
        <section id="characteristic" class="characteristic">
            <div class="characteristic__container">
                <div class="characteristic-flexbox">
                    <?php while (have_rows('characteristic_repeater')) {
                        the_row();
                    ?>
                        <div class="characteristic-flexbox__item">
                            <div class="characteristic-flexbox__item-unit"><?= get_sub_field('unit'); ?><?php if ($value = get_sub_field('measure')) { ?><span class="characteristic-flexbox__item-measure"><?= $value; ?></span><?php } ?></div>
                            <div class="characteristic-flexbox__item-desc"><?= get_sub_field('desc'); ?></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } ?>
    <section id="scheme" class="scheme">
        <div class="scheme__container">
            <div class="scheme-top-flexbox">
                <?php if ($value = get_field('scheme_img')) { ?>
                    <div class="scheme-top-flexbox__img-wrap">
                        <img src="<?= $value; ?>" class="scheme-top-flexbox__img" alt="catamaran-scheme">
                    </div>
                <?php } ?>
                <?php if ($value = get_field('scheme_title') || $value = get_field('scheme_txt')) { ?>
                    <div class="scheme-top-content">
                        <?php if ($value = get_field('scheme_subtitle')) { ?>
                            <div class="classic-h4-box scheme-top-content__subtitle">
                                <div class="line"></div>
                                <h4 class="classic-h4 scheme-top-content__subtitle-txt"><?= $value; ?></h4>
                            </div>
                        <?php } ?>
                        <?php if ($value = get_field('scheme_title')) { ?>
                            <h2 class="classic-h2 scheme-top-content__title"><?= $value; ?></h2>
                        <?php } ?>
                        <?php if ($value = get_field('scheme_txt')) { ?>
                            <p class="classic-txt classic-txt__black scheme-top-content__txt"><?= $value; ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <?php
            if (have_rows('scheme_repeater')) {
            ?>
                <?php while (have_rows('scheme_repeater')) {
                    the_row(); ?>
                    <div class="scheme-gallery">
                        <?php $gallery = get_sub_field('scheme_gal');
                        foreach ($gallery as $image) {
                            $image_url = $image['url']
                        ?>
                            <div class="scheme-gallery__item">
                                <img src="<?= esc_url($image_url); ?>" class="scheme-gallery__item-img" alt="<?= esc_attr($image['alt']); ?>">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </section>
    <section id="presentation" class="presentation">
        <div class="presentation__container">
            <?php if ($value = get_field('presentation_subtitle')) { ?>
                <div class="classic-h4-box presentation__subtitle">
                    <div class="line"></div>
                    <h4 class="classic-h4 presentation__subtitle-txt"><?= $value; ?> </h4>
                </div>
            <?php } ?>
            <?php if ($value = get_field('presentation_title')) { ?>
                <h2 class="classic-h2 presentation__tltle"><?= $value; ?></h2>
            <?php } ?>
            <?php if ($value = get_field('presentation_txt')) { ?>
                <p class="classic-txt classic-txt__black presentation__txt"><?= $value; ?></p>
            <?php } ?>
            <?php if ($value = get_field('presentation_gal')) { ?>
                <div class="presentation-slider__nav">
                    <?php if ($value = get_field('presentation_gal_title')) { ?>
                        <h3 class="classic-h3 presentation-slider__nav-title"><?= $value; ?></h3>
                    <?php } ?>
                    <div class="presentation-slider__nav-arrows">
                        <div class="presentation-slider__nav-arrow-wrap">
                            <img src="<?= get_template_directory_uri(); ?>/img/circle-arrow-left.png" class="presentation-slider__nav-arrow-left" alt="presentation-left-arrow">
                        </div>
                        <div class="presentation-slider__nav-arrow-wrap">
                            <img src="<?= get_template_directory_uri(); ?>/img/circle-arrow-right.png" class="presentation-slider__nav-arrow-right" alt="presentation-right-arrow">
                        </div>
                    </div>
                </div>
                <div class="presentation-slider">
                    <div class="swiper-wrapper">
                        <?php $gallery = get_field('presentation_gal');
                        foreach ($gallery as $image) {
                            $image_url = $image['url']
                        ?>
                            <div class="swiper-slide presentation-slider-slide">
                                <div class="presentation-slider__img-wrap">
                                    <img src="<?= esc_url($image_url); ?>" class="presentation-slider__img" alt="<?= esc_attr($image['alt']); ?>" />
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (get_field('presentation_nav_title') || get_field('presentation_cabin_title') || get_field('presentation_entertainment_title')) { ?>
                <div class="presentation-grid">
                    <?php if ($value = get_field('presentation_nav_title')) { ?>
                        <div class="presentation-grid__item">
                            <div class="presentation-grid__nav">
                                <?= $value; ?>
                            </div>
                            <?php
                            if (have_rows('presentation_nav_repeater')) {
                            ?>
                                <ul class="presentation-grid__nav-list">
                                    <?php while (have_rows('presentation_nav_repeater')) {
                                        the_row();
                                    ?>
                                        <li class="presentation-grid__nav-list-item">
                                            <img src="<?= get_sub_field('icon');  ?>" class="presentation-grid__nav-list-img" alt="">
                                            <div class="presentation-grid__nav-list-char"><?= get_sub_field('char'); ?></div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if ($value = get_field('presentation_cabin_title')) { ?>
                        <div class="presentation-grid__item">
                            <div class="presentation-grid__cabin">
                                <?= $value; ?>
                            </div>
                            <?php
                            if (have_rows('presentation_cabin_repeater')) {
                            ?>
                                <ul class="presentation-grid__cabin-list">
                                    <?php while (have_rows('presentation_cabin_repeater')) {
                                        the_row();
                                    ?>
                                        <li class="presentation-grid__cabin-list-item">
                                            <img src="<?= get_sub_field('icon');  ?>" class="presentation-grid__cabin-list-img" alt="">
                                            <div class="presentation-grid__cabin-list-char"><?= get_sub_field('char'); ?></div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if ($value = get_field('presentation_entertainment_title')) { ?>
                        <div class="presentation-grid__item">
                            <div class="presentation-grid__entertainment">
                                <?= $value; ?>
                            </div>
                            <?php
                            if (have_rows('presentation_entertainment_repeater')) {
                            ?>
                                <ul class="presentation-grid__entertainment-list">
                                    <?php while (have_rows('presentation_entertainment_repeater')) {
                                        the_row();
                                    ?>
                                        <li class="presentation-grid__entertainment-list-item">
                                            <img src="<?= get_sub_field('icon');  ?>" class="presentation-grid__entertainment-list-img" alt="">
                                            <div class="presentation-grid__entertainment-list-char"><?= get_sub_field('char'); ?></div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                    <?php } ?>

                <?php } ?>
    </section>
    <?php
    if (have_rows('explore_repeater')) {
    ?>
        <section id="explore" class="explore">
            <div class="explore-slider">
                <div class="swiper-wrapper">
                    <?php while (have_rows('explore_repeater')) {
                        the_row();
                    ?>
                        <div class="swiper-slide explore-slider-slide">
                            <div class="explore-grid">
                                <?php if ($value = get_sub_field('background')) {
                                    $valueAlt = $value['alt'];
                                    $valueUrl = $value['url'] ?>
                                    <div class="explore-grid__background">
                                        <img src="<?= esc_url($valueUrl); ?>" class="explore-grid__background-img" alt="<?= esc_attr($valueAlt); ?>">
                                    </div>
                                <?php } ?>
                                <?php if (get_sub_field('title') || get_sub_field('txt') || get_sub_field('url')) { ?>
                                    <div class="explore-grid__content">
                                        <?php if ($value = get_sub_field('title')) { ?>
                                            <div class="explore-grid__content-title-box">
                                                <h2 class="classic-h2 explore-grid__content-title"><?= $value; ?></h2>
                                                <div class="explore-grid__content-title-line"></div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($value = get_sub_field('txt')) { ?>
                                            <p class="explore-grid__content-txt"><?= $value; ?> </p>
                                        <?php } ?>
                                        <div class="explore-grid__content-link-nav">
                                            <?php if ($value = get_sub_field('url')) { ?>
                                                <a href="<?= $value; ?>" class="explore-grid__content-link">See all</a>
                                            <?php } ?>
                                            <div class="explore-grid__content-nav">
                                                <div class="explore-grid__content-nav-wrap">
                                                    <img src="<?= get_template_directory_uri(); ?>/img/circle-arrow-left.png" class="explore-grid__content-nav-left" alt="">
                                                </div>
                                                <div class="explore-grid__content-nav-wrap">
                                                    <img src="<?= get_template_directory_uri(); ?>/img/circle-arrow-right.png" class="explore-grid__content-nav-right" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php if (get_field('impressions_subtitle') || get_field('impressions_title') || get_field('impressions_txt')) { ?>
        <section id="impressions" class="impressions">
            <div class="impressions__container">
                <?php if ($value = get_field('impressions_subtitle')) { ?>
                    <div class="classic-h4-box impressions__subtitle">
                        <div class="line"></div>
                        <h4 class="classic-h4 impressions__subtitle-txt"><?= $value; ?></h4>
                    </div>
                <?php } ?>
                <?php if ($value = get_field('impressions_title')) { ?>
                    <h2 class="classic-h2 impressions__title"><?= $value; ?></h2>
                <?php } ?>
                <?php if ($value = get_field('impressions_txt')) { ?>
                    <p class="classic-txt classic-txt__black impressions__txt"><?= $value; ?></p>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
    <?php
    if (get_field('table_title') || have_rows('table_repeater')) {
    ?>
        <section id="table" class="table">
            <div class="table__container">
                <div class="table-wrap-grid">
                    <?php if ($value = get_field('table_title')) { ?>
                        <div class="table-wrap-grid__title-wrap">
                            <h3 class="table-wrap-grid__title"><?= $value; ?></h3>
                        </div>
                    <?php } ?>
                    <?php
                    if (have_rows('table_repeater')) {
                    ?>
                        <div class="table-wrap-grid__repeater">
                            <?php while (have_rows('table_repeater')) {
                                the_row();
                            ?>
                                <div class="table-grid">
                                    <?php if ($value = get_sub_field('name')) { ?>
                                        <div class="table-grid__item-st"><?= $value; ?></div>
                                    <?php } ?>
                                    <?php if ($value = get_sub_field('value')) { ?>
                                        <div class="table-grid__item-nd"><?= $value; ?></div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php
    if (have_rows('testimonials_list') || get_field('testimonials__title')) {
    ?>
        <section id="testimonials" class="testimonials">
            <?php if ($valueDesk = get_field('testimonials_desktop_bg') and $valueMob = get_field('testimonials_mob_bg')) { ?>
                <picture class="testimonials__bg">
                    <source srcset="<?= $valueDesk; ?>" media="(min-width: 768px)">
                    <img class="testimonials__bg-img" src="<?= $valueMob; ?>" alt="sea">
                </picture>
            <?php } ?>
            <div class="testimonials__container">
                <div class="testimonials-slider__nav">
                    <?php if ($value = get_field('testimonials__title')) { ?>
                        <h3 class="classic-h2 testimonials__title"><?= $value; ?></h3>
                    <?php } ?>
                    <?php if (have_rows('testimonials_list')) { ?>
                        <div class="testimonials-slider__nav-box">
                            <div class="testimonials-slider__nav-box-link">
                                <picture>
                                    <source srcset="<?= get_template_directory_uri(); ?>/img/testimonials_arrow_left.png" media="(min-width: 768px)">
                                    <img class="testimonials-slider__nav-box-link-left" src="<?= get_template_directory_uri(); ?>/img/circle-arrow-left.png" alt="left-testimonials-nav">
                                </picture>
                            </div>
                            <div class="testimonials-slider__nav-box-link">
                                <picture>
                                    <source srcset="<?= get_template_directory_uri(); ?>/img/testimonials_arrow_right.png" media="(min-width: 768px)">
                                    <img class="testimonials-slider__nav-box-link-right" src="<?= get_template_directory_uri(); ?>/img/circle-arrow-right.png" alt="right-testimonials-nav">
                                </picture>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="testimonials-slider">
                    <div class="swiper-wrapper">
                        <?php while (have_rows('testimonials_list')) {
                            the_row();
                        ?>
                            <div class="swiper-slide testimonials-slider-slide">
                                <?php if ($value = get_sub_field('photo')) { ?>
                                    <div class="testimonials-slider-card__photo-wrap">
                                        <img src="<?= $value; ?>" class="testimonials-slider-card__photo" alt="photo">
                                    </div>
                                <?php } ?>
                                <div class="testimonials-slider-card">
                                    <?php if (get_sub_field('name') || get_sub_field('prof') || get_sub_field('rating')) { ?>
                                        <div class="testimonials-slider-card-flexbox">
                                            <?php if (get_sub_field('name') || get_sub_field('prof')) { ?>
                                                <div class="testimonials-slider-card-flexbox__person-box">
                                                    <?php if ($value = get_sub_field('name')) { ?>
                                                        <div class="testimonials-slider-card-flexbox__person-name"><?= $value; ?></div>
                                                    <?php } ?>
                                                    <?php if ($value = get_sub_field('prof')) { ?>
                                                        <div class="testimonials-slider-card-flexbox__person-prof"><?= $value; ?></div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <?php $rating = get_sub_field('rating');
                                            if ($rating && is_numeric($rating)) { ?>
                                                <div class="testimonials-slider-rating">
                                                    <?php for ($i = 0; $i < $rating; $i++) { ?>
                                                        <div class="testimonials-slider-rating__img-wrap">
                                                            <img src="<?= get_template_directory_uri(); ?>/img/star.png" class="testimonials-slider-rating__img" alt="rating">
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($value = get_sub_field('testimonial')) { ?>
                                            <p class="testimonials-slider-card__txt"><?= $value; ?></p>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php if (shortcode_exists('contact-form-7')) { ?>
        <section id="form-section" class="form-section">
            <?php if ($valueDesk = get_field('testimonials_desktop_bg') and $valueMob = get_field('testimonials_mob_bg')) { ?>
                <picture class="form-section__bg">
                    <source srcset="<?= $valueDesk; ?>" media="(min-width: 768px)">
                    <img class="form-section__bg-img" src="<?= $valueMob; ?>" alt="sea">
                </picture>
            <?php } ?>
            <div class="form-section__container">
                <div class="form-container">
                    <?php echo do_shortcode('[contact-form-7 id="5dec3d0" title="Форма"]'); ?>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php include 'inc/form-main.php'; ?>
</main>
<?php get_footer(); ?>