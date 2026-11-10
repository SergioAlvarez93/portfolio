<?php
/*
Template Name: Объекты
*/
?>
<?php get_header(); ?>
<main class="main">
  <section class="objects-exp">
    <div class="objects-exp__bg"></div>
    <div class="objects-exp__container">
      <?php if ($value = get_field('projects_title')) { ?>
        <h2 class="basic-title basic-title__color"><?= $value; ?></h2>
      <?php } ?>
      <?php if ($value = get_field('projects_subtitle')) { ?>
        <h3 class="basic-subtitle basic-subtitle__color"><?= $value; ?></h3>
      <?php } ?>
      <?php
      $args = array(
        'post_type' => 'objects',
        'posts_per_page' => 0,
        'order' => 'ASC',
      );
      $query = new WP_Query($args);
      if ($query->have_posts()) { ?>
        <div class="objects-exp-grid">
          <?php while ($query->have_posts()) {
            $query->the_post(); ?>
            <a href=<?php echo get_permalink(); ?> class="objects-exp-item">
              <?php if (has_post_thumbnail()) {
                $thumbnail_id = get_post_thumbnail_id();
                $thumbnail_url = wp_get_attachment_url($thumbnail_id); ?>
                <div class="objects-exp-item__bg-wrap">
                  <img class="objects-exp-item__bg" src=<?php echo $thumbnail_url; ?> alt="" />
                </div>
              <?php } ?>
              <div class="objects-exp-item-inner-flexbox">
                <div class="objects-exp-item-inner-flexbox__content">
                  <?php if (get_the_title()) { ?>
                    <div class="objects-exp-item-inner-flexbox__content-st-title"><?php the_title(); ?></div>
                  <?php } ?>
                  <?php if ($value = get_field('material')) { ?>
                    <div class="objects-exp-item-inner-flexbox__content-nd-title"><?= $value; ?></div>
                  <?php } ?>
                </div>
                <div class="objects-exp-item-inner-flexbox__arrow"></div>
              </div>
            </a>
          <?php } ?>
        </div>
      <?php }
      wp_reset_query(); ?>
    </div>
  </section>
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