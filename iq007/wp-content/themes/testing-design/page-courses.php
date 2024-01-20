<?php  
/*Template Name: Страница курсов*/
?>
<?php get_header(); ?>
	<section class="courses">
		<div class="container">
			<h1 class="courses__title mb-5"><?php h1_title(); ?></h1>
			<?php get_template_part( 'inc/template', 'courses' ); ?>
		</div>
	</section>
<?php get_footer(); ?>