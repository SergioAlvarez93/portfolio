<?php
/*
Template Name: Главная
*/
?>
<?php get_header(); ?>
<main class="main">
    <section class="basic-container">
        <div class="summary__container">
            <div class="summary">
                <?php if ($contact = get_field('action_title')) { ?>
                    <h1 class="summary__title"><?php echo $contact; ?></h1>
                <?php } ?>
                <?php if ($contact = get_field('action_info')) { ?>
                    <div class="summary__flexbox">
                        <div class="summary-call">
                            <div class="summary-call__basic-info-flexbox">
                                <!-- prettier-ignore -->
                                <div class="summary-call-text"><?php echo $contact; ?></div>
                                <?php if ($contact = get_field('tel', 'options')) { ?>
                                    <a href="tel:<?php echo preg_replace('/[^0-9\+]/', '', $contact); ?>" class="call-btn">Позвонить</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="summary-info__flexbox">
                    <?php
                    if (have_rows('action_advantages')) {
                    ?>
                        <div class="summary-info">
                            <?php while (have_rows('action_advantages')) {
                                the_row();
                            ?>
                                <div class="summary-info__item">
                                    <div class="summary-info__item-number"><?php echo get_sub_field('title'); ?></div>
                                    <div class="summary-info__item-content"><?php echo get_sub_field('content'); ?></div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php
                    if (have_rows('action_item')) {
                    ?>
                        <div class="summary-info-slider-container">
                            <div class="swiper-wrapper">
                                <?php while (have_rows('action_item')) {
                                    the_row();
                                ?>
                                    <div class="summary-info-slider-slide swiper-slide">
                                        <a href="<?php echo get_sub_field('img') ?>" data-fancybox="group-1">
                                            <img src="<?php echo get_sub_field('img') ?>" alt="<?php echo get_sub_field('title') ?>" data-size="<?php echo get_sub_field('content') ?>" />
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="slider-nav">
                    <div class="slider-nav__flex">
                        <div class="summary-info-slider-prev"></div>
                        <div class="bar">
                            <div class="bar__values">
                                <div class="bar__values-current summary-slider-curr"></div>
                                <div class="bar__values-sum summary-slider-sum"></div>
                            </div>
                            <div class="summary-info-slider-progressbar"></div>
                            <div class="curr-house-name"></div>
                            <div class="curr-house-size"></div>
                        </div>
                        <div class="summary-info-slider-next"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="construction__bg" id="catNav">
        <div class="basic-container">
            <div class="construction__container">
                <div class="construction">
                    <?php if ($value = get_field('types_title')) { ?>
                        <h2 class="basic-title"><?= $value; ?></h2>
                    <?php } ?>
                    <?php
                    if (have_rows('types_item')) {
                    ?>
                        <div class="construction__grid">
                            <div class="construction-info-block">
                                <ul class="construction-list">
                                    <?php while (have_rows('types_item')) {
                                        the_row();
                                    ?>
                                        <li class="construction-list__item" data-text="<?php echo get_sub_field('contents') ?>" data-imgVert="<?php echo get_sub_field('vert_img') ?>" data-imgHor="<?php echo get_sub_field('hor_img') ?>">
                                            <?php echo get_sub_field('title') ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <p class="construction-text"></p>
                            </div>
                            <div class="constructions-photo">
                                <div class="constructions-photo__first-wrap">
                                    <a href="" data-fancybox="group-2"><img src="" alt="" class="constructions-photo__first" /></a>
                                </div>
                                <div class="constructions-photo__second-wrap">
                                    <a href="" data-fancybox="group-2"><img src="" alt="" class="constructions-photo__second" /></a>
                                </div>
                            </div>
                            <a href="#more" class="call-btn construction-btn scroll">Узнать подбробнее</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <section class="engineering">
        <div class="engineering__container">
            <div class="engineering__grid">
                <div class="engineering-header">
                    <?php if ($value = get_field('project_title')) { ?>
                        <h2 class="basic-title basic-title__color"><?= $value; ?></h2>
                    <?php } ?>
                    <?php if ($value = get_field('project_subtitle')) { ?>
                        <h3 class="basic-subtitle basic-subtitle__color">
                            свой проект либо работаем по проекту клиента
                        </h3>
                    <?php } ?>
                </div>
                <div class="engineering-group">
                    <?php if ($value = get_field('project_content')) { ?>
                        <?= $value; ?>
                    <?php } ?>
                </div>
                <div class="engineering-img__wrap">
                    <?php if ($value = get_field('project_img')) { ?>
                        <img src="<?= $value; ?>" class="engineering-img" alt="" />
                    <?php } ?>

                </div>
            </div>
        </div>
    </section>
    <section class="basic-container">
        <div class="request__container" id="more">
            <div class="request">
                <?php if ($value = get_field('worker-form_img')) { ?>
                    <div class="request__bg-wrap">
                        <img class="request__bg" src="<?= $value; ?>" alt="" />
                    </div>
                <?php } ?>
                <div class="request__content">
                    <?php if ($value = get_field('worker-form_title')) { ?>
                        <h2 class="basic-title"><?= $value; ?></h2>
                    <?php } ?>
                    <?php if ($value = get_field('worker-form_subtitle')) { ?>
                        <h3 class="basic-subtitle"><?= $value; ?></h3>
                    <?php } ?>
                    <?php echo do_shortcode('[contact-form-7 id="65135c8" title="Дополнительная форма"]'); ?>
                </div>
            </div>
        </div>
    </section>
    <section class="objects__bg">
        <div class="basic-container">
            <div class="objects">
                <div class="objects__container">
                    <?php if ($value = get_field('object_title')) { ?>
                        <h2 class="basic-title"><?= $value; ?></h2>
                    <?php } ?>
                    <?php if ($value = get_field('object_subtitle')) { ?>
                        <h3 class="basic-subtitle"><?= $value; ?></h3>
                    <?php } ?>
                </div>
                <?php
                if (have_rows('object_repeater')) {
                ?>
                    <div class="objects-slider-container">
                        <div class="swiper-wrapper">
                            <?php while (have_rows('object_repeater')) {
                                the_row();
                            ?>
                                <div class="objects-slider-slide swiper-slide">
                                    <?php $images = get_sub_field('gallery_item');
                                    if ($images) { ?>
                                        <?php foreach ($images as $image) { ?>
                                            <a class="objects-slider-slide-inner" href=<?= $image['url']; ?> data-fancybox="group-3">
                                                <img src=<?= $image['url']; ?> alt="" />
                                            </a>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="objects__container">
                    <div class="basic-slider-nav">
                        <div class="basic-slider-nav__flex">
                            <div class="basic-slider-prev objects-slider-slider-prev"></div>
                            <div class="bar-basic">
                                <div class="bar-basic__values">
                                    <div class="bar-basic__values-current objects-slider-curr"></div>
                                    <div class="bar-basic__values-sum objects-slider-sum"></div>
                                </div>
                                <div class="basic-slider-progressbar objects-slider-progressbar"></div>
                            </div>
                            <div class="basic-slider-next objects-slider-slider-next"></div>
                        </div>
                        <a href=<?= get_home_url() . "/objects" ?> class="call-btn objects__btn">
                            Смотреть все
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="advantages__bg">
        <div class="basic-container">
            <div class="advantages__container">
                <div class="advantages">
                    <div class="advantages-tech">
                        <div class="advantages-tech-st-item">
                            <?php if ($value = get_field('tech_adv_title')) { ?>
                                <h2 class="basic-title basic-title__color"><?= $value; ?></h2>
                            <?php } ?>
                            <?php if ($value = get_field('tech_adv_subtitle')) { ?>
                                <h3 class="basic-subtitle basic-subtitle__color"><?= $value; ?></h3>
                            <?php } ?>
                            <?php if ($value = get_field('tech_adv_img')) { ?>
                                <div class="advantages-tech-st-item__wrap-img">
                                    <img class="advantages-tech-st-item__img" src=<?= $value; ?> alt="" />
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                        if (have_rows('tech_repeater')) {
                        ?>
                            <div class="advantages-tech-nd-item">
                                <?php if ($value = get_field('tech_adv_title2')) { ?>
                                    <h4 class="advantages-tech-nd-item__title"><?= $value; ?></h4>
                                <?php } ?>
                                <ul class="advantages-tech-nd-item-list">
                                    <?php while (have_rows('tech_repeater')) {
                                        the_row();
                                    ?>
                                        <li class="advantages-tech-nd-item-list__point">
                                            <div class="advantages-tech-nd-item-list__point-wrap-img">
                                                <img class="advantages-tech-nd-item-list__point-img" src=<?= get_template_directory_uri() . "/img/advantages/point.png" ?> alt="" />
                                            </div>
                                            <p class="advantages-tech-nd-item-list__point-text"><?php echo get_sub_field('item'); ?></p>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="advantages-work">
                        <?php if ($value = get_field('adv_title')) { ?>
                            <h2 class="basic-title basic-title__color"><?= $value; ?></h2>
                        <?php } ?>
                        <?php if ($value = get_field('adv_subtitle')) { ?>
                            <h3 class="basic-subtitle basic-subtitle__color"><?= $value; ?></h3>
                        <?php } ?>
                        <?php
                        if (have_rows('adv_repeater')) {
                        ?>
                            <div class="advantages-work-grid">
                                <?php while (have_rows('adv_repeater')) {
                                    the_row();
                                ?>
                                    <div class="advantages-work-grid__item">
                                        <div class="advantages-work-grid__item-inner-wrap">
                                            <img class="advantages-work-grid__item-img" src=<?php echo get_sub_field('img'); ?> alt="" />
                                        </div>
                                        <div class="advantages-work-grid__item-advantage"><?php echo get_sub_field('text'); ?></div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    if (have_rows('revoews_repeater')) {
    ?>
        <section class="reviews__container">
            <div class="reviews">
                <?php if ($value = get_field('reviews_title')) { ?>
                    <h2 class="basic-title"><?= $value; ?></h2>
                <?php } ?>
                <?php if ($value = get_field('_reviews_subtitle')) { ?>
                    <h3 class="basic-subtitle"><?= $value; ?></h3>
                <?php } ?>
                <div class="reviews__inner-container">
                    <div class="reviews-slider">
                        <div class="swiper-wrapper">
                            <?php while (have_rows('revoews_repeater')) {
                                the_row();
                            ?>
                                <div class="reviews-slider-slide swiper-slide">
                                    <a class="reviews-slider-slide__st-img" href=<?= get_sub_field('img_st'); ?> data-fancybox="group-review<?php echo get_row_index(); ?>">
                                        <img src=<?= get_sub_field('img_st'); ?> alt="" />
                                    </a>
                                    <a class="reviews-slider-slide__nd-img" href=<?= get_sub_field('img_nd'); ?> data-fancybox="group-review<?php echo get_row_index(); ?>">
                                        <img src=<?= get_sub_field('img_nd'); ?> alt="" />
                                    </a>
                                    <div class="reviews-slider-text-block">
                                        <p class="reviews-slider-text-block__text"><?= get_sub_field('item'); ?></p>
                                        <div class="reviews-slider-text-block__signatire"><?= get_sub_field('signature'); ?></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="bar-basic reviews-bar">
                    <div class="bar-basic__values reviews-slider-values">
                        <div class="bar-basic__values-current reviews-slider-curr"></div>
                        <div class="bar-basic__values-sum reviews-slider-sum"></div>
                    </div>
                    <div class="basic-slider-progressbar reviews-slider-progressbar"></div>
                </div>
            </div>
        </section>
    <?php } ?>
   <!--  <section class="calc__bg">
        <div class="calc__container">
            <div class="calc">
                <h2 class="basic-title">Стоимость</h2>
                <h3 class="basic-subtitle">
                    вашего обьекта вы можете предварительно просчитать прямо здесь
                </h3>
                <div class="calc-body">
                    <div class="calc-body-parameters">
                        <div class="calc-body-parameters__label">Желаемый метраж</div>
                        <input class="calc-body-parameters__universal-parameter" type="text" placeholder="100-300" />
                        <div class="calc-body-parameters__selects-block">
                            <div class="calc-body-parameters__label">Этажность</div>
                            <div class="calc-body-parameters__universal-parameter-wrap">
                                <select class="calc-body-parameters__universal-parameter">
                                    <option value="1">1 этаж</option>
                                    <option value="2">2 этажа</option>
                                    <option value="3">3 этажа</option>
                                </select>
                            </div>
                            <div class="calc-body-parameters__label">Сроки</div>
                            <div class="calc-body-parameters__universal-parameter-wrap">
                                <select class="calc-body-parameters__universal-parameter">
                                    <option value="8">8 месяцев</option>
                                    <option value="10">10 месяцев</option>
                                    <option value="12">12 месяцев</option>
                                </select>
                            </div>
                            <div class="calc-body-parameters__label">Финансирование</div>
                            <div class="calc-body-parameters__universal-parameter-wrap">
                                <select class="calc-body-parameters__universal-parameter">
                                    <option value="50">50%</option>
                                    <option value="75">75%</option>
                                    <option value="100">100%</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="calc-body-result">
                        <div class="calc-body-result__title">
                            Предварительная стоимость
                        </div>
                        <div class="calc-body-result__money">5 000 000 р.</div>
                    </div>
                    <button class="call-btn calc-btn">Рассчитать</button>
                </div>
            </div>
        </div>
    </section> -->
    <section class="basic-container">
        <div class="request__container">
            <div class="request">
                <?php if ($value = get_field('uni_form_img')) { ?>
                    <div class="request__bg-wrap manager-img">
                        <img class="request__bg" src=<?= $value; ?> alt="" />
                    </div>
                <?php } ?>
                <div class="request__content">
                    <?php if ($value = get_field('uni_form_title')) { ?>
                        <h2 class="basic-title basic-title__consulting"><?= $value; ?></h2>
                    <?php } ?>
                    <?php if ($value = get_field('uni_form_subtitle')) { ?>
                        <h3 class="basic-subtitle"><?= $value; ?></h3>
                    <?php } ?>
                    <?php echo do_shortcode('[contact-form-7 id="803985b" title="Основная форма"]'); ?>
                </div>
            </div>
        </div>
    </section>
    <section class="modal-section">
        <div id="myModal" class="modal">
            <div class="modal__content">
                <div class="modal__close"></div>
                <div class="modal__title">Спасибо</div>
                <p class="modal__text">
                    В ближайшее время наш консультант свяжется с Вами, чтобы ответить
                    на все вопросы!
                </p>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>