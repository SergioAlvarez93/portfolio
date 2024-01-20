<div class="row gy-4">
	<?php 
		$args = array(
			'post_type' => 'courses',
			'posts_per_page' => is_front_page() ? 3 : 0,
			'order'   => 'ASC',
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post(); ?>
					<div class="col-lg-4 col-md-6 courses__bg">
						<a href="<?= get_permalink(); ?>" class="d-block courses__item">
							<div class="courses__item-txt-box">
								<div class="courses__item-title mb-3"><?= the_title(); ?></div>
								<span >Подробнее</span>
								<svg width="30" height="10" viewBox="0 0 30 10" fill="none">
									<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#course-arrow" x="0" y="0"></use>
								</svg>
							</div>
							<?php if ($value = get_field('course_img_preview')) { ?>
								<img src="<?= kama_thumb_src('w=420&h=450&src=' . $value) ?>" alt="">		
							<?php } ?>
						</a>
					</div>
			<?php }
		}
		wp_reset_query(); ?>
</div>