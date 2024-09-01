<?php
/*
Template Name: О нас
*/
?>
<?php get_header(); ?>
<main class="main">
  <section class="basic-container">
    <div class="about__container">
      <div class="about">
        <div class="about-flex">
          <div class="about-flex__text">
            <?php if (get_the_title()) { ?>
              <h2 class="basic-title basic-title__color"><?php the_title(); ?></h2>
            <?php } ?>
            <!-- prettier-ignore -->
            <?php if ($value = get_field('about_text1')) { ?>
              <p class="about-flex__text-st-part"><?= $value; ?></p>
            <?php } ?>
            <?php if ($value = get_field('about_text2')) { ?>
              <div class="about-flex__text-nd-part-wrap">
                <p class="about-flex__text-nd-part"><?= $value; ?></p>
              </div>
          </div>
        <?php } ?>
        <?php if ($value = get_field('about_img')) { ?>
          <div class="about-flex__text-img-wrap">
            <img class="about-flex__text-img" src=<?= $value; ?> alt="" />
          </div>
        <?php } ?>
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
              <?php if ($value = get_field('tech_adv_title', 15)) { ?>
                <h2 class="basic-title basic-title__color"><?= $value; ?></h2>
              <?php } ?>
              <?php if ($value = get_field('tech_adv_subtitle', 15)) { ?>
                <h3 class="basic-subtitle basic-subtitle__color"><?= $value; ?></h3>
              <?php } ?>
              <?php if ($value = get_field('tech_adv_img', 15)) { ?>
                <div class="advantages-tech-st-item__wrap-img">
                  <img class="advantages-tech-st-item__img" src=<?= $value; ?> alt="" />
                </div>
              <?php } ?>
            </div>
            <?php
            if (have_rows('tech_repeater', 15)) {
            ?>
              <div class="advantages-tech-nd-item">
                <?php if ($value = get_field('tech_adv_title2', 15)) { ?>
                  <h4 class="advantages-tech-nd-item__title"><?= $value; ?></h4>
                <?php } ?>
                <ul class="advantages-tech-nd-item-list">
                  <?php while (have_rows('tech_repeater', 15)) {
                    the_row();
                  ?>
                    <li class="advantages-tech-nd-item-list__point">
                      <div class="advantages-tech-nd-item-list__point-wrap-img">
                        <img class="advantages-tech-nd-item-list__point-img" src=<?= get_template_directory_uri() . "/img/advantages/point.png" ?> alt="" />
                      </div>
                      <p class="advantages-tech-nd-item-list__point-text"><?php echo get_sub_field('item', 15); ?></p>
                    </li>
                  <?php } ?>
                </ul>
              </div>
            <?php } ?>
          </div>
          <div class="advantages-work">
            <?php if ($value = get_field('adv_title', 15)) { ?>
              <h2 class="basic-title basic-title__color"><?= $value; ?></h2>
            <?php } ?>
            <?php if ($value = get_field('adv_subtitle', 15)) { ?>
              <h3 class="basic-subtitle basic-subtitle__color"><?= $value; ?></h3>
            <?php } ?>
            <?php
            if (have_rows('adv_repeater', 15)) {
            ?>
              <div class="advantages-work-grid">
                <?php while (have_rows('adv_repeater', 15)) {
                  the_row();
                ?>
                  <div class="advantages-work-grid__item">
                    <div class="advantages-work-grid__item-inner-wrap">
                      <img class="advantages-work-grid__item-img" src=<?php echo get_sub_field('img', 15); ?> alt="" />
                    </div>
                    <div class="advantages-work-grid__item-advantage"><?php echo get_sub_field('text', 15); ?></div>
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
  if (have_rows('revoews_repeater', 15)) {
  ?>
    <section class="reviews__container">
      <div class="reviews">
        <?php if ($value = get_field('reviews_title', 15)) { ?>
          <h2 class="basic-title"><?= $value; ?></h2>
        <?php } ?>
        <?php if ($value = get_field('_reviews_subtitle', 15)) { ?>
          <h3 class="basic-subtitle"><?= $value; ?></h3>
        <?php } ?>
        <div class="reviews__inner-container">
          <div class="reviews-slider">
            <div class="swiper-wrapper">
              <?php while (have_rows('revoews_repeater', 15)) {
                the_row();
              ?>
                <div class="reviews-slider-slide swiper-slide">
                  <a class="reviews-slider-slide__st-img" href=<?= get_sub_field('img_st', 15); ?> data-fancybox="group-review<?php echo get_row_index(); ?>">
                    <img src=<?= get_sub_field('img_st', 15); ?> alt="" />
                  </a>
                  <a class="reviews-slider-slide__nd-img" href=<?= get_sub_field('img_nd', 15); ?> data-fancybox="group-review<?php echo get_row_index(); ?>">
                    <img src=<?= get_sub_field('img_nd', 15); ?> alt="" />
                  </a>
                  <div class="reviews-slider-text-block">
                    <p class="reviews-slider-text-block__text"><?= get_sub_field('item', 15); ?></p>
                    <div class="reviews-slider-text-block__signatire"><?= get_sub_field('signature', 15); ?></div>
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
        <?php if ($value = get_field('uni_form_img', 15)) { ?>
          <div class="request__bg-wrap manager-img">
            <img class="request__bg" src=<?= $value; ?> alt="" />
          </div>
        <?php } ?>
        <div class="request__content">
          <?php if ($value = get_field('uni_form_title', 15)) { ?>
            <h2 class="basic-title basic-title__consulting"><?= $value; ?></h2>
          <?php } ?>
          <?php if ($value = get_field('uni_form_subtitle', 15)) { ?>
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