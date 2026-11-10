<?php
/*
Template Name: Главная
*/
?>
<?php get_header(); ?>
<main class="main">
    <section class="greeting">
        <div class="greeting__content">
            <?php if ($value = get_field('greeting_img')) { ?>
                <img src=<?= $value; ?> class="greeting__content-img" alt="" />
            <?php } ?>
            <div class="greeting__content-txt-block">
                <?php if ($value = get_field('greeting_title')) { ?>
                    <h1 class="greeting__content-main-title">
                        <?= $value; ?>
                    </h1>
                <?php } ?>
                <a href=<?= get_home_url() . "/excursion" ?> class="greeting__content-request">Записаться на экскурсию</a>
            </div>
        </div>
    </section>
    <section class="summary">
        <div class="summary__container">
            <div class="summary-grid">
                <?php
                if (have_rows('summary_repeater')) {
                ?>
                    <?php while (have_rows('summary_repeater')) {
                        the_row();
                    ?>
                        <div class="summary-grid__item">
                            <?php if ($value = get_sub_field('title')) { ?>
                                <h3 class="summary-grid__item-title"><?= $value; ?></h3>
                            <?php } ?>
                            <?php if ($value = get_sub_field('text')) { ?>
                                <p class="summary-grid__item-text basic-font-txt">
                                    <?= $value; ?>
                                </p>
                            <?php } ?>
                            <?php if ($value = get_sub_field('link')) { ?>
                                <a href="<?= $value; ?>" class="summary-grid__item-btn more-btn scroll">Подробнее</a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </section>
    <section class="about" id="about">
        <div class="about__container">
            <?php if ($value = get_field('about_title')) { ?>
                <h2 class="about__title basic-font-h2"><?= $value; ?></h2>
            <?php } ?>
            <?php if ($value = get_field('about_text')) { ?>
                <!-- prettier-ignore -->
                <p class="about__text basic-font-txt"><?= $value; ?>
                </p>
            <?php } ?>
        </div>
    </section>
    <section class="mission" id="mission">
        <div class="mission__content">
            <div class="mission-slider__wrap">
                <div class="mission-slider">
                    <div class="swiper-wrapper">
                        <?php $images = get_field('mission_gallery');
                        if ($images) { ?>
                            <?php foreach ($images as $image) { ?>
                                <div class="mission-slider-slide swiper-slide" data-swiper-autoplay="5000">
                                    <img src=<?= $image['url']; ?> class="mission-slider-slide__img" alt="" />
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="mission-txt-block">
                <?php if ($value = get_field('mission_title')) { ?>
                    <h2 class="mission-txt-block__title basic-font-h2"><?= $value; ?></h2>
                <?php } ?>
                <?php if ($value = get_field('mission_text')) { ?>
                    <p class="mission-txt-block__text basic-font-txt">
                        <?= $value; ?>
                    </p>
                <?php } ?>
                <a href=<?= get_home_url() . "/mission" ?> class="more-btn mission-txt-block__btn">Подробнее</a>
            </div>
            <div class="mission-contacts-block">
                <div class="mission-contacts-block-flexbox">
                    <a href="#map" class="mission-contacts-block-flexbox__item scroll">КАРТА ПРОЕЗДА</a>
                    <?php if ($contact = get_field('general_tel', 'options')) { ?>
                        <a href="tel:<?php echo preg_replace('/[^0-9\+]/', '', $contact); ?>" class="mission-contacts-block-flexbox__item" target="_blank">ТЕЛЕФОН: <?php echo $contact; ?></a>
                    <?php } ?>
                    <a href="#contacts" class="mission-contacts-block-flexbox__item scroll">КОНТАКТЫ</a>
                </div>
            </div>
        </div>
    </section>
    <?php if ($value = get_field('map')) { ?>
        <section class="map" id="map">
            <?= $value; ?>
        </section>
    <?php } ?>
    <section class="proggrams-preview" id="programms">
        <div class="proggrams-preview__content">
            <?php if ($value = get_field('programms_preview_title')) { ?>
                <h2 class="proggrams-preview__title basic-font-h2"><?= $value; ?></h2>
            <?php } ?>
            <?php if ($value = get_field('programms_preview_text')) { ?>
                <!-- prettier-ignore -->
                <p class="proggrams-preview__text basic-font-txt"><?= $value; ?></p>
            <?php } ?>
        </div>
    </section>
    <section class="proggrams">
        <div class="proggrams__container">
            <div class="proggrams-grid">
                <?php
                $args = array(
                    'post_type' => 'programm',
                    'posts_per_page' => 0,
                    'order' => 'ASC',
                );
                $query = new WP_Query($args);
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post(); ?>
                        <div class="proggrams-grid__item">
                            <?php if ($value = get_field('programm_title')) { ?>
                                <h3 class="proggrams-grid__item-title basic-font-txt">
                                    <?= $value; ?>
                                </h3>
                            <?php } ?>
                            <?php if ($value = get_field('programm_adress')) { ?>
                                <div class="proggrams-grid__item-adress"><?= $value; ?></div>
                            <?php } ?>
                            <?php if ($value = get_field('programm_short_text')) { ?>
                                <!-- prettier-ignore -->
                                <p class="proggrams-grid__text basic-font-txt"><?= $value; ?></p>
                            <?php } ?>
                            <?php if ($value = get_field('programm_quote') || $value = get_field('programm_author') || $value = get_field('programm_full_text')) { ?>
                                <a href="<?= get_permalink(); ?>" class="proggrams-grid__btn more-btn">ПОДРОБНЕЕ</a>
                            <?php } ?>
                        </div>
                <?php }
                    wp_reset_postdata();
                }
                ?>
            </div>
        </div>
    </section>
    <section class="teachers" id="teachers">
        <section class="teachers__container">
            <?php if ($value = get_field('teachers_title')) { ?>
                <h2 class="basic-font-h2 teachers__title"><?= $value; ?></h2>
            <?php } ?>
            <?php if ($value = get_field('teachers_text')) { ?>
                <p class="basic-font-txt teachers__text">
                    <?= $value; ?>
                </p>
            <?php } ?>
            <div class="teachers-grid">
                <?php
                if (have_rows('teachers_repeater')) {
                ?>
                    <?php while (have_rows('teachers_repeater')) {
                        the_row();
                    ?>
                        <div class="teachers-grid__item" data-sal="slide-up" data-sal-duration="2000" data-sal-easing="ease-out-back">
                            <?php if ($value = get_sub_field('img')) { ?>
                                <div class="teachers-grid__item-img-wrap">
                                    <img src=<?= $value; ?> class="teachers-grid__item-img" alt="" />
                                </div>
                            <?php } ?>
                            <?php if ($value = get_sub_field('name')) { ?>
                                <div class="teachers-grid__item-name">
                                    <?= $value; ?>
                                </div>
                            <?php } ?>
                            <?php if ($value = get_sub_field('short_info')) { ?>
                                <div class="teachers-grid__item-position">
                                    <?= $value; ?>
                                </div>
                            <?php } ?>
                            <?php if ($value = get_sub_field('diploma')) { ?>
                                <div class="teachers-grid__item-grade">
                                    <?= $value; ?>
                                </div>
                            <?php } ?>
                            <?php if ($value = get_sub_field('full_info')) { ?>
                                <!-- prettier-ignore -->
                                <p class="basic-font-txt teachers-grid__info"><?= $value; ?>
                                </p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </section>
    </section>
    <section class="news" id="news">
        <div class="news__container">
            <h2 class="news__title basic-font-h2">
                НОВОСТИ И ОТЗЫВЫ ИЗ СОЦИАЛЬНЫХ СЕТЕЙ
            </h2>
            <div class="news-flexbox">
                <div class="vk-wrap">
                    <div id="vk_groups"></div>
                </div>
                <div class="ya-review">
                    <iframe class="ya-review__iframe" src="https://yandex.ru/maps-reviews-widget/170191165486?comments"></iframe>
                    <a href="https://yandex.ru/maps/org/talant/170191165486/" class="ya-review__a" target="_blank">
                        Талант на карте Самары — Яндекс Карты
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="contacts" id="contacts">
        <div class="contacts__container">
            <h2 class="contacts__title basic-font-h2">Контактная информация</h2>
            <div class="contacts-flexbox">
                <div class="contacts-flexbox__item">
                    <div class="contacts-inner-flexbox">
                        <div class="contacts-inner-flexbox__img-wrap">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="contacts-inner-flexbox__img" x="0px" y="0px" viewBox="0 0 287.32 287.32" style="enable-background: new 0 0 287.32 287.32" xml:space="preserve">
                                <path d="M267.749,191.076c-14.595-11.729-27.983-17.431-40.93-17.431c-18.729,0-32.214,11.914-44.423,24.119 c-1.404,1.405-3.104,2.06-5.349,2.06c-10.288,0.001-28.387-12.883-53.794-38.293c-29.89-29.892-41.191-48.904-33.592-56.506 c20.6-20.593,27.031-41.237-4.509-80.462C73.861,10.51,62.814,3.68,51.38,3.68c-15.42,0-27.142,12.326-37.484,23.202 c-1.788,1.88-3.477,3.656-5.133,5.312c-11.689,11.688-11.683,37.182,0.017,68.2c12.837,34.033,38.183,71.055,71.37,104.247 c25.665,25.663,53.59,46.403,80.758,60.328c23.719,12.158,46.726,18.672,64.783,18.672c0.002,0,0.004,0,0.007,0 c11.3,0,20.479-2.465,26.541-7.478c12.314-10.181,35.234-29.039,35.081-51.439C287.236,212.71,280.653,201.451,267.749,191.076z" />
                            </svg>
                        </div>
                        <div class="contacts-inner-flexbox__main-block">
                            <div class="contacts-inner-flexbox__title">ТЕЛЕФОН</div>
                            <?php if ($contact = get_field('general_tel', 'options')) { ?>
                                <a href="tel:<?php echo preg_replace('/[^0-9\+]/', '', $contact); ?>" class="contacts-inner-flexbox__phone" target="_blank"><?php echo $contact; ?></a>
                            <?php } ?>
                            <?php if ($contact = get_field('addictive_tel', 'options')) { ?>
                                <a href="tel:<?php echo preg_replace('/[^0-9\+]/', '', $contact); ?>" class="contacts-inner-flexbox__phone" target="_blank"><?php echo $contact; ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="contacts-flexbox__item">
                    <div class="contacts-inner-flexbox">
                        <div class="contacts-inner-flexbox__img-wrap">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="contacts-inner-flexbox__img" x="0px" y="0px" viewBox="0 0 438.536 438.536" style="enable-background: new 0 0 438.536 438.536" xml:space="preserve">
                                <g>
                                    <path d="M322.621,42.825C294.073,14.272,259.619,0,219.268,0c-40.353,0-74.803,14.275-103.353,42.825 c-28.549,28.549-42.825,63-42.825,103.353c0,20.749,3.14,37.782,9.419,51.106l104.21,220.986 c2.856,6.276,7.283,11.225,13.278,14.838c5.996,3.617,12.419,5.428,19.273,5.428c6.852,0,13.278-1.811,19.273-5.428 c5.996-3.613,10.513-8.562,13.559-14.838l103.918-220.986c6.282-13.324,9.424-30.358,9.424-51.106 C365.449,105.825,351.176,71.378,322.621,42.825z M270.942,197.855c-14.273,14.272-31.497,21.411-51.674,21.411 s-37.401-7.139-51.678-21.411c-14.275-14.277-21.414-31.501-21.414-51.678c0-20.175,7.139-37.402,21.414-51.675 c14.277-14.275,31.504-21.414,51.678-21.414c20.177,0,37.401,7.139,51.674,21.414c14.274,14.272,21.413,31.5,21.413,51.675 C292.355,166.352,285.217,183.575,270.942,197.855z" />
                                </g>
                            </svg>
                        </div>
                        <div class="contacts-inner-flexbox__main-block">
                            <div class="contacts-inner-flexbox__title">АДРЕС</div>
                            <?php if ($contact = get_field('adress_st', 'options')) { ?>
                                <div class="contacts-inner-flexbox__adress">
                                    <?php echo $contact; ?>
                                </div>
                            <?php } ?>
                            <?php if ($contact = get_field('adress_nd', 'options')) { ?>
                                <div class="contacts-inner-flexbox__adress">
                                    <?php echo $contact; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="contacts-flexbox__item">
                    <div class="contacts-inner-flexbox">
                        <div class="contacts-inner-flexbox__img-wrap">
                            <svg viewBox="0 0 481.4 406.9" class="contacts-inner-flexbox__img" xmlns="http://www.w3.org/2000/svg">
                                <g id="Layer_20">
                                    <path d="m240.7 306.2 43.1-38.1 191.6-169h-469.5l144.5 127.5z" />
                                    <path d="m146.2 238.9-146.2-129v289.7l164.2-144.9z" />
                                    <path d="m289.4 279.3-44.7 39.5c-2.3 2-5.7 2-7.9 0l-63.5-56-163.4 144.1h461.6l-163.4-144.2z" />
                                    <path d="m317.2 254.7 164.2 144.9v-289.7z" />
                                    <path d="m281.6 0h12v46.7h-12z" />
                                    <path d="m335 18.9h46.7v12h-46.7z" transform="matrix(.363 -.932 .932 .363 205.19 349.815)" />
                                    <path d="m404.7 21.6h46.7v12h-46.7z" transform="matrix(.577 -.817 .817 .577 158.68 361.412)" />
                                </g>
                            </svg>
                        </div>
                        <div class="contacts-inner-flexbox__main-block">
                            <div class="contacts-inner-flexbox__title">EMAIL</div>
                            <?php if ($contact = get_field('email', 'options')) { ?>
                                <a href="mailto:<?php echo $contact; ?>" class="contacts-inner-flexbox__email">
                                    <?php echo $contact; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?= do_shortcode('[contact-form-7 id="7597d79" title="Заявочная форма"]'); ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>