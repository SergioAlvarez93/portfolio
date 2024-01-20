	</main>
	<?php get_template_part( 'inc/modal', 'screen' ); ?>
	<footer id="footer">
		<div class="footer">
			<div class="container">
				<div class="row gy-3">
					<div class="col-md-auto">
						<?php if ($value = get_field('logo', 15)) { ?>
							<a href="<?= get_home_url(); ?>"><img class="logo" src="<?= $value; ?>" alt=""></a>		
						<?php } ?>	
					</div>
					<div class="col">
						<div class="contacts footer__contacts row justify-content-between mb-5 gy-lg-2 gy-4">
							<div class="col-lg-auto col-12 order-lg-1 order-xxl-1">
								<div class="mb-2">
									<?php if ($value = get_field('adress-bal', 'option')) { ?>
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
											<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#map" x="0" y="0"></use>
										</svg>
										<span class="contacts__txt"><?= $value; ?></span>
									<?php } ?>
								</div>
								<div>
									<?php if ($value = get_field('adress_zav', 'option')) { ?>
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
											<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#map" x="0" y="0"></use>
										</svg>
										<span class="contacts__txt"><?= $value; ?></span>
									<?php } ?>
								</div>
							</div>
							<div class="col-lg-auto col-12 order-lg-3 order-xxl-2">
								<div class="mb-2">
									<?php if ($value = get_field('tel', 'option')) { ?>
										<a class="tel" href="tel:<?php echo preg_replace('/[^0-9\+]/', '', $value); ?>" target="_blank">
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
												<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#tel" x="0" y="0"></use>	
											</svg>
											<span class="contacts__txt"><?= $value; ?></span>
										</a>
									<?php } ?>
								</div>
								<div>
									<?php if ($value = get_field('work_day', 'option')) { ?>
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
											<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#clock" x="0" y="0"></use>
										</svg>
										<span class="contacts__txt"><?= $value; ?></span>
									<?php } ?>
								</div>
							</div>
							<div class="col-auto order-3 order-lg-2 order-xxl-3">
								<div class="row justify-content-between">
									<div class="col-auto">
										<?php if ($value = get_field('vk', 'option')) { ?>
											<a class="social" href="<?= $value; ?>" target="_blank">
												<svg class="vk" width="40" height="40" viewBox="0 0 40 40" fill="none">
													<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#vk" x="0" y="0"></use>
												</svg>
											</a>
										<?php } ?>
									</div>
									<div class="col-auto">
										<?php if ($value = get_field('telegram', 'option')) { ?>
											<a class="social" href="<?= $value; ?>" target="_blank">
												<svg class="vk" width="40" height="40" viewBox="0 0 40 40" fill="none">
													<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#telegram" x="0" y="0"></use>
												</svg>
											</a>
										<?php } ?>
									</div>
									<div class="col-auto">
										<?php if ($value = get_field('whatsapp', 'option')) { ?>
											<a class="social" href="<?= $value; ?>" target="_blank">
												<svg class="vk" width="40" height="40" viewBox="0 0 40 40" fill="none">
													<use href="<?= get_template_directory_uri()?>/sprites/sprites.svg#whatsapp" x="0" y="0"></use>
												</svg>
											</a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<ul class="footer__privacy">
							<li>
								<a href="<?= get_home_url(); ?>/privacy-policy">Политика конфиденциальности</a>
							</li>
							<li>
								<a href="#">Правила оказания образовательных услуг</a>
							</li>
							<li>&copy; <?= date('Y') ?> Все права защищены</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</footer>
</div>
</body>
<?php wp_footer(); ?>
</html>