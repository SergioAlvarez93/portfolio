<?php get_header(); ?>
		<section class="about" id="about">
			<div class="container">
				<div class="row gy-4 mb-5 mb-xxl-0">
					<div class="col-xl-5">
						<?php if ($value = get_field('main_title')) { ?>
							<h1 class="about__main-title mt-4"><?php h1_title(); ?></h1> 		
						<?php } ?>	 
						<?php if ($value = get_field('school_inf')) { ?>
							 <div class="about__main-txt mt-5"><?= $value; ?></div> 		
						<?php } ?>	   
						<?php 
							if (have_rows('license')) {
						?>
						<?php while ( have_rows('license') ) { the_row(); ?>
							<?php if ($value = get_sub_field('license_file')) { ?>
								<a href="<?= $value;  ?>" class="d-flex mt-5 about__license">
									<?php if ($valueImg = get_sub_field('license_img')) { ?>
										<img class="me-3" src="<?= $valueImg; ?>" alt=""> 		
									<?php } ?>	  
									<?php if ($valueTxt = get_sub_field('license_txt')) { ?>
										<span class="about__license-span align-self-center"><?= $valueTxt; ?></span> 	
									<?php } ?>	  
								</a> 		
							<?php } ?>	  
						<?php } ?>
						<?php } ?>
					</div>
					<div class="col-xl-7 text-center">
						<div class="about__photo-wrap">
							<?php if ($value = get_field('about_photo')) { ?>
								<img class="about__photo" src="<?= kama_thumb_src('w=749&h=484&src=' . $value) ?>" alt=""> 		
							<?php } ?>	  
						</div>
					</div>
				</div>
				<div class="row justify-content-center justify-content-xl-between align-items-end gy-5">
					<div class="col-auto order-2 order-xxl-1">
						<?= do_shortcode('[modal_btn]') ?>
					</div>
					<div class="col-xxl-7 order-1 order-xxl-2">
						<div class="about-cards-fb">
							<?php 
								if (have_rows('about_cards')) {
							?>
							<?php while (have_rows('about_cards')) {
								the_row();
							?>
								<div class="about-cards-fb__item">
									<?php if ($value = get_sub_field('about_cards_title')) { ?>
										<div class="about-cards-fb__item-title"><?= $value; ?></div> 		
									<?php } ?>	  
									<?php if ($value = get_sub_field('about_cards_subtitle')) { ?>
										<div class="about-cards-fb__item-substitle"><?= $value; ?></div> 		
									<?php } ?>	  
									
								</div>
							<?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="courses">
			<div class="container">
				<div class="courses__title mb-5"><?= get_the_title(79); ?></div>
				<?php get_template_part( 'inc/template', 'courses' ); ?>
				<div class="courses__btn-box"><a href="<?= get_home_url(); ?>/courses" class="btn btn-warning">Показать все</a></div>
			</div>
		</section>
		<?php if (have_rows('advantages')) { ?>
			<section class="advantages">
				<div class="container">
					<?php if ($value = get_field('advantages_main_title')) { ?>
						<div class="advantages__title mb-5"><?= $value; ?></div>
					<?php } ?>	
					<div class="row gy-5">
						<?php while (have_rows('advantages')) {
							the_row();
						?>
							<div class="col-lg-4 col-md-6 advantages__box">
								<?php if ($value = get_sub_field('advantages_img')) { ?>
									<img src="<?= $value; ?>" class="'advantages__item-img mb-5" alt="">
								<?php } ?>
								<?php if ($value = get_sub_field('advantages_title')) { ?>
									<div class="advantages__item-title mb-3"><?= $value; ?></div>
								<?php } ?>
								<?php if ($value = get_sub_field('advantages_txt')) { ?>
									<div class="advantages__item-txt"><?= $value; ?></div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>  
				</div>
			</section>
		<?php } ?>
		<?php if (have_rows('programm')) { ?>
			<section class="programms">
				<div class="container">
					<?php if ($value = get_field('programm_main_title')) { ?>
						<div class="programms__main-title mb-5"><?= $value; ?></div>		
					<?php } ?>
					<div class="row justify-content-center gy-4">
						<?php while (have_rows('programm')) {
							the_row();
						?>
							<div class="col-10 programms-fb-bg">
								<div class="row programms-fb-bg__item">
									<div class="col-12 col-lg-auto d-flex order-1 order-lg-0">
										<?php if ($value = get_sub_field('programm_img')) { ?>
											<div class="programms-fb-bg__item-img-wrap">
												<img src="<?= kama_thumb_src('w=420&h=315&src=' . $value) ?>" class="programms-fb-bg__item-img pt-5" alt="">
											</div>		
										<?php } ?>	  
									</div>
									<div class="col">
										<div class="programms-fb-bg__item-box py-lg-5 pt-5 pe-xl-5">
											<?php if ($value = get_sub_field('programm_title')) { ?>
												<div class="programms-fb-bg__item-title mb-4"><?= $value; ?></div>		
											<?php } ?>	
											<?php if ($value = get_sub_field('programm_txt')) { ?>
												<div class="programms-fb-bg__item-txt mb-3 mb-xl-0"><?= $value; ?></div>		
											<?php } ?>	
											<button class="btn btn-white" data-bs-toggle="modal" data-bs-target="#modal-form">Записаться на бесплатный урок</button>  
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>	  
				</div>
			</section>
		<?php } ?>
		<?php if (have_rows('review')) { ?>
			<section class="reviews" id="reviews">
				<div class="container">
					<?php if ($value = get_field('reviews_main_title')) { ?>
						<div class="review__main-title mb-5"><?= $value; ?></div>	
					<?php } ?>	
					<div class="row slider mb-5">
						<?php while (have_rows('review')) {
							the_row();
						?>
							<div class="col-4 slider__item">
								<div class="review-item-fb px-3">
									<div class="review-item-fb__top">
										<svg width="84" height="56" viewBox="0 0 84 56" fill="none">
											<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#quotes" x="0" y="0"></use>
										</svg>
										<?php if ($value = get_sub_field('review_txt')) { ?>
											<div class="review-item-fb__top-txt mt-4"><?= $value; ?></div> 		
										<?php } ?>
										<?php if ($value = get_sub_field('review_author')) { ?>
											<div class="review-item-fb__top-author my-4"><?= $value; ?></div> 		
										<?php } ?>	  	  
									</div>
									<div class="review-item-fb__bottom">
										<?php if ($value = get_sub_field('review_img')) { ?>
											<a href="<?= $value; ?>" class="fancybox">
												<img src="<?= kama_thumb_src('w=200&h=260&src=' . $value) ?>" alt="">
											</a> 	
										<?php } ?>	  
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class="slider-btns">
						<div class="slider-prev-btn">
							<svg width="40" height="40" viewBox="0 0 40 40" fill="none">
								<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#slider-btn-prev" x="0" y="0"></use>
							</svg>
						</div>
		  				<div class="slider-next-btn">
		  					<svg width="40" height="40" viewBox="0 0 40 40" fill="none">
								<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#slider-btn-next" x="0" y="0"></use>
							</svg>
		  				</div>
		  			</div>  
				</div>
			</section>
		<?php } ?>
		<?php if (get_the_content()) { ?>
			<section class="content-section py-5">
				<div class="container">
					<div><?php the_content(); ?></div>
				</div>
			</section>
		<?php } ?>
		<section class="contacts-section">
			<div class="container">
				<div class="contacts__wraper">
					<img class="contacts__bg d-none d-lg-block" src="<?= get_template_directory_uri()?>/img/cat.png" alt="">
					<div class="contacts__title mb-5">Контакты</div>
					<div class="request-box">
						<div class="request">
							<div class="request__header mb-5">
								<?php if ($value = get_field('logo', 15)) { ?>
									<img class="logo request__logo mb-2" src="<?= $value; ?>" alt="logo"> 		
								<?php } ?>	
								<div class="request__title">Школа скорочтения и развития интеллекта IQ007</div> 
							</div>
							<div class="request__main">
								<?= do_shortcode( '[contact-form-7 id="132cfda" title="Заявка на сайте"]' ); ?>
							</div>
							<div class="request__footer mt-5">
								<?php if (get_field('tel', 'option')) { ?>
									<a class="tel" href="tel:<?php echo preg_replace('/[^0-9\+]/', '', get_field('tel', 'option')); ?>" target="_blank">
										<svg width="25" height="24" viewBox="0 0 25 24" fill="none">
											<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#tel2" x="0" y="0"></use>
										</svg>
										<span class="contacts__txt"><?php the_field('tel', 'option'); ?></span>
									</a>
								<?php } ?> 
								<div class="request__socials mt-3">
									<?php if (get_field('vk', 'option')) { ?>
										<a class="social" href="<?php the_field('vk', 'option'); ?>" target="_blank">
											<svg class="vk" width="40" height="40" viewBox="0 0 40 40" fill="none">
												<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#vk" x="0" y="0"></use>
											</svg>
										</a>
									<?php } ?>
									<?php if (get_field('telegram', 'option')) { ?>
										<a class="social" href="<?php the_field('telegram', 'option'); ?>" target="_blank">
											<svg class="vk" width="40" height="40" viewBox="0 0 40 40" fill="none">
												<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#telegram" x="0" y="0"></use>
											</svg>
										</a>
									<?php } ?>
									<?php if (get_field('whatsapp', 'option')) { ?>
										<a class="social" href="<?php the_field('whatsapp', 'option'); ?>" target="_blank">
											<svg class="vk" width="40" height="40" viewBox="0 0 40 40" fill="none">
												<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#whatsapp" x="0" y="0"></use>
											</svg>
										</a>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
<?php get_footer(); ?>