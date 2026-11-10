<?php
/*
Template Name: Политика конфиденциальности
*/
?>
<?php get_header(); ?>
<main class="main under-header">
  <section class="breadcrumbs">
    <div class="breadcrumbs__container">
      <div class="breadcrumbs-flexbox">
        <h1 class="breadcrumbs-flexbox__title basic-font-h2">
          <?php the_title(); ?>
        </h1>
        <div class="breadcrumbs-flexbox__path">
          <a href="<?= get_home_url(); ?>" class="breadcrumbs-flexbox__path_link">ГЛАВНАЯ</a> /
          <?php the_title(); ?>
        </div>
      </div>
    </div>
  </section>
  <section class="policy">
    <div class="policy__container">
      <div class="policy-content">
        <?php the_content(); ?>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>