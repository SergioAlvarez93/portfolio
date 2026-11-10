<?php
/*
Template Name: Главная
*/
?>
<?php get_header(); ?>
<main class="main">
    <?php if (get_field('presentation_title') || get_field('presentation_subtitle')) { ?>
        <section class="presentation" id="presentation">
            <div class="presentation__wrap">
                <div class="presentation-grid">
                    <div class="presentation-grid-content">
                        <?php if ($value = get_field('presentation_title')) { ?>
                            <h1 class="classic-h1 presentation-grid-content__title"><?= $value ?></h1>
                        <?php } ?>
                        <?php if ($value = get_field('presentation_subtitle')) { ?>
                            <p class="presentation-grid-content__subtitle"><?= $value ?></p>
                        <?php } ?>
                    </div>
                    <a href="<?= get_home_url(); ?>/#request" class="presentation-grid-button smooth">
                        <img src="<?= get_template_directory_uri(); ?>/img/presentation/camera.svg" class="presentation-grid-button__camera" alt="Значок камеры наблюдения">
                        <div class="presentation-grid-button__txt">Заказать аудит безопасности</div>
                        <img src="<?= get_template_directory_uri(); ?>/img/presentation/lock.svg" class="presentation-grid-button__lock" alt="Значок замка">
                    </a>
                    <?php if (get_field('presentation_bg') and get_field('presentation_bg_mob')) { ?>
                        <div class="presentation-grid-bg">
                            <picture class="presentation-grid-bg__img">
                                <source media="(max-width: 767px)" srcset="<?= get_field('presentation_bg_mob')['url']; ?>">
                                <img src="<?= get_field('presentation_bg')['url']; ?>" alt="<?= get_field('presentation_bg')['alt']; ?>">
                            </picture>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php if (get_field('about_title') || get_field('about_txt') || get_field('about_gallery')) { ?>
        <section class="about classic-black-line" id="about">
            <div class="container">
                <?php if ($value = get_field('about_title')) { ?>
                    <h2 class="classic-h2 about__title"><?= $value ?></h2>
                <?php } ?>
                <?php if ($value = get_field('about_txt')) { ?>
                    <p class="classic-txt about__txt"><?= $value ?></p>
                <?php } ?>
                <?php if ($images = get_field('about_gallery')) { ?>
                    <div class="about-slider">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $image) { ?>
                                <a href="<?= $image['url']; ?>" class="swiper-slide about-slider__slide" data-fancybox="about-slider">
                                    <img src="<?= $image['url']; ?>" class="about-slider__slide-img" alt="<?= $image['alt']; ?>">
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
    <?php if (get_field('services_title') || get_field('services_txt') || have_rows('services_repeater_st') || have_rows('services_repeater_nd')) { ?>
        <section class="services classic-black-line" id="services">
            <div class="container">
                <?php if ($value = get_field('services_title')) { ?>
                    <h2 class="classic-h2 services__title"><?= $value; ?></h2>
                <?php } ?>
                <?php if ($value = get_field('services_txt')) { ?>
                    <p class="classic-txt services__txt"><?= $value; ?></p>
                <?php } ?>
                <?php if (have_rows('services_repeater_st')) { ?>
                    <div class="classic-cards services-cards-st">
                        <?php if ($value = get_field('services_slider_title_st')) { ?>
                            <h3 class="classic-h3 services-cards-st__title"><?= $value; ?></h3>
                        <?php } ?>
                        <div class="classic-slider services-st-slider">
                            <div class="swiper-wrapper">
                                <?php while (have_rows('services_repeater_st')) {
                                    the_row(); ?>
                                    <div class="swiper-slide classic-slider__slide services-st-slider__slide">
                                        <?php if ($value = get_sub_field('img')) { ?>
                                            <a href="<?= $value['url'] ?>" class="classic-slider__slide-img-link services-st-slider__slide-img-link" data-fancybox="services-st-slider" data-caption="<?= $value['caption'] ?>">
                                                <img src="<?= $value['url'] ?>" class="classic-slider__slide-img services-st-slider__slide-img" alt="<?= $value['alt'] ?>">
                                            </a>
                                        <?php } ?>
                                        <?php if ($value = get_sub_field('txt')) { ?>
                                            <p class="classic-slider__slide-txt services-st-slider__slide-txt"><?= $value; ?></p>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if (have_rows('services_repeater_nd')) { ?>
                    <div class="classic-cards services-cards-nd">
                        <?php if ($value = get_field('services_slider_title_nd')) { ?>
                            <h3 class="classic-h3 services-cards-nd__title"><?= $value; ?></h3>
                        <?php } ?>
                        <div class="classic-slider services-nd-slider">
                            <div class="swiper-wrapper">
                                <?php while (have_rows('services_repeater_nd')) {
                                    the_row(); ?>
                                    <div class="swiper-slide classic-slider__slide services-nd-slider__slide">
                                        <?php if ($value = get_sub_field('img')) { ?>
                                            <a href="<?= $value['url'] ?>" class="classic-slider__slide-img-link services-nd-slider__slide-img-link" data-fancybox="services-nd-slider" data-caption="<?= $value['caption'] ?>">
                                                <img src="<?= $value['url'] ?>" class="classic-slider__slide-img services-nd-slider__slide-img" alt="<?= $value['alt'] ?>">
                                            </a>
                                        <?php } ?>
                                        <?php if ($value = get_sub_field('txt')) { ?>
                                            <p class="classic-slider__slide-txt services-nd-slider__slide-txt"><?= $value; ?></p>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
    <?php if (get_field('rules_title') || get_field('rules_txt') || have_rows('rules_repeater')) { ?>
        <section class="rules classic-black-line" id="rules">
            <div class="container">
                <?php if ($value = get_field('rules_title')) { ?>
                    <h2 class="classic-h2 rules__title"><?= $value; ?></h2>
                <?php } ?>
                <?php if ($value = get_field('rules_txt')) { ?>
                    <p class="classic-txt rules__txt"><?= $value; ?></p>
                <?php } ?>
                <?php if (have_rows('rules_repeater')) { ?>
                    <div class="classic-cards rules-cards">
                        <?php if ($value = get_field('rules_slider_title')) { ?>
                            <h3 class="classic-h3 rules-cards__title"><?= $value; ?></h3>
                        <?php } ?>
                        <div class="classic-slider rules-slider">
                            <div class="swiper-wrapper">
                                <?php while (have_rows('rules_repeater')) {
                                    the_row(); ?>
                                    <div class="swiper-slide classic-slider__slide rules-slider__slide">
                                        <?php if ($value = get_sub_field('img')) { ?>
                                            <a href="<?= $value['url'] ?>" class="classic-slider__slide-img-link rules-slider__slide-img-link" data-fancybox="rules-slider" data-caption="<?= $value['caption'] ?>">
                                                <img src="<?= $value['url'] ?>" class="classic-slider__slide-img rules-slider__slide-img" alt="<?= $value['alt'] ?>">
                                            </a>
                                            <?php if ($value = get_sub_field('txt')) { ?>
                                                <p class="classic-slider__slide-txt rules-slider__slide-txt"><?= $value; ?></p>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
    <?php if (get_field('advantages_title') || get_field('advantages_txt') || have_rows('advantages_list')) { ?>
        <section class="advantages classic-black-line" id="advantages">
            <div class="container">
                <?php if ($value = get_field('advantages_title')) { ?>
                    <h2 class="classic-h2 advantages__title"><?= $value; ?></h2>
                <?php } ?>
                <?php if ($value = get_field('advantages_txt')) { ?>
                    <p class="classic-txt advantages__txt"><?= $value; ?></p>
                <?php } ?>
                <?php if (have_rows('advantages_list')) { ?>
                    <div class="advantages-list">
                        <?php while (have_rows('advantages_list')) {
                            the_row(); ?>
                            <div class="advantages-list__item">
                                <?php if ($value = get_sub_field('icon')) { ?>
                                    <div class="advantages-list__item-img-wrap">
                                        <img src="<?= $value['url']; ?>" class="advantages-list__item-img" alt="<?= $value['alt']; ?>">
                                    </div>
                                <?php } ?>
                                <?php if ($value = get_sub_field('txt')) { ?>
                                    <p class="advantages-list__item-txt"><?= $value; ?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
    <?php if (get_field('partners_title') || get_field('partners_txt') || have_rows('partners_list')) { ?>
        <section class="partners" id="partners">
            <div class="container">
                <?php if ($value = get_field('partners_title')) { ?>
                    <h2 class="classic-h2 partners__title"><?= $value; ?></h2>
                <?php } ?>
                <?php if ($value = get_field('partners_txt')) { ?>
                    <p class="classic-txt partners__txt"><?= $value; ?></p>
                <?php } ?>
                <?php if (have_rows('partners_list')) { ?>
                    <div class="partners-list">
                        <?php while (have_rows('partners_list')) {
                            the_row(); ?>
                            <div class="partners-list__item">
                                <?php if ($value = get_sub_field('name')) { ?>
                                    <div class="partners-list__item-name" data-desctop-font="<?= get_sub_field('desctop_font') ?>" data-mobile-font="<?= get_sub_field('mobile_font') ?>"><?= $value; ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
    <?php
    $form = wpcf7_get_contact_form_by_hash('8e70ca4');
    if ($form) { ?>
        <section class="request" id="request">
            <div class="container">
                <div class="request-flexbox">
                    <div class="request-flexbox__content">
                        <?php if ($value = get_field('request_title')) { ?>
                            <h2 class="classic-h2 request-flexbox__content-title"><?= $value; ?></h2>
                        <?php } ?>
                        <?php if ($value = get_field('request_txt')) { ?>
                            <p class="classic-txt request-flexbox__content-txt"><?= $value; ?></p>
                        <?php } ?>
                        <div class="request-form">
                            <?php echo do_shortcode('[contact-form-7 id="8e70ca4" title="Contact form 1"]'); ?>
                        </div>
                    </div>
                    <?php if ($value = get_field('request_img')) { ?>
                        <div class="request-flexbox__img-wrap">
                            <img src="<?= $value['url'] ?>" class="request-flexbox__img" alt="<?= $value['alt'] ?>">
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php  } ?>
</main>
<?php get_footer(); ?>