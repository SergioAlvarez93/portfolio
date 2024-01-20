<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charser'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= get_home_url(); ?>/favicon.ico" type="image/x-icon">
	<?php wp_head(); ?>
</head>
<body>
	<div class="wrapper">
		<header class="sticky-top">
			<div class="header">
				<div class="container py-3">
					<div class="row justify-content-between lustify-content-lg-start">
						<div class="col-auto">
							<?php if ($value = get_field('logo', 15)) { ?>
								<a href="<?= get_home_url(); ?>"><img class="logo" src="<?= $value; ?>" alt=""></a>		
							<?php } ?>	  
						</div>
						<div class="col mob-menu">
							<div class="d-lg-block d-flex flex-column align-items-center">
								<div class="contacts row justify-content-lg-between justify-content-center mb-4 gy-lg-2 gy-4 order-2">
									<div class="col-lg-auto col-12 order-1 order-xxl-1">
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
									<div class="col-lg-auto col-12 order-3 order-xxl-2">
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
													<a class="social" href="<?= $value ?>" target="_blank">
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
								<nav class="navs order-1">
									<?php wp_nav_menu( array(
											'theme_location'  => 'menu',
											'menu_class'      => 'menu',
										));
									?>
								</nav>
							</div>
						</div>
						<div class="col-auto col-lg-none align-self-center">
							<div class="header__burger">
								<span></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<main class="main">
