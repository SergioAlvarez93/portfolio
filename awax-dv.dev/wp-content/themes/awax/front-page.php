<?php
/*Template Name: front-page*/
?>
<?php get_header(); ?>
<main>
	<section class="about" id="about">
		<h2 class="title"><?php if ( get_field('about_title') ) { ?>
		<?php the_field('about_title'); ?>
		<?php } ?></h2>
		<h4 class="subtitle"><?php if ( get_field('about_subtitle') ) { ?>
		<?php the_field('about_subtitle'); ?>
		<?php } ?></h4>
		<div class="container">
			<div class="row text-lg-start text-md-center text-sm-center text-center">
				<div class="col-lg-7 col-md-12 col-sm-12 col-12 order-lg-1 order-md-2 order-sm-2 order-2 ">
					<p class="about-txt"><?php if ( get_field('about_text') ) { ?>
						<?php the_field('about_text'); ?>
					<?php } ?></p>
					<button class="btn btn-p">learn MORE</button>
				</div>
				<div class="col-lg-5 col-md-12 col-sm-12 col-12 order-lg-2 order-md-1 order-sm-1 order-1 mb-md-4 mb-sm-4 mb-4 video"><img src="<?php if ( get_field('about_image') ) { ?>
					<?php the_field('about_image'); ?>
				<?php } ?>" alt=""></div>
			</div>
		</div>
	</section>
	<section class="price" id="price">
		<h2 class="title"><?php if ( get_field('price_title') ) { ?>
		<?php the_field('price_title'); ?>
		<?php } ?></h2>
		<h4 class="subtitle subtitle-price"><?php if ( get_field('price_subtitle') ) { ?>
		<?php the_field('price_subtitle'); ?>
		<?php } ?></h4>
		<div class="container container-price">
			<div class="row row-price">
				<?php
				$query = new WP_Query( 'cat=2' );
				if( $query->have_posts() ){
					while( $query->have_posts() ){
						$query->the_post();
				?>
				<div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-md-4 mb-sm-4 mb-4">
					<div class="d-flex flex-column text-center card">
						<h3><?php the_title(); ?></h4>
						<h2><?php if ( get_field('card_price') ) { ?>
						<?php the_field('card_price'); ?>
						<?php } ?></h2>
						<h4><?php if ( get_field('card_period') ) { ?>
						<?php the_field('card_period'); ?>
						<?php } ?></h4>
						<p><?php if ( get_field('card_advantages') ) { ?>
							<?php the_field('card_advantages'); ?>
						<?php } ?></p>
						<div><button class="btn btn-card">CHOOSE</button></div>
					</div>
				</div>
				<?php
				}
				}?>
				<?php wp_reset_query(); ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>