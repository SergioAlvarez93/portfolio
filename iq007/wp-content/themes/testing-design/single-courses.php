<?php get_header(); ?>
	<section class="current-course">
		<div class="container">
			<div class="current-course__wrapper">
				<h1 class="current-course__main-title"><?php h1_title(); ?></h1> 
				<div class="row justify-content-center gy-4">
					<div class="col-sm-10 programms-fb-bg">
						<div class="row justify-content-center justify-content-xl-between programms-fb-bg__item gy-4">
							<div class="col-auto d-flex">
								<div class="programms-fb-bg__item-img-wrap">
									<img class="programms-fb-bg__item-img pt-5" src="<?= kama_thumb_src('w=420&h=410&src=' . get_the_post_thumbnail_url()) ?>" alt="">
								</div>		
							</div>
							<div class="col">
								<div class="programms-fb-bg__item-box pe-0 pe-xl-5">
									<div class="programms-fb-bg__item-txt"><?php the_content(); ?></div>		
									<?= do_shortcode('[modal_btn]') ?>  
								</div>
							</div>
						</div>
					</div>
				</div>	
				<?php if (have_rows('course_content_part')) { ?>
				<?php while (have_rows('course_content_part')) { the_row(); ?>
					<?php get_template_part('inc/' . get_row_layout()); ?>
				<?php } ?>
				<?php } ?>
			</div>
		</div>
	</section>
<?php get_footer(); ?>
