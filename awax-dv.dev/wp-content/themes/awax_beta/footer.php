<footer>
	<section class="contacts" id="contacts">
		<div class="container">
			<div class="contacts-content">
				<h2 class="title"><?php if ( get_field('contacts_title') ) { ?>
				<?php the_field('contacts_title'); ?>
				<?php } ?></h2>
				<h4 class="subtitle subtitle-contacts"><?php if ( get_field('contacts_subtitle') ) { ?>
				<?php the_field('contacts_subtitle'); ?>
				<?php } ?></h4>
			</div>
			<div class="form">
				<?php echo do_shortcode('[contact-form-7 id="f86c98f" title="Contact Form 1"]'); ?>
			</div>
		</div>
	</section>
	<section class="social">
		<div class="container">
			<div class="row text-center text-sm-center text-md-start">
				<div class="col-lg-3 col-md-3 col-sm-12 col-12 order-2 order-sm-2 order-md-1 d-flex flex-column">
					<div class="icons d-flex justify-content-md-start justify-content-sm-center justify-content-center mb-3 mb-sm-3 mb-md-0">
						<?php
						$query = new WP_Query( 'cat=5' );
						if( $query->have_posts() ){
							while( $query->have_posts() ){
								$query->the_post();
						?>
						<a href="<?php if ( get_field('social_url') ) { ?>
							<?php the_field('social_url'); ?>
						<?php } ?>" target="_blank"><?php the_post_thumbnail(); ?></a>
						<?php
						}
						}?>
						<?php wp_reset_query(); ?>
					</div>
					<h5 class="awax"><?php if ( get_field('social_copyright') ) { ?>
					<?php the_field('social_copyright'); ?>
					<?php } ?></h5>
				</div>
				<p class="col-lg-4 col-md-4 col-sm-12 col-12 order-1 order-sm-1 order-md-2 mb-4 mb-sm-4 mb-md-0 social-txt">
					<?php if ( get_field('social_text') ) { ?>
					<?php the_field('social_text'); ?>
					<?php } ?>
				</p>
			</div>
		</div>
		<a class="arrow" href="#"><img src="<?php bloginfo('template_url'); ?>/assets/img/scrollup.png" alt=""></a>
	</section>
</footer>
<?php wp_footer(); ?>
</body>
</html>