<div class="modal" id="modal-form" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			<div class="modal-body">
				<div class="request">
					<div class="request__header mb-3 mb-sm-5">
						<?php if ($value = get_field('logo', 15)) { ?>
							<img class="logo request__logo mb-2" src="<?= $value; ?>" alt="logo"> 		
						<?php } ?>	
						<div class="request__title">Школа скорочтения и развития интеллекта IQ007</div> 
					</div>
					<div class="request__main">
						<?= do_shortcode( '[contact-form-7 id="132cfda" title="Заявка на сайте"]' ); ?>
					</div>
					<div class="request__footer mt-3 mt-sm-5">
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
</div>
<div class="modal" id="modal-form-success" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			<div class="modal-body">
				<div class="modal-body__title mb-4">Форма отправлена</div>
				<div class="modal-body__txt">Спасибо! 

				Скоро мы вам перезвоним 
				и проконсультируем</div>
			</div>
		</div>
	</div>
</div>