<?php
defined('ABSPATH') || exit;

global $product;

if (!is_a($product, 'WC_Product') || !$product->is_visible()) {
	error_log('Product not available or not visible for ID: ' . get_the_ID());
	return;
}

$showcase_context = apply_filters('is_showcase_list_context', false);

if ($showcase_context) { ?>
	<li <?php wc_product_class('showcase-list__item', $product); ?>>
		<article class="cardFlip">
			<div class="cardFlip-content">
				<div class="cardFlip-inside cardFlip-inside__front" style="<?php if ($value = get_field('bg_color_card')) { ?> background-color:<?= esc_attr($value); ?>; <?php } ?>">
					<?php do_action('custom_before_shop_loop_item_title'); ?>
					<div class="arrow-flip-outer">
						<div class="arrow-flip__color" style="<?php if ($value = get_field('bg_color_card')) { ?> background-color:<?= esc_attr($value); ?>; <?php } ?>"></div>
						<img class="arrow-flip__img" src="<?= esc_url(get_template_directory_uri() . '/img/showcase/arrow_flip.svg') ?>" alt="Стрелка разворота (Arrow flip)">
					</div>
				</div>
				<div class="cardFlip-inside cardFlip-inside__back">
					<div class="cardFlip-back-content" style="<?php if ($value = get_field('bg_color_card')) { ?> background-color:<?= esc_attr($value); ?>; <?php } ?>">
						<div class="back-side-card">
							<div class="back-side-card__meta"><?php do_action('custom_show__product_meta'); ?></div>
							<p class="back-side-card__excerpt"><?php woocommerce_template_single_excerpt(); ?></p>
							<?php if (have_rows('application')) { ?>
								<ul class="back-side-card-list">
									<?php while (have_rows('application')) {
										the_row(); ?>
										<li class="back-side-card-list__item">
											<div class="back-side-card-list__list_circle"></div>
											<div class="back-side-card-list__list_content"><?= esc_html(get_sub_field('li')); ?></div>
										</li>
									<?php } ?>
								</ul>
							<?php } ?>
							<a href="#" class="back-side-card__btn modal-trigger" data-product-id="<?php echo get_the_ID(); ?>" style="<?php if ($value = get_field('bg_color_card')) { ?> color:<?= esc_attr($value); ?>; <?php } ?>">Заказать</a>
						</div>
					</div>
					<div class="arrow-flip">
						<div class="arrow-flip__color" style="<?php if ($value = get_field('bg_color_card')) { ?> background-color:<?= esc_attr($value); ?>; <?php } ?>"></div>
						<img class="arrow-flip__img" src="<?= esc_url(get_template_directory_uri() . '/img/showcase/arrow_flip.svg') ?>" alt="Стрелка разворота (Arrow flip)">
					</div>
				</div>
			</div>
		</article>
	</li>
<?php } else { ?>
	<li <?php wc_product_class('market-list__item modal-trigger', $product); ?> data-product-id="<?php echo get_the_ID(); ?>">
		<?php
		do_action('custom_before_shop_loop_item_title_market');
		if (!$product) {
			error_log('Product object missing in else branch for ID: ' . get_the_ID());
			echo '<p>Ошибка: товар не найден</p>';
		}
		?>
		<?php /* <a href="#" class="market-list__item-btn">Подробнее</a> */ ?>
	</li>
<?php } ?>