<?php
/*
Template Name: Записаться на экскурсию
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
  <section class="contacts">
    <div class="contacts__container">
      <?= do_shortcode('[contact-form-7 id="e7ea705" title="Заявочная форма 2"]'); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>