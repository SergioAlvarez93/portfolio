<?php
/*
Template name: Программа
Template post type: programm
*/
?>
<?php get_header(); ?>
<main class="main under-header">
  <section class="breadcrumbs breadcrumbs-programm">
    <?php
    if (has_post_thumbnail()) {
      $thumbnail_id = get_post_thumbnail_id();
      $thumbnail_url = wp_get_attachment_url($thumbnail_id); ?>
      <div class="parallax-container">
        <img src="<?= $thumbnail_url; ?>" class="parallax-img" alt="" />
      </div>
    <?php }
    ?>
    <div class="breadcrumbs__container">
      <div class="breadcrumbs-flexbox">
        <h1 class="breadcrumbs-flexbox__title basic-font-h2 breadcrumbs-flexbox__title_clr">
          <?php the_title(); ?>
        </h1>
        <div class="breadcrumbs-flexbox__path breadcrumbs-flexbox__path_clr">
          <a href="<?= get_home_url(); ?>" class="breadcrumbs-flexbox__path_link breadcrumbs-flexbox__path_inner-clr">ГЛАВНАЯ</a>
          / <?php the_title(); ?>
        </div>
      </div>
    </div>
  </section>
  <section class="curr-programm">
    <div class="curr-programm__container">
      <div class="curr-programm-grid">
        <?php if ($value = get_field('programm_quote')) { ?>
          <p class="curr-programm-grid__quote">
            <?= $value; ?>
            <?php if ($value = get_field('programm_author')) { ?>
              <span class="curr-programm-grid__quote_span"><?= $value; ?></span>
            <?php } ?>
          </p>
        <?php } ?>
        <?php if ($value = get_field('programm_full_text')) { ?>
          <!-- prettier-ignore -->
          <p class="basic-font-txt curr-programm-grid__text"><?= $value; ?></p>
        <?php } ?>
      </div>
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
</main>
<?php get_footer(); ?>