<?php
/*
Template Name: Наша миссия
*/
?>
<?php get_header(); ?>
<main class="main under-header">
  <section class="breadcrumbs">
    <div class="breadcrumbs__container">
      <div class="breadcrumbs-flexbox">
        <h1 class="breadcrumbs-flexbox__title basic-font-h2"><?php the_title(); ?></h1>
        <div class="breadcrumbs-flexbox__path">
          <a href="<?= get_home_url(); ?>" class="breadcrumbs-flexbox__path_link">ГЛАВНАЯ</a> /
          <?php the_title(); ?>
        </div>
      </div>
    </div>
  </section>
  <section class="more-about">
    <div class="more-about__container">
      <div class="more-about-content">
        <?php the_content(); ?>
      </div>
      <?php $images = get_field('mission_sertificates');
      if ($images) { ?>
        <div class="more-about-sertificates">
          <?php foreach ($images as $image) { ?>
            <div class="more-about-sertificates__img-wrap">
              <img src=<?= $image['url']; ?> class="more-about-sertificates__img" alt="" />
            </div>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>