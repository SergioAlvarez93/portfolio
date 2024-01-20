<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charser'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Awax</title>
	<?php wp_head(); ?>
</head>
<body>
	<header>
		<nav class="navbar navbar-expand-lg">
			<div class="container">
				<?php the_custom_logo(); ?>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Переключатель навигации" id="nav">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<?php wp_nav_menu( [
						'theme_location'  => 'menu',
						'container'       => null,
						'menu_class'      => 'navbar-nav my-3 my-lg-0',
						'menu_id'         => 'ul-menu',
						'add_li_class'    => 'nav-item navl',
						'add_a_class'     => '',
					] ); ?>
				</div>
			</div>
		</nav>
		<section class="design">
			<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
				<div class="carousel-indicators">
					<?php
					$query = new WP_Query( 'cat=4' );
					if( $query->have_posts() ){
						while( $query->have_posts() ){
							$query->the_post();
					?>
					<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $query->current_post; ?>" class="<?php echo $query->current_post >= 1 ? '' : 'active'; ?>" aria-current="true" aria-label="Slide <?php echo $query->current_post + 1; ?>">
					</button>
					<?php
					}
					}?>
					<?php wp_reset_query(); ?>
				</div>
				<div class="carousel-inner">
					<?php
					$query = new WP_Query( 'cat=4' );
					if( $query->have_posts() ){
						while( $query->have_posts() ){
							$query->the_post();
					?>
					<div class="carousel-item <?php echo $query->current_post >= 1 ? '' : 'active'; ?>">
						<?php the_post_thumbnail(
							array(1600, 751),
							array(
								'class' => 'd-block w-100'
							)
						); ?>
						<h1><?php if ( get_field('title_slider') ) { ?>
						<?php the_field('title_slider'); ?>
						<?php } ?>
						<span><?php if ( get_field('span_slider') ) { ?>
							<?php the_field('span_slider'); ?>
						<?php } ?></span></h1>
						<h3><?php if ( get_field('subtitle_slider') ) { ?>
						<?php the_field('subtitle_slider'); ?>
						<?php } ?></h3>
						<div><button class="btn">Get in touch</button></div>
					</div>
					<?php
					}
					
					}?>
					<?php wp_reset_query(); ?>
				</div>
				<a class="call" data-bs-toggle="modal" data-bs-target="#exampleModal"><img src="<?php bloginfo('template_url'); ?>/assets/img/telephone.png" alt=""></a>
			</div>
		</section>
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<div class="form-mod">
							<?php echo do_shortcode('[contact-form-7 id="2f27442" title="Contact Form 2 (Modal)"]'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>