<?php
/*
Template name: Отдельный объект
Template post type: objects
*/
?>

<?php get_header(); ?>
<main class="main">
  <section class="basic-container">
    <div class="summary__container">
      <div class="summary">
        <?php if (get_the_title()) { ?>
          <h1 class="summary__title summary-single-obj-title"><?php the_title(); ?></h1>
        <?php } ?>
        <div class="summary__flexbox">
          <div class="summary-call">
            <?php if ($value = get_field('up_text')) { ?>
              <p class="summary-single-obj"><?= $value; ?></p>
            <?php } ?>
          </div>
        </div>
        <?php
        if (have_rows('inf_repeater')) {
        ?>
          <div class="summary-info__flexbox">
            <div class="summary-info">
              <?php while (have_rows('inf_repeater')) {
                the_row();
              ?>
                <div class="summary-info__item">
                  <?php if ($value = get_sub_field('title')) { ?>
                    <div class="summary-info__item-number"><?= $value; ?></div>
                  <?php } ?>
                  <?php if ($value = get_sub_field('subtitle')) { ?>
                    <div class="summary-info__item-content"><?= $value; ?></div>
                  <?php } ?>
                </div>
              <?php } ?>
            </div>
            <?php if ($value = get_field('general_img')) { ?>
              <a href=<?= $value; ?> class="summary-single-obj-img__wrap" data-fancybox="group-single-obj-1">
                <img class="summary-single-obj-img" src=<?= $value; ?> alt="<?php the_title(); ?>" />
              </a>
            <?php } ?>
          </div>
        <?php } ?>
        <div class="summary-single-obj-bottom__wrap">
          <div class="summary-single-obj-bottom">
            <?php if ($value = get_field('down_text')) { ?>
              <p class="summary-single-obj"><?= $value; ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php $images = get_field('gallery_items');
  if ($images) { ?>
    <section class="obj-gallery__bg">
      <div class="basic-container">
        <div class="obj-gallery">
          <?php if ($value = get_field('gal_title')) { ?>
            <div class="obj-gallery__container">
              <h2 class="basic-title"><?= $value; ?></h2>
            </div>
          <?php } ?>
          <div class="obj-gallery-slider">
            <div class="swiper-wrapper">
              <?php foreach ($images as $image) { ?>
                <div class="obj-gallery-slider-slide swiper-slide">
                  <a class="obj-gallery-slider-slide-inner" href=<?= $image['url']; ?> data-fancybox="group-gallery-1">
                    <img src=<?= $image['url']; ?> alt="" />
                  </a>
                  <?php if ($image['title'] || $image['caption']) { ?>
                    <div class="obj-gallery-slider-slide-text">
                      <div class="obj-gallery-slider-slide-text__general"><?= $image['title']; ?></div>
                      <div class="obj-gallery-slider-slide-text__sub"><?= $image['caption']; ?></div>
                    </div>
                  <?php } ?>
                </div>
              <?php } ?>
            </div>
          </div>
          <div class="obj-gallery__container">
            <div class="basic-slider-nav">
              <div class="basic-slider-nav__flex">
                <div class="basic-slider-prev obj-gallery-slider-prev"></div>
                <div class="bar-basic">
                  <div class="bar-basic__values">
                    <div class="bar-basic__values-current obj-gallery-slider-curr"></div>
                    <div class="bar-basic__values-sum obj-gallery-slider-sum"></div>
                  </div>
                  <div class="basic-slider-progressbar obj-gallery-slider-progressbar"></div>
                </div>
                <div class="basic-slider-next obj-gallery-slider-next"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php } ?>
  <section class="basic-container">
    <div class="request__container">
      <div class="request request-pad">
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